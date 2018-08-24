<?php
function mtcCallPostRequest($postData, $entryPoint){
    $ch = curl_init();
    $nodeServer = 'http://'.NODE_ADDRESS.':'.NODE_PORT.'/'.$entryPoint;
    curl_setopt($ch, CURLOPT_URL, $nodeServer);
    $headers = [
        'Content-Type: application/x-www-form-urlencoded; charset=utf-8',
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, mtcParsePostData($postData));
    curl_exec($ch);
    curl_close($ch);
}

function mtcParsePostData($postDataArray){
    $fields_string = '';
    foreach($postDataArray as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
    return rtrim($fields_string, '&');
}

function mtcTriggerNewTransaction($newTransaction){
    mtcCallPostRequest($newTransaction, 'new_transaction');
}

function mtcTriggerNewBlock($newBlock){
    mtcCallPostRequest($newBlock, 'new_block');
}

function mtcConnectedToNeighborNode($nodeIndex){
    mtcCallPostRequest(array('nodeIndex'=>$nodeIndex), 'connect_neighbor');
}

function mtcGetNeighborChainLength(){
    mtcCallPostRequest(array(), 'neighbor_chain_length');
}