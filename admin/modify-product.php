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
   RÉCUPÉRATION PRODUIT
   ===================================================== */

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Produit invalide');
}

$productId = (int) $_GET['id'];

$database = new Database();
$pdo = $database->getConnection();

$productModel = new Product($pdo);
$product = $productModel->getById($productId);

if (!$product) {
    die('Produit introuvable');
}

/* =====================================================
   SÉCURITÉ : LE PRODUIT APPARTIENT-IL AU FOURNISSEUR ?
   ===================================================== */

$fournisseurModel = new Fournisseur($pdo);
$fournisseur = $fournisseurModel->getByUserId($_SESSION['user']['id']);

if (!$fournisseur || $product['fournisseur_id'] != $fournisseur['id']) {
    die('Accès interdit à ce produit');
}

/* =====================================================
   TRAITEMENT FORMULAIRE
   ===================================================== */

$productController = new ProductController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productController->update($productId);
}

/* =====================================================
   HTML
   ===================================================== */
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier produit | Dashboard</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<?php require_once __DIR__ . '/../includes/navbar.php'; ?>

<div class="admin-container">

    <h1>Modifier le produit</h1>

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
            <input type="text" name="title" id="title"
                   value="<?= htmlspecialchars($product['title']) ?>" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" rows="4"><?= htmlspecialchars($product['description']) ?></textarea>
        </div>

        <div class="form-group">
            <label for="price">Prix</label>
            <input type="number" name="price" id="price" step="0.01"
                   value="<?= htmlspecialchars($product['price']) ?>" required>
        </div>

        <div class="form-group">
            <label for="quantity">Quantité en stock</label>
            <input type="number" name="quantity" id="quantity"
                   value="<?= (int) $product['quantity'] ?>" required>
        </div>

        <!-- Image actuelle -->
        <?php if (!empty($product['images'])): ?>
            <?php $imgs = json_decode($product['images'], true); ?>
            <?php if (!empty($imgs[0])): ?>
                <div class="form-group">
                    <label>Image actuelle</label><br>
                    <img src="../uploads/produits/<?= htmlspecialchars($imgs[0]) ?>"
                         alt="Image produit"
                         style="max-width:150px;">
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="form-group">
            <label for="image">Nouvelle image (optionnel)</label>
            <input type="file" name="image" id="image" accept="image/*">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn primary">💾 Enregistrer</button>
            <a href="produits.php" class="btn">↩ Retour</a>
        </div>

    </form>

</div>

</body>
</html>
