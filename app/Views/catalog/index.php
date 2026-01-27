<?php
/**
 * Vue : Catalogue des produits
 *
 * Page publique affichant les produits disponibles.
 * Les données sont fournies par le controller.
 */
?>

<?php require __DIR__ . '/../layout/header.php'; ?>
<?php require __DIR__ . '/../components/navbar.php'; ?>
<?php require __DIR__ . '/../components/flash.php'; ?>

<section class="catalog">
    <div class="container">
        <h2>Catalogue des produits</h2>
        <p>Découvrez les produits proposés par nos fournisseurs.</p>

        <?php if (empty($products)): ?>
            <p>Aucun produit disponible pour le moment.</p>
        <?php else: ?>
            <div class="product-grid">

                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <h3><?= htmlspecialchars($product['nom']) ?></h3>

                        <p class="product-description">
                            <?= htmlspecialchars($product['description'] ?? '') ?>
                        </p>

                        <p class="product-price">
                            Prix :
                            <strong><?= htmlspecialchars($product['prix']) ?> $</strong>
                        </p>

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

<?php require __DIR__ . '/../layout/footer.php'; ?>
