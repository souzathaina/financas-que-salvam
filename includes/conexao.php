<?php
$servidor = "financas-que-salvam";  
$usuario = "root";        
$senha = "";              
$banco = "financas";

$conexao = new mysqli($servidor, $usuario, $senha, $banco);

if ($conexao->connect_error) {
    die("Falha na conexão: " . $conexao->connect_error);
}

$conexao->set_charset("utf8");

// Em outros arquivos PHP do projeto, basta incluir o conexao.php com:
// require_once 'conexao.php';
?>