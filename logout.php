<?php
require_once 'config.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Si pas connecté, rediriger vers l'accueil
    header("Location: index.php");
    exit();
}

// Détruire toutes les variables de session
$_SESSION = [];

// Si on veut détruire complètement la session, on doit aussi supprimer le cookie de session
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Détruire la session
session_destroy();

// Redirection vers la page d'accueil avec message de confirmation
header("Location: index.php?logout=success");
exit();
?>