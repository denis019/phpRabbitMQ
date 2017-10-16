<?php

if (!function_exists('rabbitmqConfig')) {
    function rabbitmqConfig()
    {
        return [
            'host' => 'localhost',
            'port' => 5672,
            'user' => 'admin',
            'password' => 'password',
        ];
    }
}
