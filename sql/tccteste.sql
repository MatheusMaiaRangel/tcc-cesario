-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 03/06/2025 às 02:35
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
-- Banco de dados: `tccteste`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `alunos`
--

CREATE TABLE `alunos` (
  `Nome_Aluno` varchar(100) DEFAULT NULL,
  `NomeSocial_Aluno` varchar(100) DEFAULT NULL,
  `Cpf_Aluno` varchar(14) DEFAULT NULL,
  `Cel_Aluno` varchar(15) DEFAULT NULL,
  `Senha_Aluno` varchar(255) DEFAULT NULL,
  `Email_Aluno` varchar(256) DEFAULT NULL,
  `Id_Aluno` int(11) NOT NULL,
  `fk_Turma_Id_Turma` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `alunos`
--

INSERT INTO `alunos` (`Nome_Aluno`, `NomeSocial_Aluno`, `Cpf_Aluno`, `Cel_Aluno`, `Senha_Aluno`, `Email_Aluno`, `Id_Aluno`, `fk_Turma_Id_Turma`) VALUES
('teste again', '', '33333333333', '19999999999', '$2y$10$sAkBnH3W.K..f3zBVYgju.jgfgb8DcY2IJHwywj5itBmRTgsrIsGC', 'ada@gmail.com', 32, NULL),
('gustavo', '', '62189131231', '19999999999', '$2y$10$v8KtmSHpHIXSH0SRrPbUXOgS9Hq4c3990St2tBdWzSOuhS16dbqg2', 'amam@gmail.com', 33, NULL),
('teste again', '', '75465644565', '19999999999', '$2y$10$viGE2SGOvmng0v/UotlacuboBfVDHLs0nGgZOGSekF2O0QByCi4Ku', 'test@gmail.com', 34, NULL),
('teste gpt', 'chat', '32323232323', '19999999999', '$2y$10$7/7a19RsZDMo7tG94UqNL.Cjj3BYDLgL0Lvjf.fpNTfok0Q13HLsO', 'maia@gmail.com', 36, 3),
('luigi', 'luisa', '96969669696', '19999999999', '$2y$10$76rT4QTcoYUiEdD3RG6iF.UW/e1IvsY..g79v0mLlaFbhnG8orjqa', 'lui@gmail.com', 37, 5),
('paulo', 'paulo', '15488186225', '19981220027', '$2y$10$6yODRdcAeTogUpZeXIC2YOOXyRmLSESUkV1Wpnl6SGlqVm7VBKBe6', 'coisa@Gmail.com', 38, 1),
('Maia', 'Maia', '66666666666', '19999999999', '$2y$10$DrptG4ElQHF08.4crzC6Uezt67ReWAFfsXIDmIaF.7e0Nr64u5Q7u', 'maia@gmail.com', 39, 3);

-- --------------------------------------------------------

--
-- Estrutura para tabela `coordenadores`
--

CREATE TABLE `coordenadores` (
  `Nome_Coord` varchar(100) DEFAULT NULL,
  `NomeSocial_Coord` varchar(100) DEFAULT NULL,
  `Cpf_Coord` varchar(14) DEFAULT NULL,
  `Cel_Coord` varchar(15) DEFAULT NULL,
  `Senha_Coord` varchar(255) DEFAULT NULL,
  `Email_Coord` varchar(256) DEFAULT NULL,
  `Id_Coord` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `coordenadores`
--

INSERT INTO `coordenadores` (`Nome_Coord`, `NomeSocial_Coord`, `Cpf_Coord`, `Cel_Coord`, `Senha_Coord`, `Email_Coord`, `Id_Coord`) VALUES
('coord master', 'o mestre', '00000000000', '19999999999', '$2y$10$KZkfwTt34ODwk8OB/m8hLeetv35tJsKnW/DZGUSgGcmfdjlEmHwdy', 'master@gmail.com', 5),
('Celoto', '', '11111111111', '19999999999', '$2y$10$DLwh53i3DFWHT6wjBbIyPOna9aLgG5x7I66fyBOhE82r8vLbFcJyO', 'celoto@gmail.com', 6),
('Calixto', '', '22222222222', '19999999999', '$2y$10$HVZZRKA3BmlSEcx2BiqCaevTQ3bGK3veKwa6TgqAJcqQHf8abF97.', 'calixto@gmail.com', 7);

-- --------------------------------------------------------

--
-- Estrutura para tabela `cursos`
--

CREATE TABLE `cursos` (
  `Id_Curso` int(11) NOT NULL,
  `Nome_Curso` varchar(100) DEFAULT NULL,
  `fk_Coordenadores_Id_Coord` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `cursos`
--

INSERT INTO `cursos` (`Id_Curso`, `Nome_Curso`, `fk_Coordenadores_Id_Coord`) VALUES
(1, 'Informática para Internet', 6),
(2, 'Administração', 7);

-- --------------------------------------------------------

--
-- Estrutura para tabela `ensina`
--

CREATE TABLE `ensina` (
  `fk_Turma_Id_Turma` int(11) DEFAULT NULL,
  `fk_Professores_Id_Prof` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `evento`
--

CREATE TABLE `evento` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `time_from` varchar(5) DEFAULT NULL,
  `time_to` varchar(5) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `dia` int(11) DEFAULT NULL,
  `mes` int(11) DEFAULT NULL,
  `ano` int(11) DEFAULT NULL,
  `fk_Turma_Id_Turma` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `evento`
--

INSERT INTO `evento` (`id`, `nome`, `time_from`, `time_to`, `descricao`, `tipo`, `dia`, `mes`, `ano`, `fk_Turma_Id_Turma`) VALUES
(1, 'prova', '12:12', '12:21', 'matéria: dor', 'Português', 2, 5, 2025, NULL),
(2, 'TESTE LOUCO', '14:41', '20:00', 'apenas loucuras', 'Urgente', 0, 0, 0, NULL),
(3, 'provinha', '15:00', '18:00', 'prova', 'Ciencias', 25, 5, 2025, NULL),
(4, 'Teste', '12:00', '13:00', 'teste', 'Ciencias', 28, 5, 2025, NULL),
(5, 'tetse', '12:00', '14:00', 'teste', 'Urgente', 2, 6, 2025, 3),
(6, 'Teste pra mostrar', '12:00', '15:00', 'teste', 'Urgente', 2, 6, 2025, 1),
(7, 'teste de cor', '15:00', '15:00', 'cor', 'louca', 2, 6, 2025, 3),
(8, 'teste de cor dnv', '15:00', '20:50', 'cor', 'allaa', 2, 6, 2025, 3),
(9, 'cor tesye', '19:10', '20:50', 'teste', 'bri', 2, 6, 2025, 3),
(10, 'Teste1 com o Paulão', '12:00', '20:50', 'teste', 'lepo', 2, 6, 2025, 3),
(11, 'tsetse da cor', '12:00', '13:00', 'corzada xd', 'pop', 2, 6, 2025, 3);

-- --------------------------------------------------------

--
-- Estrutura para tabela `eventos`
--

CREATE TABLE `eventos` (
  `Id_Evento` int(11) NOT NULL,
  `Tipo_Evento` varchar(100) DEFAULT NULL,
  `Data_Evento` date DEFAULT NULL,
  `Desc_Evento` varchar(500) DEFAULT NULL,
  `fk_Professores_Id_Prof` int(11) DEFAULT NULL,
  `fk_Coordenadores_Id_Coord` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `materias`
--

CREATE TABLE `materias` (
  `Id_Materia` int(11) NOT NULL,
  `Nome_Materia` varchar(100) NOT NULL,
  `fk_Coordenadores_Id_Coord` int(11) DEFAULT NULL,
  `cor_materia` varchar(7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `materias`
--

INSERT INTO `materias` (`Id_Materia`, `Nome_Materia`, `fk_Coordenadores_Id_Coord`, `cor_materia`) VALUES
(1, 'Ciencias', 5, NULL),
(3, 'Matemática', 5, NULL),
(4, 'Português', 5, NULL),
(5, 'Geografia', 5, NULL),
(7, 'louca', 5, NULL),
(9, 'bri', 5, NULL),
(10, 'lepo', 5, NULL),
(11, 'pop', 5, '#63db00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pertence`
--

CREATE TABLE `pertence` (
  `fk_Turma_Id_Turma` int(11) DEFAULT NULL,
  `fk_Serie_Id_Serie` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `professores`
--

CREATE TABLE `professores` (
  `Id_Prof` int(11) NOT NULL,
  `Email_Prof` varchar(256) DEFAULT NULL,
  `Senha_Prof` varchar(255) DEFAULT NULL,
  `Cel_Prof` varchar(15) NOT NULL,
  `Cpf_Prof` varchar(14) NOT NULL,
  `NomeSocial_Prof` varchar(100) DEFAULT NULL,
  `Nome_Prof` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `professores`
--

INSERT INTO `professores` (`Id_Prof`, `Email_Prof`, `Senha_Prof`, `Cel_Prof`, `Cpf_Prof`, `NomeSocial_Prof`, `Nome_Prof`) VALUES
(4, 'aa@gmail.com', '$2y$10$BhaAsgULD7sk6E0n3pQFweglcYSEThb3YWJdqpoP9shd7EDKwEIn6', '19999999999', '98988998322', '', 'prof'),
(5, 'manothi@gmail.com', '$2y$10$I0KJlBTZLGJsvHGpnnn.weYKUtKnloDNdJsN5RmJIaHrhna2CmGe6', '19999999999', '74747474747', 'thiaga', 'mendes');

-- --------------------------------------------------------

--
-- Estrutura para tabela `serie`
--

CREATE TABLE `serie` (
  `Id_Serie` int(11) NOT NULL,
  `Ano_Serie` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `serie`
--

INSERT INTO `serie` (`Id_Serie`, `Ano_Serie`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4);

-- --------------------------------------------------------

--
-- Estrutura para tabela `turma`
--

CREATE TABLE `turma` (
  `Id_Turma` int(11) NOT NULL,
  `Nome_Turma` varchar(100) DEFAULT NULL,
  `fk_Cursos_Id_Curso` int(11) DEFAULT NULL,
  `fk_Serie_Id_Serie` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `turma`
--

INSERT INTO `turma` (`Id_Turma`, `Nome_Turma`, `fk_Cursos_Id_Curso`, `fk_Serie_Id_Serie`) VALUES
(1, '1° Informática para Internet', 1, 1),
(2, '2° Informática para Internet', 1, 2),
(3, '3º Informática para Internet', 1, 3),
(4, '1° Administração', 2, 1),
(5, '2° Administração', 2, 2),
(6, '3° Administração', 2, 3);

-- --------------------------------------------------------

--
-- Estrutura para tabela `visualiza`
--

CREATE TABLE `visualiza` (
  `fk_Turma_Id_Turma` int(11) DEFAULT NULL,
  `fk_Eventos_Id_Evento` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `alunos`
--
ALTER TABLE `alunos`
  ADD PRIMARY KEY (`Id_Aluno`),
  ADD UNIQUE KEY `unique_cpf_aluno` (`Cpf_Aluno`),
  ADD KEY `FK_Alunos_2` (`fk_Turma_Id_Turma`);

--
-- Índices de tabela `coordenadores`
--
ALTER TABLE `coordenadores`
  ADD PRIMARY KEY (`Id_Coord`),
  ADD UNIQUE KEY `unique_cpf_coord` (`Cpf_Coord`);

--
-- Índices de tabela `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`Id_Curso`),
  ADD KEY `FK_Cursos_2` (`fk_Coordenadores_Id_Coord`);

--
-- Índices de tabela `ensina`
--
ALTER TABLE `ensina`
  ADD KEY `FK_Ensina_1` (`fk_Turma_Id_Turma`),
  ADD KEY `FK_Ensina_2` (`fk_Professores_Id_Prof`);

--
-- Índices de tabela `evento`
--
ALTER TABLE `evento`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`Id_Evento`),
  ADD KEY `FK_Eventos_2` (`fk_Professores_Id_Prof`),
  ADD KEY `FK_Eventos_3` (`fk_Coordenadores_Id_Coord`);

--
-- Índices de tabela `materias`
--
ALTER TABLE `materias`
  ADD PRIMARY KEY (`Id_Materia`);

--
-- Índices de tabela `pertence`
--
ALTER TABLE `pertence`
  ADD KEY `FK_Pertence_1` (`fk_Turma_Id_Turma`),
  ADD KEY `FK_Pertence_2` (`fk_Serie_Id_Serie`);

--
-- Índices de tabela `professores`
--
ALTER TABLE `professores`
  ADD PRIMARY KEY (`Id_Prof`),
  ADD UNIQUE KEY `unique_cpf_prof` (`Cpf_Prof`);

--
-- Índices de tabela `serie`
--
ALTER TABLE `serie`
  ADD PRIMARY KEY (`Id_Serie`);

--
-- Índices de tabela `turma`
--
ALTER TABLE `turma`
  ADD PRIMARY KEY (`Id_Turma`),
  ADD KEY `FK_Turma_2` (`fk_Cursos_Id_Curso`),
  ADD KEY `fk_Serie_Id_Serie` (`fk_Serie_Id_Serie`);

--
-- Índices de tabela `visualiza`
--
ALTER TABLE `visualiza`
  ADD KEY `FK_Visualiza_1` (`fk_Turma_Id_Turma`),
  ADD KEY `FK_Visualiza_2` (`fk_Eventos_Id_Evento`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `alunos`
--
ALTER TABLE `alunos`
  MODIFY `Id_Aluno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de tabela `coordenadores`
--
ALTER TABLE `coordenadores`
  MODIFY `Id_Coord` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `cursos`
--
ALTER TABLE `cursos`
  MODIFY `Id_Curso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `evento`
--
ALTER TABLE `evento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `eventos`
--
ALTER TABLE `eventos`
  MODIFY `Id_Evento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `materias`
--
ALTER TABLE `materias`
  MODIFY `Id_Materia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `professores`
--
ALTER TABLE `professores`
  MODIFY `Id_Prof` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `serie`
--
ALTER TABLE `serie`
  MODIFY `Id_Serie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `turma`
--
ALTER TABLE `turma`
  MODIFY `Id_Turma` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `alunos`
--
ALTER TABLE `alunos`
  ADD CONSTRAINT `FK_Alunos_2` FOREIGN KEY (`fk_Turma_Id_Turma`) REFERENCES `turma` (`Id_Turma`);

--
-- Restrições para tabelas `cursos`
--
ALTER TABLE `cursos`
  ADD CONSTRAINT `FK_Cursos_2` FOREIGN KEY (`fk_Coordenadores_Id_Coord`) REFERENCES `coordenadores` (`Id_Coord`);

--
-- Restrições para tabelas `ensina`
--
ALTER TABLE `ensina`
  ADD CONSTRAINT `FK_Ensina_1` FOREIGN KEY (`fk_Turma_Id_Turma`) REFERENCES `turma` (`Id_Turma`),
  ADD CONSTRAINT `FK_Ensina_2` FOREIGN KEY (`fk_Professores_Id_Prof`) REFERENCES `professores` (`Id_Prof`);

--
-- Restrições para tabelas `eventos`
--
ALTER TABLE `eventos`
  ADD CONSTRAINT `FK_Eventos_2` FOREIGN KEY (`fk_Professores_Id_Prof`) REFERENCES `professores` (`Id_Prof`),
  ADD CONSTRAINT `FK_Eventos_3` FOREIGN KEY (`fk_Coordenadores_Id_Coord`) REFERENCES `coordenadores` (`Id_Coord`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
