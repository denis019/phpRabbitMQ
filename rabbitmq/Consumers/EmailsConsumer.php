<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Workers\Consumers\EmailsConsumer;

$worker = new EmailsConsumer();

$worker->listen();