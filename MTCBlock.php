<?php
namespace MTCChain;
class MTCBlock {
    private $id;
    private $transactions = [];
    private $previousBlockHash;
    private $hash;
    private $randomSalt;
    function __construct($transactions, $previousBlockHash){
        $this->transactions = $transactions;
        $this->previousBlockHash = $previousBlockHash;
        $this->hash = $this->tryToFindHash();
    }

    public function getHash(){
        return $this->hash;
    }

    private function tryToFindHash(){
        while(true){
            $this->randomSalt = rand();
            $hash = $this->calculateHash();
            if($this->isAllowHash($hash)) {
                return $hash;
            }
        }
    }

    public function calculateHash(){
        return md5(md5($this->transactions) . md5($this->$previousBlockHash) . $this->randomSalt);
    }

    private function isAllowHash($hash){
        $theFirstCharacterOfHashString = substr($hash,0, 1);
        return is_int($theFirstCharacterOfHashString);
    }

    public function getPreviousBlockHash(){
        return $this->previousBlockHash;
    }

    public function getTransactionOfWallet($walletId){
        return array_filter($this->transactions, function($transaction){
            return $transaction->getTo() === $walletId;
        });
    }
}