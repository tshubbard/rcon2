var express = require('express'),
    socketSession = require('socket.io-mysql-session'),
    cors = require('cors'),
    util = require('util'),
    _ = require('underscore'),
    app = express(),
    http = require('http').Server(app),
    Logger = require('filelogger'),
    logger = new Logger('error', 'info', 'rustrecon.log'),
    io = require('socket.io')(http),
    mysql = require('mysql'),
    options = {
        host: 'localhost',
        port: 3306,
        user: 'homestead',
        password: 'secret',
        database: 'rcon2'
    },
    db = mysql.createConnection(options),
    dbConnectAttempts = 0,
    socketSessionObj = {
        db: db,
        logger: logger
    },
    server = http.listen(6969, 'localhost', function() {
        var host = server.address().address,
            port = server.address().port;
        logger.log('info', 'RustReCON app server started. Listening at http://' + host + ':' + port);
    });

//db error handling
db.on('error', dbErrorHandler);

/**
 * db error handler, tries to reconnect.
 * @param error
 */
function dbErrorHandler(error) {
    logger.log('error', 'Uncaught DB error: ' + error.code);
    if (error.code == 'ECONNREFUSED' || error.code == 'PROTOCOL_CONNECTION_LOST') {
        logger.log('info', 'Attempting to reconnect to Database due to internal error...');
        dbReconnect();
    }
}

/**
 * reconnects the db upon failure. hopefully reconencts the sockets as well.
 */
function dbReconnect() {
    dbConnectAttempts++;
    db = mysql.createConnection(options);
    socketSessionObj.db = db;
    db.connect(function(error) {
        if (_.isNull(error)) {
            logger.log('info', 'DB connection restored after ' + dbConnectAttempts + ' seconds');
            dbConnectAttempts = 0;
            //restore error handler on the new object
            db.on('error', dbErrorHandler);
        } else {
            setTimeout(dbReconnect, 1000);
        }
    });
}

app.use(cors({
    origin: true,
    credentials: true
}));

io.use(new socketSession(socketSessionObj));

io.on('connection', function(socket) {
    logger.log('debug', 'Socket Connected');

    /**
     * handles user login events
     */
    socket.on('user:login', function(params) {
        logger.log('info', 'user:login params');
        logger.log('info', params);
        //var user = new User(app, socket);
        //user.login(params);
    });
});

/**
 * app router
 *
 * This function splits the url into parts and includes files as accordingly
 *
 * For example, a url of /user/add will spawn the following actions:
 *   - ./user.js is loaded
 *   - user.preEvent() is called (if defined) with the express request and response variables, along with the function
 *     requested.
 *   - user.add() is called with the express request and response variables
 *
 * If the router can't find a file to load, or an appropriate function, it will throw a 404 response
 *
 */
app.all('*', function(request, response, next) {
    var path = request.path.split('/'),
        serviceResponse,
        preEventSuccess = true;

    response.locals.serviceResponse = app.locals.getResponseObject();
    serviceResponse = response.locals.serviceResponse;

    if (path.length <= 2) {
        app.locals.sendResponse(response, serviceResponse);
    } else {
        try {
            var Obj = require('./' + path[1] + '.js'),
                obj = new Obj(app, request, response);
            if (!_.isUndefined(obj.preEvent) && _.isFunction(obj.preEvent)) {
                preEventSuccess = obj.preEvent(path[2]);
            } else {
                serviceResponse.code = 404;
                serviceResponse.errors.push('Unknown Path');
                logger.log('warn', 'Invalid Path Attempted');
            }
            if (preEventSuccess) {
                obj[path[2]]();
            }
        } catch (e) {
            console.log(util.inspect(e));
            serviceResponse.success = false;
            serviceResponse.code = 404;
            serviceResponse.errors.push('Unknown Path');
            logger.log('error', e);
            app.locals.sendResponse(response);
        }
    }
});

/**
 * service response
 * @returns {{success: boolean, code: number, errors: Array, data: Array}}
 */
app.locals.getResponseObject = function() {
    return {
        success: true,
        code: 200,
        errors: [],
        data: {}
    }
};

/**
 * util to send data with proper mimetypes
 * @param response express response object
 */
app.locals.sendResponse = function(response) {
    logger.log('debug', 'Rendering');
    response.type('json');
    response.send(response.locals.serviceResponse);
    response.end();
};

/**
 * emits a message to a particular socket.  It takes the socket as a param, as well as a payload.
 *
 * The payload consists of:
 *    {
 *      message: 'the_event_message',
 *      serviceResponse: serviceResponse object
 *    }
 * @param socket
 * @param payload
 */
app.locals.emit = function(socket, payload, room) {
    logger.log('debug', 'Emitting');
    logger.log('debug', util.inspect(payload));
    if (_.isUndefined(room)) {
        logger.log('debug', 'To socket: ' + socket.id);
        io.to(socket.id).emit(payload.message, payload.serviceResponse);
    } else {
        logger.log('debug', 'To room: ' + room);
        socket.broadcast.to(room).emit(payload.message, payload.serviceResponse)
    }
};

app.locals.db = mysql.createPool(_.extend(options, {
    connectionLimit: 500
}));

app.locals.getQueryValue = function(request, key) {
    var returnValue = '',
        params = _.extend(request.params || {}, request.query || {}, request.body || {});

    if (!_.isUndefined(params[key])) {
        returnValue = params[key];
    }

    return returnValue;
};

/**
 * utility function to return the current unixTimestamp of NOW
 * @returns {number}
 */
app.locals.now = function() {
    return Math.round((new Date()).getTime() / 1000)
};

app.locals.logger = logger;
