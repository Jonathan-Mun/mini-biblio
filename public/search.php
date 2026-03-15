<?php
session_start();
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';
$active_page = 'search';

include __DIR__ . '/../includes/header.php';
?>
  <div class="bg-ink py-12 px-6">
    <div class="max-w-2xl mx-auto">
      <p class="text-white/50 text-xs font-semibold uppercase tracking-widest mb-3 text-center">Recherche</p>
      <form method="GET" action="search.php">
        <div class="flex gap-2">
          <input type="text" name="q" value="clean code" placeholder="Titre, auteur, categorie..."
            class="flex-1 bg-white/10 border border-white/20 text-white placeholder-white/40 rounded-lg px-5 py-3 text-sm focus:outline-none focus:border-white/50 focus:bg-white/15 transition-all">
          <button type="submit" class="bg-white text-ink text-sm font-semibold px-6 py-3 rounded-lg hover:bg-cream transition-colors flex-shrink-0">
            Rechercher
          </button>
        </div>
      </form>
    </div>
  </div>

  <div class="max-w-6xl mx-auto px-6 py-10">
    <p class="text-ink-muted text-sm mb-6">3 resultats pour <strong class="text-ink">"clean code"</strong></p>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
      <a href="book.php?id=1" class="bg-white border border-cream-border rounded-xl card-hover block overflow-hidden">
        <div class="h-36 bg-gradient-to-br from-emerald-50 to-teal-100 flex items-end p-4">
          <span class="text-xs font-semibold text-ink/40 uppercase tracking-wider">Informatique</span>
        </div>
        <div class="p-4">
          <h3 class="font-display text-sm font-semibold text-ink mb-1">Clean Code</h3>
          <p class="text-ink-muted text-xs">Robert C. Martin</p>
        </div>
      </a>
      <a href="book.php?id=5" class="bg-white border border-cream-border rounded-xl card-hover block overflow-hidden">
        <div class="h-36 bg-gradient-to-br from-slate-100 to-slate-200 flex items-end p-4">
          <span class="text-xs font-semibold text-ink/40 uppercase tracking-wider">Informatique</span>
        </div>
        <div class="p-4">
          <h3 class="font-display text-sm font-semibold text-ink mb-1">The Clean Coder</h3>
          <p class="text-ink-muted text-xs">Robert C. Martin</p>
        </div>
      </a>
      <a href="book.php?id=6" class="bg-white border border-cream-border rounded-xl card-hover block overflow-hidden">
        <div class="h-36 bg-gradient-to-br from-blue-50 to-indigo-100 flex items-end p-4">
          <span class="text-xs font-semibold text-ink/40 uppercase tracking-wider">Informatique</span>
        </div>
        <div class="p-4">
          <h3 class="font-display text-sm font-semibold text-ink mb-1">Clean Architecture</h3>
          <p class="text-ink-muted text-xs">Robert C. Martin</p>
        </div>
      </a>
    </div>
  </div>
