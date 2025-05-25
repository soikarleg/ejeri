-- phpMyAdmin SQL Dump
-- version 4.9.11
-- https://www.phpmyadmin.net/
--
-- Hôte : db5017695380.hosting-data.io
-- Généré le : ven. 23 mai 2025 à 20:46
-- Version du serveur : 8.0.36
-- Version de PHP : 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `dbs14147264`
--
CREATE DATABASE IF NOT EXISTS `dbs14147264` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `dbs14147264`;

-- --------------------------------------------------------

--
-- Structure de la table `adresses`
--

DROP TABLE IF EXISTS `adresses`;
CREATE TABLE IF NOT EXISTS `adresses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `compte_id` int NOT NULL,
  `type` enum('facturation','intervention','personnel','organisation_principale','organisation_secondaire') NOT NULL,
  `ligne1` varchar(255) NOT NULL,
  `ligne2` varchar(255) DEFAULT NULL,
  `cp` varchar(10) DEFAULT NULL,
  `ville` varchar(100) DEFAULT NULL,
  `pays` varchar(50) DEFAULT NULL,
  `client_id` int DEFAULT NULL,
  `user_idcli` int DEFAULT NULL,
  `organisation_id` int DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `compte_id` (`compte_id`),
  KEY `client_id` (`client_id`),
  KEY `user_idcli` (`user_idcli`),
  KEY `organisation_id` (`organisation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `banque`
--

