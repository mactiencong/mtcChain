<?php
class MTCTxInput {
    public $txId; // transaction id of coinbase transaction
    public $txOutIdx; // txOutput index in $txId transaction
    public $signature; // using private key to sign
    public $pubKey;
}