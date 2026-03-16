<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: ../auth/login.php');
  exit;
}
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../classes/User.php';

$active_page = "dashboard";
$page_title = "Mon tableau de bord - Bibliotheca";

$user          = get_user_by_id($_SESSION['user_id']);
$date_reg      = new DateTime(get_date_registration($_SESSION['user_id']));
$photo         = get_photo_path($_SESSION['user_id']);
$nb_favorites  = count_favorites($_SESSION['user_id']);
$nb_downloads  = count_downloads($_SESSION['user_id']);
$nb_reviews    = count_user_reviews($_SESSION['user_id']);
$favorites     = get_recent_favorites($_SESSION['user_id'], 3);
$Allfavorites     = get_all_favorites($_SESSION['user_id']);
$downloads     = get_recent_downloads($_SESSION['user_id'], 5);
$reviews       = get_user_reviews($_SESSION['user_id'], 3);
$preferences   = get_user_preferences($_SESSION['user_id']);

function date_rev($rev){
    $date = new DateTime($rev['created_at']);
    $mounths = ['janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];
    return "Publié le ". $date->format('d') . ' ' . $mounths[$date->format('n') - 1] . ' ' . $date->format('Y');
}


include __DIR__ . '/../../includes/header.php';

?>
  <div class="max-w-6xl mx-auto px-6 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
      <!-- ── SIDEBAR ── -->
      <?php include __DIR__ . '/slider.php'; ?>

        <!-- Navigation -->
        <div class="bg-white border border-cream-border rounded-xl overflow-hidden">
          <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 bg-ink text-white text-sm font-semibold">
            <span>Tableau de bord</span>
          </a>
          <a href="favorites.php" class="flex items-center gap-3 px-4 py-3 text-ink-soft text-sm font-medium hover:bg-cream transition-colors border-t border-cream-border">
            <span>Mes favoris</span>
            <span class="ml-auto bg-cream-dark text-ink-muted text-xs font-bold px-2 py-0.5 rounded-full"><?php echo $nb_favorites; ?></span>
          </a>
          <a href="reviews.php" class="flex items-center gap-3 px-4 py-3 text-ink-soft text-sm font-medium hover:bg-cream transition-colors border-t border-cream-border">
            <span>Mes avis</span>
            <span class="ml-auto bg-cream-dark text-ink-muted text-xs font-bold px-2 py-0.5 rounded-full"><?php echo $nb_reviews; ?></span>
          </a>
          <a href="downloads.php" class="flex items-center gap-3 px-4 py-3 text-ink-soft text-sm font-medium hover:bg-cream transition-colors border-t border-cream-border">
            <span>Telechargements</span>
            <span class="ml-auto bg-cream-dark text-ink-muted text-xs font-bold px-2 py-0.5 rounded-full"><?php echo $nb_downloads; ?></span>
          </a>
          <a href="preferences.php" class="flex items-center gap-3 px-4 py-3 text-ink-soft text-sm font-medium hover:bg-cream transition-colors border-t border-cream-border">
            <span>Mes preferences</span>
          </a>
          <a href="profile.php" class="flex items-center gap-3 px-4 py-3 text-ink-soft text-sm font-medium hover:bg-cream transition-colors border-t border-cream-border">
            <span>Mon profil</span>
          </a>
        </div>

        <!-- Mes categories -->
        <div class="bg-white border border-cream-border rounded-xl p-5">
          <p class="text-xs font-semibold text-ink-muted uppercase tracking-wider mb-3">Mes categories</p>
          <div class="flex flex-wrap gap-2">
            <?php foreach ($preferences as $cat): ?>
            <span class="bg-forest-light text-forest text-xs font-semibold px-3 py-1.5 rounded-full"><?php echo htmlspecialchars($cat['name']) ?></span>
            <?php endforeach; ?>
            <a href="preferences.php" class="text-ink-muted text-xs font-medium hover:text-ink transition-colors">+ Modifier</a>
          </div>
        </div>

      </aside>

      <!-- ── CONTENU PRINCIPAL ── -->
      <div class="lg:col-span-3 space-y-8">

        <!-- Bonjour -->
        <div>
          <h1 class="font-display text-2xl font-bold text-ink">Bonjour, <?php echo htmlspecialchars($user['username']) ?></h1>
          <p class="text-ink-muted text-sm mt-1">Voici un apercu de votre activite sur Bibliotheca.</p>
        </div>

        <!-- Livres enregistres / a lire plus tard -->
        <div>
          <div class="flex items-center justify-between mb-4">
            <div>
              <h2 class="font-display text-lg font-semibold text-ink">A lire plus tard</h2>
              <p class="text-ink-muted text-xs mt-0.5"><?php echo $nb_favorites; ?> livres dans votre liste</p>
            </div>
            <a href="favorites.php" class="text-forest text-sm font-semibold hover:underline">Voir tout</a>
          </div>

          <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <?php foreach ($favorites as $fav): ?>
            <a href="../book.php?id=<?php echo $fav['id'] ?>" class="bg-white border border-cream-border rounded-xl overflow-hidden card-hover block">
              <div class="h-28 bg-gradient-to-br from-emerald-50 to-teal-100 flex items-end p-3">
                <span class="text-xs text-ink/40 font-semibold uppercase tracking-wide"><?php echo htmlspecialchars($fav['category_name']) ?></span>
              </div>
              <div class="p-3">
                <h3 class="font-display text-xs font-semibold text-ink leading-snug mb-1"><?php echo htmlspecialchars($fav['title']) ?></h3>
                <p class="text-ink-muted text-xs"><?php echo htmlspecialchars($fav['first_name'] . ' ' . $fav['last_name']) ?></p>
              </div>
            </a>
            <?php endforeach; ?>
        </div>
        </div> 

        <!-- Derniers telechargements -->
        <div>
          <div class="flex items-center justify-between mb-4">
            <div>
              <h2 class="font-display text-lg font-semibold text-ink">Derniers telechargements</h2>
              <p class="text-ink-muted text-xs mt-0.5"><?php echo $nb_downloads; ?> livres telecharges au total</p>
            </div>
            <a href="downloads.php" class="text-forest text-sm font-semibold hover:underline">Voir tout</a>
          </div>

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
                  <td class="px-5 py-3 font-medium text-ink"><?php echo htmlspecialchars($dl['title']) ?></td>
                  <td class="px-5 py-3 hidden sm:table-cell">
                    <span class="bg-forest-light text-forest text-xs font-semibold px-2.5 py-1 rounded-full"><?php echo htmlspecialchars($dl['category_name']) ?></span>
                  </td>
                  <td class="px-5 py-3 text-ink-muted text-xs"><?php echo htmlspecialchars(date('d M Y', strtotime($dl['downloaded_at']))) ?></td>
                  <td class="px-5 py-3 text-right">
                    <a href="../download.php?id=<?php echo $dl['id'] ?>" class="text-forest text-xs font-semibold hover:underline">Retelecharger</a>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Mes avis -->
        <div>
          <div class="flex items-center justify-between mb-4">
            <div>
              <h2 class="font-display text-lg font-semibold text-ink">Mes avis</h2>
              <p class="text-ink-muted text-xs mt-0.5"><?php echo $nb_reviews; ?> avis publies</p>
            </div>
            <a href="reviews.php" class="text-forest text-sm font-semibold hover:underline">Voir tout</a>
          </div>

          <div class="space-y-3">
            <?php foreach ($reviews as $rev): ?>
            <div class="bg-white border border-cream-border rounded-xl p-5">
              <div class="flex items-start justify-between mb-2">
                <a href="../book.php?id=<?php echo $rev['book_id'] ?>" class="font-display text-sm font-semibold text-ink hover:text-forest transition-colors"><?php echo htmlspecialchars($rev['title']) ?></a>
                <div class="flex gap-0.5 flex-shrink-0">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                    <svg class="w-3 h-3 <?= $i <= $rev['rating'] ? 'text-amber-400' : 'text-cream-border' ?>">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                  </svg>
                  <?php endfor; ?>
                </div>
              </div>
              <p class="text-ink-soft text-sm leading-relaxed"><?php echo htmlspecialchars($rev['comment']) ?></p>
              <div class="flex items-center justify-between mt-3">
                <p class="text-ink-muted text-xs"><?php echo date_rev($rev); ?></p>
                <a href="reviews.php?delete=<?php echo $rev['id'] ?>" class="text-red-600 text-xs font-semibold hover:underline" onclick="return confirm('Supprimer cet avis ?')">Supprimer</a>
              </div>
            </div>
            <?php endforeach; ?>

          </div>
        </div>

        <!-- Modifier les preferences -->
        <div>
          <div class="flex items-center justify-between mb-4">
            <div>
              <h2 class="font-display text-lg font-semibold text-ink">Mes preferences</h2>
              <p class="text-ink-muted text-xs mt-0.5">Categories selectionnees pour votre catalogue personnalise</p>
            </div>
            <a href="../auth/preferences.php" class="text-forest text-sm font-semibold hover:underline">Modifier</a>
          </div>

          <div class="bg-white border border-cream-border rounded-xl p-5">
            <div class="flex flex-wrap gap-2">
                <?php foreach ($preferences as $cat): ?>
              <span class="bg-forest-light text-forest text-xs font-semibold px-3 py-1.5 rounded-full"><?php echo htmlspecialchars($cat['name']) ?></span>
              <?php endforeach; ?>
              <a href="../auth/preferences.php" class="border border-dashed border-cream-border text-ink-muted text-xs font-semibold px-3 py-1.5 rounded-full hover:border-ink/40 hover:text-ink transition-colors">
                + Ajouter une categorie
              </a>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <footer class="border-t border-cream-border mt-8 py-8 px-6">
    <div class="max-w-6xl mx-auto flex items-center justify-between text-ink-muted text-sm">
      <span class="font-display text-ink text-base">Bibliotheca</span>
      <span>Mini Bibliotheque &mdash; Projet PHP / PDO</span>
    </div>
  </footer>

</body>
</html>