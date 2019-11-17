<?php
    // Se não houver uma sessão iniciada, será iniciada uma nova.
    if(!isset($_SESSION)){
        session_start();
    }

    // Se existir uma sessão de login o usuário será redirecionado para a página do chat.
    if(isset($_SESSION["id_usuario"])){
        header("location: chat.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Arimo&display=swap" rel="stylesheet">
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.8.2/css/all.css' integrity='sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay' crossorigin='anonymous'>
    <link rel="stylesheet" href="estilo.css?<?= time() ?>">
    <title>Área usuário</title>
</head>
<body>
    <section id="area-usuario">
        <div id="area-usuario-btn-group">
            <button type="button" class="ativo" id="btn-area-login"><i class="fas fa-user"></i>Login</button>
            <button type="button" id="btn-area-cadastro"><i class="fas fa-user-plus"></i>Cadastro</button>
        </div>

        <div id="login-form">
            <div id="form-group">
                <h4>Faça já seu login</h4>
            </div>

            <div id="form-group">
                <label for="nome-usuario-login"><i class="far fa-user"></i>Nome de usuário</label>
                <input type="text" id="nome-usuario-login" maxlength="50" v-model="log.nome_usuario" @keyup.enter="EnviarLogin">
            </div>

            <div id="form-group">
                <label for="senha-login"><i class="fas fa-lock"></i>Senha</label>
                <input type="password" id="senha-login" maxlength="30" v-model="log.senha" @keyup.enter="EnviarLogin">
            </div>

            <button type="button" id="entrar" v-on:click="EnviarLogin"><i class="fas fa-user"></i>Entrar</button>

            <div id="div-gif-loader" class="gif-loader d-none">
                <img src="imagens/ajax-loader.gif">
            </div>
        </div>

        <div id="cadastro-form" class="d-none">
            <div id="form-group">
                <h4>Realize seu cadastro</h4>
            </div>

            <div id="form-group">
                <label for="nome-usuario-cadastro"><i class="far fa-user"></i>Nome de usuário</label>
                <input type="text" id="nome-usuario-cadastro" maxlength="50" v-model="cad.nome_usuario" @keyup.enter="EnviarCadastro">
            </div>

            <div id="form-group">
                <label for="senha-cadastro"><i class="fas fa-lock"></i>Senha</label>
                <input type="password" id="senha-cadastro" maxlength="30" v-model="cad.senha" @keyup.enter="EnviarCadastro">
            </div>

            <div id="form-group">
                <label for="repita-senha-cadastro"><i class="fas fa-key"></i>Repita a senha</label>
                <input type="password" id="repita-senha-cadastro" maxlength="30" v-model="cad.repita_senha" @keyup.enter="EnviarCadastro">
            </div>

            <button type="button" id="cadastrar" v-on:click="EnviarCadastro"><i class="fas fa-user-plus"></i>Cadastrar</button>

            <div id="div-gif-loader" class="gif-loader d-none">
                <img src="imagens/ajax-loader.gif">
            </div>
        </div>
    </section>

    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/vue@2.6.0"></script> -->
    <script src="script_principal.js"></script>
</body>
</html>