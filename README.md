# 📚 Mini-Bibliothèque

Un projet web en **PHP / PDO / MySQL** pour gérer un catalogue de livres avec authentification, upload de PDFs et système d'avis.

---

## ✨ Fonctionnalités

- 📖 Parcourir le catalogue de livres (titre, auteur, catégorie, note moyenne)
- 🔍 Rechercher un livre par titre, auteur ou catégorie
- 📄 Voir le détail d'un livre et les avis des membres
- 👤 Inscription et connexion avec deux rôles : **Admin** et **Membre**
- ⭐ Laisser une note (1 à 5) et un commentaire sur un livre
- 📥 Télécharger le PDF d'un livre (membres connectés uniquement)
- 🔒 Accès sécurisé aux PDFs — aucun fichier accessible directement par URL

### Espace Admin
- Ajouter, modifier, supprimer des livres et des auteurs
- Gérer les catégories
- Uploader les PDFs associés aux livres
- Gérer les membres

---

## 🗂️ Structure du projet

```
mini-biblio/
├── config/          → Connexion PDO
├── classes/         → Book, Author, User, Review, Repositories
├── includes/        → Fonctions utilitaires, auth, upload
├── uploads/pdfs/    → Fichiers PDF (accès contrôlé)
├── database/        → schema.sql + seed.sql
└── public/
    ├── auth/        → login, register, logout
    ├── member/      → dashboard, profil, mes avis
    ├── admin/       → gestion complète
    └── assets/      → CSS, JS
```

---

## 🛠️ Technologies

- **PHP 8+** — sans framework
- **PDO** — requêtes préparées uniquement
- **MySQL** — 6 tables relationnelles
- **HTML / CSS / JS** — frontend simple et fonctionnel

---

## 🗄️ Base de données

| Table | Description |
|---|---|
| `users` | Comptes utilisateurs (admin / membre) |
| `authors` | Auteurs des livres |
| `categories` | Catégories de livres |
| `books` | Catalogue des livres |
| `pdfs` | Fichiers PDF associés aux livres |
| `reviews` | Avis et notes des membres |

---

## 🚀 Installation

```bash
# 1. Cloner le projet
git clone https://github.com/ton-username/mini-biblio.git
cd mini-biblio

# 2. Créer la base de données
mysql -u root -p < database/schema.sql
mysql -u root -p < database/seed.sql   # optionnel

# 3. Configurer la connexion
cp config/database.php.example config/database.php
# puis remplir les identifiants BDD

# 4. Lancer avec PHP
php -S localhost:8000 -t public/
```

---

## 🔐 Rôles

| Action | Visiteur | Membre | Admin |
|---|:---:|:---:|:---:|
| Parcourir le catalogue | ✅ | ✅ | ✅ |
| Voir le détail d'un livre | ✅ | ✅ | ✅ |
| Télécharger un PDF | ❌ | ✅ | ✅ |
| Laisser un avis | ❌ | ✅ | ✅ |
| Ajouter / modifier un livre | ❌ | ❌ | ✅ |
| Gérer les utilisateurs | ❌ | ❌ | ✅ |

---

## 📌 Objectif pédagogique

Ce projet a été conçu pour pratiquer :

- Les requêtes SQL : `INSERT`, `SELECT`, `JOIN`, `DELETE`
- Les relations entre tables
- PDO et les requêtes préparées
- La gestion des sessions et des rôles
- L'upload et le téléchargement sécurisé de fichiers
- La séparation des responsabilités (Repository pattern)

---

## 📝 Licence

Projet personnel à but pédagogique — libre d'utilisation.
