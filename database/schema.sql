-- ════════════════════════════════════════════════════════════════
--                   MINI-BIBLIOTHÈQUE
--                   Schéma de base de données complet
--                   MySQL / MariaDB — UTF8MB4
-- ════════════════════════════════════════════════════════════════

CREATE DATABASE IF NOT EXISTS mini_biblio;
USE mini_biblio;

-- ────────────────────────────────────────────────────────────────
-- 1. UTILISATEURS
-- ────────────────────────────────────────────────────────────────
--
-- Trois rôles :
--   membre  → consulter, télécharger les livres, laisser des avis
--   auteur  → ajouter et gérer ses propres livres
--   admin   → accès complet à toute la plateforme
--
-- is_active = 0  →  compte banni ou désactivé par l'admin
-- avatar        →  chemin relatif : uploads/avatars/user-{id}.jpg
-- last_login    →  mis à jour à chaque connexion réussie
-- ────────────────────────────────────────────────────────────────

CREATE TABLE users (
  id            INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
  username      VARCHAR(50)     NOT NULL UNIQUE,
  email         VARCHAR(150)    NOT NULL UNIQUE,
  password      VARCHAR(255)    NOT NULL,
  role          ENUM('admin', 'membre') NOT NULL DEFAULT 'membre',
  is_active     TINYINT(1)      NOT NULL DEFAULT 1,
  photo        VARCHAR(255)    NULL, -- Chemin relatif : uploads/avatars/user-{id}.jpg
  created_at    DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
);


-- ────────────────────────────────────────────────────────────────
-- 2. CATÉGORIES
-- ────────────────────────────────────────────────────────────────
--
-- Gérées exclusivement par l'admin
-- slug = version URL-friendly du nom
--        ex : "Science-fiction" → "science-fiction"
-- ────────────────────────────────────────────────────────────────

CREATE TABLE categories (
  id            INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
  name          VARCHAR(100)    NOT NULL UNIQUE,
  slug          VARCHAR(110)    NOT NULL UNIQUE, -- 10 caractères supplémentaires pour les éventuels suffixes d'URL
  description   TEXT            NULL,
  created_at    DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP -- Date de creation de la categorie
);


-- ────────────────────────────────────────────────────────────────
-- 3. AUTEURS DE LIVRES
-- ────────────────────────────────────────────────────────────────
--
-- Entité distincte des utilisateurs de la plateforme.
-- Un auteur de livre (ex : Robert C. Martin) n'est pas
-- forcément un utilisateur inscrit sur le site.
--
-- created_by → mis à NULL si l'utilisateur qui a créé la fiche
--              est supprimé
-- ────────────────────────────────────────────────────────────────

CREATE TABLE authors (
  id            INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
  first_name    VARCHAR(100)    NOT NULL,
  last_name     VARCHAR(100)    NOT NULL,
  bio           TEXT            NULL,
  nationality   VARCHAR(100)    NULL,
  photo         VARCHAR(255)    NULL,
  born_year     YEAR            NULL, -- Année de naissance
  created_by    INT UNSIGNED    NULL -- Date de creation de la fiche auteur
);


-- ────────────────────────────────────────────────────────────────
-- 4. LIVRES (= e-books PDF téléchargeables)
-- ────────────────────────────────────────────────────────────────
--
-- Un livre EST un fichier PDF. Il n'y a pas de table séparée.
-- Tous les champs du fichier sont directement dans cette table.
--
-- slug          → identifiant URL unique généré depuis le titre
--                 ex : "Clean Code" → "clean-code"
--
-- cover_image   → chemin relatif de l'image de couverture
--                 ex : uploads/covers/book-1-clean-code.jpg
--                 NULL si aucune image uploadée
--
-- pdf_filename  → nom du fichier généré côté serveur à l'upload
--                 Format : book-{id}-{slug}-{timestamp}.pdf
--                 ex : book-1-clean-code-1710432000.pdf
--
-- pdf_original  → nom original fourni par l'admin lors de l'upload
--                 ex : Clean_Code_Robert_Martin.pdf
--                 Utilisé dans le header Content-Disposition
--                 pour nommer le fichier lors du téléchargement
--
-- pdf_path      → chemin ABSOLU du fichier sur le serveur
--                 ex : /var/www/html/mini-biblio/uploads/pdfs/book-1-clean-code-1710432000.pdf
--                 Utilisé directement par readfile() dans download.php
--                 Le dossier uploads/pdfs/ est hors de public/
--                 Aucun accès direct par URL n'est possible
--
-- pdf_size      → taille en octets enregistrée à l'upload via filesize()
--                 Utilisée pour le header Content-Length
--
-- Tous les champs pdf_* sont NULL tant qu'aucun fichier n'est uploadé
--
-- added_by      → mis à NULL si l'utilisateur est supprimé
--
-- ON DELETE RESTRICT sur author_id et category_id :
--   → impossible de supprimer un auteur ou une catégorie
--     tant qu'il existe des livres rattachés
-- ────────────────────────────────────────────────────────────────

