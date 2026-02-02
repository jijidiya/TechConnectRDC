<?php
/**
 * Dashboard Client
 *
 * Page principale de l’espace client connecté.
 * Le client peut accéder rapidement aux fonctionnalités clés.
 */

require_once __DIR__ . '/../../Controllers/UserController.php';

// Sécurité : uniquement client connecté
if (!UserController::check() || !UserController::isRole('client')) {
    http_response_code(403);
    exit('Accès refusé');
}

$user = UserController::user();
?>

<?php require __DIR__ . '/../layout/header.php'; ?>
<?php require __DIR__ . '/../components/navbar.php'; ?>

<main class="container">

    <h1>Tableau de bord client</h1>

    <p>
        Bienvenue <strong><?= htmlspecialchars($user['nom']) ?></strong>,
        vous êtes connecté en tant que client.
    </p>

    <div class="dashboard-grid">

        <!-- PROFIL -->
        <div class="dashboard-card">
            <h3>Mon profil</h3>
            <p>Consulter et gérer vos informations personnelles.</p>
            <a href="/index.php?page=client-profile" class="btn">
                Voir mon profil
            </a>
        </div>

        <!-- PRODUITS -->
        <div class="dashboard-card">
            <h3>Produits</h3>
            <p>Parcourir le catalogue des produits disponibles.</p>
            <a href="/index.php?page=products" class="btn">
                Voir les produits
            </a>
        </div>

        <!-- PANIER -->
        <div class="dashboard-card">
            <h3>Mon panier</h3>
            <p>Consulter les produits ajoutés à votre panier.</p>
            <a href="/index.php?page=cart" class="btn">
                Voir le panier
            </a>
        </div>

        <!-- COMMANDES -->
        <div class="dashboard-card">
            <h3>Mes commandes</h3>
            <p>Suivre vos commandes passées.</p>
            <a href="/index.php?page=client-orders" class="btn">
                Voir mes commandes
            </a>
        </div>

        <!-- MESSAGES -->
        <div class="dashboard-card">
            <h3>Messages</h3>
            <p>Discuter avec les fournisseurs.</p>
            <a href="/index.php?page=client-messages" class="btn">
                Voir mes messages
            </a>
        </div>

    </div>

</main>

<?php require __DIR__ . '/../layout/footer.php'; ?>
