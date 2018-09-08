<?php

namespace GlebecV;

class Server
{
    protected $host;
    protected $port;
    protected $socket;

    public function __construct($host, $port)
    {
        $this->host = $host;
        $this->port = $port;
        $this->createSocket();
        $this->bind();
    }

    protected function createSocket()
    {
        $this->socket = socket_create(AF_INET, SOCK_STREAM, 0);
    }

    protected function bind()
    {
        socket_set_option($this->socket, SOL_SOCKET, SO_REUSEADDR, 1);
        socket_bind($this->socket, $this->host, $this->port);
    }

    public function listen($callback)
    {
        while (true) {
            socket_listen($this->socket);

            $client = socket_accept($this->socket);
            if (!$client) {
                socket_close($client);
                continue;
            }

            $text = trim(socket_read($client, 1024));

            if (!empty($text)) {
                $request = Request::createRequest($text);
                $response = call_user_func($callback, $request);

                socket_write($client, $response, strlen($response));


                echo (string)$response;
            }
            socket_close($client);
        }
    }

}