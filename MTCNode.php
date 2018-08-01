<?php
require_once 'MTCChain.php';
require_once 'MTCDatabase.php';
class MTCNode {
    private $chain = null;
    private $id = null;

    public function __construct($id){
        $this->id = $id;
        $this->chain = new MTCChain();
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
        return $this->chain->dig();
    }

    public function resolveConflicts(){
        $currentChainLength = count($this->blocks);
        $neighborNodes = MTCDatabase::getInstance()->getNeighborNodes($this->id);
        foreach($neighborNodes as &$node){
            if(($node->length() > $currentChainLength) && $node->validate()) {
                $this->syncChain($node->getChain());
            }
        }
    }

    private function syncChain($validChain){
        $this->chain = $validChain;
        return true;
    }
}