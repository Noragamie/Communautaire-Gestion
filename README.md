# Gestion Communautaire - Projet Laravel 11

## 🚀 Démarrage rapide

Le projet est déjà configuré et prêt à l'emploi!

### Lancer l'application

```bash
cd gestion-communautaire
./start.sh
```

Ou manuellement:
```bash
cd gestion-communautaire
php artisan serve
```

L'application sera accessible sur: **http://127.0.0.1:8000** (ou 8001/8002 si le port est occupé)

### Lancer les tests

```bash
cd gestion-communautaire
php artisan test
```

## ✅ Configuration actuelle

- ✅ Laravel 11 installé
- ✅ SQLite configuré (database/database.sqlite)
- ✅ Migrations exécutées
- ✅ Dépendances installées:
  - maatwebsite/excel (export Excel)
  - barryvdh/laravel-dompdf (export PDF)
- ✅ Tests fonctionnels

## 📝 Prochaines étapes

Pour compléter le projet avec les fonctionnalités décrites dans install.sh:

1. Copier les modèles, contrôleurs, vues depuis les fichiers ALL_*.php
2. Configurer les routes et middleware
3. Créer les seeders pour les données de test
4. Configurer l'envoi d'emails (MAIL_* dans .env)

## 🔑 Comptes par défaut (après seeding)

- Administrateur: admin@commune.bj / password123
- Opérateur: jean@example.com / password123
