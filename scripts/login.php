<?php
    // Se não houver uma sessão iniciada, será iniciada uma nova.
    if(!isset($_SESSION)){
        session_start();
    }

    // Script com métodos para várias coisas.
    require_once 'dados.php'; 
    $login = new Dados("project_chat", "localhost", "root", "");

    // Array para guardar mensagens de erro e variáveis para os dados do usuário
    $mensagens_erro = [];
    $nome_usuario = addslashes(strip_tags(trim($_POST["nome_usuario"])));
    $senha = addslashes(strip_tags(trim($_POST["senha"])));

    // Verificando se o campo nome de usuário está vazio, se sim, uma mensagem de erro será adicionado ao array mensagens de erro, se não, a mensagem será removida do array.
    if(empty($nome_usuario)){
        $mensagens_erro["nome_usuario_vazio"] = "<span class='mensagem-erro msg-erro-login-1'>Preencha o campo Nome de usuário</span>";

    }else{
        unset($mensagens_erro["nome_usuario_vazio"]);
    }

    // Verificando se o nome do usuário não existe no banco de dados, se sim, uma mensagem de erro será adicionado ao array mensagens de erro, se não, a mensagem será removida do array.
    if($login->Login($nome_usuario, $senha) === "nome-usuario-incorreto"){
        $mensagens_erro["nome_usuario_invalido"] = "<span class='mensagem-erro msg-erro-login-2'>Nome de usuário inválido</span>";
    }else{
        unset($mensagens_erro["nome_usuario_invalido"]);
    }

    // Verificando se o campo senha está vazio, se sim, uma mensagem de erro será adicionado ao array mensagens de erro, se não, a mensagem será removida do array.
    if(empty($senha)){
        $mensagens_erro["senha_vazio"] = "<span class='mensagem-erro msg-erro-login-3'>Preencha o campo Senha</span>";
    }else{
        unset($mensagens_erro["senha_vazio"]);
    }

    // Verificando se a senha do usuário está incorreta, se sim, uma mensagem de erro será adicionado ao array mensagens de erro, se não, a mensagem será removida do array.
    if($login->Login($nome_usuario, $senha) === "senha-incorreta"){
        $mensagens_erro["senha_incorreta"] = "<span class='mensagem-erro msg-erro-login-4'>Senha inválida</span>";
    }else{
        unset($mensagens_erro["senha_incorreta"]);
    }

    // Se não houver mensagens de erro, será realizado o login e vai ser retornado um aviso de sucesso.
    // Se houver mensagens de erro, será retornado todas mensagens em formato JSON.
    if(count($mensagens_erro) == 0){
        $login->Login($nome_usuario, $senha);
        echo "sucesso";
    }else{
        echo json_encode($mensagens_erro);
    }
?>