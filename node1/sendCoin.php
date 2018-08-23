<?php
require_once __DIR__ . '/MTCConfig.php';
require_once __DIR__ . '/../MTCNode.php';
$mtcNode = new MTCNode();
var_dump($mtcNode->send(7, 10));