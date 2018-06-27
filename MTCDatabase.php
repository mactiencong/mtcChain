<?php
namespace MTCChain;
use MTCChain\MTCBlock;
use MTCChain\MTCTransaction;
class MTCDatabase {
    private $connection = null;
    private static $instance = null;
    private function __construct(){
        $this->connect();
    }

    public static function getInstance(){
        return $instance? $instance: new MTCDatabase();
    }

    private function connect(){
        $this->connection =  mysqli_connect('localhost', 'root', '', 'mtcchain');
    }

    public function getBlocks(){
        $blocks = [];
        $result = $this->getData('SELECT * FROM mtc_blocks');
        foreach($result as $row){
            $blocks[] = new MTCBlock($row['id'], $row['hash'], $row['previous_block_hash'], $row['hash_salt']);
        }
        return $blocks;
    }

    public function saveBlock(MTCBlock $block){
        return $this->query("INSERT INTO mtc_blocks(hash, previous_block_hash, hash_salt) VALUES('{$block->getHash()}', '{$block->getPreviousBlockHash()}', '{$block->getCurrentSalt()}')");
    }

    public function getTransactionsOfWallet($walletId){
        $result = $this->getData('SELECT * FROM mtc_transactions WHERE to='+$walletId);
        foreach($result as $row){
            $blocks[] = new MTCTransaction($row['id'], $row['from'], $row['to'], $row['amount']);
        }
    }

    public function saveTransaction($transactionId){
    return $this->query("UPDATE mtc_transactions SET is_pending=0 WHERE id={$transactionId}");
    }

    public function getPendingTransactions(){
        return $this->getData('SELECT * FROM mtc_transactions WHERE is_pending=1');
    }

    public function savePendingTransaction(MTCTransaction $transaction){
        return $this->query("INSERT INTO mtc_transactions(from, to, amount) VALUES({$transaction->getFrom()}, {$transaction->getTo()}, {$transaction->getAmount()})");
    }

    private function query($query){
        return mysqli_query($this->connection, $query);
    }

    private function getData($query){
        $rows = [];
        $result = $this->query($query);
        while($row = $result->fetch_row()) {
            $rows[] = $row;
        }
        $result->close();
        return $rows;
    }
}