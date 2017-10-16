<?php

namespace App\Workers\Producers;

interface ProducerInterface
{
    public function execute(string $event);
}