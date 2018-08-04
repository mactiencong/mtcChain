<?php
require_once 'MTCDatabase.php';
class MTCWallet {
    private $id;
    function __construct($id){
        $this->id = $id;
    }
    public function checkBalance() {
        $balance = 0;
        $transactions = $this->getTransactionsOfWallet();
        foreach($transactions as $transaction){
            if($transaction->getTo()==$this->id){
                $balance += $transaction->getAmount();
            }
            if($transaction->getFrom()==$this->id){
                $balance -= $transaction->getAmount();
            }
        }
        return $balance;
    }

    private function isSystem(){
        return $this->id === "1" || $this->id === 1;
    }

    private function getTransactionsOfWallet(){
        return MTCDatabase::getInstance()->getTransactionsOfWallet($this->id);
    }

    private function isAmountEnough($amount){
        return $this->isSystem() || $this->checkBalance()>=$amount;
    }

    public function send($to, $amount){
        if(!$this->isAmountEnough($amount)) return false;
        return MTCDatabase::getInstance()->savePendingTransaction(new MTCTransaction(null, $this->id, $to, $amount));
    }
}