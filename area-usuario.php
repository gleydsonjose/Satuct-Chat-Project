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
    <style>
        *{
            margin: 0px;
            padding: 0px;
            outline: 0;
            font-family: 'Arimo', sans-serif;
        }

        body{
            background-color: #272727;
            display: flex;
            flex-flow: row nowrap;
            justify-content: center;
        }

        .d-none{
            display: none !important;
        }

        .ativo{
            background-color: #f0f0f0 !important;
            border-left: none !important;
            border-right: none !important;
        }

        .ativo:hover{
            opacity: 1 !important;
        }

        #area-usuario{
            margin-top: 7%;
            background-color: #f0f0f0;
            width: 370px;
            border-radius: 1%;
            box-shadow: 0 0 2px 0px #cecece;
            border: 1px solid #cecece;
        }

        #area-usuario-btn-group{
            width: 100%;
            display: flex;
            flex-flow: row wrap;
            margin-bottom: 25px;
            border-top-left-radius: 1%;
            border-top-right-radius: 1%;
        }

        #btn-area-login{
            width: 50%;
            text-align: center;
            padding: 9px 0;
            background-color: #cecece;
            border-right: 1px solid #cecece;
            font-size: 11pt;
        }

        #btn-area-cadastro{
            width: 50%;
            text-align: center;
            padding: 9px 0;
            background-color: #cecece;
            border-left: 1px solid #cecece;
            font-size: 11pt;
        }

        #btn-area-login:hover, #btn-area-cadastro:hover{
            opacity: 0.8;
            transition: ease-in 0.15s;
        }

        #btn-area-login i, #btn-area-cadastro i{
            color: #29b37e;
        }

        #login-form, #cadastro-form{
            display: flex;
            flex-flow: column nowrap;
            width: 100%;
            margin-top: 10%;
            margin-bottom: 2%;
        }

        #login-form h4, #cadastro-form h4{
            border-left: 1px solid #29b37e;
            color: #29b37e;
            padding-left: 8px;
            margin-bottom: 4% !important;
            font-weight: normal;
        }

        #form-group{
            width: auto;
            display: flex;
            flex-flow: column nowrap;
            margin-bottom: 15px;
            padding: 0 25px;
        }

        #entrar, #cadastrar{
            margin-top: 2%;
            margin-bottom: 5%;
            padding: 8px 11px;;
            background-color: #29b37e;
            border-radius: 3px;
            color: #fff;
        }

        .mensagem-erro{
            color: #da1313;
            font-size: 11pt;
            text-align: center;
            margin-bottom: 8px;
        }

        .mensagem-sucesso{
            color: #29b37e;
            font-size: 11pt;
            text-align: center;
            margin-bottom: 8px;
        }

        .borda-erro{
            border: 1px solid #da1313 !important;
        }

        .borda-erro:focus{
            box-shadow: none !important;
        }

        #div-gif-loader{
            display: flex;
            flex-flow: row nowrap;
            justify-content: center;
            margin-bottom: 3%;
        }

        input{
            padding: 6px 5px;
            border: 1px solid #b5b6b5;
            border-radius: 5px;
            background-color: #fff;
            outline: none;
        }

        label{
            color: #3f3f3f;
            margin-bottom: 7px;
            font-size: 11pt;
        }

        input:focus{
            border: 1px solid #29b37e;
            box-shadow: 0px 0px 3px 0px #29b37e;
            transition: ease-in 0.15s;
        }

        button{
            border: none;
            align-self: center;
            color: #2c2c2c;
        }

        button:hover{
            opacity: 0.9;
            transition: ease-in 0.15s;
            cursor: pointer;
        }

        button i{
            color: #fff;
        }

        i{
            margin-right: 6px;
            color: #29b37e;
        }
    </style>
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
                <input type="text" id="nome-usuario-login" maxlength="50" v-model="log.nome_usuario">
            </div>

            <div id="form-group">
                <label for="senha-login"><i class="fas fa-lock"></i>Senha</label>
                <input type="password" id="senha-login" maxlength="30" v-model="log.senha">
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
                <input type="text" id="nome-usuario-cadastro" maxlength="50" v-model="cad.nome_usuario">
            </div>

            <div id="form-group">
                <label for="senha-cadastro"><i class="fas fa-lock"></i>Senha</label>
                <input type="password" id="senha-cadastro" maxlength="30" v-model="cad.senha">
            </div>

            <div id="form-group">
                <label for="repita-senha-cadastro"><i class="fas fa-key"></i>Repita a senha</label>
                <input type="password" id="repita-senha-cadastro" maxlength="30" v-model="cad.repita_senha">
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
    <script>
        $(function(){
            // Quando for clicado no botão login, será mostrado a área de login e removido a de cadastro.
            $("#btn-area-login").click(function(){
                $("#btn-area-cadastro").removeClass("ativo");
                $("#cadastro-form").addClass("d-none");
                $("#btn-area-login").addClass("ativo");
                $("#login-form").removeClass("d-none");

                // Se existir alguma mensagem de erro na tela de cadastro, ela será removida.
                if($(".msg-erro-cadastro-1").length || $(".msg-erro-cadastro-2").length || $(".msg-erro-cadastro-3").length || $(".msg-erro-cadastro-4").length || $(".msg-erro-cadastro-5").length || $(".msg-erro-cadastro-6").length || $(".msg-erro-cadastro-7").length){
                    $("#nome-usuario-cadastro").removeClass("borda-erro");
                    $("#senha-cadastro").removeClass("borda-erro");
                    $("#repita-senha-cadastro").removeClass("borda-erro");

                    $(".msg-erro-cadastro-1").remove();
                    $(".msg-erro-cadastro-2").remove();
                    $(".msg-erro-cadastro-3").remove();
                    $(".msg-erro-cadastro-4").remove();
                    $(".msg-erro-cadastro-5").remove();
                    $(".msg-erro-cadastro-6").remove();
                    $(".msg-erro-cadastro-7").remove();
                }

                // Se existir uma mensagem de sucesso na tela de cadastro, ela será removida.
                if($(".msg-sucesso-cadastro").length){
                    $(".msg-sucesso-cadastro").remove();
                }
            })

            // Quando for clicado no botão cadastro, será mostrado a área de cadastro e removido a de login.
            $("#btn-area-cadastro").click(function(){
                $("#btn-area-login").removeClass("ativo");
                $("#login-form").addClass("d-none");
                $("#btn-area-cadastro").addClass("ativo");
                $("#cadastro-form").removeClass("d-none");

                // Se existir alguma mensagem de erro na tela de login, ela será removida.
                if($(".msg-erro-login-1").length || $(".msg-erro-login-2").length || $(".msg-erro-login-3").length || $(".msg-erro-login-4").length){
                    $("#nome-usuario-login").removeClass("borda-erro");
                    $("#senha-login").removeClass("borda-erro");

                    $(".msg-erro-login-1").remove();
                    $(".msg-erro-login-2").remove();
                    $(".msg-erro-login-3").remove();
                    $(".msg-erro-login-4").remove();
                }
            })

            // Iniciando uma instância Vue.JS
            var login = new Vue({
                // Ligando ao elemento login-form
                el: "#login-form",

                // Inicializando um array para dados do usuário e uma variável com URL.
                data: {
                    log: {nome_usuario: "", senha: ""},
                    urlPost: "http://localhost/Chat-Project/scripts/login.php"
                },

                methods: {
                    EnviarLogin: function () {
                        // Quando o botão login for clicado, o gif de loading será mostrado.
                        $(".gif-loader").removeClass("d-none");

                        // Array com dados do usuário
                        var log_form = this.formData(this.log);

                        // Axios vai enviar para a URL fornecida, os dados do usuário para verificações antes de ter o login realizado.
                        axios.post(this.urlPost, log_form)
                            .then(function(resposta){
                                /* -------------- Nome de usuário -------------- */
                                // Se não existir essa mensagem, ela será mostrada.
                                // OBS: Isso é para evitar repetição da mesma mensagem.
                                if(!$(".msg-erro-login-1").length){
                                    $(".gif-loader").after(resposta.data.nome_usuario_vazio);
                                }

                                // Se não existir no array a mensagem de campo nome de usuário vazio, ela será removida da página.
                                if(!resposta.data.nome_usuario_vazio){
                                    $(".msg-erro-login-1").remove();
                                }

                                // Se não existir essa mensagem, ela será mostrada.
                                // OBS: Isso é para evitar repetição da mesma mensagem.
                                if(!$(".msg-erro-login-2").length){
                                    $(".gif-loader").after(resposta.data.nome_usuario_invalido);
                                }

                                // Se não existir no array a mensagem de campo nome de usuário inválido, ela será removida da página.
                                if(!resposta.data.nome_usuario_invalido){
                                    $(".msg-erro-login-2").remove();
                                }

                                // Se existir alguma mensagem de erro do campo nome de usuário, o campo terá a borda vermelha, se não, a borda será removida.
                                if($(".msg-erro-login-1").length || $(".msg-erro-login-2").length){
                                    $("#nome-usuario-login").addClass("borda-erro");
                                }else{
                                    $("#nome-usuario-login").removeClass("borda-erro");
                                }
                                /* -------------- Nome de usuário -------------- */

                                /* -------------- Senha -------------- */
                                // Se não existir essa mensagem, ela será mostrada.
                                // OBS: Isso é para evitar repetição da mesma mensagem.
                                if(!$(".msg-erro-login-3").length){
                                    $(".gif-loader").after(resposta.data.senha_vazio);
                                }

                                // Se não existir no array a mensagem de campo senha vazio, ela será removida da página.
                                if(!resposta.data.senha_vazio){
                                    $(".msg-erro-login-3").remove();
                                }

                                // Se não existir essa mensagem, ela será mostrada.
                                // OBS: Isso é para evitar repetição da mesma mensagem.
                                if(!$(".msg-erro-login-4").length){
                                    $(".gif-loader").after(resposta.data.senha_incorreta);
                                }

                                // Se não existir no array a mensagem de campo senha incorreta, ela será removida da página.
                                if(!resposta.data.senha_incorreta){
                                    $(".msg-erro-login-4").remove();
                                }

                                // Se existir alguma mensagem de erro do campo senha, o campo terá a borda vermelha, se não, a borda será removida.
                                if($(".msg-erro-login-3").length || $(".msg-erro-login-4").length){
                                    $("#senha-login").addClass("borda-erro");
                                }else{
                                    $("#senha-login").removeClass("borda-erro");
                                }
                                /* -------------- Senha -------------- */

                                // Se o retorno for igual a "sucesso", o usuário será redirecionado para a página do chat.
                                if(resposta.data == "sucesso"){
                                    window.location.href = "chat.php";
                                }
                            })
                            .finally(function(){
                                // Logo após tudo ser realizado, o gif de loading será escondido.
                                $(".gif-loader").addClass("d-none");
                            })
                    },

                    // Pegando dados do usuário e passando para um array.
                    formData: function(dados_log) {
                        var formData = new FormData();
                        for(chave in dados_log){
                            formData.append(chave, dados_log[chave]);
                        }
                        return formData;
                    }
                }
            })

            // Iniciando uma instância Vue.JS
            var cadastro = new Vue({
                // Ligando ao elemento cadastro-form
                el: "#cadastro-form",

                // Inicializando um array para dados do usuário e uma variável com URL.
                data: {
                    cad: {nome_usuario: "", senha: "", repita_senha: ""},
                    urlPost: "http://localhost/Chat-Project/scripts/cadastro.php"
                },

                methods: {
                    EnviarCadastro: function () {
                        // Quando o botão cadastro for clicado, o gif de loading será mostrado.
                        $('.gif-loader').removeClass('d-none');

                        // Array com dados do usuário
                        var cad_form = this.formData(this.cad);

                        // Axios vai enviar para a URL fornecida, os dados do usuário para verificações antes de ter o cadastro realizado.
                        axios.post(this.urlPost, cad_form)
                            .then(function(resposta){
                                /* -------------- Nome de usuário -------------- */
                                // Se não existir essa mensagem, ela será mostrada.
                                // OBS: Isso é para evitar repetição da mesma mensagem.
                                if(!$(".msg-erro-cadastro-1").length){
                                    $(".gif-loader").after(resposta.data.nome_usuario_vazio);
                                }

                                // Se não existir no array a mensagem de campo nome de usuário vazio, ela será removida da página.
                                if(!resposta.data.nome_usuario_vazio){
                                    $(".msg-erro-cadastro-1").remove();
                                }

                                // Se não existir essa mensagem, ela será mostrada.
                                // OBS: Isso é para evitar repetição da mesma mensagem.
                                if(!$(".msg-erro-cadastro-2").length){
                                    $(".gif-loader").after(resposta.data.nome_usuario_utilizado);
                                }

                                // Se não existir no array a mensagem de campo nome de usuário utilizado, ela será removida da página.
                                if(!resposta.data.nome_usuario_utilizado){
                                    $(".msg-erro-cadastro-2").remove();
                                }

                                // Se não existir essa mensagem, ela será mostrada.
                                // OBS: Isso é para evitar repetição da mesma mensagem.
                                if(!$(".msg-erro-cadastro-3").length){
                                    $(".gif-loader").after(resposta.data.nome_usuario_caractere_erro);
                                }

                                // Se não existir no array a mensagem de campo nome de usuário caractere erro, ela será removida da página.
                                if(!resposta.data.nome_usuario_caractere_erro){
                                    $(".msg-erro-cadastro-3").remove();
                                }

                                // Se existir alguma mensagem de erro do campo nome de usuário, o campo terá a borda vermelha, se não, a borda será removida.
                                if($(".msg-erro-cadastro-1").length || $(".msg-erro-cadastro-2").length || $(".msg-erro-cadastro-3").length){
                                    $("#nome-usuario-cadastro").addClass("borda-erro");
                                }else{
                                    $("#nome-usuario-cadastro").removeClass("borda-erro");
                                }
                                /* -------------- Nome de usuário -------------- */

                                /* -------------- Senha -------------- */
                                // Se não existir essa mensagem, ela será mostrada.
                                // OBS: Isso é para evitar repetição da mesma mensagem.
                                if(!$(".msg-erro-cadastro-4").length){
                                    $(".gif-loader").after(resposta.data.senha_vazio);
                                }

                                // Se não existir no array a mensagem de campo senha vazio, ela será removida da página.
                                if(!resposta.data.senha_vazio){
                                    $(".msg-erro-cadastro-4").remove();
                                }

                                // Se não existir essa mensagem, ela será mostrada.
                                // OBS: Isso é para evitar repetição da mesma mensagem.
                                if(!$(".msg-erro-cadastro-5").length){
                                    $(".gif-loader").after(resposta.data.senhas_diferentes);
                                }

                                // Se não existir no array a mensagem de senhas diferentes, ela será removida da página.
                                if(!resposta.data.senhas_diferentes){
                                    $(".msg-erro-cadastro-5").remove();
                                }

                                // Se não existir essa mensagem, ela será mostrada.
                                // OBS: Isso é para evitar repetição da mesma mensagem.
                                if(!$(".msg-erro-cadastro-6").length){
                                    $(".gif-loader").after(resposta.data.senha_caractere_erro);
                                }

                                // Se não existir no array a mensagem de campo senha caractere erro, ela será removida da página.
                                if(!resposta.data.senha_caractere_erro){
                                    $(".msg-erro-cadastro-6").remove();
                                }

                                // Se existir alguma mensagem de erro do campo senha, o campo terá a borda vermelha, se não, a borda será removida.
                                if($(".msg-erro-cadastro-4").length || $(".msg-erro-cadastro-5").length || $(".msg-erro-cadastro-6").length){
                                    $("#senha-cadastro").addClass("borda-erro");
                                }else{
                                    $("#senha-cadastro").removeClass("borda-erro");
                                }
                                /* -------------- Senha -------------- */

                                /* -------------- Repita a Senha -------------- */
                                // Se não existir essa mensagem, ela será mostrada.
                                // OBS: Isso é para evitar repetição da mesma mensagem.
                                if(!$(".msg-erro-cadastro-7").length){
                                    $(".gif-loader").after(resposta.data.repita_senha_vazio);
                                }

                                // Se não existir no array a mensagem de campo repita a senha vazio, ela será removida da página.
                                if(!resposta.data.repita_senha_vazio){
                                    $(".msg-erro-cadastro-7").remove();
                                }

                                // Se existir alguma mensagem de erro do campo senha(que não seja o erro de caractere ou campo vazio) ou repita a senha, o campo repita a senha terá a borda vermelha, se não, a borda será removida.
                                if($(".msg-erro-cadastro-5").length || $(".msg-erro-cadastro-6").length || $(".msg-erro-cadastro-7").length){
                                    $("#repita-senha-cadastro").addClass("borda-erro");
                                }else{
                                    $("#repita-senha-cadastro").removeClass("borda-erro");
                                }
                                /* -------------- Repita a Senha -------------- */

                                // Se não existir essa mensagem, ela será mostrada.
                                // OBS: Isso é para evitar repetição da mesma mensagem.
                                // Logo após ela ser mostrada, os campos serão limpos.
                                if(!$("#msg-sucesso-cadastro").length){
                                    $(".gif-loader").after(resposta.data.sucesso_cadastro);

                                    cadastro.cad.nome_usuario = "";
                                    cadastro.cad.senha = "";
                                    cadastro.cad.repita_senha = "";
                                }

                                // Se não existir no array a mensagem de sucesso cadastro, ela será removida da página.
                                if(!resposta.data.sucesso_cadastro){
                                    $(".msg-sucesso-cadastro").remove();
                                }
                            })
                            .finally(function(){
                                // Logo após tudo ser realizado, o gif de loading será escondido.
                                $('.gif-loader').addClass('d-none');
                            })
                    },

                    // Pegando dados do usuário e passando para um array.
                    formData: function (dados_cad) {
                        formData = new FormData();
                        for(chave in dados_cad){
                            formData.append(chave, dados_cad[chave]);
                        }
                        return formData;
                    }
                }
            })
        })
    </script>
</body>
</html>