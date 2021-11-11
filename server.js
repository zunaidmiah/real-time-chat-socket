const express = require('express');
const app = express();
const server = require('http').createServer(app);
const io = require('socket.io')(server, {
    cors: { origin: "*" }
});

io.on('connection', (socket) => {
    console.log('connection');
    socket.on('sendChatToServer', (msgInfo) => {
        console.log(msgInfo);
        var channel = msgInfo['channel'];
        socket.broadcast.emit("channel", msgInfo);
        // socket.to(msgInfo['reciver'].toString()).emit('sendChatToClient', msgInfo);
        // socket.broadcast.to(msgInfo['reciver'].toString()).emit('sendChatToClient', msgInfo);
    });

    socket.on('disconnect', (socket) => {
        console.log('Disconnect');
    });
});

server.listen(3000, () => {
    console.log('Server is running');
});