<?php

namespace App\Workers\Consumers;

interface ConsumerInterface
{
    public function listen();
}