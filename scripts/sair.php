<?php
    // Se não houver uma sessão iniciada, será iniciada uma nova.
    if(!isset($_SESSION)){
        session_start();
    }

    // Script com métodos para várias coisas.
    require_once "dados.php";
    $dados = new Dados("project_chat", "localhost", "root", "");

    // Alterando o status do usuário para offline.
    $dados->StatusUsuario($_SESSION["id_usuario"], 0);

    // Removendo sessão de login.
    unset($_SESSION['id_usuario']);

    // Redirecionado para a página inicial.
    header("location: ../index.php");
?>