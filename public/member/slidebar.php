  <!-- SIDEBAR RÉUTILISABLE (même sur toutes les pages) -->
  <div class="max-w-6xl mx-auto px-6 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

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
      <?php if ($key === 'favorites' && ($nb_favorites ?? 0) > 0): ?>
        <span class="ml-auto bg-cream-dark text-ink-muted text-xs font-bold px-2 py-0.5 rounded-full"><?= $nb_favorites ?></span>
      <?php elseif ($key === 'reviews' && ($nb_reviews ?? 0) > 0): ?>
        <span class="ml-auto bg-cream-dark text-ink-muted text-xs font-bold px-2 py-0.5 rounded-full"><?= $nb_reviews ?></span>
      <?php elseif ($key === 'downloads' && ($nb_downloads ?? 0) > 0): ?>
        <span class="ml-auto bg-cream-dark text-ink-muted text-xs font-bold px-2 py-0.5 rounded-full"><?= $nb_downloads ?></span>
      <?php endif; ?>
    </a>
    <?php endforeach; ?>

  </div>

</aside>