<?php
// conexao.php

$host = 'localhost';
$dbname = 'financas_que_salvam'; // Nome do banco de dados
$usuario = 'root';
$senha = '';

try {
    // Fazendo a conexão corretamente
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $usuario, $senha);
    // Define o modo de erro do PDO para exceções
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Em caso de erro, exibe uma mensagem
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}
?>