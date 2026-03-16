<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /mini-biblio/public/auth/login.php');
    exit;
}
require_once __DIR__ . '/../../includes/functions.php';

$active_page   = "dashboard";
$active_onglet = "preferences";
$page_title    = "Mes preferences - Bibliotheca";

$user         = get_user_by_id($_SESSION['user_id']);
$date_reg     = new DateTime(get_date_registration($_SESSION['user_id']));
$photo        = get_photo_path($_SESSION['user_id']);
$nb_favorites = count_favorites($_SESSION['user_id']);
$nb_downloads = count_downloads($_SESSION['user_id']);
$nb_reviews   = count_user_reviews($_SESSION['user_id']);
$preferences  = get_user_preferences($_SESSION['user_id']);
$all_categories = get_all_categories();

// Ids des categories deja selectionnees
$selected_ids = array_column($preferences, 'id');

// Traitement POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categories = $_POST['categories'] ?? [];
    $categories = array_map('intval', $categories);
    $categories = array_filter($categories, fn($id) => $id > 0);
    save_user_preferences($_SESSION['user_id'], $categories);
    header('Location: preferences.php?success=1');
    exit;
}

include __DIR__ . '/../../includes/header.php';
?>

<?php include __DIR__ . '/slidebar.php'; ?>

<div class="lg:col-span-3 space-y-6">

  <div>
    <h1 class="font-display text-2xl font-bold text-ink">Mes preferences</h1>
    <p class="text-ink-muted text-sm mt-1">Choisissez les categories qui vous interessent pour personnaliser votre catalogue.</p>
  </div>

  <?php if (isset($_GET['success'])): ?>
  <div class="bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-lg flex items-center gap-2">
    <span class="font-bold">&#10003;</span> Preferences mises a jour avec succes.
  </div>
  <?php endif; ?>

  <div class="bg-white border border-cream-border rounded-xl p-7">
    <form method="POST" action="preferences.php">
      <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

      <p class="text-xs font-semibold text-ink-muted uppercase tracking-wider mb-5">
        Selectionnez une ou plusieurs categories
      </p>

      <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-6">
        <?php foreach ($all_categories as $cat): ?>
        <?php $checked = in_array($cat['id'], $selected_ids); ?>
        <div>
          <input type="checkbox"
                 id="cat-<?= $cat['id'] ?>"
                 name="categories[]"
                 value="<?= $cat['id'] ?>"
                 class="hidden peer"
                 <?= $checked ? 'checked' : '' ?>>
          <label for="cat-<?= $cat['id'] ?>"
                 class="flex flex-col items-center gap-2 p-4 border-2 rounded-xl cursor-pointer text-center transition-all
                        <?= $checked ? 'border-forest bg-forest-light' : 'border-cream-border hover:border-forest/40' ?>
                        peer-checked:border-forest peer-checked:bg-forest-light">
            <span class="text-xs font-semibold <?= $checked ? 'text-forest' : 'text-ink' ?> peer-checked:text-forest">
              <?= htmlspecialchars($cat['name']) ?>
            </span>
          </label>
        </div>
        <?php endforeach; ?>
      </div>

      <p class="text-ink-muted text-xs mb-5">
        <?= count($selected_ids) ?> categorie<?= count($selected_ids) > 1 ? 's' : '' ?> selectionnee<?= count($selected_ids) > 1 ? 's' : '' ?>.
      </p>

      <div class="flex gap-3">
        <button type="submit"
                class="bg-forest text-white text-sm font-semibold px-6 py-3 rounded-lg hover:bg-forest-hover transition-colors">
          Enregistrer les preferences
        </button>
        <a href="dashboard.php"
           class="border border-cream-border text-ink text-sm font-semibold px-6 py-3 rounded-lg hover:border-ink/40 transition-colors">
          Annuler
        </a>
      </div>

    </form>
  </div>

  <!-- Apercu des categories selectionnees -->
  <?php if (!empty($preferences)): ?>
  <div class="bg-white border border-cream-border rounded-xl p-6">
    <p class="text-xs font-semibold text-ink-muted uppercase tracking-wider mb-3">Categories actives</p>
    <div class="flex flex-wrap gap-2">
      <?php foreach ($preferences as $cat): ?>
      <span class="bg-forest-light text-forest text-xs font-semibold px-3 py-1.5 rounded-full">
        <?= htmlspecialchars($cat['name']) ?>
      </span>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

</div>

<style>
  input.peer:checked + label span { color: #2c5f3e; }
  input.peer:checked + label { border-color: #2c5f3e; background-color: #e8f0eb; }
</style>

<script>
  document.querySelectorAll('input[type="checkbox"].peer').forEach(input => {
    input.addEventListener('change', () => {
      const label = input.nextElementSibling;
      const span  = label.querySelector('span');
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

<?php include __DIR__ . '/../../includes/footer.php'; ?>