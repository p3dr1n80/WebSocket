<?php

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use App\SystemChat;

include_once 'vendor/autoload.php';

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new SystemChat()
        )
    ),
    8080
);

$server->run();
