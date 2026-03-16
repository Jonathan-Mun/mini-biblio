  <!-- SIDEBAR RÉUTILISABLE (même sur toutes les pages) -->
  <div class="max-w-6xl mx-auto px-6 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

      <aside class="lg:col-span-1 space-y-4">
        <div class="bg-white border border-cream-border rounded-xl p-5 text-center">
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
          <p class="font-semibold text-ink text-sm"><?php echo htmlspecialchars($user['username']); ?></p>
          <p class="text-ink-muted text-xs mt-0.5"><?php echo "Membre depuis ".htmlspecialchars($date_reg->format("Y")); ?></p>
          <div class="mt-3 pt-3 border-t border-cream-border grid grid-cols-3 gap-2 text-center">
            <div><p class="font-bold text-ink text-sm"><?php echo htmlspecialchars($nb_favorites); ?></p><p class="text-ink-muted text-xs">Favoris</p></div>
            <div><p class="font-bold text-ink text-sm"><?php echo htmlspecialchars($nb_reviews); ?></p><p class="text-ink-muted text-xs">Avis</p></div>
            <div><p class="font-bold text-ink text-sm"><?php echo htmlspecialchars($nb_downloads); ?></p><p class="text-ink-muted text-xs">Telecharg.</p></div>
          </div>
        </div>
        <div class="bg-white border border-cream-border rounded-xl overflow-hidden">
          <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 text-ink-soft text-sm font-medium hover:bg-cream transition-colors" <?php if ($active_onglet === 'dashboard') echo 'bg-ink text-white'; ?>>
            Tableau de bord</a>
          <a href="favorites.php" class="flex items-center gap-3 px-4 py-3 text-ink-soft text-sm font-medium hover:bg-cream transition-colors border-t border-cream-border" <?php if ($active_onglet === 'favorites') echo 'bg-ink text-white'; ?>>
            Mes favoris</a>
          <a href="reviews.php" class="flex items-center gap-3 px-4 py-3 text-ink-soft text-sm font-medium hover:bg-cream transition-colors border-t border-cream-border" <?php if ($active_onglet === 'reviews') echo 'bg-ink text-white'; ?>>
            Mes avis</a>
          <a href="downloads.php" class="flex items-center gap-3 px-4 py-3 text-ink-soft text-sm font-medium hover:bg-cream transition-colors border-t border-cream-border" <?php if ($active_onglet === 'downloads') echo 'bg-ink text-white'; ?>>
            Telechargements</a>
          <a href="preferences.php" class="flex items-center gap-3 px-4 py-3 text-ink-soft text-sm font-medium hover:bg-cream transition-colors border-t border-cream-border" <?php if ($active_onglet === 'preferences') echo 'bg-ink text-white'; ?>>
            Preferences</a>
          <a href="profile.php" class="flex items-center gap-3 px-4 py-3 bg-ink text-white text-sm font-semibold border-t border-cream-border">Mon profil</a>
        </div>
      </aside>