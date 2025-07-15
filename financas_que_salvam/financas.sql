CREATE DATABASE IF NOT EXISTS financas;
USE financas  ;


CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL
    
);

USE financas;

ALTER TABLE usuarios
ADD COLUMN valor DECIMAL(10,2) NOT NULL DEFAULT 0.00;  -- campo para armazenar o valor financeiro

SELECT * FROM usuarios;
