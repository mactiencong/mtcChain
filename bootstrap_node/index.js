var app = require('express')()
var server = require('http').Server(app)
var io = require('socket.io')(server)
var nodeAddresses = new Set()

server.listen(8081, ()=>{
    console.log("Listening on 8081")
})

io.on('connection', function (socket) {
    socket.on('NEW_NODE', function (nodeAddress) {
        nodeAddresses.add(nodeAddress)
        socket.emit('NODE_CONNECTED', {nodeAddress})
    })
})