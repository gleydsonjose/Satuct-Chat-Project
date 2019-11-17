<?php
    // Se não houver uma sessão iniciada, será iniciada uma nova
    if(!isset($_SESSION)){
        session_start();
    }

    // Script com métodos para várias coisas.
    require_once "dados.php";
    $dados = new Dados("project_chat", "localhost", "root", "");

    $id_usuario = addslashes(strip_tags(trim($_SESSION["id_usuario"])));
    $id_sala = $dados->BuscarSalaAtualUsuario($id_usuario)["id_sala"];

    // Método que retorna todas mensagens com o nome do usuário e data de postagem.
    echo json_encode($dados->BuscandoMensagens($id_sala));
?>