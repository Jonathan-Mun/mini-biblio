<?php
require_once __DIR__ . '/../../classes/User.php';
require_once __DIR__ . '/../../includes/functions.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] !== true) {
    header('Location: ../auth/login.php');
    exit;
}

$page_title  = 'Administration — Bibliotheca';
$active_page = 'admin';

$stats   = get_admin_stats();
$books   = get_recent_books(5);
$users   = get_recent_users(5);
$reviews = get_recent_reviews(5);

include __DIR__ . '/../../includes/header.php';
?>

<div class="max-w-6xl mx-auto px-6 py-10">
  <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

    <!-- ── SIDEBAR GAUCHE ── -->
    <aside class="lg:col-span-1 space-y-4">

      <!-- Profil admin -->
      <div class="bg-white border border-cream-border rounded-xl p-5 text-center">
        <div class="w-16 h-16 rounded-full bg-ink mx-auto mb-3 flex items-center justify-center">
          <div class="w-16 h-16 rounded-full bg-ink mx-auto mb-3 flex items-center justify-center overflow-hidden">
            <?php $photo = get_photo_path($_SESSION['user_id']); ?>

            <div class="w-16 h-16 rounded-full bg-ink mx-auto mb-3 flex items-center justify-center overflow-hidden">
              <?php if ($photo): ?>
                <img src="/mini-biblio/<?= htmlspecialchars($photo) ?>"
                    alt="Avatar"
                    class="w-full h-full object-cover">
              <?php else: ?>
                <span class="font-display text-white text-2xl font-bold">
                  <?= htmlspecialchars(name_character($_SESSION['username'])) ?>
                </span>
              <?php endif; ?>
            </div>
      </div>
        </div>
        <p class="font-semibold text-ink text-sm"><?= htmlspecialchars($_SESSION['username']) ?></p>
        <p class="text-ink-muted text-xs mt-0.5">Administrateur</p>
        <div class="mt-3 pt-3 border-t border-cream-border grid grid-cols-3 gap-2 text-center">
          <div>
            <p class="font-bold text-ink text-sm"><?= $stats['total_books'] ?></p>
            <p class="text-ink-muted text-xs">Livres</p>
          </div>
          <div>
            <p class="font-bold text-ink text-sm"><?= $stats['total_users'] ?></p>
            <p class="text-ink-muted text-xs">Membres</p>
          </div>
          <div>
            <p class="font-bold text-ink text-sm"><?= $stats['total_reviews'] ?></p>
            <p class="text-ink-muted text-xs">Avis</p>
          </div>
        </div>
      </div>

      <!-- Navigation admin -->
      <div class="bg-white border border-cream-border rounded-xl overflow-hidden">
        <a href="index.php" class="flex items-center gap-3 px-4 py-3 bg-ink text-white text-sm font-semibold">
          <span>Tableau de bord</span>
        </a>
        <a href="books.php" class="flex items-center gap-3 px-4 py-3 text-ink-soft text-sm font-medium hover:bg-cream transition-colors border-t border-cream-border">
          <span>Livres</span>
          <span class="ml-auto bg-cream-dark text-ink-muted text-xs font-bold px-2 py-0.5 rounded-full"><?= $stats['total_books'] ?></span>
        </a>
        <a href="authors.php" class="flex items-center gap-3 px-4 py-3 text-ink-soft text-sm font-medium hover:bg-cream transition-colors border-t border-cream-border">
          <span>Auteurs</span>
          <span class="ml-auto bg-cream-dark text-ink-muted text-xs font-bold px-2 py-0.5 rounded-full"><?= $stats['total_authors'] ?></span>
        </a>
        <a href="categories.php" class="flex items-center gap-3 px-4 py-3 text-ink-soft text-sm font-medium hover:bg-cream transition-colors border-t border-cream-border">
          <span>Categories</span>
          <span class="ml-auto bg-cream-dark text-ink-muted text-xs font-bold px-2 py-0.5 rounded-full"><?= $stats['total_categories'] ?></span>
        </a>
        <a href="users.php" class="flex items-center gap-3 px-4 py-3 text-ink-soft text-sm font-medium hover:bg-cream transition-colors border-t border-cream-border">
          <span>Membres</span>
          <span class="ml-auto bg-cream-dark text-ink-muted text-xs font-bold px-2 py-0.5 rounded-full"><?= $stats['total_users'] ?></span>
        </a>
        <a href="reviews.php" class="flex items-center gap-3 px-4 py-3 text-ink-soft text-sm font-medium hover:bg-cream transition-colors border-t border-cream-border">
          <span>Avis</span>
          <span class="ml-auto bg-cream-dark text-ink-muted text-xs font-bold px-2 py-0.5 rounded-full"><?= $stats['total_reviews'] ?></span>
        </a>
        <a href="downloads.php" class="flex items-center gap-3 px-4 py-3 text-ink-soft text-sm font-medium hover:bg-cream transition-colors border-t border-cream-border">
          <span>Telechargements</span>
          <span class="ml-auto bg-cream-dark text-ink-muted text-xs font-bold px-2 py-0.5 rounded-full"><?= $stats['total_downloads'] ?></span>
        </a>
      </div>

      <!-- Actions rapides -->
      <div class="bg-white border border-cream-border rounded-xl p-5 space-y-2">
        <p class="text-xs font-semibold text-ink-muted uppercase tracking-wider mb-3">Actions rapides</p>
        <a href="add_book.php" class="block w-full text-center bg-ink text-white text-xs font-semibold py-2.5 rounded-lg hover:bg-ink/80 transition-colors">
          + Ajouter un livre
        </a>
        <a href="add_author.php" class="block w-full text-center border border-cream-border text-ink text-xs font-semibold py-2.5 rounded-lg hover:border-ink/40 transition-colors">
          + Ajouter un auteur
        </a>
        <a href="add_category.php" class="block w-full text-center border border-cream-border text-ink text-xs font-semibold py-2.5 rounded-lg hover:border-ink/40 transition-colors">
          + Ajouter une categorie
        </a>
      </div>

    </aside>

    <!-- ── CONTENU PRINCIPAL ── -->
    <div class="lg:col-span-3 space-y-8">

      <!-- Bonjour -->
      <div>
        <h1 class="font-display text-2xl font-bold text-ink">
          Bonjour, <?= htmlspecialchars($_SESSION['username']) ?>
        </h1>
        <p class="text-ink-muted text-sm mt-1">Voici un apercu complet de la plateforme.</p>
      </div>

      <!-- Stats cards -->
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white border border-cream-border rounded-xl p-4">
          <p class="text-xs font-semibold text-ink-muted uppercase tracking-wider mb-2">Livres</p>
          <p class="font-display text-3xl font-bold text-ink"><?= $stats['total_books'] ?></p>
          <p class="text-ink-muted text-xs mt-1">dans le catalogue</p>
        </div>
        <div class="bg-white border border-cream-border rounded-xl p-4">
          <p class="text-xs font-semibold text-ink-muted uppercase tracking-wider mb-2">Auteurs</p>
          <p class="font-display text-3xl font-bold text-ink"><?= $stats['total_authors'] ?></p>
          <p class="text-ink-muted text-xs mt-1">enregistres</p>
        </div>
        <div class="bg-white border border-cream-border rounded-xl p-4">
          <p class="text-xs font-semibold text-ink-muted uppercase tracking-wider mb-2">Membres</p>
          <p class="font-display text-3xl font-bold text-ink"><?= $stats['total_users'] ?></p>
          <p class="text-ink-muted text-xs mt-1">inscrits</p>
        </div>
        <div class="bg-white border border-cream-border rounded-xl p-4">
          <p class="text-xs font-semibold text-ink-muted uppercase tracking-wider mb-2">Telecharg.</p>
          <p class="font-display text-3xl font-bold text-ink"><?= $stats['total_downloads'] ?></p>
          <p class="text-ink-muted text-xs mt-1">au total</p>
        </div>
      </div>

      <!-- Derniers livres ajoutés -->
      <div>
        <div class="flex items-center justify-between mb-4">
          <div>
            <h2 class="font-display text-lg font-semibold text-ink">Derniers livres ajoutes</h2>
            <p class="text-ink-muted text-xs mt-0.5"><?= $stats['total_books'] ?> livres au total</p>
          </div>
          <a href="books.php" class="text-forest text-sm font-semibold hover:underline">Voir tout</a>
        </div>
        <div class="bg-white border border-cream-border rounded-xl overflow-hidden">
          <table class="w-full text-sm">
            <thead class="bg-cream border-b border-cream-border">
              <tr>
                <th class="text-left px-5 py-3 text-xs font-semibold text-ink-muted uppercase tracking-wider">Titre</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-ink-muted uppercase tracking-wider hidden sm:table-cell">Auteur</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-ink-muted uppercase tracking-wider hidden sm:table-cell">Categorie</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-ink-muted uppercase tracking-wider">PDF</th>
                <th class="text-right px-5 py-3 text-xs font-semibold text-ink-muted uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-cream-border">
              <?php foreach ($books as $book): ?>
              <tr class="hover:bg-cream/50 transition-colors">
                <td class="px-5 py-3 font-medium text-ink"><?= htmlspecialchars($book['title']) ?></td>
                <td class="px-5 py-3 text-ink-soft hidden sm:table-cell">
                  <?= htmlspecialchars($book['first_name'] . ' ' . $book['last_name']) ?>
                </td>
                <td class="px-5 py-3 hidden sm:table-cell">
                  <span class="bg-forest/10 text-forest text-xs font-semibold px-2.5 py-1 rounded-full">
                    <?= htmlspecialchars($book['category_name']) ?>
                  </span>
                </td>
                <td class="px-5 py-3">
                  <?php if ($book['pdf_path']): ?>
                    <span class="text-forest text-xs font-semibold">Oui</span>
                  <?php else: ?>
                    <span class="text-ink-muted text-xs">Non</span>
                  <?php endif; ?>
                </td>
                <td class="px-5 py-3 text-right">
                  <div class="flex gap-2 justify-end">
                    <a href="edit_book.php?id=<?= $book['id'] ?>"
                       class="text-ink border border-cream-border text-xs font-semibold px-3 py-1.5 rounded-lg hover:border-ink/40 transition-colors">
                      Modifier
                    </a>
                    <a href="delete_book.php?id=<?= $book['id'] ?>"
                       class="text-red-700 border border-red-200 bg-red-50 text-xs font-semibold px-3 py-1.5 rounded-lg hover:bg-red-100 transition-colors"
                       onclick="return confirm('Supprimer ce livre ?')">
                      Supprimer
                    </a>
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Membres recents -->
      <div>
        <div class="flex items-center justify-between mb-4">
          <div>
            <h2 class="font-display text-lg font-semibold text-ink">Membres recents</h2>
            <p class="text-ink-muted text-xs mt-0.5"><?= $stats['total_users'] ?> membres inscrits</p>
          </div>
          <a href="users.php" class="text-forest text-sm font-semibold hover:underline">Voir tout</a>
        </div>
        <div class="bg-white border border-cream-border rounded-xl overflow-hidden">
          <table class="w-full text-sm">
            <thead class="bg-cream border-b border-cream-border">
              <tr>
                <th class="text-left px-5 py-3 text-xs font-semibold text-ink-muted uppercase tracking-wider">Utilisateur</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-ink-muted uppercase tracking-wider hidden sm:table-cell">Email</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-ink-muted uppercase tracking-wider">Role</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-ink-muted uppercase tracking-wider">Inscription</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-cream-border">
              <?php foreach ($users as $user): ?>
              <tr class="hover:bg-cream/50 transition-colors">
                <td class="px-5 py-3 font-medium text-ink"><?= htmlspecialchars($user['username']) ?></td>
                <td class="px-5 py-3 text-ink-soft hidden sm:table-cell"><?= htmlspecialchars($user['email']) ?></td>
                <td class="px-5 py-3">
                  <?php if ($user['role'] === 'admin'): ?>
                    <span class="bg-amber-100 text-amber-800 text-xs font-semibold px-2.5 py-1 rounded-full">Admin</span>
                  <?php else: ?>
                    <span class="bg-forest/10 text-forest text-xs font-semibold px-2.5 py-1 rounded-full">Membre</span>
                  <?php endif; ?>
                </td>
                <td class="px-5 py-3 text-ink-muted text-xs">
                  <?= date('d M Y', strtotime($user['created_at'])) ?>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Derniers avis -->
      <div>
        <div class="flex items-center justify-between mb-4">
          <div>
            <h2 class="font-display text-lg font-semibold text-ink">Derniers avis</h2>
            <p class="text-ink-muted text-xs mt-0.5"><?= $stats['total_reviews'] ?> avis au total</p>
          </div>
          <a href="reviews.php" class="text-forest text-sm font-semibold hover:underline">Voir tout</a>
        </div>
        <div class="space-y-3">
          <?php foreach ($reviews as $review): ?>
          <div class="bg-white border border-cream-border rounded-xl p-5">
            <div class="flex items-start justify-between mb-2">
              <div>
                <a href="../book.php?id=<?= $review['book_id'] ?>"
                   class="font-display text-sm font-semibold text-ink hover:text-forest transition-colors">
                  <?= htmlspecialchars($review['title']) ?>
                </a>
                <p class="text-ink-muted text-xs mt-0.5">par <?= htmlspecialchars($review['username']) ?></p>
              </div>
              <div class="flex items-center gap-3 flex-shrink-0">
                <div class="flex gap-0.5">
                  <?php for ($i = 1; $i <= 5; $i++): ?>
                  <svg class="w-3 h-3 <?= $i <= $review['rating'] ? 'text-amber-400' : 'text-cream-border' ?>"
                       fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                  </svg>
                  <?php endfor; ?>
                </div>
                <a href="reviews.php?delete=<?= $review['id'] ?>"
                   class="text-red-600 text-xs font-semibold hover:underline"
                   onclick="return confirm('Supprimer cet avis ?')">
                  Supprimer
                </a>
              </div>
            </div>
            <?php if ($review['comment']): ?>
            <p class="text-ink-soft text-sm leading-relaxed"><?= htmlspecialchars($review['comment']) ?></p>
            <?php endif; ?>
            <p class="text-ink-muted text-xs mt-2"><?= date('d M Y', strtotime($review['created_at'])) ?></p>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

    </div>
  </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>