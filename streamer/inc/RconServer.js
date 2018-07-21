class RconServer {
	static load(id = null) {
		let sql = 'SELECT id, host, password, port, timezone FROM servers WHERE is_active = 1';

		if(id !== null)
			sql += (' AND id = ' + id);

		db.query(sql, function(error, results, fields){
			if(error)
			{
				console.log('Error retrieving list of servers.');
				process.exit();
			}

			results.forEach(function(server){
				if(_servers[server.id] != null)
				{
					_servers[server.id].rcon.disconnect();

					RconConnectionEvents.cleanupIntervals(server.id);
					RconConnectionEvents.unbindEvents(server.id);

					delete _servers[server.id];
				}

				_servers[server.id] = {
					'intervals': [],
					'timezone': server.timezone
				};

				_servers[server.id].rcon = new RconWebSocket((server.host + ':' + server.port), server.password);
				_servers[server.id].player = new Player(server.id);
				_servers[server.id].scheduler = new Scheduler(server.id);

				_servers[server.id].rcon.connect();
				RconConnectionEvents.bindEvents(server);
			});
		});
	}
}

module.exports = RconServer;
