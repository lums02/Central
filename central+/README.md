# CENTRAL+ - Plateforme de Gestion Médicale Intégrée

## 📋 TABLE DES MATIÈRES

1. [Vue d'ensemble](#vue-densemble)
2. [Architecture du système](#architecture-du-système)
3. [Entités et Rôles](#entités-et-rôles)
4. [Fonctionnalités par Rôle](#fonctionnalités-par-rôle)
5. [Workflow des Examens Médicaux](#workflow-des-examens-médicaux)
6. [Système de Notifications](#système-de-notifications)
7. [Isolation des Données](#isolation-des-données)
8. [Installation et Configuration](#installation-et-configuration)

---

## 🎯 VUE D'ENSEMBLE

**CENTRAL+** est une plateforme complète de gestion pour les établissements de santé en RDC, incluant :
- 🏥 **Hôpitaux** - Gestion des patients, dossiers médicaux, rendez-vous
- 💊 **Pharmacies** - Gestion des médicaments, stocks, commandes
- 🩸 **Banques de Sang** - Gestion des donneurs, réserves, demandes
- 👤 **Patients** - Accès à leurs dossiers médicaux

---

## 🏗️ ARCHITECTURE DU SYSTÈME

### Technologies Utilisées
- **Backend** : Laravel 12.17.0
- **Frontend** : Blade Templates, Bootstrap 5, Font Awesome
- **Base de données** : MySQL
- **Authentification** : Laravel Auth + Spatie Permissions
- **Notifications** : Système temps réel avec actualisation auto

### Structure des Dossiers
```
central+/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/
│   │   │   ├── DashboardController.php
│   │   │   ├── UserController.php
│   │   │   ├── PermissionController.php
│   │   │   ├── HopitalPatientController.php
│   │   │   ├── HopitalRendezVousController.php
│   │   │   ├── TransfertDossierController.php
│   │   │   └── NotificationController.php
│   │   ├── Auth/
│   │   │   ├── LoginController.php
│   │   │   └── RegisterController.php
│   │   ├── MedecinController.php
│   │   ├── CaissierController.php
│   │   └── LaborantinController.php
│   └── Models/
│       ├── Utilisateur.php
│       ├── Hopital.php
│       ├── Pharmacie.php
│       ├── BanqueSang.php
│       ├── DossierMedical.php
│       ├── RendezVous.php
│       ├── ExamenPrescrit.php
│       ├── DemandeTransfertDossier.php
│       └── Notification.php
├── resources/views/
│   ├── admin/
│   ├── medecin/
│   ├── caissier/
│   ├── laborantin/
│   └── patient/
└── database/migrations/
```

---

## 👥 ENTITÉS ET RÔLES

### 1. SUPERADMIN
**Accès** : Plateforme complète
**Permissions** :
- Créer les administrateurs de chaque entité
- Voir toutes les entités
- Gérer les rôles et permissions
- Statistiques globales

### 2. HÔPITAL (6 rôles)

#### **Admin Hôpital**
- Gestion complète de l'hôpital
- Création du personnel
- Gestion des patients
- Gestion des rendez-vous
- Demandes de transfert de dossiers

#### **Médecin**
- Consultation des patients
- Création et modification de dossiers médicaux
- Prescription d'examens
- Gestion des rendez-vous
- Ajout de consultations au dossier

#### **Infirmier**
- Consultation des patients
- Lecture des dossiers médicaux
- Consultation des rendez-vous

#### **Laborantin**
- Réception des examens prescrits (après paiement)
- Réalisation des examens
- Upload des résultats (texte + fichier PDF/image)
- Notification au médecin

#### **Caissier**
- Réception des prescriptions d'examens
- Fixation des prix
- Validation des paiements
- Notification au laborantin

#### **Réceptionniste**
- Création de patients
- Gestion des rendez-vous

### 3. PHARMACIE (3 rôles)

#### **Admin Pharmacie**
- Gestion complète de la pharmacie
- Création du personnel
- Gestion des médicaments
- Gestion des stocks
- Gestion des commandes

#### **Pharmacien**
- Gestion des médicaments
- Gestion des stocks
- Traitement des commandes

#### **Assistant Pharmacie**
- Consultation des médicaments
- Consultation des stocks

### 4. BANQUE DE SANG (3 rôles)

#### **Admin Banque de Sang**
- Gestion complète de la banque
- Création du personnel
- Gestion des donneurs
- Gestion des réserves
- Gestion des demandes

#### **Technicien Laboratoire**
- Gestion des réserves de sang
- Analyses

#### **Gestionnaire Donneurs**
- Gestion des donneurs
- Traitement des demandes de sang

### 5. PATIENT
- Consultation de ses dossiers médicaux
- Consultation de ses rendez-vous
- Gestion des consentements de transfert

---

## 🔧 FONCTIONNALITÉS PAR RÔLE

### MÉDECIN - Espace Complet

#### Dashboard
- Statistiques : Total patients, dossiers, consultations
- Liste des patients récents
- Liste des dossiers récents
- Notifications en temps réel

#### Gestion des Patients
- Liste de tous les patients de l'hôpital
- Recherche et filtres
- Accès aux dossiers médicaux

#### Dossiers Médicaux
**Création d'un dossier** (formulaire complet) :
1. **Informations Patient**
   - Sélection du patient
   - Date de consultation

2. **Consultation**
   - Motif de consultation
   - Symptômes présentés
   - Examen clinique (signes vitaux)

3. **Diagnostic & Traitement**
   - Diagnostic principal
   - Code CIM-10
   - Diagnostics secondaires
   - Traitement prescrit
   - Plan de traitement

4. **Notes & Observations**
   - Recommandations
   - Observations médicales
   - Prochain rendez-vous
   - Niveau d'urgence

**Actions sur un dossier** :
- ✅ Prescrire des examens
- ✅ Ajouter une consultation
- ✅ Modifier le dossier
- ✅ Voir l'historique complet

#### Prescription d'Examens
- Formulaire multi-examens
- Types : Biologique, Imagerie, Fonctionnel
- Indication obligatoire
- Prix fixé par le caissier
- Notification automatique au caissier

#### Rendez-vous
- Création de rendez-vous
- Gestion des statuts
- Notifications

---

### CAISSIER - Gestion des Paiements

#### Page Examens en Attente
**Affichage** :
- N° Examen
- Patient
- Médecin prescripteur
- Type et nom de l'examen
- Prix (à définir)
- Date de prescription

**Actions** :
- Bouton "Fixer Prix & Valider"
- Modal pour entrer le prix
- Choix du mode de paiement (Espèces, Carte, Mobile Money)
- Validation → Notification au laborantin

---

### LABORANTIN - Gestion des Examens

#### Page Examens à Réaliser
**Affichage** :
- N° Examen
- Patient
- Type et nom de l'examen
- Indication
- Date
- Statut (En attente, En cours, Terminé)

**Actions** :
- Bouton "Commencer" → Marque l'examen en cours
- Bouton "Résultats" → Modal pour uploader
  - Résultats (texte)
  - Interprétation
  - Fichier (PDF, JPG, PNG)
- Upload → Notification au médecin

---

## 🔄 WORKFLOW DES EXAMENS MÉDICAUX

### Étape 1 : Prescription (MÉDECIN)
```
Médecin ouvre dossier patient
   ↓
Clique "Prescrire des Examens"
   ↓
Remplit formulaire :
   - Type : Analyse Biologique
   - Nom : NFS (Numération Formule Sanguine)
   - Indication : Suspicion d'anémie
   ↓
Peut ajouter plusieurs examens
   ↓
Envoie la prescription
   ↓
Examen créé avec statut "prescrit" et prix = $0
```

### Étape 2 : Paiement (CAISSIER)
```
Caissier reçoit notification 🔔
   ↓
Va sur /admin/caissier/examens
   ↓
Voit examen avec badge "À définir"
   ↓
Clique "Fixer Prix & Valider"
   ↓
Entre prix : $20.00
   ↓
Choisit mode : Espèces
   ↓
Valide
   ↓
Examen mis à jour :
   - prix = $20.00
   - statut_paiement = "paye"
   - statut_examen = "paye"
   - date_paiement = maintenant
   - valide_par = ID caissier
```

### Étape 3 : Réalisation (LABORANTIN)
```
Laborantin reçoit notification 🔔
   ↓
Va sur /admin/laborantin/examens
   ↓
Voit examen avec statut "En attente"
   ↓
Clique "Commencer"
   ↓
Statut → "En cours"
   ↓
Fait l'examen au laboratoire
   ↓
Clique "Résultats"
   ↓
Remplit :
   - Résultats : "Hémoglobine: 12g/dL, Leucocytes: 7000/mm³"
   - Interprétation : "Valeurs normales"
   - Upload fichier PDF
   ↓
Envoie
   ↓
Examen mis à jour :
   - statut_examen = "termine"
   - date_realisation = maintenant
   - laborantin_id = ID laborantin
   - resultats = texte
   - fichier_resultat = chemin PDF
```

### Étape 4 : Consultation Résultats (MÉDECIN)
```
Médecin reçoit notification 🔔
   "Résultats de NFS pour Jean Dupont disponibles"
   ↓
Clique sur notification
   ↓
Voit les résultats dans le dossier
   ↓
Peut ajuster le traitement
   ↓
Ajoute une consultation de suivi
```

---

## 🔔 SYSTÈME DE NOTIFICATIONS

### Types de Notifications

#### Pour MÉDECIN :
- `dossier_assigne` - Dossier assigné par l'admin
- `resultats_examen` - Résultats d'examen disponibles
- `rendez_vous` - Rappels de rendez-vous

#### Pour CAISSIER :
- `examens_a_payer` - Examens prescrits en attente de validation

#### Pour LABORANTIN :
- `examen_a_realiser` - Examen payé à réaliser

#### Pour ADMIN HÔPITAL :
- `demande_transfert_recue` - Demande de transfert de dossier
- `transfert_complete` - Dossier transféré

### Fonctionnement
- Cloche dans le topbar avec badge rouge
- Actualisation automatique toutes les 30 secondes
- Animation de balancement si notifications non lues
- Clic sur notification → Redirection vers la page concernée
- Marquage automatique comme lu

---

## 🔒 ISOLATION DES DONNÉES

### Principe
Chaque entité est **complètement isolée** et ne voit QUE ses propres données.

### Implémentation

#### Scopes dans les Modèles
```php
// Utilisateur.php
public function scopeOfSameEntity($query)
{
    $user = auth()->user();
    if ($user->isSuperAdmin()) return $query;
    return $query->where('entite_id', $user->entite_id)
                 ->where('type_utilisateur', $user->type_utilisateur);
}

// DossierMedical.php
public function scopeOfSameHospital($query)
{
    $user = auth()->user();
    if ($user->isSuperAdmin()) return $query;
    return $query->where('hopital_id', $user->entite_id);
}
```

#### Contrôleurs
Tous les contrôleurs filtrent par `entite_id` sauf pour le superadmin.

### Exemples d'Isolation

**Hôpital Saint-Joseph** voit :
- ✅ Ses 50 patients
- ✅ Ses 5 médecins
- ✅ Ses 200 dossiers médicaux
- ❌ NE VOIT PAS Hôpital Général
- ❌ NE VOIT PAS les pharmacies
- ❌ NE VOIT PAS les banques de sang

**Pharmacie Centrale** voit :
- ✅ Ses médicaments
- ✅ Ses stocks
- ✅ Son personnel
- ❌ NE VOIT PAS Pharmacie du Peuple
- ❌ NE VOIT PAS les hôpitaux

---

## 🔐 SYSTÈME DE CRÉATION D'UTILISATEURS

### SUPERADMIN
**Peut créer** : Administrateurs d'entités uniquement

**Formulaire** :
1. Type d'entité (Hôpital, Pharmacie, Banque de Sang)
2. Sélection de l'entité (dropdown dynamique)
3. Rôle = "admin" (fixe)

**Exemple** :
- Type : Hôpital
- Entité : Hôpital Saint-Joseph
- Rôle : Administrateur
- → Crée l'admin de Saint-Joseph

### ADMIN D'ENTITÉ
**Peut créer** : Son personnel uniquement

**Formulaire pour Admin Hôpital** :
- Médecin
- Infirmier
- Laborantin
- Caissier
- Réceptionniste

**Formulaire pour Admin Pharmacie** :
- Pharmacien
- Assistant Pharmacie

**Formulaire pour Admin Banque** :
- Technicien Laboratoire
- Gestionnaire Donneurs

---

## 📊 DOSSIER MÉDICAL COMPLET

### Structure du Dossier

#### 1. Informations Administratives
- Numéro de dossier (auto-généré : DM-YYYYMMDD-00001)
- Patient
- Médecin
- Hôpital
- Date de consultation
- Statut (actif, archivé)

#### 2. Consultation
- Motif de consultation
- Symptômes présentés
- Examen clinique (signes vitaux, observations)

#### 3. Diagnostic
- Diagnostic principal
- Code CIM-10 (Classification Internationale)
- Diagnostics secondaires

#### 4. Traitement
- Traitement prescrit (médicaments, dosages)
- Plan de traitement à long terme
- Recommandations

#### 5. Suivi
- Observations médicales
- Prochain rendez-vous
- Niveau d'urgence (Normale, Urgente, Très Urgente)

#### 6. Historique
Chaque consultation ajoutée est stockée dans les observations avec format :
```
=== CONSULTATION DU 07/11/2025 ===
Type: Consultation de Suivi
Motif: Contrôle post-traitement
Symptômes: Amélioration notable
Examen clinique: TA 120/80, Temp 37°C
Diagnostic/Évolution: Guérison en cours
Traitement: Continuer antibiotiques
Notes: Patient répondant bien au traitement
Urgence: normale
```

---

## 🔬 WORKFLOW EXAMENS MÉDICAUX DÉTAILLÉ

### Table : examens_prescrits

**Champs** :
- `id`, `numero_examen` (unique)
- `dossier_medical_id`, `patient_id`, `medecin_id`, `hopital_id`
- `laborantin_id`, `valide_par` (caissier)
- `type_examen`, `nom_examen`, `indication`
- `date_prescription`, `date_realisation`, `date_paiement`
- `prix`, `statut_paiement`, `statut_examen`
- `resultats`, `interpretation`, `fichier_resultat`

### Statuts

**statut_paiement** :
- `en_attente` - En attente de validation caissier
- `paye` - Payé et validé
- `annule` - Annulé

**statut_examen** :
- `prescrit` - Prescrit par le médecin
- `paye` - Payé, en attente du laborantin
- `en_cours` - En cours de réalisation
- `termine` - Résultats disponibles

### Routes

**Médecin** :
- `POST /admin/medecin/dossiers/{id}/prescrire-examens`

**Caissier** :
- `GET /admin/caissier/examens` - Liste
- `POST /admin/caissier/examens/{id}/valider-paiement` - Valider

**Laborantin** :
- `GET /admin/laborantin/examens` - Liste
- `POST /admin/laborantin/examens/{id}/marquer-en-cours` - Commencer
- `POST /admin/laborantin/examens/{id}/uploader-resultats` - Upload

---

## 🔄 TRANSFERT INTER-HOSPITALIER DE DOSSIERS

### Workflow

```
HÔPITAL B (demandeur)
   ↓ Recherche patient d'un autre hôpital
   ↓ Clique "Demander un Dossier Externe"
   ↓ Remplit motif de la demande
   ↓ Envoie
   
   ↓ 🔔 Notification

HÔPITAL A (détenteur)
   ↓ Reçoit notification
   ↓ Va sur "Demandes Reçues"
   ↓ Voit statut "En attente du patient"
   
   ↓ Attente consentement

PATIENT
   ↓ Reçoit demande de consentement
   ↓ Accepte ou refuse
   
   ↓ Si accepté

HÔPITAL A
   ↓ Voit "Accepté par le patient"
   ↓ Clique "Transférer"
   ↓ Dossier copié vers Hôpital B
   
   ↓ 🔔 Notification

HÔPITAL B
   ↓ Reçoit notification "Dossier transféré"
   ↓ Peut maintenant consulter le dossier
```

### Table : demandes_transfert_dossier

**Statuts** :
- `en_attente_patient` - En attente du consentement
- `accepte_patient` - Patient a accepté
- `refuse_patient` - Patient a refusé
- `transfere` - Dossier transféré
- `refuse_hopital` - Hôpital a refusé
- `annule` - Demande annulée

---

## 🎨 DESIGN ET UX

### Couleurs CENTRAL+
- **Primary** : `#003366` (Bleu foncé)
- **Secondary** : `#ff6b35` (Orange)
- **Success** : `#28a745` (Vert)
- **Info** : `#17a2b8` (Bleu clair)

### Layouts

#### Espace Médecin
- Sidebar bleu avec nom de l'hôpital
- Topbar blanc avec message de bienvenue
- Cloche de notifications
- Cartes de statistiques avec icônes colorées
- Design sobre et professionnel

#### Espace Admin
- Sidebar avec navigation dynamique
- Topbar avec notifications
- Cartes et tableaux modernes
- Modals pour les actions

---

## 📱 RESPONSIVE

- Desktop : Sidebar fixe
- Tablet : Sidebar adaptative
- Mobile : Sidebar cachée avec bouton hamburger

---

## 🚀 INSTALLATION ET CONFIGURATION

### Prérequis
- PHP 8.2+
- MySQL 5.7+
- Composer
- Node.js & NPM

### Installation

```bash
# Cloner le projet
cd C:\wamp64\www\Central\central+

# Installer les dépendances
composer install
npm install

# Configuration
cp .env.example .env
php artisan key:generate

# Base de données
php artisan migrate

# Créer les rôles et permissions
php artisan db:seed --class=CompleteRolesPermissionsSeeder

# Créer les entités de test
php artisan db:seed --class=EntitiesSeeder

# Créer le superadmin
php artisan db:seed --class=SuperAdminSeeder

# Lancer le serveur
php artisan serve
```

### Compte par Défaut

**Superadmin** :
- Email : `admin@central.com`
- Password : `password`

### Entités Créées

**Hôpitaux** :
- Hôpital Saint-Joseph
- Hôpital Général de Référence

**Pharmacies** :
- Pharmacie Centrale
- Pharmacie du Peuple

**Banques de Sang** :
- Banque de Sang Nationale
- Centre de Transfusion Sanguine

---

## 📊 BASE DE DONNÉES

### Tables Principales

1. **utilisateurs** - Tous les utilisateurs
2. **hopitaux** - Hôpitaux
3. **pharmacies** - Pharmacies
4. **banque_sangs** - Banques de sang
5. **dossier_medicals** - Dossiers médicaux
6. **rendezvous** - Rendez-vous
7. **examens_prescrits** - Examens médicaux
8. **demandes_transfert_dossier** - Transferts inter-hospitaliers
9. **notifications** - Notifications
10. **roles** - Rôles (Spatie)
11. **permissions** - Permissions (Spatie)

---

## 🔐 SÉCURITÉ

### Authentification
- Middleware `auth` sur toutes les routes admin
- Vérification des rôles et permissions
- Protection CSRF sur tous les formulaires

### Isolation des Données
- Filtrage par `entite_id` dans tous les contrôleurs
- Scopes dans les modèles
- Middleware `CheckEntityAccess`

### Validation
- Validation côté serveur (Laravel)
- Validation côté client (JavaScript)
- Email format strict (RFC, DNS)
- Mot de passe fort (8 chars, majuscule, minuscule, chiffre, caractère spécial)

---

## 📞 SUPPORT

Pour toute question ou problème, contactez l'équipe CENTRAL+.

---

## 📝 CHANGELOG

### Version 1.0 (07/11/2025)
- ✅ Système complet de gestion hospitalière
- ✅ Espace médecin avec dossiers médicaux
- ✅ Workflow examens médicaux (Médecin → Caissier → Laborantin)
- ✅ Système de notifications en temps réel
- ✅ Transfert inter-hospitalier de dossiers
- ✅ Isolation complète des données par entité
- ✅ Gestion dynamique des utilisateurs
- ✅ 14 rôles pour toutes les entités
- ✅ 68+ permissions granulaires

---

**CENTRAL+ - La solution complète pour la gestion de votre établissement de santé** 🏥✨
