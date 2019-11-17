<?php
    // Pegando a data e hora de São Paulo
    date_default_timezone_set("America/Sao_Paulo");

    // Chave para criptografia openssl.
    global $chave;
    $chave = '�#��Bf�4>C�6c�t�ݨ�q�r$���H';

    Class Dados{
        // Conexão com banco de dados
        public function __construct($bdnome, $host, $usuario, $senha){
            try{
                $this->pdo = new PDO("mysql:dbname=$bdnome;charset=utf8;host=".$host, $usuario, $senha);
            }catch(PDOException $e){
                echo "Erro com PDO: ".$e->getMessage();
            }catch(Exception $e){
                echo "Erro: ".$e->getMessage();
            }
        }

        // Primeiro será verificado se o nome do usuário existe, se sim, será verificado se a senha passada está correta, se sim, será  criado uma sessão com seu id para login, se qualquer uma das condições falharem, será retornado um valor que será usado para mostrar um aviso de erro.
        public function Login($nome_usuario, $senha){
            $sql = $this->pdo->prepare("SELECT nome_usuario FROM usuarios WHERE nome_usuario = :nu");
            $sql->bindValue(":nu", $nome_usuario, PDO::PARAM_STR);
            $sql->execute();

            if($sql->rowCount() > 0){
                $sql = $this->pdo->prepare("SELECT iv FROM usuarios WHERE nome_usuario = :nu");
                $sql->bindValue(":nu", $nome_usuario, PDO::PARAM_STR);
                $sql->execute();
                $iv = $sql->fetch();
                global $chave;
                $senha_criptografada = openssl_encrypt($senha, "AES-256-CBC", $chave, 0, $iv["iv"]);

                $sql = $this->pdo->prepare("SELECT * FROM usuarios WHERE nome_usuario = :nu AND senha = :s");
                $sql->bindValue(":nu", $nome_usuario, PDO::PARAM_STR);
                $sql->bindValue(":s", $senha_criptografada, PDO::PARAM_STR);
                $sql->execute();
                
                if($sql->rowCount() > 0){
                    $dados = $sql->fetch();
                    $_SESSION["id_usuario"] = $dados["id"];
                }else{
                    return "senha-incorreta";
                }
            }else{
                return "nome-usuario-incorreto";
            }
        }

        // Cadastrando o usuário pelo nome de usuário e senha recebidos.
        public function Cadastrar($nome_usuario, $senha){
            global $chave;
            $iv = bin2hex(random_bytes(8));
            $senha_criptografada = openssl_encrypt($senha, "AES-256-CBC", $chave, 0, $iv);

            $sql = $this->pdo->prepare("INSERT INTO usuarios (nome_usuario, senha, iv) VALUES (:nu, :s, :iv)");
            $sql->bindValue(":nu", $nome_usuario, PDO::PARAM_STR);
            $sql->bindValue(":s", $senha_criptografada, PDO::PARAM_STR);
            $sql->bindValue(":iv", $iv, PDO::PARAM_STR);
            $sql->execute();
        }

        // Verificando se existe um usuário com o nome de usuário recebido.
        public function VerificarNomeUsuario($nome_usuario){
            $sql = $this->pdo->prepare("SELECT id FROM usuarios WHERE nome_usuario = :nu");
            $sql->bindValue(":nu", $nome_usuario, PDO::PARAM_STR);
            $sql->execute();
            if($sql->rowCount() > 0){
                return true;
            }else{
                return false;
            }
        }

        // Buscando o nome do usuário pelo seu id de sessão.
        public function BuscarNomeUsuario($id){
            $sql = $this->pdo->prepare("SELECT nome_usuario FROM usuarios WHERE id = :id");
            $sql->bindValue(":id", $id, PDO::PARAM_INT);
            $sql->execute();
            return $sql->fetch();
        }

        // Enviando a mensagem do usuário para o banco de dados.
        public function EnviarMensagem($mensagem, $id_usuario, $id_sala){
            $sql = $this->pdo->prepare("INSERT INTO chat (mensagem, data, pk_id_usuario, pk_id_sala) VALUES (:m, :d, :piu, :pis)");
            $sql->bindValue(":m", $mensagem, PDO::PARAM_STR);
            $sql->bindValue(":d", date("Y-m-d H:i:s"));
            $sql->bindValue(":piu", $id_usuario, PDO::PARAM_INT);
            $sql->bindValue(":pis", $id_sala, PDO::PARAM_INT);
            $sql->execute();
        }

        // Buscando mensagens com o nome do usuário e data de postagem.
        public function BuscandoMensagens($id_sala_usuario){
            $sql = $this->pdo->prepare("SELECT *,(SELECT nome_usuario FROM usuarios WHERE id = pk_id_usuario) AS nome_usuario FROM chat WHERE pk_id_sala = :pis ORDER BY id ASC");
            $sql->bindValue(":pis", $id_sala_usuario, PDO::PARAM_INT);
            $sql->execute();
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }

        // Buscando todas salas
        public function BuscarTodasSalas(){
            $sql = $this->pdo->prepare("SELECT * FROM salas");
            $sql->execute();
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }

        // Verificando se o usuário está em alguma sala, se ele não estiver, será retornado true.
        public function VerificarSalaUsuario($id_usuario){
            $sql = $this->pdo->prepare("SELECT * FROM lista_pessoas_sala WHERE id_usuario = :iu");
            $sql->bindValue(":iu", $id_usuario, PDO::PARAM_INT);
            $sql->execute();

            if($sql->rowCount() == 0){
                return true;
            }
        }

        // Colocando a sala padrão para o usuário com id dele e id da sala recebidos
        public function InicializadorSalaAtual($id_usuario, $id_sala){
            $sql = $this->pdo->prepare("INSERT INTO lista_pessoas_sala (id_usuario, id_sala, status) VALUES (:iu, :is, 1)");
            $sql->bindValue(":iu", $id_usuario, PDO::PARAM_INT);
            $sql->bindValue(":is", $id_sala, PDO::PARAM_INT);
            $sql->execute();
        }

        // Buscando sala atual do usuário logado
        public function BuscarSalaAtualUsuario($id_usuario){
            $sql = $this->pdo->prepare("SELECT id_sala FROM lista_pessoas_sala WHERE id_usuario = :iu");
            $sql->bindValue(":iu", $id_usuario, PDO::PARAM_INT);
            $sql->execute();
            return $sql->fetch();
        }

        // Entrando na sala escolhida pelo usuário
        public function EntrarNaSala($id_sala, $id_usuario){
            $sql = $this->pdo->prepare("UPDATE lista_pessoas_sala SET id_sala = :is WHERE id_usuario = :iu");
            $sql->bindValue(":is", $id_sala, PDO::PARAM_INT);
            $sql->bindValue(":iu", $id_usuario, PDO::PARAM_INT);
            $sql->execute();
        }

        // Alterando o status do usuário
        public function StatusUsuario($id_usuario, $status){
            $sql = $this->pdo->prepare("UPDATE lista_pessoas_sala SET status = :s WHERE id_usuario = :iu");
            $sql->bindValue(":iu", $id_usuario, PDO::PARAM_INT);
            $sql->bindValue(":s", $status, PDO::PARAM_INT);
            $sql->execute();
        }

        // Buscando todos usuários online numa sala específica
        public function BuscandoUsuariosOnlineSala($id_sala){
            $sql = $this->pdo->prepare("SELECT *,(SELECT nome_usuario FROM usuarios WHERE id = id_usuario) AS nome_usuario FROM lista_pessoas_sala WHERE id_sala = :is AND status = 1 ORDER BY id ASC");
            $sql->bindValue(":is", $id_sala, PDO::PARAM_INT);
            $sql->execute();
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }

        // Buscando todos os usuários online
        public function BuscandoUsuariosOnline(){
            $sql = $this->pdo->prepare("SELECT *,(SELECT nome_usuario FROM usuarios WHERE id = id_usuario) AS nome_usuario FROM lista_pessoas_sala WHERE status = 1 ORDER BY id ASC");
            $sql->execute();
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>