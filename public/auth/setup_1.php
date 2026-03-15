<?php
require_once __DIR__ . '/../../classes/User.php';
require_once __DIR__ . '/../../includes/functions.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $csrf_token = $_POST['csrf_token'] ?? '';
    if ($csrf_token !== $_SESSION['csrf_token']) {
        header('Location: preference.php?error=csrf_error');
        die();
    }

    $categories = $_POST['categories'] ?? [];


    if (!empty($categories)) {
        // Nettoyer les ids — s'assurer que ce sont bien des entiers
        $categories = array_map('intval', $categories);
        $categories = array_filter($categories, fn($id) => $id > 0);

        save_user_preferences($_SESSION['user_id'], $categories);
    }

    header('Location: ../index.php');
    exit;
}