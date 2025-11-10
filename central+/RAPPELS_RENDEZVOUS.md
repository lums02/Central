# 🔔 SYSTÈME DE RAPPELS DE RENDEZ-VOUS

## 📋 Vue d'ensemble

Le système envoie automatiquement des notifications de rappel aux médecins et patients :
- **24 heures avant** le rendez-vous
- **2 heures avant** le rendez-vous

---

## ⚙️ CONFIGURATION

### **1. Tester la commande manuellement**

```bash
php artisan rendezvous:rappels
```

Vous verrez :
```
🔔 Vérification des rendez-vous à rappeler...
✅ Rappels 24h envoyés: X
✅ Rappels 2h envoyés: X
✨ Terminé !
```

---

### **2. Configuration du Scheduler Laravel**

Le scheduler est déjà configuré dans `app/Console/Kernel.php` pour exécuter la commande **toutes les heures**.

#### **Sur Windows (WAMP/XAMPP)**

**Option A : Task Scheduler Windows**

1. Ouvrir "Planificateur de tâches" (Task Scheduler)
2. Créer une tâche de base
3. Nom : "Laravel Scheduler - CENTRAL+"
4. Déclencheur : Quotidien à 00:00
5. Action : Démarrer un programme
   - Programme : `C:\wamp64\bin\php\php8.2.28\php.exe` (ajustez selon votre version)
   - Arguments : `artisan schedule:run`
   - Répertoire : `C:\wamp64\www\Central\central+`
6. Répéter la tâche toutes les : **1 heure**
7. Pendant : **1 jour**

**Option B : Exécution manuelle (Développement)**

Lancer dans un terminal PowerShell :
```powershell
cd C:\wamp64\www\Central\central+
while ($true) { php artisan schedule:run; Start-Sleep -Seconds 3600 }
```

---

#### **Sur Linux/Mac (Production)**

Ajouter au crontab :
```bash
crontab -e
```

Ajouter cette ligne :
```
* * * * * cd /path/to/central+ && php artisan schedule:run >> /dev/null 2>&1
```

---

### **3. Vérifier que ça fonctionne**

#### **Créer un rendez-vous de test**

1. Connectez-vous en tant que médecin
2. Créez un rendez-vous pour **demain à 10h00**
3. Attendez que la commande s'exécute (ou exécutez manuellement)
4. Vérifiez les notifications du médecin et du patient

#### **Forcer l'exécution immédiate**

```bash
php artisan rendezvous:rappels
```

---

## 📊 FONCTIONNEMENT

### **Logique de la commande**

1. **Récupère** tous les rendez-vous avec statut `en_attente` ou `confirme`
2. **Calcule** la date/heure du rendez-vous
3. **Vérifie** si le RDV est dans 24h (±1h) ou 2h (±30min)
4. **Vérifie** qu'un rappel n'a pas déjà été envoyé (évite les doublons)
5. **Crée** des notifications pour le médecin ET le patient
6. **Enregistre** dans la base de données

### **Types de notifications créées**

#### **Rappel 24h**
- **Type** : `rappel_rdv_24h`
- **Icône** : 📅 (calendar-day)
- **Titre** : "Rappel : Rendez-vous demain"
- **Message Médecin** : "Rendez-vous avec [Patient] demain à [Heure]"
- **Message Patient** : "Vous avez un rendez-vous avec Dr. [Médecin] demain à [Heure]"

#### **Rappel 2h**
- **Type** : `rappel_rdv_2h`
- **Icône** : ⏰ (clock)
- **Titre** : "⚠️ Rendez-vous dans 2 heures"
- **Message Médecin** : "Rendez-vous avec [Patient] aujourd'hui à [Heure]"
- **Message Patient** : "N'oubliez pas votre rendez-vous avec Dr. [Médecin] aujourd'hui à [Heure]"

---

## 🧪 TESTS

### **Test 1 : Rappel 24h**

```bash
# Créer un RDV pour demain
# Exécuter la commande
php artisan rendezvous:rappels

# Vérifier les notifications dans la base de données
```

### **Test 2 : Rappel 2h**

```bash
# Créer un RDV pour aujourd'hui dans 2h
# Exécuter la commande
php artisan rendezvous:rappels

# Vérifier les notifications
```

---

## 📝 NOTES IMPORTANTES

1. ✅ **Pas de doublons** : La commande vérifie qu'un rappel n'a pas déjà été envoyé
2. ✅ **Marge de tolérance** : 
   - 24h : ±1 heure (entre 23h et 25h avant)
   - 2h : ±30 minutes (entre 1h30 et 2h30 avant)
3. ✅ **Statuts concernés** : Uniquement `en_attente` et `confirme` (pas les annulés ou terminés)
4. ✅ **Performance** : Exécution rapide, pas de surcharge

---

## 🔧 DÉPANNAGE

### **La commande ne s'exécute pas**

```bash
# Vérifier les logs Laravel
tail -f storage/logs/laravel.log

# Vérifier la liste des commandes
php artisan list

# Tester manuellement
php artisan rendezvous:rappels
```

### **Pas de notifications reçues**

1. Vérifier qu'il y a des rendez-vous dans les prochaines 24h ou 2h
2. Vérifier que le statut est `en_attente` ou `confirme`
3. Vérifier dans la table `notifications` si elles ont été créées
4. Vérifier que le scheduler Laravel tourne

---

## 📈 AMÉLIORATIONS FUTURES

- [ ] Envoyer des emails en plus des notifications
- [ ] Envoyer des SMS
- [ ] Personnaliser les heures de rappel
- [ ] Ajouter un rappel 1 semaine avant
- [ ] Permettre aux patients de confirmer par notification

---

**Système de rappels implémenté avec succès ! 🎉**

