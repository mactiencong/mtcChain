function getConfigFileFromArgv(){
    return process.argv.slice(2)[0]
}

module.exports = function loadConfig(){
    var configFilePath = getConfigFileFromArgv()
    return require('../node'+configFilePath+'/node_socket.conf.json')
}