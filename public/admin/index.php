!-- ═══════════════════════════════════════════════════ -->
  <!-- ADMIN DASHBOARD (admin/index.php)                  -->
  <!-- ═══════════════════════════════════════════════════ -->
  <div class="border-t-4 border-dashed border-cream-border max-w-6xl mx-auto px-6 pt-16 pb-16">
    <p class="text-xs font-semibold uppercase tracking-widest text-ink-muted mb-8">-- admin/index.php --</p>

    <div class="flex items-center justify-between mb-8">
      <div>
        <h2 class="font-display text-2xl font-bold text-ink">Tableau de bord</h2>
        <p class="text-ink-muted text-sm mt-1">Bienvenue, Admin.</p>
      </div>
      <a href="add_book.php" class="bg-ink text-white text-sm font-semibold px-5 py-2.5 rounded-lg hover:bg-ink/80 transition-colors">+ Ajouter un livre</a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
      <div class="bg-white border border-cream-border rounded-xl p-5">
        <p class="text-xs font-semibold text-ink-muted uppercase tracking-wider mb-2">Livres</p>
        <p class="font-display text-3xl font-bold text-ink">24</p>
        <p class="text-ink-muted text-xs mt-1">dans le catalogue</p>
      </div>
      <div class="bg-white border border-cream-border rounded-xl p-5">
        <p class="text-xs font-semibold text-ink-muted uppercase tracking-wider mb-2">Auteurs</p>
        <p class="font-display text-3xl font-bold text-ink">9</p>
        <p class="text-ink-muted text-xs mt-1">enregistres</p>
      </div>
      <div class="bg-white border border-cream-border rounded-xl p-5">
        <p class="text-xs font-semibold text-ink-muted uppercase tracking-wider mb-2">Membres</p>
        <p class="font-display text-3xl font-bold text-ink">47</p>
        <p class="text-ink-muted text-xs mt-1">inscrits</p>
      </div>
      <div class="bg-white border border-cream-border rounded-xl p-5">
        <p class="text-xs font-semibold text-ink-muted uppercase tracking-wider mb-2">Avis</p>
        <p class="font-display text-3xl font-bold text-ink">138</p>
        <p class="text-ink-muted text-xs mt-1">deposes</p>
      </div>
    </div>

    <!-- Table livres -->
    <div class="flex items-center justify-between mb-4">
      <h3 class="font-display text-lg font-semibold">Gestion des livres</h3>
      <a href="books.php" class="text-forest text-sm font-semibold hover:underline">Voir tout &rarr;</a>
    </div>
    <div class="bg-white border border-cream-border rounded-xl overflow-hidden mb-8">
      <table class="w-full text-sm">
        <thead class="bg-cream border-b border-cream-border">
          <tr>
            <th class="text-left px-5 py-3 text-xs font-semibold text-ink-muted uppercase tracking-wider">Titre</th>
            <th class="text-left px-5 py-3 text-xs font-semibold text-ink-muted uppercase tracking-wider">Auteur</th>
            <th class="text-left px-5 py-3 text-xs font-semibold text-ink-muted uppercase tracking-wider">Categorie</th>
            <th class="text-left px-5 py-3 text-xs font-semibold text-ink-muted uppercase tracking-wider">Note</th>
            <th class="text-left px-5 py-3 text-xs font-semibold text-ink-muted uppercase tracking-wider">PDF</th>
            <th class="text-right px-5 py-3 text-xs font-semibold text-ink-muted uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-cream-border">
          <tr class="hover:bg-cream/50 transition-colors">
            <td class="px-5 py-4 font-medium text-ink">Clean Code</td>
            <td class="px-5 py-4 text-ink-soft">Robert C. Martin</td>
            <td class="px-5 py-4"><span class="bg-forest/10 text-forest text-xs font-semibold px-2.5 py-1 rounded-full">Informatique</span></td>
            <td class="px-5 py-4 text-ink-soft">4.2 / 5</td>
            <td class="px-5 py-4"><span class="text-forest text-xs font-semibold">Oui</span></td>
            <td class="px-5 py-4 text-right">
              <div class="flex gap-2 justify-end">
                <a href="edit_book.php?id=1" class="text-ink border border-cream-border text-xs font-semibold px-3 py-1.5 rounded-lg hover:border-ink/40 transition-colors">Modifier</a>
                <a href="delete_book.php?id=1" class="text-red-700 border border-red-200 bg-red-50 text-xs font-semibold px-3 py-1.5 rounded-lg hover:bg-red-100 transition-colors" onclick="return confirm('Supprimer ce livre ?')">Supprimer</a>
              </div>
            </td>
          </tr>
          <tr class="hover:bg-cream/50 transition-colors">
            <td class="px-5 py-4 font-medium text-ink">Sapiens</td>
            <td class="px-5 py-4 text-ink-soft">Yuval Noah Harari</td>
            <td class="px-5 py-4"><span class="bg-amber-100 text-amber-800 text-xs font-semibold px-2.5 py-1 rounded-full">Histoire</span></td>
            <td class="px-5 py-4 text-ink-soft">4.8 / 5</td>
            <td class="px-5 py-4"><span class="text-ink-muted text-xs">Non</span></td>
            <td class="px-5 py-4 text-right">
              <div class="flex gap-2 justify-end">
                <a href="edit_book.php?id=2" class="text-ink border border-cream-border text-xs font-semibold px-3 py-1.5 rounded-lg hover:border-ink/40 transition-colors">Modifier</a>
                <a href="delete_book.php?id=2" class="text-red-700 border border-red-200 bg-red-50 text-xs font-semibold px-3 py-1.5 rounded-lg hover:bg-red-100 transition-colors" onclick="return confirm('Supprimer ce livre ?')">Supprimer</a>
              </div>
            </td>
          </tr>
          <tr class="hover:bg-cream/50 transition-colors">
            <td class="px-5 py-4 font-medium text-ink">L'Etranger</td>
            <td class="px-5 py-4 text-ink-soft">Albert Camus</td>
            <td class="px-5 py-4"><span class="bg-rose-100 text-rose-800 text-xs font-semibold px-2.5 py-1 rounded-full">Roman</span></td>
            <td class="px-5 py-4 text-ink-soft">4.5 / 5</td>
            <td class="px-5 py-4"><span class="text-forest text-xs font-semibold">Oui</span></td>
            <td class="px-5 py-4 text-right">
              <div class="flex gap-2 justify-end">
                <a href="edit_book.php?id=3" class="text-ink border border-cream-border text-xs font-semibold px-3 py-1.5 rounded-lg hover:border-ink/40 transition-colors">Modifier</a>
                <a href="delete_book.php?id=3" class="text-red-700 border border-red-200 bg-red-50 text-xs font-semibold px-3 py-1.5 rounded-lg hover:bg-red-100 transition-colors" onclick="return confirm('Supprimer ce livre ?')">Supprimer</a>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Table membres -->
    <div class="flex items-center justify-between mb-4">
      <h3 class="font-display text-lg font-semibold">Membres recents</h3>
      <a href="users.php" class="text-forest text-sm font-semibold hover:underline">Voir tout &rarr;</a>
    </div>
    <div class="bg-white border border-cream-border rounded-xl overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-cream border-b border-cream-border">
          <tr>
            <th class="text-left px-5 py-3 text-xs font-semibold text-ink-muted uppercase tracking-wider">Utilisateur</th>
            <th class="text-left px-5 py-3 text-xs font-semibold text-ink-muted uppercase tracking-wider">Email</th>
            <th class="text-left px-5 py-3 text-xs font-semibold text-ink-muted uppercase tracking-wider">Role</th>
            <th class="text-left px-5 py-3 text-xs font-semibold text-ink-muted uppercase tracking-wider">Inscription</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-cream-border">
          <tr class="hover:bg-cream/50">
            <td class="px-5 py-4 font-medium text-ink">jonathan_m</td>
            <td class="px-5 py-4 text-ink-soft">jonathan@exemple.fr</td>
            <td class="px-5 py-4"><span class="bg-forest/10 text-forest text-xs font-semibold px-2.5 py-1 rounded-full">Membre</span></td>
            <td class="px-5 py-4 text-ink-muted">10 mars 2026</td>
          </tr>
          <tr class="hover:bg-cream/50">
            <td class="px-5 py-4 font-medium text-ink">admin</td>
            <td class="px-5 py-4 text-ink-soft">admin@exemple.fr</td>
            <td class="px-5 py-4"><span class="bg-amber-100 text-amber-800 text-xs font-semibold px-2.5 py-1 rounded-full">Admin</span></td>
            <td class="px-5 py-4 text-ink-muted">1 janv. 2026</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
