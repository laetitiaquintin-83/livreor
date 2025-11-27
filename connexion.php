<?php
require_once 'includes/config.php';

// Initialisation des variables
$error = '';
$success = '';
$login = '';

// Si l'utilisateur est déjà connecté, redirection
if (isset($_SESSION['user_id'])) {
    header("Location: livre-or.php");
    exit();
}

// Message de succès depuis inscription
if (isset($_GET['inscription']) && $_GET['inscription'] === 'success') {
    $success = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
}

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $login = trim($_POST['login']);
    $password = $_POST['password'];

    // Validation des champs
    if (empty($login) || empty($password)) {
        $error = "Tous les champs sont obligatoires.";
    } else {
        try {
            // Récupérer l'utilisateur depuis la base de données
            $stmt = $pdo->prepare("SELECT id, login, password FROM utilisateurs WHERE login = ?");
            $stmt->execute([$login]);
            $user = $stmt->fetch();

            // Vérifier si l'utilisateur existe et si le mot de passe est correct
            if ($user && password_verify($password, $user['password'])) {
                // Connexion réussie : créer les variables de session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['login'] = $user['login'];

                // Redirection vers le livre d'or (avec message de bienvenue)
                flash('success', 'Bienvenue ' . $user['login'] . ' !');
                redirect('livre-or.php');
            } else {
                // Identifiants incorrects
                $error = "Identifiants incorrects. Veuillez réessayer.";
            }
        } catch (PDOException $e) {
            $error = "Erreur de connexion. Veuillez réessayer plus tard.";
            // En développement : $error = $e->getMessage();
        }
    }
}

// Définir le titre de la page
$pageTitle = "Connexion";
require_once 'header.php';
?>

<main class="main-content">
    <div class="container">
        <div class="form-container">
            <div class="form-header">
                <h1>🗝️ Entrer dans le Sanctuaire</h1>
                <p class="form-subtitle">Retrouvez votre grimoire et vos sortilèges</p>
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

            <!-- Formulaire de connexion -->
            <form action="connexion.php" method="POST" class="form">
                
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
                        placeholder="Entrez votre nom d'utilisateur"
                        required
                        autocomplete="username"
                        autofocus
                    >
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
                        autocomplete="current-password"
                    >
                </div>

                <div class="form-group">
                    <button type="submit" class="submit-btn">
                        <span class="btn-icon">✨</span>
                        Ouvrir le portail
                    </button>
                </div>

                <div class="form-footer">
                    <p>Vous n'avez pas encore de compte ? 
                        <a href="inscription.php" class="link-primary">Créer un compte</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</main>

<?php require_once 'footer.php'; ?>