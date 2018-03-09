class RconWebSocket {

	constructor(addr, pass) {
		this.ConnectionStatus = {
			'CONNECTING': 0,
			'OPEN': 1,
			'CLOSING': 2,
			'CLOSED': 3
		};

		this.Address = addr;
		this.Password = pass;

		this.Callbacks = {};
		this.LastIndex = 1001;
	}

	on(event, callback) {
		if(this.Socket == null)
			return null;

		this.Socket.on(event, callback);
	}

	connect() {
		this.Socket = new WebSocket(('ws://' + this.Address + '/' + this.Password), {'handshakeTimeout': 3000});

		this.Socket.on('message', function(data){
			data = JSON.parse(data);

			//
			// This is a targetted message, it has an identifier
			// So feed it back to the right callback.
			//

			if (data.Identifier > 1000) {
				var cb = this.Callbacks[data.Identifier];
				if (cb != null) {
					cb.callback(data);
				}
				this.Callbacks[data.Identifier] = null;

				return;
			}
		}.bind(this));
	}

	disconnect() {
		if (this.Socket) {
			this.Socket.close();
			this.Socket = null;
		}

		this.Callbacks = {};
	}

	//
	// Send a command without a bound callback.
	//
	command(msg, identifier) {
		if (this.Socket === null)
			return;

		if (!this.isConnected())
			return;

		if (identifier === null)
			identifier = -1;

		if(typeof(msg) !== 'object')
			msg = [msg];

		msg.forEach(function(item){
			this.Socket.send(JSON.stringify({
				'Identifier': identifier,
				'Message': item,
				'Name': 'WebRcon'
			}));
		}.bind(this));
	}

	//
	// Make a request, call this function when it returns.
	//
	request(msg, callback) {
		this.LastIndex++;
		this.Callbacks[this.LastIndex] = {
			'callback': callback
		};
		this.command(msg, this.LastIndex);
	}

	//
	// Returns true if websocket is connected.
	//
	isConnected() {
		if (this.Socket == null)
			return false;

		return this.Socket.readyState === this.ConnectionStatus.OPEN;
	}
}

module.exports = RconWebSocket;
