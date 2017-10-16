<?php

namespace App\Http\Controllers;

use App\Models\ModelBuilderTrait;

class EmailsController extends AbstractController
{
    use ModelBuilderTrait;

    public function sendRabbitMQEmailAction()
    {
        return $this->_api(function () {

            $this->_rabbitMqEmailModel()->sendEmail($this->requestBody);

            return 'Email Has been sent';
        });
    }

    /**
     * @return $this
     */
    public function sendEmailAction()
    {
        return $this->_api(function () {
            $this->_emailModel()->sendEmail($this->requestBody);

            return 'Email Has been sent: ';
        });
    }
}