var socketIOClient = require('socket.io-client')
var fs = require('fs')
var config = require('./config.js')()
console.log(config)
var bootstapNodeSocket = socketIOClient.connect(config.bootstap_node, {reconnect: true})
var nodeAddresses = []

bootstapNodeSocket.on('connect', function() {
    console.log('connect')
    bootstapNodeSocket.emit('NEW_NODE', {address: config.address, port: config.port})
    bootstapNodeSocket.emit('GET_NODE_ADDRESS')
    bootstapNodeSocket.on('NODE_ADDRESSES', (nodeAddresses)=>{
        console.log(nodeAddresses)
        nodeAddresses = nodeAddresses
        fs.writeFile('nodes.json')
    })
})

var express = require('express')()
var nodeServer = require('http').Server()
var socketIO = require('socket.io')(nodeServer)

express.post('/new_transaction', (transaction)=>{
    console.log(transaction)
    bootstapNodeSocket.emit('NEW_TRANSACTION', transaction)
})

express.post('/new_block', (block)=>{
    console.log(block)
    bootstapNodeSocket.emit('NEW_BLOCK', transaction)
})


nodeServer.listen(config.port, () => {
    console.log("Listening on " + config.port)
})
nodeServer.post
