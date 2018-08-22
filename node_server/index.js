var io = require('socket.io-client')
var socket = io.connect('http://127.0.0.1:8081/', {reconnect: true})
var nodeAddresses = new Set()
var address = '127.0.0.1'
var port = 8082

socket.on('connect', function() {
    console.log('connect')
    socket.emit('NEW_NODE', {address: address, port: port})
    socket.on('NODE_CONNECTED', (nodeAddress)=>{
        nodeAddresses.add(nodeAddress)
        console.log(nodeAddress)
    })
})