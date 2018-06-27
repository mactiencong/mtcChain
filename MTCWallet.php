<?php
namespace MTCChain;
use MTCChain\MTCDatabase;
class MTCWallet {
    private $id;
    function __construct($id){
        $this->id = $id;
    }
    public function checkBalance() {
        $balance = 0;
        $transactions = $this->getTransactionsOfWallet();
        foreach($transactions as $transaction){
            $balance += $transaction->getAmount();
        }
        return $balance;
    }

    private function getTransactionsOfWallet(){
        return MTCDatabase::getInstance()->getTransactionsOfWallet($this->id);
    }
}