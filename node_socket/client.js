var io = require('socket.io-client')
var fs = require('fs')
var config = require('./config.js')()
console.log(config)
var socket = io.connect(config.bootstap_node, {reconnect: true})
var nodeAddresses = []

socket.on('connect', function() {
    console.log('connect')
    socket.emit('NEW_NODE', {address: config.address, port: config.port})
    socket.emit('GET_NODE_ADDRESS')
    socket.on('NODE_ADDRESSES', (nodeAddresses)=>{
        console.log(nodeAddresses)
        nodeAddresses = nodeAddresses
        fs.writeFile('nodes.json')
    })
})