<?php

namespace GlebecV;

class Request
{
    protected $method;
    protected $uri;
    protected $params = [];
    protected $headers = [];

    public function __construct($method, $uri, $headers = [])
    {
        $this->method = $method;
        $this->headers = $headers;

        $uriArr = explode('?', $uri);
        if (isset($uriArr[1])) {
            $params = $uriArr[1];
            parse_str($params, $this->params);
        }
        $this->uri = $uriArr[0];
    }

    public static function createRequest($header)
    {
        $lines = explode(PHP_EOL, $header);
        list($method, $uri) = explode(' ', array_shift($lines));

        $headers = [];
        foreach ($lines as $line) {
            $line = trim($line);
            if (strpos($line, ': ') !== false) {
                list($key, $value) = explode(': ', $line);
                $headers[$key] = $value;
            }
        }
        return new static($method, $uri, $headers);
    }

    public function param($key)
    {
        return $this->params[$key] ?? '';
    }

}