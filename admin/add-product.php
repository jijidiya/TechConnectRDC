<?php


require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/autoload.php';

require_once __DIR__ . '/../app/Controllers/ProductController.php';
require_once __DIR__ . '/../app/Controllers/UserController.php';

// Accès réservé aux utilisateurs connectés
if (!isLogged()) {
    header('Location: login.php');
    exit;
}

// Accès réservé aux fournisseurs
if (!isFournisseur()) {
    die('Accès refusé');
}

/* =====================================================
   TRAITEMENT FORMULAIRE
   ===================================================== */

$productController = new ProductController();

// Si formulaire soumis → on délègue au controller
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productController->store();
}

/* =====================================================
   HTML
   ===================================================== */
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un produit | Dashboard</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<?php require_once __DIR__ . '/../includes/navbar.php'; ?>

<div class="admin-container">

    <h1>Ajouter un produit</h1>

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
    <form method="POST" enctype="multipart/form-data" class="admin-form">

        <div class="form-group">
            <label for="title">Nom du produit</label>
            <input type="text" name="title" id="title" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" rows="4"></textarea>
        </div>

        <div class="form-group">
            <label for="price">Prix</label>
            <input type="number" name="price" id="price" step="0.01" required>
        </div>

        <div class="form-group">
            <label for="quantity">Quantité en stock</label>
            <input type="number" name="quantity" id="quantity" value="0" required>
        </div>

        <div class="form-group">
            <label for="image">Image du produit</label>
            <input type="file" name="image" id="image" accept="image/*">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn primary">➕ Ajouter le produit</button>
            <a href="produits.php" class="btn">↩ Retour</a>
        </div>

    </form>

</div>

</body>
</html>
    