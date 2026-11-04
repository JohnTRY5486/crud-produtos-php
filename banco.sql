-- Banco de dados e tabela do sistema de produtos
-- Aluno: Joao Victor Mini Massagi - RA 8213731

CREATE DATABASE IF NOT EXISTS sistema_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE sistema_db;

CREATE TABLE IF NOT EXISTS produtos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  marca VARCHAR(100) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL,
  data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  cor VARCHAR(50),
  valor DOUBLE,
  ativo TINYINT DEFAULT 1
);
