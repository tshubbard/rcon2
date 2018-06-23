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

					switch(message.type)
					{
						case 'stream.connect':
							if(this._clients[message.server_id] == null)
								this._clients[message.server_id] = [];

							this._clients[message.server_id].push({'id': id, 'socket': ws});
						break;
						case 'stream.disconnect':
							let index = this._clients[message.server_id].findIndex(function(item){return (item != null && item.id == id);});

							this._clients[message.server_id][index].socket.close();
							delete this._clients[message.server_id][index];
						break;
						case 'stream.command':
							_servers[message.server_id].rcon.command(message.command);
						break;
						case 'testserver':
							let response = {
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
						case 'event.update':
							_servers[message.server_id].scheduler.handleEventChange(message.id, message.event_type);
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
