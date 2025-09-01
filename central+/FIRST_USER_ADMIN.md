# 👑 Premier Utilisateur = Administrateur Automatique

## Vue d'ensemble

Le système CENTRAL+ attribue automatiquement le rôle **administrateur** au premier utilisateur de chaque type d'entité (hôpital, pharmacie, banque de sang, centre, patient).

## 🎯 Logique d'attribution

### Règles automatiques
- **Premier utilisateur** d'un type d'entité → **Rôle : `admin`**
- **Utilisateurs suivants** du même type → **Rôle : `user`**
- **Superadmin** → Toujours `superadmin` (immuable)

### Types d'entités supportés
- 🏥 **Hôpital** (`hopital`)
- 💊 **Pharmacie** (`pharmacie`) 
- 🩸 **Banque de sang** (`banque_sang`)
- 🏢 **Centre médical** (`centre`)
- 👤 **Patient** (`patient`)

## 🔧 Implémentation technique

### 1. RegisterController
```php
// Vérifier si c'est le premier utilisateur de ce type d'entité
$isFirstUserOfType = !Utilisateur::where('type_utilisateur', $type)
    ->where('status', 'approved') // Seulement les utilisateurs déjà approuvés
    ->exists();

// Déterminer le rôle : admin si premier utilisateur de ce type, sinon user
$userRole = $isFirstUserOfType ? 'admin' : 'user';
```

### 2. Modèle Utilisateur
```php
// Vérifier si l'utilisateur est le premier de son type d'entité
public function isFirstOfEntityType()
{
    return !static::where('type_utilisateur', $this->type_utilisateur)
        ->where('status', 'approved')
        ->where('id', '!=', $this->id)
        ->exists();
}

// Vérifier si l'utilisateur peut être promu admin
public function canBePromotedToAdmin()
{
    return $this->isFirstOfEntityType();
}
```

## 🚀 Commandes Artisan

### Vérifier et corriger les rôles
```bash
php artisan admin:ensure-first-user-admin
```

Cette commande :
- ✅ Vérifie chaque type d'entité
- ✅ Promu le premier utilisateur en admin si nécessaire
- ✅ Rétrograde les autres utilisateurs du même type en user
- ✅ Attribue les permissions appropriées

### Exemple de sortie
```
🔍 Vérification des premiers utilisateurs de chaque type d'entité...

📋 Vérification pour : Hopital
  👤 Premier utilisateur : Dr. Martin (martin@hopital.com)
  🎭 Rôle actuel : user
  ✅ Promu en admin avec permissions hopital
  🔑 25 permissions attribuées

📋 Vérification pour : Pharmacie
  👤 Premier utilisateur : M. Dupont (dupont@pharmacie.com)
  🎭 Rôle actuel : admin
  ✅ Déjà admin

🎉 1 changements effectués !
```

## 🔑 Permissions automatiques

### Permissions de base (tous les admins)
- **Gestion des utilisateurs** : `view_users`, `create_users`, `edit_users`
- **Gestion des patients** : `view_patients`, `create_patients`, `edit_patients`
- **Rendez-vous** : `view_appointments`, `create_appointments`, `edit_appointments`
- **Dossiers médicaux** : `view_medical_records`, `create_medical_records`, `edit_medical_records`
- **Prescriptions** : `view_prescriptions`, `create_prescriptions`, `edit_prescriptions`
- **Rapports** : `view_reports`, `create_reports`

### Permissions spécifiques par entité

#### 🏥 Hôpital
- `view_consultations`, `create_consultations`, `edit_consultations`
- `view_services`, `create_services`, `edit_services`
- `view_hopital`, `edit_hopital`

#### 💊 Pharmacie
- `view_medicines`, `create_medicines`, `edit_medicines`
- `view_stocks`, `create_stocks`, `edit_stocks`
- `view_invoices`, `create_invoices`, `edit_invoices`
- `view_pharmacie`, `edit_pharmacie`

#### 🩸 Banque de sang
- `view_donors`, `create_donors`, `edit_donors`
- `view_blood_reserves`, `create_blood_reserves`, `edit_blood_reserves`
- `view_banque_sang`, `edit_banque_sang`

#### 🏢 Centre médical
- `view_centre`, `edit_centre`
- `view_patients`, `create_patients`, `edit_patients`

#### 👤 Patient
- `view_patient`, `edit_patient`
- `view_appointments`, `create_appointments`

## 📋 Messages utilisateur

### Premier utilisateur (admin)
```
Inscription réussie ! Vous êtes le premier utilisateur de ce type d'entité 
et serez administrateur. Votre compte est en attente d'approbation.
```

### Utilisateurs suivants (user)
```
Inscription réussie ! Votre compte est en attente d'approbation 
par l'administrateur.
```

## 🔄 Gestion des changements

### Promotion automatique
- Se produit lors de l'inscription
- Basé sur l'ordre chronologique (`created_at`)
- Seulement pour les utilisateurs approuvés

### Rétrogradation automatique
- Si un utilisateur admin n'est plus le premier
- Rôle changé de `admin` à `user`
- Permissions révoquées automatiquement

## 🛡️ Sécurité

### Protection contre la manipulation
- Vérification du statut `approved`
- Ordre chronologique strict
- Une seule personne admin par type d'entité

### Vérifications
- Rôle admin uniquement pour le premier
- Permissions cohérentes avec le type d'entité
- Pas de conflit avec le superadmin

## 📝 Cas d'usage

### Scénario 1 : Premier hôpital
1. **Dr. Martin** s'inscrit → Rôle : `admin`
2. **Dr. Dubois** s'inscrit → Rôle : `user`
3. **Dr. Martin** approuve **Dr. Dubois**

### Scénario 2 : Première pharmacie
1. **M. Dupont** s'inscrit → Rôle : `admin`
2. **M. Dupont** peut gérer tous les utilisateurs de sa pharmacie

### Scénario 3 : Changement de premier utilisateur
1. **Dr. Martin** (admin) quitte l'hôpital
2. **Dr. Dubois** devient automatiquement admin
3. **Dr. Martin** est rétrogradé en user

## 🎯 Avantages

- **Simplicité** : Attribution automatique des rôles
- **Sécurité** : Un seul admin par type d'entité
- **Flexibilité** : Changement automatique selon l'ordre chronologique
- **Cohérence** : Permissions adaptées au type d'entité
- **Maintenance** : Commandes Artisan pour vérification
