-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  mer. 12 mai 2021 à 23:50
-- Version du serveur :  5.7.9
-- Version de PHP :  7.2.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Base de données :  `gac`
--

CREATE DATABASE IF NOT EXISTS `gac`;
USE `gac`;
-- --------------------------------------------------------

--
-- Structure de la table `tickets`
--

DROP TABLE IF EXISTS `tickets`;
CREATE TABLE IF NOT EXISTS `tickets` (
  `tic_subscriber_number` int(10) UNSIGNED NOT NULL,
  `tic_datetime` datetime NOT NULL,
  `tic_time` TIME NOT NULL,
  `tic_qty_real` int(10) UNSIGNED NOT NULL,
  `tic_qty_billed` int(10) UNSIGNED NOT NULL,
  `tic_type` enum('data','sms','call') NOT NULL,
  `tic_type_details` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE utf8_bin;
COMMIT;

CREATE USER 'gac_user'@'%' IDENTIFIED BY 'gac_pwd';
GRANT USAGE ON *.* TO 'gac_user'@'%' REQUIRE NONE WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;
GRANT SELECT, INSERT, UPDATE, DELETE ON `gac`.* TO 'gac_user'@'%';