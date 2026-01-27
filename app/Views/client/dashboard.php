<?php
/**
 * Vue : Dashboard client
 *
 * Cette page est affichée après la connexion d’un client.
 * Les données sont préparées par ClientController.
 */
?>

<?php require __DIR__ . '/../layout/header.php'; ?>
<?php require __DIR__ . '/../components/navbar.php'; ?>
<?php require __DIR__ . '/../components/flash.php'; ?>

<section class="dashboard">
    <div class="container">
        <h2>Tableau de bord</h2>

        <p>
            Bienvenue
            <strong><?= htmlspecialchars($_SESSION['user']['nom']) ?></strong>
            sur TechConnect RDC.
        </p>

        <div class="dashboard-actions">

            <div class="dashboard-card">
                <h3>Catalogue produits</h3>
                <p>Consultez les produits disponibles sur la plateforme.</p>
                <a href="/index.php?page=catalog" class="btn-primary">
                    Voir le catalogue
                </a>
            </div>

            <div class="dashboard-card">
                <h3>Mes commandes</h3>
                <p>Suivez l’état de vos commandes.</p>
                <a href="/index.php?page=client-orders" class="btn-primary">
                    Voir mes commandes
                </a>
            </div>

            <div class="dashboard-card">
                <h3>Messages</h3>
                <p>Discutez avec les fournisseurs.</p>
                <a href="/index.php?page=client-chat" class="btn-primary">
                    Accéder au chat
                </a>
            </div>

        </div>
    </div>
</section>

<?php require __DIR__ . '/../layout/footer.php'; ?>
