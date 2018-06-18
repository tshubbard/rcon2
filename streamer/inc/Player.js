class Player {
	constructor(server_id) {
		this.server_id = server_id;
		this.cache = {};
	}

	load() {
		_servers[this.server_id].rcon.request('playerlist', function(data){
			this.cache = {};

			data = JSON.parse(data.Message);

			data.forEach(function(row){
				if(row.ConnectedSeconds > Util.hourToSeconds(8))
					_servers[this.server_id].rcon.command('kick ' + row.SteamID + ' "Connected for more than 8 hours."');
				else
					this.cache[row.SteamID] = {
						'steam_id': row.SteamID,
						'short_id': null,
						'username': row.DisplayName,
						'ip_address': row.Address.substr(0, row.Address.lastIndexOf(':'))
					};
			}.bind(this));

			db.query('DELETE FROM players_current WHERE server_id=?', [this.server_id], function (error, results, fields){
				if (error) console.log('Error deleting players_current records.');

				Object.keys(this.cache).forEach(function(key){
					db.query('INSERT INTO players_current (server_id, steam_id) VALUES (?, ?)', [this.server_id, key], function (error, results, fields){
						if (error) console.log('Error inserting record into players_current table.');
					});
				}.bind(this));
			}.bind(this));

			Object.keys(this.cache).forEach(function(key){
				this.syncEntryToDatabase(key);
			}.bind(this));

		}.bind(this));
	}

	getLastDeath(steam_id, callback) {
		db.query('SELECT TIMESTAMPDIFF(SECOND, created_at, NOW()) AS live_seconds FROM kills WHERE server_id=? AND victim_steam_id=? ORDER BY id DESC LIMIT 1', [this.server_id, steam_id], function (error, results, fields){
			if (error) console.log('Error querying kills table for last death.');

			let row = null;

			if(results.length == 1)
				row = results[0];

			callback(row);
		});
	}

	handleConnect(obj) {
		this.cache[obj.steam_id] = obj;

		this.syncEntryToDatabase(obj.steam_id, true);
		this.addToCurrent(obj.steam_id);
	}

	handleDisconnect(obj) {
		delete this.cache[obj.steam_id];
		this.deleteFromCurrent(obj.steam_id);
	}

	handleRespawn(obj) {
		if(this.cache[obj.steam_id] == null)
			return null;

		this.cache[obj.steam_id].username = obj.username;
		this.cache[obj.steam_id].short_id = obj.short_id;

		this.syncEntryToDatabase(obj.steam_id);
	}

	syncEntryToDatabase(steam_id, force_update = false) {
		db.query('SELECT * FROM players WHERE server_id=? AND steam_id=?', [this.server_id, steam_id], function (error, results, fields){
			if (error) console.log('Error querying players table for user record.');

			let row = results[0];

			if(results.length == 0)
				this.addPlayerToDatabase(this.cache[steam_id]);
			else
			{
				if(row.short_id != null && this.cache[steam_id].short_id == null)
					this.cache[steam_id].short_id = row.short_id;

				if(row.username != this.cache[steam_id].username || row.short_id != this.cache[steam_id].short_id || force_update)
					this.updatePlayerInDatabase(this.cache[steam_id]);
			}
		}.bind(this));
	}

	addPlayerToDatabase(obj) {
		db.query('INSERT INTO players (server_id, steam_id, username) VALUES (?, ?, ?)', [this.server_id, obj.steam_id, utf8.encode(obj.username)], function (error, results, fields){
			if (error) console.log('Error inserting record into players table.');
		});
	}

	updatePlayerInDatabase(obj) {
		let sql = null;
		let args = null;

		if(obj.short_id != null)
		{
			sql = 'UPDATE players SET last_login=NOW(), username=?, short_id=? WHERE server_id=? AND steam_id=?';
			args = [utf8.encode(obj.username), obj.short_id, this.server_id, obj.steam_id];
		}
		else
		{
			sql = 'UPDATE players SET last_login=NOW(), username=? WHERE server_id=? AND steam_id=?';
			args = [utf8.encode(obj.username), this.server_id, obj.steam_id];
		}

		db.query(sql, args, function (error, results, fields){
			if (error) console.log('Error updating players table record.');
		});
	}

	addToCurrent(steam_id) {
		db.query('INSERT INTO players_current (server_id, steam_id) VALUES (?, ?)', [this.server_id, steam_id], function (error, results, fields){
			if (error) console.log('Error inserting record into players_current table.');
		});
	}

	deleteFromCurrent(steam_id) {
		db.query('DELETE FROM players_current WHERE server_id=? AND steam_id=?', [this.server_id, steam_id], function (error, results, fields){
			if (error) console.log('Error deleting record into players_current table.');
		});
	}

	updateSteamFeed() {
		const http = require('http');

		db.query('SELECT steam_id FROM players WHERE server_id=? AND (last_steam_sync is null OR TIMESTAMPDIFF(HOUR, last_steam_sync, NOW()) >= 24)', [this.server_id], function (error, results, fields){
			if (error) console.log('Error querying players table for records requiring steam profile updates.');;

			let ids = [];
			let requestids = null;

			results.forEach(function(row){
				ids.push(row.steam_id);
			});

			while(ids.length != 0)
			{
				requestids = ids.splice(0, 10);

				// ****************************
				// **** Load Steam Profile ****
				// ****************************

				http.get(('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=' + _config.api.valve.key + '&steamids=' + requestids.join(',')), function(res){
					const { statusCode } = res;

					if(statusCode !== 200)
					{
						res.resume();
						return;
					}

					res.setEncoding('utf8');

					let rawData = '';

					res.on('data', function(chunk){
						rawData += chunk;
					});

					res.on('end', function(){
						try {
							const parsedData = JSON.parse(rawData);

							parsedData.response.players.forEach(function(row){
								db.query('UPDATE players SET last_steam_sync=NOW(), steam_profile=?, steam_avatar_small=?, steam_avatar_medium=?, steam_avatar_large=? WHERE server_id=? AND steam_id=?', [row.profileurl, row.avatar, row.avatarmedium, row.avatarfull, this.server_id, row.steamid], function (error, results, fields){
									if (error) console.log('Error updating record in players table with latest steam profile data.');;
								});
							}.bind(this));
						} catch (e) {
							throw e.message;
						}
					}.bind(this));
				}.bind(this)).on('error', function(error){
					throw error.message;
				});

				// *************************
				// **** Load Steam Bans ****
				// *************************

				http.get(('http://api.steampowered.com/ISteamUser/GetPlayerBans/v1/?key=' + _config.api.valve.key + '&steamids=' + requestids.join(',')), function(res){
					const { statusCode } = res;

					if(statusCode !== 200)
					{
						res.resume();
						return;
					}

					res.setEncoding('utf8');

					let rawData = '';

					res.on('data', function(chunk){
						rawData += chunk;
					});

					res.on('end', function(){
						try {
							const parsedData = JSON.parse(rawData);

							parsedData.players.forEach(function(row){
								db.query('UPDATE players SET steam_vacban_count=?, steam_gameban_count=?, steam_days_since_last_ban=? WHERE server_id=? AND steam_id=?', [row.NumberOfVACBans, row.NumberOfGameBans, row.DaysSinceLastBan, this.server_id, row.SteamId], function (error, results, fields){
									if (error) console.log('Error updating record in players table with latest steam ban data.');;
								});
							}.bind(this));
						} catch (e) {
							throw e.message;
						}
					}.bind(this));
				}.bind(this)).on('error', function(error){
					throw error.message;
				});
			}
		}.bind(this));
	}
}

module.exports = Player;
