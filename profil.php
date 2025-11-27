<?php
require_once 'includes/config.php';

// 7.1 Protection de la page
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

$userId = $_SESSION['user_id'];
$success = '';
$errors = [];

// Récupérer les informations actuelles de l'utilisateur
try {
    $stmt = $pdo->prepare("SELECT login FROM utilisateurs WHERE id = ?");
    $stmt->execute([$userId]);
    $currentUser = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$currentUser) {
        // L'utilisateur n'existe plus en BDD (cas rare)
        session_destroy();
        header("Location: connexion.php");
        exit();
    }
} catch (PDOException $e) {
    die("Erreur lors de la récupération du profil.");
}

// 7.3 Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newLogin = trim($_POST['login'] ?? '');
    $newPassword = trim($_POST['password'] ?? '');
    $confirmPassword = trim($_POST['confirm_password'] ?? '');

    // Validation du login
    if (empty($newLogin)) {
        $errors[] = "Le login ne peut pas être vide.";
    } elseif (strlen($newLogin) < 3) {
        $errors[] = "Le login doit contenir au moins 3 caractères.";
    }

    // Vérifier que le login n'est pas déjà pris par un autre utilisateur
    if (empty($errors) && $newLogin !== $currentUser['login']) {
        try {
            $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE login = ? AND id != ?");
            $stmt->execute([$newLogin, $userId]);
            if ($stmt->fetch()) {
                $errors[] = "Ce login est déjà utilisé par un autre utilisateur.";
            }
        } catch (PDOException $e) {
            $errors[] = "Erreur lors de la vérification du login.";
        }
    }

    // Si mot de passe fourni, vérifier confirmation et longueur
    $hashedPassword = null;
    if (!empty($newPassword)) {
        if (strlen($newPassword) < 6) {
            $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
        } elseif ($newPassword !== $confirmPassword) {
            $errors[] = "Le mot de passe et sa confirmation ne correspondent pas.";
        } else {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        }
    }

    // Si pas d'erreurs, mise à jour en BDD
    if (empty($errors)) {
        try {
            if ($hashedPassword) {
                // Mise à jour login ET mot de passe
                $stmt = $pdo->prepare("UPDATE utilisateurs SET login = ?, password = ? WHERE id = ?");
                $stmt->execute([$newLogin, $hashedPassword, $userId]);
            } else {
                // Mise à jour login uniquement
                $stmt = $pdo->prepare("UPDATE utilisateurs SET login = ? WHERE id = ?");
                $stmt->execute([$newLogin, $userId]);
            }

            // Mettre à jour la session si login modifié
            if ($newLogin !== $currentUser['login']) {
                $_SESSION['login'] = $newLogin;
            }
            
            $currentUser['login'] = $newLogin;
            // Utiliser flash et redirection pour éviter resoumission
            flash('success', 'Votre identité mystique a été modifiée avec succès !');
            redirect('profil.php');
        } catch (PDOException $e) {
            $errors[] = "Erreur lors de la mise à jour du profil.";
        }
    }
}

// Inclusion du header et affichage du formulaire
$pageTitle = 'Mon Grimoire Personnel';
require_once 'includes/header.php';
?>

<main class="main-content">
    <div class="container">
        <div class="form-container">
            <div class="form-header">
                <h1>🔮 Mon Grimoire Personnel</h1>
                <p class="form-subtitle">Modifiez votre identité mystique</p>
            </div>

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

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <span class="alert-icon">✨</span>
                    <span class="alert-message"><?php echo escape($success); ?></span>
                </div>
            <?php endif; ?>

            <form action="" method="post" class="form">
                <div class="form-group">
                    <label for="login" class="form-label">✦ Nom de Mage</label>
                    <input type="text" name="login" id="login" class="form-input" value="<?php echo escape($currentUser['login']); ?>" required placeholder="Votre nom mystique">
                    <small class="form-hint">Ce nom apparaîtra sur vos incantations</small>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">🔐 Nouvelle formule secrète</label>
                    <input type="password" id="password" name="password" class="form-input" placeholder="Laissez vide pour conserver l'actuelle">
                    <small class="form-hint">Minimum 6 caractères runiques</small>
                </div>

                <div class="form-group">
                    <label for="confirm_password" class="form-label">🔐 Confirmer la formule</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-input" placeholder="Répétez la formule secrète">
                </div>

                <button type="submit" class="submit-btn">✨ Sceller les modifications</button>
            </form>

            <div class="form-footer">
                <a href="index.php" class="btn btn-outline">📖 Retour au Sanctuaire</a>
            </div>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>