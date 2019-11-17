<?php

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class RatchetMetodos implements MessageComponentInterface
{
    // Variável para guardar dados dos usuários usando o SplObjectStorage.
    protected $usuarios;

    // construtor do RatchetMetodos
    public function __construct()
    {
        // Iniciamos a coleção que irá armazenar os usuários conectados
        $this->usuarios = new \SplObjectStorage;
    }

    // Método que será chamado quando um usuário se conectar ao websockets
    public function onOpen(ConnectionInterface $conn)
    {
        // Adicionando o usuário na coleção
        $this->usuarios->attach($conn);
    }

    // Método que será chamado quando um usuário enviar dados ao websockets
    public function onMessage(ConnectionInterface $from, $data)
    {
        // Decodificando dados em formato json para uma variável
        $data = json_decode($data);

        // Passando pelos usuários conectados e enviando a mensagem para cada um deles
        foreach ($this->usuarios as $usuario) {
            $usuario->send(json_encode($data));
        }
    }

    // Método que será chamado quando o usuário desconectar do websockets
    public function onClose(ConnectionInterface $conn)
    {
        // Retirando o usuário da coleção
        $this->usuarios->detach($conn);
    }

    // Método que será chamado caso ocorra algum erro no websockets
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        // Fechando conexão do cliente
        $conn->close();
    }
}