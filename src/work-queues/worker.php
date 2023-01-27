<?php

use PhpAmqpLib\Connection\AMQPStreamConnection;

require_once __DIR__ . '/../../vendor/autoload.php';

## Create connection with Rabbit MQ Server
$connection = new AMQPStreamConnection('localhost',5672,'guest','guest');

## Open a channel from connection
$channel = $connection->channel();

## Declare queue For message source
$channel->queue_declare('hello',false,false,false,false);

echo "[*] Waiting for messages. To exit press CTRL+C\n";

$callback = function($msg) {
    echo '[x] Received ', $msg->body, "\n";

    ## halt time with total '.' * 1 second  
    sleep(substr_count($msg->body,'.'));
    
    echo "[x] Done\n";
};

$channel->basic_consume('hello','',false,true,false,false,$callback);

while ($channel->is_open()){
    $channel->wait();
}


/** 
 * TERMS IN THIS CHAPTER
 * 
 * Round-robin Dispatching
 * If there are 3 or more running worker, and the there  are 3 or many messages
 * the message will be sent to each worker in sequence, and make sure all the worker
 * receive same number of messages at once.
*/