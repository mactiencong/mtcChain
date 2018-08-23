var app = require('express')()
var server = require('http').Server(app)
var io = require('socket.io')(server)
var nodeAddresses = []

server.listen(8081, () => {
    console.log("Listening on 8081")
})

io.on('connection', (node) => {
    console.log('connection')
    node.on('NEW_NODE', (nodeAddress) => {
        nodeAddresses.push(nodeAddress)
        console.log(nodeAddresses)
        broadcastNewNodeConnected(nodeAddress)
    })

    node.on('GET_NODE_ADDRESS', ()=>{
        pushNodeAddressForNewNode(node)
    })
})

function broadcastNewNodeConnected(nodeAddress){
    io.emit('NODE_CONNECTED', {nodeAddress})
    console.log("Add new node: ")
    console.log(nodeAddress)
}

function pushNodeAddressForNewNode(node){
    console.log('Push node addresses for new node')
    console.log(nodeAddresses)
    node.emit('NODE_ADDRESSES', nodeAddresses)
}