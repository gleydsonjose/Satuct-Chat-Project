<?php
    // Script com métodos para várias coisas.
    require_once "dados.php";
    $dados = new Dados("project_chat", "localhost", "root", "");

    $id_sala = addslashes(strip_tags(trim($_POST["id_sala"])));

    // Retornado todos usuários online numa sala específica em formato JSON.
    echo json_encode($dados->BuscandoUsuariosOnlineSala($id_sala));
?>