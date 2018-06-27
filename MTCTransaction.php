<?php
namespace MTCChain;
class MTCTransaction {
    private $from;
    private $to;
    private $amount;
    function __construct($from, $to, $amount){
        $this->from = $from;
        $this->to = $to;
        $this->amount = $amount;
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