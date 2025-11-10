<?php
require_once 'config.php';

// 8.1 Affichage des commentaires
try {
    $stmt = $pdo->prepare("
        SELECT c.id, c.contenu AS commentaire, c.date_creation AS date, u.login 
        FROM commentaires c
        INNER JOIN utilisateurs u ON c.id_utilisateur = u.id
        ORDER BY c.date_creation DESC
    ");
    $stmt->execute();
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $comments = [];
    $error = "Impossible de charger les commentaires.";
}

// Compter le nombre total de commentaires
$totalComments = count($comments);

// Définir le titre de la page
$pageTitle = "Livre d'Or";
require_once 'header.php';
?>

<main class="main-content">
    <div class="container">
        <div class="livre-or-container">
            
            <!-- En-tête du livre d'or -->
            <div class="livre-or-header">
                <h1>📖 Livre d'Or</h1>
                <p class="livre-or-subtitle">Découvrez les témoignages de notre communauté</p>
                <div class="comments-count">
                    <span class="count-number"><?php echo $totalComments; ?></span>
                    <span class="count-label"><?php echo $totalComments > 1 ? 'commentaires' : 'commentaire'; ?></span>
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
                        <span class="btn-icon">✍️</span>
                        Ajouter mon commentaire
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
                        <?php foreach ($comments as $index => $comment): ?>
                            <article class="comment-card" data-comment-id="<?php echo $comment['id']; ?>">
                                <div class="comment-header">
                                    <div class="comment-author">
                                        <span class="author-avatar">
                                            <?php echo strtoupper(substr($comment['login'], 0, 1)); ?>
                                        </span>
                                        <span class="author-name">
                                            <?php echo escape($comment['login']); ?>
                                        </span>
                                    </div>
                                    <div class="comment-meta">
                                        <span class="comment-date">
                                            📅 Posté le 
                                            <?php 
                                                $date = new DateTime($comment['date']);
                                                echo $date->format('d/m/Y'); 
                                            ?>
                                        </span>
                                        <span class="comment-time">
                                            🕒 à <?php echo $date->format('H:i'); ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="comment-body">
                                    <p class="comment-text">
                                        <?php echo nl2br(escape($comment['commentaire'])); ?>
                                    </p>
                                </div>
                                
                                <div class="comment-footer">
                                    <span class="comment-number">#<?php echo $totalComments - $index; ?></span>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-icon">📭</div>
                        <h2>Aucun commentaire pour le moment</h2>
                        <p>Soyez le premier à partager votre expérience !</p>
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

            <!-- Statistiques supplémentaires (optionnel) -->
            <?php if ($totalComments > 0): ?>
                <div class="livre-or-stats">
                    <h3>📊 Statistiques</h3>
                    <div class="stats-grid">
                        <div class="stat-item">
                            <span class="stat-icon">💬</span>
                            <span class="stat-value"><?php echo $totalComments; ?></span>
                            <span class="stat-label">Commentaires</span>
                        </div>
                        <?php
                        // Compter les utilisateurs uniques
                        $uniqueUsers = count(array_unique(array_column($comments, 'login')));
                        ?>
                        <div class="stat-item">
                            <span class="stat-icon">👥</span>
                            <span class="stat-value"><?php echo $uniqueUsers; ?></span>
                            <span class="stat-label">Contributeurs</span>
                        </div>
                        <?php
                        // Commentaire le plus récent
                        $lastComment = new DateTime($comments[0]['date']);
                        $now = new DateTime();
                        $diff = $now->diff($lastComment);
                        
                        if ($diff->days == 0) {
                            $lastActivity = "Aujourd'hui";
                        } elseif ($diff->days == 1) {
                            $lastActivity = "Hier";
                        } else {
                            $lastActivity = "Il y a " . $diff->days . " jours";
                        }
                        ?>
                        <div class="stat-item">
                            <span class="stat-icon">🕐</span>
                            <span class="stat-value"><?php echo $lastActivity; ?></span>
                            <span class="stat-label">Dernière activité</span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</main>

<?php require_once 'footer.php'; ?>