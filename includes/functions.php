<?php

// Nettoie une chaîne de caractères entrée par l'utilisateur
// - supprime les espaces inutiles (trim)
// - empêche l'injection de HTML/JS (XSS)
function sanitize($str) {
    return htmlspecialchars(trim($str), ENT_QUOTES, 'UTF-8');
}

// Redirige l'utilisateur vers une autre page
// Utilisé après un login, un logout, un formulaire, etc.
function redirect($url) {
    header("Location: $url"); // envoie l'en-tête HTTP de redirection
    exit; // stoppe le script immédiatement
}

// Fonction de debug rapide
// Affiche proprement une variable puis arrête le script
// Ca va nous sauver la vie lors du developpement !
function dd($data) {
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    exit;
}
?>
