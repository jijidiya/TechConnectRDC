<?php
// Vue catalogue : affiche la liste des produits
// $products est fourni par le router (via ProductController)
?>

<?php
// Header global
require __DIR__ . '/../layout/header.php';
?>

<!-- CSS spécifique aux cartes produits -->
<link rel="stylesheet" href="assets/css/pages/cards.css">

<?php
require __DIR__ . '/../components/flash.php';
?>

<section class="catalog">
    <div class="container">

        <h2 class="section-title">Catalogue des produits</h2>
        <p class="section-subtitle">Découvrez les produits proposés par nos fournisseurs.</p>

        <?php if (empty($products)): ?>
            <p>Aucun produit disponible pour le moment.</p>
        <?php else: ?>

            <div class="products-grid">

                <?php foreach ($products as $product): ?>

                    <?php
                    // Décodage du champ images (JSON)
                    // On récupère la première image si elle existe
                    $images = [];
                    if (!empty($product['images'])) {
                        $images = json_decode($product['images'], true);
                    }

                    $imagePath = !empty($images[0])
                        ? '/TechConnectRDC/uploads/products/' . $images[0]
                        : '/assets/img/placeholder.png';
                    ?>

                    <div class="product-card">

                        <!-- Image du produit -->
                        <div class="product-image">
                            <img
                                src="<?= htmlspecialchars($imagePath) ?>"
                                alt="<?= htmlspecialchars($product['title']) ?>"
                            >
                        </div>

                        <!-- Nom du produit -->
                        <h3>
                            <?= htmlspecialchars($product['title']) ?>
                        </h3>

                        <!-- Description -->
                        <p class="product-description">
                            <?= htmlspecialchars($product['description'] ?? '') ?>
                        </p>

                        <!-- Prix -->
                        <p class="product-price">
                            Prix :
                            <strong>
                                <?= number_format($product['price'], 2) ?> $
                            </strong>
                        </p>

                        <!-- Lien vers la page détail -->
                        <a
                            href="/index.php?page=product&id=<?= (int)$product['id'] ?>"
                            class="btn-primary"
                        >
                            Voir le produit
                        </a>

                    </div>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </div>
</section>

<?php
// Footer global
require __DIR__ . '/../layout/footer.php';
?>
