<?php
require_once 'MTCChain.php';
require_once 'MTCWallet.php';
require_once 'MTCDatabase.php';
class MTCNode {
    private $id = NODE_ID;
    private $chain = null;
    private $wallet = null;
    private $address = NODE_ADDRESS;
    private $port = NODE_PORT;

    public function __construct(){
        $this->load($this->id, NODE_WALLET_ID);
    }

    public function checkBalance(){
        return $this->wallet->checkBalance();
    }

    public function send($to, $amount){
        return $this->wallet->send($to, $amount);
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
        // 1. verify signature using public key
        // 2. check txOutputs are not used yet
        // 3. total amount from txOutputs >= amount will be sent
        return true;
    }
}