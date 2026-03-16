<?php
session_start();
require_once __DIR__ . '/../../includes/functions.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: /mini-biblio/public/login.php');
    die();
}
$active_page = "dashboard";
$active_onglet = "profile";
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
<?php include __DIR__ . '/slidebar.php'; ?>

      <div class="lg:col-span-3 space-y-6">

        <div>
          <h1 class="font-display text-2xl font-bold text-ink">Mon profil</h1>
          <p class="text-ink-muted text-sm mt-1">Informations visibles sur votre page publique.</p>
        </div>

        <!-- Carte profil -->
        <div class="bg-white border border-cream-border rounded-xl overflow-hidden">
          <div class="bg-ink h-24 relative">
            <div class="absolute -bottom-10 left-6">
          <div class="w-16 h-16 rounded-full bg-ink mx-auto mb-3 flex items-center justify-center">
            <span class="font-display text-white text-2xl font-bold">
                <?php
                if ($photo) {
                    echo '<img src="/mini-biblio/' . $photo . '" alt="Photo de profil" class="w-full h-full object-cover rounded-full">';
                } else {
                    echo strtoupper(substr($user['username'], 0, 1));
                }
                ?>
            </span>
          </div>
            </div>
          </div>
          <div class="pt-14 px-6 pb-6">
            <div class="flex items-start justify-between">
              <div>
                <h2 class="font-display text-xl font-bold text-ink"><?php echo htmlspecialchars($user['username']); ?></h2>
                <p class="text-ink-muted text-xs mt-0.5"><?php echo "Membre depuis ".htmlspecialchars($date_reg->format("Y")); ?></p>
              </div>
              <a href="edit_profile.php" class="border border-cream-border text-ink text-xs font-semibold px-4 py-2 rounded-lg hover:border-ink/40 transition-colors">
                Modifier le profil
              </a>
            </div>
            <p class="text-ink-soft text-sm mt-4 leading-relaxed">
                <?php
                if ($user['bio']) {
                    echo nl2br(htmlspecialchars($user['bio']));
                } else {
                    echo "Aucune biographie renseignée.";
                }
                ?>
            </p>
            <div class="flex gap-6 mt-5 pt-5 border-t border-cream-border">
              <div><p class="font-bold text-ink text-lg"><?php echo htmlspecialchars($nb_favorites); ?></p><p class="text-ink-muted text-xs">Favoris</p></div>
              <div><p class="font-bold text-ink text-lg"><?php echo htmlspecialchars($nb_reviews); ?></p><p class="text-ink-muted text-xs">Avis publies</p></div>
              <div><p class="font-bold text-ink text-lg"><?php echo htmlspecialchars($nb_downloads); ?></p><p class="text-ink-muted text-xs">Telechargements</p></div>
            </div>
          </div>
        </div>

        <!-- Centres d'intérêt -->
        <div class="bg-white border border-cream-border rounded-xl p-6">
          <h3 class="font-display text-base font-semibold text-ink mb-4">Centres d'interet</h3>
          <div class="flex flex-wrap gap-2">
            <?php foreach ($preferences as $cat): ?>
              <span class="bg-forest-light text-forest text-xs font-semibold px-3 py-1.5 rounded-full"><?php echo htmlspecialchars($cat['name']); ?></span>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Derniers avis -->
        <div class="bg-white border border-cream-border rounded-xl p-6">
          <div class="flex items-center justify-between mb-4">
            <h3 class="font-display text-base font-semibold text-ink">Derniers avis</h3>
            <a href="reviews.php" class="text-forest text-xs font-semibold hover:underline">Voir tout</a>
          </div>
          <?php if (empty($reviews)): ?>
            <p class="text-ink-soft text-sm">Vous n'avez publié aucun avis pour le moment.</p>
          <?php else: ?>
            
          <div class="space-y-3">
            <?php foreach ($reviews as $rev): ?>
              <div class="flex items-start gap-3 pb-3 border-b border-cream-border">
                <div class="flex-1">
                  <p class="text-sm font-semibold text-ink"><?php echo htmlspecialchars($rev['title']); ?></p>
                  <div class="flex gap-0.5 my-1">
                    <?php for ($i = 0; $i < 5; $i++): ?>
                      <svg class="w-3 h-3 <?= $i < $rev['rating'] ? 'text-amber-400' : 'text-cream-border' ?>" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <?php endfor; ?>
                  </div>
                  <p class="text-ink-soft text-xs leading-relaxed"><?php echo nl2br(htmlspecialchars($rev['comment'])); ?></p>
                </div>
                <p class="text-ink-muted text-xs flex-shrink-0"><?php echo date_rev($rev); ?></p>
              </div>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
      </div>
    </div>
  </div>