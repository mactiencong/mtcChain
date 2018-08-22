var io = require('socket.io-client')
var socket = io.connect('localhost:8081', {reconnect: true})
var nodeAddresses = new Set()

server.listen(8082, ()=>{
    console.log("Listening on 8082")
})

socket.on('connect', function(socket) { 
    console.log('Connected!')
})