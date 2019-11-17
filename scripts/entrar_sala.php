<?php
    // Se não houver uma sessão iniciada, será iniciada uma nova.
    if(!isset($_SESSION)){
        session_start();
    }

    // Script com métodos para várias coisas.
    require_once 'dados.php'; 
    $dados = new Dados("project_chat", "localhost", "root", "");

    $id_sala = addslashes(strip_tags(trim($_POST["id_sala"])));
    $id_usuario = addslashes(strip_tags(trim($_SESSION["id_usuario"])));

    // Verificando se a sala atual do usuário é diferente da que está no banco de dados, se sim, ele entrará na sala, se não, será mostrado uma mensagem de erro.
    if($dados->BuscarSalaAtualUsuario($id_usuario)["id_sala"] !== $id_sala){
        $dados->EntrarNaSala($id_sala, $id_usuario);

        echo "sucesso";
    }else{
        echo "<div class='mensagens-aviso-chat sala-msg-erro'><span class='mensagem-erro'>Você já está nesta sala</span></div>";
    }
?>