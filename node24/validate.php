<?php
require_once __DIR__ . '/MTCConfig.php';
require_once __DIR__ . '/../MTCNode.php';
$mtcNode = new MTCNode();
$mtcNode->login('matico2@gmail.com', '123456');
var_dump($mtcNode->validate());