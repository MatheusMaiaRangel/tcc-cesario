-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 18-Jun-2025 às 16:37
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.2.12

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
-- Estrutura da tabela `alunos`
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
-- Extraindo dados da tabela `alunos`
--

INSERT INTO `alunos` (`Nome_Aluno`, `NomeSocial_Aluno`, `Cpf_Aluno`, `Cel_Aluno`, `Senha_Aluno`, `Email_Aluno`, `Id_Aluno`, `fk_Turma_Id_Turma`) VALUES
('Aluno Genérico', '', '66666666666', '19999999999', '$2y$10$hijvAyQO8vUR5p1DWY2MUuMzYrgHHFugF3dIcy3ZEp.GqElAvZHza', 'alunogenerico@gmail.com', 1, 12),
('Maia', '', '12313213213', '+5519999999999', '$2y$10$5j6YgaI/pcGyOZgaxlX.fOjhij0fP4/OnWDxNcl3uNU2kVDeIg4wO', 'maia@gmial.com', 2, 12);

-- --------------------------------------------------------

--
-- Estrutura da tabela `coordenadores`
--

CREATE TABLE `coordenadores` (
  `Nome_Coord` varchar(100) DEFAULT NULL,
  `NomeSocial_Coord` varchar(100) DEFAULT NULL,
  `Cpf_Coord` varchar(14) DEFAULT NULL,
  `Cel_Coord` varchar(15) DEFAULT NULL,
  `Senha_Coord` varchar(255) DEFAULT NULL,
  `Email_Coord` varchar(256) DEFAULT NULL,
  `Id_Coord` int(11) NOT NULL,
  `aprovado` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `coordenadores`
--

INSERT INTO `coordenadores` (`Nome_Coord`, `NomeSocial_Coord`, `Cpf_Coord`, `Cel_Coord`, `Senha_Coord`, `Email_Coord`, `Id_Coord`, `aprovado`) VALUES
('Coord Genérico', '', '00000000001', '19999999999', '$2y$10$RB/O/5e/mb72fctZTGYOguS9UTRTlmR9GSykVZ1FpmmFX.nZiBcMi', 'coordgenerico@gmail.com', 1, 0),
('coord master', 'o mestre', '00000000000', '19999999999', '$2y$10$KZkfwTt34ODwk8OB/m8hLeetv35tJsKnW/DZGUSgGcmfdjlEmHwdy', 'master@gmail.com', 5, 1),
('Celoto', '', '11111111111', '19999999999', '$2y$10$DLwh53i3DFWHT6wjBbIyPOna9aLgG5x7I66fyBOhE82r8vLbFcJyO', 'celoto@gmail.com', 6, 0),
('Calixto', '', '22222222222', '19999999999', '$2y$10$HVZZRKA3BmlSEcx2BiqCaevTQ3bGK3veKwa6TgqAJcqQHf8abF97.', 'calixto@gmail.com', 7, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `cursos`
--

CREATE TABLE `cursos` (
  `Id_Curso` int(11) NOT NULL,
  `Nome_Curso` varchar(100) DEFAULT NULL,
  `fk_Coordenadores_Id_Coord` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `cursos`
--

INSERT INTO `cursos` (`Id_Curso`, `Nome_Curso`, `fk_Coordenadores_Id_Coord`) VALUES
(1, 'Informática para Internet - Noturno', 6),
(2, 'Administração - Noturno', 7),
(3, 'Desenvolvimento de Sistemas - Noturno', 1),
(4, 'Desenvolvimento de Sistemas - Diurno', 1),
(5, 'Administração - Diurno', 1),
(6, 'Meio Ambiente - Diurno', 1),
(7, 'Química - Diurno', 1),
(8, 'Mecatrônica - Diurno', 1),
(9, 'Nutrição - Diurno', 1),
(10, 'Recursos Humanos - Noturno', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `diretores`
--

CREATE TABLE `diretores` (
  `Id_Diretor` int(11) NOT NULL,
  `Nome_Diretor` varchar(100) DEFAULT NULL,
  `NomeSocial_Diretor` varchar(100) DEFAULT NULL,
  `Cpf_Diretor` varchar(14) DEFAULT NULL,
  `Cel_Diretor` varchar(15) DEFAULT NULL,
  `Senha_Diretor` varchar(255) DEFAULT NULL,
  `Email_Diretor` varchar(256) DEFAULT NULL,
  `aprovado` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `diretores`
--

INSERT INTO `diretores` (`Id_Diretor`, `Nome_Diretor`, `NomeSocial_Diretor`, `Cpf_Diretor`, `Cel_Diretor`, `Senha_Diretor`, `Email_Diretor`, `aprovado`) VALUES
(1, 'Diretor teste', '', '72096587858', '+5519999999999', '$2y$10$SSaD.JwYL8.ILw2cr.kLleLxwWCaf2lkz4ltdZb6eYCnOPKqCQ1ku', 'diretor@gmail.com', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `ensina`
--

CREATE TABLE `ensina` (
  `fk_Turma_Id_Turma` int(11) DEFAULT NULL,
  `fk_Professores_Id_Prof` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `evento`
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

-- --------------------------------------------------------

--
-- Estrutura da tabela `materias`
--

CREATE TABLE `materias` (
  `Id_Materia` int(11) NOT NULL,
  `Nome_Materia` varchar(100) NOT NULL,
  `fk_Coordenadores_Id_Coord` int(11) DEFAULT NULL,
  `cor_materia` varchar(7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `materias`
--

INSERT INTO `materias` (`Id_Materia`, `Nome_Materia`, `fk_Coordenadores_Id_Coord`, `cor_materia`) VALUES
(1, 'Matemática', 5, '#010afe'),
(2, 'Português', 5, '#ff7300'),
(3, 'História', 5, '#fdda58'),
(4, 'Ciencias', 5, '#00ff1e'),
(5, 'Biologia', 5, '#01601d'),
(6, 'Geografia', 5, '#3c8cb4'),
(7, 'Inglês', 5, '#f95353');

-- --------------------------------------------------------

--
-- Estrutura da tabela `pertence`
--

CREATE TABLE `pertence` (
  `fk_Turma_Id_Turma` int(11) DEFAULT NULL,
  `fk_Serie_Id_Serie` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `predio`
--

CREATE TABLE `predio` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `predio`
--

INSERT INTO `predio` (`id`, `nome`) VALUES
(1, 'Sede'),
(2, 'Fatec'),
(3, 'Cesário');

-- --------------------------------------------------------

--
-- Estrutura da tabela `professores`
--

CREATE TABLE `professores` (
  `Id_Prof` int(11) NOT NULL,
  `Email_Prof` varchar(256) DEFAULT NULL,
  `Senha_Prof` varchar(255) DEFAULT NULL,
  `Cel_Prof` varchar(15) NOT NULL,
  `Cpf_Prof` varchar(14) NOT NULL,
  `NomeSocial_Prof` varchar(100) DEFAULT NULL,
  `Nome_Prof` varchar(100) DEFAULT NULL,
  `aprovado` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `professores`
--

INSERT INTO `professores` (`Id_Prof`, `Email_Prof`, `Senha_Prof`, `Cel_Prof`, `Cpf_Prof`, `NomeSocial_Prof`, `Nome_Prof`, `aprovado`) VALUES
(7, 'profteste@gmail.com', '$2y$10$JM0E045OlyHJQxqZCyjjne442l4KkokrdSrKEMrO9yNpn1eU8/YE.', '19999999999', '22222222201', '', 'Prof Teste', 0),
(8, 'profteste@gmail.com', '$2y$10$a0P8SlmLjWkEI1wuCzK1beyqKqxqZ/6hmVf2pBl76GKgWNpG5yq3m', '+5519999999999', '12341300002', '', 'Prof teste msm', 0),
(9, 'testando@gmail.com', '$2y$10$r1D5UOLwfTAHwWkUy3ippOeLTEQUTuRf1pqUGH/RNSEEON9QQsqPi', '+5519999999999', '32654215600', '', 'Prof teste dois', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `serie`
--

CREATE TABLE `serie` (
  `Id_Serie` int(11) NOT NULL,
  `Ano_Serie` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `serie`
--

INSERT INTO `serie` (`Id_Serie`, `Ano_Serie`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4);

-- --------------------------------------------------------

--
-- Estrutura da tabela `solicitacoes`
--

CREATE TABLE `solicitacoes` (
  `id` int(11) NOT NULL,
  `tipo` enum('professor','coordenador') NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `status` enum('pendente','aprovado','negado') DEFAULT 'pendente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `solicitacoes`
--

INSERT INTO `solicitacoes` (`id`, `tipo`, `id_usuario`, `status`) VALUES
(1, 'professor', 8, 'pendente'),
(2, 'professor', 9, 'aprovado'),
(3, 'professor', 10, 'negado');

-- --------------------------------------------------------

--
-- Estrutura da tabela `turma`
--

CREATE TABLE `turma` (
  `Id_Turma` int(11) NOT NULL,
  `Nome_Turma` varchar(100) DEFAULT NULL,
  `fk_Cursos_Id_Curso` int(11) DEFAULT NULL,
  `fk_Serie_Id_Serie` int(11) DEFAULT NULL,
  `id_predio` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `turma`
--

INSERT INTO `turma` (`Id_Turma`, `Nome_Turma`, `fk_Cursos_Id_Curso`, `fk_Serie_Id_Serie`, `id_predio`) VALUES
(1, '1° Informática para Internet - Noturno', 1, 1, 3),
(2, '2° Informática para Internet - Noturno', 1, 2, 3),
(3, '3º Informática para Internet - Noturno', 1, 3, 3),
(4, '1° Administração - Noturno', 2, 1, 3),
(5, '2° Administração - Noturno', 2, 2, 3),
(6, '3° Administração - Noturno', 2, 3, 3),
(7, '1° Desenvolvimento de Sistemas - Noturno', 3, 1, 3),
(8, '2° Desenvolvimento de Sistemas - Noturno', 3, 2, 3),
(9, '3° Desenvolvimento de Sistemas - Noturno', 3, 3, 3),
(10, '1º Desenvolvimento de Sistemas - Diurno', 4, 1, 2),
(11, '2º Desenvolvimento de Sistemas - Diurno', 4, 2, 2),
(12, '3º Desenvolvimento de Sistemas - Diurno', 4, 3, 2),
(13, '1º Administração - Diurno', 5, 1, 2),
(14, '2º Administração - Diurno', 5, 2, 2),
(15, '3º Administração - Diurno', 5, 3, 2),
(16, '1º Meio Ambiente - Diurno', 6, 1, 1),
(17, '2º Meio Ambiente - Diurno', 6, 2, 1),
(18, '3º Meio Ambiente - Diurno', 6, 3, 1),
(19, '1º Química - Diurno', 7, 1, 1),
(20, '2º Química - Diurno', 7, 2, 1),
(21, '3º Química - Diurno', 7, 3, 1),
(22, '1º Mecatrônica - Diurno', 8, 1, 1),
(23, '2º Mecatrônica - Diurno', 8, 2, 1),
(24, '3º Mecatrônica - Diurno', 8, 3, 1),
(25, '1º Nutrição - Diurno', 9, 1, 1),
(26, '2º Nutrição - Diurno', 9, 2, 1),
(27, '3º Nutrição - Diurno', 9, 3, 1),
(28, '1º Recursos Humanos - Noturno', 10, 1, 3),
(29, '2º Recursos Humanos - Noturno', 10, 2, 3),
(30, '3º Recursos Humanos - Noturno', 10, 3, 3);

-- --------------------------------------------------------

--
-- Estrutura da tabela `visualiza`
--

CREATE TABLE `visualiza` (
  `fk_Turma_Id_Turma` int(11) DEFAULT NULL,
  `fk_Eventos_Id_Evento` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `alunos`
--
ALTER TABLE `alunos`
  ADD PRIMARY KEY (`Id_Aluno`),
  ADD UNIQUE KEY `unique_cpf_aluno` (`Cpf_Aluno`),
  ADD KEY `FK_Alunos_2` (`fk_Turma_Id_Turma`);

--
-- Índices para tabela `coordenadores`
--
ALTER TABLE `coordenadores`
  ADD PRIMARY KEY (`Id_Coord`),
  ADD UNIQUE KEY `unique_cpf_coord` (`Cpf_Coord`);

--
-- Índices para tabela `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`Id_Curso`),
  ADD KEY `FK_Cursos_2` (`fk_Coordenadores_Id_Coord`);

--
-- Índices para tabela `diretores`
--
ALTER TABLE `diretores`
  ADD PRIMARY KEY (`Id_Diretor`),
  ADD UNIQUE KEY `unique_cpf_diretor` (`Cpf_Diretor`);

--
-- Índices para tabela `ensina`
--
ALTER TABLE `ensina`
  ADD KEY `FK_Ensina_1` (`fk_Turma_Id_Turma`),
  ADD KEY `FK_Ensina_2` (`fk_Professores_Id_Prof`);

--
-- Índices para tabela `evento`
--
ALTER TABLE `evento`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `materias`
--
ALTER TABLE `materias`
  ADD PRIMARY KEY (`Id_Materia`);

--
-- Índices para tabela `pertence`
--
ALTER TABLE `pertence`
  ADD KEY `FK_Pertence_1` (`fk_Turma_Id_Turma`),
  ADD KEY `FK_Pertence_2` (`fk_Serie_Id_Serie`);

--
-- Índices para tabela `predio`
--
ALTER TABLE `predio`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `professores`
--
ALTER TABLE `professores`
  ADD PRIMARY KEY (`Id_Prof`),
  ADD UNIQUE KEY `unique_cpf_prof` (`Cpf_Prof`);

--
-- Índices para tabela `serie`
--
ALTER TABLE `serie`
  ADD PRIMARY KEY (`Id_Serie`);

--
-- Índices para tabela `solicitacoes`
--
ALTER TABLE `solicitacoes`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `turma`
--
ALTER TABLE `turma`
  ADD PRIMARY KEY (`Id_Turma`),
  ADD KEY `FK_Turma_2` (`fk_Cursos_Id_Curso`),
  ADD KEY `fk_Serie_Id_Serie` (`fk_Serie_Id_Serie`),
  ADD KEY `fk_turma_predio` (`id_predio`);

--
-- Índices para tabela `visualiza`
--
ALTER TABLE `visualiza`
  ADD KEY `FK_Visualiza_1` (`fk_Turma_Id_Turma`),
  ADD KEY `FK_Visualiza_2` (`fk_Eventos_Id_Evento`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `alunos`
--
ALTER TABLE `alunos`
  MODIFY `Id_Aluno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `coordenadores`
--
ALTER TABLE `coordenadores`
  MODIFY `Id_Coord` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `cursos`
--
ALTER TABLE `cursos`
  MODIFY `Id_Curso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `diretores`
--
ALTER TABLE `diretores`
  MODIFY `Id_Diretor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `evento`
--
ALTER TABLE `evento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `materias`
--
ALTER TABLE `materias`
  MODIFY `Id_Materia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `predio`
--
ALTER TABLE `predio`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `professores`
--
ALTER TABLE `professores`
  MODIFY `Id_Prof` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `serie`
--
ALTER TABLE `serie`
  MODIFY `Id_Serie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `solicitacoes`
--
ALTER TABLE `solicitacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `turma`
--
ALTER TABLE `turma`
  MODIFY `Id_Turma` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `alunos`
--
ALTER TABLE `alunos`
  ADD CONSTRAINT `FK_Alunos_2` FOREIGN KEY (`fk_Turma_Id_Turma`) REFERENCES `turma` (`Id_Turma`);

--
-- Limitadores para a tabela `cursos`
--
ALTER TABLE `cursos`
  ADD CONSTRAINT `FK_Cursos_2` FOREIGN KEY (`fk_Coordenadores_Id_Coord`) REFERENCES `coordenadores` (`Id_Coord`);

--
-- Limitadores para a tabela `ensina`
--
ALTER TABLE `ensina`
  ADD CONSTRAINT `FK_Ensina_1` FOREIGN KEY (`fk_Turma_Id_Turma`) REFERENCES `turma` (`Id_Turma`),
  ADD CONSTRAINT `FK_Ensina_2` FOREIGN KEY (`fk_Professores_Id_Prof`) REFERENCES `professores` (`Id_Prof`);

--
-- Limitadores para a tabela `turma`
--
ALTER TABLE `turma`
  ADD CONSTRAINT `fk_turma_predio` FOREIGN KEY (`id_predio`) REFERENCES `predio` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
