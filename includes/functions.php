<?php
// Helpers généraux pour l'application

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Ajouter un message flash en session.
 * Ex: flash('success', 'Opération réussie');
 */
function flash(string $type, string $message): void {
    if (!isset($_SESSION['flash']) || !is_array($_SESSION['flash'])) {
        $_SESSION['flash'] = [];
    }
    if (!isset($_SESSION['flash'][$type])) {
        $_SESSION['flash'][$type] = [];
    }
    $_SESSION['flash'][$type][] = $message;
}

/**
 * Récupère et vide les messages flash.
 * Retourne un tableau associatif [type => [messages...]]
 */
function get_flashes(): array {
    $flashes = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $flashes;
}

/** Petite aide pour redirection propre */
function redirect(string $url): void {
    header('Location: ' . $url);
    exit();
}

?>
