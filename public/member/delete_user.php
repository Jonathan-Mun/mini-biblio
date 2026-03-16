<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: ../auth/login.php');
  exit; 
}
if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); 
}
require_once __DIR__ . '/../../includes/functions.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $csrf_token = $_POST['csrf_token'];
  if ($csrf_token !== $_SESSION['csrf_token']) {
    header('HTTP/1.1 400 Bad Request');
    header('Location: edit_profile.php?error=csrf_error');
    die();
  }
  delete_account($_SESSION['user_id']);
  session_destroy();
  header('Location: ../index.php');
  exit;
}

?>