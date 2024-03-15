<?php

namespace App;

use Ratchet\ConnectionInterface;
use Ratchet\WebSocket\MessageComponentInterface;

class SystemChat implements MessageComponentInterface
{
    protected $client;

    public function __construct()
    {
        // Iniciar o objeto que deve armazenar os clientes conectados.
        $this->client = new \SplObjectStorage;

    }

    //Abrir a conexão para o novo cliente
    function onOpen(ConnectionInterface $conn)
    {
        //Adicionar o cliente a lista
        $this->client->attach($conn);

        echo "\n Nova conexão: {$conn->resourceId} \n";
    }

    function onClose(ConnectionInterface $conn)
    {
        //Fechar a conexão e retirar o cliente da lista
        $this->client->detach($conn);

        echo "\n Usuário {$conn->resourceId} desconectou. \n";
    }

    //Função que será chamada caso ocorra algum erro no WS
    function onError(ConnectionInterface $conn, \Exception $e)
    {
        //Fechar a conexão do cliente
        $conn->close();

        echo "\n Ocorreu um erro {$e->getMessage()} \n";
    }

    //Enviar mensagens para os usuários conectados
    public function onMessage(ConnectionInterface $from, $msg)
    {
        //Percorrer a lista de usuários conectados
        foreach ($this->client as $client) {

            //Não enviar a mensagem para o usuário que enviou a mensagem
            if ($client != $from){
                //Enviar as mensagens para os usuários
                $client->send($msg);
            }
        }

        echo "\n Usuário {$from->resourceId} enviou uma mensagem \n";
    }
}