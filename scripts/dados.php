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
    }
?>