-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 28-Abr-2015 às 16:01
-- Versão do servidor: 5.6.16
-- PHP Version: 5.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ctism`
--
CREATE DATABASE IF NOT EXISTS `ctism` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `ctism`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `ctism_aula_regular`
--

DROP TABLE IF EXISTS `ctism_aula_regular`;
CREATE TABLE IF NOT EXISTS `ctism_aula_regular` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_professor` varchar(50) NOT NULL,
  `id_componente` int(11) NOT NULL,
  `dia_semana` int(11) NOT NULL,
  `horario` time NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_professor` (`id_professor`),
  KEY `id_componente` (`id_componente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `ctism_componente`
--

DROP TABLE IF EXISTS `ctism_componente`;
CREATE TABLE IF NOT EXISTS `ctism_componente` (
  `idcomponente` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`idcomponente`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=221 ;

--
-- Extraindo dados da tabela `ctism_componente`
--

INSERT INTO `ctism_componente` (`idcomponente`, `nome`) VALUES
(33, 'Acionamentos Elétricos '),
(34, 'Acionamentos Elétricos e Automação Industrial'),
(35, 'Acionamentos Hidráulicos e Pneumáticos'),
(36, 'Algorítmo e Lógica de Programação'),
(37, 'Algorítmos e Programação'),
(38, 'Análise e Projeto de Sistemas Web'),
(39, 'Arquitetura de Computadores'),
(40, 'Artes'),
(41, 'Automação I'),
(42, 'Automação II'),
(43, 'Automação III'),
(44, 'Automação Industrial'),
(45, 'Banco de Dados'),
(46, 'Biologia'),
(47, 'Cabeamento Estruturado'),
(48, 'CAD/CAE'),
(49, 'CAE/CAM/CAD'),
(50, 'Cálculo'),
(51, 'Cálculo com Geometria Analítica'),
(52, 'Ciência dos Materiais I'),
(53, 'Ciência dos Materiais II'),
(54, 'Ciências da Natureza, Matemática e suas Tecnologias'),
(55, 'Ciências da Natureza, Matemática e suas Tecnologias V'),
(56, 'Ciências Humanas e suas Tecnologias'),
(57, 'Ciências Humanas e suas Tecnologias V'),
(58, 'Circuitos Digitais'),
(59, 'Circuitos Digitais e Controladores Programáveis'),
(60, 'Comunicação de Dados'),
(61, 'Comunicação e Expressão'),
(62, 'Comunicação e Expressão Técnica'),
(63, 'Controle de Processos Industriais'),
(64, 'Desenho Assistido por Computador (CAD)'),
(65, 'Desenho Técnico'),
(66, 'Desenho Técnico Básico'),
(67, 'Desenho Técnico Mecânico'),
(68, 'Desenho Técnico Mecânico A'),
(69, 'Educação Física'),
(70, 'Elementos de Máquinas'),
(71, 'Elementos de máquinas A'),
(72, 'Eletricidade '),
(73, 'Eletricidade Aplicada'),
(74, 'Eletricidade e Magetismo'),
(75, 'Eletricidade I'),
(76, 'Eletrônica'),
(77, 'Eletrônica Básica'),
(78, 'Eletrônica de Potência'),
(79, 'Eletrônica Digital'),
(80, 'Eletrônica I'),
(81, 'Eletrônica II'),
(82, 'Eletrotécnica I'),
(83, 'Eletrotécnica II'),
(84, 'Empreendedorismo'),
(85, 'Ensaios de Materiais II'),
(86, 'Equações Diferenciais'),
(87, 'Ergonomia'),
(88, 'Estágio Curricular'),
(89, 'Estatística e Probabilidade'),
(90, 'Ética Profissional e Segurança do Trabalho'),
(91, 'Ferramentas de Projetos'),
(92, 'Ferramentas e Elementos de Máquinas'),
(93, 'Ferramentas e Elementos maquinas I'),
(94, 'Filosofia'),
(95, 'Física'),
(96, 'Física Aplicada I'),
(97, 'Física Aplicada II'),
(98, 'Fundamentos de Computação e Hardware'),
(99, 'Geografia'),
(100, 'Gerenciamento de Redes'),
(101, 'Gerenciamento de Riscos'),
(102, 'Gestão Ambiental'),
(103, 'Gestão e Empreendedorismo'),
(104, 'Gestão Industrial'),
(105, 'Gestão Industrial A'),
(106, 'Gestão Industrial e Segurança do Trabalho'),
(107, 'Gestão Industrial I'),
(108, 'Gestão Industrial II'),
(109, 'Gestão Industrial III'),
(110, 'Higiene e Segurança do Trabalho'),
(111, 'Higiene e Segurança no Trabalho'),
(112, 'Higiene Ocupacional I (a)'),
(113, 'Higiene Ocupacional I (b)'),
(114, 'Higiene Ocupacional III'),
(115, 'História'),
(116, 'Informática'),
(117, 'Informática Aplicada'),
(118, 'Informática com Algorítmo'),
(119, 'Inglês Técnico'),
(120, 'Instalações e Manutenção Elétrica'),
(121, 'Instalações e Projetos Elétricos'),
(122, 'Instalações Elétricas I'),
(123, 'Introdução à Ciência dos Materiais'),
(124, 'Introdução a Física e Matemática'),
(125, 'Introdução a Informática'),
(126, 'Introdução a Programação em Java'),
(127, 'Introdução a Redes'),
(128, 'Introdução à Segurança de Máquinas e Equipamentos'),
(129, 'Lingua Estrangeira Moderna'),
(130, 'Língua Portuguesa'),
(131, 'Linguagem de Programação'),
(132, 'Linguagens, Códigos e suas Tecnologias'),
(133, 'Linguagens, Códigos e suas Tecnologias V'),
(134, 'Literatura Brasileira'),
(135, 'Manufatura Assistida por Computador'),
(136, 'Manutenção Elétrica I'),
(137, 'Manutenção Industrial'),
(138, 'Manutenção Industrial A'),
(139, 'Máquinas e Tubulações Industriais'),
(140, 'Máquinas e Tubulações Industriais A'),
(141, 'Máquinas Elétricas'),
(142, 'Máquinas Elétricas e Transformadores'),
(143, 'Máquinas Térmicas'),
(144, 'Máquinas Térmicas A'),
(145, 'Matemática'),
(146, 'Matemática Aplicada'),
(147, 'Mecânica Geral e Dinâmica dos Mecanismos'),
(148, 'Metrologia'),
(149, 'Metrologia Aplicada'),
(150, 'Metrologia e Instrumentação'),
(151, 'Metrologia e Instrumentação A'),
(152, 'Microprocessadores e Microcontroladores'),
(153, 'Normas e Qualificação de Soldagem'),
(154, 'Normatização e Legislação Aplicada'),
(155, 'Organização de Computadores'),
(156, 'Planejamento e Projetos de Redes'),
(157, 'Português e Produção de Textos'),
(158, 'Prevenção e Combate a Sinistros'),
(159, 'Processos de Fabricação II'),
(160, 'Processos de Fabricação III'),
(161, 'Processos de Soldagem II'),
(162, 'Processos Especiais de Fabricação I'),
(163, 'Produção Mecânica - Ajustagem'),
(164, 'Produção Mecânica - Ajustagem A'),
(165, 'Produção Mecânica - CNC'),
(166, 'Produção Mecânica - Soldagem'),
(167, 'Produção Mecânica - Soldagem A'),
(168, 'Produção Mecânica - Usinagem'),
(169, 'Produção Mecânica – Usinagem A'),
(170, 'Programação Web I'),
(171, 'Projeto Assistido por Computador'),
(172, 'Projetos de Perfis Soldados'),
(173, 'Projetos de Redes sem Fio'),
(174, 'Projetos Elétricos'),
(175, 'Projetos Elétricos I'),
(176, 'Projetos Eletrônicos I'),
(177, 'Química'),
(178, 'Redes Aplicadas a Telecomunicações'),
(179, 'Redes de Computadores e Com. de Dados'),
(180, 'Redes de Computadores I'),
(181, 'Redes de Computadores II'),
(182, 'Redes Industriais'),
(183, 'Relações Humanas '),
(184, 'Relações Humanas e Ética'),
(185, 'Resistência dos Materiais'),
(186, 'Resistência dos Materiais A'),
(187, 'Resistência dos Materiais Aplicada'),
(188, 'Resistência dos Materiais com Elementos de Máquinas I'),
(189, 'Resistência dos Materiais com Elementos de Máquinas II'),
(190, 'Robótica Industrial'),
(191, 'Segurança Aplicada à Soldagem (a)'),
(192, 'Segurança Aplicada à Soldagem (b)'),
(193, 'Segurança de Redes'),
(194, 'Segurança do Trabalho I'),
(195, 'Segurança do Trabalho III'),
(196, 'Sistemas Elétricos de Potência'),
(197, 'Sistemas Hidráulicos e Pneumáticos'),
(198, 'Sistemas Hidráulicos e Pneumáticos B'),
(199, 'Sistemas Operacionais I'),
(200, 'Sistemas Operacionais II'),
(201, 'Sistemas Térmicos I'),
(202, 'Sistemas Térmicos II'),
(203, 'Sociologia'),
(204, 'Soldagem e Ajustagem'),
(205, 'Técnicas e Planejamento da Manutenção'),
(206, 'Tecnologia de Superfícies'),
(207, 'Tecnologia Mecânica I'),
(208, 'Tecnologia Mecânica I - A'),
(209, 'Tecnologia Mecânica II'),
(210, 'Tecnologia Mecânica II A'),
(211, 'Tecnologias e Processos Industriais I'),
(212, 'Tecnologias e Processos Industriais III'),
(213, 'Telecomunicações'),
(214, 'Telecomunicações II'),
(215, 'Teoria da Comunicação'),
(216, 'Termodinâmica e Transferência de Calor'),
(217, 'Toxicologia'),
(218, 'Trabalho de Conclusão de Curso'),
(219, 'Tubulações Industriais'),
(220, 'Usinagem');

-- --------------------------------------------------------

--
-- Estrutura da tabela `ctism_solicita_aula_solicitada`
--

DROP TABLE IF EXISTS `ctism_solicita_aula_solicitada`;
CREATE TABLE IF NOT EXISTS `ctism_solicita_aula_solicitada` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_prof_substituto` varchar(50) NOT NULL,
  `id_componente` int(11) NOT NULL,
  `id_situacao` int(11) NOT NULL DEFAULT '1',
  `id_solicitacao` int(11) NOT NULL,
  `dt_aula` datetime NOT NULL,
  `dt_recuperacao` datetime NOT NULL,
  `mail_enviado` varchar(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_solicitacao` (`id_solicitacao`),
  KEY `id_componente` (`id_componente`),
  KEY `id_prof_substituto` (`id_prof_substituto`),
  KEY `id_situacao` (`id_situacao`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Extraindo dados da tabela `ctism_solicita_aula_solicitada`
--

INSERT INTO `ctism_solicita_aula_solicitada` (`id`, `id_prof_substituto`, `id_componente`, `id_situacao`, `id_solicitacao`, `dt_aula`, `dt_recuperacao`, `mail_enviado`) VALUES
(6, 'acmarchesan', 33, 1, 33, '2015-04-01 08:30:00', '2015-04-04 08:30:00', '0'),
(7, 'acmarchesan', 33, 1, 33, '2015-04-01 08:30:00', '2015-04-04 08:30:00', '0');

--
-- Acionadores `ctism_solicita_aula_solicitada`
--
DROP TRIGGER IF EXISTS `DELETAR_SOL_VAZIA`;
DELIMITER //
CREATE TRIGGER `DELETAR_SOL_VAZIA` AFTER DELETE ON `ctism_solicita_aula_solicitada`
 FOR EACH ROW BEGIN
   DECLARE TESTE INT;
   SET TESTE = old.id_solicitacao;
   IF ((SELECT COUNT(ID) FROM CTISM_SOLICITA_AULA_SOLICITADA WHERE ID_SOLICITACAO=TESTE)=0) THEN
      DELETE FROM CTISM_SOLICITA_SOLICITACAO WHERE ID=TESTE;
   END IF;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `REMOVE_AVISO`;
DELIMITER //
CREATE TRIGGER `REMOVE_AVISO` BEFORE UPDATE ON `ctism_solicita_aula_solicitada`
 FOR EACH ROW BEGIN
	IF NEW.ID_SITUACAO <> OLD.ID_SITUACAO THEN
		SET NEW.MAIL_ENVIADO = '0';
    END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `ctism_solicita_grupo`
--

DROP TABLE IF EXISTS `ctism_solicita_grupo`;
CREATE TABLE IF NOT EXISTS `ctism_solicita_grupo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `ctism_solicita_grupo`
--

INSERT INTO `ctism_solicita_grupo` (`id`, `descricao`) VALUES
(1, 'Professores'),
(2, 'Admins'),
(3, 'Alunos');

-- --------------------------------------------------------

--
-- Estrutura da tabela `ctism_solicita_motivoafastamento`
--

DROP TABLE IF EXISTS `ctism_solicita_motivoafastamento`;
CREATE TABLE IF NOT EXISTS `ctism_solicita_motivoafastamento` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Extraindo dados da tabela `ctism_solicita_motivoafastamento`
--

INSERT INTO `ctism_solicita_motivoafastamento` (`id`, `descricao`) VALUES
(1, 'Doação de sangue (1 dia por ano)'),
(2, 'Falecimento do cônjuge, compannheiro, pais, madrasta ou padrasto, filhos, enteados, menor sob guarda ou tutela e irmãos (8 dias)'),
(3, 'Casamento (8 dias)'),
(4, 'Gozo de férias'),
(5, 'Participação em evento acadêmico'),
(6, 'Participação em atividade sindical');

-- --------------------------------------------------------

--
-- Estrutura da tabela `ctism_solicita_situacao`
--

DROP TABLE IF EXISTS `ctism_solicita_situacao`;
CREATE TABLE IF NOT EXISTS `ctism_solicita_situacao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(70) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Extraindo dados da tabela `ctism_solicita_situacao`
--

INSERT INTO `ctism_solicita_situacao` (`id`, `descricao`) VALUES
(1, 'Solicitada'),
(2, 'Aceita'),
(3, 'Negada'),
(4, 'Deferida'),
(5, 'Indeferida');

-- --------------------------------------------------------

--
-- Estrutura da tabela `ctism_solicita_solicitacao`
--

DROP TABLE IF EXISTS `ctism_solicita_solicitacao`;
CREATE TABLE IF NOT EXISTS `ctism_solicita_solicitacao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_professor` varchar(50) NOT NULL,
  `datainicio` date NOT NULL,
  `datafim` date DEFAULT NULL,
  `outro_motivo` varchar(150) DEFAULT NULL,
  `data_solicitacao` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_professor` (`id_professor`),
  KEY `data_solicitacao` (`data_solicitacao`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=35 ;

--
-- Extraindo dados da tabela `ctism_solicita_solicitacao`
--

INSERT INTO `ctism_solicita_solicitacao` (`id`, `id_professor`, `datainicio`, `datafim`, `outro_motivo`, `data_solicitacao`) VALUES
(33, 'profteste', '2015-04-01', '2015-04-29', NULL, '2015-04-27 12:56:27');

-- --------------------------------------------------------

--
-- Estrutura da tabela `ctism_solicita_solicitacao_has_motivo`
--

DROP TABLE IF EXISTS `ctism_solicita_solicitacao_has_motivo`;
CREATE TABLE IF NOT EXISTS `ctism_solicita_solicitacao_has_motivo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_solicitacao` int(11) NOT NULL,
  `id_motivo` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_solicitacao` (`id_solicitacao`),
  KEY `id_motivo` (`id_motivo`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `ctism_solicita_solicitacao_has_motivo`
--

INSERT INTO `ctism_solicita_solicitacao_has_motivo` (`id`, `id_solicitacao`, `id_motivo`) VALUES
(1, 33, 1);

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `ctism_aula_regular`
--
ALTER TABLE `ctism_aula_regular`
  ADD CONSTRAINT `ctism_aula_regular_ibfk_2` FOREIGN KEY (`id_componente`) REFERENCES `ctism_componente` (`idcomponente`);

--
-- Limitadores para a tabela `ctism_solicita_aula_solicitada`
--
ALTER TABLE `ctism_solicita_aula_solicitada`
  ADD CONSTRAINT `ctism_solicita_aula_solicitada_ibfk_1` FOREIGN KEY (`id_solicitacao`) REFERENCES `ctism_solicita_solicitacao` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ctism_solicita_aula_solicitada_ibfk_2` FOREIGN KEY (`id_componente`) REFERENCES `ctism_componente` (`idcomponente`) ON DELETE CASCADE,
  ADD CONSTRAINT `ctism_solicita_aula_solicitada_ibfk_4` FOREIGN KEY (`id_situacao`) REFERENCES `ctism_solicita_situacao` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `ctism_solicita_solicitacao_has_motivo`
--
ALTER TABLE `ctism_solicita_solicitacao_has_motivo`
  ADD CONSTRAINT `ctism_solicita_solicitacao_has_motivo_ibfk_1` FOREIGN KEY (`id_solicitacao`) REFERENCES `ctism_solicita_solicitacao` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ctism_solicita_solicitacao_has_motivo_ibfk_2` FOREIGN KEY (`id_motivo`) REFERENCES `ctism_solicita_motivoafastamento` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
