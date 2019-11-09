<?php
    // Se não houver uma sessão iniciada, será iniciada uma nova.
    if(!isset($_SESSION)){
        session_start();
    }

    // Removendo sessão de login.
    unset($_SESSION['id_usuario']);

    // Redirecionado para a página inicial.
    header("location: ../area-usuario.php");
?>