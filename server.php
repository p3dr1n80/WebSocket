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
    // A porta do WS, não deve ser a mesma da aplicação.
    8080
);

// Iniciar o servidor e começar a escutar as conexões.
$server->run();