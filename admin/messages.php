<?php



require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/autoload.php';

require_once __DIR__ . '/../app/Controllers/MessageController.php';
require_once __DIR__ . '/../app/Controllers/UserController.php';

// Accès réservé aux utilisateurs connectés
if (!isLogged()) {
    header('Location: login.php');
    exit;
}

// Accès réservé aux fournisseurs et admin
if (!isFournisseur() && !isAdmin()) {
    die('Accès refusé');
}

/* =====================================================
                    CONTROLLER
   ===================================================== */

$messageController = new MessageController();

/* =====================================================
                ACTION ENVOI MESSAGE
   ===================================================== */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $messageController->send();
}

/* =====================================================
            RÉCUPÉRATION DES DONNÉES
   ===================================================== */

// Boîte de réception
$messages = $messageController->inbox();

// Discussion sélectionnée
$conversation = [];
$receiverId = null;

if (isset($_GET['user']) && is_numeric($_GET['user']()_
