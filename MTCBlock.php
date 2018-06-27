<?php
namespace MTCChain;
class MTCBlock {
    private $id;
    private $previousBlockHash;
    private $hash;
    private $randomSalt;
    function __construct($id, $hash, $previousBlockHash, $randomSalt){
        $this->id = $id;
        $this->hash = $hash;
        $this->previousBlockHash = $previousBlockHash;
        $this->randomSalt = $randomSalt;
    }

    public function getHash(){
        return $this->hash;
    }

    public function getCurrentSalt(){
        return $this->randomSalt;
    }

    public function tryToFindHash($transactions){
        while(true){
            $this->randomSalt = rand();
            $this->hash = $this->calculateHash($transactions);
            if($this->isAllowHash($this->hash)) {
                return;
            }
        }
    }

    public function calculateHash($transactions){
        return md5(md5($transactions) . md5($this->$previousBlockHash) . $this->randomSalt);
    }

    private function isAllowHash($hash){
        $theFirstCharacterOfHashString = substr($hash,0, 1);
        return is_int($theFirstCharacterOfHashString);
    }

    public function getPreviousBlockHash(){
        return $this->previousBlockHash;
    }
}