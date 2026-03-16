<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /mini-biblio/public/auth/login.php');
    exit;
}
require_once __DIR__ . '/../../includes/functions.php';

$active_page   = "dashboard";
$active_onglet = "reviews";
$page_title    = "Mes avis - Bibliotheca";

$user         = get_user_by_id($_SESSION['user_id']);
$date_reg     = new DateTime(get_date_registration($_SESSION['user_id']));
$photo        = get_photo_path($_SESSION['user_id']);
$nb_favorites = count_favorites($_SESSION['user_id']);
$nb_downloads = count_downloads($_SESSION['user_id']);
$nb_reviews   = count_user_reviews($_SESSION['user_id']);
$preferences  = get_user_preferences($_SESSION['user_id']);
$reviews      = get_user_reviews($_SESSION['user_id'], 50);

// Suppression d'un avis
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    delete_user_review($_SESSION['user_id'], (int)$_GET['delete']);
    header('Location: reviews.php?success=1');
    exit;
}

function date_rev(array $rev): string {
    $date   = new DateTime($rev['created_at']);
    $months = ['janvier','février','mars','avril','mai','juin',
               'juillet','août','septembre','octobre','novembre','décembre'];
    return 'Publie le ' . $date->format('d') . ' '
         . $months[$date->format('n') - 1] . ' '
         . $date->format('Y');
}

include __DIR__ . '/../../includes/header.php';
?>

<?php include __DIR__ . '/slidebar.php'; ?>

<div class="lg:col-span-3 space-y-6">

  <div>
    <h1 class="font-display text-2xl font-bold text-ink">Mes avis</h1>
    <p class="text-ink-muted text-sm mt-1">
      <?= $nb_reviews ?> avis publie<?= $nb_reviews > 1 ? 's' : '' ?> sur la plateforme.
    </p>
  </div>

  <?php if (isset($_GET['success'])): ?>
  <div class="bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-lg flex items-center gap-2">
    <span class="font-bold">&#10003;</span> Avis supprime avec succes.
  </div>
  <?php endif; ?>

  <?php if (empty($reviews)): ?>
  <div class="bg-white border border-cream-border rounded-xl p-12 text-center">
    <p class="font-display text-lg font-semibold text-ink mb-2">Aucun avis</p>
    <p class="text-ink-muted text-sm mb-5">Vous n'avez pas encore laisse d'avis sur un livre.</p>
    <a href="/mini-biblio/public/index.php"
       class="bg-ink text-white text-sm font-semibold px-5 py-2.5 rounded-lg hover:bg-ink/80 transition-colors">
      Parcourir le catalogue
    </a>
  </div>

  <?php else: ?>
  <div class="space-y-4">
    <?php foreach ($reviews as $rev): ?>
    <div class="bg-white border border-cream-border rounded-xl p-5">

      <div class="flex items-start justify-between mb-3">
        <div>
          <a href="/mini-biblio/public/book.php?id=<?= $rev['book_id'] ?>"
             class="font-display text-base font-semibold text-ink hover:text-forest transition-colors">
            <?= htmlspecialchars($rev['title']) ?>
          </a>
          <p class="text-ink-muted text-xs mt-0.5"><?= date_rev($rev) ?></p>
        </div>

        <!-- Etoiles -->
        <div class="flex gap-0.5 flex-shrink-0">
          <?php for ($i = 1; $i <= 5; $i++): ?>
          <svg class="w-3.5 h-3.5 <?= $i <= $rev['rating'] ? 'text-amber-400' : 'text-cream-border' ?>"
               fill="currentColor" viewBox="0 0 20 20">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
          </svg>
          <?php endfor; ?>
          <span class="text-ink-muted text-xs ml-1"><?= $rev['rating'] ?>/5</span>
        </div>
      </div>

      <?php if ($rev['comment']): ?>
      <p class="text-ink-soft text-sm leading-relaxed mb-4">
        <?= nl2br(htmlspecialchars($rev['comment'])) ?>
      </p>
      <?php else: ?>
      <p class="text-ink-muted text-sm italic mb-4">Aucun commentaire.</p>
      <?php endif; ?>

      <div class="flex items-center justify-between pt-3 border-t border-cream-border">
        <a href="/mini-biblio/public/book.php?id=<?= $rev['book_id'] ?>"
           class="text-forest text-xs font-semibold hover:underline">
          Voir le livre
        </a>
        <a href="reviews.php?delete=<?= $rev['id'] ?>"
           class="text-red-600 text-xs font-semibold hover:underline"
           onclick="return confirm('Supprimer cet avis definitivement ?')">
          Supprimer
        </a>
      </div>

    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>