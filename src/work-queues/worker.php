<?php

use PhpAmqpLib\Connection\AMQPStreamConnection;

require_once __DIR__ . '/../../vendor/autoload.php';

## Create connection with Rabbit MQ Server
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');

## Open a channel from connection
$channel = $connection->channel();

## Declare queue For message source
$channel->queue_declare('hello', false, false, false, false);

echo "[*] Waiting for messages. To exit press CTRL+C\n";

$callback = function ($msg) {
    echo '[x] Received ', $msg->body, "\n";

    ## halt time with total '.' * 1 second  
    sleep(substr_count($msg->body, '.'));

    echo "[x] Done\n";
    $msg->ack(); // will send message ack(nowledgement) to Rabbit MQ.
};

$channel->basic_consume('hello', '', false, false, false, false, $callback);

while ($channel->is_open()) {
    $channel->wait();
}


/** 
 * TERMS IN THIS CHAPTER
 * 
 * Round-robin Dispatching
 * If there are 3 or more running worker, and the there  are 3 or many messages
 * the message will be sent to each worker in sequence, and make sure all the worker
 * receive same number of messages at once.
 * 
 * Message Acknowledgment
 * When the message receiver is busy, and there's a problem while doin' the task (channel closed, TCP timeout)
 * the message will lost, With Message Acknowledgement the message that not received properly to receiver
 * will be sending back to queue and will be send to another receiver ASAP. 
 */
