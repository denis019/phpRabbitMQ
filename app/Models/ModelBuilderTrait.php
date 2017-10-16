<?php

namespace App\Models;

trait ModelBuilderTrait
{
    /**
     * @return EmailModel
     */
    protected function _emailModel()
    {
        return new EmailModel();
    }

    /**
     * @return RabbitMQEmailModel
     */
    protected function _rabbitMqEmailModel()
    {
        return new RabbitMQEmailModel();
    }
}