-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u2
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Jeu 06 Décembre 2018 à 10:51
-- Version du serveur :  5.5.53-0+deb8u1
-- Version de PHP :  5.6.27-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `winlog`
--

-- --------------------------------------------------------

--
-- Structure de la table `comptes`
--
-- Création :  Mer 05 Décembre 2018 à 09:45
-- Dernière modification :  Mer 05 Décembre 2018 à 09:45
--

CREATE TABLE IF NOT EXISTS `comptes` (
`compte_id` int(11) NOT NULL,
  `username` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `prenom` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `nom` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `groupe` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=929 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `connexions`
--
-- Création :  Ven 25 Novembre 2016 à 12:34
-- Dernière modification :  Jeu 06 Décembre 2018 à 09:51
-- Dernière vérification :  Mer 31 Octobre 2018 à 10:05
--

CREATE TABLE IF NOT EXISTS `connexions` (
`con_id` bigint(20) NOT NULL,
  `username` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `hote` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fin_con` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `debut_con` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `close` tinyint(1) NOT NULL DEFAULT '1',
  `archivable` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=195776 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `log_admin_connexions`
--
-- Création :  Jeu 06 Décembre 2018 à 08:18
-- Dernière modification :  Jeu 06 Décembre 2018 à 09:49
--

CREATE TABLE IF NOT EXISTS `log_admin_connexions` (
`id_admin_con` int(11) NOT NULL,
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='table de logs des connexions à la console d''administration';

-- --------------------------------------------------------

--
-- Structure de la table `machines`
--
-- Création :  Jeu 29 Novembre 2018 à 10:27
-- Dernière modification :  Jeu 06 Décembre 2018 à 09:50
--

CREATE TABLE IF NOT EXISTS `machines` (
  `machine_id` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `salle` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `os` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `os_sp` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `os_version` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `adresse_ip` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'adresse ip collectée',
  `marque` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'marque',
  `modele` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'modèle',
  `type_systeme` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT 'type du système',
  `mac` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT 'mac address',
  `mac_description` varchar(60) COLLATE utf8_unicode_ci NOT NULL COMMENT 'description mac address',
  `ram` bigint(20) NOT NULL COMMENT 'mémoire physique totale',
  `procSpeed` bigint(20) NOT NULL COMMENT 'vitesse processeur',
  `diskSize` bigint(20) NOT NULL COMMENT 'taille disque C:',
  `freeSpace` bigint(20) NOT NULL COMMENT 'espace libre sur C:'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ping`
--
-- Création :  Ven 30 Novembre 2018 à 13:33
-- Dernière modification :  Jeu 06 Décembre 2018 à 09:51
--

CREATE TABLE IF NOT EXISTS `ping` (
  `machine_id` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `ping_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='table des réponses au ping de chaque machine';

-- --------------------------------------------------------

--
-- Structure de la table `proxy`
--
-- Création :  Ven 16 Septembre 2016 à 12:39
-- Dernière modification :  Jeu 06 Décembre 2018 à 09:48
-- Dernière vérification :  Mer 31 Octobre 2018 à 10:05
--

CREATE TABLE IF NOT EXISTS `proxy` (
  `ip` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `target` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `logts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `checked` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `salles`
--
-- Création :  Mer 05 Décembre 2018 à 09:18
-- Dernière modification :  Mer 05 Décembre 2018 à 09:18
--

CREATE TABLE IF NOT EXISTS `salles` (
  `salle_id` varchar(10) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `total_connexions`
--
-- Création :  Lun 11 Décembre 2017 à 09:05
--

CREATE TABLE IF NOT EXISTS `total_connexions` (
  `con_id` bigint(20) NOT NULL,
  `username` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT 'login windows',
  `hote` varchar(15) COLLATE utf8_unicode_ci NOT NULL COMMENT 'nom machine',
  `ip` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `fin_con` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `debut_con` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `wifi`
--
-- Création :  Mar 13 Décembre 2016 à 09:02
-- Dernière modification :  Jeu 06 Décembre 2018 à 09:49
-- Dernière vérification :  Mer 31 Octobre 2018 à 10:05
--

CREATE TABLE IF NOT EXISTS `wifi` (
`wifi_id` bigint(20) NOT NULL,
  `wifi_username` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `wifi_ip` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `wifi_browser` varchar(160) COLLATE utf8_unicode_ci NOT NULL,
  `wifi_deb_conn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `close` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=97548 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `comptes`
--
ALTER TABLE `comptes`
 ADD PRIMARY KEY (`compte_id`);

--
-- Index pour la table `connexions`
--
ALTER TABLE `connexions`
 ADD PRIMARY KEY (`con_id`), ADD KEY `user_host` (`username`,`hote`), ADD KEY `close` (`close`), ADD KEY `archivable` (`archivable`);

--
-- Index pour la table `log_admin_connexions`
--
ALTER TABLE `log_admin_connexions`
 ADD PRIMARY KEY (`id_admin_con`);

--
-- Index pour la table `machines`
--
ALTER TABLE `machines`
 ADD PRIMARY KEY (`machine_id`);

--
-- Index pour la table `ping`
--
ALTER TABLE `ping`
 ADD PRIMARY KEY (`machine_id`);

--
-- Index pour la table `salles`
--
ALTER TABLE `salles`
 ADD PRIMARY KEY (`salle_id`);

--
-- Index pour la table `total_connexions`
--
ALTER TABLE `total_connexions`
 ADD PRIMARY KEY (`con_id`), ADD KEY `username` (`username`), ADD KEY `hote` (`hote`), ADD KEY `ip` (`ip`);

--
-- Index pour la table `wifi`
--
ALTER TABLE `wifi`
 ADD PRIMARY KEY (`wifi_id`), ADD KEY `wifi_username` (`wifi_username`), ADD KEY `wifi_deb_conn` (`wifi_deb_conn`), ADD KEY `close` (`close`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `comptes`
--
ALTER TABLE `comptes`
MODIFY `compte_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=929;
--
-- AUTO_INCREMENT pour la table `connexions`
--
ALTER TABLE `connexions`
MODIFY `con_id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=195776;
--
-- AUTO_INCREMENT pour la table `log_admin_connexions`
--
ALTER TABLE `log_admin_connexions`
MODIFY `id_admin_con` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `wifi`
--
ALTER TABLE `wifi`
MODIFY `wifi_id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=97548;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
