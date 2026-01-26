<?php

/* =====================================================
   SESSION & DÉPENDANCES
   ===================================================== */

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/autoload.php';

require_once __DIR__ . '/../app/Controllers/UserController.php';

// Si déjà connecté → redirection dashboard
if (isLogged()) {
    header('Location: dashboard.php');
    exit;
}

/* =====================================================
   TRAITEMENT FORMULAIRE
   ===================================================== */

$userController = new UserController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userController->login();
}

/* =====================================================
   HTML
   ===================================================== */
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion | Admin TechConnect RDC</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="auth-page">

<div class="auth-container">

    <h1>Connexion</h1>
    <p class="auth-subtitle">Accès fournisseur / administrateur</p>

    <!-- Messages -->
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert error">
            <?= htmlspecialchars($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert success">
            <?= htmlspecialchars($_SESSION['success']) ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <!-- FORMULAIRE -->
    <form method="POST" class="auth-form">

        <div class="form-group">
            <label for="email">Adresse email</label>
            <input type="email" name="email" id="email" required>
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" required>
        </div>

        <button type="submit" class="btn primary full-width">
            🔐 Se connecter
        </button>

    </form>

    <div class="auth-footer">
        <a href="../index.php">← Retour au site</a>
    </div>

</div>

</body>
</html>
