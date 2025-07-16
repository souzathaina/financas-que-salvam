-- Script completo para criar o banco de dados Finanças que Salvam
-- Execute este arquivo no phpMyAdmin ou via linha de comando

CREATE DATABASE IF NOT EXISTS financas;
USE financas;

-- Tabela de usuários
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    salario DECIMAL(10,2) DEFAULT 0.00,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de categorias
CREATE TABLE IF NOT EXISTS categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de despesas
CREATE TABLE IF NOT EXISTS despesas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    categoria_id INT NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    data DATE NOT NULL,
    descricao TEXT,
    observacoes TEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE CASCADE
);

-- Inserir algumas categorias padrão
INSERT INTO categorias (nome) VALUES 
('Alimentação'),
('Transporte'),
('Moradia'),
('Saúde'),
('Educação'),
('Lazer'),
('Vestuário'),
('Contas'),
('Outros');

-- Inserir usuário de teste (senha: 123456)
INSERT INTO usuarios (nome, email, senha, salario) VALUES 
('Leonardo', 'leo@mail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3000.00);

-- Comentários sobre a estrutura:
-- 1. usuarios: Armazena dados dos usuários, incluindo salário mensal
-- 2. categorias: Categorias de despesas (pode ser expandida pelo usuário)
-- 3. despesas: Registro de todas as despesas com relacionamentos
-- 
-- Relacionamentos:
-- - despesas.usuario_id -> usuarios.id
-- - despesas.categoria_id -> categorias.id
--
-- O salário é armazenado na tabela usuarios e usado para calcular percentuais 