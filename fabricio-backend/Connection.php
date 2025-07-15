<?php

$host = 'localhost'; // host
$dbname = 'financas_que_salvam'; // nome do banco
$username = 'root'; // usuario
$password = '';  // senha

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Conexão estabelecida com sucesso!";

} catch (PDOException $e) {

    echo "Erro na conexão: " . $e->getMessage();

}

?>