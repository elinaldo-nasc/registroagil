-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 30/09/2025 às 22:03
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
-- Banco de dados: `registro_agil`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome_completo` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `cep` varchar(9) NOT NULL,
  `logradouro` varchar(255) NOT NULL,
  `bairro` varchar(100) NOT NULL,
  `localidade` varchar(100) NOT NULL,
  `uf` varchar(2) NOT NULL,
  `complemento` varchar(255) DEFAULT NULL,
  `numero` varchar(20) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `foto_perfil` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome_completo`, `email`, `cpf`, `telefone`, `cep`, `logradouro`, `bairro`, `localidade`, `uf`, `complemento`, `numero`, `senha`, `role`, `foto_perfil`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Elinaldo Oliveira', 'elinaldo.oliveira@outlook.com', '062.147.474-63', '81984792068', '53330710', 'Rua Lagoa Azul', 'Ouro Preto', 'Olinda', 'PE', 'casa', '200', '$2y$10$2qeVJBzjW2xQPcX17xi4v.WE/R61ag8UpAQYBF5TlqHgbCfIgZIqm', 'admin', NULL, '2025-09-20 23:57:06', '2025-09-27 16:32:44', NULL),
(2, 'Ana Silva Santos', 'ana.santos.ac@email.com', '123.456.789-01', '(68) 99801-1001', '69900-000', 'Rua das Flores', 'Centro', 'Rio Branco', 'AC', '', '100', '$2y$10$u6y4TalAGP9K/P2au2lYBODvBduKIDQHavrIHDlGwyYIyKRnxzBNy', 'user', NULL, '2025-09-30 19:17:43', '2025-09-30 19:17:43', NULL),
(3, 'Carlos Oliveira Lima', 'carlos.lima.ac@email.com', '234.567.890-12', '(68) 99802-1002', '69915-000', 'Avenida Brasil', 'Bosque', 'Rio Branco', 'AC', 'Apto 101', '200', '$2y$10$u6y4TalAGP9K/P2au2lYBODvBduKIDQHavrIHDlGwyYIyKRnxzBNy', 'user', NULL, '2025-09-30 19:17:43', '2025-09-30 19:17:43', NULL),
(4, 'Maria Costa Souza', 'maria.souza.ac@email.com', '345.678.901-23', '(68) 99803-1003', '69920-000', 'Rua Amazonas', 'Tucumã', 'Rio Branco', 'AC', '', '300', '$2y$10$u6y4TalAGP9K/P2au2lYBODvBduKIDQHavrIHDlGwyYIyKRnxzBNy', 'user', NULL, '2025-09-30 19:17:43', '2025-09-30 19:17:43', NULL),
(5, 'João Santos Ferreira', 'joao.ferreira.al@email.com', '456.789.012-34', '(82) 99801-2001', '57000-000', 'Rua do Comércio', 'Centro', 'Maceió', 'AL', '', '150', '$2y$10$u6y4TalAGP9K/P2au2lYBODvBduKIDQHavrIHDlGwyYIyKRnxzBNy', 'user', NULL, '2025-09-30 19:17:43', '2025-09-30 19:17:43', NULL),
(6, 'Paula Lima Silva', 'paula.silva.al@email.com', '567.890.123-45', '(82) 99802-2002', '57010-000', 'Avenida Fernandes Lima', 'Farol', 'Maceió', 'AL', 'Casa 2', '250', '$2y$10$u6y4TalAGP9K/P2au2lYBODvBduKIDQHavrIHDlGwyYIyKRnxzBNy', 'user', NULL, '2025-09-30 19:17:43', '2025-09-30 19:17:43', NULL),
(7, 'Roberto Costa Nunes', 'roberto.nunes.al@email.com', '678.901.234-56', '(82) 99803-2003', '57020-000', 'Rua da Praia', 'Pajuçara', 'Maceió', 'AL', '', '350', '$2y$10$u6y4TalAGP9K/P2au2lYBODvBduKIDQHavrIHDlGwyYIyKRnxzBNy', 'user', NULL, '2025-09-30 19:17:43', '2025-09-30 19:17:43', NULL),
(8, 'Fernanda Alves Dias', 'fernanda.dias.ap@email.com', '789.012.345-67', '(96) 99801-3001', '68900-000', 'Avenida FAB', 'Centro', 'Macapá', 'AP', '', '180', '$2y$10$u6y4TalAGP9K/P2au2lYBODvBduKIDQHavrIHDlGwyYIyKRnxzBNy', 'user', NULL, '2025-09-30 19:17:43', '2025-09-30 19:17:43', NULL),
(9, 'Lucas Silva Martins', 'lucas.martins.ap@email.com', '890.123.456-78', '(96) 99802-3002', '68906-000', 'Rua Hamilton Silva', 'Santa Rita', 'Macapá', 'AP', 'Bloco A', '280', '$2y$10$u6y4TalAGP9K/P2au2lYBODvBduKIDQHavrIHDlGwyYIyKRnxzBNy', 'user', NULL, '2025-09-30 19:17:43', '2025-09-30 19:17:43', NULL),
(10, 'Juliana Santos Rocha', 'juliana.rocha.ap@email.com', '901.234.567-89', '(96) 99803-3003', '68908-000', 'Rua Cândido Mendes', 'Buritizal', 'Macapá', 'AP', '', '380', '$2y$10$u6y4TalAGP9K/P2au2lYBODvBduKIDQHavrIHDlGwyYIyKRnxzBNy', 'user', NULL, '2025-09-30 19:17:43', '2025-09-30 19:17:43', NULL),
(11, 'Pedro Oliveira Cruz', 'pedro.cruz.am@email.com', '012.345.678-90', '(92) 99801-4001', '69000-000', 'Avenida Eduardo Ribeiro', 'Centro', 'Manaus', 'AM', '', '120', '$2y$10$u6y4TalAGP9K/P2au2lYBODvBduKIDQHavrIHDlGwyYIyKRnxzBNy', 'user', NULL, '2025-09-30 19:17:43', '2025-09-30 19:17:43', NULL),
(12, 'Amanda Costa Lima', 'amanda.lima.am@email.com', '123.456.780-01', '(92) 99802-4002', '69010-000', 'Rua Barroso', 'Centro', 'Manaus', 'AM', 'Sala 5', '220', '$2y$10$u6y4TalAGP9K/P2au2lYBODvBduKIDQHavrIHDlGwyYIyKRnxzBNy', 'user', NULL, '2025-09-30 19:17:43', '2025-09-30 19:17:43', NULL),
(13, 'Rafael Santos Dias', 'rafael.dias.am@email.com', '234.567.801-12', '(92) 99803-4003', '69020-000', 'Avenida Djalma Batista', 'Chapada', 'Manaus', 'AM', '', '320', '$2y$10$u6y4TalAGP9K/P2au2lYBODvBduKIDQHavrIHDlGwyYIyKRnxzBNy', 'user', NULL, '2025-09-30 19:17:43', '2025-09-30 19:17:43', NULL),
(14, 'Beatriz Lima Souza', 'beatriz.souza.ba@email.com', '345.678.012-23', '(71) 99801-5001', '40000-000', 'Rua Chile', 'Centro', 'Salvador', 'BA', '', '140', '$2y$10$u6y4TalAGP9K/P2au2lYBODvBduKIDQHavrIHDlGwyYIyKRnxzBNy', 'user', NULL, '2025-09-30 19:17:43', '2025-09-30 19:17:43', NULL),
(15, 'Gabriel Silva Santos', 'gabriel.santos.ba@email.com', '456.789.123-34', '(71) 99802-5002', '40010-000', 'Avenida Sete', 'Barra', 'Salvador', 'BA', 'Apto 202', '240', '$2y$10$u6y4TalAGP9K/P2au2lYBODvBduKIDQHavrIHDlGwyYIyKRnxzBNy', 'user', NULL, '2025-09-30 19:17:43', '2025-09-30 19:17:43', NULL),
(16, 'Carolina Costa Nunes', 'carolina.nunes.ba@email.com', '567.890.234-45', '(71) 99803-5003', '40020-000', 'Rua da Paz', 'Pituba', 'Salvador', 'BA', '', '340', '$2y$10$u6y4TalAGP9K/P2au2lYBODvBduKIDQHavrIHDlGwyYIyKRnxzBNy', 'user', NULL, '2025-09-30 19:17:43', '2025-09-30 19:17:43', NULL),
(17, 'Daniel Oliveira Rocha', 'daniel.rocha.ce@email.com', '678.901.345-56', '(85) 99801-6001', '60000-000', 'Rua Senador Pompeu', 'Centro', 'Fortaleza', 'CE', '', '160', '$2y$10$u6y4TalAGP9K/P2au2lYBODvBduKIDQHavrIHDlGwyYIyKRnxzBNy', 'user', NULL, '2025-09-30 19:17:43', '2025-09-30 19:17:43', NULL),
(18, 'Larissa Santos Cruz', 'larissa.cruz.ce@email.com', '789.012.456-67', '(85) 99802-6002', '60010-000', 'Avenida Beira Mar', 'Meireles', 'Fortaleza', 'CE', 'Casa 3', '260', '$2y$10$u6y4TalAGP9K/P2au2lYBODvBduKIDQHavrIHDlGwyYIyKRnxzBNy', 'user', NULL, '2025-09-30 19:17:43', '2025-09-30 19:17:43', NULL),
(19, 'Thiago Lima Dias', 'thiago.dias.ce@email.com', '890.123.567-78', '(85) 99803-6003', '60020-000', 'Rua José Vilar', 'Aldeota', 'Fortaleza', 'CE', '', '360', '$2y$10$u6y4TalAGP9K/P2au2lYBODvBduKIDQHavrIHDlGwyYIyKRnxzBNy', 'user', NULL, '2025-09-30 19:17:43', '2025-09-30 19:17:43', NULL),
(20, 'Isabela Costa Silva', 'isabela.silva.df@email.com', '901.234.678-89', '(61) 99801-7001', '70000-000', 'Setor Comercial Sul', 'Asa Sul', 'Brasília', 'DF', '', '170', '$2y$10$u6y4TalAGP9K/P2au2lYBODvBduKIDQHavrIHDlGwyYIyKRnxzBNy', 'user', NULL, '2025-09-30 19:17:43', '2025-09-30 19:17:43', NULL),
(21, 'Gustavo Santos Martins', 'gustavo.martins.df@email.com', '012.345.789-90', '(61) 99802-7002', '70010-000', 'Setor Bancário Norte', 'Asa Norte', 'Brasília', 'DF', 'Sala 10', '270', '$2y$10$u6y4TalAGP9K/P2au2lYBODvBduKIDQHavrIHDlGwyYIyKRnxzBNy', 'user', NULL, '2025-09-30 19:17:43', '2025-09-30 19:17:43', NULL),
(22, 'Mariana Lima Souza', 'mariana.souza.df@email.com', '123.456.890-01', '(61) 99803-7003', '71000-000', 'Avenida Central', 'Taguatinga', 'Brasília', 'DF', '', '370', '$2y$10$u6y4TalAGP9K/P2au2lYBODvBduKIDQHavrIHDlGwyYIyKRnxzBNy', 'user', NULL, '2025-09-30 19:17:43', '2025-09-30 19:17:43', NULL),
(23, 'Felipe Oliveira Nunes', 'felipe.nunes.es@email.com', '234.567.901-12', '(27) 99801-8001', '29000-000', 'Avenida Jerônimo Monteiro', 'Centro', 'Vitória', 'ES', '', '190', '$2y$10$u6y4TalAGP9K/P2au2lYBODvBduKIDQHavrIHDlGwyYIyKRnxzBNy', 'user', NULL, '2025-09-30 19:17:43', '2025-09-30 19:17:43', NULL),
(24, 'Camila Santos Rocha', 'camila.rocha.es@email.com', '345.678.012-24', '(27) 99802-8002', '29010-000', 'Rua Sete', 'Praia do Canto', 'Vitória', 'ES', 'Apto 303', '290', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(25, 'Bruno Costa Lima', 'bruno.lima.es@email.com', '456.789.123-35', '(27) 99803-8003', '29020-000', 'Avenida Nossa Senhora da Penha', 'Praia do Suá', 'Vitória', 'ES', '', '390', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(26, 'Letícia Silva Santos', 'leticia.santos.go@email.com', '567.890.234-46', '(62) 99801-9001', '74000-000', 'Avenida Goiás', 'Centro', 'Goiânia', 'GO', '', '110', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(27, 'Rodrigo Lima Cruz', 'rodrigo.cruz.go@email.com', '678.901.345-57', '(62) 99802-9002', '74010-000', 'Rua 1', 'Setor Oeste', 'Goiânia', 'GO', 'Casa 4', '210', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(28, 'Patrícia Costa Dias', 'patricia.dias.go@email.com', '789.012.456-68', '(62) 99803-9003', '74020-000', 'Avenida T-9', 'Setor Bueno', 'Goiânia', 'GO', '', '310', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(29, 'André Santos Silva', 'andre.silva.ma@email.com', '890.123.567-79', '(98) 99801-0001', '65000-000', 'Rua Grande', 'Centro', 'São Luís', 'MA', '', '130', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(30, 'Bianca Lima Martins', 'bianca.martins.ma@email.com', '901.234.678-90', '(98) 99802-0002', '65010-000', 'Avenida Colares Moreira', 'Renascença', 'São Luís', 'MA', 'Apto 404', '230', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(31, 'Diego Costa Souza', 'diego.souza.ma@email.com', '012.345.789-91', '(98) 99803-0003', '65020-000', 'Rua do Passeio', 'Calhau', 'São Luís', 'MA', '', '330', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(32, 'Vanessa Silva Nunes', 'vanessa.nunes.mt@email.com', '123.456.901-01', '(65) 99801-1101', '78000-000', 'Avenida Getúlio Vargas', 'Centro Norte', 'Cuiabá', 'MT', '', '145', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(33, 'Marcelo Lima Rocha', 'marcelo.rocha.mt@email.com', '234.567.012-12', '(65) 99802-1102', '78010-000', 'Rua 13 de Junho', 'Centro Sul', 'Cuiabá', 'MT', 'Casa 5', '245', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(34, 'Tatiana Costa Cruz', 'tatiana.cruz.mt@email.com', '345.678.123-23', '(65) 99803-1103', '78020-000', 'Avenida Fernando Corrêa', 'Coxipó', 'Cuiabá', 'MT', '', '345', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(35, 'Vinícius Santos Lima', 'vinicius.lima.ms@email.com', '456.789.234-34', '(67) 99801-1201', '79000-000', 'Avenida Afonso Pena', 'Centro', 'Campo Grande', 'MS', '', '155', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(36, 'Natália Oliveira Dias', 'natalia.dias.ms@email.com', '567.890.345-45', '(67) 99802-1202', '79010-000', 'Rua 14 de Julho', 'Centro', 'Campo Grande', 'MS', 'Apto 505', '255', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(37, 'Eduardo Costa Silva', 'eduardo.silva.ms@email.com', '678.901.456-56', '(67) 99803-1203', '79020-000', 'Avenida Tamandaré', 'Jardim dos Estados', 'Campo Grande', 'MS', '', '355', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(38, 'Priscila Lima Santos', 'priscila.santos.mg@email.com', '789.012.567-67', '(31) 99801-1301', '30000-000', 'Avenida Afonso Pena', 'Centro', 'Belo Horizonte', 'MG', '', '165', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(39, 'Renato Silva Cruz', 'renato.cruz.mg@email.com', '890.123.678-78', '(31) 99802-1302', '30010-000', 'Rua da Bahia', 'Centro', 'Belo Horizonte', 'MG', 'Casa 6', '265', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(40, 'Adriana Costa Martins', 'adriana.martins.mg@email.com', '901.234.789-89', '(31) 99803-1303', '30020-000', 'Avenida Raja Gabaglia', 'Luxemburgo', 'Belo Horizonte', 'MG', '', '365', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(41, 'Leonardo Santos Souza', 'leonardo.souza.pa@email.com', '012.345.890-90', '(91) 99801-1401', '66000-000', 'Avenida Presidente Vargas', 'Campina', 'Belém', 'PA', '', '175', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(42, 'Gabriela Lima Nunes', 'gabriela.nunes.pa@email.com', '123.456.012-01', '(91) 99802-1402', '66010-000', 'Rua João Balbi', 'Umarizal', 'Belém', 'PA', 'Apto 606', '275', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(43, 'Matheus Costa Rocha', 'matheus.rocha.pa@email.com', '234.567.123-12', '(91) 99803-1403', '66020-000', 'Travessa Padre Eutíquio', 'Batista Campos', 'Belém', 'PA', '', '375', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(44, 'Aline Silva Lima', 'aline.lima.pb@email.com', '345.678.234-23', '(83) 99801-1501', '58000-000', 'Avenida Epitácio Pessoa', 'Manaíra', 'João Pessoa', 'PB', '', '185', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(45, 'Fábio Santos Cruz', 'fabio.cruz.pb@email.com', '456.789.345-34', '(83) 99802-1502', '58010-000', 'Rua das Trincheiras', 'Centro', 'João Pessoa', 'PB', 'Casa 7', '285', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(46, 'Simone Costa Dias', 'simone.dias.pb@email.com', '567.890.456-45', '(83) 99803-1503', '58020-000', 'Avenida Cabo Branco', 'Cabo Branco', 'João Pessoa', 'PB', '', '385', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(47, 'Alexandre Lima Silva', 'alexandre.silva.pr@email.com', '678.901.567-56', '(41) 99801-1601', '80000-000', 'Rua XV de Novembro', 'Centro', 'Curitiba', 'PR', '', '195', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(48, 'Cláudia Santos Martins', 'claudia.martins.pr@email.com', '789.012.678-67', '(41) 99802-1602', '80010-000', 'Avenida Sete de Setembro', 'Batel', 'Curitiba', 'PR', 'Apto 707', '295', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(49, 'Ricardo Costa Souza', 'ricardo.souza.pr@email.com', '890.123.789-78', '(41) 99803-1603', '80020-000', 'Rua Comendador Araújo', 'Centro Cívico', 'Curitiba', 'PR', '', '395', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(50, 'Fernanda Silva Nunes', 'fernanda.nunes.pe@email.com', '901.234.890-89', '(81) 99801-1701', '50000-000', 'Avenida Boa Viagem', 'Boa Viagem', 'Recife', 'PE', '', '105', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(51, 'Henrique Lima Rocha', 'henrique.rocha.pe@email.com', '012.345.901-90', '(81) 99802-1702', '50010-000', 'Rua do Hospício', 'Boa Vista', 'Recife', 'PE', 'Casa 8', '205', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(52, 'Mônica Costa Cruz', 'monica.cruz.pe@email.com', '123.456.123-01', '(81) 99803-1703', '50020-000', 'Avenida Rosa e Silva', 'Aflitos', 'Recife', 'PE', '', '305', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(53, 'Júlio Santos Silva', 'julio.silva.pi@email.com', '234.567.234-12', '(86) 99801-1801', '64000-000', 'Avenida Frei Serafim', 'Centro', 'Teresina', 'PI', '', '115', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(54, 'Raquel Lima Dias', 'raquel.dias.pi@email.com', '345.678.345-23', '(86) 99802-1802', '64010-000', 'Rua Álvaro Mendes', 'Centro', 'Teresina', 'PI', 'Apto 808', '215', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(55, 'Sérgio Costa Martins', 'sergio.martins.pi@email.com', '456.789.456-34', '(86) 99803-1803', '64020-000', 'Avenida Joaquim Nelson', 'Fátima', 'Teresina', 'PI', '', '315', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(56, 'Denise Silva Souza', 'denise.souza.rj@email.com', '567.890.567-45', '(21) 99801-1901', '20000-000', 'Avenida Rio Branco', 'Centro', 'Rio de Janeiro', 'RJ', '', '125', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(57, 'Márcio Lima Nunes', 'marcio.nunes.rj@email.com', '678.901.678-56', '(21) 99802-1902', '20010-000', 'Rua Visconde de Pirajá', 'Ipanema', 'Rio de Janeiro', 'RJ', 'Casa 9', '225', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(58, 'Luciana Costa Rocha', 'luciana.rocha.rj@email.com', '789.012.789-67', '(21) 99803-1903', '20020-000', 'Avenida Atlântica', 'Copacabana', 'Rio de Janeiro', 'RJ', '', '325', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(59, 'Paulo Santos Lima', 'paulo.lima.rn@email.com', '890.123.890-78', '(84) 99801-2001', '59000-000', 'Avenida Hermes da Fonseca', 'Tirol', 'Natal', 'RN', '', '135', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(60, 'Jéssica Silva Cruz', 'jessica.cruz.rn@email.com', '901.234.901-89', '(84) 99802-2002', '59010-000', 'Rua Chile', 'Cidade Alta', 'Natal', 'RN', 'Apto 909', '235', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(61, 'Wagner Costa Dias', 'wagner.dias.rn@email.com', '012.345.012-90', '(84) 99803-2003', '59020-000', 'Avenida Engenheiro Roberto Freire', 'Ponta Negra', 'Natal', 'RN', '', '335', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(62, 'Sandra Lima Martins', 'sandra.martins.rs@email.com', '123.456.234-01', '(51) 99801-2101', '90000-000', 'Avenida Borges de Medeiros', 'Centro', 'Porto Alegre', 'RS', '', '148', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(63, 'Ronaldo Silva Souza', 'ronaldo.souza.rs@email.com', '234.567.345-12', '(51) 99802-2102', '90010-000', 'Rua dos Andradas', 'Centro Histórico', 'Porto Alegre', 'RS', 'Casa 10', '248', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(64, 'Cristina Costa Nunes', 'cristina.nunes.rs@email.com', '345.678.456-23', '(51) 99803-2103', '90020-000', 'Avenida Ipiranga', 'Azenha', 'Porto Alegre', 'RS', '', '348', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(65, 'Vieira Santos Rocha', 'vieira.rocha.ro@email.com', '456.789.567-34', '(69) 99801-2201', '76800-000', 'Avenida Jorge Teixeira', 'Centro', 'Porto Velho', 'RO', '', '158', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(66, 'Eliane Lima Silva', 'eliane.silva.ro@email.com', '567.890.678-45', '(69) 99802-2202', '76801-000', 'Rua José de Alencar', 'Centro', 'Porto Velho', 'RO', 'Apto 1010', '258', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(67, 'Antônio Costa Cruz', 'antonio.cruz.ro@email.com', '678.901.789-56', '(69) 99803-2203', '76802-000', 'Avenida Farquar', 'Pedrinhas', 'Porto Velho', 'RO', '', '358', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(68, 'Valéria Silva Lima', 'valeria.lima.rr@email.com', '789.012.890-67', '(95) 99801-2301', '69300-000', 'Avenida Ville Roy', 'Centro', 'Boa Vista', 'RR', '', '168', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(69, 'Edson Santos Dias', 'edson.dias.rr@email.com', '890.123.901-78', '(95) 99802-2302', '69301-000', 'Rua Coronel Pinto', 'Centro', 'Boa Vista', 'RR', 'Casa 11', '268', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(70, 'Silvia Costa Martins', 'silvia.martins.rr@email.com', '901.234.012-89', '(95) 99803-2303', '69302-000', 'Avenida Capitão Ene Garcez', 'São Francisco', 'Boa Vista', 'RR', '', '368', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(71, 'Rogério Lima Souza', 'rogerio.souza.sc@email.com', '012.346.123-90', '(48) 99801-2401', '88000-000', 'Rua Felipe Schmidt', 'Centro', 'Florianópolis', 'SC', '', '178', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(72, 'Andréia Silva Nunes', 'andreia.nunes.sc@email.com', '123.457.234-01', '(48) 99802-2402', '88010-000', 'Avenida Beira Mar Norte', 'Centro', 'Florianópolis', 'SC', 'Apto 1111', '278', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(73, 'Cláudio Costa Rocha', 'claudio.rocha.sc@email.com', '234.568.345-12', '(48) 99803-2403', '88020-000', 'Rua Bocaiúva', 'Centro', 'Florianópolis', 'SC', '', '378', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(74, 'Débora Santos Cruz', 'debora.cruz.sp@email.com', '345.679.456-23', '(11) 99801-2501', '01000-000', 'Avenida Paulista', 'Bela Vista', 'São Paulo', 'SP', '', '188', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(75, 'Fernando Lima Dias', 'fernando.dias.sp@email.com', '456.780.567-34', '(11) 99802-2502', '01010-000', 'Rua Augusta', 'Consolação', 'São Paulo', 'SP', 'Casa 12', '288', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(76, 'Helena Costa Silva', 'helena.silva.sp@email.com', '567.891.678-45', '(11) 99803-2503', '01020-000', 'Avenida Faria Lima', 'Pinheiros', 'São Paulo', 'SP', '', '388', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(77, 'Maurício Silva Martins', 'mauricio.martins.se@email.com', '678.902.789-56', '(79) 99801-2601', '49000-000', 'Rua João Pessoa', 'Centro', 'Aracaju', 'SE', '', '198', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(78, 'Flávia Lima Souza', 'flavia.souza.se@email.com', '789.013.890-67', '(79) 99802-2602', '49010-000', 'Avenida Beira Mar', 'Atalaia', 'Aracaju', 'SE', 'Apto 1212', '298', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(79, 'Olavo Costa Nunes', 'olavo.nunes.se@email.com', '890.124.901-78', '(79) 99803-2603', '49020-000', 'Rua Laranjeiras', 'Centro', 'Aracaju', 'SE', '', '398', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(80, 'Regina Santos Rocha', 'regina.rocha.to@email.com', '901.235.012-89', '(63) 99801-2701', '77000-000', 'Avenida Joaquim Teotônio Segurado', 'Plano Diretor Sul', 'Palmas', 'TO', '', '108', '$2y$10$ul1/nL/lz8kULJq8MeNhm.PCIsR03uVisyikSaGve/rCFcve0sE7.', 'user', NULL, '2025-09-30 19:23:10', '2025-09-30 19:23:10', NULL),
(81, 'Nelson Lima Cruz', 'nelson.cruz.to@email.com', '012.346.123-91', '(63) 99802-2702', '77001-000', 'Rua NS 1', 'Plano Diretor Norte', 'Palmas', 'TO', 'Casa 13', '208', '$2y$10$rvPH7E/oUTvwecQo/nXWIe8lkiqWN9YBb8AHdIEaiy/p5msbFK4xy', 'user', NULL, '2025-09-30 19:23:58', '2025-09-30 19:23:58', NULL),
(82, 'Vera Costa Dias', 'vera.dias.to@email.com', '123.457.234-02', '(63) 99803-2703', '77002-000', 'Avenida LO 1', 'Taquari', 'Palmas', 'TO', '', '308', '$2y$10$rvPH7E/oUTvwecQo/nXWIe8lkiqWN9YBb8AHdIEaiy/p5msbFK4xy', 'user', NULL, '2025-09-30 19:23:58', '2025-09-30 19:23:58', NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_cpf` (`cpf`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
