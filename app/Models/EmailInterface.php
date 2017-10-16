<?php
namespace App\Models;

/**
 * Interface EmailInterface
 * @package App\Models
 */
interface EmailInterface
{
    public function sendEmail($data);
}