<?php

namespace App\Workers\Consumers;

use App\Models\ModelBuilderTrait;
use App\Workers\Queue;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class EmailsConsumer implements ConsumerInterface
{
    use ModelBuilderTrait;

    const SEND_EMAILS_ACTION = 'sendEmails';

    public function listen()
    {
        $connection = new AMQPStreamConnection(
            rabbitmqConfig()['host'],
            rabbitmqConfig()['port'],
            rabbitmqConfig()['user'],
            rabbitmqConfig()['password']
        );

        $channel = $connection->channel();

        $channel->queue_declare(
            Queue::EMAILS_QUEUE,
            false,
            true,
            false,
            false
        );

        /**
         * don't dispatch a new message to a worker until it has processed and
         * acknowledged the previous one. Instead, it will dispatch it to the
         * next worker that is not still busy.
         */
        $channel->basic_qos(
        // prefetch size - prefetch window size in octets, null meaning "no specific limit"
            null,
            // prefetch count - prefetch window in terms of whole messages
            1,
            // global - global=null to mean that the QoS settings should apply per-consumer,
            // global=true to mean that the QoS settings should apply per-channel
            null
        );

        /**
         * indicate interest in consuming messages from a particular queue. When they do
         * so, we say that they register a consumer or, simply put, subscribe to a queue.
         * Each consumer (subscription) has an identifier called a consumer tag
         */
        $channel->basic_consume(
            Queue::EMAILS_QUEUE,
            // consumer tag - Identifier for the consumer, valid within the current channel. just string
            '',
            // no local - TRUE: the server will not send messages to the connection that published them
            false,
            // no ack, false - acks turned on, true - off.  send a proper acknowledgment from the worker,
            // once we're done with a task
            false,
            // exclusive - queues may only be accessed by the current connection
            false,
            // no wait - TRUE: the server will not respond to the method. The client should not wait for a reply method
            false,
            array($this, 'process')
        );

        while (count($channel->callbacks)) {
            echo "Waiting for incoming messages...\n";

            // Waiting for incoming messages
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }

    /**
     * process received request
     *
     * @param AMQPMessage $msg
     */
    public function process(AMQPMessage $msg)
    {
        $msgBody = json_decode($msg->getBody());
        $data = $msgBody->data;
        $action = $msgBody->action;
        $this->{$action}($data);

        /**
         * If a consumer dies without sending an acknowledgement the AMQP broker
         * will redeliver it to another consumer or, if none are available at the
         * time, the broker will wait until at least one consumer is registered
         * for the same queue before attempting redelivery
         */
        $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
    }

    /**
     * @param $data
     * @return $this
     */
    private function sendEmails($data)
    {
        echo "Email has been sent: " . $this->_emailModel()->sendEmail($data) . "\n";
    }
}