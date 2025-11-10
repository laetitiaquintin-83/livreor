<?php
require_once 'config.php';
$pageTitle = "Accueil";
require_once 'header.php';
?>

<main class="main-content">
    <div class="container">
        <!-- Section Hero / Bienvenue -->
        <section class="hero-section">
            <div class="hero-content">
                <h1 class="hero-title">🎉 Hey! Bienvenue dans notre Super Livre d'Or! 🌈</h1>
                <p class="hero-subtitle">Prêt(e) à partager ta super expérience et à découvrir les messages géniaux de notre fantastique communauté? C'est parti! 🚀</p>
            </div>
        </section>

        <!-- Section présentation -->
        <section class="welcome-section">
            <div class="welcome-content">
                <h2>🎨 Notre Livre d'Or Magique, c'est quoi?</h2>
                <p>C'est comme un journal secret, mais en mieux! Ici, tu peux :</p>
                <ul class="features-list">
                    <li>🎭 Partager tes aventures et tes impressions les plus folles</li>
                    <li>🎪 Explorer les histoires incroyables de la communauté</li>
                    <li>✨ Créer ton message unique et coloré</li>
                    <li>� Faire partie de quelque chose de magique!</li>
                </ul>
            </div>
        </section>

        <!-- Section statistiques (optionnel) -->
        <section class="stats-section">
            <div class="stats-grid">
                <?php
                try {
                    // Compter les utilisateurs
                    $stmtUsers = $pdo->query("SELECT COUNT(*) as total FROM utilisateurs");
                    $totalUsers = $stmtUsers->fetch()['total'];
                    
                    // Compter les commentaires
                    $stmtComments = $pdo->query("SELECT COUNT(*) as total FROM commentaires");
                    $totalComments = $stmtComments->fetch()['total'];
                    
                    // Récupérer le dernier commentaire (colonne date_creation dans la BDD)
                    $stmtLast = $pdo->query("SELECT MAX(date_creation) as last_date FROM commentaires");
                    $lastComment = $stmtLast->fetch()['last_date'];
                } catch(PDOException $e) {
                    $totalUsers = 0;
                    $totalComments = 0;
                    $lastComment = null;
                }
                ?>
                
                <div class="stat-card">
                    <div class="stat-icon">🎭</div>
                    <div class="stat-number"><?php echo $totalUsers; ?></div>
                    <div class="stat-label">Super Héros Inscrits</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">�</div>
                    <div class="stat-number"><?php echo $totalComments; ?></div>
                    <div class="stat-label">Messages Magiques</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">🎪</div>
                    <div class="stat-number">
                        <?php 
                        if($lastComment) {
                            $date = new DateTime($lastComment);
                            echo $date->format('d/m/Y');
                        } else {
                            echo "Sois le 1er! ✨";
                        }
                        ?>
                    </div>
                    <div class="stat-label">Dernière Aventure</div>
                </div>
            </div>
        </section>

        <!-- Section appels à l'action -->
        <section class="cta-section">
            <h2>🚀 Commencez dès maintenant</h2>
            <div class="cta-buttons">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Utilisateur connecté -->
                    <a href="commentaire.php" class="btn btn-primary">
                        ✍️ Laisser un commentaire
                    </a>
                    <a href="livre-or.php" class="btn btn-secondary">
                        📖 Consulter le livre d'or
                    </a>
                <?php else: ?>
                    <!-- Utilisateur non connecté -->
                    <a href="inscription.php" class="btn btn-primary">
                        🎯 Créer un compte
                    </a>
                    <a href="connexion.php" class="btn btn-secondary">
                        🔑 Se connecter
                    </a>
                    <a href="livre-or.php" class="btn btn-outline">
                        👀 Voir le livre d'or
                    </a>
                <?php endif; ?>
            </div>
        </section>

        <!-- Section derniers commentaires (optionnel) -->
        <section class="recent-comments">
            <h2>💭 Derniers commentaires</h2>
            <?php
            try {
                // Récupérer les 3 derniers commentaires
                    $stmt = $pdo->query("
                        SELECT c.contenu AS commentaire, c.date_creation AS date, u.login 
                        FROM commentaires c
                        INNER JOIN utilisateurs u ON c.id_utilisateur = u.id
                        ORDER BY c.date_creation DESC
                        LIMIT 3
                    ");
                $recentComments = $stmt->fetchAll();
                
                if(count($recentComments) > 0): ?>
                    <div class="comments-preview">
                        <?php foreach($recentComments as $comment): ?>
                            <div class="comment-preview-card">
                                <p class="comment-preview-text">
                                    "<?php echo escape(substr($comment['commentaire'], 0, 100)); ?><?php echo strlen($comment['commentaire']) > 100 ? '...' : ''; ?>"
                                </p>
                                <p class="comment-preview-meta">
                                    Par <strong><?php echo escape($comment['login']); ?></strong>
                                    le <?php 
                                        $date = new DateTime($comment['date']);
                                        echo $date->format('d/m/Y à H:i');
                                    ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="text-center">
                        <a href="livre-or.php" class="btn-link">Voir tous les commentaires →</a>
                    </div>
                <?php else: ?>
                    <p class="empty-state">Aucun commentaire pour le moment. Soyez le premier à laisser un message ! ✨</p>
                <?php endif;
            } catch(PDOException $e) {
                echo '<p class="error-message">Impossible de charger les commentaires.</p>';
            }
            ?>
        </section>
    </div>
</main>

<?php require_once 'footer.php'; ?>