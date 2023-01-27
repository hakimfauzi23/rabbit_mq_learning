<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

## Create connection with Rabbit MQ Server
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');

## Open a channel from connection
$channel = $connection->channel();

## Declare queue For message destination (will auto-create if new)
$channel->queue_declare('hello', false, false, false, false);

# Get Data From Command Arguments => php new_task.php "This is message data"
$data = implode(' ', array_slice($argv, 1));
if (empty($data)) {
    $data = "Hello World";
}

$msg = new AMQPMessage($data);
$channel->basic_publish($msg, '','hello');

echo '[x] Sent ', $data, "\n";

