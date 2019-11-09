<?php
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        // Se não houver uma sessão iniciada, será iniciada uma nova
        if(!isset($_SESSION)){
            session_start();
        }
        
        // Script com métodos para várias coisas.
        require_once 'dados.php'; 
        $login = new Dados("project_chat", "localhost", "root", "");

        $mensagens_erro = [];
        $nome_usuario = addslashes(strip_tags(trim($_POST["nome_usuario"])));
        $senha = addslashes(strip_tags(trim($_POST["senha"])));
        ?>
        <script>
            $(function(){
                <?php
                    // Verificando se o campo nome de usuário está vazio, se sim, será mostrado um aviso de erro, se não, o aviso será removido.
                    if(empty($nome_usuario)){
                        $mensagens_erro["nome_usuario_vazio"] = true;
                        ?>

                        if(!$(".msg-erro-login-1").length){
                            $(".gif-loader").after("<span class='mensagem-erro msg-erro-login-1'>Preencha o campo Nome de usuário</span>")
                        }
                        <?php
                    }else{
                        unset($mensagens_erro["nome_usuario_vazio"]);
                        ?>
                        $(".msg-erro-login-1").remove();
                        <?php
                    }

                    // Verificando se o nome de usuário está incorreto, se sim, será  mostrado um aviso de erro, se não, o aviso será removido.
                    if($login->Login($nome_usuario, $senha) === "nome-usuario-incorreto"){
                        $mensagens_erro["nome_usuario_invalido"] = true;
                        ?>

                        if(!$(".msg-erro-login-2").length){
                            $(".gif-loader").after("<span class='mensagem-erro msg-erro-login-2'>Nome de usuário inválido</span>")
                        }
                        <?php
                    }else{
                        unset($mensagens_erro["nome_usuario_invalido"]);
                        ?>
                        $(".msg-erro-login-2").remove();
                        <?php
                    }

                    // Se existir alguma mensagem de erro do campo nome de usuário, o campo terá a borda vermelha, se não, a borda será removida.
                    ?>
                    if($(".msg-erro-login-1").length || $(".msg-erro-login-2").length){
                        $("#nome-usuario-login").addClass("borda-erro");
                    }else{
                        $("#nome-usuario-login").removeClass("borda-erro");
                    }
                    <?php

                    // Verificando se o campo senha está vazio, se sim, será mostrado um aviso de erro, se não, o aviso será removido.
                    if(empty($senha)){
                        $mensagens_erro["senha_vazio"] = true;
                        ?>

                        if(!$(".msg-erro-login-3").length){
                            $(".gif-loader").after("<span class='mensagem-erro msg-erro-login-3'>Preencha o campo Senha</span>")
                        }
                        <?php
                    }else{
                        unset($mensagens_erro["senha_vazio"]);
                        ?>
                        $(".msg-erro-login-3").remove();
                        <?php
                    }

                    // Verificando se a senha do usuário está incorreta, se sim, será  mostrado um aviso de erro, se não, o aviso será removido.
                    if($login->Login($nome_usuario, $senha) === "senha-incorreta"){
                        $mensagens_erro["senha_invalida"] = true;
                        ?>

                        if(!$(".msg-erro-login-4").length){
                            $(".gif-loader").after("<span class='mensagem-erro msg-erro-login-4'>Senha inválida</span>")
                        }
                        <?php
                    }else{
                        unset($mensagens_erro["senha_invalida"]);
                        ?>
                        $(".msg-erro-login-4").remove();
                        <?php

                    }

                    // Se existir alguma mensagem de erro do campo senha, o campo terá a borda vermelha, se não, a borda será removida.
                    ?>
                    if($(".msg-erro-login-3").length || $(".msg-erro-login-4").length){
                        $("#senha-login").addClass("borda-erro");
                    }else{
                        $("#senha-login").removeClass("borda-erro");
                    }
                    <?php

                    // Se não houver mensagens de erro, o usuário será redirecionado para a página do chat.
                    if(count($mensagens_erro) == 0){
                        $login->Login($nome_usuario, $senha);
                        ?>
                        window.location.href = "chat.php";
                        <?php
                    }
                ?>
            })
        </script>
        <?php
    }
?>