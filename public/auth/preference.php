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
        <div class="w-7 h-7 rounded-full bg-white/20 text-white/40 text-xs font-bold flex items-center justify-center">2</div>
        <span class="text-white text-xs font-medium hidden sm:block">Mon profil</span>
      </div>
      <div class="w-8 h-px bg-white/20"></div>
      <div class="flex items-center gap-2">
        <div class="w-7 h-7 rounded-full bg-white text-ink text-xs font-bold flex items-center justify-center">3</div>
        <span class="text-white/40 text-xs font-medium hidden sm:block">Mes preferences</span>
      </div>
    </div>

    <div class="bg-cream rounded-2xl shadow-2xl overflow-hidden">
      <div class="border-t-2 border-dashed border-cream-border">

        <div class="p-8 border-b border-cream-border">
          <h2 class="font-display text-2xl font-bold text-ink mb-1">Vos centres d'interet</h2>
          <p class="text-ink-muted text-sm">Selectionnez les categories qui vous interessent. Votre catalogue sera personnalise en fonction.</p>
        </div>

        <form method="POST" action="setup_1.php" class="p-8">

          <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-8">

            <?php
            $categories = get_all_categories();
            foreach ($categories as $cat):
            ?>
            <div class="pref-card">
              <input type="checkbox" name="categories[]" value="<?= $cat['id'] ?>"
                id="cat-<?= $cat['id'] ?>" class="hidden">
              <label for="cat-<?= $cat['id'] ?>"
                class="flex flex-col items-center gap-2 p-4 border-2 border-cream-border rounded-xl cursor-pointer hover:border-forest/40 transition-all text-center">
                <span class="text-xs font-semibold text-ink"><?= $cat['name'] ?></span>
              </label>
            </div>
            <?php endforeach; ?>
          </div>

          <p class="text-ink-muted text-xs mb-5">2 categories selectionnees — vous pouvez en choisir autant que vous voulez.</p>

          <button type="submit"
            class="w-full bg-forest text-white text-sm font-semibold py-3 rounded-lg hover:bg-forest-hover transition-colors">
            Terminer la configuration
          </button>
          <a href="../index.php" class="block text-center text-ink-muted text-xs mt-3 hover:text-ink transition-colors">
            Passer — je configurerai plus tard
          </a>
          <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

        </form>
      </div>

    </div>

    <p class="text-center text-white/30 text-xs mt-6">
      <a href="../index.php" class="hover:text-white/60 transition-colors">Retour au catalogue</a>
    </p>
  </div>

  <script>
    // Toggle visuel des cartes de categories
    document.querySelectorAll('.pref-card input').forEach(input => {
      input.addEventListener('change', () => {
        const label = input.nextElementSibling;
        const span  = label.querySelector('span:last-child');
        if (input.checked) {
          label.classList.add('border-forest', 'bg-forest-light');
          label.classList.remove('border-cream-border');
          span.classList.add('text-forest');
          span.classList.remove('text-ink');
        } else {
          label.classList.remove('border-forest', 'bg-forest-light');
          label.classList.add('border-cream-border');
          span.classList.remove('text-forest');
          span.classList.add('text-ink');
        }
      });
    });
  </script>

</body>
</html>
