<?php
namespace App\Http\Response\Formats;

use App\Http\Response\Response;

interface FormatInterface {
    public function response($data = []): Response;
}