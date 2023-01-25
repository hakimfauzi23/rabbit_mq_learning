<?php

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

require_once __DIR__ . '/../../vendor/autoload.php';

## Create connection with Rabbit MQ Server
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');

## Open a channel from connection
$channel = $connection->channel();

## Declare queue For message destination (will auto-create if new)
$channel->queue_declare('hello', false, false, false, false);

## Create a message with AMQPMessage 
$msg = new AMQPMessage('Hello World!');

## Publish Message with opened channel to destinate queue 'hello'
$channel->basic_publish($msg, '', 'hello');

echo " [x] Sent 'Hello World!' :", $i, "\n";


$channel->close();
$connection->close();
