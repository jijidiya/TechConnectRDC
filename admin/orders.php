<?php



require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/autoload.php';

require_once __DIR__ . '/../app/Controllers/OrderController.php';
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
   RÉCUPÉRATION DES COMMANDES
   ===================================================== */

$orderController = new OrderController();

// Fournisseur : commandes liées à ses produits
$orders = [];

if (isFournisseur()) {
    $orders = $orderController->fournisseurOrders();
}

// (Optionnel) Admin : voir toutes les commandes
if (isAdmin()) {
    $database = new Database();
    $pdo = $database->getConnection();

    $stmt = $pdo->query(
        "SELECT c.*, u.nom AS client_nom
         FROM commandes c
         JOIN users u ON c.user_id = u.id
         ORDER BY c.created_at DESC"
    );

    $orders = $stmt->fetchAll();
}

/* =====================================================
   HTML
   ===================================================== */
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Commandes | Dashboard</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<?php require_once __DIR__ . '/../includes/navbar.php'; ?>

<div class="admin-container">

    <h1>Commandes</h1>

    <?php if (empty($orders)): ?>
        <p>Aucune commande trouvée.</p>
    <?php else: ?>

        <table class="admin-table">
            <thead>
                <tr>
                    <?php if (isAdmin()): ?>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Total</th>
                        <th>Statut</th>
                        <th>Date</th>
                    <?php else: ?>
                        <th>ID Commande</th>
                        <th>Produit</th>
                        <th>Quantité</th>
                        <th>Prix</th>
                        <th>Statut</th>
                        <th>Date</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>

            <?php foreach ($orders as $order): ?>

                <?php if (isAdmin()): ?>
                    <tr>
                        <td>#<?= $order['id'] ?></td>
                        <td><?= htmlspecialchars($order['client_nom']) ?></td>
                        <td><?= number_format($order['total'], 2) ?> $</td>
                        <td><?= htmlspecialchars($order['status']) ?></td>
                        <td><?= date('d/m/Y', strtotime($order['created_at'])) ?></td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td>#<?= $order['commande_id'] ?></td>
                        <td><?= htmlspecialchars($order['produit_id']) ?></td>
                        <td><?= (int) $order['quantite'] ?></td>
                        <td><?= number_format($order['prix'], 2) ?> $</td>
                        <td><?= htmlspecialchars($order['statut']) ?></td>
                        <td><?= date('d/m/Y', strtotime($order['created_at'])) ?></td>
                    </tr>
                <?php endif; ?>

            <?php endforeach; ?>

            </tbody>
        </table>

    <?php endif; ?>

    <a href="dashboard.php" class="btn">↩ Retour au dashboard</a>

</div>

</body>
</html>
