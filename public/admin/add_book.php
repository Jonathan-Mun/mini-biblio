 <!-- ═══════════════════════════════════════════════════ -->
  <!-- ADD BOOK (admin/add_book.php)                       -->
  <!-- ═══════════════════════════════════════════════════ -->
  <div class="border-t-4 border-dashed border-cream-border max-w-6xl mx-auto px-6 pt-16 pb-16">
    <p class="text-xs font-semibold uppercase tracking-widest text-ink-muted mb-8">-- admin/add_book.php --</p>

    <div class="max-w-2xl">
      <div class="flex items-center gap-4 mb-8">
        <a href="books.php" class="text-ink-muted text-sm hover:text-ink transition-colors">&larr; Livres</a>
        <h2 class="font-display text-2xl font-bold text-ink">Ajouter un livre</h2>
      </div>

      <div class="bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-lg mb-6">
        Livre ajoute avec succes.
      </div>

      <div class="bg-white border border-cream-border rounded-xl p-7 shadow-sm">
        <form method="POST" action="add_book.php" enctype="multipart/form-data" class="space-y-5">
          <div>
            <label class="block text-xs font-semibold text-ink-muted uppercase tracking-wider mb-2">Titre *</label>
            <input type="text" name="title" placeholder="Titre du livre"
              class="w-full border border-cream-border rounded-lg px-4 py-3 text-sm text-ink placeholder-ink-muted/50 focus:outline-none focus:border-ink/40 bg-cream transition-colors">
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-semibold text-ink-muted uppercase tracking-wider mb-2">Auteur *</label>
              <select name="author_id" class="w-full border border-cream-border rounded-lg px-4 py-3 text-sm text-ink focus:outline-none focus:border-ink/40 bg-cream transition-colors">
                <option value="">-- Choisir --</option>
                <option value="1">Robert C. Martin</option>
                <option value="2">Yuval Noah Harari</option>
                <option value="3">Albert Camus</option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-semibold text-ink-muted uppercase tracking-wider mb-2">Categorie *</label>
              <select name="category_id" class="w-full border border-cream-border rounded-lg px-4 py-3 text-sm text-ink focus:outline-none focus:border-ink/40 bg-cream transition-colors">
                <option value="">-- Choisir --</option>
                <option value="1">Informatique</option>
                <option value="2">Histoire</option>
                <option value="3">Roman</option>
                <option value="4">Science</option>
              </select>
            </div>
          </div>
          <div>
            <label class="block text-xs font-semibold text-ink-muted uppercase tracking-wider mb-2">Annee de parution</label>
            <input type="number" name="year" placeholder="2024" min="1900" max="2099"
              class="w-full border border-cream-border rounded-lg px-4 py-3 text-sm text-ink placeholder-ink-muted/50 focus:outline-none focus:border-ink/40 bg-cream transition-colors">
          </div>
          <div>
            <label class="block text-xs font-semibold text-ink-muted uppercase tracking-wider mb-2">Description</label>
            <textarea name="description" rows="4" placeholder="Resume du livre..."
              class="w-full border border-cream-border rounded-lg px-4 py-3 text-sm text-ink placeholder-ink-muted/50 focus:outline-none focus:border-ink/40 bg-cream transition-colors resize-none"></textarea>
          </div>
          <div>
            <label class="block text-xs font-semibold text-ink-muted uppercase tracking-wider mb-2">Fichier PDF</label>
            <div class="border-2 border-dashed border-cream-border rounded-lg p-6 text-center hover:border-ink/30 transition-colors cursor-pointer">
              <input type="file" name="pdf" accept=".pdf" class="hidden" id="pdf-input">
              <label for="pdf-input" class="cursor-pointer">
                <p class="text-ink-muted text-sm">Glisser-deposer ou <span class="text-forest font-semibold">parcourir</span></p>
                <p class="text-ink-muted text-xs mt-1">PDF uniquement &mdash; 20 Mo max</p>
              </label>
            </div>
          </div>
          <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-ink text-white text-sm font-semibold px-6 py-3 rounded-lg hover:bg-ink/80 transition-colors">Ajouter le livre</button>
            <a href="books.php" class="border border-cream-border text-ink text-sm font-semibold px-6 py-3 rounded-lg hover:border-ink/40 transition-colors">Annuler</a>
          </div>
        </form>
      </div>
    </div>
  </div>