CREATE TABLE books (
  id              INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
  title           VARCHAR(255)    NOT NULL,
  slug            VARCHAR(270)    NOT NULL UNIQUE,
  description     TEXT            NULL,
  published_year  YEAR            NULL,
  cover_image     VARCHAR(255)    NULL,
  -- Chemin relatif : uploads/covers/book-{id}-{slug}.jpg
  -- Fichier PDF (le livre lui-même)

  pdf_filename    VARCHAR(255)    NULL, -- Nom sur le serveur : book-{id}-{slug}-{timestamp}.pdf
  pdf_original    VARCHAR(255)    NULL, -- Nom d'origine pour le Content-Disposition au téléchargement
  pdf_path        VARCHAR(500)    NULL, -- Chemin absolu : /var/www/html/mini-biblio/uploads/pdfs/{pdf_filename}
  pdf_size        INT UNSIGNED    NULL, -- Taille en octets pour le header Content-Length

  author_id       INT UNSIGNED    NOT NULL,
  category_id     INT UNSIGNED    NOT NULL,
  added_by        INT UNSIGNED    NULL,
  created_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (author_id) REFERENCES authors(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,

  FOREIGN KEY (category_id) REFERENCES categories(id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
-- ────────────────────────────────────────────────────────────────
-- 5. AVIS ET NOTES
-- ────────────────────────────────────────────────────────────────
--
-- Réservé aux membres connectés
-- UNIQUE (user_id, book_id) → 1 seul avis par membre par livre
-- Note obligatoire entre 1 et 5
-- Commentaire facultatif
-- ────────────────────────────────────────────────────────────────

CREATE TABLE reviews (
  id              INT UNSIGNED     AUTO_INCREMENT PRIMARY KEY,
  book_id         INT UNSIGNED     NOT NULL,
  user_id         INT UNSIGNED     NOT NULL,
  rating          TINYINT UNSIGNED NOT NULL,
  comment         TEXT             NULL,
  created_at      DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE(user_id, book_id),
  CONSTRAINT chk_rating CHECK (rating BETWEEN 1 AND 5),
  FOREIGN KEY (book_id) REFERENCES books(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
-- ────────────────────────────────────────────────────────────────
-- 6. HISTORIQUE DES TÉLÉCHARGEMENTS
-- ────────────────────────────────────────────────────────────────
--
-- Enregistré à chaque téléchargement réussi via download.php
-- Permet les stats admin : livres les plus téléchargés
-- Pas de UNIQUE → un membre peut télécharger plusieurs fois
-- ────────────────────────────────────────────────────────────────

CREATE TABLE downloads (
  id              INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
  book_id         INT UNSIGNED    NOT NULL,
  user_id         INT UNSIGNED    NOT NULL,
  downloaded_at   DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (book_id) REFERENCES books(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,

  FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
-- ════════════════════════════════════════════════════════════════
-- INDEX DE PERFORMANCE
-- ════════════════════════════════════════════════════════════════

CREATE INDEX idx_books_category   ON books(category_id);
CREATE INDEX idx_books_author     ON books(author_id);
CREATE INDEX idx_books_title      ON books(title);
CREATE INDEX idx_reviews_book     ON reviews(book_id);
CREATE INDEX idx_downloads_book   ON downloads(book_id);
CREATE INDEX idx_downloads_user   ON downloads(user_id);
CREATE INDEX idx_authors_lastname ON authors(last_name);