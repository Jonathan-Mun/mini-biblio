<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /mini-biblio/public/auth/login.php');
    exit;
}
require_once __DIR__ . '/../../includes/functions.php';

$active_page   = "dashboard";
$active_onglet = "dashboard";
$page_title    = "Mon tableau de bord - Bibliotheca";

$user         = get_user_by_id($_SESSION['user_id']);
$date_reg     = new DateTime(get_date_registration($_SESSION['user_id']));
$photo        = get_photo_path($_SESSION['user_id']);
$nb_favorites = count_favorites($_SESSION['user_id']);
$nb_downloads = count_downloads($_SESSION['user_id']);
$nb_reviews   = count_user_reviews($_SESSION['user_id']);
$favorites    = get_recent_favorites($_SESSION['user_id'], 3);
$downloads    = get_recent_downloads($_SESSION['user_id'], 5);
$reviews      = get_user_reviews($_SESSION['user_id'], 3);
$preferences  = get_user_preferences($_SESSION['user_id']);

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

<div class="max-w-6xl mx-auto px-6 py-10">
  <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

    <!-- ── SIDEBAR ── -->
    <aside class="lg:col-span-1 space-y-4">

      <div class="bg-white border border-cream-border rounded-xl p-5 text-center">
        <div class="w-16 h-16 rounded-full bg-ink mx-auto mb-3 overflow-hidden flex items-center justify-center">
          <?php if ($photo): ?>
            <img src="/mini-biblio/<?= htmlspecialchars($photo) ?>"
                 alt="Avatar" class="w-16 h-16 rounded-full object-cover object-center">
          <?php else: ?>
            <span class="font-display text-white text-2xl font-bold">
              <?= htmlspecialchars(name_character($user['username'])) ?>
            </span>
          <?php endif; ?>
        </div>
        <p class="font-semibold text-ink text-sm"><?= htmlspecialchars($user['username']) ?></p>
        <p class="text-ink-muted text-xs mt-0.5">Membre depuis <?= $date_reg->format("Y") ?></p>
        <div class="mt-3 pt-3 border-t border-cream-border grid grid-cols-3 gap-2 text-center">
          <div><p class="font-bold text-ink text-sm"><?= $nb_favorites ?></p><p class="text-ink-muted text-xs">Favoris</p></div>
          <div><p class="font-bold text-ink text-sm"><?= $nb_reviews ?></p><p class="text-ink-muted text-xs">Avis</p></div>
          <div><p class="font-bold text-ink text-sm"><?= $nb_downloads ?></p><p class="text-ink-muted text-xs">Telecharg.</p></div>
        </div>
      </div>

      <div class="bg-white border border-cream-border rounded-xl overflow-hidden">
        <?php
        $nav = [
          'dashboard'   => ['url' => 'dashboard.php',   'label' => 'Tableau de bord'],
          'favorites'   => ['url' => 'favorites.php',   'label' => 'Mes favoris'],
          'reviews'     => ['url' => 'reviews.php',     'label' => 'Mes avis'],
          'downloads'   => ['url' => 'downloads.php',   'label' => 'Telechargements'],
          'preferences' => ['url' => 'preferences.php', 'label' => 'Preferences'],
          'profile'     => ['url' => 'profile.php',     'label' => 'Mon profil'],
        ];
        $first = true;
        foreach ($nav as $key => $item):
          $active  = ($active_onglet ?? '') === $key;
          $border  = $first ? '' : 'border-t border-cream-border';
          $classes = $active
            ? 'bg-ink text-white font-semibold'
            : 'text-ink-soft hover:bg-cream font-medium';
          $first = false;
        ?>
        <a href="<?= $item['url'] ?>"
           class="flex items-center gap-3 px-4 py-3 text-sm transition-colors <?= $border ?> <?= $classes ?>">
          <?= $item['label'] ?>
          <?php if ($key === 'favorites' && $nb_favorites > 0): ?>
            <span class="ml-auto bg-cream-dark text-ink-muted text-xs font-bold px-2 py-0.5 rounded-full"><?= $nb_favorites ?></span>
          <?php elseif ($key === 'reviews' && $nb_reviews > 0): ?>
            <span class="ml-auto bg-cream-dark text-ink-muted text-xs font-bold px-2 py-0.5 rounded-full"><?= $nb_reviews ?></span>
          <?php elseif ($key === 'downloads' && $nb_downloads > 0): ?>
            <span class="ml-auto bg-cream-dark text-ink-muted text-xs font-bold px-2 py-0.5 rounded-full"><?= $nb_downloads ?></span>
          <?php endif; ?>
        </a>
        <?php endforeach; ?>
      </div>

      <?php if (!empty($preferences)): ?>
      <div class="bg-white border border-cream-border rounded-xl p-5">
        <p class="text-xs font-semibold text-ink-muted uppercase tracking-wider mb-3">Mes categories</p>
        <div class="flex flex-wrap gap-2">
          <?php foreach ($preferences as $cat): ?>
          <span class="bg-forest-light text-forest text-xs font-semibold px-3 py-1.5 rounded-full">
            <?= htmlspecialchars($cat['name']) ?>
          </span>
          <?php endforeach; ?>
          <a href="preferences.php" class="text-ink-muted text-xs font-medium hover:text-ink transition-colors">+ Modifier</a>
        </div>
      </div>
      <?php endif; ?>

    </aside>

    <!-- ── CONTENU PRINCIPAL ── -->
    <div class="lg:col-span-3 space-y-8">

      <!-- Bonjour -->
      <div>
        <h1 class="font-display text-2xl font-bold text-ink">Bonjour, <?= htmlspecialchars($user['username']) ?></h1>
        <p class="text-ink-muted text-sm mt-1">Voici un apercu de votre activite sur Bibliotheca.</p>
      </div>

      <!-- A lire plus tard -->
      <div>
        <div class="flex items-center justify-between mb-4">
          <div>
            <h2 class="font-display text-lg font-semibold text-ink">A lire plus tard</h2>
            <p class="text-ink-muted text-xs mt-0.5"><?= $nb_favorites ?> livres dans votre liste</p>
          </div>
          <?php if ($nb_favorites > 0): ?>
          <a href="favorites.php" class="text-forest text-sm font-semibold hover:underline">Voir tout</a>
          <?php endif; ?>
        </div>

        <?php if (empty($favorites)): ?>
        <div class="bg-white border border-cream-border rounded-xl p-10 text-center">
          <p class="font-display text-base font-semibold text-ink mb-2">Aucun favori</p>
          <p class="text-ink-muted text-sm mb-4">Vous n'avez pas encore ajoute de livre a votre liste.</p>
          <a href="/mini-biblio/public/index.php"
             class="bg-ink text-white text-sm font-semibold px-5 py-2.5 rounded-lg hover:bg-ink/80 transition-colors">
            Parcourir le catalogue
          </a>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
          <?php foreach ($favorites as $fav): ?>
          <a href="/mini-biblio/public/book.php?id=<?= $fav['id'] ?>"
             class="bg-white border border-cream-border rounded-xl overflow-hidden card-hover block">
            <div class="h-28 bg-gradient-to-br from-emerald-50 to-teal-100 flex items-end p-3">
              <span class="text-xs text-ink/40 font-semibold uppercase tracking-wide">
                <?= htmlspecialchars($fav['category_name']) ?>
              </span>
            </div>
            <div class="p-3">
              <h3 class="font-display text-xs font-semibold text-ink leading-snug mb-1">
                <?= htmlspecialchars($fav['title']) ?>
              </h3>
              <p class="text-ink-muted text-xs">
                <?= htmlspecialchars($fav['first_name'] . ' ' . $fav['last_name']) ?>
              </p>
            </div>
          </a>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>

      <!-- Derniers telechargements -->
      <div>
        <div class="flex items-center justify-between mb-4">
          <div>
            <h2 class="font-display text-lg font-semibold text-ink">Derniers telechargements</h2>
            <p class="text-ink-muted text-xs mt-0.5"><?= $nb_downloads ?> livres telecharges au total</p>
          </div>
          <?php if ($nb_downloads > 0): ?>
          <a href="downloads.php" class="text-forest text-sm font-semibold hover:underline">Voir tout</a>
          <?php endif; ?>
        </div>

        <?php if (empty($downloads)): ?>
        <div class="bg-white border border-cream-border rounded-xl p-10 text-center">
          <p class="font-display text-base font-semibold text-ink mb-2">Aucun telechargement</p>
          <p class="text-ink-muted text-sm mb-4">Vous n'avez pas encore telecharge de livre.</p>
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
                <th class="text-left px-5 py-3 text-xs font-semibold text-ink-muted uppercase tracking-wider hidden sm:table-cell">Categorie</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-ink-muted uppercase tracking-wider">Date</th>
                <th class="text-right px-5 py-3 text-xs font-semibold text-ink-muted uppercase tracking-wider">Action</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-cream-border">
              <?php foreach ($downloads as $dl): ?>
              <tr class="hover:bg-cream/50 transition-colors">
                <td class="px-5 py-3 font-medium text-ink"><?= htmlspecialchars($dl['title']) ?></td>
                <td class="px-5 py-3 hidden sm:table-cell">
                  <span class="bg-forest-light text-forest text-xs font-semibold px-2.5 py-1 rounded-full">
                    <?= htmlspecialchars($dl['category_name']) ?>
                  </span>
                </td>
                <td class="px-5 py-3 text-ink-muted text-xs">
                  <?= date('d M Y', strtotime($dl['downloaded_at'])) ?>
                </td>
                <td class="px-5 py-3 text-right">
                  <a href="/mini-biblio/public/download.php?id=<?= $dl['id'] ?>"
                     class="text-forest text-xs font-semibold hover:underline">
                    Retelecharger
                  </a>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php endif; ?>
      </div>

      <!-- Mes avis -->
      <div>
        <div class="flex items-center justify-between mb-4">
          <div>
            <h2 class="font-display text-lg font-semibold text-ink">Mes avis</h2>
            <p class="text-ink-muted text-xs mt-0.5"><?= $nb_reviews ?> avis publies</p>
          </div>
          <?php if ($nb_reviews > 0): ?>
          <a href="reviews.php" class="text-forest text-sm font-semibold hover:underline">Voir tout</a>
          <?php endif; ?>
        </div>

        <?php if (empty($reviews)): ?>
        <div class="bg-white border border-cream-border rounded-xl p-10 text-center">
          <p class="font-display text-base font-semibold text-ink mb-2">Aucun avis</p>
          <p class="text-ink-muted text-sm">Vous n'avez pas encore laisse d'avis sur un livre.</p>
        </div>
        <?php else: ?>
        <div class="space-y-3">
          <?php foreach ($reviews as $rev): ?>
          <div class="bg-white border border-cream-border rounded-xl p-5">
            <div class="flex items-start justify-between mb-2">
              <a href="/mini-biblio/public/book.php?id=<?= $rev['book_id'] ?>"
                 class="font-display text-sm font-semibold text-ink hover:text-forest transition-colors">
                <?= htmlspecialchars($rev['title']) ?>
              </a>
              <div class="flex gap-0.5 flex-shrink-0">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                <svg class="w-3 h-3 <?= $i <= $rev['rating'] ? 'text-amber-400' : 'text-cream-border' ?>"
                     fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                <?php endfor; ?>
              </div>
            </div>
            <p class="text-ink-soft text-sm leading-relaxed"><?= htmlspecialchars($rev['comment']) ?></p>
            <div class="flex items-center justify-between mt-3">
              <p class="text-ink-muted text-xs"><?= date_rev($rev) ?></p>
              <a href="reviews.php?delete=<?= $rev['id'] ?>"
                 class="text-red-600 text-xs font-semibold hover:underline"
                 onclick="return confirm('Supprimer cet avis ?')">
                Supprimer
              </a>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>

      <!-- Preferences -->
      <div>
        <div class="flex items-center justify-between mb-4">
          <div>
            <h2 class="font-display text-lg font-semibold text-ink">Mes preferences</h2>
            <p class="text-ink-muted text-xs mt-0.5">Categories selectionnees pour votre catalogue</p>
          </div>
          <a href="preferences.php" class="text-forest text-sm font-semibold hover:underline">Modifier</a>
        </div>

        <div class="bg-white border border-cream-border rounded-xl p-5">
          <?php if (empty($preferences)): ?>
          <p class="text-ink-muted text-sm text-center py-4">Aucune preference selectionnee.</p>
          <div class="text-center mt-2">
            <a href="preferences.php"
               class="bg-ink text-white text-xs font-semibold px-4 py-2 rounded-lg hover:bg-ink/80 transition-colors">
              Choisir mes categories
            </a>
          </div>
          <?php else: ?>
          <div class="flex flex-wrap gap-2">
            <?php foreach ($preferences as $cat): ?>
            <span class="bg-forest-light text-forest text-xs font-semibold px-3 py-1.5 rounded-full">
              <?= htmlspecialchars($cat['name']) ?>
            </span>
            <?php endforeach; ?>
            <a href="preferences.php"
               class="border border-dashed border-cream-border text-ink-muted text-xs font-semibold px-3 py-1.5 rounded-full hover:border-ink/40 hover:text-ink transition-colors">
              + Modifier
            </a>
          </div>
          <?php endif; ?>
        </div>
      </div>

    </div>
  </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>