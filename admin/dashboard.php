<?php

/* =====================================================
                 SÉCURITÉ & DÉPENDANCES
   ===================================================== */

require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/autoload.php';

require_once __DIR__ . '/../app/Controllers/UserController.php';
require_once __DIR__ . '/../app/Controllers/ProductController.php';
require_once __DIR__ . '/../app/Controllers/OrderController.php';
require_once __DIR__ . '/../app/Controllers/MessageController.php';

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
   INSTANCIATION DES CONTROLLERS
   ===================================================== */

$productController = new ProductController();
$orderController   = new OrderController();
$messageController = new MessageController();

/* =====================================================
   DONNÉES DU DASHBOARD
   ===================================================== */

// Produits du fournisseur
$products = [];
if (isFournisseur()) {
    // On récupère tous les produits du fournisseur via le modèle
    $fournisseurModel = new Fournisseur((new Database())->getConnection());
    $fournisseur = $fournisseurModel->getByUserId($_SESSION['user']['id']);

    if ($fournisseur) {
        $products = (new Product((new Database())->getConnection()))
            ->getByFournisseur($fournisseur['id']);
    }
}

// Commandes liées au fournisseur
$orders = isFournisseur() ? $orderController->fournisseurOrders() : [];

// Messages reçus
$messages = $messageController->inbox();

// Stats simples
$totalProduits = count($products);
$totalCommandes = count($orders);
$messagesNonLus = array_filter($messages, function ($m) {
    return $m['is_read'] == 0;
});

/* =====================================================
   HTML
   ===================================================== */
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Fournisseur | TechConnect RDC</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<?php require_once __DIR__ . '/../includes/navbar.php'; ?>

<div class="admin-container">

    <h1>Tableau de bord</h1>

    <p>Bienvenue,
        <strong><?= htmlspecialchars($_SESSION['user']['nom']) ?></strong>
    </p>

    <!-- STATS -->
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Produits</h3>
            <p><?= $totalProduits ?></p>
        </div>

        <div class="stat-card">
            <h3>Commandes</h3>
            <p><?= $totalCommandes ?></p>
        </div>

        <div class="stat-card">
            <h3>Messages non lus</h3>
            <p><?= count($messagesNonLus) ?></p>
        </div>
    </div>

    <!-- ACTIONS RAPIDES -->
    <div class="quick-actions">
        <a href="produits.php" class="btn">📦 Gérer mes produits</a>
        <a href="ajouter-produit.php" class="btn">➕ Ajouter un produit</a>
        <a href="commandes.php" class="btn">🧾 Voir les commandes</a>
        <a href="messages.php" class="btn">💬 Messages</a>
        <a href="logout.php" class="btn danger">🚪 Déconnexion</a>
    </div>

    <!-- DERNIÈRES COMMANDES -->
    <h2>Dernières commandes</h2>

    <?php if (empty($orders)): ?>
        <p>Aucune commande pour le moment.</p>
    <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (array_slice($orders, 0, 5) as $order): ?>
                    <tr>
                        <td>#<?= $order['commande_id'] ?></td>
                        <td><?= date('d/m/Y', strtotime($order['created_at'])) ?></td>
                        <td><?= htmlspecialchars($order['statut']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

</div>

</body>
</html>
