-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 30, 2025 at 12:32 PM
-- Server version: 8.2.0
-- PHP Version: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `electricity`
--

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

DROP TABLE IF EXISTS `client`;
CREATE TABLE IF NOT EXISTS `client` (
  `id_client` int NOT NULL,
  `numero_compteur` varchar(50) NOT NULL,
  `adresse_installation` varchar(255) NOT NULL,
  PRIMARY KEY (`id_client`),
  UNIQUE KEY `numero_compteur` (`numero_compteur`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `consommation`
--

DROP TABLE IF EXISTS `consommation`;
CREATE TABLE IF NOT EXISTS `consommation` (
  `id_consommation` int NOT NULL AUTO_INCREMENT,
  `client_id` int NOT NULL,
  `mois` int NOT NULL,
  `annee` int NOT NULL,
  `valeur_compteur` decimal(10,2) NOT NULL,
  `photo_compteur` varchar(255) DEFAULT NULL,
  `date_saisie` datetime DEFAULT CURRENT_TIMESTAMP,
  `validee` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id_consommation`),
  KEY `client_id` (`client_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `consommationannuelle`
--

DROP TABLE IF EXISTS `consommationannuelle`;
CREATE TABLE IF NOT EXISTS `consommationannuelle` (
  `id_conso_annuelle` int NOT NULL AUTO_INCREMENT,
  `client_id` int NOT NULL,
  `annee` int NOT NULL,
  `consommation_totale` decimal(10,2) NOT NULL,
  `date_generation` date NOT NULL,
  `id_agent` int NOT NULL,
  PRIMARY KEY (`id_conso_annuelle`),
  KEY `client_id` (`client_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `facture`
--

DROP TABLE IF EXISTS `facture`;
CREATE TABLE IF NOT EXISTS `facture` (
  `id_facture` int NOT NULL AUTO_INCREMENT,
  `client_id` int NOT NULL,
  `date_emission` date NOT NULL,
  `date_echeance` date NOT NULL,
  `montant_ht` decimal(10,2) NOT NULL,
  `tva` decimal(5,2) NOT NULL,
  `montant_ttc` decimal(10,2) NOT NULL,
  `statut` enum('payée','impayée','en attente') DEFAULT 'en attente',
  PRIMARY KEY (`id_facture`),
  KEY `client_id` (`client_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fournisseur`
--

DROP TABLE IF EXISTS `fournisseur`;
CREATE TABLE IF NOT EXISTS `fournisseur` (
  `id_fournisseur` int NOT NULL,
  `departement` varchar(255) NOT NULL,
  PRIMARY KEY (`id_fournisseur`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reclamation`
--

DROP TABLE IF EXISTS `reclamation`;
CREATE TABLE IF NOT EXISTS `reclamation` (
  `id_reclamation` int NOT NULL AUTO_INCREMENT,
  `client_id` int NOT NULL,
  `type_reclamation` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `statut` enum('soumise','en cours','résolue') DEFAULT 'soumise',
  `date_soumission` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_reclamation`),
  KEY `client_id` (`client_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tarifelectricite`
--

DROP TABLE IF EXISTS `tarifelectricite`;
CREATE TABLE IF NOT EXISTS `tarifelectricite` (
  `id` int NOT NULL AUTO_INCREMENT,
  `plage_min` decimal(10,2) NOT NULL,
  `plage_max` decimal(10,2) NOT NULL,
  `prix_unitaire` decimal(10,2) NOT NULL,
  `date_application` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id_utilisateur` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `type` enum('client','fournisseur') NOT NULL,
  `date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_utilisateur`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;



ALTER TABLE `client` 
MODIFY `id_client` INT NOT NULL AUTO_INCREMENT,
ADD CONSTRAINT `fk_client_utilisateur` 
FOREIGN KEY (`id_client`) REFERENCES `utilisateur`(`id_utilisateur`);
