-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 05/11/2025 às 15:29
-- Versão do servidor: 9.1.0
-- Versão do PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `indicador_pro`
--

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE indicador_pro
default character set utf8
default collate utf8_general_ci;

USE indicador_pro;

-- --------------------------------------------------------

--
-- Estrutura para tabela `data`
--

DROP TABLE IF EXISTS `data`;
CREATE TABLE IF NOT EXISTS `data` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_indicador` int NOT NULL,
  `date` date NOT NULL,
  `value` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `data` (`id_indicador`, `date`, `value`) VALUES
-- 2025-11-01
(1, '2025-11-01', 1200),
(2, '2025-11-01', 800),
(3, '2025-11-01', 500),
(4, '2025-11-01', 2000),
(5, '2025-11-01', 100),

-- 2025-11-02
(1, '2025-11-02', 1300),
(2, '2025-11-02', 850),
(3, '2025-11-02', 480),
(4, '2025-11-02', 2100),
(5, '2025-11-02', 110),

-- 2025-11-03
(1, '2025-11-03', 1250),
(2, '2025-11-03', 820),
(3, '2025-11-03', 510),
(4, '2025-11-03', 2050),
(5, '2025-11-03', 90),

-- 2025-11-04
(1, '2025-11-04', 1400),
(2, '2025-11-04', 870),
(3, '2025-11-04', 495),
(4, '2025-11-04', 2150),
(5, '2025-11-04', 95),

-- 2025-11-05
(1, '2025-11-05', 1350),
(2, '2025-11-05', 860),
(3, '2025-11-05', 505),
(4, '2025-11-05', 2200),
(5, '2025-11-05', 105),

-- 2025-11-06
(1, '2025-11-06', 1450),
(2, '2025-11-06', 880),
(3, '2025-11-06', 515),
(4, '2025-11-06', 2250),
(5, '2025-11-06', 115),

-- 2025-11-07
(1, '2025-11-07', 1500),
(2, '2025-11-07', 900),
(3, '2025-11-07', 520),
(4, '2025-11-07', 2300),
(5, '2025-11-07', 120);

-- --------------------------------------------------------

--
-- Estrutura para tabela `indicadores`
--

DROP TABLE IF EXISTS `indicadores`;
CREATE TABLE IF NOT EXISTS `indicadores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `unit` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'real',
  `id_user` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `indicadores`
--

INSERT INTO `indicadores` (`id`, `name`, `unit`, `id_user`) VALUES
(1, 'Faturamento', 'real', 1),
(2, 'Lucro', 'real', 1),
(3, 'Despesas', 'real', 1),
(4, 'Faturamento', 'real', 2),
(5, 'Lixo', 'real', 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`) VALUES
(1, 'Nikolas', 'nik@gmail.com', '$2y$10$9MuLGUtWznCEbADKCJyXB.T5scI40xKFiuubABtIC1l8EVi1dw0Ni'),
(2, 'Max', 'max@gmail.com', '$2y$10$u945GAJV2i5W.kh87lXnqelN2HukhJLh71VjemJ3iR30pW5F9euT.'),
(3, 'Nilva', 'nilva@gmail.com', '$2y$10$EZ6RmLhTg0wFwJEEWH38.OIkIKEZs1Bs58qQXozsOnRVPoz0dJr4W');
COMMIT;