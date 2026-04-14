-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  ven. 14 nov. 2025 à 09:50
-- Version du serveur :  5.7.26
-- Version de PHP :  7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `central+`
--

-- --------------------------------------------------------

--
-- Structure de la table `banque_sangs`
--

DROP TABLE IF EXISTS `banque_sangs`;
CREATE TABLE IF NOT EXISTS `banque_sangs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `banque_sangs`
--

INSERT INTO `banque_sangs` (`id`, `nom`, `adresse`, `telephone`, `email`, `logo`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Banque de Sang Nationale', 'Kinshasa, RDC', '+243 777 888 999', 'contact@banquesang.cd', NULL, NULL, '2025-11-07 12:38:36', '2025-11-07 12:38:36'),
(2, 'Centre de Transfusion Sanguine', 'Lubumbashi, RDC', '+243 666 777 888', 'contact@transfusion.cd', NULL, NULL, '2025-11-07 12:38:36', '2025-11-07 12:38:36'),
(3, 'santé vie', 'Vovo 71, efobanck,n\'sele', NULL, 'santevie@gmail.com', NULL, NULL, '2025-11-09 03:19:33', '2025-11-09 03:19:33');

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `centres`
--

DROP TABLE IF EXISTS `centres`;
CREATE TABLE IF NOT EXISTS `centres` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

DROP TABLE IF EXISTS `commandes`;
CREATE TABLE IF NOT EXISTS `commandes` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pharmacie_id` bigint(20) UNSIGNED NOT NULL,
  `fournisseur_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `numero_commande` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `statut` enum('brouillon','en_attente','validee','en_cours','livree_partielle','livree','annulee') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'brouillon',
  `date_commande` date NOT NULL,
  `date_livraison_prevue` date DEFAULT NULL,
  `date_livraison_reelle` date DEFAULT NULL,
  `montant_total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `montant_tva` decimal(10,2) NOT NULL DEFAULT '0.00',
  `frais_livraison` decimal(10,2) NOT NULL DEFAULT '0.00',
  `remise` decimal(10,2) NOT NULL DEFAULT '0.00',
  `montant_final` decimal(12,2) NOT NULL DEFAULT '0.00',
  `reference_fournisseur` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_facture` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `notes_reception` text COLLATE utf8mb4_unicode_ci,
  `validee_par` bigint(20) UNSIGNED DEFAULT NULL,
  `validee_at` timestamp NULL DEFAULT NULL,
  `receptionnee_par` bigint(20) UNSIGNED DEFAULT NULL,
  `receptionnee_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `commandes_numero_commande_unique` (`numero_commande`),
  KEY `commandes_pharmacie_id_index` (`pharmacie_id`),
  KEY `commandes_fournisseur_id_index` (`fournisseur_id`),
  KEY `commandes_statut_index` (`statut`),
  KEY `commandes_pharmacie_id_statut_index` (`pharmacie_id`,`statut`),
  KEY `commandes_date_commande_index` (`date_commande`),
  KEY `commandes_user_id_foreign` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `consultations`
--

