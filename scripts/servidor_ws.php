<?php
    use Ratchet\Http\HttpServer;
    use Ratchet\Server\IoServer;
    use Ratchet\WebSocket\WsServer;

    // Incluindo biblioteca e classe do ws
    require "vendor/autoload.php";
    require "ratchet_metodos.php";

    // Iniciando conexão
    $servidor = IoServer::factory(
        new HttpServer(
            new WsServer(
                new RatchetMetodos()
            )
        ),
        8080
    );

    $servidor->run();
?>