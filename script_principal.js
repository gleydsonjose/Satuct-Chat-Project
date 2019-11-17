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
            urlPost: "http://"+window.location.hostname+"/Chat-Project/scripts/login.php"
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
            urlPost: "http://"+window.location.hostname+"/Chat-Project/scripts/cadastro.php"
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
                        if(!$(".msg-sucesso-cadastro").length){
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