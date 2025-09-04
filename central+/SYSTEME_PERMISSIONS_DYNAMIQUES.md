# 🎯 Système de Permissions Dynamiques - CENTRAL+

## 📋 **Vue d'ensemble**

Le système de permissions dynamiques permet à chaque utilisateur de voir uniquement les fonctionnalités pour lesquelles il a des permissions. L'interface s'adapte automatiquement selon les droits accordés.

## 🔧 **Fonctionnement**

### 1. **Sidebar Dynamique**
- **Fichier**: `resources/views/layouts/partials/admin/leftsidebar.blade.php`
- **Logique**: Chaque élément de menu est conditionnel selon les permissions
- **Exemple**: `@if(auth()->user()->can('view_users'))`

### 2. **Tableau de Bord Adaptatif**
- **Fichier**: `resources/views/admin/dashboard.blade.php`
- **Contrôleur**: `app/Http/Controllers/Admin/DashboardController.php`
- **Logique**: Affiche seulement les statistiques selon les permissions

### 3. **Middleware de Protection**
- **Fichier**: `app/Http/Middleware/CheckPermission.php`
- **Enregistrement**: `app/Http/Kernel.php`
- **Utilisation**: `Route::middleware('permission:view_users')`

## 🎨 **Interface Utilisateur**

### **Éléments Affichés Selon les Permissions**

| Permission | Élément Affiché | Description |
|------------|----------------|-------------|
| `view_roles` | Rôles et Permissions | Gestion des rôles et permissions |
| `view_users` | Utilisateurs + En Attente | Gestion des utilisateurs |
| `view_patients` | Patients | Gestion des patients |
| `view_appointments` | Rendez-vous | Gestion des rendez-vous |
| `view_medical_records` | Dossiers Médicaux | Gestion des dossiers médicaux |
| `view_prescriptions` | Prescriptions | Gestion des prescriptions |
| `view_invoices` | Factures | Gestion des factures |
| `view_reports` | Rapports | Gestion des rapports |
| `view_medicines` | Médicaments | Gestion des médicaments |
| `view_stocks` | Stocks | Gestion des stocks |
| `view_donors` | Donneurs | Gestion des donneurs |
| `view_blood_reserves` | Réserves de Sang | Gestion des réserves de sang |
| `view_services` | Services | Gestion des services |
| `view_consultations` | Consultations | Gestion des consultations |

### **Actions Rapides Conditionnelles**

Les boutons d'actions rapides apparaissent seulement si l'utilisateur a les permissions de création correspondantes :

- `create_users` → "Nouvel Utilisateur"
- `create_patients` → "Nouveau Patient"
- `create_appointments` → "Nouveau Rendez-vous"
- `create_prescriptions` → "Nouvelle Prescription"

## 🔒 **Sécurité**

### **Middleware de Protection**

```php
// Exemple d'utilisation
Route::middleware('permission:view_users')->group(function () {
    Route::get('/admin/users', [UserController::class, 'index']);
});
```

### **Vérifications dans les Vues**

```blade
@if(auth()->user()->can('view_users'))
    <!-- Contenu visible seulement si l'utilisateur a la permission -->
@endif
```

## 🚀 **Utilisation**

### **Pour l'Administrateur de l'Hôpital**

1. **Connexion**: L'administrateur se connecte avec ses identifiants
2. **Interface Adaptée**: Il voit seulement les modules pour lesquels il a des permissions
3. **Actions Limitées**: Il ne peut effectuer que les actions autorisées

### **Exemple de Permissions Typiques pour un Admin d'Hôpital**

```php
// Permissions recommandées pour un admin d'hôpital
$permissions = [
    'view_patients',
    'create_patients',
    'edit_patients',
    'delete_patients',
    'view_appointments',
    'create_appointments',
    'edit_appointments',
    'delete_appointments',
    'view_medical_records',
    'create_medical_records',
    'edit_medical_records',
    'view_prescriptions',
    'create_prescriptions',
    'edit_prescriptions',
    'view_invoices',
    'create_invoices',
    'edit_invoices',
    'view_reports',
    'create_reports',
    'view_services',
    'create_services',
    'edit_services',
    'view_consultations',
    'create_consultations',
    'edit_consultations'
];
```

## 📊 **Statistiques Adaptatives**

Le tableau de bord affiche seulement les statistiques pertinentes :

- **Utilisateurs**: Seulement si `view_users`
- **Patients**: Seulement si `view_patients`
- **Rendez-vous**: Seulement si `view_appointments`
- **Factures**: Seulement si `view_invoices`
- **Réserves de sang**: Seulement si `view_blood_reserves`

## 🎯 **Avantages**

1. **Sécurité Renforcée**: Accès limité aux fonctionnalités autorisées
2. **Interface Claire**: Pas de confusion avec des éléments non accessibles
3. **Performance**: Chargement optimisé des données nécessaires
4. **Flexibilité**: Permissions granulaires et personnalisables
5. **Expérience Utilisateur**: Interface adaptée au rôle de chacun

## 🔄 **Évolution**

Le système est extensible et peut facilement intégrer de nouveaux modules :

1. Ajouter la permission dans la base de données
2. Créer le middleware de protection
3. Ajouter la condition dans la sidebar
4. Créer les routes protégées
5. Implémenter la logique métier

---

**Note**: Ce système garantit que chaque utilisateur ne voit et n'utilise que les fonctionnalités pour lesquelles il a été explicitement autorisé.
