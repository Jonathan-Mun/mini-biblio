<?php
session_start();
require_once __DIR__ . '/../../includes/functions.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: /mini-biblio/public/login.php');
    die();
}

function _error_message($code) {
    $messages = [
        '0' => 'Le mot de passe actuel est incorrect.',
        '1' => 'Le profil a été modifié avec succès.',
        '2' => "Une erreur s'est produite lors de la modification du mot de passe.",
        '3' => "Les nouveaux mots de passe ne correspondent pas."
    ];
    return $messages[$code] ?? 'Une erreur inconnue est survenue.';
}
if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['success'])) {
    $message = _error_message($_GET['success']);
    $success = (int)$_GET['success'];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $bio = $_POST['bio'];

    // Gérer la photo de profil
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $file_tmp_path = $_FILES['photo']['tmp_name'];
        $file_name = basename($_FILES['photo']['name']);
        $file_size = $_FILES['photo']['size'];
        $file_type = mime_content_type($file_tmp_path);

        // Vérifier le type de fichier
        if (in_array($file_type, ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'])) {
            // Vérifier la taille du fichier (4 Mo maximum)
            if ($file_size <= 4 * 1024 * 1024) {
                $upload_dir = __DIR__ . '/../../uploads/avatars/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                $new_file_name = 'user-' . $user_id . '-' . time() . '.' . pathinfo($file_name, PATHINFO_EXTENSION);
                $dest_path = $upload_dir . $new_file_name;

                if (move_uploaded_file($file_tmp_path, $dest_path)) {
                    // Enregistrer le chemin de la photo dans la base de données
                    save_profile_photo($user_id, 'uploads/avatars/' . $new_file_name);
                }
            }
        }
    }

    update_user_profile($user_id, $username, $email, $bio);
    $_SESSION['username'] = $username;
    header('Location: edit_profil.php?success=1');
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
          <h1 class="font-display text-2xl font-bold text-ink">Modifier mon profil</h1>
          <p class="text-ink-muted text-sm mt-1">Mettez a jour vos informations personnelles.</p>
        </div>

        <!-- Message succès -->
         <?php if (isset($message)) : ?>
        <div class="bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-3 rounded-lg flex items-center gap-3 <?php
        if ($success == 1) {
            echo 'bg-green-50 border-green-200 text-green-800';
        } else {
            echo 'bg-red-50 border-red-200 text-red-800';
        }
        ?>">
          <span class="font-bold">&#10003;</span> <?php echo htmlspecialchars($message); ?>
        </div>
        <?php endif; ?>

        <div class="bg-white border border-cream-border rounded-xl p-7">
          <form method="POST" action="edit_profil.php" enctype="multipart/form-data" class="space-y-6">

            <!-- Photo de profil -->
            <div>
              <label class="block text-xs font-semibold text-ink-muted uppercase tracking-wider mb-3">Photo de profil</label>
              <div class="flex items-center gap-5">
            <div class="w-20 h-20 rounded-full overflow-hidden bg-ink flex items-center justify-center flex-shrink-0">
                <span class="font-display text-white text-2xl font-bold">
                    <?php
                    if ($photo) {
                        echo '<img src="/mini-biblio/' . $photo . '" alt="Photo de profil" class="w-full h-full object-cover object-center">';
                    } else {
                        echo strtoupper(substr($user['username'], 0, 1));
                    }
                    ?>
                </span>
            </div>
                <div>
                  <label for="photo" class="cursor-pointer inline-block bg-ink text-white text-xs font-semibold px-4 py-2.5 rounded-lg hover:bg-ink/80 transition-colors">
                    Changer la photo
                  </label>
                  <input type="file" id="photo" name="photo" accept="image/*" class="hidden">
                  <p class="text-ink-muted text-xs mt-2">JPG ou PNG — 2 Mo maximum</p>
                </div>
              </div>
            </div>

            <!-- Nom d'utilisateur -->
            <div>
              <label class="block text-xs font-semibold text-ink-muted uppercase tracking-wider mb-2">Nom d'utilisateur</label>
              <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>"
                class="w-full border border-cream-border rounded-lg px-4 py-3 text-sm text-ink focus:outline-none focus:border-ink/40 bg-cream transition-colors">
            </div>

            <!-- Email -->
            <div>
              <label class="block text-xs font-semibold text-ink-muted uppercase tracking-wider mb-2">Email</label>
              <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>"
                class="w-full border border-cream-border rounded-lg px-4 py-3 text-sm text-ink focus:outline-none focus:border-ink/40 bg-cream transition-colors">
            </div>

            <!-- Bio -->
            <div>
              <label class="block text-xs font-semibold text-ink-muted uppercase tracking-wider mb-2">
                Biographie <span class="normal-case font-normal text-ink-muted">(facultatif)</span>
              </label>
              <textarea name="bio" rows="4"
                class="w-full border border-cream-border rounded-lg px-4 py-3 text-sm text-ink focus:outline-none focus:border-ink/40 bg-cream transition-colors resize-none"><?php echo htmlspecialchars($user['bio']); ?></textarea>
              <p class="text-ink-muted text-xs mt-1.5">250 caracteres maximum</p>
            </div>

            <div class="pt-2 flex gap-3">
              <button type="submit" class="bg-ink text-white text-sm font-semibold px-6 py-3 rounded-lg hover:bg-ink/80 transition-colors">
                Enregistrer les modifications
              </button>
              <a href="dashboard.php" class="border border-cream-border text-ink text-sm font-semibold px-6 py-3 rounded-lg hover:border-ink/40 transition-colors">
                Annuler
              </a>
            </div>

          </form>
        </div>

        <!-- Changer mot de passe -->
        <div class="bg-white border border-cream-border rounded-xl p-7">
          <h3 class="font-display text-base font-semibold text-ink mb-5">Changer le mot de passe</h3>
          <form method="POST" action="change_password.php" class="space-y-4">
            <div>
              <label class="block text-xs font-semibold text-ink-muted uppercase tracking-wider mb-2">Mot de passe actuel</label>
              <input type="password" name="current_password" placeholder="••••••••"
                class="w-full border border-cream-border rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-ink/40 bg-cream transition-colors">
            </div>
            <div>
              <label class="block text-xs font-semibold text-ink-muted uppercase tracking-wider mb-2">Nouveau mot de passe</label>
              <input type="password" name="new_password" placeholder="••••••••"
                class="w-full border border-cream-border rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-ink/40 bg-cream transition-colors">
            </div>
            <div>
              <label class="block text-xs font-semibold text-ink-muted uppercase tracking-wider mb-2">Confirmer le nouveau mot de passe</label>
              <input type="password" name="confirm_password" placeholder="••••••••"
                class="w-full border border-cream-border rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-ink/40 bg-cream transition-colors">
            </div>
            <button type="submit" class="bg-ink text-white text-sm font-semibold px-6 py-3 rounded-lg hover:bg-ink/80 transition-colors">
              Mettre a jour le mot de passe
            </button>
          </form>
        </div>

        <!-- Zone dangereuse -->
        <div class="bg-red-50 border border-red-200 rounded-xl p-6">
        <h3 class="font-display text-base font-semibold text-red-800 mb-2">Zone dangereuse</h3>
        <p class="text-red-700 text-sm mb-4">La suppression de votre compte est irreversible. Toutes vos donnees seront perdues.</p>
        <form method="POST" action="delete_user.php" onsubmit="return confirm('Supprimer definitivement votre compte ? Cette action est irreversible.')">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <button type="submit" class="border border-red-300 text-red-700 text-xs font-semibold px-4 py-2.5 rounded-lg hover:bg-red-100 transition-colors">
            Supprimer mon compte
            </button>
        </form>
        </div>

      </div>
    </div>
  </div>