<?php
session_start();

// Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'livreor');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Connexion
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    
} catch (PDOException $e) {
    // En développement
    if ($_SERVER['SERVER_NAME'] === 'localhost') {
        die("Erreur DB : " . $e->getMessage());
    }
    // En production
    else {
        error_log($e->getMessage());
        die("Une erreur est survenue. Veuillez contacter l'administrateur.");
    }
}

// Fonction helper pour protéger contre XSS
function escape($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Charger les helpers (flash, redirect, etc.)
require_once __DIR__ . '/includes/functions.php';
?>