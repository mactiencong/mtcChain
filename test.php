<?php
require_once 'MTCChain.php';
require_once 'MTCWallet.php';
$mtcWallet = new MTCWallet(3);
// $mtcWallet->send(2, 20);
$mtcChain = new MTCChain();
$mtcChain->dig(3);
// $mtcChain = new MTCChain();
// var_dump($mtcChain->validate());
var_dump($mtcWallet->checkBalance());