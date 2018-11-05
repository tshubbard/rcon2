class Util {
	static toMSeconds(seconds)
	{
		return (seconds * 1000);
	}

	static minToMSeconds(minutes)
	{
		return (minutes * 60000);
	}

	static minToSeconds(minutes)
	{
		return (minutes * 60);
	}

	static hourToSeconds(hours)
	{
		return (hours * 3600);
	}

	static log(message)
	{
		console.log(DateTime.local().toString() + ' - ' + message);
	}

	static gracefulShutdown(){
		Util.log('Graceful shutdown initiated.');

		let shutdown_counter = 0;
		let shutdown_attempts = 0;

		shutdown_counter++;

		db.end(function(){
			Util.log('Database connection closed.');

			shutdown_counter--;
		});

		Object.keys(_servers).forEach(function(server){
			if(_servers[server].rcon.isConnected())
				shutdown_counter++;

			RconConnectionEvents.cleanupIntervals(server);

			_servers[server].rcon.on('close', function(){
				Util.log('Rust server [' + server + '] connection closed.');
				shutdown_counter--;
			});

			_servers[server].rcon.disconnect();
		});

		setInterval(function(){
			if(shutdown_counter == 0 || shutdown_attempts == 4)
			{
				if(shutdown_counter == 0)
					Util.log('Graceful shutdown complete.');
				else
					Util.log('Graceful shutdown timer exceeded, program exiting.');

				process.exit();
			}
			else
				shutdown_attempts++;
		}, Util.toMSeconds(1));
	}
}

module.exports = Util;
