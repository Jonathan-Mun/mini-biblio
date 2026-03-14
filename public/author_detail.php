<!-- ══════════════════════════════════════════════════ -->
  <!-- AUTHOR DETAIL (author.php)                         -->
  <!-- ══════════════════════════════════════════════════ -->
  <div class="border-t-4 border-dashed border-cream-border mt-16 pt-16 max-w-6xl mx-auto px-6 pb-16">
    <p class="text-xs font-semibold uppercase tracking-widest text-ink-muted mb-8">-- author.php --</p>

    <div class="flex items-center gap-6 mb-10">
      <div class="w-20 h-20 rounded-full bg-gradient-to-br from-emerald-100 to-teal-100 flex items-center justify-center flex-shrink-0">
        <span class="text-teal-600 font-display font-bold text-3xl">R</span>
      </div>
      <div>
        <h2 class="font-display text-3xl font-bold text-ink mb-1">Robert C. Martin</h2>
        <p class="text-ink-muted text-sm">4 livres dans le catalogue</p>
      </div>
    </div>

    <h3 class="font-display text-xl font-semibold mb-5">Livres de cet auteur</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
      <a href="book.php?id=1" class="bg-white border border-cream-border rounded-xl p-4 card-hover block">
        <div class="h-28 bg-gradient-to-br from-emerald-50 to-teal-100 rounded-lg mb-3 flex items-end p-2">
          <span class="text-xs text-ink/40 font-semibold uppercase tracking-wide">Informatique</span>
        </div>
        <h4 class="font-display text-sm font-semibold text-ink leading-snug">Clean Code</h4>
        <p class="text-ink-muted text-xs mt-1">2008</p>
      </a>
      <a href="book.php?id=5" class="bg-white border border-cream-border rounded-xl p-4 card-hover block">
        <div class="h-28 bg-gradient-to-br from-slate-100 to-slate-200 rounded-lg mb-3 flex items-end p-2">
          <span class="text-xs text-ink/40 font-semibold uppercase tracking-wide">Informatique</span>
        </div>
        <h4 class="font-display text-sm font-semibold text-ink leading-snug">The Clean Coder</h4>
        <p class="text-ink-muted text-xs mt-1">2011</p>
      </a>
    </div>
  </div>

  <footer class="border-t border-cream-border py-8 px-6">
    <div class="max-w-6xl mx-auto flex items-center justify-between text-ink-muted text-sm">
      <span class="font-display text-ink text-base">Bibliotheca</span>
      <span>Mini Bibliotheque &mdash; Projet PHP / PDO</span>
    </div>
  </footer>

</body>
</html>
