<?php
/**
 * Vue : Produits d’un fournisseur
 *
 * Affiche tous les produits publiés d’un fournisseur spécifique.
 * Le fournisseur est identifié par ?id= dans l’URL.
 */

require_once __DIR__ . '/../../Controllers/ProductController.php';

// Récupération et validation de l’ID fournisseur
$fournisseurId = (int) ($_GET['id'] ?? 0);

if ($fournisseurId <= 0) {
    http_response_code(404);
    exit('Fournisseur introuvable');
}

$controller = new ProductController();
$products = $controller->byFournisseur($fournisseurId);
?>

<?php require __DIR__ . '/../layout/header.php'; ?>
<?php require __DIR__ . '/../components/navbar.php'; ?>

<!-- CSS spécifique produits -->
<link rel="stylesheet" href="/assets/css/pages/product.css">

<!-- Injection des produits du fournisseur vers JavaScript -->
<script>
/**
 * Produits du fournisseur
 * Même structure que pour products/index.php
 */
const products = <?= json_encode(
    $products,
    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
); ?>;
</script>

<main class="container">

    <h1 class="section-title">Produits du fournisseur</h1>

    <?php if (empty($products)): ?>
        <p>Ce fournisseur n’a aucun produit publié pour le moment.</p>
    <?php endif; ?>

    <!-- Catalogue rempli dynamiquement par product.js -->
    <div class="grid" id="catalog"></div>

</main>

<script src="/assets/js/product.js" defer></script>

<?php require __DIR__ . '/../layout/footer.php'; ?>
