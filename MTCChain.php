<?php
require_once 'MTCBlock.php';
require_once 'MTCTransaction.php';
require_once 'MTCDatabase.php';
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
        return end($this->blocks);
    }

    private function loadBlocks(){
        $this->blocks = MTCDatabase::getInstance()->getBlocks();
    }

    public function dig($walletId){
        $newBlock = $this->tryToCreateNewBlock();
        $blockId = MTCDatabase::getInstance()->saveBlock($newBlock);
        $this->saveTransactionFromPendingTransaction($blockId);
        MTCDatabase::getInstance()->savePendingTransaction(new MTCTransaction(null, 1, $walletId, $this->bonus));
        $this->initData();
    }

    private function tryToCreateNewBlock(){
        $previousBlockHash = $this->getLastBlock()->getHash();
        $newBlock = new MTCBlock(null, $previousBlockHash, null, null, $this->pendingTransactions);
        $newBlock->tryToFindHash();
        return $newBlock;
    }

    private function saveTransactionFromPendingTransaction($blockId){
        foreach($this->pendingTransactions as $transaction){
            MTCDatabase::getInstance()->saveTransaction($blockId, $transaction->getId());
        }
    }

    private function loadTransactionsToAddToBlock(){
        $this->pendingTransactions = MTCDatabase::getInstance()->getPendingTransactions();
    }

    public function validate(){
        $blockCount = count($this->blocks);
        for($index = 1; $index < $blockCount; $index++){
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

    private function isHashOfBlockValid(MTCBlock $block){
        return $block->calculateHash() === $block->getHash();
    }

    private function isHashOfTwoSequentBlockValid(MTCBlock $currentBlock, MTCBlock $previousBlock){
        return $currentBlock->getPreviousBlockHash() === $previousBlock->getHash();
    }
}