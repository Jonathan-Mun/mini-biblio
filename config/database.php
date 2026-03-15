<?php
require_once __DIR__ . '/db-config.php';
try {

    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $sql = "SELECT * FROM authors";
    $results = $pdo->query($sql);
    $data = $results->fetchAll(PDO::FETCH_ASSOC);
    foreach ($data as $author) {
        echo "<pre>";
        print_r($author);
        echo "</pre>";
    }
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>