DROP TABLE IF EXISTS `consultations`;
CREATE TABLE IF NOT EXISTS `consultations` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `hopital_id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `medecin_id` bigint(20) UNSIGNED NOT NULL,
  `receptionniste_id` bigint(20) UNSIGNED NOT NULL,
  `caissier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `dossier_medical_id` bigint(20) UNSIGNED DEFAULT NULL,
  `poids` decimal(5,2) DEFAULT NULL COMMENT 'En kg',
  `taille` decimal(5,2) DEFAULT NULL COMMENT 'En cm',
  `temperature` decimal(4,1) DEFAULT NULL COMMENT 'En °C',
  `tension_arterielle` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ex: 120/80',
  `frequence_cardiaque` int(11) DEFAULT NULL COMMENT 'Pouls en bpm',
  `motif_consultation` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `frais_consultation` decimal(10,2) NOT NULL DEFAULT '0.00',
  `statut_paiement` enum('en_attente','paye','rembourse') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en_attente',
  `mode_paiement` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Espèces, Carte, Mobile Money, etc.',
  `montant_paye` decimal(10,2) DEFAULT NULL,
  `date_paiement` datetime DEFAULT NULL,
  `numero_facture` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `statut_consultation` enum('en_attente_paiement','paye_en_attente','en_cours','termine','annule') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en_attente_paiement',
  `date_consultation` datetime DEFAULT NULL COMMENT 'Date/heure de début de la consultation',
  `date_fin_consultation` datetime DEFAULT NULL,
  `notes_receptionniste` text COLLATE utf8mb4_unicode_ci,
  `notes_caissier` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `consultations_patient_id_foreign` (`patient_id`),
  KEY `consultations_medecin_id_foreign` (`medecin_id`),
  KEY `consultations_receptionniste_id_foreign` (`receptionniste_id`),
  KEY `consultations_caissier_id_foreign` (`caissier_id`),
  KEY `consultations_dossier_medical_id_foreign` (`dossier_medical_id`),
  KEY `consultations_hopital_id_index` (`hopital_id`),
  KEY `consultations_statut_paiement_index` (`statut_paiement`),
  KEY `consultations_statut_consultation_index` (`statut_consultation`),
  KEY `consultations_date_consultation_index` (`date_consultation`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `consultations`
--

INSERT INTO `consultations` (`id`, `hopital_id`, `patient_id`, `medecin_id`, `receptionniste_id`, `caissier_id`, `dossier_medical_id`, `poids`, `taille`, `temperature`, `tension_arterielle`, `frequence_cardiaque`, `motif_consultation`, `frais_consultation`, `statut_paiement`, `mode_paiement`, `montant_paye`, `date_paiement`, `numero_facture`, `statut_consultation`, `date_consultation`, `date_fin_consultation`, `notes_receptionniste`, `notes_caissier`, `created_at`, `updated_at`) VALUES
(1, 3, 14, 4, 11, 9, 2, '80.00', '170.00', '40.0', '130/80', 75, 'fievres', '10000.00', 'paye', 'especes', '10000.00', '2025-11-11 16:28:54', 'FACT-3-20251111-000001', 'termine', NULL, NULL, NULL, NULL, '2025-11-11 15:22:13', '2025-11-12 02:45:28');

-- --------------------------------------------------------

--
-- Structure de la table `demandes_sang`
--

DROP TABLE IF EXISTS `demandes_sang`;
CREATE TABLE IF NOT EXISTS `demandes_sang` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `banque_sang_id` bigint(20) UNSIGNED NOT NULL,
  `hopital_id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED DEFAULT NULL,
  `numero_demande` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `groupe_sanguin` enum('A+','A-','B+','B-','AB+','AB-','O+','O-') COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantite_demandee` decimal(5,2) NOT NULL,
  `quantite_fournie` decimal(5,2) NOT NULL DEFAULT '0.00',
  `urgence` enum('normale','urgente','critique') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normale',
  `statut` enum('en_attente','en_preparation','prete','livree','annulee') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en_attente',
  `date_demande` date NOT NULL,
  `date_besoin` date NOT NULL,
  `date_livraison` date DEFAULT NULL,
  `nom_patient` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `medecin_demandeur` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `indication_medicale` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `traitee_par` bigint(20) UNSIGNED DEFAULT NULL,
  `traitee_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `demandes_sang_numero_demande_unique` (`numero_demande`),
  KEY `demandes_sang_banque_sang_id_index` (`banque_sang_id`),
  KEY `demandes_sang_hopital_id_index` (`hopital_id`),
  KEY `demandes_sang_groupe_sanguin_index` (`groupe_sanguin`),
  KEY `demandes_sang_statut_index` (`statut`),
  KEY `demandes_sang_urgence_index` (`urgence`),
  KEY `demandes_sang_banque_sang_id_statut_index` (`banque_sang_id`,`statut`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `demandes_transfert_dossier`
--

DROP TABLE IF EXISTS `demandes_transfert_dossier`;
CREATE TABLE IF NOT EXISTS `demandes_transfert_dossier` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `hopital_demandeur_id` bigint(20) UNSIGNED NOT NULL,
  `hopital_detenteur_id` bigint(20) UNSIGNED NOT NULL,
  `dossier_medical_id` bigint(20) UNSIGNED DEFAULT NULL,
  `statut` enum('en_attente_patient','accepte_patient','refuse_patient','transfere','refuse_hopital','annule') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en_attente_patient',
  `motif_demande` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes_demandeur` text COLLATE utf8mb4_unicode_ci,
  `notes_detenteur` text COLLATE utf8mb4_unicode_ci,
  `reponse_patient` text COLLATE utf8mb4_unicode_ci,
  `date_demande` timestamp NOT NULL,
  `date_consentement_patient` timestamp NULL DEFAULT NULL,
  `date_transfert` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `demandes_transfert_dossier_dossier_medical_id_foreign` (`dossier_medical_id`),
  KEY `demandes_transfert_dossier_patient_id_index` (`patient_id`),
  KEY `demandes_transfert_dossier_hopital_demandeur_id_index` (`hopital_demandeur_id`),
  KEY `demandes_transfert_dossier_hopital_detenteur_id_index` (`hopital_detenteur_id`),
  KEY `demandes_transfert_dossier_statut_index` (`statut`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `donneurs`
--

DROP TABLE IF EXISTS `donneurs`;
CREATE TABLE IF NOT EXISTS `donneurs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `banque_sang_id` bigint(20) UNSIGNED NOT NULL,
  `numero_donneur` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sexe` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_naissance` date NOT NULL,
  `groupe_sanguin` enum('A+','A-','B+','B-','AB+','AB-','O+','O-') COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `ville` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profession` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `poids` decimal(5,2) DEFAULT NULL,
  `numero_carte_identite` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `eligible` tinyint(1) NOT NULL DEFAULT '1',
  `raison_ineligibilite` text COLLATE utf8mb4_unicode_ci,
  `derniere_date_don` date DEFAULT NULL,
  `nombre_dons` int(11) NOT NULL DEFAULT '0',
  `antecedents_medicaux` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `donneurs_numero_donneur_unique` (`numero_donneur`),
  KEY `donneurs_banque_sang_id_index` (`banque_sang_id`),
  KEY `donneurs_groupe_sanguin_index` (`groupe_sanguin`),
  KEY `donneurs_banque_sang_id_eligible_index` (`banque_sang_id`,`eligible`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `dons`
--

DROP TABLE IF EXISTS `dons`;
CREATE TABLE IF NOT EXISTS `dons` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `banque_sang_id` bigint(20) UNSIGNED NOT NULL,
  `donneur_id` bigint(20) UNSIGNED NOT NULL,
  `technicien_id` bigint(20) UNSIGNED NOT NULL,
  `numero_don` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_don` date NOT NULL,
  `heure_don` time NOT NULL,
  `groupe_sanguin` enum('A+','A-','B+','B-','AB+','AB-','O+','O-') COLLATE utf8mb4_unicode_ci NOT NULL,
  `volume_preleve` decimal(5,2) NOT NULL,
  `type_don` enum('sang_total','plasma','plaquettes','globules_rouges') COLLATE utf8mb4_unicode_ci NOT NULL,
  `statut` enum('en_attente_analyse','analyse_en_cours','conforme','non_conforme','utilise','perime') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en_attente_analyse',
  `observations_prelevement` text COLLATE utf8mb4_unicode_ci,
  `tension_arterielle_systolique` decimal(5,2) DEFAULT NULL,
  `tension_arterielle_diastolique` decimal(5,2) DEFAULT NULL,
  `hemoglobine` decimal(5,2) DEFAULT NULL,
  `temperature` decimal(4,1) DEFAULT NULL,
  `resultats_analyses` text COLLATE utf8mb4_unicode_ci,
  `date_analyse` date DEFAULT NULL,
  `date_expiration` date DEFAULT NULL,
  `numero_poche` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emplacement_stockage` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dons_numero_don_unique` (`numero_don`),
  KEY `dons_banque_sang_id_index` (`banque_sang_id`),
  KEY `dons_donneur_id_index` (`donneur_id`),
  KEY `dons_groupe_sanguin_index` (`groupe_sanguin`),
  KEY `dons_statut_index` (`statut`),
  KEY `dons_banque_sang_id_statut_index` (`banque_sang_id`,`statut`),
  KEY `dons_technicien_id_foreign` (`technicien_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `dossier_medicals`
--

DROP TABLE IF EXISTS `dossier_medicals`;
CREATE TABLE IF NOT EXISTS `dossier_medicals` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `medecin_id` bigint(20) UNSIGNED NOT NULL,
  `hopital_id` bigint(20) UNSIGNED NOT NULL,
  `numero_dossier` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `motif_consultation` text COLLATE utf8mb4_unicode_ci,
  `antecedents` text COLLATE utf8mb4_unicode_ci,
  `examen_clinique` text COLLATE utf8mb4_unicode_ci,
  `diagnostic` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `traitement` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `observations` text COLLATE utf8mb4_unicode_ci,
  `date_consultation` date NOT NULL,
  `date_prochain_rdv` date DEFAULT NULL,
  `urgence` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normale',
  `statut` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'actif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dossier_medicals_numero_dossier_unique` (`numero_dossier`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `dossier_medicals`
--

INSERT INTO `dossier_medicals` (`id`, `patient_id`, `medecin_id`, `hopital_id`, `numero_dossier`, `motif_consultation`, `antecedents`, `examen_clinique`, `diagnostic`, `traitement`, `observations`, `date_consultation`, `date_prochain_rdv`, `urgence`, `statut`, `created_at`, `updated_at`) VALUES
(1, 8, 4, 3, 'DM-20251109-00001', 'gsdhfejf\n\nHISTOIRE DE LA MALADIE:\njcksvldcv\n\nSYMPTÔMES:\nhcjdvld', 'ANTÉCÉDENTS MÉDICAUX:\ncghjok\n\nANTÉCÉDENTS FAMILIAUX:\njdfpdv\n\nALLERGIES:\ndhfjekf\n\nTRAITEMENTS EN COURS:\nfhdif', 'SIGNES VITAUX:\nTempérature: 40°C\nPoids: 80 kg\nTaille: 150 cm\nIMC: 35.6', 'ghsdfedlf', '=== TRAITEMENT PRESCRIT LE 14/11/2025 ===\n\nparacetamol\n\n=== TRAITEMENT PRESCRIT LE 14/11/2025 ===\n\nparacetamol', NULL, '2025-11-09', NULL, 'normale', 'actif', '2025-11-09 21:19:29', '2025-11-13 23:23:52'),
(2, 14, 4, 3, 'DOS-69136B07AE2AB', 'fievres', NULL, 'gonflement abdominal', 'DIAGNOSTIC FINAL (Confirmé):\nappendicite aigue\n\nDIAGNOSTIC INITIAL:\nDIAGNOSTIC FINAL (Confirmé):\nappendicite aigue\n\nDIAGNOSTIC INITIAL:\nune entorse\n\n=== RÉSULTAT EXAMEN: echographie ===\nDate: 12/11/2025 03:37\nRésultats: appendicite aigue', '=== TRAITEMENT PRESCRIT LE 12/11/2025 ===\n\nintervention chirurgicale\n\n=== TRAITEMENT PRESCRIT LE 12/11/2025 ===\n\nintervention chirurgicale', NULL, '2025-11-11', NULL, 'normale', 'actif', '2025-11-11 15:57:43', '2025-11-12 02:44:24');

-- --------------------------------------------------------

--
-- Structure de la table `examens_prescrits`
--

DROP TABLE IF EXISTS `examens_prescrits`;
CREATE TABLE IF NOT EXISTS `examens_prescrits` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `dossier_medical_id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `medecin_id` bigint(20) UNSIGNED NOT NULL,
  `hopital_id` bigint(20) UNSIGNED NOT NULL,
  `laborantin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `numero_examen` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_examen` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom_examen` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `indication` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_prescription` date NOT NULL,
  `date_realisation` date DEFAULT NULL,
  `prix` decimal(10,2) NOT NULL DEFAULT '0.00',
  `statut_paiement` enum('en_attente','paye','annule') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en_attente',
  `date_paiement` date DEFAULT NULL,
  `valide_par` bigint(20) UNSIGNED DEFAULT NULL,
  `statut_examen` enum('prescrit','paye','en_cours','termine') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'prescrit',
  `resultats` text COLLATE utf8mb4_unicode_ci,
  `interpretation` text COLLATE utf8mb4_unicode_ci,
  `fichier_resultat` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `examens_prescrits_numero_examen_unique` (`numero_examen`),
  KEY `examens_prescrits_dossier_medical_id_statut_examen_index` (`dossier_medical_id`,`statut_examen`),
  KEY `examens_prescrits_hopital_id_statut_paiement_index` (`hopital_id`,`statut_paiement`),
  KEY `examens_prescrits_laborantin_id_statut_examen_index` (`laborantin_id`,`statut_examen`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `examens_prescrits`
--

INSERT INTO `examens_prescrits` (`id`, `dossier_medical_id`, `patient_id`, `medecin_id`, `hopital_id`, `laborantin_id`, `numero_examen`, `type_examen`, `nom_examen`, `indication`, `date_prescription`, `date_realisation`, `prix`, `statut_paiement`, `date_paiement`, `valide_par`, `statut_examen`, `resultats`, `interpretation`, `fichier_resultat`, `created_at`, `updated_at`) VALUES
(1, 1, 8, 4, 3, 10, 'EX-20251109-00001', 'Biologie', 'test de paludisme', 'pour verifier si le patient est atteint du paludisme', '2025-11-09', '2025-11-09', '10.00', 'paye', '2025-11-09', 9, 'termine', '2+', 'le patient est atteint d\'une forme severe de paludisme', NULL, '2025-11-09 21:27:39', '2025-11-09 21:35:04'),
(2, 2, 14, 4, 3, 10, 'EX-20251112-00002', 'Imagerie', 'echographie', '', '2025-11-12', '2025-11-12', '50.00', 'paye', '2025-11-12', NULL, 'termine', 'appendicite aigue', NULL, NULL, '2025-11-12 02:25:04', '2025-11-12 02:37:11'),
(3, 2, 14, 4, 3, 10, 'EX-20251114-00003', 'Imagerie', 'scanner', '', '2025-11-14', NULL, '50000.00', 'paye', '2025-11-14', NULL, 'en_cours', NULL, NULL, NULL, '2025-11-13 23:06:47', '2025-11-13 23:16:53');

-- --------------------------------------------------------

--
-- Structure de la table `fournisseurs`
--

DROP TABLE IF EXISTS `fournisseurs`;
CREATE TABLE IF NOT EXISTS `fournisseurs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pharmacie_id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone_2` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse` text COLLATE utf8mb4_unicode_ci,
  `ville` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pays` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'RDC',
  `contact_nom` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_fonction` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_registre` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_fiscal` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `specialites` text COLLATE utf8mb4_unicode_ci,
  `delai_livraison_jours` int(11) NOT NULL DEFAULT '7',
  `montant_minimum_commande` decimal(10,2) DEFAULT NULL,
  `conditions_paiement` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `fournisseurs_code_unique` (`code`),
  KEY `fournisseurs_pharmacie_id_index` (`pharmacie_id`),
  KEY `fournisseurs_pharmacie_id_actif_index` (`pharmacie_id`,`actif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `hopitaux`
--

DROP TABLE IF EXISTS `hopitaux`;
CREATE TABLE IF NOT EXISTS `hopitaux` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_hopital` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nombre_lits` int(11) DEFAULT NULL,
  `adresse` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `hopitaux`
--

INSERT INTO `hopitaux` (`id`, `nom`, `type_hopital`, `nombre_lits`, `adresse`, `telephone`, `email`, `logo`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Hôpital Saint-Joseph', NULL, NULL, 'Kinshasa, RDC', '+243 123 456 789', 'contact@saintjoseph.cd', NULL, NULL, '2025-11-07 12:38:36', '2025-11-07 12:38:36'),
(2, 'Hôpital Général de Référence', NULL, NULL, 'Lubumbashi, RDC', '+243 987 654 321', 'contact@hgr.cd', NULL, NULL, '2025-11-07 12:38:36', '2025-11-07 12:38:36'),
(3, 'saint-joseph', 'Général', 200, 'Vovo 71, efobanck,n\'sele', NULL, 'saintjoseph@gmail.com', NULL, NULL, '2025-11-07 12:51:31', '2025-11-07 12:51:31'),
(4, 'clinique universitaire', 'Clinique', 200, 'Vovo 71, efobanck,n\'sele', NULL, 'clinique@gmail.com', NULL, NULL, '2025-11-13 10:20:35', '2025-11-13 10:20:35');

-- --------------------------------------------------------

--
-- Structure de la table `lignes_commande`
--

DROP TABLE IF EXISTS `lignes_commande`;
CREATE TABLE IF NOT EXISTS `lignes_commande` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `commande_id` bigint(20) UNSIGNED NOT NULL,
  `medicament_id` bigint(20) UNSIGNED NOT NULL,
  `quantite_commandee` int(11) NOT NULL,
  `quantite_recue` int(11) NOT NULL DEFAULT '0',
  `prix_unitaire` decimal(10,2) NOT NULL,
  `montant_ligne` decimal(10,2) NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lignes_commande_commande_id_index` (`commande_id`),
  KEY `lignes_commande_medicament_id_index` (`medicament_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `medicaments`
--

DROP TABLE IF EXISTS `medicaments`;
CREATE TABLE IF NOT EXISTS `medicaments` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pharmacie_id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nom` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom_generique` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `categorie` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `forme` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dosage` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prix_unitaire` decimal(10,2) NOT NULL,
  `prix_achat` decimal(10,2) DEFAULT NULL,
  `stock_actuel` int(11) NOT NULL DEFAULT '0',
  `stock_minimum` int(11) NOT NULL DEFAULT '10',
  `prescription_requise` tinyint(1) NOT NULL DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci,
  `indication` text COLLATE utf8mb4_unicode_ci,
  `contre_indication` text COLLATE utf8mb4_unicode_ci,
  `effets_secondaires` text COLLATE utf8mb4_unicode_ci,
  `posologie` text COLLATE utf8mb4_unicode_ci,
  `fabricant` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_lot` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_fabrication` date DEFAULT NULL,
  `date_expiration` date DEFAULT NULL,
  `emplacement` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `medicaments_code_unique` (`code`),
  KEY `medicaments_pharmacie_id_index` (`pharmacie_id`),
  KEY `medicaments_categorie_index` (`categorie`),
  KEY `medicaments_nom_index` (`nom`),
  KEY `medicaments_pharmacie_id_actif_index` (`pharmacie_id`,`actif`),
  KEY `medicaments_pharmacie_id_stock_actuel_index` (`pharmacie_id`,`stock_actuel`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `medicaments`
--

INSERT INTO `medicaments` (`id`, `pharmacie_id`, `code`, `nom`, `nom_generique`, `categorie`, `forme`, `dosage`, `prix_unitaire`, `prix_achat`, `stock_actuel`, `stock_minimum`, `prescription_requise`, `description`, `indication`, `contre_indication`, `effets_secondaires`, `posologie`, `fabricant`, `numero_lot`, `date_fabrication`, `date_expiration`, `emplacement`, `actif`, `created_at`, `updated_at`) VALUES
(1, 3, 'CD452', 'paracetamol', NULL, 'Anti-inflammatoires', 'Comprimé', '500mg', '10.00', '5.00', 500, 10, 0, NULL, NULL, NULL, NULL, NULL, 'pharmakina', '425', '2025-05-20', '2030-05-20', 'etagere4', 1, '2025-11-13 23:36:18', '2025-11-13 23:36:18');

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(11, '2024_01_01_000000_create_utilisateurs_table', 1),
(12, '2024_01_01_000001_create_hopitaux_table', 1),
(13, '2024_01_01_000002_create_pharmacies_table', 1),
(14, '2024_01_01_000003_create_banque_sangs_table', 1),
(15, '2024_01_01_000004_create_rendezvous_table', 1),
(16, '2025_07_10_153431_create_centres_table', 2),
(17, '2025_07_11_120612_update_pharmacies_and_banque_sangs_tables', 2),
(18, '2025_08_12_113545_create_cache_table', 2),
(19, '2025_08_22_060130_create_permission_tables', 2),
(20, '2025_09_26_133500_create_dossier_medicals_table', 2),
(21, '2025_11_06_150000_create_demandes_transfert_dossier_table', 2),
(22, '2025_11_06_160000_create_notifications_table', 2),
(23, '2024_01_01_000005_add_fields_to_hopitaux_table', 3),
(24, '2025_11_07_170000_create_examens_prescrits_table', 4),
(25, '2025_11_09_000000_add_entity_fields_to_notifications_table', 5),
(26, '2025_11_09_100000_create_medicaments_table', 6),
(27, '2025_11_09_110000_create_mouvements_stock_table', 7),
(28, '2025_11_09_120000_create_fournisseurs_table', 8),
(29, '2025_11_09_120001_create_commandes_table', 8),
(30, '2025_11_09_120002_create_lignes_commande_table', 8),
(31, '2025_11_09_130000_create_donneurs_table', 9),
(32, '2025_11_09_130001_create_dons_table', 9),
(33, '2025_11_09_130002_create_reserves_sang_table', 9),
(34, '2025_11_09_130003_create_demandes_sang_table', 9),
(35, '2025_11_09_220000_add_fields_to_dossier_medicals_table', 10),
(36, '2025_11_10_143800_add_hopital_id_to_utilisateurs_table', 11),
(38, '2025_11_11_140000_create_consultations_table', 12);

-- --------------------------------------------------------

--
-- Structure de la table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `model_has_permissions`
--

INSERT INTO `model_has_permissions` (`permission_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\Utilisateur', 3),
(2, 'App\\Models\\Utilisateur', 3),
(3, 'App\\Models\\Utilisateur', 3),
(4, 'App\\Models\\Utilisateur', 3),
(5, 'App\\Models\\Utilisateur', 3),
(6, 'App\\Models\\Utilisateur', 3),
(7, 'App\\Models\\Utilisateur', 3),
(8, 'App\\Models\\Utilisateur', 3),
(9, 'App\\Models\\Utilisateur', 3),
(10, 'App\\Models\\Utilisateur', 3),
(11, 'App\\Models\\Utilisateur', 3),
(12, 'App\\Models\\Utilisateur', 3),
(13, 'App\\Models\\Utilisateur', 3),
(14, 'App\\Models\\Utilisateur', 3),
(15, 'App\\Models\\Utilisateur', 3),
(16, 'App\\Models\\Utilisateur', 3),
(17, 'App\\Models\\Utilisateur', 3),
(18, 'App\\Models\\Utilisateur', 3),
(19, 'App\\Models\\Utilisateur', 3),
(20, 'App\\Models\\Utilisateur', 3),
(21, 'App\\Models\\Utilisateur', 3),
(22, 'App\\Models\\Utilisateur', 3),
(23, 'App\\Models\\Utilisateur', 3),
(24, 'App\\Models\\Utilisateur', 3),
(49, 'App\\Models\\Utilisateur', 3),
(50, 'App\\Models\\Utilisateur', 3),
(51, 'App\\Models\\Utilisateur', 3),
(52, 'App\\Models\\Utilisateur', 3),
(53, 'App\\Models\\Utilisateur', 3),
(54, 'App\\Models\\Utilisateur', 3),
(57, 'App\\Models\\Utilisateur', 3),
(58, 'App\\Models\\Utilisateur', 3),
(59, 'App\\Models\\Utilisateur', 3),
(61, 'App\\Models\\Utilisateur', 3),
(62, 'App\\Models\\Utilisateur', 3),
(63, 'App\\Models\\Utilisateur', 3),
(64, 'App\\Models\\Utilisateur', 3),
(65, 'App\\Models\\Utilisateur', 3),
(1, 'App\\Models\\Utilisateur', 5),
(2, 'App\\Models\\Utilisateur', 5),
(3, 'App\\Models\\Utilisateur', 5),
(4, 'App\\Models\\Utilisateur', 5),
(5, 'App\\Models\\Utilisateur', 5),
(6, 'App\\Models\\Utilisateur', 5),
(7, 'App\\Models\\Utilisateur', 5),
(8, 'App\\Models\\Utilisateur', 5),
(21, 'App\\Models\\Utilisateur', 5),
(25, 'App\\Models\\Utilisateur', 5),
(26, 'App\\Models\\Utilisateur', 5),
(27, 'App\\Models\\Utilisateur', 5),
(28, 'App\\Models\\Utilisateur', 5),
(29, 'App\\Models\\Utilisateur', 5),
(30, 'App\\Models\\Utilisateur', 5),
(31, 'App\\Models\\Utilisateur', 5),
(32, 'App\\Models\\Utilisateur', 5),
(49, 'App\\Models\\Utilisateur', 5),
(50, 'App\\Models\\Utilisateur', 5),
(51, 'App\\Models\\Utilisateur', 5),
(52, 'App\\Models\\Utilisateur', 5),
(53, 'App\\Models\\Utilisateur', 5),
(54, 'App\\Models\\Utilisateur', 5),
(55, 'App\\Models\\Utilisateur', 5),
(56, 'App\\Models\\Utilisateur', 5),
(1, 'App\\Models\\Utilisateur', 6),
(2, 'App\\Models\\Utilisateur', 6),
(3, 'App\\Models\\Utilisateur', 6),
(4, 'App\\Models\\Utilisateur', 6),
(5, 'App\\Models\\Utilisateur', 6),
(6, 'App\\Models\\Utilisateur', 6),
(7, 'App\\Models\\Utilisateur', 6),
(8, 'App\\Models\\Utilisateur', 6),
(37, 'App\\Models\\Utilisateur', 6),
(38, 'App\\Models\\Utilisateur', 6),
(39, 'App\\Models\\Utilisateur', 6),
(40, 'App\\Models\\Utilisateur', 6),
(41, 'App\\Models\\Utilisateur', 6),
(42, 'App\\Models\\Utilisateur', 6),
(43, 'App\\Models\\Utilisateur', 6),
(44, 'App\\Models\\Utilisateur', 6),
(45, 'App\\Models\\Utilisateur', 6),
(46, 'App\\Models\\Utilisateur', 6),
(47, 'App\\Models\\Utilisateur', 6),
(48, 'App\\Models\\Utilisateur', 6),
(49, 'App\\Models\\Utilisateur', 6),
(50, 'App\\Models\\Utilisateur', 6),
(51, 'App\\Models\\Utilisateur', 6),
(52, 'App\\Models\\Utilisateur', 6),
(53, 'App\\Models\\Utilisateur', 6),
(54, 'App\\Models\\Utilisateur', 6),
(55, 'App\\Models\\Utilisateur', 6),
(56, 'App\\Models\\Utilisateur', 6),
(1, 'App\\Models\\Utilisateur', 15),
(2, 'App\\Models\\Utilisateur', 15),
(3, 'App\\Models\\Utilisateur', 15),
(5, 'App\\Models\\Utilisateur', 15),
(6, 'App\\Models\\Utilisateur', 15),
(7, 'App\\Models\\Utilisateur', 15),
(9, 'App\\Models\\Utilisateur', 15),
(10, 'App\\Models\\Utilisateur', 15),
(11, 'App\\Models\\Utilisateur', 15),
(13, 'App\\Models\\Utilisateur', 15),
(14, 'App\\Models\\Utilisateur', 15),
(15, 'App\\Models\\Utilisateur', 15),
(17, 'App\\Models\\Utilisateur', 15),
(18, 'App\\Models\\Utilisateur', 15),
(19, 'App\\Models\\Utilisateur', 15),
(21, 'App\\Models\\Utilisateur', 15),
(22, 'App\\Models\\Utilisateur', 15),
(23, 'App\\Models\\Utilisateur', 15),
(49, 'App\\Models\\Utilisateur', 15),
(50, 'App\\Models\\Utilisateur', 15),
(51, 'App\\Models\\Utilisateur', 15),
(53, 'App\\Models\\Utilisateur', 15),
(54, 'App\\Models\\Utilisateur', 15);

-- --------------------------------------------------------

--
-- Structure de la table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(2, 'App\\Models\\Utilisateur', 3),
(3, 'App\\Models\\Utilisateur', 4),
(2, 'App\\Models\\Utilisateur', 5),
(4, 'App\\Models\\Utilisateur', 6),
(15, 'App\\Models\\Utilisateur', 9),
(14, 'App\\Models\\Utilisateur', 10),
(16, 'App\\Models\\Utilisateur', 11),
(13, 'App\\Models\\Utilisateur', 12),
(13, 'App\\Models\\Utilisateur', 13),
(13, 'App\\Models\\Utilisateur', 14),
(2, 'App\\Models\\Utilisateur', 15);

-- --------------------------------------------------------

--
-- Structure de la table `mouvements_stock`
--

DROP TABLE IF EXISTS `mouvements_stock`;
CREATE TABLE IF NOT EXISTS `mouvements_stock` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `medicament_id` bigint(20) UNSIGNED NOT NULL,
  `pharmacie_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('entree','sortie','ajustement','vente','retour','perime') COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantite` int(11) NOT NULL,
  `stock_avant` int(11) NOT NULL,
  `stock_apres` int(11) NOT NULL,
  `prix_unitaire` decimal(10,2) DEFAULT NULL,
  `reference` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `motif` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mouvements_stock_medicament_id_index` (`medicament_id`),
  KEY `mouvements_stock_pharmacie_id_index` (`pharmacie_id`),
  KEY `mouvements_stock_type_index` (`type`),
  KEY `mouvements_stock_medicament_id_created_at_index` (`medicament_id`,`created_at`),
  KEY `mouvements_stock_pharmacie_id_created_at_index` (`pharmacie_id`,`created_at`),
  KEY `mouvements_stock_user_id_foreign` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `hopital_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pharmacie_id` bigint(20) UNSIGNED DEFAULT NULL,
  `banque_sang_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `data` json DEFAULT NULL,
  `read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_hopital_id_read_index` (`hopital_id`,`read`),
  KEY `notifications_user_id_read_index` (`user_id`,`read`),
  KEY `notifications_pharmacie_id_read_index` (`pharmacie_id`,`read`),
  KEY `notifications_banque_sang_id_read_index` (`banque_sang_id`,`read`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `hopital_id`, `pharmacie_id`, `banque_sang_id`, `type`, `title`, `message`, `data`, `read`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, 3, NULL, 'stock_faible', 'Stock Faible', 'Le médicament Paracétamol 500mg est en stock faible (5 unités restantes)', '{\"quantite\": 5, \"medicament\": \"Paracétamol 500mg\"}', 0, '2025-11-09 03:14:57', '2025-11-09 03:14:57'),
(2, 7, NULL, NULL, NULL, 'examen_a_realiser', 'Nouvel examen à réaliser', 'Examen payé : test de paludisme pour Ekumu', '\"{\\\"examen_id\\\":1}\"', 0, '2025-11-09 21:30:20', '2025-11-09 21:30:20'),
(3, 4, NULL, NULL, NULL, 'resultats_examen', 'Résultats d\'examen disponibles', 'Résultats de test de paludisme pour Ekumu sont disponibles.', '\"{\\\"examen_id\\\":1,\\\"dossier_id\\\":1}\"', 0, '2025-11-09 21:35:04', '2025-11-09 21:35:04'),
(4, 9, NULL, NULL, NULL, 'nouvelle_consultation', 'Nouvelle consultation à encaisser', 'Patient : jetima maria - Montant : 10000 FC', '\"{\\\"consultation_id\\\":1}\"', 0, '2025-11-11 15:22:13', '2025-11-11 15:22:13'),
(5, 4, NULL, NULL, NULL, 'consultation_payee', 'Consultation payée - Patient en attente', 'Le patient jetima maria a payé et vous attend.', '\"{\\\"consultation_id\\\":1}\"', 0, '2025-11-11 15:28:54', '2025-11-11 15:28:54'),
(6, 9, NULL, NULL, NULL, 'examens_a_payer', 'Examens à valider', 'Le Dr. joseph kabamba a prescrit 1 examen(s) pour jetima', '\"{\\\"dossier_id\\\":2,\\\"examens\\\":[2]}\"', 0, '2025-11-12 02:25:04', '2025-11-12 02:25:04'),
(7, 10, NULL, NULL, NULL, 'examen_a_realiser', 'Nouvel examen à réaliser', 'Examen payé pour jetima maria : echographie', '\"{\\\"examen_id\\\":2}\"', 0, '2025-11-12 02:25:59', '2025-11-12 02:25:59'),
(8, 4, NULL, NULL, NULL, 'resultats_examen', 'Résultats d\'examen disponibles', 'Résultats de echographie pour jetima sont disponibles.', '\"{\\\"examen_id\\\":2,\\\"dossier_id\\\":2}\"', 0, '2025-11-12 02:37:11', '2025-11-12 02:37:11'),
(9, 9, NULL, NULL, NULL, 'examens_a_payer', 'Examens à valider', 'Le Dr. joseph kabamba a prescrit 1 examen(s) pour jetima', '\"{\\\"dossier_id\\\":2,\\\"examens\\\":[3]}\"', 0, '2025-11-13 23:06:48', '2025-11-13 23:06:48'),
(10, 10, NULL, NULL, NULL, 'examen_a_realiser', 'Nouvel examen à réaliser', 'Examen payé pour jetima maria : scanner', '\"{\\\"examen_id\\\":3}\"', 0, '2025-11-13 23:15:33', '2025-11-13 23:15:33');

-- --------------------------------------------------------

--
-- Structure de la table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(125) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'view_users', 'web', '2025-11-07 12:21:06', '2025-11-07 12:21:06'),
(2, 'create_users', 'web', '2025-11-07 12:21:06', '2025-11-07 12:21:06'),
(3, 'edit_users', 'web', '2025-11-07 12:21:06', '2025-11-07 12:21:06'),
(4, 'delete_users', 'web', '2025-11-07 12:21:06', '2025-11-07 12:21:06'),
(5, 'view_roles', 'web', '2025-11-07 12:21:06', '2025-11-07 12:21:06'),
(6, 'create_roles', 'web', '2025-11-07 12:21:06', '2025-11-07 12:21:06'),
(7, 'edit_roles', 'web', '2025-11-07 12:21:07', '2025-11-07 12:21:07'),
(8, 'delete_roles', 'web', '2025-11-07 12:21:07', '2025-11-07 12:21:07'),
(9, 'view_patients', 'web', '2025-11-07 12:21:07', '2025-11-07 12:21:07'),
(10, 'create_patients', 'web', '2025-11-07 12:21:07', '2025-11-07 12:21:07'),
(11, 'edit_patients', 'web', '2025-11-07 12:21:07', '2025-11-07 12:21:07'),
(12, 'delete_patients', 'web', '2025-11-07 12:21:07', '2025-11-07 12:21:07'),
(13, 'view_medical_records', 'web', '2025-11-07 12:21:07', '2025-11-07 12:21:07'),
(14, 'create_medical_records', 'web', '2025-11-07 12:21:07', '2025-11-07 12:21:07'),
(15, 'edit_medical_records', 'web', '2025-11-07 12:21:07', '2025-11-07 12:21:07'),
(16, 'delete_medical_records', 'web', '2025-11-07 12:21:07', '2025-11-07 12:21:07'),
(17, 'view_appointments', 'web', '2025-11-07 12:21:07', '2025-11-07 12:21:07'),
(18, 'create_appointments', 'web', '2025-11-07 12:21:07', '2025-11-07 12:21:07'),
(19, 'edit_appointments', 'web', '2025-11-07 12:21:07', '2025-11-07 12:21:07'),
(20, 'delete_appointments', 'web', '2025-11-07 12:21:08', '2025-11-07 12:21:08'),
(21, 'view_prescriptions', 'web', '2025-11-07 12:21:08', '2025-11-07 12:21:08'),
(22, 'create_prescriptions', 'web', '2025-11-07 12:21:08', '2025-11-07 12:21:08'),
(23, 'edit_prescriptions', 'web', '2025-11-07 12:21:08', '2025-11-07 12:21:08'),
(24, 'delete_prescriptions', 'web', '2025-11-07 12:21:08', '2025-11-07 12:21:08'),
(25, 'view_medicines', 'web', '2025-11-07 12:24:52', '2025-11-07 12:24:52'),
(26, 'create_medicines', 'web', '2025-11-07 12:24:52', '2025-11-07 12:24:52'),
(27, 'edit_medicines', 'web', '2025-11-07 12:24:52', '2025-11-07 12:24:52'),
(28, 'delete_medicines', 'web', '2025-11-07 12:24:52', '2025-11-07 12:24:52'),
(29, 'view_stocks', 'web', '2025-11-07 12:24:52', '2025-11-07 12:24:52'),
(30, 'create_stocks', 'web', '2025-11-07 12:24:52', '2025-11-07 12:24:52'),
(31, 'edit_stocks', 'web', '2025-11-07 12:24:52', '2025-11-07 12:24:52'),
(32, 'delete_stocks', 'web', '2025-11-07 12:24:52', '2025-11-07 12:24:52'),
(33, 'view_orders', 'web', '2025-11-07 12:24:52', '2025-11-07 12:24:52'),
(34, 'create_orders', 'web', '2025-11-07 12:24:52', '2025-11-07 12:24:52'),
(35, 'edit_orders', 'web', '2025-11-07 12:24:53', '2025-11-07 12:24:53'),
(36, 'delete_orders', 'web', '2025-11-07 12:24:53', '2025-11-07 12:24:53'),
(37, 'view_donors', 'web', '2025-11-07 12:24:53', '2025-11-07 12:24:53'),
(38, 'create_donors', 'web', '2025-11-07 12:24:53', '2025-11-07 12:24:53'),
(39, 'edit_donors', 'web', '2025-11-07 12:24:53', '2025-11-07 12:24:53'),
(40, 'delete_donors', 'web', '2025-11-07 12:24:53', '2025-11-07 12:24:53'),
(41, 'view_blood_reserves', 'web', '2025-11-07 12:24:53', '2025-11-07 12:24:53'),
(42, 'create_blood_reserves', 'web', '2025-11-07 12:24:53', '2025-11-07 12:24:53'),
(43, 'edit_blood_reserves', 'web', '2025-11-07 12:24:53', '2025-11-07 12:24:53'),
(44, 'delete_blood_reserves', 'web', '2025-11-07 12:24:53', '2025-11-07 12:24:53'),
(45, 'view_blood_requests', 'web', '2025-11-07 12:24:53', '2025-11-07 12:24:53'),
(46, 'create_blood_requests', 'web', '2025-11-07 12:24:53', '2025-11-07 12:24:53'),
(47, 'edit_blood_requests', 'web', '2025-11-07 12:24:53', '2025-11-07 12:24:53'),
(48, 'delete_blood_requests', 'web', '2025-11-07 12:24:53', '2025-11-07 12:24:53'),
(49, 'view_invoices', 'web', '2025-11-07 12:24:53', '2025-11-07 12:24:53'),
(50, 'create_invoices', 'web', '2025-11-07 12:24:54', '2025-11-07 12:24:54'),
(51, 'edit_invoices', 'web', '2025-11-07 12:24:54', '2025-11-07 12:24:54'),
(52, 'delete_invoices', 'web', '2025-11-07 12:24:54', '2025-11-07 12:24:54'),
(53, 'view_reports', 'web', '2025-11-07 12:24:54', '2025-11-07 12:24:54'),
(54, 'create_reports', 'web', '2025-11-07 12:24:54', '2025-11-07 12:24:54'),
(55, 'edit_reports', 'web', '2025-11-07 12:24:54', '2025-11-07 12:24:54'),
(56, 'delete_reports', 'web', '2025-11-07 12:24:54', '2025-11-07 12:24:54'),
(57, 'view_exams', 'web', '2025-11-07 12:28:59', '2025-11-07 12:28:59'),
(58, 'create_exams', 'web', '2025-11-07 12:28:59', '2025-11-07 12:28:59'),
(59, 'edit_exams', 'web', '2025-11-07 12:28:59', '2025-11-07 12:28:59'),
(60, 'upload_exam_results', 'web', '2025-11-07 12:28:59', '2025-11-07 12:28:59'),
(61, 'delete_exams', 'web', '2025-11-07 12:52:38', '2025-11-07 12:52:38'),
(62, 'view_exam_results', 'web', '2025-11-07 12:52:38', '2025-11-07 12:52:38'),
(63, 'create_exam_results', 'web', '2025-11-07 12:52:38', '2025-11-07 12:52:38'),
(64, 'edit_exam_results', 'web', '2025-11-07 12:52:38', '2025-11-07 12:52:38'),
(65, 'delete_exam_results', 'web', '2025-11-07 12:52:38', '2025-11-07 12:52:38');

-- --------------------------------------------------------

--
-- Structure de la table `pharmacies`
--

DROP TABLE IF EXISTS `pharmacies`;
CREATE TABLE IF NOT EXISTS `pharmacies` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `pharmacies`
--

INSERT INTO `pharmacies` (`id`, `nom`, `adresse`, `telephone`, `email`, `logo`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Pharmacie Centrale', 'Kinshasa, RDC', '+243 111 222 333', 'contact@pharmaciecentrale.cd', NULL, NULL, '2025-11-07 12:38:36', '2025-11-07 12:38:36'),
(2, 'Pharmacie du Peuple', 'Goma, RDC', '+243 444 555 666', 'contact@pharmaciedupeuple.cd', NULL, NULL, '2025-11-07 12:38:36', '2025-11-07 12:38:36'),
(3, 'la bonté', 'Vovo 71, efobanck,n\'sele', NULL, 'labonte@gmail.com', NULL, NULL, '2025-11-09 02:45:18', '2025-11-09 02:45:18');

-- --------------------------------------------------------

--
-- Structure de la table `rendezvous`
--

DROP TABLE IF EXISTS `rendezvous`;
CREATE TABLE IF NOT EXISTS `rendezvous` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `medecin_id` bigint(20) UNSIGNED NOT NULL,
  `hopital_id` bigint(20) UNSIGNED NOT NULL,
  `date_rendezvous` date NOT NULL,
  `heure_rendezvous` time NOT NULL,
  `type_consultation` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `motif` text COLLATE utf8mb4_unicode_ci,
  `statut` enum('en_attente','confirme','annule','termine') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en_attente',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `prix` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `rendezvous`
--

INSERT INTO `rendezvous` (`id`, `patient_id`, `medecin_id`, `hopital_id`, `date_rendezvous`, `heure_rendezvous`, `type_consultation`, `motif`, `statut`, `notes`, `prix`, `created_at`, `updated_at`) VALUES
(1, 8, 4, 3, '2025-11-15', '09:00:00', 'suivi', 'suivi de retablissement', 'confirme', NULL, '0.00', '2025-11-09 21:51:10', '2025-11-12 02:51:11'),
(2, 14, 4, 3, '2025-11-13', '09:00:00', 'urgence', 'appendocectomie', 'confirme', NULL, '0.00', '2025-11-12 02:51:54', '2025-11-12 02:52:01');

-- --------------------------------------------------------

--
-- Structure de la table `reserves_sang`
--

DROP TABLE IF EXISTS `reserves_sang`;
CREATE TABLE IF NOT EXISTS `reserves_sang` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `banque_sang_id` bigint(20) UNSIGNED NOT NULL,
  `groupe_sanguin` enum('A+','A-','B+','B-','AB+','AB-','O+','O-') COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantite_disponible` decimal(8,2) NOT NULL DEFAULT '0.00',
  `quantite_minimum` decimal(8,2) NOT NULL DEFAULT '5.00',
  `quantite_critique` decimal(8,2) NOT NULL DEFAULT '2.00',
  `nombre_poches` int(11) NOT NULL DEFAULT '0',
  `derniere_mise_a_jour` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reserves_sang_banque_sang_id_groupe_sanguin_unique` (`banque_sang_id`,`groupe_sanguin`),
  KEY `reserves_sang_banque_sang_id_index` (`banque_sang_id`),
  KEY `reserves_sang_groupe_sanguin_index` (`groupe_sanguin`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `reserves_sang`
--

INSERT INTO `reserves_sang` (`id`, `banque_sang_id`, `groupe_sanguin`, `quantite_disponible`, `quantite_minimum`, `quantite_critique`, `nombre_poches`, `derniere_mise_a_jour`, `created_at`, `updated_at`) VALUES
(1, 3, 'A+', '0.00', '5.00', '2.00', 0, NULL, '2025-11-09 07:48:39', '2025-11-09 07:48:39'),
(2, 3, 'A-', '0.00', '5.00', '2.00', 0, NULL, '2025-11-09 07:48:39', '2025-11-09 07:48:39'),
(3, 3, 'B+', '0.00', '5.00', '2.00', 0, NULL, '2025-11-09 07:48:39', '2025-11-09 07:48:39'),
(4, 3, 'B-', '0.00', '5.00', '2.00', 0, NULL, '2025-11-09 07:48:39', '2025-11-09 07:48:39'),
(5, 3, 'AB+', '0.00', '5.00', '2.00', 0, NULL, '2025-11-09 07:48:39', '2025-11-09 07:48:39'),
(6, 3, 'AB-', '0.00', '5.00', '2.00', 0, NULL, '2025-11-09 07:48:39', '2025-11-09 07:48:39'),
(7, 3, 'O+', '0.00', '5.00', '2.00', 0, NULL, '2025-11-09 07:48:39', '2025-11-09 07:48:39'),
(8, 3, 'O-', '0.00', '5.00', '2.00', 0, NULL, '2025-11-09 07:48:39', '2025-11-09 07:48:39');

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'superadmin', 'web', '2025-11-07 12:21:08', '2025-11-07 12:21:08'),
(2, 'admin', 'web', '2025-11-07 12:21:08', '2025-11-07 12:21:08'),
(3, 'medecin', 'web', '2025-11-07 12:21:08', '2025-11-07 12:21:08'),
(4, 'user', 'web', '2025-11-07 12:21:08', '2025-11-07 12:21:08'),
(5, 'hopital_admin', 'web', '2025-11-07 12:24:54', '2025-11-07 12:24:54'),
(6, 'infirmier', 'web', '2025-11-07 12:24:55', '2025-11-07 12:24:55'),
(7, 'pharmacie_admin', 'web', '2025-11-07 12:24:55', '2025-11-07 12:24:55'),
(8, 'pharmacien', 'web', '2025-11-07 12:24:55', '2025-11-07 12:24:55'),
(9, 'assistant_pharmacie', 'web', '2025-11-07 12:24:55', '2025-11-07 12:24:55'),
(10, 'banque_sang_admin', 'web', '2025-11-07 12:24:55', '2025-11-07 12:24:55'),
(11, 'technicien_labo', 'web', '2025-11-07 12:24:55', '2025-11-07 12:24:55'),
(12, 'gestionnaire_donneurs', 'web', '2025-11-07 12:24:55', '2025-11-07 12:24:55'),
(13, 'patient', 'web', '2025-11-07 12:24:55', '2025-11-07 12:24:55'),
(14, 'laborantin', 'web', '2025-11-07 12:27:06', '2025-11-07 12:27:06'),
(15, 'caissier', 'web', '2025-11-07 12:27:06', '2025-11-07 12:27:06'),
(16, 'receptionniste', 'web', '2025-11-07 12:27:06', '2025-11-07 12:27:06');

-- --------------------------------------------------------

--
-- Structure de la table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1),
(33, 1),
(34, 1),
(35, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1),
(41, 1),
(42, 1),
(43, 1),
(44, 1),
(45, 1),
(46, 1),
(47, 1),
(48, 1),
(49, 1),
(50, 1),
(51, 1),
(52, 1),
(53, 1),
(54, 1),
(55, 1),
(56, 1),
(57, 1),
(58, 1),
(59, 1),
(60, 1),
(61, 1),
(62, 1),
(63, 1),
(64, 1),
(65, 1),
(1, 2),
(2, 2),
(3, 2),
(9, 2),
(10, 2),
(11, 2),
(13, 2),
(14, 2),
(15, 2),
(17, 2),
(18, 2),
(19, 2),
(21, 2),
(22, 2),
(23, 2),
(9, 3),
(13, 3),
(14, 3),
(15, 3),
(17, 3),
(18, 3),
(19, 3),
(21, 3),
(22, 3),
(23, 3),
(17, 4),
(1, 5),
(2, 5),
(3, 5),
(9, 5),
(10, 5),
(11, 5),
(13, 5),
(14, 5),
(15, 5),
(17, 5),
(18, 5),
(19, 5),
(21, 5),
(22, 5),
(23, 5),
(49, 5),
(50, 5),
(51, 5),
(53, 5),
(54, 5),
(9, 6),
(13, 6),
(17, 6),
(21, 6),
(1, 7),
(2, 7),
(3, 7),
(21, 7),
(25, 7),
(26, 7),
(27, 7),
(28, 7),
(29, 7),
(30, 7),
(31, 7),
(33, 7),
(34, 7),
(35, 7),
(49, 7),
(50, 7),
(51, 7),
(53, 7),
(54, 7),
(21, 8),
(25, 8),
(26, 8),
(27, 8),
(29, 8),
(31, 8),
(33, 8),
(34, 8),
(35, 8),
(21, 9),
(25, 9),
(29, 9),
(33, 9),
(1, 10),
(2, 10),
(3, 10),
(37, 10),
(38, 10),
(39, 10),
(40, 10),
(41, 10),
(42, 10),
(43, 10),
(44, 10),
(45, 10),
(46, 10),
(47, 10),
(49, 10),
(50, 10),
(51, 10),
(53, 10),
(54, 10),
(37, 11),
(41, 11),
(42, 11),
(43, 11),
(45, 11),
(37, 12),
(38, 12),
(39, 12),
(41, 12),
(45, 12),
(46, 12),
(13, 13),
(17, 13),
(21, 13),
(9, 14),
(13, 14),
(21, 14),
(9, 15),
(17, 15),
(49, 15),
(50, 15),
(51, 15),
(9, 16),
(10, 16),
(11, 16),
(17, 16),
(18, 16),
(19, 16);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_utilisateur` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entite_id` bigint(20) UNSIGNED DEFAULT NULL,
  `hopital_id` bigint(20) UNSIGNED DEFAULT NULL,
  `groupe_sanguin` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_naissance` date DEFAULT NULL,
  `sexe` enum('masculin','feminin') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse` text COLLATE utf8mb4_unicode_ci,
  `mot_de_passe` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `status` enum('pending','approved','rejected','actif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `utilisateurs_email_unique` (`email`),
  KEY `utilisateurs_hopital_id_foreign` (`hopital_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `prenom`, `email`, `type_utilisateur`, `entite_id`, `hopital_id`, `groupe_sanguin`, `telephone`, `date_naissance`, `sexe`, `adresse`, `mot_de_passe`, `role`, `status`, `rejection_reason`, `created_at`, `updated_at`) VALUES
(1, 'Admin', NULL, 'admin@central.com', 'hopital', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$12$IRW1GpvhNycbaaVZv1OsG.4LzaP47zx.iR1qoBGyV5qP9cU6qg0lW', 'superadmin', 'approved', NULL, '2025-11-07 12:05:31', '2025-11-07 12:05:31'),
(3, 'saint-joseph', NULL, 'saintjoseph@gmail.com', 'hopital', 3, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$12$TtK6DlzguG64RhoE3Un8GOSSbvexJ7fco0oYtHPhCk7EPQVKdi9rq', 'admin', 'approved', NULL, '2025-11-07 12:51:31', '2025-11-07 12:52:38'),
(4, 'joseph kabamba', NULL, 'joseph@gmail.com', 'hopital', 3, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$12$QHrEQcsTKk3yZMlILeoDjOWIVePZCgwexZBTyw0QAaefcIloezWjG', 'medecin', 'approved', NULL, '2025-11-07 13:05:22', '2025-11-07 13:05:22'),
(5, 'la bonté', NULL, 'labonte@gmail.com', 'pharmacie', 3, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$12$iicUxiSJBKPJGBOykt734u2XdrIrzRrjQX.nwIgeLolVb7Xszr0Li', 'admin', 'approved', NULL, '2025-11-09 02:45:20', '2025-11-09 02:47:17'),
(6, 'santé vie', NULL, 'santevie@gmail.com', 'banque_sang', 3, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$12$5pO0Iq1EucyMDOfJvvrL2OTolBMWxKjNd3PHiNyGlKJAB8zhJrV0i', 'user', 'approved', NULL, '2025-11-09 03:19:33', '2025-11-09 03:22:09'),
(8, 'Ekumu', NULL, 'lauclass@gmail.com', 'patient', 3, NULL, NULL, '+243976935450', '2005-06-09', 'feminin', 'Vovo 71, efobanck,n\'sele', '$2y$12$dAyJ8cfLlk7TO6hwJScQDuVvtEHf9rnTARnVSq8JgALW2xiHsMXgS', 'patient', 'approved', NULL, '2025-11-09 21:07:03', '2025-11-09 21:07:03'),
(9, 'arsene siviwe', NULL, 'arsene@gmail.com', 'hopital', 3, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$12$wVSVOLnJ8conYCsbzzqOKe.pMxgxaLuSOJ4kolTfVtPwtSmvqQC4O', 'caissier', 'approved', NULL, '2025-11-09 21:29:31', '2025-11-09 21:29:31'),
(10, 'exaucee lumeya', NULL, 'exaucee@gmail.com', 'hopital', 3, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$12$PEaM13ZDU76Mnl2o0IvgheB9r6TnGQHzSwDEDyo1rlrT0xtVbpmNm', 'laborantin', 'approved', NULL, '2025-11-09 21:33:55', '2025-11-09 21:33:55'),
(11, 'dieumerci', NULL, 'dieumerci@gmail.com', 'hopital', 3, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$12$w0YsjR4tiM5Ga0awGId8kupMcATp58G5MauXlUYLgAvUEyW6YwELS', 'receptionniste', 'approved', NULL, '2025-11-10 12:22:26', '2025-11-10 12:22:26'),
(12, 'jossie massamba', NULL, 'jossie@gmail.com', 'patient', NULL, 3, NULL, NULL, '2007-04-10', 'feminin', NULL, '$2y$12$haRm4N9zL.HHdbCLJSJ/4OX38zjLK4Cu3WZYS29rQ5ieYbkRN5tfq', 'patient', 'approved', NULL, '2025-11-10 13:23:44', '2025-11-10 13:41:58'),
(13, 'kowa', 'joseph', 'kowa@gmail.com', 'patient', NULL, 3, 'A+', '0976935450', '2000-01-20', 'masculin', 'Vovo 71, efobanck,n\'sele', '$2y$12$GtDHUpf1AMZyV6.2beGga.iIQ3ok9/6jFL.VZG8NZGVCDWHqpAyaG', 'patient', 'approved', NULL, '2025-11-11 14:45:55', '2025-11-11 14:45:55'),
(14, 'jetima', 'maria', 'jetima@gmail.com', 'patient', NULL, 3, 'A+', '0976935450', '2005-10-10', 'feminin', 'Vovo 71, efobanck,n\'sele', '$2y$12$PPNO3/SQrF6my5F/rBOWueI53iUM9cpnudkOA0h9gc9zNgu17PtTS', 'patient', 'approved', NULL, '2025-11-11 15:22:13', '2025-11-11 15:22:13'),
(15, 'clinique universitaire', NULL, 'clinique@gmail.com', 'hopital', 4, NULL, NULL, NULL, NULL, NULL, NULL, '$2y$12$nYrpinGpc8cbcP9wX0XIgu1YSiBTVMw31NuVAJwLNDki7qvhhT5hW', 'admin', 'approved', NULL, '2025-11-13 10:20:38', '2025-11-13 10:23:10');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD CONSTRAINT `commandes_fournisseur_id_foreign` FOREIGN KEY (`fournisseur_id`) REFERENCES `fournisseurs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `commandes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `consultations`
--
ALTER TABLE `consultations`
  ADD CONSTRAINT `consultations_caissier_id_foreign` FOREIGN KEY (`caissier_id`) REFERENCES `utilisateurs` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `consultations_dossier_medical_id_foreign` FOREIGN KEY (`dossier_medical_id`) REFERENCES `dossier_medicals` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `consultations_hopital_id_foreign` FOREIGN KEY (`hopital_id`) REFERENCES `hopitaux` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `consultations_medecin_id_foreign` FOREIGN KEY (`medecin_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `consultations_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `consultations_receptionniste_id_foreign` FOREIGN KEY (`receptionniste_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `demandes_transfert_dossier`
--
ALTER TABLE `demandes_transfert_dossier`
  ADD CONSTRAINT `demandes_transfert_dossier_dossier_medical_id_foreign` FOREIGN KEY (`dossier_medical_id`) REFERENCES `dossier_medicals` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `demandes_transfert_dossier_hopital_demandeur_id_foreign` FOREIGN KEY (`hopital_demandeur_id`) REFERENCES `hopitaux` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `demandes_transfert_dossier_hopital_detenteur_id_foreign` FOREIGN KEY (`hopital_detenteur_id`) REFERENCES `hopitaux` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `demandes_transfert_dossier_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `dons`
--
ALTER TABLE `dons`
  ADD CONSTRAINT `dons_donneur_id_foreign` FOREIGN KEY (`donneur_id`) REFERENCES `donneurs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dons_technicien_id_foreign` FOREIGN KEY (`technicien_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `lignes_commande`
--
ALTER TABLE `lignes_commande`
  ADD CONSTRAINT `lignes_commande_commande_id_foreign` FOREIGN KEY (`commande_id`) REFERENCES `commandes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lignes_commande_medicament_id_foreign` FOREIGN KEY (`medicament_id`) REFERENCES `medicaments` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `mouvements_stock`
--
ALTER TABLE `mouvements_stock`
  ADD CONSTRAINT `mouvements_stock_medicament_id_foreign` FOREIGN KEY (`medicament_id`) REFERENCES `medicaments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mouvements_stock_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD CONSTRAINT `utilisateurs_hopital_id_foreign` FOREIGN KEY (`hopital_id`) REFERENCES `hopitaux` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
