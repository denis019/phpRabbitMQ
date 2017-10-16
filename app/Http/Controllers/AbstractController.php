<?php

namespace App\Http\Controllers;

use App\Http\Response\Formats\FormatFactory;
use App\Http\Response\ResponseTrait;
use DateTime;

abstract class AbstractController
{
    use ResponseTrait;

    /** @var  string */
    protected $requestBody;

    /** @var  string */
    protected $responseFormat;

    /** @var  string Y-m-d */
    protected $requestDate;

    public function __construct()
    {
        $this->setResponseFormat();
        $this->setRequestBody();
        $this->setRequestDate();
    }

    /**
     * @param \Closure $callback
     * @return $this
     */
    protected function _api(\Closure $callback)
    {
        try {
            $args = func_get_args();

            $data = call_user_func_array($callback, $args);

            return $this->successResponse($data, $this->responseFormat);
        } catch (\Exception $ex) {
            return $this->errorsResponse(array(
                $ex->getMessage()
            ));
        }
    }

    public function setResponseFormat()
    {
        $this->responseFormat = FormatFactory::JSON_RESPONSE;

        if ($_SERVER['HTTP_ACCEPT'] === 'text/xml') {
            $this->responseFormat = FormatFactory::XML_RESPONSE;
        }
    }

    public function setRequestBody()
    {
        $this->requestBody = json_decode(file_get_contents('php://input'));
    }

    public function setRequestDate()
    {
        $date = new DateTime();
        $this->requestDate = $date->format('Y-m-d');
    }
}