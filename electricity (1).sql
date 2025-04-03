-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 02 avr. 2025 à 01:44
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `electricity`
--

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

DROP TABLE IF EXISTS `client`;
CREATE TABLE IF NOT EXISTS `client` (
  `id_client` int NOT NULL,
  `numero_compteur` varchar(50) NOT NULL,
  `adresse_installation` varchar(255) NOT NULL,
  PRIMARY KEY (`id_client`),
  UNIQUE KEY `numero_compteur` (`numero_compteur`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`id_client`, `numero_compteur`, `adresse_installation`) VALUES
(5, 'G986660', 'Tanger'),
(4, 'G98666', 'rue SALE');

-- --------------------------------------------------------

--
-- Structure de la table `consommation`
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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `consommation`
--

INSERT INTO `consommation` (`id_consommation`, `client_id`, `mois`, `annee`, `valeur_compteur`, `photo_compteur`, `date_saisie`, `validee`) VALUES
(1, 5, 1, 2024, 1222.04, 'compteur1.jpeg', '2024-01-05 08:30:00', 1),
(2, 4, 6, 2023, 1250.75, 'compteur2.png', '2023-06-15 14:30:00', 1);

-- --------------------------------------------------------

--
-- Structure de la table `consommationannuelle`
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
-- Structure de la table `facture`
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
-- Structure de la table `fournisseur`
--

DROP TABLE IF EXISTS `fournisseur`;
CREATE TABLE IF NOT EXISTS `fournisseur` (
  `id_fournisseur` int NOT NULL,
  `departement` varchar(255) NOT NULL,
  PRIMARY KEY (`id_fournisseur`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reclamation`
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
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `reclamation`
--

INSERT INTO `reclamation` (`id_reclamation`, `client_id`, `type_reclamation`, `description`, `statut`, `date_soumission`) VALUES
(1, 1, 'Facture non reçue', 'Je n\'ai pas encore reçu ma facture d\'électricité de ce mois.', 'soumise', '2025-03-30 17:13:54'),
(2, 2, 'Montant incorrect', 'Le montant de ma facture est plus élevé que d\'habitude sans explication.', 'en cours', '2025-03-30 17:13:54'),
(3, 3, 'Paiement non pris en compte', 'J\'ai réglé ma facture mais le paiement n\'apparaît pas sur mon compte.', 'résolue', '2025-03-30 17:13:54'),
(4, 4, 'Coupure injustifiée', 'Mon électricité a été coupée alors que j\'ai payé ma facture.', 'soumise', '2025-03-30 17:13:54'),
(5, 5, 'Double prélèvement', 'Ma facture a été débitée deux fois ce mois-ci.', 'résolue', '2025-03-30 17:13:54'),
(6, 6, 'Erreur de compte', 'Mon paiement a été affecté à un mauvais compte client.', 'soumise', '2025-03-30 17:13:54'),
(7, 7, 'Délai de traitement long', 'Mon paiement met trop de temps à être pris en compte.', 'résolue', '2025-03-30 17:13:54'),
(8, 8, 'Problème de connexion', 'Impossible d\'accéder à mon compte pour payer ma facture.', 'en cours', '2025-03-30 17:13:54');

-- --------------------------------------------------------

--
-- Structure de la table `tarifelectricite`
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
-- Structure de la table `utilisateur`
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
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `nom`, `prenom`, `email`, `mot_de_passe`, `type`, `date_creation`) VALUES
(5, 'benani', 'ahme', 'rajae9433@gmail.com', '$2y$10$9PHMEs3NzVidFVV9ePdyBefu14L38vRjJ47BLaixR5312fV8oVQFy', 'client', '2025-03-30 17:22:58'),
(7, 'Admin', 'Super', 'admin@voltforce.com', '1', 'fournisseur', '2025-04-01 22:03:12'),
(4, 'allaoui', 'hala', 'hala@gmail.com', '$2y$10$6Ymqs0EON74cZDHj8/CNBuqm/989l4cNCLboIqMyNhWSEiaRMvjzK', 'client', '2025-03-30 17:16:49');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
