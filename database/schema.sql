-- ════════════════════════════════════════════════════════════════
--                   MINI-BIBLIOTHÈQUE
--                   Schéma de base de données complet
--                   MySQL / MariaDB — UTF8MB4
-- ════════════════════════════════════════════════════════════════

CREATE DATABASE IF NOT EXISTS mini_biblio
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

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
  -- Hash bcrypt via password_hash($plain, PASSWORD_BCRYPT)
  role          ENUM('admin','auteur','membre') NOT NULL DEFAULT 'membre',
  is_active     TINYINT(1)      NOT NULL DEFAULT 1,
  avatar        VARCHAR(255)    NULL,
  bio           TEXT            NULL,
  last_login    DATETIME        NULL,
  created_at    DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at    DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
                                         ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


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
  slug          VARCHAR(110)    NOT NULL UNIQUE,
  description   TEXT            NULL,
  created_at    DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


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
  born_year     YEAR            NULL,
  created_by    INT UNSIGNED    NULL,
  created_at    DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,

  CONSTRAINT fk_authors_created_by
    FOREIGN KEY (created_by) REFERENCES users(id)
    ON DELETE SET NULL
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


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
  pdf_filename    VARCHAR(255)    NULL,
  -- Nom sur le serveur : book-{id}-{slug}-{timestamp}.pdf
  pdf_original    VARCHAR(255)    NULL,
  -- Nom d'origine pour le Content-Disposition au téléchargement
  pdf_path        VARCHAR(500)    NULL,
  -- Chemin absolu : /var/www/html/mini-biblio/uploads/pdfs/{pdf_filename}
  pdf_size        INT UNSIGNED    NULL,
  -- Taille en octets pour le header Content-Length

  author_id       INT UNSIGNED    NOT NULL,
  category_id     INT UNSIGNED    NOT NULL,
  added_by        INT UNSIGNED    NULL,
  created_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
                                           ON UPDATE CURRENT_TIMESTAMP,

  CONSTRAINT fk_books_author
    FOREIGN KEY (author_id) REFERENCES authors(id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,

  CONSTRAINT fk_books_category
    FOREIGN KEY (category_id) REFERENCES categories(id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,

  CONSTRAINT fk_books_added_by
    FOREIGN KEY (added_by) REFERENCES users(id)
    ON DELETE SET NULL
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


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
  updated_at      DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP
                                            ON UPDATE CURRENT_TIMESTAMP,

  UNIQUE KEY uq_review_user_book (user_id, book_id),

  CONSTRAINT chk_rating CHECK (rating BETWEEN 1 AND 5),

  CONSTRAINT fk_reviews_book
    FOREIGN KEY (book_id) REFERENCES books(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,

  CONSTRAINT fk_reviews_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


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

  CONSTRAINT fk_downloads_book
    FOREIGN KEY (book_id) REFERENCES books(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,

  CONSTRAINT fk_downloads_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


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


-- ════════════════════════════════════════════════════════════════
-- VUE : catalogue complet
-- ════════════════════════════════════════════════════════════════
--
-- Utilisée dans BookRepository pour éviter les JOIN répétitifs
--
-- has_pdf = 1 si pdf_path n'est pas NULL (livre uploadé)
--         = 0 si le livre est dans le catalogue mais sans fichier
-- ════════════════════════════════════════════════════════════════

CREATE VIEW view_books_full AS
SELECT
  b.id                                        AS book_id,
  b.title,
  b.slug,
  b.description,
  b.published_year,
  b.cover_image,
  b.pdf_filename,
  b.pdf_original,
  b.pdf_path,
  b.pdf_size,
  CASE WHEN b.pdf_path IS NOT NULL THEN 1 ELSE 0 END AS has_pdf,
  b.created_at,

  a.id                                        AS author_id,
  CONCAT(a.first_name, ' ', a.last_name)      AS author_name,
  a.first_name                                AS author_first_name,
  a.last_name                                 AS author_last_name,

  c.id                                        AS category_id,
  c.name                                      AS category_name,
  c.slug                                      AS category_slug,

  COUNT(DISTINCT r.id)                        AS review_count,
  ROUND(AVG(r.rating), 1)                     AS avg_rating,

  COUNT(DISTINCT d.id)                        AS download_count

FROM books b
JOIN  authors    a ON a.id = b.author_id
JOIN  categories c ON c.id = b.category_id
LEFT JOIN reviews   r ON r.book_id = b.id
LEFT JOIN downloads d ON d.book_id = b.id

GROUP BY
  b.id, b.title, b.slug, b.description,
  b.published_year, b.cover_image,
  b.pdf_filename, b.pdf_original, b.pdf_path, b.pdf_size,
  b.created_at,
  a.id, a.first_name, a.last_name,
  c.id, c.name, c.slug;


-- ════════════════════════════════════════════════════════════════
-- DONNÉES DE BASE (seed)
-- ════════════════════════════════════════════════════════════════

INSERT INTO users (username, email, password, role) VALUES
('admin',       'admin@biblio.fr',    '$2y$12$5RVq4MdNPcBdojZVFj9kzuEZxrFGoTJq5yCEJLmkKxjXOg8M3oYGi', 'admin'),
('auteur_test', 'auteur@biblio.fr',   '$2y$12$5RVq4MdNPcBdojZVFj9kzuEZxrFGoTJq5yCEJLmkKxjXOg8M3oYGi', 'auteur'),
('jonathan_m',  'jonathan@biblio.fr', '$2y$12$5RVq4MdNPcBdojZVFj9kzuEZxrFGoTJq5yCEJLmkKxjXOg8M3oYGi', 'membre');

INSERT INTO categories (name, slug, description) VALUES
('Informatique',    'informatique',    'Programmation, architecture logicielle, systèmes'),
('Roman',           'roman',           'Fiction littéraire, récits narratifs'),
('Histoire',        'histoire',        'Histoire mondiale, civilisations, biographies'),
('Science',         'science',         'Physique, biologie, mathématiques, astronomie'),
('Philosophie',     'philosophie',     'Pensée critique, éthique, métaphysique'),
('Economie',        'economie',        'Microéconomie, macroéconomie, finance'),
('Science-fiction', 'science-fiction', 'Anticipation, futurisme, uchronies'),
('Biographie',      'biographie',      'Vies de personnalités, mémoires, autobiographies');

INSERT INTO authors (first_name, last_name, born_year, created_by) VALUES
('Robert',  'Martin',  1952, 1),
('Yuval',   'Harari',  1976, 1),
('Albert',  'Camus',   1913, 1),
('Stephen', 'Hawking', 1942, 1),
('Donald',  'Knuth',   1938, 1);

-- pdf_* sont NULL : les fichiers seront uploadés depuis l'admin
INSERT INTO books (title, slug, description, published_year, author_id, category_id, added_by) VALUES
('Clean Code',                'clean-code',                 'Guide pratique pour ecrire du code lisible et maintenable.',          2008, 1, 1, 1),
('The Clean Coder',           'the-clean-coder',            'Les regles de conduite du developpeur professionnel.',                 2011, 1, 1, 1),
('Sapiens',                   'sapiens',                    'Une breve histoire de l humanite, des origines a nos jours.',          2011, 2, 3, 1),
('L Etranger',                'l-etranger',                 'Roman emblematique de Camus sur l absurde.',                          1942, 3, 2, 1),
('Une breve histoire du temps','une-breve-histoire-du-temps','Hawking vulgarise les grandes theories de la physique moderne.',      1988, 4, 4, 1);

INSERT INTO reviews (book_id, user_id, rating, comment) VALUES
(1, 3, 5, 'Reference absolue. Lecture obligatoire pour tout developpeur.'),
(2, 3, 4, 'Tres bon complement au premier livre.'),
(3, 3, 5, 'Fascinant. Change la facon de voir l histoire humaine.');


-- ════════════════════════════════════════════════════════════════
-- RÉSUMÉ
-- ════════════════════════════════════════════════════════════════
--
--  TABLE           DESCRIPTION
--  ─────────────── ──────────────────────────────────────────────
--  users           Comptes (admin / auteur / membre)
--  categories      Catégories — gérées par l'admin
--  authors         Auteurs des livres — entité indépendante
--  books           Livres + fichier PDF intégré (même entité)
--  reviews         Avis et notes — 1 par membre par livre
--  downloads       Historique des téléchargements
--
--  VUE                  UTILISATION
--  ──────────────────── ─────────────────────────────────────────
--  view_books_full      Catalogue complet avec auteur, catégorie,
--                       note moyenne, infos PDF — utilisée partout
--
-- ════════════════════════════════════════════════════════════════
