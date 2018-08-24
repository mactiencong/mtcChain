var socketIOClient = require('socket.io-client')
var fs = require('fs')
var config = require('./config.js')()
console.log(config)
var bootstapNodeSocket = socketIOClient.connect(config.bootstap_node, {reconnect: true})
var nodeAddresses = []

bootstapNodeSocket.on('connect', function() {
    console.log('Connected to Bootstrap node: '+config.bootstap_node)
    bootstapNodeSocket.emit('NEW_NODE', {address: config.address, port: config.port})
    bootstapNodeSocket.emit('GET_NODE_ADDRESS')
    bootstapNodeSocket.on('NODE_ADDRESSES', (nodeAddresses)=>{
        console.log(nodeAddresses)
        nodeAddresses = nodeAddresses
        fs.writeFile('nodes.json')
    })
    bootstapNodeSocket.on('NODE_CONNECTED', (nodeAddress)=>{
        console.log("New node connected: " + nodeAddress)
        nodeAddresses.push(nodeAddress)
        console.log(nodeAddresses)
    })

    bootstapNodeSocket.on('NEW_TRANSACTION', (transaction)=>{
        console.log("New transacton: " + transaction)
    })
})

var express = require('express')()
var bodyParser = require('body-parser')
express.use(bodyParser.json())
express.use(bodyParser.urlencoded({ extended: true }))
var nodeServer = require('http').Server(express)
var socketIO = require('socket.io')(nodeServer)

express.get('/', (req, res)=>{
    console.log(config.address + ':' + config.port)
    res.send(config.address + ':' + config.port);
})

express.post('/new_transaction', (req, res)=>{
    console.log('/new_transaction')
    console.log(req.body)
    bootstapNodeSocket.emit('NEW_TRANSACTION', req.body)
    res.send('ok')
})

express.post('/new_block', (req, res)=>{
    console.log('/new_block')
    console.log(req.body)
    bootstapNodeSocket.emit('NEW_BLOCK', req.body)
    res.send('ok')
})

express.post('/connect_neighbor', (req, res)=>{
    console.log('/connect_neighbor')
    console.log(req.body.nodeIndex)
    connectToNeighborNode(req.body.nodeIndex)
    res.send('ok')
})
var neighborNodeSocket = null
function connectToNeighborNode(nodeIndex){
    console.log(nodeAddresses)
    neighborNode = nodeAddresses[nodeIndex]
    console.log(neighborNode)
    neighborNodeUrl = "http://"+neighborNode.address+":"+neighborNode.port
    console.log("neighborNodeUrl: "+neighborNodeUrl)
    neighborNodeSocket = socketIOClient.connect(neighborNodeUrl, {reconnect: true})
    neighborNodeSocket.on('connect', () => {
        console.log('Connected to node: '+neighborNodeUrl)
    })
}

express.post('/neighbor_chain_length', (req, res)=>{
    neighborNodeSocket.emit('REQ_CHAIN_LENGTH')
    new Promise(resolve=>{
        neighborNodeSocket.on('RES_CHAIN_LENGTH', length => {
            console.log('RES_CHAIN_LENGTH: '+length)
            resolve(length)
        })
    }).then(length=>{
        res.send(length+"")
    })
})

express.post('/get_block', (req, res)=>{
    currentBlockId = req.body.blockId
    neighborNodeSocket.emit('REQ_BLOCK', currentBlockId)
    new Promise(resolve=>{
        neighborNodeSocket.on('RES_REQ_BLOCK', nextBlock => {
            console.log('RES_REQ_BLOCK: '+nextBlock)
            resolve(nextBlock)
        })
    }).then(nextBlock=>{
        res.send(nextBlock)
    })
})

socketIO.on('connection', node => {
    console.log("Other node connected")
    node.on('REQ_CHAIN_LENGTH', ()=>{
        node.emit('RES_CHAIN_LENGTH', 5)
    })
})

nodeServer.listen(config.port, () => {
    console.log("Listening on " + config.port)
})