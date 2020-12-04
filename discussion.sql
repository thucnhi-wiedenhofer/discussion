-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 04 déc. 2020 à 17:23
-- Version du serveur :  5.7.31
-- Version de PHP : 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `discussion`
--

-- --------------------------------------------------------

--
-- Structure de la table `connected`
--

DROP TABLE IF EXISTS `connected`;
CREATE TABLE IF NOT EXISTS `connected` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login_connected` varchar(50) NOT NULL,
  `color` varchar(50) NOT NULL,
  `id_connected` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` varchar(140) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id`, `message`, `id_utilisateur`, `date`) VALUES
(6, 'Je vais bien \r\n', 4, '2020-12-03 11:40:57'),
(3, 'ca va?', 3, '2020-12-03 11:30:35'),
(4, 'vas bien?\r\n', 3, '2020-12-03 11:31:03'),
(5, 'tu vas bien?', 4, '2020-12-03 11:35:53'),
(7, 'ok', 4, '2020-12-03 11:41:05'),
(10, 'coucou', 3, '2020-12-03 16:58:11'),
(11, 'salut\r\n', 6, '2020-12-03 16:59:27'),
(12, 'hello', 7, '2020-12-03 17:10:28'),
(13, 'va bien?', 3, '2020-12-03 18:00:08'),
(14, 'personnellement le yoga marche trÃ¨s bien pour moi', 8, '2020-12-03 20:06:55'),
(15, 'Super!', 9, '2020-12-04 11:49:01'),
(16, 'Coucou!', 7, '2020-12-04 12:02:40'),
(18, 'Si vous Ãªtes sur google chrome, inscrivez vous aussi dans internet explore  sous un autre login pour montrer 2 utilisateurs connectÃ©s', 3, '2020-12-04 18:21:03');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `login`, `password`) VALUES
(3, 'noÃ©mie', '$2y$10$WQWeho42/LKsdnZZKnEsbe4.LdkJZZEt3j3tm.OUjC/.9SFdtPpoy'),
(4, 'leo', '$2y$10$zAVi34H9ckKF0Woo./WT0O/E7jlGefnstHn0nPE8ymXq4Jsdkm6BK'),
(5, 'da', '$2y$10$CT4iY4C4xOsNJSB3B9EKU.OFVDyZEov8f8ksFJ4W6U.ZYus6ei38y'),
(6, 'toto', '$2y$10$yw8QpVdCIAzjbsTAaNaKOO61rGtymVnfwEtG3IvuBbuIC2QFS2LIO'),
(7, 'lisa', '$2y$10$VaRHErZ/RhctiphodM9DgOJkzW8uxYtUa4V7u6KH7YokCj2rgckFq'),
(8, 'pioupiou', '$2y$10$KfQTExfFyw8V/CnWeBZGruYi21XPclcA547uJKWNyl1AFbcGeRHsy'),
(9, 'test', '$2y$10$emW0L35OHaJrGJvczJattuie.Gf7/HGySQa74dCEUxwB3aqd2EGPy');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
