<?php
    // Se não houver uma sessão iniciada, será iniciada uma nova.
    if(!isset($_SESSION)){
        session_start();
    }
    // Se não tiver ninguém logado, o usuário será redirecionado para a página do usuário.
    if(!$_SESSION["id_usuario"]){
        header("location: area-usuario.php");
    }
    // Script com métodos para várias coisas.
    require_once "scripts/dados.php";
    $dados = new Dados("project_chat", "localhost", "root", "");
    // Método que pega o nome do usuário a partir do seu id.
    $mostrar_nome = $dados->BuscarNomeUsuario($_SESSION["id_usuario"]);
    // Método que retorna todos comentários com o nome do usuário e data de postagem.
    $mostrar_comentarios = $dados->BuscandoComentarios();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Arimo&display=swap" rel="stylesheet">
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.8.2/css/all.css' integrity='sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay' crossorigin='anonymous'>
    <link rel="stylesheet" href="estilo.css">
    <title>Project Chat</title>
</head>
<body>
    <div id="chat-lista-pessoas-main">
        <section id="div-chat">
            <div id="titulo-chat">
                <h5 id="nome-usuario-titulo"><i class="fas fa-user"></i><?= $mostrar_nome["nome_usuario"] ?></h5>

                <div id="sala-btn-sair">
                    <h5 id="sala-numeracao"><i class="fas fa-door-closed"></i>Você está na sala 1</h5>
                    <a href="scripts/sair.php" id="sair"><i class="fas fa-door-open"></i>Sair</a>
                </div>            
            </div>

            <div id="chat">
                <?php for($c = 0; $c < 5; $c++){ ?>
                    <div id="usuario-info">
                        <div id="nome-mensagem">
                            <div id="nome-info">
                                <h5 id="nome-usuario-chat">Nome usuário</h5>
                                <h5 id="data-usuario-chat">12/11/2019 - 00:11</h5>
                            </div>

                            <p>Lorem Ipsum é simplesmente uma simulação de texto da indústria tipográfica e de impressos, e vem sendo utilizado desde o século XVI, quando um impressor desconhecido pegou uma bandeja de tipos e os embaralhou para fazer um livro de modelos de tipos. Lorem Ipsum sobreviveu não só a cinco séculos</p>
                        </div>
                    </div>
                <?php } ?>

                <?php foreach($mostrar_comentarios as $mc){ 
                // Formatando a data recebida do banco de dados
                $data = new DateTime($mc["data"]);
                $horario = $data->format("d/m/Y")." - ".$data->format("H:i:s");
                ?>
                <div id="usuario-info">
                    <div id="nome-mensagem">
                        <div id="nome-info">
                            <h5 id="nome-usuario-chat"><?= $mc["nome_usuario"] ?></h5>
                            <h5 id="data-usuario-chat"><?= $horario ?></h5>
                        </div>

                        <p><?= $mc["comentario"] ?></p>
                    </div>
                </div>
                <?php } ?>
            </div>

            <form method="POST" autocomplete="off" id="div-publicar-mensagem">
                <div id="form-group-publicar-mensagem">
                    <input type="text" id="input-publicar" placeholder="Digite sua mensagem">
                </div>

                <button type="button" id="publicar"><i class="fas fa-reply"></i></button>
            </form>

            <form method="POST" autocomplete="off" id="div-escolher-sala">
                <button type="button" id="entrar-sala">Entrar</button>

                <div id="form-group-escolher-sala">
                    <select id="select-escolher-sala">
                        <option value="Sala 1" selected>Sala 1</option>
                        <option value="Sala 2">Sala 2</option>
                        <option value="Sala 3">Sala 3</option>
                    </select>
                </div>
            </form>
        </section>

        <section id="div-lista-pessoas">
            <div id="titulo-lista-pessoas">
                <h5>Lista de pessoas nesta sala</h5>
            </div>

            <div id="lista-pessoas">
                <?php for($c = 0; $c < 11; $c++){ ?>
                    <div id="usuario-status">
                        <h5 id="nome-usuario-lista-pessoas">Nome usuário</h5>

                        <h5 id="status"><i class="fas fa-circle"></i> Online</h5>
                    </div>
                <?php } ?>
            </div>
        </section>
    </div>

    <div class="msg-erro-publicar-mensagem">
        <span class='mensagem-erro'>Não é possível enviar uma mensagem em branco</span>
    </div>

    <div class="msg-erro-publicar-mensagem">
        <span class='mensagem-erro'>Você já está nesta sala</span>
    </div>

    <div id="retorno-ajax-js"></div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
        $(function(){
            // Quando o botão publicar for pressionado, será feita uma verificação se o campo está vázio, se não estiver, o comentário será enviado.
            $("#publicar").click(function(){
                var comentario = $("#input-publicar").val();
                $.ajax({
                    url: "scripts/publicar-comentario.php",
                    type: "post",
                    data: {comentario:comentario},
                    success: function(resposta){
                        $("#retorno-ajax-js").html(resposta);
                    }
                })
            })
        })
    </script>
</body>
</html>