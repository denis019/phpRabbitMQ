<?php

namespace App\Http\Response\Formats;

use App\Http\Response\Response;
use SoapBox\Formatter\Formatter;

class XmlFormat implements FormatInterface
{
    /**
     * @param array $data
     * @return Response
     */
    public function response($data = []): Response
    {
        $formatter = Formatter::make($data, Formatter::ARR);

        return new Response($formatter->toXml(), 200, [
            'Content-Type' => 'text/xml'
        ]);
    }
}