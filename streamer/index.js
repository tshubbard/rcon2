_config = {
	'db': {
		'host': 'localhost',
		'port': 3306,
		'user': 'homestead',
		'password': 'secret',
		'database': 'rust-rcon',
		'dateStrings': true
	},
	'api': {
		'valve': {
			'key': '1960ED69EAE66A44E881ED5F6F1EADF0',
			'appid': '252490'
		}
	}
};

utf8 = require('utf8');
WebSocket = require('ws');
EventEmitter = require('events');
DateTime = require('luxon').DateTime;

Util = require('./inc/Util.js');
Player = require('./inc/Player.js');
RconWebSocket = require('./inc/RconWebSocket.js');
RconRustEvents = require('./inc/RconRustEvents.js');
RconConnectionEvents = require('./inc/RconConnectionEvents.js');
Scheduler = require('./inc/Scheduler.js');
StreamerServer = require('./inc/StreamerServer.js');

StreamerServer_i = new StreamerServer;
StreamerEvents = new EventEmitter;

// **********************************
// **** Setup Server Connections ****
// **********************************

StreamerEvents.on('dbready', function(){
	db.query('SELECT id, host, password, port, timezone FROM servers WHERE disabled = 0', function(error, results, fields){
		if(error)
		{
			console.log('Error retrieving list of servers.');
			process.exit();
		}

		_servers = {};

		results.forEach(function(server){
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
});

// *****************************
// **** Database Initialize ****
// *****************************

const mysql = require('mysql');

db = mysql.createConnection(_config.db);

db.on('error', function(error){
	console.log(error);
});

db.connect(function(error) {
	if (error) {
		console.log(err.stack);
		process.exit();
	}
	else
	{
		db.query('TRUNCATE TABLE players_current', [], function (error, results, fields){
			if (error) console.log('Error truncating players_current table.');

			StreamerEvents.emit('dbready');
		});
	}
});

// **************************
// **** Shutdown Handler ****
// **************************

process.on('SIGINT', Util.gracefulShutdown);
process.on('SIGTERM', Util.gracefulShutdown);
