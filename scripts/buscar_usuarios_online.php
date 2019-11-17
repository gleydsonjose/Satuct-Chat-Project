<?php
    // Script com métodos para várias coisas.
    require_once "dados.php";
    $dados = new Dados("project_chat", "localhost", "root", "");

    // Retornado todos usuários online em formato JSON.
    echo json_encode($dados->BuscandoUsuariosOnline());
?>