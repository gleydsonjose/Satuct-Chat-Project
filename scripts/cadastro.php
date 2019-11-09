<?php
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        // Script com métodos para várias coisas.
        require_once 'dados.php'; 
        $cadastro = new Dados("project_chat", "localhost", "root", "");

        $mensagens_erro = [];
        $nome_usuario = addslashes(strip_tags(trim($_POST["nome_usuario"])));
        $senha = addslashes(strip_tags(trim($_POST["senha"])));
        $repita_senha = addslashes(strip_tags(trim($_POST["repita_senha"])));
        ?>
        <script>
            $(function(){
                <?php
                    // Verificando se o campo nome de usuário está vazio, se sim, será mostrado um aviso de erro, se não, o aviso será removido.
                    if(empty($nome_usuario)){
                        $mensagens_erro["nome_usuario_vazio"] = true;
                        ?>

                        if(!$(".msg-erro-cadastro-1").length){
                            $(".gif-loader").after("<span class='mensagem-erro msg-erro-cadastro-1'>Preencha o campo Nome de usuário</span>")
                        }
                        <?php
                    }else{
                        unset($mensagens_erro["nome_usuario_vazio"]);
                        ?>
                        $(".msg-erro-cadastro-1").remove();
                        <?php
                    }

                    // Verificando se o nome do usuário já existe no banco de dados, se sim, será mostrado um aviso de erro, se não, o aviso será removido.
                    if($cadastro->VerificarNomeUsuario($nome_usuario)){
                        $mensagens_erro["nome_usuario_utilizado"] = true;
                        ?>

                        if(!$(".msg-erro-cadastro-2").length){
                            $(".gif-loader").after("<span class='mensagem-erro msg-erro-cadastro-2'>Este nome de usuário já existe</span>")
                        }
                        <?php
                    }else{
                        // Se o campo nome de usuário não estiver vazio, o aviso de erro será removido.
                        if(!empty($nome_usuario)){
                            unset($mensagens_erro["nome_usuario_utilizado"]);
                            ?>
                            $(".msg-erro-cadastro-2").remove();
                            <?php
                        }
                    }

                    // Se o nome de usuário for menor ou igual a 6 caracteres, será mostrado um aviso de erro, se não, o aviso será removido.
                    if(strlen($nome_usuario) <= 6){
                        $mensagens_erro["nome_usuario_caractere"] = true;
                        ?>

                        if(!$(".msg-erro-cadastro-3").length){
                            $(".gif-loader").after("<span class='mensagem-erro msg-erro-cadastro-3'>O nome de usuário precisa ter mais que 6 caracteres</span>")
                        }
                        <?php
                    }else{
                        // Se o campo nome de usuário não estiver vazio, o aviso de erro será removido.
                        if(!empty($nome_usuario)){
                            unset($mensagens_erro["nome_usuario_caractere"]);
                            ?>
                            $(".msg-erro-cadastro-3").remove();
                            <?php
                        }
                    }

                    // Se existir alguma mensagem de erro do campo nome de usuário, o campo terá a borda vermelha, se não, a borda será removida.
                    ?>
                    if($(".msg-erro-cadastro-1").length || $(".msg-erro-cadastro-2").length || $(".msg-erro-cadastro-3").length){
                        $("#nome-usuario-cadastro").addClass("borda-erro");
                    }else{
                        $("#nome-usuario-cadastro").removeClass("borda-erro");
                    }
                    <?php

                    // Verificando se o campo senha está vazio, se sim, será mostrado um aviso de erro, se não, o aviso será removido.
                    if(empty($senha)){
                        $mensagens_erro["senha_vazio"] = true;
                        ?>

                        if(!$(".msg-erro-cadastro-4").length){
                            $(".gif-loader").after("<span class='mensagem-erro msg-erro-cadastro-4'>Preencha o campo Senha</span>")
                        }
                        <?php
                    }else{
                        unset($mensagens_erro["senha_vazio"]);
                        ?>
                        $(".msg-erro-cadastro-4").remove();
                        <?php
                    }

                    // Se as senhas não forem iguais, será mostrado um aviso de erro, se não, o aviso será removido.
                    if(strcmp($senha, $repita_senha) != 0){
                        $mensagens_erro["senhas_diferentes"] = true;
                        ?>

                        if(!$(".msg-erro-cadastro-5").length){
                            $(".gif-loader").after("<span class='mensagem-erro msg-erro-cadastro-5'>As senhas não correspondem</span>")
                        }
                        <?php
                    }else{
                        unset($mensagens_erro["senhas-diferentes"]);
                        ?>
                        $(".msg-erro-cadastro-5").remove();
                        <?php
                    }
                    
                    // Se a senha for menor ou igual a 6 caracteres, será mostrado um aviso de erro, se não, o aviso será removido.
                    if(strlen($senha) <= 6){
                        $mensagens_erro["senha_caractere"] = true;
                        ?>

                        if(!$(".msg-erro-cadastro-6").length){
                            $(".gif-loader").after("<span class='mensagem-erro msg-erro-cadastro-6'>A senha precisa ter mais que 6 caracteres</span>")
                        }
                        <?php
                    }else{
                        unset($mensagens_erro["senha_caractere"]);
                        ?>
                        $(".msg-erro-cadastro-6").remove();
                        <?php
                    }

                    // Se existir alguma mensagem de erro do campo senha, o campo terá a borda vermelha, se não, a borda será removida.
                    ?>
                    if($(".msg-erro-cadastro-4").length || $(".msg-erro-cadastro-5").length || $(".msg-erro-cadastro-6").length){
                        $("#senha-cadastro").addClass("borda-erro");
                    }else{
                        $("#senha-cadastro").removeClass("borda-erro");
                    }
                    <?php

                    // Verificando se o campo repita a senha está vazio, se sim, será mostrado um aviso de erro, se não, o aviso será removido.
                    if(empty($repita_senha)){
                        $mensagens_erro["repita_senha_vazio"] = true;
                        ?>

                        if(!$(".msg-erro-cadastro-7").length){
                            $(".gif-loader").after("<span class='mensagem-erro msg-erro-cadastro-7'>Preencha o campo Repita a senha</span>")
                        }
                        <?php
                    }else{
                        unset($mensagens_erro["repita_senha_vazio"]);
                        ?>
                        $(".msg-erro-cadastro-7").remove();
                        <?php
                    }

                    // Se existir alguma mensagem de erro do campo senha(que não seja o erro de caractere ou campo vazio) ou repita a senha, o campo repita a senha terá a borda vermelha, se não, a borda será removida.
                    ?>
                    if($(".msg-erro-cadastro-5").length || $(".msg-erro-cadastro-6").length || $(".msg-erro-cadastro-7").length){
                        $("#repita-senha-cadastro").addClass("borda-erro");
                    }else{
                        $("#repita-senha-cadastro").removeClass("borda-erro");
                    }
                    <?php

                    // Se não houver mensagens de erro, o usuário será cadastrado com sucesso.
                    if(count($mensagens_erro) == 0){
                        $cadastro->Cadastrar($nome_usuario, $senha);
                        ?>
                        if(!$("#msg-sucesso-cadastro").length){
                            $(".gif-loader").after("<span class='mensagem-sucesso' id='msg-sucesso-cadastro'>Você foi cadastrado com sucesso</span>")
                        }
                        <?php
                    }
                ?>
            })
        </script>
        <?php
    }
?>