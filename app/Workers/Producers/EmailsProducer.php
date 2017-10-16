<?php

namespace App\Workers\Producers;

use App\Workers\Queue;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class EmailsProducer implements ProducerInterface
{
    /**
     * @param string $event Json string
     */
    public function execute(string $event)
    {
        $connection = new AMQPStreamConnection(
            rabbitmqConfig()['host'],
            rabbitmqConfig()['port'],
            rabbitmqConfig()['user'],
            rabbitmqConfig()['password']
        );

        $channel = $connection->channel();

        $channel->queue_declare(
            // queue - Queue names may be up to 255 bytes of UTF-8 characters
            Queue::EMAILS_QUEUE,
            // passive - can use this to check whether an exchange exists without modifying the server state
            false,
            // durable, make sure that RabbitMQ will never lose our queue if a crash occurs - the queue will survive a broker restart
            true,
            // exclusive - used by only one connection and the queue will be deleted when that connection closes
            false,
            // auto delete - queue is deleted when last consumer unsubscribes
            false
        );

        $msg = new AMQPMessage(
            $event,
            array('delivery_mode' => 2) #make message persistent, so it is not lost if server crashes or quits
        );

        $channel->basic_publish(
            $msg, #message
            '', #exchange
            Queue::EMAILS_QUEUE #routing key (queue)
        );

        $channel->close();
        $connection->close();
    }
}