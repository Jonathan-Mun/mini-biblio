<?php
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../includes/functions.php';
session_start();

$page_title  = 'Catalogue — Bibliotheca';
$active_page = 'catalogue';

include __DIR__ . '/../includes/header.php';
?>

<!-- HERO -->
<div class="bg-ink text-white py-14 px-6">
  <div class="max-w-6xl mx-auto">
    <p class="text-white/50 text-xs font-semibold uppercase tracking-widest mb-3">Catalogue complet</p>
    <h1 class="font-display text-4xl mb-3">Tous les livres</h1>
    <p class="text-white/60 text-sm max-w-lg">Parcourez notre collection, filtrez par categorie et telechargez les PDFs disponibles.</p>
  </div>
</div>

<!-- FILTRES + CONTENU -->
<div class="max-w-6xl mx-auto px-6 py-10">

  <!-- Barre filtres -->
  <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
    <div class="flex flex-wrap gap-2">
      <a href="index.php"
         class="text-xs font-semibold px-4 py-2 rounded-full <?= !isset($_GET['category']) ? 'bg-ink text-white' : 'bg-white border border-cream-border text-ink/70 hover:border-ink/40' ?> transition-colors">
        Tous
      </a>
      <?php foreach (get_all_categories() as $cat): ?>
      <a href="index.php?category=<?= $cat['slug'] ?>"
         class="text-xs font-semibold px-4 py-2 rounded-full <?= ($_GET['category'] ?? '') === $cat['slug'] ? 'bg-ink text-white' : 'bg-white border border-cream-border text-ink/70 hover:border-ink/40' ?> transition-colors">
        <?= htmlspecialchars($cat['name']) ?>
      </a>
      <?php endforeach; ?>
    </div>
    <p class="text-ink-muted text-sm"><?= count($books) ?> livre(s)</p>
  </div>

  <!-- Grille livres -->
  <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
    <?php if (empty($books)): ?>
      <p class="col-span-4 text-center text-ink-muted py-16">Aucun livre disponible pour le moment.</p>
    <?php else: ?>
      <?php foreach ($books as $book): ?>
      <a href="book.php?id=<?= $book['id'] ?>" class="bg-white rounded-xl border border-cream-border card-hover block overflow-hidden">
        <div class="h-44 bg-gradient-to-br from-slate-100 to-slate-200 flex items-end p-4 relative overflow-hidden">
          <?php if ($book['cover_image']): ?>
            <img src="<?= htmlspecialchars($book['cover_image']) ?>"
                 alt="<?= htmlspecialchars($book['title']) ?>"
                 class="absolute inset-0 w-full h-full object-cover">
          <?php endif; ?>
          <span class="relative z-10 text-xs font-semibold text-ink/40 uppercase tracking-wider">
            <?= htmlspecialchars($book['category_name']) ?>
          </span>
        </div>
        <div class="p-4">
          <h3 class="font-display text-base font-semibold text-ink leading-snug mb-1 line-clamp-2">
            <?= htmlspecialchars($book['title']) ?>
          </h3>
          <p class="text-ink-muted text-xs mb-3">
            <?= htmlspecialchars($book['first_name'] . ' ' . $book['last_name']) ?>
          </p>
          <div class="flex items-center justify-between">
            <div class="flex gap-0.5">
              <?php for ($i = 1; $i <= 5; $i++): ?>
              <svg class="w-3 h-3 <?= $i <= round($book['avg_rating'] ?? 0) ? 'text-amber-400' : 'text-cream-border' ?>"
                   fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
              </svg>
              <?php endfor; ?>
            </div>
            <?php if ($book['pdf_path']): ?>
              <span class="text-forest text-xs font-semibold">PDF dispo</span>
            <?php endif; ?>
          </div>
        </div>
      </a>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <!-- Pagination -->
  <div class="flex items-center justify-center gap-1 mt-12">
    <a href="#" class="w-9 h-9 flex items-center justify-center rounded border border-cream-border text-ink-muted text-sm hover:border-ink/40 transition-colors">&lsaquo;</a>
    <a href="#" class="w-9 h-9 flex items-center justify-center rounded bg-ink text-white text-sm font-semibold">1</a>
    <a href="#" class="w-9 h-9 flex items-center justify-center rounded border border-cream-border text-ink-muted text-sm hover:border-ink/40 transition-colors">2</a>
    <a href="#" class="w-9 h-9 flex items-center justify-center rounded border border-cream-border text-ink-muted text-sm hover:border-ink/40 transition-colors">3</a>
    <a href="#" class="w-9 h-9 flex items-center justify-center rounded border border-cream-border text-ink-muted text-sm hover:border-ink/40 transition-colors">&rsaquo;</a>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>