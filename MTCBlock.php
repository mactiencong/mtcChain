<?php
class MTCBlock {
    private $id;
    private $previousBlockHash;
    private $hash;
    private $timeStamp;
    private $randomSalt;
    private $transactions;
    function __construct($id, $previousBlockHash, $hash, $randomSalt, $transactions){
        $this->id = $id;
        $this->hash = $hash;
        $this->previousBlockHash = $previousBlockHash;
        $this->randomSalt = $randomSalt;
        $this->transactions = $transactions;
    }

    public function getId(){
        return $this->id;
    }

    public function getHash(){
        return $this->hash;
    }

    public function getCurrentSalt(){
        return $this->randomSalt;
    }

    public function tryToFindHash(){
        while(true){
            $this->randomSalt = rand();
            $this->hash = $this->calculateHash();
            if($this->isAllowHash($this->hash)) {
                return;
            }
        }
    }

    public function calculateHash(){
        return md5(serialize($this->transactions) . $this->previousBlockHash . $this->randomSalt);
    }

    private function isAllowHash($hash){
        $theFirstCharacterOfHashString = substr($hash, 0, 1);
        return $theFirstCharacterOfHashString === "2";
    }

    public function getPreviousBlockHash(){
        return $this->previousBlockHash;
    }
}