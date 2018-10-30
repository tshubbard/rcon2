class Scheduler extends EventEmitter {
	constructor(server_id)
	{
		super();

		this.initial_load = false;

		this.server_id = server_id;
		this.timers = {};
		this.triggers = {
			'player.chat': {},
			'player.killed': {},
			'player.spawned': {},
			'player.connected': {},
			'player.disconnected': {},
			'cargo_plane': {},
			'patrolhelicopter': {},
			'xmasrefill': {},
			'ch47scientists.entity': {},
			'cargoshiptest': {}
		};
	}

	load(id = null)
	{
		let sql;
		let parameters;

		if(id == null)
		{
			sql = 'SELECT id, event_type, commands, command_trigger, command_timer, is_indefinite, start_date, end_date, lastrun_date FROM server_events WHERE server_id = ? AND is_active = 1 AND (is_indefinite = 1 OR ((start_date < NOW() OR TIMESTAMPDIFF(DAY, now(), start_date) <= 20) AND end_date > NOW()))';
			parameters = [this.server_id];
		}
		else
		{
			sql = 'SELECT id, event_type, commands, command_trigger, command_timer, is_indefinite, start_date, end_date, lastrun_date FROM server_events WHERE server_id = ? AND id = ? AND is_active = 1 AND (is_indefinite = 1 OR ((start_date < NOW() OR TIMESTAMPDIFF(DAY, now(), start_date) <= 20) AND end_date > NOW()))';
			parameters = [this.server_id, id];
		}

		db.query(sql, parameters, function(error, results, fields){
			if(error) Util.log('Error retrieving server event list.');

			let now = DateTime.fromObject({'zone': _servers[this.server_id].timezone});

			results.forEach(function(row){
				row.commands = JSON.parse(row.commands);

				let start_date = (row.is_indefinite == 0 && row.start_date !== null) ? DateTime.fromSQL(row.start_date, {'zone': _servers[this.server_id].timezone}) : null;
				let end_date = (row.is_indefinite == 0 && row.end_date !== null) ? DateTime.fromSQL(row.end_date, {'zone': _servers[this.server_id].timezone}) : null;
				let delay = null;

				switch(row.event_type)
				{
					case('timer'):
						let lastrun_date = (row.lastrun_date !== null) ? DateTime.fromSQL(row.lastrun_date, {'zone': _servers[this.server_id].timezone}) : null;
						let nextrun_date = (lastrun_date !== null) ? lastrun_date.plus({ 'minutes': row.command_timer }) : null;

						if(start_date !== null && now < start_date)
							delay = start_date.diff(now).toObject().milliseconds;
						else if(lastrun_date !== null && nextrun_date > now)
							delay = nextrun_date.diff(now).toObject().milliseconds;
						else
							delay = 0;

						setTimeout(function(){
							_servers[this.server_id].rcon.command(row.commands);
							_servers[this.server_id].scheduler.updateLastRun(row.id);

							this.timers[row.id] = setInterval(function(){
								_servers[this.server_id].rcon.command(row.commands);
								_servers[this.server_id].scheduler.updateLastRun(row.id);
							}.bind(this), Util.minToMSeconds(row.command_timer));
						}.bind(this), delay);

						if(end_date !== null && end_date.diff(now, 'days').toObject().days <= 20)
						{
							setTimeout(function(){
								this.unloadTimer(row.id);
							}.bind(this), end_date.diff(now).toObject().milliseconds);
						}
					break;
					case('player.chat'):
						if(start_date !== null && now < start_date)
							delay = start_date.diff(now).toObject().milliseconds;
						else
							delay = 0;

						setTimeout(function(){
							this.triggers['player.chat'][row.id] = {
								'command': row.commands,
								'trigger': row.command_trigger
							};
						}.bind(this), delay);

						if(end_date !== null && end_date.diff(now, 'days').toObject().days <= 20)
						{
							setTimeout(function(){
								this.unloadTrigger('player.chat', row.id);
							}.bind(this), end_date.diff(now).toObject().milliseconds);
						}
					break;
					case('player.killed'):
					case('player.spawned'):
					case('player.connected'):
					case('player.disconnected'):
					case('cargo_plane'):
					case('patrolhelicopter'):
					case('xmasrefill'):
					case('ch47scientists.entity'):
					case('cargoshiptest'):
						if(start_date !== null && now < start_date)
							delay = start_date.diff(now).toObject().milliseconds;
						else
							delay = 0;

						setTimeout(function(){
							this.triggers[row.event_type][row.id] = {
								'command': row.commands
							};
						}.bind(this), delay);

						if(end_date !== null && end_date.diff(now, 'days').toObject().days <= 20)
						{
							setTimeout(function(){
								this.unloadTrigger(row.event_type, row.id);
							}.bind(this), end_date.diff(now).toObject().milliseconds);
						}
					break;
				}
			}.bind(this));

			if(!this.initial_load)
			{
				this.on('rustEvent', this.handleEvent);
				this.initial_load = true;
			}

		}.bind(this));
	}

	unload()
	{
		Object.keys(this.timers).forEach(function(key){
			this.unloadTimer(key);
		}.bind(this));

		Object.keys(this.triggers).forEach(function(key){
			this.triggers[key] = {};
		}.bind(this));
	}

	unloadTimer(id)
	{
		clearInterval(this.timers[id]);
		delete this.timers[id];
	}

	unloadTrigger(type, id)
	{
		delete this.triggers[type][id];
	}

	updateLastRun(id)
	{
		db.query('UPDATE server_events SET lastrun_date = NOW() WHERE id = ? AND server_id = ?', [id, this.server_id], function(error, results, fields){
			if(error) Util.log('Error updating event last run date.');
		});
	}

	handleEventChange(id, type)
	{
		if(type == 'timer')
			this.unloadTimer(id);
		else
			this.unloadTrigger(type, id);

		this.load(id);
	}

	handleEvent(event)
	{
		switch(event.type)
		{
			case 'player.chat':
				Object.keys(this.triggers['player.chat']).forEach(function(key){
					if(event.message == this.triggers['player.chat'][key].trigger)
						this.triggers['player.chat'][key].command.forEach(function(item){
							_servers[this.server_id].rcon.command(this.handleVariableSubstitution(event, item.command));
						}.bind(this));
				}.bind(this));
			break;
			case('player.killed'):
			case('player.spawned'):
			case('player.connected'):
			case('player.disconnected'):
			case('cargo_plane'):
			case('patrolhelicopter'):
			case('xmasrefill'):
			case('ch47scientists.entity'):
			case('cargoshiptest'):
				Object.keys(this.triggers[event.type]).forEach(function(key){
					this.triggers[event.type][key].command.forEach(function(item){
						_servers[this.server_id].rcon.command(this.handleVariableSubstitution(event, item.command));
					}.bind(this));
				}.bind(this));
			break;
		}
	}

	handleVariableSubstitution(event, command)
	{
		let subs = {
			'player.chat': ['user.username', 'user.steam_id'],
			'player.killed': ['victim.username', 'victim.short_id', 'victim.steam_id', 'killer.username', 'killer.short_id', 'killer.steam_id'],
			'player.spawned': ['user.username', 'user.short_id', 'user.steam_id'],
			'player.connected': ['host', 'user.username', 'user.steam_id'],
			'player.disconnected': ['host', 'user.username', 'user.steam_id']
		};

		if(subs[event.type] != null)
			subs[event.type].forEach(function(item){
				command = command.replace('${' + item + '}', eval('event.' + item));
			});

		return command;
	}
}

module.exports = Scheduler;
