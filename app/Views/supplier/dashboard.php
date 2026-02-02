<?php
/**
 * Vue : Dashboard fournisseur
 *
 * Page affichée après la connexion d’un fournisseur.
 * Les contrôles d’accès sont gérés par le router et le controller.
 */
?>

<?php require __DIR__ . '/../layout/header.php'; ?>
<?php require __DIR__ . '/../components/flash.php'; ?>

<section class="dashboard">
    <div class="container">
        <h2>Tableau de bord fournisseur</h2>

        <p>
            Bienvenue
            <strong><?= htmlspecialchars($_SESSION['user']['nom']) ?></strong>,
            gérez votre activité sur TechConnect RDC.
        </p>

        <div class="dashboard-actions">

            <div class="dashboard-card">
                <h3>Mes produits</h3>
                <p>Ajoutez, modifiez et gérez vos produits.</p>
                <a href="/index.php?page=supplier-products" class="btn-primary">
                    Gérer mes produits
                </a>
            </div>

            <div class="dashboard-card">
             
            <h3>Commandes</h3>
                <p>Consultez les commandes liées à vos produits.</p>
                <a href="/index.php?page=supplier-orders" class="btn-primary">
                    Voir les commandes
                </a>
            </div>

            <div class="dashboard-card">
                <h3>Messages</h3>
                <p>Discutez avec vos clients.</p>
                <a href="/index.php?page=supplier-chat" class="btn-primary">
                    Accéder aux messages
                </a>
            </div>

        </div>
    </div>
</section>

<?php require __DIR__ . '/../layout/footer.php'; ?>
