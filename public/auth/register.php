<?php
session_start();
if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
require_once __DIR__ . '/../../includes/functions.php';
include_once __DIR__ . '/../../classes/User.php';
$error = '';
if (isset($_GET['error'])) {
  $error = error_message($_GET['error']);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $csrf_token = $_POST['csrf_token'];
  if ($csrf_token !== $_SESSION['csrf_token']) {
    header('HTTP/1.1 400 Bad Request');
    header('Location: login.php?error=csrf_error');
    die();
  }
  if(email_valid($_POST['email']) === false) {
    header('Location: register.php?error=email_invalid');
    die();
  }
  $email = $_POST['email'];
  if(email_exists($_POST['email'])) {
    header('Location: register.php?error=email_exists');
    die();
  }
  if(verify_username($_POST['username'])) {
    header('Location: register.php?error=user_exists');
    die();
  }
  $username = $_POST['username'];
  $password = $_POST['password'];
  $confiem_password = $_POST['password_confirm'];
  if ($password !== $confiem_password) {
    header('Location: register.php?error=password_mismatch');
    die();
  }
  $user = new User(null, $username, $email, password_hash($password, PASSWORD_DEFAULT));
  $_SESSION['user'] = $user;
  $_SESSION['username'] = $username;
  create_user($user);
  header('Location: profil_config.php');
  exit;
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inscription — Mini Bibliothèque</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { display: ['Georgia','Cambria','serif'] },
          colors: {
            ink: { DEFAULT:'#1c1c1a', soft:'#4a4a46', muted:'#8a8a84' },
            cream: { DEFAULT:'#f7f4ed', dark:'#ece8df', border:'#ddd9ce' },
            forest: { DEFAULT:'#2c5f3e', light:'#e8f0eb', hover:'#234d32' },
          }
        }
      }
    }
  </script>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'DM Sans', system-ui, sans-serif; }
    .font-display { font-family: 'Playfair Display', Georgia, serif; }
  </style>
</head>
<body class="bg-ink min-h-screen flex items-center justify-center px-4 py-10">

  <div class="w-full max-w-md">
    <div class="text-center mb-8">
      <a href="../index.php" class="font-display text-white text-3xl tracking-wide">Bibliotheca</a>
      <p class="text-white/40 text-sm mt-2">Rejoignez la communaute</p>
    </div>

    <div class="bg-cream rounded-2xl p-8 shadow-2xl">

      <div class="flex rounded-lg overflow-hidden border border-cream-border mb-7">
        <a href="login.php" class="flex-1 text-center py-2.5 text-sm font-semibold text-ink-muted hover:text-ink transition-colors">Connexion</a>
        <a href="register.php" class="flex-1 text-center py-2.5 text-sm font-semibold bg-ink text-white">S'inscrire</a>
      </div>
      <?php if (!empty($error)): ?>
      <div class="bg-amber-50 border border-amber-200 text-amber-800 text-sm px-4 py-3 rounded-lg mb-5">
        <?= htmlspecialchars($error) ?>
      </div>
      <?php endif; ?>
      <form method="POST" action="register.php" class="space-y-4">
        <div>
          <label class="block text-xs font-semibold text-ink-muted uppercase tracking-wider mb-2">Nom d'utilisateur</label>
          <input type="text" name="username" placeholder="johnsmith"
            class="w-full border border-cream-border rounded-lg px-4 py-3 text-sm text-ink placeholder-ink-muted/50 focus:outline-none focus:border-ink/50 bg-white transition-colors">
        </div>
        <div>
          <label class="block text-xs font-semibold text-ink-muted uppercase tracking-wider mb-2">Email</label>
          <input type="email" name="email" placeholder="vous@exemple.fr"
            class="w-full border border-cream-border rounded-lg px-4 py-3 text-sm text-ink placeholder-ink-muted/50 focus:outline-none focus:border-ink/50 bg-white transition-colors">
        </div>
        <div>
          <label class="block text-xs font-semibold text-ink-muted uppercase tracking-wider mb-2">Mot de passe</label>
          <input type="password" name="password" placeholder="••••••••"
            class="w-full border border-cream-border rounded-lg px-4 py-3 text-sm text-ink placeholder-ink-muted/50 focus:outline-none focus:border-ink/50 bg-white transition-colors">
        </div>
        <div>
          <label class="block text-xs font-semibold text-ink-muted uppercase tracking-wider mb-2">Confirmer le mot de passe</label>
          <input type="password" name="password_confirm" placeholder="••••••••" minlength="8"
            class="w-full border border-cream-border rounded-lg px-4 py-3 text-sm text-ink placeholder-ink-muted/50 focus:outline-none focus:border-ink/50 bg-white transition-colors">
        </div>
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <button type="submit" class="w-full bg-forest text-white text-sm font-semibold py-3 rounded-lg hover:bg-forest-hover transition-colors mt-2">
          Creer mon compte
        </button>
      </form>

      <p class="text-center text-ink-muted text-sm mt-6">
        Deja un compte ?
        <a href="login.php" class="text-forest font-semibold hover:underline">Se connecter</a>
      </p>
    </div>

    <p class="text-center text-white/30 text-xs mt-6">
      <a href="../index.php" class="hover:text-white/60 transition-colors">Retour au catalogue</a>
    </p>
  </div>

</body>
</html>
