<?php

$host = 'localhost'; // host
$dbname = 'financas'; // nome do banco
$username = 'root'; // usuario
$password = '';  // senha

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    error_log("Erro na conexão: " . $e->getMessage());
    die("Erro na conexão com o banco de dados");
}

?>