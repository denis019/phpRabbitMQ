<?php

namespace App\Models;

class EmailModel extends AbstractModel
{
    public function sendEmail($data)
    {
        sleep(5);
        return $data->message;
    }
}