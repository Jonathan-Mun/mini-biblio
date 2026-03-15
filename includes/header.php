<?php
// header.php est inclus dans toutes les pages
// La variable $active_page est définie dans chaque page pour surligner le bon lien
// ex: $active_page = 'catalogue';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $page_title ?? 'Bibliotheca' ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { display: ['Georgia','Cambria','serif'] },
          colors: {
            ink:    { DEFAULT:'#1c1c1a', soft:'#4a4a46', muted:'#8a8a84' },
            cream:  { DEFAULT:'#f7f4ed', dark:'#ece8df', border:'#ddd9ce' },
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
    .card-hover { transition: transform 200ms ease, box-shadow 200ms ease; }
    .card-hover:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(28,28,26,.1); }
  </style>
</head>
<body class="bg-cream text-ink min-h-screen">

<nav class="bg-ink sticky top-0 z-50">
  <div class="max-w-6xl mx-auto px-6 h-14 flex items-center justify-between">
    <a href="/mini-biblio/public/index.php" class="font-display text-white text-xl tracking-wide">Bibliotheca</a>
    <div class="flex items-center gap-1">
      <a href="/mini-biblio/public/index.php"
         class="text-sm font-medium px-4 py-2 transition-colors <?= ($active_page ?? '') === 'catalogue' ? 'text-white border-b-2 border-white' : 'text-white/60 hover:text-white' ?>">
        Catalogue
      </a>
      <a href="/mini-biblio/public/search.php"
         class="text-sm font-medium px-4 py-2 transition-colors <?= ($active_page ?? '') === 'search' ? 'text-white border-b-2 border-white' : 'text-white/60 hover:text-white' ?>">
        Recherche
      </a>
      <?php if (isset($_SESSION['user_id'])): ?>
      <a href="/mini-biblio/public/member/dashboard.php"
         class="text-sm font-medium px-4 py-2 transition-colors <?= ($active_page ?? '') === 'dashboard' ? 'text-white border-b-2 border-white' : 'text-white/60 hover:text-white' ?>">
        Mon espace
      </a>
      <?php endif; ?>
    </div>
    <div class="flex items-center gap-3">
      <?php if (isset($_SESSION['user_id'])): ?>
        <span class="text-white/60 text-sm">
          Bonjour, <span class="text-white font-semibold"><?= htmlspecialchars($_SESSION['username'] ?? '') ?></span>
        </span>
        <a href="/mini-biblio/public/auth/logout.php"
           class="bg-white/10 text-white text-sm font-semibold px-4 py-1.5 rounded hover:bg-white/20 transition-colors">
          Deconnexion
        </a>
      <?php else: ?>
        <a href="/mini-biblio/public/auth/login.php"
           class="text-white/70 text-sm hover:text-white transition-colors">
          Connexion
        </a>
        <a href="/mini-biblio/public/auth/register.php"
           class="bg-white text-ink text-sm font-semibold px-4 py-1.5 rounded hover:bg-cream transition-colors">
          S'inscrire
        </a>
      <?php endif; ?>
    </div>
  </div>
</nav>