<?php

namespace App\Models;

use App\Workers\Consumers\EmailsConsumer;
use App\Workers\Producers\EmailsProducer;
use App\Workers\WorkerDispatcher;

class RabbitMQEmailModel extends AbstractModel implements EmailInterface
{
    public function sendEmail($data)
    {
        WorkerDispatcher::dispatch(
            EmailsProducer::class,
            $data,
            EmailsConsumer::SEND_EMAILS_ACTION
        );
    }
}