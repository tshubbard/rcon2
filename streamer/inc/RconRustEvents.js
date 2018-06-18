class RconRustEvents {

	static parseMessage(msg, type) {
		let event = {'type': null};

		msg = String(msg).trim();

/*
		if(msg.startsWith('[CHAT]'))
		{
			msg = msg.slice(7);

			let splitpoint = (msg.indexOf(']')+1);
			let user = msg.slice(0, splitpoint);
			let message = msg.slice((splitpoint+3));

			event.type = 'player.chat';
			event.user = this.parseUserString(user);
			event.message = message;
		}
*/
		if(msg.startsWith('[CHAT]') || (type != 'Chat' && msg.startsWith('{')))
		{
			event.type = null;
		}
		else if(type == 'Chat')
		{
			// Facepunch JSON Encoding Bug Temp-Fix
			msg = msg.replace(/("UserId": )(\d+)/, '$1 "$2"');

			msg = JSON.parse(msg);

			event.type = 'player.chat';
			event.user = {'username': msg.Username, 'steam_id': msg.UserId};
			event.message = msg.Message;
		}
		else if(msg.startsWith('[event]'))
		{
			let name = msg.slice((msg.lastIndexOf('/')+1), msg.lastIndexOf('.'));

			event.type = name;
		}
		else if(msg.indexOf(' was killed by ') != -1)
		{
			msg = msg.split(' was killed by ');

			event.type = 'player.killed';
			event.victim = this.parseUserString(msg[0]);
			event.killer = this.parseUserString(msg[1]);
		}
		else if(msg.indexOf(' has entered the game') != -1)
		{
			msg = msg.split(' has entered the game');

			event.type = 'player.spawned';
			event.user = this.parseUserString(msg[0]);
		}
		else if(msg.search(/ joined \[.*\]$/g) != -1)
		{
			event.type = 'player.connected';
			event.user = {};

			msg = msg.replace(/ joined \[.*\]$/g, '');

			event.host = msg.substr(0, msg.indexOf('/'))
			event.host = event.host.substr(0, event.host.indexOf(':'));

			msg = msg.substr(msg.indexOf('/')+1);

			event.user.steam_id = msg.substr(0, msg.indexOf('/'));

			msg = msg.substr(msg.indexOf('/')+1);

			event.user.username = msg;
		}
		else if(msg.search(/ disconnecting: (disconnect|closing)$/g) != -1)
		{
			event.type = 'player.disconnected';
			event.user = {};

			msg = msg.replace(/ disconnecting: (disconnect|closing)$/g, '');

			event.host = msg.substr(0, msg.indexOf('/'))
			event.host = event.host.substr(0, event.host.indexOf(':'));

			msg = msg.substr(msg.indexOf('/')+1);

			event.user.steam_id = msg.substr(0, msg.indexOf('/'));

			msg = msg.substr(msg.indexOf('/')+1);

			event.user.username = msg;
		}
		else
		{
			event.type = 'unknown';
			event.message = msg;
		}

		return event;
	}

	static parseUserString(user) {
		let userobj = {
			'username': null,
			'short_id': null,
			'steam_id': null
		};

		let splitpoint = user.lastIndexOf('[');

		if(splitpoint != -1)
		{
			let ids = user.slice((splitpoint+1), -1).split('/');

			userobj.username = user.slice(0, splitpoint);
			userobj.short_id = ids[0];
			userobj.steam_id = ids[1];

			if(userobj.steam_id.length < 17)
			{
				userobj.username = 'NPC';
				userobj.steam_id = null;
			}
			else if(userobj.username == '')
				userobj.username = null;
		}
		else
			userobj.username = user;

		return userobj;
	}
}

module.exports = RconRustEvents;
