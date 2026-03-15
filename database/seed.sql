-- Correction du type avant d'insérer
ALTER TABLE authors MODIFY born_year SMALLINT NULL;
ALTER TABLE books   MODIFY published_year SMALLINT NULL;

-- ── CATÉGORIES ──────────────────────────────────────────────────
INSERT INTO categories (name, slug, description) VALUES
('Science',      'science',      'Physique, biologie, astronomie et vulgarisation scientifique'),
('Roman',        'roman',        'Fiction littéraire, classiques et littérature contemporaine'),
('Histoire',     'histoire',     'Histoire mondiale, civilisations et récits historiques'),
('Philosophie',  'philosophie',  'Pensée critique, éthique, métaphysique et essais philosophiques'),
('Informatique', 'informatique', 'Programmation, architecture logicielle et nouvelles technologies'),
('Art',          'art',          'Peinture, sculpture, photographie et mouvements artistiques'),
('Cuisine',      'cuisine',      'Recettes, techniques culinaires et gastronomie du monde'),
('Voyage',       'voyage',       'Récits de voyage, guides et découvertes à travers le monde');

-- ── AUTEURS ─────────────────────────────────────────────────────
INSERT INTO authors (first_name, last_name, bio, nationality, born_year) VALUES
('George',         'Orwell',          'Romancier et essayiste britannique, auteur de 1984 et La Ferme des animaux. Ses oeuvres dénoncent le totalitarisme et la manipulation politique.',                                        'Britannique', 1903),
('Marcel',         'Proust',          'Romancier français auteur de A la recherche du temps perdu, une oeuvre fleuve en sept volumes explorant la mémoire et le temps.',                                                        'Française',   1871),
('Antoine',        'de Saint-Exupery','Écrivain et aviateur français, auteur du Petit Prince. Disparu en mission en 1944 lors de la Seconde Guerre mondiale.',                                                                   'Française',   1900),
('Victor',         'Hugo',            'Poète, romancier et dramaturge français, figure majeure du romantisme. Auteur des Misérables et de Notre-Dame de Paris.',                                                                 'Française',   1802),
('J.K.',           'Rowling',         'Romancière britannique, créatrice de l univers Harry Potter. La saga s est vendue à plus de 500 millions d exemplaires dans le monde.',                                                   'Britannique', 1965),
('Stephen',        'King',            'Romancier américain spécialisé dans l horreur et le fantastique. Auteur de plus de 60 romans dont Shining, Ca et Misery.',                                                                'Américaine',  1947),
('Agatha',         'Christie',        'Romancière britannique, reine du roman policier. Créatrice du détective Hercule Poirot et de Miss Marple. Auteure la plus traduite au monde après la Bible.',                             'Britannique', 1890),
('Ernest',         'Hemingway',       'Romancier américain, prix Nobel de littérature en 1954. Auteur du Vieil Homme et la Mer et de L Adieu aux armes. Son style épuré a révolutionné la prose anglophone.',                    'Américaine',  1899),
('Toni',           'Morrison',        'Romancière américaine, prix Nobel de littérature en 1993. Auteure de Beloved, oeuvre majeure sur l esclavage et la mémoire collective afro-américaine.',                                  'Américaine',  1931),
('Gabriel Garcia', 'Marquez',         'Romancier colombien, prix Nobel de littérature en 1982. Père du réalisme magique, auteur de Cent ans de solitude et de L Amour aux temps du choléra.',                                    'Colombienne', 1927);

-- ── COMPTE ADMIN PAR DÉFAUT ─────────────────────────────────────
-- Mot de passe : Admin1234!
-- Regénère le hash avec : password_hash('Admin1234!', PASSWORD_BCRYPT)

INSERT INTO users (username, email, password, role) VALUES
('admin', 'admin@gmail.com', '$2y$12$5RVq4MdNPcBdojZVFj9kzuEZxrFGoTJq5yCEJLmkKxjXOg8M3oYGi', 'admin');