DROP TABLE IF EXISTS `banque`;
CREATE TABLE IF NOT EXISTS `banque` (
  `id` int NOT NULL AUTO_INCREMENT,
  `compte_id` int NOT NULL,
  `nom` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `iban` varchar(34) COLLATE utf8mb4_general_ci NOT NULL,
  `bic` varchar(11) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `api_bridge_id` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `webhook_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `compte_id` (`compte_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

DROP TABLE IF EXISTS `clients`;
CREATE TABLE IF NOT EXISTS `clients` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idcli` int NOT NULL,
  `compte_id` int DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `nom` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `prenom` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telephone` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `email_2` (`email`),
  UNIQUE KEY `email_3` (`email`),
  UNIQUE KEY `email_4` (`email`),
  UNIQUE KEY `email_5` (`email`),
  UNIQUE KEY `email_6` (`email`),
  UNIQUE KEY `email_7` (`email`),
  UNIQUE KEY `email_8` (`email`),
  UNIQUE KEY `email_9` (`email`),
  UNIQUE KEY `email_10` (`email`),
  UNIQUE KEY `email_11` (`email`),
  KEY `fk_clients_organisations` (`compte_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `corps_devis`
--

DROP TABLE IF EXISTS `corps_devis`;
CREATE TABLE IF NOT EXISTS `corps_devis` (
  `id` int NOT NULL AUTO_INCREMENT,
  `devis_id` int NOT NULL,
  `designation` varchar(255) NOT NULL,
  `quantite` decimal(10,2) NOT NULL,
  `prix_unitaire` decimal(10,2) NOT NULL,
  `tva` decimal(5,2) NOT NULL,
  `ordre` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `devis_id` (`devis_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `corps_factures`
--

DROP TABLE IF EXISTS `corps_factures`;
CREATE TABLE IF NOT EXISTS `corps_factures` (
  `id` int NOT NULL AUTO_INCREMENT,
  `facture_id` int NOT NULL,
  `designation` varchar(255) NOT NULL,
  `quantite` decimal(10,2) NOT NULL,
  `prix_unitaire` decimal(10,2) NOT NULL,
  `tva` decimal(5,2) NOT NULL,
  `ordre` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `facture_id` (`facture_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `demande`
--

DROP TABLE IF EXISTS `demande`;
CREATE TABLE IF NOT EXISTS `demande` (
  `id` int NOT NULL AUTO_INCREMENT,
  `prenom` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `nom` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `telephone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sujet` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `message` text COLLATE utf8mb4_general_ci NOT NULL,
  `date_submitted` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `depenses`
--

DROP TABLE IF EXISTS `depenses`;
CREATE TABLE IF NOT EXISTS `depenses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `compte_id` int NOT NULL,
  `banque_id` int NOT NULL,
  `date_depense` date NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  `libelle` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `categorie` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `api_bridge_id` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `compte_id` (`compte_id`),
  KEY `banque_id` (`banque_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `devis`
--

DROP TABLE IF EXISTS `devis`;
CREATE TABLE IF NOT EXISTS `devis` (
  `id` int NOT NULL AUTO_INCREMENT,
  `organisation_id` int NOT NULL,
  `client_id` int NOT NULL,
  `intervenant_id` int DEFAULT NULL,
  `date_devis` date NOT NULL,
  `statut` varchar(32) NOT NULL DEFAULT 'en_cours',
  `total_ht` decimal(10,2) NOT NULL,
  `total_ttc` decimal(10,2) NOT NULL,
  `commentaire` text,
  PRIMARY KEY (`id`),
  KEY `organisation_id` (`organisation_id`),
  KEY `client_id` (`client_id`),
  KEY `intervenant_id` (`intervenant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `event`
--

DROP TABLE IF EXISTS `event`;
CREATE TABLE IF NOT EXISTS `event` (
  `id` int NOT NULL AUTO_INCREMENT,
  `compte_id` int NOT NULL,
  `intervenant_id` int NOT NULL,
  `client_id` int NOT NULL,
  `adresse_id` int NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `heure_debut` time NOT NULL,
  `heure_fin` time NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `compte_id` (`compte_id`),
  KEY `intervenant_id` (`intervenant_id`),
  KEY `client_id` (`client_id`),
  KEY `adresse_id` (`adresse_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `factures`
--

DROP TABLE IF EXISTS `factures`;
CREATE TABLE IF NOT EXISTS `factures` (
  `id` int NOT NULL AUTO_INCREMENT,
  `compte_id` int NOT NULL,
  `client_id` int NOT NULL,
  `numero` varchar(50) NOT NULL,
  `date_emission` date NOT NULL,
  `date_echeance` date DEFAULT NULL,
  `statut` enum('brouillon','envoyee','payee','relance','annulee') DEFAULT 'brouillon',
  `montant_ht` decimal(10,2) NOT NULL,
  `montant_tva` decimal(10,2) NOT NULL,
  `montant_ttc` decimal(10,2) NOT NULL,
  `objet` varchar(255) DEFAULT NULL,
  `commentaire` text,
  `lien_pdf` varchar(255) DEFAULT NULL,
  `date_paiement` date DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `numero` (`numero`,`compte_id`),
  KEY `compte_id` (`compte_id`),
  KEY `client_id` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `intervenants`
--

DROP TABLE IF EXISTS `intervenants`;
CREATE TABLE IF NOT EXISTS `intervenants` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idinter` int NOT NULL,
  `compte_id` int NOT NULL,
  `civilite` varchar(10) DEFAULT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `telephone` varchar(30) DEFAULT NULL,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`,`compte_id`),
  UNIQUE KEY `email_2` (`email`,`compte_id`),
  UNIQUE KEY `email_3` (`email`,`compte_id`),
  UNIQUE KEY `email_4` (`email`,`compte_id`),
  UNIQUE KEY `email_5` (`email`,`compte_id`),
  UNIQUE KEY `email_6` (`email`,`compte_id`),
  UNIQUE KEY `email_7` (`email`,`compte_id`),
  UNIQUE KEY `email_8` (`email`,`compte_id`),
  UNIQUE KEY `email_9` (`email`,`compte_id`),
  UNIQUE KEY `email_10` (`email`,`compte_id`),
  KEY `fk_intervenants_organisations` (`compte_id`),
  KEY `fk_intervenants_users` (`idinter`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `lien_paiement`
--

DROP TABLE IF EXISTS `lien_paiement`;
CREATE TABLE IF NOT EXISTS `lien_paiement` (
  `id` int NOT NULL AUTO_INCREMENT,
  `compte_id` int NOT NULL,
  `client_id` int NOT NULL,
  `facture_id` int NOT NULL,
  `mollie_id` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `statut` enum('envoye','en_attente','payee','expire','annule') COLLATE utf8mb4_general_ci DEFAULT 'en_attente',
  `webhook_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `compte_id` (`compte_id`),
  KEY `client_id` (`client_id`),
  KEY `facture_id` (`facture_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `organisations`
--

DROP TABLE IF EXISTS `organisations`;
CREATE TABLE IF NOT EXISTS `organisations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `couleur` varchar(7) COLLATE utf8mb4_general_ci DEFAULT '#cccccc',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom` (`nom`),
  UNIQUE KEY `nom_2` (`nom`),
  UNIQUE KEY `nom_3` (`nom`),
  UNIQUE KEY `nom_4` (`nom`),
  UNIQUE KEY `nom_5` (`nom`),
  UNIQUE KEY `nom_6` (`nom`),
  UNIQUE KEY `nom_7` (`nom`),
  UNIQUE KEY `nom_8` (`nom`),
  UNIQUE KEY `nom_9` (`nom`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `paiement`
--

DROP TABLE IF EXISTS `paiement`;
CREATE TABLE IF NOT EXISTS `paiement` (
  `id` int NOT NULL AUTO_INCREMENT,
  `compte_id` int NOT NULL,
  `client_id` int NOT NULL,
  `facture_id` int NOT NULL,
  `date_paiement` date NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  `moyen` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `banque_id` int DEFAULT NULL,
  `etat` enum('en_attente','partiel','total') COLLATE utf8mb4_general_ci DEFAULT 'en_attente',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `compte_id` (`compte_id`),
  KEY `client_id` (`client_id`),
  KEY `facture_id` (`facture_id`),
  KEY `banque_id` (`banque_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déclencheurs `paiement`
--
DROP TRIGGER IF EXISTS `trg_paiement_facture_total`;
DELIMITER $$
CREATE TRIGGER `trg_paiement_facture_total` AFTER INSERT ON `paiement` FOR EACH ROW BEGIN
  IF NEW.etat = 'total' THEN
    UPDATE factures SET statut = 'payee' WHERE id = NEW.facture_id;
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `production`
--

DROP TABLE IF EXISTS `production`;
CREATE TABLE IF NOT EXISTS `production` (
  `id` int NOT NULL AUTO_INCREMENT,
  `compte_id` int NOT NULL,
  `client_id` int NOT NULL,
  `intervenant_id` int NOT NULL,
  `adresse_id` int NOT NULL,
  `date_production` date NOT NULL,
  `heures` decimal(5,2) NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `compte_id` (`compte_id`),
  KEY `client_id` (`client_id`),
  KEY `intervenant_id` (`intervenant_id`),
  KEY `adresse_id` (`adresse_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `idcli` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('client','intervenant','admin','gerant','responsable') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'client',
  `activation_token` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `reset_token` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `activated_at` datetime DEFAULT NULL,
  `compte_id` int NOT NULL,
  PRIMARY KEY (`idcli`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `email_2` (`email`),
  UNIQUE KEY `email_3` (`email`),
  UNIQUE KEY `email_4` (`email`),
  UNIQUE KEY `email_5` (`email`),
  UNIQUE KEY `email_6` (`email`),
  UNIQUE KEY `email_7` (`email`),
  UNIQUE KEY `email_8` (`email`),
  UNIQUE KEY `email_9` (`email`),
  UNIQUE KEY `email_10` (`email`),
  UNIQUE KEY `email_11` (`email`),
  UNIQUE KEY `email_12` (`email`),
  KEY `fk_users_organisations` (`compte_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `vue_adresses_facturation`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `vue_adresses_facturation`;
CREATE TABLE IF NOT EXISTS `vue_adresses_facturation` (
`id` int
,`compte_id` int
,`type` enum('facturation','intervention','personnel','organisation_principale','organisation_secondaire')
,`ligne1` varchar(255)
,`ligne2` varchar(255)
,`cp` varchar(10)
,`ville` varchar(100)
,`pays` varchar(50)
,`client_id` int
,`user_idcli` int
,`organisation_id` int
,`description` varchar(255)
,`date_creation` datetime
,`client_nom` varchar(100)
,`client_prenom` varchar(100)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `vue_factures_en_cours`
-- (Voir ci-dessous la vue réelle)
--
DROP VIEW IF EXISTS `vue_factures_en_cours`;
CREATE TABLE IF NOT EXISTS `vue_factures_en_cours` (
`id` int
,`compte_id` int
,`client_id` int
,`numero` varchar(50)
,`date_emission` date
,`date_echeance` date
,`statut` enum('brouillon','envoyee','payee','relance','annulee')
,`montant_ht` decimal(10,2)
,`montant_tva` decimal(10,2)
,`montant_ttc` decimal(10,2)
,`objet` varchar(255)
,`commentaire` text
,`lien_pdf` varchar(255)
,`date_paiement` date
,`created_at` datetime
,`updated_at` datetime
,`client_nom` varchar(100)
,`client_prenom` varchar(100)
);

-- --------------------------------------------------------

--
-- Structure de la vue `vue_adresses_facturation` exportée comme une table
--
DROP TABLE IF EXISTS `vue_adresses_facturation`;
CREATE TABLE IF NOT EXISTS `vue_adresses_facturation`(
    `id` int NOT NULL DEFAULT '0',
    `compte_id` int NOT NULL,
    `type` enum('facturation','intervention','personnel','organisation_principale','organisation_secondaire') COLLATE utf8mb4_0900_ai_ci NOT NULL,
    `ligne1` varchar(255) COLLATE utf8mb4_0900_ai_ci NOT NULL,
    `ligne2` varchar(255) COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
    `cp` varchar(10) COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
    `ville` varchar(100) COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
    `pays` varchar(50) COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
    `client_id` int DEFAULT NULL,
    `user_idcli` int DEFAULT NULL,
    `organisation_id` int DEFAULT NULL,
    `description` varchar(255) COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
    `date_creation` datetime DEFAULT 'CURRENT_TIMESTAMP',
    `client_nom` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
    `client_prenom` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL
);

-- --------------------------------------------------------

--
-- Structure de la vue `vue_factures_en_cours` exportée comme une table
--
DROP TABLE IF EXISTS `vue_factures_en_cours`;
CREATE TABLE IF NOT EXISTS `vue_factures_en_cours`(
    `id` int NOT NULL DEFAULT '0',
    `compte_id` int NOT NULL,
    `client_id` int NOT NULL,
    `numero` varchar(50) COLLATE utf8mb4_0900_ai_ci NOT NULL,
    `date_emission` date NOT NULL,
    `date_echeance` date DEFAULT NULL,
    `statut` enum('brouillon','envoyee','payee','relance','annulee') COLLATE utf8mb4_0900_ai_ci DEFAULT 'brouillon',
    `montant_ht` decimal(10,2) NOT NULL,
    `montant_tva` decimal(10,2) NOT NULL,
    `montant_ttc` decimal(10,2) NOT NULL,
    `objet` varchar(255) COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
    `commentaire` text COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
    `lien_pdf` varchar(255) COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
    `date_paiement` date DEFAULT NULL,
    `created_at` datetime DEFAULT 'CURRENT_TIMESTAMP',
    `updated_at` datetime DEFAULT 'CURRENT_TIMESTAMP',
    `client_nom` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
    `client_prenom` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL
);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `adresses`
--
ALTER TABLE `adresses`
  ADD CONSTRAINT `adresses_ibfk_1` FOREIGN KEY (`compte_id`) REFERENCES `organisations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `adresses_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `adresses_ibfk_3` FOREIGN KEY (`user_idcli`) REFERENCES `users` (`idcli`) ON DELETE SET NULL,
  ADD CONSTRAINT `adresses_ibfk_4` FOREIGN KEY (`organisation_id`) REFERENCES `organisations` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `banque`
--
ALTER TABLE `banque`
  ADD CONSTRAINT `banque_ibfk_1` FOREIGN KEY (`compte_id`) REFERENCES `organisations` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `fk_clients_organisations` FOREIGN KEY (`compte_id`) REFERENCES `organisations` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `corps_devis`
--
ALTER TABLE `corps_devis`
  ADD CONSTRAINT `fk_corps_devis_devis` FOREIGN KEY (`devis_id`) REFERENCES `devis` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `corps_factures`
--
ALTER TABLE `corps_factures`
  ADD CONSTRAINT `fk_corps_factures_facture` FOREIGN KEY (`facture_id`) REFERENCES `factures` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `depenses`
--
ALTER TABLE `depenses`
  ADD CONSTRAINT `depenses_ibfk_1` FOREIGN KEY (`compte_id`) REFERENCES `organisations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `depenses_ibfk_2` FOREIGN KEY (`banque_id`) REFERENCES `banque` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `devis`
--
ALTER TABLE `devis`
  ADD CONSTRAINT `fk_devis_client` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_devis_intervenant` FOREIGN KEY (`intervenant_id`) REFERENCES `intervenants` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_devis_organisation` FOREIGN KEY (`organisation_id`) REFERENCES `organisations` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `event_ibfk_1` FOREIGN KEY (`compte_id`) REFERENCES `organisations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `event_ibfk_2` FOREIGN KEY (`intervenant_id`) REFERENCES `intervenants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `event_ibfk_3` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `event_ibfk_4` FOREIGN KEY (`adresse_id`) REFERENCES `adresses` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `factures`
--
ALTER TABLE `factures`
  ADD CONSTRAINT `factures_ibfk_1` FOREIGN KEY (`compte_id`) REFERENCES `organisations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `factures_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `intervenants`
--
ALTER TABLE `intervenants`
  ADD CONSTRAINT `fk_intervenants_organisations` FOREIGN KEY (`compte_id`) REFERENCES `organisations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_intervenants_users` FOREIGN KEY (`idinter`) REFERENCES `users` (`idcli`) ON DELETE CASCADE;

--
-- Contraintes pour la table `lien_paiement`
--
ALTER TABLE `lien_paiement`
  ADD CONSTRAINT `lien_paiement_ibfk_1` FOREIGN KEY (`compte_id`) REFERENCES `organisations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lien_paiement_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lien_paiement_ibfk_3` FOREIGN KEY (`facture_id`) REFERENCES `factures` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `paiement`
--
ALTER TABLE `paiement`
  ADD CONSTRAINT `paiement_ibfk_1` FOREIGN KEY (`compte_id`) REFERENCES `organisations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `paiement_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `paiement_ibfk_3` FOREIGN KEY (`facture_id`) REFERENCES `factures` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `paiement_ibfk_4` FOREIGN KEY (`banque_id`) REFERENCES `banque` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `production`
--
ALTER TABLE `production`
  ADD CONSTRAINT `production_ibfk_1` FOREIGN KEY (`compte_id`) REFERENCES `organisations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `production_ibfk_2` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `production_ibfk_3` FOREIGN KEY (`intervenant_id`) REFERENCES `intervenants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `production_ibfk_4` FOREIGN KEY (`adresse_id`) REFERENCES `adresses` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_organisations` FOREIGN KEY (`compte_id`) REFERENCES `organisations` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
