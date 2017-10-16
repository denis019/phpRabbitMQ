<?php

namespace App\Http\Response\Formats;

use App\Http\Response\Response;
use SoapBox\Formatter\Formatter;

class JsonFormat implements FormatInterface
{
    /**
     * @param array $data
     * @return Response
     */
    public function response($data = []): Response
    {
        $data = array(
            'data' => $data
        );

        $formatter = Formatter::make($data, Formatter::ARR);

        return new Response($formatter->toJson(), 200, [
            'Content-Type' => 'application/json'
        ]);
    }
}