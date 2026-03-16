<?php
require_once __DIR__ . '/../config/database.php';

// Function trait avec la base de données
function verify_user($email, $password) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        return $user;
    }
    return false;
}
function verify_username($username) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if($stmt->fetch()) {
        return true;
    }
    return false;
}
function email_exists($email) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if($stmt->fetch()) {
        return true;
    }
    return false;
}
function get_id_user($username) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    return $stmt->fetch();
}
function get_user_by_id($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id, username, email, password ,role, bio, photo FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetch();
}

function create_user($user) {
    try {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$user->getUsername(), $user->getEmail(), $user->getPassword()]);
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        header('Location: register.php?error=database_error');
        die();
    }
}
function get_all_categories() {
    global $pdo;
    $stmt = $pdo->query("SELECT id, name FROM categories");
    return $stmt->fetchAll();
}

function save_profile_preferences($user_id, $photo_path, $bio) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE users SET photo = ?, bio = ? WHERE id = ?");
    $stmt->execute([$photo_path, $bio, $user_id]);
}

function get_photo_path(int $user_id): ?string {
    global $pdo;
    $stmt = $pdo->prepare("SELECT photo FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $result = $stmt->fetchColumn();
    return $result ?: null;
}

function save_user_preferences(int $user_id, array $categories): void {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM preference_user WHERE user_id = ?");
    $stmt->execute([$user_id]);

    $stmt = $pdo->prepare("INSERT INTO preference_user (user_id, category_id) VALUES (?, ?)");
    foreach ($categories as $category_id) {
        $stmt->execute([$user_id, $category_id]);
    }
}function get_role($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetchColumn();
}

function get_date_registration($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT created_at FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetchColumn();
}

// Fonction de l' authentification
function name_character(string $username): string {
    return strtoupper(mb_substr($username, 0, 2));
}
function email_letters($email) {
    if(ctype_alpha($email[0])) {
        return true;
    }
    return false;
}
function error_message($message) {
    switch ($message) {
        case 'invalid_credentials':
            return 'Username ou mot de passe incorrect.';
        case 'csrf_error':
            return 'Token CSRF invalide.';
        case 'password_mismatch':
            return 'Les mots de passe ne correspondent pas.';
        case 'email_exists':
            return 'cet email existe déjà.';
        case 'email_invalid':
            return 'Adresse email invalide.';
        case 'user_exists':
            return 'Ce nom d\'utilisateur est déjà pris.';
        case 'database_error':
            return 'Une erreur est survenue lors de la création du compte. Veuillez réessayer.';
        default:
            return 'Une erreur est survenue. Veuillez réessayer.';
    }
}
function email_valid(string $email): bool {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    if (!email_letters($email)) {
        return false;
    }

    $domain = substr(strrchr($email, '@'), 1);
    if (!checkdnsrr($domain, 'MX')) {
        return false;
    }
    return true;
}

// ─────────────────────────────────────────────────────
// FONCTIONS UTILISATEUR
// ─────────────────────────────────────────────────────
function count_favorites(int $user_id): int {
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM favorite_books WHERE user_id = ?");
    $stmt->execute([$user_id]);
    return (int) $stmt->fetchColumn();
}

function get_recent_favorites(int $user_id, int $limit = 3): array {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT b.id, b.title, b.cover_image, c.name AS category_name,
               a.first_name, a.last_name
        FROM favorite_books f
        JOIN books      b ON b.id = f.book_id
        JOIN authors    a ON a.id = b.author_id
        JOIN categories c ON c.id = b.category_id
        WHERE f.user_id = ?
        ORDER BY f.created_at DESC
        LIMIT ?
    ");
    $stmt->execute([$user_id, $limit]);
    return $stmt->fetchAll();
}

function get_all_favorites(int $user_id): array {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT b.id, b.title, b.cover_image, c.name AS category_name,
               a.first_name, a.last_name
        FROM favorite_books f
        JOIN books      b ON b.id = f.book_id
        JOIN authors    a ON a.id = b.author_id
        JOIN categories c ON c.id = b.category_id
        WHERE f.user_id = ?
    ");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}
function get_rating_reviews(int $id_review){
    global $pdo;
    $stmt = $pdo->prepare("SELECT rating FROM reviews WHERE id = ?");
    $stmt->execute([$id_review]);
    return $stmt->fetchColumn();
}

// ─────────────────────────────────────────────────────
// FONCTIONS TÉLÉCHARGEMENTS
// ─────────────────────────────────────────────────────

// Compte total des téléchargements
function count_downloads(int $user_id): int {
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM downloads WHERE user_id = ?");
    $stmt->execute([$user_id]);
    return (int) $stmt->fetchColumn();
}

// Récupère les derniers téléchargements (pour la table du dashboard)
function get_recent_downloads(int $user_id, int $limit = 5): array {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT b.id, b.title, c.name AS category_name, d.downloaded_at
        FROM downloads d
        JOIN books      b ON b.id = d.book_id
        JOIN categories c ON c.id = b.category_id
        WHERE d.user_id = ?
        ORDER BY d.downloaded_at DESC
        LIMIT ?
    ");
    $stmt->execute([$user_id, $limit]);
    return $stmt->fetchAll();
}

// ─────────────────────────────────────────────────────
// FONCTIONS AVIS
// ─────────────────────────────────────────────────────

// Compte total des avis
function count_user_reviews(int $user_id): int {
    global $pdo;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM reviews WHERE user_id = ?");
    $stmt->execute([$user_id]);
    return (int) $stmt->fetchColumn();
}

// Récupère les avis de l'utilisateur
function get_user_reviews(int $user_id, int $limit = 5): array {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT r.id, r.rating, r.comment, r.created_at,
               b.id AS book_id, b.title
        FROM reviews r
        JOIN books b ON b.id = r.book_id
        WHERE r.user_id = ?
        ORDER BY r.created_at DESC
        LIMIT ?
    ");
    $stmt->execute([$user_id, $limit]);
    return $stmt->fetchAll();
}

function get_user_preferences(int $user_id): array {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT c.id, c.name, c.slug
        FROM preference_user p
        JOIN categories c ON c.id = p.category_id
        WHERE p.user_id = ?
        ORDER BY c.name ASC
    ");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}



function get_admin_stats(): array {
    global $pdo;
    return [
        'total_books'     => $pdo->query("SELECT COUNT(*) FROM books")->fetchColumn(),
        'total_authors'   => $pdo->query("SELECT COUNT(*) FROM authors")->fetchColumn(),
        'total_categories'=> $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn(),
        'total_users'     => $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'membre'")->fetchColumn(),
        'total_reviews'   => $pdo->query("SELECT COUNT(*) FROM reviews")->fetchColumn(),
        'total_downloads' => $pdo->query("SELECT COUNT(*) FROM downloads")->fetchColumn(),
    ];
}

function get_recent_books(int $limit = 5): array {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT b.*, a.first_name, a.last_name, c.name AS category_name
        FROM books b
        JOIN authors a    ON a.id = b.author_id
        JOIN categories c ON c.id = b.category_id
        ORDER BY b.created_at DESC
        LIMIT ?
    ");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}

function get_recent_users(int $limit = 5): array {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT id, username, email, role, created_at
        FROM users
        ORDER BY created_at DESC
        LIMIT ?
    ");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}

function get_recent_reviews(int $limit = 5): array {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT r.*, b.title, b.id AS book_id, u.username
        FROM reviews r
        JOIN books b ON b.id = r.book_id
        JOIN users u ON u.id = r.user_id
        ORDER BY r.created_at DESC
        LIMIT ?
    ");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}

function change_password(int $user_id, string $new_password): bool {
    global $pdo;
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
    return $stmt->execute([$hashed_password, $user_id]);
}

function delete_account(int $user_id): bool {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    return $stmt->execute([$user_id]);
}
function update_user_profile(int $user_id, string $username, string $email, string $bio): bool {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, bio = ? WHERE id = ?");
    return $stmt->execute([$username, $email, $bio, $user_id]);
}

function save_profile_photo(int $user_id, string $photo_path): bool {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE users SET photo = ? WHERE id = ?");
    return $stmt->execute([$photo_path, $user_id]);
}
