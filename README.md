# 🚀 Gestion Communautaire - Projet Laravel 11

## 📌 Prérequis

Avant de lancer le projet, assurez-vous d’avoir :

- PHP **≥ 8.2** (idéalement 8.4)
- Composer
- Node.js et npm
- SQLite (ou autre base de données)

---

## ⚙️ Installation

```bash
git clone <repo-url>
cd gestion-communautaire
````

### 1. Installer les dépendances backend

```bash
composer install
```

### 2. Configurer l’environnement

```bash
cp .env.example .env
php artisan key:generate
```

---

## 🗄️ Configuration de la base de données

Le projet utilise **SQLite** par défaut.

```bash
touch database/database.sqlite
```

Vérifiez dans `.env` :

```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

---

## 📦 Migration et Seeders

```bash
php artisan migrate --seed
```

---

## 🎨 Installation du frontend

```bash
npm install
npm run build
```

---

## ▶️ Lancer l’application

### Option 1 : Script automatique

```bash
./start.sh
```

### Option 2 : Manuel

```bash
php artisan serve
```

Accès à l'application :

👉 [http://127.0.0.1:8000](http://127.0.0.1:8000)
(Si occupé : 8001 ou 8002)

---

## 🧪 Lancer les tests

```bash
php artisan test
```

---

## 🔐 Comptes par défaut

Après le seeding :

* **Administrateur**
  Email: `admin@commune.bj`
  Mot de passe: `password123`


## ✅ État du projet

* ✔️ Laravel 11 installé
* ✔️ Base de données SQLite configurée
* ✔️ Migrations et seeders opérationnels
* ✔️ Dépendances installées :

  * `maatwebsite/excel` (export Excel)
  * `barryvdh/laravel-dompdf` (export PDF)
* ✔️ Tests fonctionnels

---

## 📄 Licence

Ce projet est destiné à un usage académique / interne.

---

## 👨‍💻 Auteur

Projet développé dans le cadre d’une application de gestion communautaire.

```
