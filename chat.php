<?php
    // Se não houver uma sessão iniciada, será iniciada uma nova.
    if(!isset($_SESSION)){
        session_start();
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

        ::-webkit-scrollbar{
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            box-shadow: inset 0 0 3px #7e7e7e;
        }

        ::-webkit-scrollbar-thumb {
            background: #29b37e;
            box-shadow: 0 0 1px 0 #7e7e7e;
        }

        body{
            background-color: #272727;
            display: flex;
            flex-flow: row nowrap;
            justify-content: center;
        }

        #div-chat{
            margin-top: 7%;
            background-color: #f0f0f0;
            width: 350px;
            border-radius: 1%;
            box-shadow: 0 0 2px 0px #cecece;
            border: 1px solid #cecece;
        }

        #titulo-chat{
            width: auto;
            text-align: center;
            padding: 7px 0;
            background-color: #272727;
            margin-bottom: 1px;
        }

        #titulo-chat h5{
            color: #29b37e;;
            font-weight: normal;
        }

        #titulo-chat h5 i{
            margin-right: 6px;
            color: #29b37e;
        }

        #chat{
            width: auto;
            padding: 20px;
            height: 300px;
            overflow: auto;
        }

        #usuario-info{
            display: flex;
            flex-flow: row nowrap;
            margin-bottom: 25px;
        }

        #usuario-info:last-child{
            margin-bottom: 0px;
        }

        #nome-info{
            display: flex;
            flex-flow: row nowrap;
        }

        #nome-info h5{
            font-size: 10pt;
            font-weight: normal;
        }

        #nome-usuario-chat{
            color: #29b37e;
            padding-right: 6px;
        }

        #data-usuario-chat{
            padding-top: 1px;
            font-size: 9pt !important;
            color: #7e7e7e;
        }

        #nome-comentario p{
            word-wrap: break-word;
            text-align: justify;
            font-size: 9pt;
            padding-top: 4px;
        }

        #publicar-comentario{
            display: flex;
            flex-flow: row nowrap;
            background-color: gray;
            margin-top: 1px;
        }

        #publicar-comentario #form-group{
            display: flex;
            flex-flow: row nowrap;
            width: 100%;
        }

        #publicar-comentario input{
            padding: 6px 5px;
            border: 1px solid #b5b6b5;
            background-color: #fff;
            width: 100%;
        }

        #publicar-comentario input:focus{
            border: 1px solid #29b37e;
            box-shadow: 0px 0px 3px 0px #29b37e;
            transition: ease-in 0.15s;
        }

        #publicar-comentario button{
            border: none;
            align-self: center;
            padding: 8px 15px;;
            background-color: #29b37e;
            border-radius: 3px;
            color: #fff;
        }

        #publicar-comentario button:hover{
            opacity: 0.8;
            transition: ease-in 0.15s;
            cursor: pointer;
        }

        #publicar-comentario button i{
            padding-top: 2px;
        }
    </style>
    <title>Project Chat</title>
</head>
<body>
    <!-- <a href="scripts/sair.php">Sair</a> -->
    <section id="div-chat">
        <div id="titulo-chat">
            <h5><i class="fas fa-user"></i>Project Chat - Sala 1</h5>
        </div>

        <div id="chat">
            <div id="usuario-info">
                <div id="nome-comentario">
                    <div id="nome-info">
                        <h5 id="nome-usuario-chat">Nome do usuário</h5>
                        <h5 id="data-usuario-chat">07/11/2019 - 01:05</h5>
                    </div>

                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</p>
                </div>
            </div>

            <div id="usuario-info">
                <div id="nome-comentario">
                    <div id="nome-info">
                        <h5 id="nome-usuario-chat">Nome do usuário</h5>
                        <h5 id="data-usuario-chat">07/11/2019 - 01:05</h5>
                    </div>

                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</p>
                </div>
            </div>

            <div id="usuario-info">
                <div id="nome-comentario">
                    <div id="nome-info">
                        <h5 id="nome-usuario-chat">Nome do usuário</h5>
                        <h5 id="data-usuario-chat">07/11/2019 - 01:05</h5>
                    </div>

                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</p>
                </div>
            </div>

            <div id="usuario-info">
                <div id="nome-comentario">
                    <div id="nome-info">
                        <h5 id="nome-usuario-chat">Nome do usuário</h5>
                        <h5 id="data-usuario-chat">07/11/2019 - 01:05</h5>
                    </div>

                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</p>
                </div>
            </div>

            <div id="usuario-info">
                <div id="nome-comentario">
                    <div id="nome-info">
                        <h5 id="nome-usuario-chat">Nome do usuário</h5>
                        <h5 id="data-usuario-chat">07/11/2019 - 01:05</h5>
                    </div>

                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.</p>
                </div>
            </div>
        </div>

        <form method="POST" autocomplete="off" id="publicar-comentario">
            <div id="form-group">
                <input type="text">
            </div>

            <button type="button"><i class="fas fa-reply"></i></button>
        </form>
    </section>
</body>
</html>