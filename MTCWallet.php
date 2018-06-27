<?php
namespace MTCChain;
class MTCWallet {
    private $id;
    private $blocks;
    public function checkBalance() {
        $balance = 0;
        foreach($this->blocks as $block){
            $transactions = $block->getTransactionOfWallet($this->id);
            foreach($transactions as $trans){
                $balance += $trans->getAmount();
            }
        }
        return $balance;
    }
}