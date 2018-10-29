_config = {
	'db': {
		'host': 'localhost',
		'port': 3306,
		'user': 'homestead',
		'password': 'secret',
		'database': 'rcon2',
		'dateStrings': true
	},
	'api': {
		'valve': {
			'key': '1960ED69EAE66A44E881ED5F6F1EADF0',
			'appid': '252490'
		}
	}
};

express = require('express');
bodyParser = require('body-parser');
http = require('http');

utf8 = require('utf8');
WebSocket = require('ws');
EventEmitter = require('events');
DateTime = require('luxon').DateTime;

Util = require('./inc/Util.js');
Player = require('./inc/Player.js');
RconServer = require('./inc/RconServer.js');
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

_servers = {};

StreamerEvents.on('dbready', function(){
	RconServer.load();
});

// ***********************
// **** Express Setup ****
// ***********************

express_app = new express;

server = http.createServer(express_app);

express_app.use(bodyParser.json());
express_app.use(bodyParser.urlencoded({ extended: true }));

express_app.post('/api/server', function(req, res){
	let server_id = req.body.server_id;
	let operation = req.body.operation;

	switch(operation)
	{
		case 'event.update':
			_servers[server_id].scheduler.handleEventChange(req.body.event_id, req.body.event_type);
			return res.json({'result': 'success', 'message': 'Event updated.'});
		break;
		case 'server.update':
			RconServer.load(req.body.server_id);
			return res.json({'result': 'success', 'message': 'Server updated.'});
		break;
		case 'player.kick':
			_servers[server_id].rcon.command('kick ' + req.body.steam_id);
			return res.json({'result': 'success', 'message': 'Player kicked.'});
		break;
		case 'player.ban':
			_servers[server_id].rcon.command('banid ' + req.body.steam_id);
			return res.json({'result': 'success', 'message': 'Player banned.'});
		break;
	}

	return res.json({'result': 'failure', 'message': 'Invalid parameters.'});
});

server.listen(7869, '127.0.0.1', 1024);

// *****************************
// **** Database Initialize ****
// *****************************

const mysql = require('mysql');

db = mysql.createConnection(_config.db);

db.on('error', function(error){
	Util.log(error);
});

db.connect(function(error) {
	if (error) {
		Util.log(error.stack);
		process.exit();
	}
	else
	{
		db.query('SET time_zone = \'UTC\'', [], function(error, results, fields){
			if(error) Util.log('Error setting time zone to UTC.');

			db.query('SET NAMES \'utf8mb4\' COLLATE \'utf8mb4_unicode_ci\';', [], function(error, results, fields){
				if(error) Util.log('Error setting time zone to UTC.');

				db.query('TRUNCATE TABLE players_current', [], function (error, results, fields){
					if(error) Util.log('Error truncating players_current table.');

					StreamerEvents.emit('dbready');
				});
			});
		});

		setInterval(function(){
			db.ping();
		}, Util.minToMSeconds(1));
	}
});

// **************************
// **** Shutdown Handler ****
// **************************

process.on('SIGINT', Util.gracefulShutdown);
process.on('SIGTERM', Util.gracefulShutdown);
