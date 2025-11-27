<?php
// Démarre la session uniquement si elle n'existe pas déjà
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Système de rangs de mages
function getMageRank($nbCommentaires) {
    if ($nbCommentaires >= 50) return ['title' => 'Archimage Suprême', 'icon' => '👑', 'class' => 'rank-archimage'];
    if ($nbCommentaires >= 25) return ['title' => 'Maître des Arcanes', 'icon' => '🌟', 'class' => 'rank-master'];
    if ($nbCommentaires >= 10) return ['title' => 'Enchanteur', 'icon' => '✨', 'class' => 'rank-enchanter'];
    if ($nbCommentaires >= 5) return ['title' => 'Adepte', 'icon' => '🔮', 'class' => 'rank-adept'];
    if ($nbCommentaires >= 1) return ['title' => 'Apprenti', 'icon' => '📜', 'class' => 'rank-apprentice'];
    return ['title' => 'Novice', 'icon' => '🌱', 'class' => 'rank-novice'];
}

// Citations magiques aléatoires
$magicQuotes = [
    "« La magie est partout, il suffit d'ouvrir les yeux. » — Merlin",
    "« Les mots ont un pouvoir que même les plus grands mages ne maîtrisent pas. »",
    "« Chaque incantation écrite illumine l'obscurité. »",
    "« Le grimoire grandit avec chaque âme qui y inscrit sa sagesse. »",
    "« La vraie magie naît du cœur de ceux qui osent rêver. »",
    "« Un mot gravé dans le grimoire est éternel. »",
    "« Les étoiles guident ceux qui cherchent la connaissance. »"
];
$randomQuote = $magicQuotes[array_rand($magicQuotes)];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle . ' - Le Grimoire') : 'Le Grimoire'; ?></title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>📖</text></svg>">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body<?php echo (isset($pageTitle) && $pageTitle === 'Accueil') ? ' class="page-home"' : ''; ?>>
    
    <!-- Particules magiques -->
    <div class="magic-particles" id="magicParticles"></div>
    
    <!-- Coins décoratifs du grimoire -->
    <div class="grimoire-corner corner-top-left"></div>
    <div class="grimoire-corner corner-top-right"></div>
    <div class="grimoire-corner corner-bottom-left"></div>
    <div class="grimoire-corner corner-bottom-right"></div>

    <header class="site-header">
        <div class="container header-inner">
            <a href="index.php" class="site-logo" aria-label="Accueil - Grimoire" id="logoMagic">
                <span class="logo-icon">📖</span>
                <strong>Le Grimoire</strong><span class="logo-accent"> ✦ Enchanté</span>
                <span class="logo-sparkle">✨</span>
            </a>

            <button class="nav-toggle" id="navToggle" aria-expanded="false" aria-controls="mainNav">☰</button>

            <nav id="mainNav" class="main-nav">
                <ul class="nav-list">
                    <li><a href="index.php"><span class="nav-icon">🏰</span> Sanctuaire</a></li>
                    <li><a href="livre-or.php"><span class="nav-icon">📜</span> Grimoire</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="commentaire.php" class="nav-cta glow-effect"><span class="nav-icon">✨</span> Incanter</a></li>
                        <li class="user-menu">
                            <a href="profil.php" class="user-badge-nav">
                                <span class="mage-icon">🧙</span>
                                <span class="mage-name"><?php echo isset($_SESSION['login']) ? htmlspecialchars($_SESSION['login']) : 'Mage'; ?></span>
                            </a>
                        </li>
                        <li><a href="logout.php" class="btn-ghost"><span class="nav-icon">🌙</span> Partir</a></li>
                    <?php else: ?>
                        <li><a href="inscription.php" class="btn btn-primary glow-effect"><span class="nav-icon">🔮</span> Initiation</a></li>
                        <li><a href="connexion.php" class="btn btn-secondary"><span class="nav-icon">🗝️</span> Entrer</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
        
        <!-- Flamme magique animée -->
        <div class="header-flame">
            <span class="flame">🕯️</span>
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
                        <span class="flash-icon"><?php echo $type === 'success' ? '✨' : ($type === 'error' ? '⚠️' : '🔮'); ?></span>
                        <button class="flash-close" aria-label="Fermer">×</button>
                        <div class="flash-body"><?php echo htmlspecialchars($msg); ?></div>
                    </div>
                <?php endforeach;
            endforeach;
        endif;
        ?>
    </div>

    <script>
    // Particules magiques flottantes
    (function() {
        const container = document.getElementById('magicParticles');
        if (!container) return;
        
        const symbols = ['✦', '✧', '⋆', '✶', '✷', '✸', '⭐', '💫'];
        const particleCount = 25;
        
        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('span');
            particle.className = 'particle';
            particle.textContent = symbols[Math.floor(Math.random() * symbols.length)];
            particle.style.left = Math.random() * 100 + '%';
            particle.style.animationDelay = Math.random() * 15 + 's';
            particle.style.animationDuration = (15 + Math.random() * 20) + 's';
            particle.style.fontSize = (0.5 + Math.random() * 1) + 'rem';
            particle.style.opacity = 0.3 + Math.random() * 0.5;
            container.appendChild(particle);
        }
    })();
    
    // Easter egg : clic sur le logo
    (function() {
        const logo = document.getElementById('logoMagic');
        let clickCount = 0;
        if (!logo) return;
        
        logo.addEventListener('click', function(e) {
            clickCount++;
            if (clickCount >= 5) {
                e.preventDefault();
                document.body.classList.add('rainbow-mode');
                setTimeout(() => document.body.classList.remove('rainbow-mode'), 3000);
                clickCount = 0;
            }
        });
    })();
    
    // Flash messages auto-hide
    document.addEventListener('DOMContentLoaded', function() {
        var flashes = document.querySelectorAll('.flash');
        flashes.forEach(function(el) {
            var timeout = setTimeout(function() {
                el.classList.add('flash-hide');
                setTimeout(function(){ el.remove(); }, 400);
            }, 4000);

            var btn = el.querySelector('.flash-close');
            if (btn) btn.addEventListener('click', function(){ 
                clearTimeout(timeout); 
                el.classList.add('flash-hide'); 
                setTimeout(function(){ el.remove(); }, 200); 
            });
        });
    });
    </script>
