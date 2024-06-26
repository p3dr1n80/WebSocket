<?php

namespace App;

use Ratchet\ConnectionInterface;
use Ratchet\WebSocket\MessageComponentInterface;

class SystemChat implements MessageComponentInterface
{
    protected $client;

    public function __construct()
    {
        $this->client = new \SplObjectStorage;
    }

    function onOpen(ConnectionInterface $conn)
    {
        $this->client->attach($conn);

        echo "\n Nova conexão: {$conn->resourceId} \n";
    }

    function onClose(ConnectionInterface $conn)
    {
        $this->client->detach($conn);

        echo "\n Usuário {$conn->resourceId} desconectou. \n";
    }

    function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();

        echo "\n Ocorreu um erro {$e->getMessage()} \n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        foreach ($this->client as $client) {

            if ($client != $from){
                $client->send($msg);
            }
        }

        echo "\n Usuário {$from->resourceId} enviou uma mensagem \n";
    }
}
