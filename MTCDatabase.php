<?php
require_once 'MTCBlock.php';
require_once 'MTCTransaction.php';
require_once 'MTCNode.php';
class MTCDatabase {
    private $connection = null;
    private static $instance = null;
    private function __construct(){
        $this->connect();
    }

    public static function getInstance(){
        if(!self::$instance) self::$instance = new MTCDatabase();
        return self::$instance;
    }

    private function connect(){
        $this->connection =  mysqli_connect(MTC_DB_HOST, MTC_DB_USER, MTC_DB_PASS, MTC_DB_DB);
    }

    public function getBlocks(){
        $blocks = [];
        $result = $this->getData('SELECT * FROM mtc_blocks');
        foreach($result as $row){
            $transactions = $this->getTransactionByBlock($row['id']);
            $blocks[] = new MTCBlock($row['id'], $row['previous_block_hash'], $row['hash'], $row['hash_salt'], $transactions);
        }
        return $blocks;
    }

    public function saveBlock(MTCBlock $block){
        $this->query("INSERT INTO mtc_blocks(`hash`, previous_block_hash, hash_salt) VALUES('{$block->getHash()}', '{$block->getPreviousBlockHash()}', '{$block->getCurrentSalt()}')");
        return $this->getLastInsertId();
    }

    public function getTransactionsOfWallet($walletId){
        $result = $this->getData("SELECT * FROM mtc_transactions WHERE (`to`={$walletId} OR `from`={$walletId}) AND is_pending=0");
        return $this->parseTransactions($result);
    }

    public function saveTransaction($blockId, $transactionId){
        return $this->query("UPDATE mtc_transactions SET is_pending=0, block_id={$blockId} WHERE id={$transactionId}");
    }

    public function getPendingTransactions(){
        $result = $this->getData('SELECT * FROM mtc_transactions WHERE is_pending=1');
        return $this->parseTransactions($result);
    }

    public function getTransactionByBlock($blockId) {
        $result = $this->getData("SELECT * FROM mtc_transactions WHERE block_id={$blockId} AND is_pending=0");
        return $this->parseTransactions($result);
    }

    private function parseTransactions($result){
        $transactions = [];
        foreach($result as $row){
            $transactions[] = new MTCTransaction($row['id'], $row['from'], $row['to'], $row['amount']);
        }
        return $transactions;
    }

    public function savePendingTransaction(MTCTransaction $transaction){
        return $this->query("INSERT INTO mtc_transactions(`from`, `to`, amount) VALUES({$transaction->getFrom()}, {$transaction->getTo()}, {$transaction->getAmount()})");
    }

    private function query($query){
        return mysqli_query($this->connection, $query);
    }

    private function getData($query){
        $rows = [];
        $result = $this->query($query);
        while($row = $result->fetch_array(MYSQLI_ASSOC)) {
            $rows[] = $row;
        }
        $result->close();
        return $rows;
    }

    public function getLastInsertId(){
        return mysqli_insert_id($this->connection);
    }

    public function createWallet(){
        return $this->query('INSERT INTO mtc_wallets VALUE()');
    }

    public function createNode($walletId){
        return $this->query("INSERT INTO mtc_nodes(`wallet_id`) VALUES({$walletId})");
    }

    public function getNextBlock($blockId){
        
    }
}