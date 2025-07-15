-- Script para adicionar a coluna salario à tabela usuarios
USE financas;

-- Adiciona a coluna salario se ela não existir
ALTER TABLE usuarios ADD COLUMN IF NOT EXISTS salario DECIMAL(10,2) DEFAULT 0.00;

-- Comentário sobre a coluna
-- A coluna salario armazena o salário mensal do usuário em reais
-- Será usada para calcular o percentual de gastos em relação ao salário 