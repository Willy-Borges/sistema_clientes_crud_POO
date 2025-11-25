-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 19/11/2025 às 19:10
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `sistema_clientes`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `clientes`
--

CREATE TABLE `clientes` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(150) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `sexo` enum('M','F') NOT NULL,
  `data_nascimento` date DEFAULT NULL,
  `profissao` varchar(100) DEFAULT NULL,
  `logradouro` varchar(200) DEFAULT NULL,
  `uf` varchar(2) NOT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `bairro` varchar(100) DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `cep` varchar(9) DEFAULT NULL,
  `celular` varchar(20) NOT NULL,
  `email` varchar(150) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `clientes`
--

INSERT INTO `clientes` (`id`, `nome`, `cpf`, `sexo`, `data_nascimento`, `profissao`, `logradouro`, `uf`, `numero`, `bairro`, `cidade`, `cep`, `celular`, `email`, `data_cadastro`) VALUES
(2, 'paloma', '000.000.000-02', 'F', '1998-12-25', 'teste02', 'rua 2', 'MG', '100', 'teste', 'teste', '00000-002', '(35) 00020-0000', 'teste2@gmail.com', '2025-11-19 00:05:16'),
(3, 'Willy Alves Borges', '000.000.000-01', 'M', '1993-06-02', 'teste1', 'rua teste1', 'MG', '01', 'teste1', 'teste1', '00000-001', '(00) 00000-0001', 'teste1@gmail.com', '2025-11-19 17:51:46'),
(4, 'willy almeida', '000.000.000-03', 'M', '1993-06-02', 'teste3', 'teste3', 'MG', '3', 'teste3', 'teste3', '00000-003', '(00) 00000-0003', 'teste3@gmail.com', '2025-11-19 18:02:10');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cpf` (`cpf`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
