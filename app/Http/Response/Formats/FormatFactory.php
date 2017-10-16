<?php
namespace App\Http\Response\Formats;

class FormatFactory {
    const JSON_RESPONSE = 'JSON';
    const XML_RESPONSE = 'XML';

    public static function make ($responseType = '') {

        switch ($responseType) {
            case self::JSON_RESPONSE:
                return new JsonFormat();
                break;
            case self::XML_RESPONSE:
                return new XmlFormat();
                break;
            default:
                return new JsonFormat();
        }
    }
}