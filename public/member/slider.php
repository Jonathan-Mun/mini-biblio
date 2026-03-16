      <!-- ── SIDEBAR GAUCHE ── -->
      <aside class="lg:col-span-1 space-y-4">

        <!-- Profil -->
        <div class="bg-white border border-cream-border rounded-xl p-5 text-center">
          <div class="w-16 h-16 rounded-full bg-ink mx-auto mb-3 overflow-hidden flex items-center justify-center">
            <?php if ($photo): ?>
              <img src="/mini-biblio/<?= htmlspecialchars($photo) ?>"
                  alt="Avatar"
                  class="w-16 h-16 rounded-full object-cover">
            <?php else: ?>
              <span class="font-display text-white text-2xl font-bold">
                <?= htmlspecialchars(name_character($user['username'])) ?>
              </span>
            <?php endif; ?>
          </div>
          <p class="font-semibold text-ink text-sm"><?php echo htmlspecialchars($user['username']) ?></p>
          <p class="text-ink-muted text-xs mt-0.5">Membre depuis <?php echo htmlspecialchars($date_reg->format("Y"))?></p>
          <div class="mt-3 pt-3 border-t border-cream-border grid grid-cols-3 gap-2 text-center">
            <div>
              <p class="font-bold text-ink text-sm"><?php echo htmlspecialchars($nb_favorites); ?></p>
              <p class="text-ink-muted text-xs">Favoris</p>
            </div>
            <div>
              <p class="font-bold text-ink text-sm"><?php echo htmlspecialchars($nb_reviews); ?></p>
              <p class="text-ink-muted text-xs">Avis</p>
            </div>
            <div>
              <p class="font-bold text-ink text-sm"><?php echo htmlspecialchars($nb_downloads); ?></p>
              <p class="text-ink-muted text-xs">Telecharg.</p>
            </div>
          </div>
          <a href="edit_profil.php" class="block mt-4 text-center border border-cream-border text-ink text-xs font-semibold py-2 rounded-lg hover:border-ink/40 transition-colors">
            Modifier le profil
          </a>
        </div>