<?php
    // Se não houver uma sessão iniciada, será iniciada uma nova.
    if(!isset($_SESSION)){
        session_start();
    }

    // Se não tiver ninguém logado, o usuário será redirecionado para a página do usuário.
    if(!$_SESSION["id_usuario"]){
        header("location: index.php");
    }

    // Script com métodos para várias coisas.
    require_once "scripts/dados.php";
    $dados = new Dados("project_chat", "localhost", "root", "");

    // Método que pega o nome do usuário a partir do seu id.
    $mostrar_nome = $dados->BuscarNomeUsuario($_SESSION["id_usuario"]);

    // Será verificado se o usuário já tem uma sala atual no banco de dados, se não tiver, ele será colocado na sala 1(padrão).
    if($dados->VerificarSalaUsuario($_SESSION["id_usuario"])){
        $dados->InicializadorSalaAtual($_SESSION["id_usuario"], 1);
    }

    // Mudando o status do usuário para online(1).
    $dados->StatusUsuario($_SESSION["id_usuario"], 1);

    // Buscando todas salas registradas no banco de dados, para ser mostrado no inicio da página.
    $mostrar_salas = $dados->BuscarTodasSalas();

    // Buscando o id da sala atual do usuário logado.
    $id_sala_atual = $dados->BuscarSalaAtualUsuario($_SESSION["id_usuario"])["id_sala"];
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
    <title>Project Chat</title>
