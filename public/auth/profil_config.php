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

?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Configurer mon compte — Mini Bibliothèque</title>
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
    .pref-card input:checked + label {
      border-color: #2c5f3e;
      background-color: #e8f0eb;
      color: #2c5f3e;
    }
  </style>
</head>
<body class="bg-ink min-h-screen px-4 py-12">

  <div class="w-full max-w-2xl mx-auto">

    <!-- Logo -->
    <div class="text-center mb-8">
      <a href="../index.php" class="font-display text-white text-3xl tracking-wide">Bibliotheca</a>
      <p class="text-white/40 text-sm mt-2">Encore une etape avant de commencer</p>
    </div>

    <!-- Etapes -->
    <div class="flex items-center justify-center gap-2 mb-8">
      <div class="flex items-center gap-2">
        <div class="w-7 h-7 rounded-full bg-white/20 text-white text-xs font-bold flex items-center justify-center line-through opacity-40">1</div>
        <span class="text-white/40 text-xs font-medium hidden sm:block">Inscription</span>
      </div>
      <div class="w-8 h-px bg-white/20"></div>
      <div class="flex items-center gap-2">
        <div class="w-7 h-7 rounded-full bg-white text-ink text-xs font-bold flex items-center justify-center">2</div>
        <span class="text-white text-xs font-medium hidden sm:block">Mon profil</span>
      </div>
      <div class="w-8 h-px bg-white/20"></div>
      <div class="flex items-center gap-2">
        <div class="w-7 h-7 rounded-full bg-white/20 text-white/40 text-xs font-bold flex items-center justify-center">3</div>
        <span class="text-white/40 text-xs font-medium hidden sm:block">Mes preferences</span>
      </div>
    </div>

    <div class="bg-cream rounded-2xl shadow-2xl overflow-hidden">

      <!-- ══════════════════════════════════════════════════ -->
      <!-- ETAPE 2 — PROFIL                                  -->
      <!-- ══════════════════════════════════════════════════ -->
      <div class="p-8 border-b border-cream-border">
        <h2 class="font-display text-2xl font-bold text-ink mb-1">Configurez votre profil</h2>
        <p class="text-ink-muted text-sm">Ces informations seront visibles sur votre page publique.</p>
      </div>

      <form method="POST" action="setup.php" enctype="multipart/form-data" class="p-8 space-y-6">

        <!-- Photo de profil -->
        <div>
          <label class="block text-xs font-semibold text-ink-muted uppercase tracking-wider mb-3">Photo de profil</label>
          <div class="flex items-center gap-5">
            <div class="w-20 h-20 rounded-full bg-forest border-2 border-cream-border flex items-center justify-center flex-shrink-0 overflow-hidden">
              <span class="text-cream text-xl text-center leading-tight px-8"><?php echo htmlspecialchars(name_character($_SESSION['user']->getUsername())); ?></span>
            </div>
            <div>
              <label for="photo" class="cursor-pointer inline-block bg-ink text-white text-xs font-semibold px-4 py-2.5 rounded-lg hover:bg-ink/80 transition-colors">
                Choisir une photo
              </label>
              <input type="file" id="photo" name="photo" accept="image/*" class="hidden">
              <p class="text-ink-muted text-xs mt-2">JPG ou PNG — 2 Mo maximum</p>
            </div>
          </div>
        </div>

        <!-- Bio -->
        <div>
          <label class="block text-xs font-semibold text-ink-muted uppercase tracking-wider mb-2">
            Biographie <span class="normal-case font-normal text-ink-muted">(facultatif)</span>
          </label>
          <textarea name="bio" rows="3" placeholder="Presentez-vous en quelques mots..."
            class="w-full border border-cream-border rounded-lg px-4 py-3 text-sm text-ink placeholder-ink-muted/50 focus:outline-none focus:border-ink/40 bg-white transition-colors resize-none"></textarea>
          <p class="text-ink-muted text-xs mt-1.5">250 caracteres maximum</p>
        </div>

        <!-- Bouton continuer -->
        <div class="pt-2">
          <button type="submit" name="step" value="preferences"
            class="w-full bg-ink text-white text-sm font-semibold py-3 rounded-lg hover:bg-ink/80 transition-colors">
            Continuer
          </button>
          <a href="preference.php" class="block text-center text-ink-muted text-xs mt-3 hover:text-ink transition-colors">
            Passer cette etape
          </a>
        </div>
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

      </form>
