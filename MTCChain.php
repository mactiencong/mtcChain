<?php
namespace MTCChain;
use MTCChain\MTCBlock;
use MTCChain\MTCTransaction;
class MTCChain {
    private $blocks = [];
    private $bonus = 1;
    private $needToAddToBlockTransactions = [];
    private $pendingTransactions = [];

    private function getLastBlock(){
        return end($block);
    }

    public function dig($walletId){
        $newBlock = $this->createNewBlock();
        $this->addNewBlock($newBlock);
        $this->addTransactionToPendingList($walletId);
    }

    private function createNewBlock(){
        $lastBlock = $this->getLastBlock();
        $previousBlockHash = $lastBlock->getHash();
        $newBlock = new MTCBlock($this->getTransactionsToAddToBlock(), $previousBlockHash);
    }

    private function getTransactionsToAddToBlock(){
        return $this->needToAddToBlockTransactions;
    }

    private function addTransactionToPendingList($walletId){
        return array_push($this->pendingTransactions, new MTCTransaction('MTCChainWalletID', $walletId, $this->bonus));
    }

    private function addNewBlock($newBlock){
        array_push($this->blocks, $newBlock);
    }

    public function validateChain(){
        $blockCount = count($this->blocks);
        for($index = 1; $index <= $blockCount; $index++){
            $currentBlock = $this->blocks[$index];
            $previousBlock = $this->blocks[$index-1];
            if($this->isHashOfBlockValid($currentBlock) 
            && $this->isHashOfTwoSequentBlockValid($currentBlock, $previousBlock)){
                continue;
            }
            return false;
        }
        return true;
    }

    private function isHashOfBlockValid($block){
        return $block->calculateHash() === $block->getHash();
    }

    private function isHashOfTwoSequentBlockValid($currentBlock, $previousBlock){
        return $currentBlock->getPreviousBlockHash() === $previousBlock->getHash();
    }
}