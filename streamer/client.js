if(process.argv.length != 4)
{
	console.log("\n" + 'Options:' + "\n" + '-s (server_id) :: Specify numeric server_id.' + "\n" + '-t (host:port/password) :: Test a given server.' + "\n");
	process.exit();
}

server_id_location = process.argv.findIndex(function(item){return item == '-s'});
test_location = process.argv.findIndex(function(item){return item == '-t'});

const server_id = (server_id_location != -1 ? process.argv[(server_id_location+1)] : null);
const test = (test_location != -1 ? process.argv[(test_location+1)] : null);

const Util = require('./inc/Util.js');

const util = require('util');
const WebSocket = require('ws');
const readline = require('readline');

const rl = readline.createInterface({
	input: process.stdin,
	output: process.stdout,
	prompt: 'RUST> '
});

Socket = new WebSocket('ws://localhost:6869/');

Socket.on('open', function(){
	if(server_id !== null)
		Socket.send(JSON.stringify({
			'type': 'stream.connect',
			'server_id': server_id
		}));

	if(test !== null)
	{
		host = test.substr(0, test.indexOf(':'));
		port = test.substr(test.indexOf(':')+1);
		port = port.substr(0, port.indexOf('/'));
		password = test.substr(test.indexOf('/')+1);

		Socket.send(JSON.stringify({
			'type': 'testserver',
			'host': host,
			'port': port,
			'password': password
		}));
	}

	rl.prompt();

	rl.on('line', function(line){
		if(line === 'quit' || line === 'exit')
		{
			gracefulShutdown();
			return;
		}

		let type;
		line = line.trim();

		switch(line)
		{
			case '*.dumpscheduler':
				type = 'dumpscheduler';
			break;
			default:
				type = 'stream.command';
			break;
		}

		Socket.send(JSON.stringify({
			'type': type,
			'server_id': server_id,
			'command': line
		}));

		rl.prompt();
	});
});

Socket.on('message', function(data){
	console.log("\n");
	console.log(util.inspect(JSON.parse(data), {'depth': null}));
	console.log("\n");
	rl.prompt();
});

// **************************
// **** Shutdown Handler ****
// **************************

function gracefulShutdown(){
	console.log("\n" + 'Graceful shutdown initiated.');

	let shutdown_counter = 0;
	let shutdown_attempts = 0;

	if(Socket.readyState === 1)
	{
		Socket.send(JSON.stringify({
			'type': 'stream.disconnect',
			'server_id': server_id
		}));

		shutdown_counter++;

		Socket.on('close', function(){
			console.log('Streamer connection closed.');
			shutdown_counter--;
		});

		Socket.close();
	}

	setInterval(function(){
		if(shutdown_counter == 0 || shutdown_attempts == 4)
		{
			if(shutdown_counter == 0)
				console.log('Graceful shutdown complete.');
			else
				console.log('Graceful shutdown timer exceeded, program exiting.');

			process.exit();
		}
		else
			shutdown_attempts++;
	}, Util.toMSeconds(1));
}

process.on('SIGINT', gracefulShutdown);
process.on('SIGTERM', gracefulShutdown);
