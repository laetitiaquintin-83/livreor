<?php
require_once 'includes/config.php';
$pageTitle = "Accueil";
require_once 'header.php';
?>

<main class="main-content">
    <!-- Section Hero Animée -->
    <div class="hero-wave">
        <div class="container">
            <section class="hero-section">
                <div class="hero-content">
                    <div class="hero-badge">
                        <span>🌟</span> Bienvenue dans le sanctuaire
                    </div>
                    
                    <div class="hero-text-wrapper">
                        <h1 class="hero-title">
                            <span class="title-line">Inscrivez votre</span>
                            <span class="text-accent magic-text">✨ sortilège ✨</span>
                        </h1>
                        <p class="hero-subtitle">Chaque incantation gravée dans ces pages anciennes résonne à travers les âges...</p>
                    </div>
                    
                    <div class="hero-features">
                        <div class="feature-item">
                            <span class="feature-icon">📜</span>
                            <span>Grimoire éternel</span>
                        </div>
                        <div class="feature-item">
                            <span class="feature-icon">🧙</span>
                            <span>Guilde des mages</span>
                        </div>
                        <div class="feature-item">
                            <span class="feature-icon">⚗️</span>
                            <span>Rangs mystiques</span>
                        </div>
                    </div>
                    
                    <div class="hero-cta">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="commentaire.php" class="btn btn-primary btn-large glow-effect">
                                <span class="btn-icon">✨</span>
                                <span>Graver une incantation</span>
                            </a>
                            <a href="livre-or.php" class="btn btn-outline">
                                <span class="btn-icon">📖</span>
                                <span>Ouvrir le Grimoire</span>
                            </a>
                        <?php else: ?>
                            <a href="inscription.php" class="btn btn-primary btn-large glow-effect">
                                <span class="btn-icon">🔮</span>
                                <span>Rejoindre la Guilde</span>
                            </a>
                            <a href="livre-or.php" class="btn btn-outline">
                                <span class="btn-icon">👁️</span>
                                <span>Consulter les sortilèges</span>
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <div class="hero-scroll-hint">
                        <span class="scroll-icon">↓</span>
                        <span>Découvrir les archives</span>
                    </div>
                </div>
                
            </section>
        </div>
    </div>

    <!-- Section Statistiques avec Cards Modernes -->
        <section class="stats-section">
            <h2 class="stats-title">🔮 Archives du Sanctuaire</h2>
            <div class="stats-grid">
                <?php
                try {
                    // Compter les utilisateurs et commentaires
                    $stmtUsers = $pdo->query("SELECT COUNT(*) as total FROM utilisateurs");
                    $totalUsers = $stmtUsers->fetch()['total'];
                    
                    $stmtComments = $pdo->query("SELECT COUNT(*) as total FROM commentaires");
                    $totalComments = $stmtComments->fetch()['total'];
                    
                    $stmtLast = $pdo->query("SELECT MAX(date_creation) as last_date FROM commentaires");
                    $lastComment = $stmtLast->fetch()['last_date'];
                } catch(PDOException $e) {
                    $totalUsers = 0;
                    $totalComments = 0;
                    $lastComment = null;
                }
                ?>
                
                <div class="stat-card">
                    <div class="stat-icon-large">🧙</div>
                    <div class="stat-number"><?php echo $totalUsers; ?></div>
                    <div class="stat-label">Mages initiés</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon-large">✨</div>
                    <div class="stat-number"><?php echo $totalComments; ?></div>
                    <div class="stat-label">Sortilèges gravés</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon-large">🌙</div>
                    <div class="stat-number">
                        <?php 
                        if($lastComment) {
                            $date = new DateTime($lastComment);
                            $now = new DateTime();
                            $diff = $now->diff($date);
                            if ($diff->days == 0) {
                                echo "Aujourd'hui";
                            } elseif ($diff->days == 1) {
                                echo "Hier";
                            } else {
                                echo "Il y a " . $diff->days . "j";
                            }
                        } else {
                            echo "—";
                        }
                        ?>
                    </div>
                    <div class="stat-label">Dernière incantation</div>
                </div>
            </div>
            
            <!-- Citation magique -->
            <div class="magic-quote">
                <?php echo $randomQuote; ?>
            </div>
        </section>
    </div>
</main>

<?php require_once 'footer.php'; ?>