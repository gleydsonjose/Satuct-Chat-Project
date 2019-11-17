<?php
    // Se não houver uma sessão iniciada, será iniciada uma nova
    if(!isset($_SESSION)){
        session_start();
    }
    
    // Script com métodos para várias coisas.
    require_once 'dados.php'; 
    $dados = new Dados("project_chat", "localhost", "root", "");

    $mensagem = addslashes(strip_tags(trim($_POST["mensagem"])));
    $id_usuario = addslashes(strip_tags(trim($_SESSION["id_usuario"])));
    $id_sala = $dados->BuscarSalaAtualUsuario($id_usuario)["id_sala"];
    $mensagem_vazio = null;

    // Se o campo de mensagem estiver vázio, será mostrado uma mensagem de erro, se não ela será removida
    if(empty($mensagem)){
        $mensagem_vazio = "<div class='mensagens-aviso-chat mensagem_vazio'><span class='mensagem-erro'>Não é possível enviar uma mensagem em branco</span></div>";
    }else{
        $mensagem_vazio = null;
    }

    // Se não houver mensagem de erro, a mensagem será enviada, se não, a mensagem será mostrada na página.
    if($mensagem_vazio === null){
        $dados->EnviarMensagem($mensagem, $id_usuario, $id_sala);
    }else{
        echo $mensagem_vazio;
    }
?>