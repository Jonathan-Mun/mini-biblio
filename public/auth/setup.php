<?php
require_once __DIR__ . '/../../includes/functions.php';
include_once __DIR__ . '/../../classes/User.php';
session_start();
if (!isset($_SESSION['user'])) {
  header('Location: login.php');
  exit;
}
if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$_SESSION['user_id'] = get_id_user($_SESSION['user']->getUsername())['id'];
$user = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $csrf_token = $_POST['csrf_token'];
    if ($csrf_token !== $_SESSION['csrf_token']) {
        header('Location: profil_config.php?error=csrf_error');
        die();
    }

    $bio = trim($_POST['bio'] ?? '');
    $photo_path = null;

    // Photo uploadée
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {

        $fileName = $_FILES['photo']['name'];  // ← nom réel du fichier
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExt = ['jpg', 'jpeg', 'png'];
        $maxSize = 4 * 1024 * 1024; // 4 Mo

        if (!in_array($fileExtension, $allowedExt)) {
            header('Location: profil_config.php?error=invalid_file_type');
            die();
        }
        if ($_FILES['photo']['size'] > $maxSize) {
            header('Location: profil_config.php?error=file_too_large');
            die();
        }

        $newFileName  = 'user-' . $_SESSION['user_id'] . '.' . $fileExtension;
        $uploadDir    = __DIR__ . '/../../uploads/avatars/';
        $photo_path   = 'uploads/avatars/' . $newFileName;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $newFileName)) {
            header('Location: profil_config.php?error=upload_failed');
            die();
        }
    }
    else {
      header("Location: profil_config.php?error=photo_upload_error");
      die();
    }
    // Sauvegarde bio + photo (photo peut être null si pas uploadée)
    save_profile_preferences($_SESSION['user_id'], $photo_path, $bio);
    header('Location: preference.php');
    exit;
}
else {
    header('Location: profil_config.php');
    exit;
}

?>