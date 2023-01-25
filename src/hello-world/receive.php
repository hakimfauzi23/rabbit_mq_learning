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

## Callback for retrieving message 
$callback = function($msg) {
    echo '[x] Received ', $msg->body, "\n";
};

## Consuming Message from 'hello' queue wiht $callback as format
$channel->basic_consume('hello','',false,true,false,false,$callback);


while ($channel->is_open()){
    $channel->wait();
}