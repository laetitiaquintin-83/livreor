<?php
require_once 'includes/config.php';

// Initialisation des variables
$error = '';
$success = '';
$login = '';

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $login = trim($_POST['login']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation des champs
    if (empty($login) || empty($password) || empty($confirm_password)) {
        $error = "Tous les champs sont obligatoires.";
    } elseif (strlen($password) < 6) {
        $error = "Le mot de passe doit contenir au moins 6 caractères.";
    } elseif ($password !== $confirm_password) {
        $error = "Les mots de passe ne correspondent pas !";
    } else {
        try {
            // Vérifier si le login existe déjà
            $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE login = ?");
            $stmt->execute([$login]);

            if ($stmt->fetch()) {
                $error = "Ce nom d'utilisateur est déjà utilisé. Veuillez en choisir un autre.";
            } else {
                // Hash du mot de passe
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                
                // Insertion en base de données
                $stmt = $pdo->prepare("INSERT INTO utilisateurs (login, password) VALUES (?, ?)");
                
                if ($stmt->execute([$login, $password_hash])) {
                    // Utiliser flash et redirection propre
                    flash('success', 'Inscription réussie ! Vous pouvez maintenant vous connecter.');
                    redirect('connexion.php');
                } else {
                    $error = "Une erreur est survenue lors de l'inscription. Veuillez réessayer.";
                }
            }
        } catch (PDOException $e) {
            $error = "Erreur de base de données. Veuillez réessayer plus tard.";
            // En développement, tu peux afficher : $error = $e->getMessage();
        }
    }
}

// Définir le titre de la page
$pageTitle = "Inscription";
require_once 'header.php';
?>

<main class="main-content">
    <div class="container">
        <div class="form-container">
            <div class="form-header">
                <h1>🔮 Rejoindre la Guilde</h1>
                <p class="form-subtitle">Créez votre grimoire personnel et inscrivez vos sortilèges</p>
            </div>

            <!-- Messages d'erreur et de succès -->
            <?php if ($error): ?>
                <div class="alert alert-error">
                    <span class="alert-icon">⚠️</span>
                    <span class="alert-message"><?php echo escape($error); ?></span>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <span class="alert-icon">✅</span>
                    <span class="alert-message"><?php echo escape($success); ?></span>
                </div>
            <?php endif; ?>

            <!-- Formulaire d'inscription -->
            <form action="inscription.php" method="POST" class="form" novalidate>
                
                <div class="form-group">
                    <label for="login" class="form-label">
                        <span class="label-icon">👤</span>
                        Nom d'utilisateur
                    </label>
                    <input 
                        type="text" 
                        name="login" 
                        id="login" 
                        class="form-input"
                        value="<?php echo escape($login); ?>" 
                        placeholder="Choisissez votre nom d'utilisateur"
                        required
                        autocomplete="username"
                        minlength="3"
                        maxlength="255"
                    >
                    <small class="form-hint">Minimum 3 caractères</small>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">
                        <span class="label-icon">🔒</span>
                        Mot de passe
                    </label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        class="form-input"
                        placeholder="Entrez votre mot de passe"
                        required 
                        autocomplete="new-password"
                        minlength="6"
                    >
                    <small class="form-hint">Minimum 6 caractères</small>
                </div>

                <div class="form-group">
                    <label for="confirm_password" class="form-label">
                        <span class="label-icon">🔑</span>
                        Confirmer le mot de passe
                    </label>
                    <input 
                        type="password" 
                        name="confirm_password" 
                        id="confirm_password" 
                        class="form-input"
                        placeholder="Confirmez votre mot de passe"
                        required 
                        autocomplete="new-password"
                        minlength="6"
                    >
                </div>

                <div class="form-group">
                    <button type="submit" class="submit-btn">
                        <span class="btn-icon">✨</span>
                        Commencer l'initiation
                    </button>
                </div>

                <div class="form-footer">
                    <p>Vous avez déjà un compte ? 
                        <a href="connexion.php" class="link-primary">Se connecter</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</main>

<?php require_once 'footer.php'; ?>