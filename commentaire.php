<?php
require_once 'includes/config.php';

// 9.1 Protection de la page
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

$errors = [];
$success = '';
$commentaire = '';

// 9.3 Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $commentaire = trim($_POST['commentaire'] ?? '');

    // Validation du commentaire
    if (empty($commentaire)) {
        $errors[] = "Le commentaire ne peut pas être vide.";
    } elseif (strlen($commentaire) < 10) {
        $errors[] = "Le commentaire doit contenir au moins 10 caractères.";
    } elseif (strlen($commentaire) > 5000) {
        $errors[] = "Le commentaire ne peut pas dépasser 5000 caractères.";
    }

    // Si pas d'erreurs, insertion en BDD
    if (empty($errors)) {
        try {
            // Récupérer l'id de l'utilisateur depuis la session
            $userId = $_SESSION['user_id'];
            
            // Préparer la requête d'insertion (colonne `contenu` et `date_creation` dans la BDD)
            $stmt = $pdo->prepare("INSERT INTO commentaires (id_utilisateur, contenu, date_creation) VALUES (?, ?, NOW())");

            // Exécuter la requête
            if ($stmt->execute([$userId, $commentaire])) {
                // Message flash et redirection immédiate
                flash('success', 'Votre commentaire a été publié avec succès !');
                redirect('livre-or.php');
            } else {
                $errors[] = "Une erreur est survenue lors de la publication. Veuillez réessayer.";
            }
        } catch (PDOException $e) {
            $errors[] = "Erreur de base de données. Veuillez réessayer plus tard.";
            // En développement : $errors[] = $e->getMessage();
        }
    }
}

// Définir le titre de la page
$pageTitle = "Ajouter un commentaire";
require_once 'includes/header.php';
?>

<main class="main-content">
    <div class="container">
        <div class="comment-form-container">
            
            <!-- En-tête -->
            <div class="form-header">
                <h1>✍️ Partager votre expérience</h1>
                <p class="form-subtitle">Laissez un message dans notre livre d'or</p>
                <div class="user-info">
                    <span class="user-badge">
                        📝 Vous publiez en tant que <strong><?php echo escape($_SESSION['login']); ?></strong>
                    </span>
                </div>
            </div>

            <!-- Messages d'erreur -->
            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <span class="alert-icon">⚠️</span>
                    <div class="alert-message">
                        <ul class="error-list">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo escape($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Message de succès -->
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <span class="alert-icon">✅</span>
                    <span class="alert-message"><?php echo escape($success); ?></span>
                </div>
            <?php endif; ?>

            <!-- Conseils de rédaction -->
            <div class="writing-tips">
                <h3>💡 Conseils pour votre commentaire</h3>
                <ul class="tips-list">
                    <li>✓ Soyez authentique et partagez votre véritable expérience</li>
                    <li>✓ Restez courtois et respectueux envers les autres</li>
                    <li>✓ Évitez les informations personnelles sensibles</li>
                    <li>✓ Minimum 10 caractères, maximum 5000 caractères</li>
                </ul>
            </div>

            <!-- Formulaire d'ajout de commentaire -->
            <form action="commentaire.php" method="POST" class="comment-form form" id="commentForm">
                
                <div class="form-group">
                    <label for="commentaire" class="form-label">
                        <span class="label-icon">💬</span>
                        Votre commentaire
                        <span class="char-counter">
                            <span id="charCount">0</span> / 5000
                        </span>
                    </label>
                    <textarea 
                        name="commentaire" 
                        id="commentaire" 
                        class="form-textarea"
                        rows="8"
                        placeholder="Partagez votre expérience, vos impressions, vos idées..."
                        required
                        minlength="10"
                        maxlength="5000"
                    ><?php echo escape($commentaire); ?></textarea>
                    <small class="form-hint">
                        Minimum 10 caractères. Utilisez des sauts de ligne pour structurer votre texte.
                    </small>
                </div>

                <!-- Aperçu du commentaire (optionnel) -->
                <div class="preview-section" id="previewSection" style="display: none;">
                    <h3>👁️ Aperçu</h3>
                    <div class="comment-preview" id="commentPreview"></div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="submit-btn">
                        <span class="btn-icon">🚀</span>
                        Publier mon commentaire
                    </button>
                    <button type="button" class="btn btn-secondary" id="previewBtn">
                        <span class="btn-icon">👁️</span>
                        Aperçu
                    </button>
                    <a href="livre-or.php" class="btn btn-outline">
                        <span class="btn-icon">◀️</span>
                        Retour au livre d'or
                    </a>
                </div>
            </form>

            <!-- Règles de la communauté -->
            <div class="community-rules">
                <h3>📜 Règles de la communauté</h3>
                <p>En publiant un commentaire, vous acceptez de respecter notre charte :</p>
                <ul>
                    <li>Pas de contenu offensant, discriminatoire ou haineux</li>
                    <li>Pas de spam ou de publicité</li>
                    <li>Pas de divulgation d'informations personnelles</li>
                    <li>Respect de la vie privée des autres utilisateurs</li>
                </ul>
            </div>

        </div>
    </div>
</main>

<!-- Script pour le compteur de caractères et l'aperçu -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('commentaire');
    const charCount = document.getElementById('charCount');
    const previewBtn = document.getElementById('previewBtn');
    const previewSection = document.getElementById('previewSection');
    const commentPreview = document.getElementById('commentPreview');
    
    // Compteur de caractères
    function updateCharCount() {
        const count = textarea.value.length;
        charCount.textContent = count;
        
        // Changer la couleur selon la longueur
        if (count < 10) {
            charCount.style.color = '#dc3545';
        } else if (count > 4500) {
            charCount.style.color = '#ffc107';
        } else {
            charCount.style.color = '#28a745';
        }
    }
    
    // Mise à jour au chargement et à chaque saisie
    updateCharCount();
    textarea.addEventListener('input', updateCharCount);
    
    // Aperçu du commentaire
    previewBtn.addEventListener('click', function() {
        if (previewSection.style.display === 'none') {
            const text = textarea.value.replace(/\n/g, '<br>');
            commentPreview.innerHTML = text || '<em>Aucun texte à prévisualiser</em>';
            previewSection.style.display = 'block';
            previewBtn.textContent = '❌ Masquer l\'aperçu';
        } else {
            previewSection.style.display = 'none';
            previewBtn.innerHTML = '<span class="btn-icon">👁️</span> Aperçu';
        }
    });
    
    // Confirmation avant de quitter si du texte a été saisi
    let initialValue = textarea.value;
    window.addEventListener('beforeunload', function(e) {
        if (textarea.value !== initialValue && textarea.value.trim() !== '') {
            e.preventDefault();
            e.returnValue = '';
        }
    });
    
    // Réinitialiser après soumission
    document.getElementById('commentForm').addEventListener('submit', function() {
        initialValue = '';
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>