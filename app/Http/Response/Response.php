<?php

namespace App\Http\Response;


class Response
{
    protected $version;

    protected $statusCode;

    protected $content;

    protected $headers = [];

    public function __construct($content = '', $status = 200, $headers = array())
    {
        $this->setHeaders($headers);
        $this->setContent($content);
        $this->setStatusCode($status);
        $this->setProtocolVersion('1.0');
    }

    /**
     * Sets the HTTP protocol version (1.0 or 1.1).
     *
     * @param string $version The HTTP protocol version
     *
     * @return $this
     */
    public function setProtocolVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @param $code
     * @return $this
     */
    public function setStatusCode($code)
    {
        $this->statusCode = $code = (int)$code;

        return $this;
    }

    public function setContent($content)
    {
        if (null !== $content &&
            !is_string($content) &&
            !is_numeric($content) &&
            !is_callable(array(
                $content,
                '__toString'
            ))
        ) {
            throw new \UnexpectedValueException(sprintf('The Response content must be a string or object implementing __toString(), "%s" given.',
                gettype($content)));
        }

        $this->content = (string)$content;

        return $this;
    }

    /**
     * @param array $headers
     * @return $this
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;

        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    private function headersToString()
    {
        if (!$this->headers) {
            return '';
        }

        $max = max(array_map('strlen', array_keys($this->headers))) + 1;
        $content = '';
        ksort($this->headers);
        foreach ($this->headers as $name => $values) {
            $name = implode('-', array_map('ucfirst', explode('-', $name)));
            foreach ($values as $value) {
                $content .= sprintf("%-{$max}s %s\r\n", $name . ':', $value);
            }
        }

        return $content;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return
            sprintf('HTTP/%s %s', $this->version, $this->statusCode) . "\r\n" .
            $this->headersToString() . "\r\n" .
            $this->getContent();
    }

    /**
     * Sends HTTP headers and content.
     *
     * @return $this
     */
    public function send()
    {
        $this->sendHeaders();
        $this->sendContent();

        return $this;
    }

    /**
     * Sends HTTP headers.
     *
     * @return $this
     */
    public function sendHeaders()
    {
        // headers have already been sent by the developer
        if (headers_sent()) {
            return $this;
        }

        // headers
        foreach ($this->headers as $name => $value) {
            header($name.': '.$value, false, $this->statusCode);
        }

        // status
        header(sprintf('HTTP/%s %s', $this->version, $this->statusCode), true, $this->statusCode);

        return $this;
    }

    /**
     * Sends content for the current web response.
     *
     * @return $this
     */
    public function sendContent()
    {
        echo $this->content;

        return $this;
    }
}