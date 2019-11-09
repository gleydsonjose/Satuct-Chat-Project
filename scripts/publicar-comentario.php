<?php
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        // Se não houver uma sessão iniciada, será iniciada uma nova
        if(!isset($_SESSION)){
            session_start();
        }
        
        // Script com métodos para várias coisas.
        require_once 'dados.php'; 
        $publicar_comentario = new Dados("project_chat", "localhost", "root", "");

        $mensagem_erro = false;
        $comentario = addslashes(strip_tags(trim($_POST["comentario"])));
        $id_usuario = addslashes(strip_tags(trim($_SESSION["id_usuario"])));
        ?>
        <script>
            $(function(){
                <?php
                    // Verificando se o campo de comentário está vazio, se sim, será mostrado um aviso de erro, se não, o aviso será removido.
                    if(empty($comentario)){
                        $mensagens_erro = true;
                        ?>

                        if(!$(".msg-erro-publicar-comentario").length){
                            $("#div-chat").after("<span class='mensagem-erro msg-erro-publicar-comentario'>Não é possível enviar um comentário em branco</span>")
                        }
                        <?php
                    }else{
                        $mensagens_erro = false;
                        ?>
                        $(".msg-erro-publicar-comentario").remove();
                        <?php
                    }

                    // Se não houver mensagem de erro, o comentário será enviado para o banco de dados.
                    if($mensagens_erro === false){
                        $publicar_comentario->EnviarComentário($comentario, $id_usuario);
                        ?>
                        $("#comentario-input").val("");
                        <?php
                    }
                ?>
            })
        </script>
    <?php
    }
?>