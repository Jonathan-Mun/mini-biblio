<?php
session_start();
require_once __DIR__ . '/../../includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

$user = get_user_by_id($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if($new_password == $confirm_password) {
        if(password_verify($old_password, $user['password'])) {
            if(change_password($user['id'], $new_password)) {
                header('Location: edit_profil.php?success=1');
                exit();
            } else {
                header('Location: edit_profil.php?success=2');
                exit();
            }
        } else {
            header('Location: edit_profil.php?success=0');
            exit();
        }
    } 
    else {
        header('Location: edit_profil.php?success=3');
        exit();
    }

    if (password_verify($old_password, $user['password'])) {
        if ($new_password === $confirm_password) {
            if (change_password($user['id'], $new_password)) {
                $success_message = "Le mot de passe a été modifié avec succès.";
            } else {
                $error_message = "Une erreur s'est produite lors de la modification du mot de passe.";
            }
        } else {
            $error_message = "Les nouveaux mots de passe ne correspondent pas.";
        }
    } else {
        $error_message = "Le mot de passe actuel est incorrect.";
    }
}
?>