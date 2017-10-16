<?php

namespace App\Workers;

use App\Workers\Producers\ProducerInterface;

/**
 * Class WorkerDispatcher
 * @package App\Workers
 */
class WorkerDispatcher
{
    public static function dispatch($producer, $data, $action)
    {
        $message = [
            'data' => $data,
            'action' => $action
        ];

        /** @var ProducerInterface $sender */
        $sender = new $producer();
        $sender->execute(json_encode($message));
    }
}