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

// Accès réservé aux fournisseurs et admin
if (!isFournisseur() && !isAdmin()) {
    die('Accès refusé');
}

/* =====================================================
   RÉCUPÉRATION DES PRODUITS
   ===================================================== */

$database = new Database();
$pdo = $database->getConnection();

$productController = new ProductController();

// Pour fournisseur : uniquement SES produits
$products = [];

if (isFournisseur()) {
    $fournisseurModel = new Fournisseur($pdo);
    $fournisseur = $fournisseurModel->getByUserId($_SESSION['user']['id']);

    if ($fournisseur) {
        $productModel = new Product($pdo);
        $products = $productModel->getByFournisseur($fournisseur['id']);
    }
}

// (Optionnel) admin : voir tous les produits
if (isAdmin()) {
    $productModel = new Product($pdo);
    $products = $productModel->getAll();
}

/* =====================================================
   ACTION SUPPRESSION (GET simple)
   ===================================================== */

if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $productController->destroy((int) $_GET['delete']);
}

/* =====================================================
   HTML
   ===================================================== */
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Produits | Dashboard</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<?php require_once __DIR__ . '/../includes/navbar.php'; ?>

<div class="admin-container">

    <h1>Gestion des produits</h1>

    <a href="ajouter-produit.php" class="btn primary">➕ Ajouter un produit</a>

    <?php if (empty($products)): ?>
        <p>Aucun produit trouvé.</p>
    <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Produit</th>
                    <th>Prix</th>
                    <th>Stock</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td>#<?= $product['id'] ?></td>
                        <td><?= htmlspecialchars($product['title']) ?></td>
                        <td><?= number_format($product['price'], 2) ?> $</td>
                        <td><?= (int) $product['quantity'] ?></td>
                        <td><?= htmlspecialchars($product['status']) ?></td>
                        <td>
                            <a href="modifier-produit.php?id=<?= $product['id'] ?>" class="btn small">
                                ✏️ Modifier
                            </a>

                            <a href="produits.php?delete=<?= $product['id'] ?>"
                               class="btn danger small"
                               onclick="return confirm('Supprimer ce produit ?');">
                                🗑 Supprimer
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

</div>

</body>
</html>