</head>
<body>
    <div id="main">
        <div id="chat-lista-pessoas-main">
            <section id="div-chat">
                <div id="titulo-chat">
                    <h5 id="nome-usuario-titulo" data-id_sala="<?= $id_sala_atual ?>"><i class="fas fa-user"></i><?= $mostrar_nome["nome_usuario"] ?></h5>

                    <div id="sala-btn-sair">
                        <h5 id="sala-numeracao"><i class="fas fa-door-closed"></i><span id="sala_numeracao_span"></span></h5>
                        <a href="scripts/sair.php" id="sair"><i class="fas fa-door-open"></i>Sair</a>
                    </div>            
                </div>

                <div id="chat">
                    <div id="usuario-info" v-for="info in dados_usuario">
                        <div id="nome-mensagem">
                            <div id="nome-info">
                                <h5 id="nome-usuario-chat">{{ info.nome_usuario }}</h5>
                                <h5 id="data-usuario-chat">{{ info.horario_envio }}</h5>
                            </div>

                            <p>{{ info.mensagem }}</p>
                        </div>
                    </div>
                </div>

                <div id="div-publicar-mensagem">
                    <div id="form-group-publicar-mensagem">
                        <input type="text" id="input-publicar" placeholder="Digite sua mensagem" v-model="mensagem_usuario" @keyup.enter="enviarMensagem">
                    </div>

                    <button type="button" id="publicar" v-on:click="enviarMensagem"><i class="fas fa-reply"></i></button>
                </div>

                <div id="div-escolher-sala">
                    <button type="button" id="entrar-sala" v-on:click="entrarSala">Entrar</button>

                    <div id="form-group-escolher-sala">
                        <select id="select-escolher-sala" v-model="id_sala">
                        <?php foreach($mostrar_salas as $sala){ ?>
                            <option value="<?= $sala["id"] ?>"><?= $sala["sala"] ?></option>
                        <?php } ?>
                        </select>
                    </div>
                </div>
            </section>

            <section id="div-lista-pessoas">
                <div id="titulo-lista-pessoas">
                    <h5>Lista de pessoas nesta sala</h5>
                </div>

                <div id="lista-pessoas">
                    <div id="usuario-status" v-for="status in usuarios_online_lista">
                        <h5 id="nome-usuario-lista-pessoas">{{ status.nome_usuario }}</h5>

                        <h5 id="status"><i class="fas fa-circle"></i> Online</h5>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <!-- <script src="https://cdn.jsdelivr.net/npm/vue@2.6.0"></script> -->
    <script>
        var chat = new Vue({
            // Conectando o Vue para o elemento com id 'main'
            el: "#main",

            // Variáveis e arrays para guardar informações para ser usados nas etapas seguintes.
            data:{
                nome_usuario: "<?= $mostrar_nome["nome_usuario"] ?>",
                horario_atual: "<?= date("d/m/Y - H:i") ?>",
                mensagem_usuario: "",
                id_sala: "<?= $id_sala_atual ?>",
                ws: null,
                dados_usuario: [],
                usuarios_online_lista: [],
                urlBuscaMensagens: "http://"+window.location.hostname+"/Chat-Project/scripts/buscar_mensagens.php",
                urlEnviarMensagem: "http://"+window.location.hostname+"/Chat-Project/scripts/enviar_mensagem.php",
                urlEntrarSala: "http://"+window.location.hostname+"/Chat-Project/scripts/entrar_sala.php",
                urlBuscarUsuariosOnline: "http://"+window.location.hostname+"/Chat-Project/scripts/buscar_usuarios_online.php",
                urlBuscarUsuariosOnlineSala: "http://"+window.location.hostname+"/Chat-Project/scripts/buscar_usuarios_online_sala.php"
            },

            // Quando o app for iniciado, o será chamado um método para atualizar informações da sala na página e o método de conexão com websocket.
            created: function() {
                this.AlterarInfoSalaAtual();
                this.connect();
            },

            // Métodos do app
            methods: {

                // Método responsável por iniciar conexão com o websocket
                connect: function(onOpen){

                    // Conectando
                    this.ws = new WebSocket("ws://localhost:8080");

                    // Quando a conexão for aberta, será mostrado uma mensagem de sucesso e dois métodos serão chamados, um para buscar mensagens da sala atual e outro para buscar usuários online na sala atual.
                    this.ws.onopen = function() {
                        $(".aviso-erro-conexao").remove();
                        $(".aviso-erro-conexao2").remove();

                        $("#chat-lista-pessoas-main").after("<div class='aviso-sucesso-conexao mensagens-aviso-chat'><span class='mensagem-sucesso'>Conectado</span></div>");

                        setTimeout(function(){ $(".aviso-sucesso-conexao").remove(); }, 3000);

                        // Se houver método de retorno
                        if (onOpen) {
                            onOpen();
                        }

                        chat.BuscarMensagensSalaAtual();
                        chat.BuscarUsuariosOnlineSala();
                    };

                    // Quando houver um erro na tentativa de conectar ao websockets, será mostrado uma mensagem de erro.
                    this.ws.onerror = function() {
                        $(".aviso-erro-conexao2").remove();

                        $("#chat-lista-pessoas-main").after("<div class='aviso-erro-conexao mensagens-aviso-chat'><span class='mensagem-erro'>Não foi possível conectar-se ao servidor</span></div>");
                    };

                    // Quando dados forem recebidos do servidor ws, esse método será chamado.
                    this.ws.onmessage = function(resposta) {
                        var dados_recebidos_ws = JSON.parse(resposta.data);

                        // Se os dados de "Entrar na sala" forem recebidos, será feito algumas filtragens para não ter repetição de usuários na lista, se caso um novo usuário for aparecer na lista, ele será adicionado, se ele sair, ele será removido.
                        // Lembrando que o usuário logado não vai ser excluido da lista, ele sempre vai ser o primeiro da sua lista.
                        if(dados_recebidos_ws.usuarios_online){
                            for(key_dr in dados_recebidos_ws.usuarios_online){
                                let dados_r_ws = dados_recebidos_ws.usuarios_online[key_dr]

                                if(dados_r_ws.id_sala == chat.id_sala){
                                    for(key_uol in chat.usuarios_online_lista){
                                        if(!Object.values(chat.usuarios_online_lista[key_uol]).includes(dados_r_ws.nome_usuario)){
                                            if(dados_r_ws.nome_usuario != chat.nome_usuario){
                                                chat.usuarios_online_lista.push({
                                                    nome_usuario: dados_r_ws.nome_usuario
                                                });
                                            }
                                        }
                                    }
                                }else{
                                    for(key_uol in chat.usuarios_online_lista){
                                        if(dados_r_ws.nome_usuario == chat.usuarios_online_lista[key_uol].nome_usuario){
                                            chat.usuarios_online_lista.splice(key_uol);
                                        }
                                    }
                                }
                            }
                        }else{
                            // Se os dados do "Envio de mensagem" forem recebidos, será feito uma filtragem para uma mensagem direcionada para uma sala específica ser entregue apenas para essa sala.
                            if(chat.id_sala == dados_recebidos_ws.id_sala){
                                chat.dados_usuario.push({
                                    nome_usuario: dados_recebidos_ws.nome_usuario,
                                    horario_envio: dados_recebidos_ws.horario_envio,
                                    mensagem: dados_recebidos_ws.mensagem
                                });
                            }
                        }
                    };
                },

                // Método responsável por enviar uma mensagem
                enviarMensagem: function() {
                    // Se a conexão não conseguir abrir, será mostrado uma mensagem avisando que está tentando se reconectar.
                    if (this.ws.readyState !== this.ws.OPEN) {
                        $(".aviso-erro-conexao").remove();

                        $("#chat-lista-pessoas-main").after("<div class='aviso-erro-conexao2 mensagens-aviso-chat'><span class='mensagem-erro'>Problemas na conexão. Tentando reconectar...</span></div>");

                        // Tentando se reconectar, se houver sucesso a próxima etapa será ativa, se houver falha, o método vai parar por aqui.
                        chat.connect(function() {
                            chat.enviarMensagem();
                        });

                        // Saindo do método
                        return;
                    }

                    // Verificando se houve falha ao tentar entrar numa sala e o usuário tentou enviar a mensagem mesmo assim, se isso acontecer, será mostrado uma mensagem de erro, se não, a mensagem será enviada para o ws e banco de dados com sucesso.
                    if($("#nome-usuario-titulo").attr("data-id_sala") != chat.id_sala){
                        $(".erro_tentativa_entrar_sala").remove();
                        $(".erro_tentativa_entrar_sala_mensagem").remove();

                        $("#chat-lista-pessoas-main").after("<div class='erro_tentativa_entrar_sala_mensagem mensagens-aviso-chat'><span class='mensagem-erro'>Houve um erro ao tentar entrar na sala, tente novamente para enviar uma mensagem</span></div>");
                    }else{
                        $(".erro_tentativa_entrar_sala").remove();
                        $(".erro_tentativa_entrar_sala_mensagem").remove();

                        this.ws.send(JSON.stringify({nome_usuario: this.nome_usuario, horario_envio: this.horario_atual, mensagem: this.mensagem_usuario, id_sala: this.id_sala}));

                        var mensagem_formData = chat.formData({mensagem: chat.mensagem_usuario});

                        axios.post(chat.urlEnviarMensagem, mensagem_formData).then(function(resposta){
                            if(!$(".mensagem_vazio").length){
                                $("#chat-lista-pessoas-main").after(resposta.data);
                            }

                            if(!resposta.data.length){
                                $(".mensagem_vazio").remove();

                                chat.mensagem_usuario = "";
                            }
                        });
                    }
                },

                // Método que será ativo na mudança de sala.
                entrarSala: function() {
                    var id_sala_formData = this.formData({id_sala: this.id_sala});

                    // Alterando o a sala atual do usuário
                    axios.post(this.urlEntrarSala, id_sala_formData).then(function(resposta){
                        if(resposta.data !== "sucesso"){
                            if(!$(".sala-msg-erro").length){
                                $("#chat-lista-pessoas-main").after(resposta.data);
                            }
                        }else{
                            $(".sala-msg-erro").remove();
                        }
                    })

                    // Pegando dados de usuários online e verificando se houve algum erro ao tentar entrar na sala, se houver, será mostrado uma mensagem de erro, se não, será feito a limpeza do chat do usuário logado, a busca de mensagens feitas na sala atual, busca de usuários online nesta sala e será enviado dados de usuários online para o ws.
                    axios.get(this.urlBuscarUsuariosOnline).then(function(usuarios){       
                        for(index in usuarios.data){
                            if(chat.nome_usuario == usuarios.data[index].nome_usuario && chat.id_sala != usuarios.data[index].id_sala){
                                $(".erro_tentativa_entrar_sala").remove();
                                $(".erro_tentativa_entrar_sala_mensagem").remove();

                                $("#chat-lista-pessoas-main").after("<div class='erro_tentativa_entrar_sala mensagens-aviso-chat'><span class='mensagem-erro'>Houve um erro ao tentar entrar na sala, tente novamente</span></div>");
                            }else if(chat.nome_usuario == usuarios.data[index].nome_usuario && chat.id_sala == usuarios.data[index].id_sala){
                                $(".erro_tentativa_entrar_sala").remove();
                                $(".erro_tentativa_entrar_sala_mensagem").remove();

                                chat.dados_usuario = [];
                                chat.BuscarMensagensSalaAtual();
                                chat.AlterarInfoSalaAtual();
                                chat.ws.send(JSON.stringify({usuarios_online:usuarios.data}));
                            }
                        }
                    })
                },

                // Alterando informações da sala atual do usuário logado
                AlterarInfoSalaAtual: function () {
                    $("#nome-usuario-titulo").attr("data-id_sala", this.id_sala);
                    $("#sala_numeracao_span").text("Você está na sala " + this.id_sala);

                    $.each($("#select-escolher-sala option"), function(){
                        if($(this).attr("value") == this.id_sala){
                            $(this).attr("selected", true);
                        }
                    })
                },

                // Buscando mensagens da sala atual.
                BuscarMensagensSalaAtual: function() {
                    axios.get(chat.urlBuscaMensagens).then(function(mensagens_bd){
                        chat.dados_usuario = [];

                        for(index in mensagens_bd.data){
                            ano = mensagens_bd.data[index].data.substr(0,4);
                            mes = mensagens_bd.data[index].data.substr(5,2);
                            dia = mensagens_bd.data[index].data.substr(8,2);
                            hora = mensagens_bd.data[index].data.substr(11, 5);
                            horario_envio = dia + "/" + mes + "/" + ano + " - " + hora;

                            chat.dados_usuario.push({
                                nome_usuario: mensagens_bd.data[index].nome_usuario,
                                horario_envio: horario_envio,
                                mensagem: mensagens_bd.data[index].mensagem
                            });
                        }
                    })
                },

                // Buscando usuários online.
                BuscarUsuariosOnline: function() {
                    axios.get(chat.urlBuscarUsuariosOnline).then(function(usuarios){                        
                        for(index in usuarios.data){
                            chat.usuarios_online_lista.push({
                                nome_usuario: usuarios.data[index].nome_usuario
                            });
                        }
                    })
                },

                // Buscando usuários online na sala atual.
                BuscarUsuariosOnlineSala: function() {
                    var id_sala_formData = this.formData({id_sala:chat.id_sala});

                    axios.post(chat.urlBuscarUsuariosOnlineSala, id_sala_formData).then(function(usuarios){                        
                        for(index in usuarios.data){
                            chat.usuarios_online_lista.push({
                                nome_usuario: usuarios.data[index].nome_usuario
                            });
                        }
                    })
                },

                // Fazendo filtragem de dados recebidos pelo input, usando o formData.
                formData: function (dados) {
                    formData = new FormData();
                    for(chave in dados){
                        formData.append(chave, dados[chave]);
                    }
                    return formData;
                }
            }
        });
    </script>
</body>
</html>