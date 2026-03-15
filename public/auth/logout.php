<?php
session_start();

// supprimer toutes les variables de session
$_SESSION = [];

// détruire la session
session_destroy();

// redirection
header("Location: login.php");
exit;