-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 16/06/2025 às 02:54
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
-- Banco de dados: `japiwatch`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `administrador`
--

CREATE TABLE `administrador` (
  `ID_Administrador` int(11) NOT NULL,
  `Nome_Completo` varchar(255) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Senha` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `administrador`
--

INSERT INTO `administrador` (`ID_Administrador`, `Nome_Completo`, `Username`, `Email`, `Senha`) VALUES
(1, 'Admin Geral', 'admin', 'admin@japiwatch.com', 'admin_senha123');

-- --------------------------------------------------------

--
-- Estrutura para tabela `comentarios`
--

CREATE TABLE `comentarios` (
  `ID_Comentario` int(11) NOT NULL,
  `Conteudo_Comentario` text NOT NULL,
  `Data_Comentario` datetime NOT NULL DEFAULT current_timestamp(),
  `ID_Categoria` int(11) NOT NULL,
  `Categoria` enum('Usuario','Especialista') NOT NULL,
  `ID_Postagem` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `comentarios`
--

INSERT INTO `comentarios` (`ID_Comentario`, `Conteudo_Comentario`, `Data_Comentario`, `ID_Categoria`, `Categoria`, `ID_Postagem`) VALUES
(5, 'Ótimo registro! Eles são importantes no controle de insetos.', '2025-06-15 14:18:48', 9, 'Usuario', 14),
(6, 'Importante alerta!', '2025-06-15 14:19:18', 9, 'Usuario', 13),
(7, 'Nunca tinha visto um tão grande!', '2025-06-15 14:19:49', 9, 'Usuario', 17),
(8, 'Já vi esse mesmo grupo na semana passada!', '2025-06-15 14:20:50', 8, 'Usuario', 10),
(9, 'Já reportei ao instituto ambiental.', '2025-06-15 14:21:17', 8, 'Usuario', 16),
(10, 'Linda foto! Qual câmera usou?', '2025-06-15 14:22:18', 7, 'Usuario', 11),
(11, 'Precisamos de mais estudos sobre seu impacto.', '2025-06-15 14:22:58', 7, 'Usuario', 19),
(12, 'Avistamento incrível! Eles são importantes dispersores de sementes.', '2025-06-15 14:23:42', 6, 'Usuario', 10),
(13, 'Espécie sensível, bom registro!', '2025-06-15 14:24:05', 6, 'Usuario', 11),
(14, 'Vi isso também na região sul da serra!', '2025-06-15 14:24:59', 5, 'Usuario', 16),
(15, 'Já vi vários atropelamentos aqui :(', '2025-06-15 14:25:26', 5, 'Usuario', 13),
(16, 'Estão em todo lugar mesmo!', '2025-06-15 14:26:04', 5, 'Usuario', 19),
(17, 'Que cores incríveis! Nunca vi um tão vibrante assim.', '2025-06-15 14:33:09', 5, 'Usuario', 20),
(18, 'Já utilizei extratos desse fungo em pesquisas de antibióticos naturais!', '2025-06-15 14:33:47', 9, 'Usuario', 20),
(19, 'Ótimo registro! Estão mais ativos nessa época úmida do ano.', '2025-06-15 14:34:18', 8, 'Usuario', 20);

-- --------------------------------------------------------

--
-- Estrutura para tabela `especialista`
--

CREATE TABLE `especialista` (
  `ID_Especialista` int(11) NOT NULL,
  `Nome_Completo` varchar(255) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Senha` varchar(255) NOT NULL,
  `Localizacao` varchar(255) DEFAULT NULL,
  `Curriculo_Lattes` varchar(255) DEFAULT NULL,
  `Especializacao` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `especialista`
--

INSERT INTO `especialista` (`ID_Especialista`, `Nome_Completo`, `Username`, `Email`, `Senha`, `Localizacao`, `Curriculo_Lattes`, `Especializacao`) VALUES
(1, 'Dr. Carlos Santos', 'carlosesp', 'carlos.santos@especialista.com', 'esp_senha123', 'Campinas, SP', 'http://lattes.cnpq.br/carlos', 'Aracnídeos'),
(2, 'Dra. Ana Costa', 'anacosta', 'ana.costa@especialista.com', 'esp_senha456', 'Belo Horizonte, MG', 'http://lattes.cnpq.br/ana', 'Ornitologia');

-- --------------------------------------------------------

--
-- Estrutura para tabela `especie`
--

CREATE TABLE `especie` (
  `Nome_Cientifico` varchar(255) NOT NULL,
  `Nome_Popular` varchar(255) DEFAULT NULL,
  `Reino` varchar(255) DEFAULT NULL,
  `Filo` varchar(255) DEFAULT NULL,
  `Classe` varchar(255) DEFAULT NULL,
  `Ordem` varchar(255) DEFAULT NULL,
  `Familia` varchar(255) DEFAULT NULL,
  `Genero` varchar(255) DEFAULT NULL,
  `Natividade` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `notificacoes`
--

CREATE TABLE `notificacoes` (
  `ID_Notificacoes` int(11) NOT NULL,
  `Conteudo_Notificacao` text NOT NULL,
  `Tipo_Notificacao` varchar(255) DEFAULT NULL,
  `ID_Usuario` int(11) NOT NULL,
  `Data_Notificacao` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `postagem`
--

CREATE TABLE `postagem` (
  `ID_Postagem` int(11) NOT NULL,
  `Foto` varchar(255) DEFAULT NULL,
  `Data_Postagem` datetime NOT NULL DEFAULT current_timestamp(),
  `Localizacao_Postagem` varchar(255) DEFAULT NULL,
  `Titulo_Postagem` varchar(255) DEFAULT NULL,
  `Descricao_Postagem` text DEFAULT NULL,
  `ID_Categoria` int(11) NOT NULL,
  `Categoria` enum('Usuario','Especialista') NOT NULL,
  `Nome_Cientifico` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `postagem`
--

INSERT INTO `postagem` (`ID_Postagem`, `Foto`, `Data_Postagem`, `Localizacao_Postagem`, `Titulo_Postagem`, `Descricao_Postagem`, `ID_Categoria`, `Categoria`, `Nome_Cientifico`) VALUES
(10, 'uploads/bugio-ruivo.jpg', '2025-06-15 13:53:09', 'Trilha do Mirante, Jundiaí', 'Bugio-ruivo', 'Encontrei este grupo de bugios durante minha caminhada matinal. Estavam vocalizando fortemente, um comportamento típico da espécie para demarcar território.', 5, 'Usuario', NULL),
(11, 'uploads/orquidea-bambu.jpg', '2025-06-15 13:54:31', 'Trilha dos Pimenteiros', 'Orquídea-bambu', 'Essa orquídea terrestre está florescendo na beira da trilha. Não colham, deixem na natureza!', 5, 'Usuario', NULL),
(12, 'uploads/jacatirao.jpg', '2025-06-15 13:58:07', 'Base da Serra, Jundiaí', 'Manacá da serra (Jacatirão)', 'A floração roxa do Jacatirão está especialmente bonita este ano. Essa espécie é endêmica da Mata Atlântica.', 8, 'Usuario', NULL),
(13, 'uploads/gamba.jpg', '2025-06-15 14:01:04', 'Estrada do Cristo', 'Gambá', 'Esse gambá atravessou a estrada à noite. Lembrem-se de reduzir a velocidade nessa região!', 8, 'Usuario', NULL),
(14, 'uploads/bufo.jpg', '2025-06-15 14:06:20', 'Córrego do Guilherme', 'Bufo regularis', 'Este sapo estava próximo ao córrego. Cuidado com o veneno que eles secretam pelas glândulas quando ameaçados!', 7, 'Usuario', NULL),
(15, 'uploads/pica-pau.jpg', '2025-06-15 14:07:36', 'Bosque dos Jequitibás', 'Pica-pau', 'Pica-pau alimentando-se de insetos na casca das árvores. Observem o bico adaptado!', 7, 'Usuario', NULL),
(16, 'uploads/capim.jpg', '2025-06-15 14:09:18', 'Borda da mata, Cabreúva', 'Capim-colonião', 'Espécie africana invasora tomando conta da borda da floresta. Precisamos monitorar seu avanço!', 6, 'Usuario', NULL),
(17, 'uploads/samambaiacu.jpg', '2025-06-15 14:10:39', 'Trilha do Rio do Sal', 'Samambaiaçu', 'Exemplar adulto de samambaiaçu, uma das maiores samambaias do Brasil. Pode viver mais de 100 anos!', 6, 'Usuario', NULL),
(18, 'uploads/tucano-toco.jpg', '2025-06-15 14:12:02', 'Fazenda próximo à serra', 'Tucano-toco', 'Esse tucano estava se alimentando de frutos nas árvores próximas ao cafezal. Lindo avistamento!', 9, 'Usuario', NULL),
(19, 'uploads/pardal.jpg', '2025-06-15 14:14:59', 'Serra do Japi, Jundiaí, SP', 'Pardal-doméstico', 'Pardal, espécie invasora, dominando a área urbana. Competem com aves nativas por recursos.', 9, 'Usuario', NULL),
(20, 'uploads/OrelhaDePau.jpg', '2025-06-15 14:32:40', 'Trilha da Cachoeira, Jundiaí', 'Orelha-de-pau', 'Encontrei esta bela colônia de Pycnoporus sanguineus em um tronco caído. Observem a coloração intensa - um sinal de que o fungo está ativo na decomposição da madeira. Espécie fundamental para o ciclo de nutrientes na floresta!', 6, 'Usuario', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `postagem_contem_especie`
--

CREATE TABLE `postagem_contem_especie` (
  `ID_Postagem` int(11) NOT NULL,
  `Nome_Cientifico` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `ID_Usuario` int(11) NOT NULL,
  `Nome_Completo` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Senha` varchar(255) NOT NULL,
  `Localizacao` varchar(255) DEFAULT NULL,
  `Username` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`ID_Usuario`, `Nome_Completo`, `Email`, `Senha`, `Localizacao`, `Username`) VALUES
(4, 'Rafael Tonetti Cardoso', 'rafaeltonetti.cardoso@gmail.com', '$2y$10$sdtHwpR0E.jLPFf.BLAKnOUxL/jxiOq5u3pZeHCmm/5jvNYjldKQK', NULL, 'rafael_tonetti'),
(5, 'Ana Silva', 'ana.silva@email.com', '$2y$10$6tYfrgqE79pfIZ8TgwfBu.oJb8DfNdLFd9WSg/BXbQJR./IIelYjy', NULL, 'anajapi'),
(6, 'Carlos Mendes', 'carlos.mendes@email.com', '$2y$10$AcMcrQkND7ryqNEZ8kRA4uH0huoUIHDVJcD5Go9WXTyOZLjhDkZjW', NULL, 'biomendes'),
(7, 'Juliana Santos', 'juliana.s@email.com', '$2y$10$GIl4gEFdQyNiMvZYxIV5cejmEoGYuzJVJPwq1/A6rtF4K26pZOU92', NULL, 'jusantos'),
(8, 'Ricardo Oliveira', 'ric.oliveira@email.com', '$2y$10$oMVYDlPK2j41m7e5.W4KZuPVFD2ZqXwSRXdWF/6CT.ubunAnzQkHO', NULL, 'ricbio'),
(9, 'Mariana Costa', 'maricosta@email.com', '$2y$10$46CNzfAE38Sdxh/KweOCOeAKnPXm0Ps.Z0Obb9fI5SMg5Zqq4bhT.', NULL, 'mari_ecolog');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`ID_Administrador`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Índices de tabela `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`ID_Comentario`),
  ADD KEY `ID_Postagem` (`ID_Postagem`);

--
-- Índices de tabela `especialista`
--
ALTER TABLE `especialista`
  ADD PRIMARY KEY (`ID_Especialista`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Índices de tabela `especie`
--
ALTER TABLE `especie`
  ADD PRIMARY KEY (`Nome_Cientifico`);

--
-- Índices de tabela `notificacoes`
--
ALTER TABLE `notificacoes`
  ADD PRIMARY KEY (`ID_Notificacoes`),
  ADD KEY `ID_Usuario` (`ID_Usuario`);

--
-- Índices de tabela `postagem`
--
ALTER TABLE `postagem`
  ADD PRIMARY KEY (`ID_Postagem`),
  ADD KEY `Nome_Cientifico` (`Nome_Cientifico`);

--
-- Índices de tabela `postagem_contem_especie`
--
ALTER TABLE `postagem_contem_especie`
  ADD PRIMARY KEY (`ID_Postagem`,`Nome_Cientifico`),
  ADD KEY `Nome_Cientifico` (`Nome_Cientifico`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`ID_Usuario`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `administrador`
--
ALTER TABLE `administrador`
  MODIFY `ID_Administrador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `ID_Comentario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de tabela `especialista`
--
ALTER TABLE `especialista`
  MODIFY `ID_Especialista` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `notificacoes`
--
ALTER TABLE `notificacoes`
  MODIFY `ID_Notificacoes` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `postagem`
--
ALTER TABLE `postagem`
  MODIFY `ID_Postagem` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `ID_Usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`ID_Postagem`) REFERENCES `postagem` (`ID_Postagem`);

--
-- Restrições para tabelas `notificacoes`
--
ALTER TABLE `notificacoes`
  ADD CONSTRAINT `notificacoes_ibfk_1` FOREIGN KEY (`ID_Usuario`) REFERENCES `usuario` (`ID_Usuario`);

--
-- Restrições para tabelas `postagem`
--
ALTER TABLE `postagem`
  ADD CONSTRAINT `postagem_ibfk_1` FOREIGN KEY (`Nome_Cientifico`) REFERENCES `especie` (`Nome_Cientifico`);

--
-- Restrições para tabelas `postagem_contem_especie`
--
ALTER TABLE `postagem_contem_especie`
  ADD CONSTRAINT `postagem_contem_especie_ibfk_1` FOREIGN KEY (`ID_Postagem`) REFERENCES `postagem` (`ID_Postagem`),
  ADD CONSTRAINT `postagem_contem_especie_ibfk_2` FOREIGN KEY (`Nome_Cientifico`) REFERENCES `especie` (`Nome_Cientifico`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
