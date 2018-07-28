class StreamerServer {
	constructor()
	{
		this._clients = {};

		this.wss = new WebSocket.Server({ 'host': '127.0.0.1', 'port': 6869 });

		this.wss.on('connection', function connection(ws) {
			let id = Date.now();

			ws.on('message', function incoming(message) {
				message = JSON.parse(message);

				if(message.type != null)
				{
					if(message.type != 'testserver' && message.server_id == null)
					{
						ws.close();

						return false;
					}

					let response = null;

					switch(message.type)
					{
						case 'stream.connect':
							if(typeof(message.server_id) != 'object')
								message.server_id = [message.server_id];

							message.server_id.forEach(function(server_id){
								if(this._clients[server_id] == null)
									this._clients[server_id] = [];

								this._clients[server_id].push({'id': id, 'socket': ws});
							}.bind(this));
						break;
						case 'stream.disconnect':
							if(typeof(message.server_id) != 'object')
								message.server_id = [message.server_id];

							message.server_id.forEach(function(server_id){
								let index = this._clients[server_id].findIndex(function(item){return (item != null && item.id == id);});

								this._clients[server_id][index].socket.close();
								delete this._clients[server_id][index];
							}.bind(this));
						break;
						case 'stream.command':
							_servers[message.server_id].rcon.command(message.command);
						break;
						case 'testserver':
							response = {
								'type': 'testserver.result',
								'host': message.host,
								'port': message.port,
								'result': null,
								'code': null
							};

							let rconTest = new WebSocket('ws://' + message.host + ':' + message.port + '/' + message.password, {'handshakeTimeout': 3000});

							rconTest.on('error', function(error){
								response.result = 'failure';

								if(error.code !== null && error.code == 'ECONNREFUSED')
									response.code = 'connection.refused';
								else if(String(error).indexOf('opening handshake has timed out') !== -1)
									response.code = 'connection.timedout';
								else if(String(error).indexOf('unexpected server response (501)') !== -1)
									response.code = 'password';
								else
									response.code = 'unknown';

								ws.send(JSON.stringify(response));

								rconTest = null;
							});

							rconTest.on('open', function(){
								response.result = 'success';

								ws.send(JSON.stringify(response));

								rconTest.close();

								rconTest = null;
							});
						break;
						case 'dumpscheduler':
							response = {
								'type': 'dumpscheduler.result',
								'result': {
									'timers': _servers[message.server_id].scheduler.timers,
									'triggers': _servers[message.server_id].scheduler.triggers
								}
							};

							ws.send(JSON.stringify(response));
						break;
					}
				}
			}.bind(this));
		}.bind(this));
	}

	wsClientBroadcast(server_id, event)
	{
		if(this._clients[server_id] != null)
			this._clients[server_id].forEach(function(client) {
				if(client.socket !== null)
					client.socket.send(JSON.stringify(event));
			});
	}
}

module.exports = StreamerServer;
