<?php

namespace GlebecV;

class Response
{
    protected $body;
    protected $status = 200;
    protected $headers = [];
    protected $statusCodes = [
        200 => 'OK',
        404 => 'Error',
    ];

    public function __construct($body, $status = null)
    {
        if (!is_null($status)) {
            $this->status = $status;
        }
        $this->body = $body;
        $this->header('Date', gmdate('D, d M Y H:i:s T'));
        $this->header('Content-Type', 'text/html; charset=utf8');
        $this->header('Server', 'Glebec-PHPServer');
    }

    public function header($key, $value)
    {
        $this->headers[ucfirst($key)] = $value;
    }

    public function buildHeader()
    {
        $lines = [];
        $lines[] = "HTTP/1.1 ". $this->status . " " . $this->statusCodes[$this->status];
        foreach ($this->headers as $k => $v) {
            $lines[] = $k . ": ". $v;
        }
        return implode(PHP_EOL, $lines) . PHP_EOL . PHP_EOL;
    }

    public function __toString()
    {
        return $this->buildHeader() . $this->body . PHP_EOL;
    }

}