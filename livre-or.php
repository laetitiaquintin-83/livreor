<?php
require_once 'includes/config.php';

// 8.1 Affichage des commentaires avec comptage pour les rangs
try {
    // Récupérer les commentaires avec le nombre de commentaires par utilisateur
    $stmt = $pdo->prepare("
        SELECT c.id, c.contenu AS commentaire, c.date_creation AS date, u.login,
               (SELECT COUNT(*) FROM commentaires WHERE id_utilisateur = u.id) as nb_commentaires
        FROM commentaires c
        INNER JOIN utilisateurs u ON c.id_utilisateur = u.id
        ORDER BY c.date_creation DESC
    ");
    $stmt->execute();
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $comments = [];
    $error = "Impossible de charger les incantations.";
}

// Compter le nombre total de commentaires
$totalComments = count($comments);

// Définir le titre de la page
$pageTitle = "Le Grand Grimoire";
require_once 'header.php';

// Fonction pour formater les dates de manière mystique
function formatMysticDate($dateStr) {
    $date = new DateTime($dateStr);
    $mois = [
        1 => 'de la Lune du Loup', 2 => 'de la Lune de Neige', 3 => 'de la Lune du Ver',
        4 => 'de la Lune Rose', 5 => 'de la Lune des Fleurs', 6 => 'de la Lune des Fraises',
        7 => 'de la Lune du Tonnerre', 8 => 'de la Lune de l\'Esturgeon', 9 => 'de la Lune des Moissons',
        10 => 'de la Lune du Chasseur', 11 => 'de la Lune du Castor', 12 => 'de la Lune Froide'
    ];
    $jour = $date->format('j');
    $moisNum = (int)$date->format('n');
    $annee = $date->format('Y');
    return $jour . ' ' . $mois[$moisNum] . ', An ' . $annee;
}
?>

<main class="main-content">
    <div class="container">
        <div class="livre-or-container">
            
            <!-- En-tête du livre d'or -->
            <div class="livre-or-header">
                <h1>📜 Le Grand Grimoire</h1>
                <p class="livre-or-subtitle">Découvrez les sortilèges inscrits par les mages</p>
                <div class="comments-count">
                    <span class="count-number"><?php echo $totalComments; ?></span>
                    <span class="count-label"><?php echo $totalComments > 1 ? 'incantations' : 'incantation'; ?></span>
                </div>
            </div>

            <!-- Message d'erreur -->
            <?php if (isset($error)): ?>
                <div class="alert alert-error">
                    <span class="alert-icon">⚠️</span>
                    <span class="alert-message"><?php echo escape($error); ?></span>
                </div>
            <?php endif; ?>

            <!-- Bouton d'action selon l'état de connexion -->
            <div class="action-bar">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="commentaire.php" class="btn btn-primary btn-large">
                        <span class="btn-icon">✨</span>
                        Inscrire un sortilège
                    </a>
                <?php else: ?>
                    <div class="alert alert-info">
                        <span class="alert-icon">ℹ️</span>
                        <span class="alert-message">
                            Vous devez être connecté pour laisser un commentaire. 
                            <a href="connexion.php" class="link-primary">Se connecter</a> ou 
                            <a href="inscription.php" class="link-primary">Créer un compte</a>
                        </span>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Section des commentaires -->
            <section class="comments-section">
                <?php if ($comments): ?>
                    <div class="comments-list">
                        <?php foreach ($comments as $index => $comment): 
                            // Obtenir le rang du mage
                            $rank = getMageRank($comment['nb_commentaires']);
                        ?>
                            <article class="comment-card" data-comment-id="<?php echo $comment['id']; ?>">
                                <div class="comment-header">
                                    <div class="comment-author">
                                        <span class="author-avatar">
                                            <?php echo strtoupper(substr($comment['login'], 0, 1)); ?>
                                        </span>
                                        <div class="author-info">
                                            <span class="author-name">
                                                <?php echo escape($comment['login']); ?>
                                            </span>
                                            <span class="author-rank <?php echo $rank['class']; ?>">
                                                <?php echo $rank['icon'] . ' ' . $rank['title']; ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="comment-meta">
                                        <span class="comment-date">
                                            🌙 <?php echo formatMysticDate($comment['date']); ?>
                                        </span>
                                        <span class="comment-time">
                                            ✧ <?php 
                                                $date = new DateTime($comment['date']);
                                                echo $date->format('H:i'); 
                                            ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="comment-body">
                                    <p class="comment-text">
                                        <?php echo nl2br(escape($comment['commentaire'])); ?>
                                    </p>
                                </div>
                                
                                <div class="comment-footer">
                                    <span class="comment-number">Sortilège #<?php echo $totalComments - $index; ?></span>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-icon">📜</div>
                        <h2>Le grimoire attend son premier sortilège</h2>
                        <p>Soyez le premier mage à inscrire une incantation !</p>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="commentaire.php" class="btn btn-primary">
                                <span class="btn-icon">✍️</span>
                                Écrire le premier commentaire
                            </a>
                        <?php else: ?>
                            <a href="inscription.php" class="btn btn-primary">
                                <span class="btn-icon">🎯</span>
                                Créer un compte pour commenter
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </section>

            <!-- Statistiques magiques -->
            <?php if ($totalComments > 0): ?>
                <div class="livre-or-stats">
                    <h3>🔮 Archives Mystiques</h3>
                    <div class="stats-grid">
                        <div class="stat-item">
                            <span class="stat-icon">✨</span>
                            <span class="stat-value"><?php echo $totalComments; ?></span>
                            <span class="stat-label">Sortilèges gravés</span>
                        </div>
                        <?php
                        // Compter les utilisateurs uniques
                        $uniqueUsers = count(array_unique(array_column($comments, 'login')));
                        ?>
                        <div class="stat-item">
                            <span class="stat-icon">🧙</span>
                            <span class="stat-value"><?php echo $uniqueUsers; ?></span>
                            <span class="stat-label">Mages initiés</span>
                        </div>
                        <?php
                        // Commentaire le plus récent
                        $lastComment = new DateTime($comments[0]['date']);
                        $now = new DateTime();
                        $diff = $now->diff($lastComment);
                        
                        if ($diff->days == 0) {
                            $lastActivity = "Cette nuit";
                        } elseif ($diff->days == 1) {
                            $lastActivity = "Hier soir";
                        } elseif ($diff->days < 7) {
                            $lastActivity = "Il y a " . $diff->days . " lunes";
                        } else {
                            $lastActivity = "Il y a " . floor($diff->days / 7) . " semaines";
                        }
                        ?>
                        <div class="stat-item">
                            <span class="stat-icon">🌙</span>
                            <span class="stat-value"><?php echo $lastActivity; ?></span>
                            <span class="stat-label">Dernière incantation</span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</main>

<?php require_once 'footer.php'; ?>