<?php

declare(strict_types=1);

/**
 * Point d’entrée unique de l’application
 *
 * - Initialise l’environnement
 * - Démarre la session
 * - Délègue le traitement au routeur
 */

// ======================================================
// SESSION
// ======================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ======================================================
// ROUTEUR
// ======================================================

require_once __DIR__ . '/router.php';
