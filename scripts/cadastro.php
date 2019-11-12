<?php
    // Script com métodos para várias coisas.
    require_once 'dados.php'; 
    $cadastro = new Dados("project_chat", "localhost", "root", "");

    // Array para guardar mensagens e variáveis para os dados do usuário
    $mensagens = [];
    $nome_usuario = addslashes(strip_tags(trim($_POST["nome_usuario"])));
    $senha = addslashes(strip_tags(trim($_POST["senha"])));
    $repita_senha = addslashes(strip_tags(trim($_POST["repita_senha"])));

    // Verificando se o campo nome de usuário está vazio, se sim, uma mensagem de erro será adicionado ao array mensagens, se não, a mensagem será removida do array.
    if(empty($nome_usuario)){
        $mensagens["nome_usuario_vazio"] = "<span class='mensagem-erro msg-erro-cadastro-1'>Preencha o campo Nome de usuário</span>";
    }else{
        unset($mensagens["nome_usuario_vazio"]);
    }

    // Verificando se o campo nome de usuário já existe no banco de dados, se sim, uma mensagem de erro será adicionado ao array mensagens, se não, a mensagem será removida do array.
    if($cadastro->VerificarNomeUsuario($nome_usuario)){
        $mensagens["nome_usuario_utilizado"] = "<span class='mensagem-erro msg-erro-cadastro-2'>Este nome de usuário já existe</span>";
    }else{
        unset($mensagens["nome_usuario_utilizado"]);
    }

    // Verificando se o nome de usuário é menor ou igual a 6 caracteres, se sim, uma mensagem de erro será adicionado ao array mensagens, se não, a mensagem será removida do array.
    if(strlen($nome_usuario) <= 6){
        $mensagens["nome_usuario_caractere_erro"] = "<span class='mensagem-erro msg-erro-cadastro-3'>O nome de usuário precisa ter mais que 6 caracteres</span>";
    }else{
        unset($mensagens["nome_usuario_caractere_erro"]);
    }

    // Verificando se o campo senha está vazio, se sim, uma mensagem de erro será adicionado ao array mensagens, se não, a mensagem será removida do array.
    if(empty($senha)){
        $mensagens["senha_vazio"] = "<span class='mensagem-erro msg-erro-cadastro-4'>Preencha o campo Senha</span>";
    }else{
        unset($mensagens["senha_vazio"]);
    }

    // Verificando se as senhas são diferentes, se sim, uma mensagem de erro será adicionado ao array mensagens, se não, a mensagem será removida do array.
    if(strcmp($senha, $repita_senha) != 0){
        $mensagens["senhas_diferentes"] = "<span class='mensagem-erro msg-erro-cadastro-5'>As senhas não correspondem</span>";
    }else{
        unset($mensagens["senhas-diferentes"]);
    }
    
    // Verificando se a senha é menor ou igual a 6 caracteres, se sim, uma mensagem de erro será adicionado ao array mensagens, se não, a mensagem será removida do array.
    if(strlen($senha) <= 6){
        $mensagens["senha_caractere_erro"] = "<span class='mensagem-erro msg-erro-cadastro-6'>A senha precisa ter mais que 6 caracteres</span>";

    }else{
        unset($mensagens["senha_caractere_erro"]);
    }

    // Verificando se o campo repita a senha está vazio, se sim, uma mensagem de erro será adicionado ao array mensagens, se não, a mensagem será removida do array.
    if(empty($repita_senha)){
        $mensagens["repita_senha_vazio"] = "<span class='mensagem-erro msg-erro-cadastro-7'>Preencha o campo Repita a senha</span>";
    }else{
        unset($mensagens["repita_senha_vazio"]);
    }

    if(count($mensagens) > 0){

    }

    // Se não houver mensagens de erro, o usuário será cadastrado e uma mensagem de sucesso será adicionada ao array mensagens.
    // Se houver mensagens de erro, será removida a mensagem de sucesso do array, e vai ser retornado todas mensagens em formato JSON.
    if(count($mensagens) == 0){
        $cadastro->Cadastrar($nome_usuario, $senha);

        $mensagens["sucesso_cadastro"] = "<span class='mensagem-sucesso msg-sucesso-cadastro'>Você foi cadastrado com sucesso</span>";
        echo json_encode($mensagens);
    }else{
        unset($mensagens["sucesso_cadastro"]);

        echo json_encode($mensagens);
    }
?>