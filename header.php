<?php
// Démarre la session uniquement si elle n'existe pas déjà
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle . ' - Livre d\'Or') : 'Livre d\'Or'; ?></title>

    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="site-header">
        <div class="container header-inner">
            <a href="index.php" class="site-logo" aria-label="Accueil - Livre d'Or">
                <strong>Livre</strong><span class="logo-accent">d'Or</span>
            </a>

            <button class="nav-toggle" id="navToggle" aria-expanded="false" aria-controls="mainNav">☰</button>

            <nav id="mainNav" class="main-nav">
                <ul class="nav-list">
                    <li><a href="index.php">🏠 Accueil</a></li>
                    <li><a href="livre-or.php">📖 Livre d'or</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="commentaire.php" class="nav-cta">✨ Poster</a></li>
                        <li class="user-menu">
                            <a href="profil.php" class="user-badge"><?php echo isset($_SESSION['login']) ? htmlspecialchars($_SESSION['login']) : 'Membre'; ?></a>
                        </li>
                        <li><a href="logout.php" class="btn-ghost">👋 Déconnexion</a></li>
                    <?php else: ?>
                        <li><a href="inscription.php" class="btn-primary">🎯 Inscription</a></li>
                        <li><a href="connexion.php" class="btn-secondary">🔑 Connexion</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <div class="page-notifications" id="pageNotifications">
        <?php
        // Récupérer les flashes via helper si disponible
        $flashes = [];
        if (function_exists('get_flashes')) {
            $flashes = get_flashes();
        } elseif (isset($_SESSION['flash']) && is_array($_SESSION['flash'])) {
            $flashes = $_SESSION['flash'];
            unset($_SESSION['flash']);
        }

        if (!empty($flashes)):
            foreach ($flashes as $type => $messages):
                foreach ($messages as $msg): ?>
                    <div class="flash flash-<?php echo htmlspecialchars($type); ?>" role="status">
                        <button class="flash-close" aria-label="Fermer">×</button>
                        <div class="flash-body"><?php echo htmlspecialchars($msg); ?></div>
                    </div>
                <?php endforeach;
            endforeach;
        endif;
        ?>
    </div>

    <script>
    // Auto-hide and close behavior for flash messages
    document.addEventListener('DOMContentLoaded', function() {
        var flashes = document.querySelectorAll('.flash');
        flashes.forEach(function(el) {
            // Auto remove after 4s
            var timeout = setTimeout(function() {
                el.classList.add('flash-hide');
                setTimeout(function(){ el.remove(); }, 400);
            }, 4000);

            // Close button
            var btn = el.querySelector('.flash-close');
            if (btn) btn.addEventListener('click', function(){ clearTimeout(timeout); el.classList.add('flash-hide'); setTimeout(function(){ el.remove(); }, 200); });
        });
    });
    </script>
