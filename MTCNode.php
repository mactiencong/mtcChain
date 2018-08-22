<?php
require_once 'MTCChain.php';
require_once 'MTCWallet.php';
require_once 'MTCDatabase.php';
class MTCNode {
    private $id = null;
    private $chain = null;
    private $wallet = null;
    private $address = null; // 127.0.0.1
    private $port = null; // 8081

    public function checkBalance(){
        return $this->wallet->checkBalance();
    }

    public function send($to, $amount){
        return $this->wallet->send($to, $amount);
    }

    private function registerNode($email, $pass, $walletId){
        MTCDatabase::getInstance()->createNode($email, $pass, $walletId);
        $nodeId = MTCDatabase::getInstance()->getLastInsertId();
        return $nodeId;
    }

    private function createWallet(){
        MTCDatabase::getInstance()->createWallet();
        $walletId = MTCDatabase::getInstance()->getLastInsertId();
        return $walletId;
    }

    private function checkLogin($email, $pass){
        return MTCDatabase::getInstance()->getNodeByEmail($email);
    }

    public function login($email, $pass){
        $node = $this->checkLogin($email, $pass);
        if(!$node) return false;
        $nodeId = $node['id'];
        $walletId = $node['wallet_id'];
        return $this->load($nodeId, $walletId);
    }

    public function register($email, $pass){
        $walletId = $this->createWallet();
        $nodeId = $this->registerNode($email, $pass, $walletId);
        return $this->load($nodeId, $walletId);
    }

    private function load($nodeId, $walletId){
        $this->id = $nodeId;
        $this->chain = new MTCChain();
        $this->wallet = new MTCWallet($walletId);
        return true;
    }

    public function validate(){
        return $this->chain->validate();
    }

    public function length(){
        return count($this->chain->length());
    }

    public function getChain(){
        return $this->chain;
    }

    public function dig(){
        return $this->chain->dig($this->wallet->getId());
    }

    public function resolve(){
        $currentChainLength = $this->length();
        $neighborNodes = MTCDatabase::getInstance()->getNeighborNodes($this->id);
        foreach($neighborNodes as &$node){
            if(($node->length() > $currentChainLength) && $node->validate()) {
                $this->sync($node->getChain());
            }
        }
    }

    private function sync($validChain){
        $this->chain = $validChain;
        return true;
    }

    // prevent double-spending
    public function verifyTransaction($transactionId){
        return true;
    }
}