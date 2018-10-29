class RconConnectionEvents {
	static bindEvents(server)
	{
		_servers[server.id].rcon.on('open', function() {
			Util.log('Connection to Rust server [' + server.id + '] ' + server.host + ':' + server.port + ' established.');

			_servers[server.id].intervals.push(setInterval(function(){
				_servers[server.id].player.load();
			}, Util.minToMSeconds(5)));

			_servers[server.id].intervals.push(setInterval(function(){
				_servers[server.id].player.updateSteamFeed();
			}, Util.minToMSeconds(60)));

			_servers[server.id].scheduler.load();

			// ***********************************
			// **** Update Server Max-Players ****
			// ***********************************

			_servers[server.id].rcon.request('status', function(data){
				data = data.Message;

				let matches = data.match(/players\s*: \d+ \((\d+) max\)/);

				if(matches.length == 2)
				{
					db.query('SELECT max_players FROM servers WHERE id = ?', [server.id], function(error, results, fields){
						if(results.length > 0 && results[0].max_players != matches[1])
							db.query('UPDATE servers SET max_players = ? WHERE id = ?', [matches[1], server.id], function (error, results, fields){
							});
					});
				}
			});

/*
			setInterval(function(){
				_servers[server.id].rcon.command('say Note that this is a limited PVP server. Killing is allowed only when raiding, counter-raiding, in radtowns (anything with a label on the map), and under airdrops (until the drop is looted). For the full list of rules visit: startingtorust.com');
			}, Util.minToMSeconds(15));
*/

/*
			setInterval(function(){
				_servers[server.id].rcon.command('say Next Full Wipe: March 1st');
			}, Util.minToMSeconds(30));
*/

/*
			setInterval(function(){
				_servers[server.id].rcon.command('say Want shit to change next wipe? Want shit to stay the same? Let us know now at: startingtorust.com/survey');
			}, Util.minToMSeconds(10));
*/
		});

		_servers[server.id].rcon.on('message', function(msg) {
			let msg_raw = msg;
			msg = JSON.parse(msg);

			if(msg.Message == null || msg.Message == '')
				return;

			let event = RconRustEvents.parseMessage(msg.Message, msg.Type);

			if(event.type !== null)
				StreamerServer_i.wsClientBroadcast(server.id, event);

			db.query('INSERT INTO stream (server_id, payload, event) VALUES (?, ?, ?)', [server.id, utf8.encode(msg_raw), ((event.type == null) ? null : JSON.stringify(event))], function (error, results, fields){
				if(error)
				{
					Util.log('Error inserting into stream table.' + "\n");
					Util.log([server.id, msg_raw, ((event.type == null) ? null : JSON.stringify(event))]);
					Util.log("\n\n");
				}
			});

			if(event.type != null)
			{
				switch(event.type){
					case 'player.chat':
						db.query('INSERT INTO chat (server_id, steam_id, username, message) VALUES (?, ?, ?, ?)', [server.id, event.user.steam_id, utf8.encode(event.user.username), event.message], function (error, results, fields){
							if (error) Util.log('Error inserting record into chat table.');
						});
					break;
					case 'player.connected':
						_servers[server.id].player.handleConnect(event.user);

						//_servers[server.id].rcon.command('say ' + event.user.username + ' has entered the game.');
					break;
					case 'player.disconnected':
						_servers[server.id].player.handleDisconnect(event.user);
					break;
					case 'player.spawned':
						_servers[server.id].player.handleRespawn(event.user);
					break;
					case 'player.killed':
						_servers[server.id].player.getLastDeath(event.victim.steam_id, function(record){
							db.query('INSERT INTO kills (server_id, victim_steam_id, victim_username, killer_steam_id, killer_username) VALUES (?, ?, ?, ?, ?)', [server.id, event.victim.steam_id, utf8.encode(event.victim.username), event.killer.steam_id, utf8.encode(event.killer.username)], function (error, results, fields){
								if (error) Util.log('Error inserting record into kills table.');
							});

							let message = event.victim.username + ' was killed by ' + event.killer.username;
							let live_string = null;

							if(record !== null)
							{
								if(record.live_seconds > 86400)
									live_string = Math.floor(record.live_seconds / 86400) + ' days, ' + Math.floor((record.live_seconds % 86400) / 3600) + ' hours, ' + Math.floor(((record.live_seconds % 86400) % 3600) / 60) + ' minutes, ' + Math.floor(((record.live_seconds % 86400) % 3600) % 60) + ' seconds';
								else if(record.live_seconds > 3600)
									live_string = Math.floor(record.live_seconds / 3600) + ' hours, ' + Math.floor((record.live_seconds % 3600) / 60) + ' minutes, ' + Math.floor(((record.live_seconds % 86400) % 3600) % 60) + ' seconds';
								else if(record.live_seconds > 60)
									live_string = Math.floor(record.live_seconds / 60) + ' minutes, ' + Math.floor(record.live_seconds % 60) + ' seconds';
								else
									live_string = record.live_seconds + ' seconds';

								message = message + '. They were alive for ' + live_string + '.';
							}

							//_servers[server.id].rcon.command('say ' + message);
						});
					break;
/*
					case 'server.event':
						if(event.name == 'cargo_plane')
							_servers[server.id].rcon.command('say Supply drop incoming.');
						else if(event.name == 'patrolhelicopter')
							_servers[server.id].rcon.command('say Helicopter incoming.');
						else if(event.name == 'xmasrefill')
							_servers[server.id].rcon.command('say Now I have a machine gun, HO HO HO!');
					break;
*/
				}

				_servers[server.id].scheduler.emit('rustEvent', event);
			}
		});

		_servers[server.id].rcon.on('error', function(msg) {
			Util.log('Connection to Rust server [' + server.id + '] ' + server.host + ':' + server.port + ' FAILED. Trying again in 15 seconds.');

			_servers[server.id].rcon.disconnect();
			RconConnectionEvents.cleanupIntervals(server.id);

			setTimeout(function(){
				_servers[server.id].rcon.connect();
				RconConnectionEvents.bindEvents(server);
			}, Util.toMSeconds(15));
		});

		_servers[server.id].rcon.on('close', function() {
			Util.log('Connection to Rust server [' + server.id + '] ' + server.host + ':' + server.port + ' closed.');
		});
	}

	static cleanupIntervals(server_id)
	{
		_servers[server_id].intervals.forEach(function(row){
			clearInterval(row);
		});

		_servers[server_id].intervals = [];

		_servers[server_id].scheduler.unload();
	}
}

module.exports = RconConnectionEvents;
