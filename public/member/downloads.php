<?php
session_start();
require_once __DIR__ . '/../../includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /mini-biblio/public/auth/login.php");
    exit();
}

$active_page   = "dashboard";
$active_onglet = "downloads";
$page_title    = "Mes telechargements - Bibliotheca";

$user         = get_user_by_id($_SESSION['user_id']);
$date_reg     = new DateTime(get_date_registration($_SESSION['user_id']));
$photo        = get_photo_path($_SESSION['user_id']);
$nb_favorites = count_favorites($_SESSION['user_id']);
$nb_downloads = count_downloads($_SESSION['user_id']);
$nb_reviews   = count_user_reviews($_SESSION['user_id']);
$preferences  = get_user_preferences($_SESSION['user_id']);
$downloads    = get_recent_downloads($_SESSION['user_id'], 50);

include __DIR__ . '/../../includes/header.php';
?>

<?php include __DIR__ . '/slidebar.php'; ?>

<div class="lg:col-span-3 space-y-6">

  <div class="flex items-center justify-between">
    <div>
      <h1 class="font-display text-2xl font-bold text-ink">Mes telechargements</h1>
      <p class="text-ink-muted text-sm mt-1">
        <?= $nb_downloads ?> livre<?= $nb_downloads > 1 ? 's' : '' ?> telecharge<?= $nb_downloads > 1 ? 's' : '' ?> au total.
      </p>
    </div>
    <a href="/mini-biblio/public/index.php"
       class="bg-ink text-white text-sm font-semibold px-5 py-2.5 rounded-lg hover:bg-ink/80 transition-colors">
      Decouvrir des livres
    </a>
  </div>

  <?php if (empty($downloads)): ?>
    <div class="bg-white border border-cream-border rounded-xl p-12 text-center">
      <p class="font-display text-lg font-semibold text-ink mb-2">Aucun telechargement</p>
      <p class="text-ink-muted text-sm mb-5">Vous n'avez pas encore telecharge de livre.</p>
      <a href="/mini-biblio/public/index.php"
         class="bg-ink text-white text-sm font-semibold px-5 py-2.5 rounded-lg hover:bg-ink/80 transition-colors">
        Parcourir le catalogue
      </a>
    </div>

  <?php else: ?>

    <div class="bg-white border border-cream-border rounded-xl overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-cream border-b border-cream-border">
          <tr>
            <th class="text-left px-5 py-3 text-xs font-semibold text-ink-muted uppercase tracking-wider">Livre</th>
            <th class="text-left px-5 py-3 text-xs font-semibold text-ink-muted uppercase tracking-wider hidden sm:table-cell">Auteur</th>
            <th class="text-left px-5 py-3 text-xs font-semibold text-ink-muted uppercase tracking-wider hidden sm:table-cell">Categorie</th>
            <th class="text-left px-5 py-3 text-xs font-semibold text-ink-muted uppercase tracking-wider">Date</th>
            <th class="text-right px-5 py-3 text-xs font-semibold text-ink-muted uppercase tracking-wider">Action</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-cream-border">
          <?php foreach ($downloads as $dl): ?>
          <tr class="hover:bg-cream/50 transition-colors">
            <td class="px-5 py-4 font-medium text-ink">
              <a href="/mini-biblio/public/book.php?id=<?= $dl['id'] ?>"
                 class="hover:text-forest transition-colors">
                <?= htmlspecialchars($dl['title']) ?>
              </a>
            </td>
            <td class="px-5 py-4 text-ink-soft hidden sm:table-cell">
              <?= htmlspecialchars($dl['first_name'] . ' ' . $dl['last_name']) ?>
            </td>
            <td class="px-5 py-4 hidden sm:table-cell">
              <span class="bg-forest-light text-forest text-xs font-semibold px-2.5 py-1 rounded-full">
                <?= htmlspecialchars($dl['category_name']) ?>
              </span>
            </td>
            <td class="px-5 py-4 text-ink-muted text-xs">
              <?= date('d M Y', strtotime($dl['downloaded_at'])) ?>
            </td>
            <td class="px-5 py-4 text-right">
              <?php if ($dl['pdf_path']): ?>
                <a href="/mini-biblio/public/download.php?id=<?= $dl['id'] ?>"
                   class="text-forest text-xs font-semibold hover:underline">
                  Retelecharger
                </a>
              <?php else: ?>
                <span class="text-ink-muted text-xs">PDF indisponible</span>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

  <?php endif; ?>

</div>