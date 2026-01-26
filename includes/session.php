<?php

// Démarrage de la session
// On vérifie d'abord qu'elle n'est pas déjà active
// (important pour éviter les erreurs "session already started")
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// --------------------------------------------------
//  FONCTIONS DE VÉRIFICATION DE SESSION / RÔLES
// --------------------------------------------------

/**
 * Vérifie si un utilisateur est connecté
 * 
 * Utilisé avant d'accéder à une page protégée
 * (dashboard, profil, messagerie, etc.)
 */
function isLogged(): bool
{
    return isset($_SESSION['user']);
}

/**
 * Vérifie si l'utilisateur connecté est un client
 * 
 * Utilisé pour :
 * - autoriser l'accès aux pages client
 * - bloquer l'accès aux fournisseurs/admins
 */
function isClient(): bool
{
    return isLogged() && $_SESSION['user']['role'] === 'client';
}

/**
 * Vérifie si l'utilisateur connecté est un fournisseur
 * 
 * Utilisé pour :
 * - autoriser l'accès aux pages fournisseur
 * - protéger les routes fournisseur
 */
function isFournisseur(): bool
{
    return isLogged() && $_SESSION['user']['role'] === 'fournisseur';
}

/**
 * Vérifie si l'utilisateur connecté est administrateur
 * 
 * Utilisé pour :
 * - accès au back-office
 * - gestion des utilisateurs, produits, etc.
 */
function isAdmin(): bool
{
    return isLogged() && $_SESSION['user']['role'] === 'admin';
}
