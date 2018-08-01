<?php
require_once 'MTCNode.php';
require_once 'MTCWallet.php';
require_once 'MTCDatabase.php';
class MTCClient {
    private $id = null;
    private $node = null;
    private $wallet = null;

    public function checkBalance(){
        return $this->wallet->checkBalance();
    }

    public function send($to, $amount){
        return $this->wallet->send($to, $amount);
    }

    public function dig(){
        return $this->node->dig();
    }

    private function registerNode(){
        MTCDatabase::getInstance()->createNode();
        $nodeId = MTCDatabase::getInstance()->getLastInsertId();
        return $nodeId;
    }

    private function createWallet(){
        MTCDatabase::getInstance()->createWallet();
        $walletId = MTCDatabase::getInstance()->getLastInsertId();
        return $walletId;
    }

    public function validate(){
        return $this->node->validate();
    }

    public function resolve(){
        return $this->node->resolveConflicts();
    }

    private function checkLogin($email, $pass){
        return MTCDatabase::getInstance()->getClientByEmail($email);
    }

    public function login($email, $pass){
        $client = $this->checkLogin($email, $pass);
        if(!$client) return false;
        $clientId = $client['id'];
        $nodeId = $client['node_id'];
        $walletId = $client['wallet_id'];
        return $this->load($clientId, $nodeId, $walletId);
    }

    public function register($email, $pass){
        $nodeId = $this->registerNode();
        $walletId = $this->createWallet();
        MTCDatabase::getInstance()->createClient($email, $pass, $nodeId, $walletId);
        $clientId = MTCDatabase::getInstance()->getLastInsertId();
        return $this->load($clientId, $nodeId, $walletId);
    }

    private function load($clientId, $nodeId, $walletId){
        $this->id = $clientId;
        $this->node = new MTCNode($nodeId);
        $this->wallet = new MTCWallet($walletId);
        return true;
    }

}