<?php
class MTCTransaction {
    private $id;
    private $from;
    private $to;
    private $amount;
    function __construct($id, $from, $to, $amount){
        $this->id = $id;
        $this->from = $from;
        $this->to = $to;
        $this->amount = $amount;
    }

    public function getId(){
        return $this->id;
    }

    public function getFrom(){
        return $this->from;
    }

    public function getTo(){
        return $this->to;
    }

    public function getAmount(){
        return $this->amount;
    }
}