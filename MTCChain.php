<?php
namespace MTCChain;
use MTCChain\MTCBlock;
use MTCChain\MTCTransaction;
use MTCChain\MTCDatabase;
class MTCChain {
    private $blocks = [];
    private $bonus = 1;
    private $pendingTransactions = [];

    function __construct(){
        $this->initData();
    }

    private function initData(){
        $this->loadBlocks();
        $this->loadTransactionsToAddToBlock();
    }

    private function getLastBlock(){
        return end($block);
    }

    private function loadBlocks(){
        $this->blocks = MTCDatabase::getInstance()->getBlocks();
    }

    public function dig($walletId){
        $newBlock = $this->tryToCreateNewBlock();
        MTCDatabase::getInstance()->saveBlock($newBlock);
        $this->saveTransactionFromPendingTransaction();
        MTCDatabase::getInstance()->savePendingTransaction(1, $walletId, $this->bonus);
        $this->initData();
    }

    private function tryToCreateNewBlock(){
        $previousBlockHash = $this->getLastBlock()->getHash();
        $newBlock = new MTCBlock(null, $previousBlockHash, null, null);
        $newBlock->tryToFindHash($this->pendingTransactions);
        return $newBlock;
    }

    private function saveTransactionFromPendingTransaction(){
        foreach($this->pendingTransactions as $transaction){
            MTCDatabase::getInstance()->saveTransaction($transaction->getId());
        }
    }

    private function loadTransactionsToAddToBlock(){
        $this->pendingTransactions = MTCDatabase::getInstance()->getPendingTransactions();
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

    public function send($from, $to, $amount){
        return MTCDatabase::getInstance()->savePendingTransaction(new MTCTransaction(null, $from, $to, $amount));
    }
}