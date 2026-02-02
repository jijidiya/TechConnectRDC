<?php
/**
 * Vue : Détail produit
 *
 * Données issues de la table `produits`
 * - title
 * - description
 * - price
 * - quantity
 * - images (JSON)
 */
?>

<?php require __DIR__ . '/../layout/header.php'; ?>
<?php require __DIR__ . '/../components/flash.php'; ?>

<main class="container">

<?php if (!$product): ?>
    <h2>Produit introuvable</h2>
    <p>Ce produit n’est pas disponible.</p>

    <a href="/index.php?page=catalog" class="btn primary">
        Retour au catalogue
    </a>

<?php else: ?>

<section class="product-hero">

    <!-- MEDIA PRODUIT -->
    <div class="media card">
        <div class="media-main">
            <?php
                $mainImage = $product['images'][0] ?? 'default.png';
            ?>
            <img
                src="/assets/img/products/<?= htmlspecialchars($mainImage) ?>"
                alt="<?= htmlspecialchars($product['title']) ?>"
            >
        </div>

        <?php if (count($product['images']) > 1): ?>
            <div class="thumbs">
                <?php foreach ($product['images'] as $img): ?>
                    <div class="thumb">
                        <img
                            src="/assets/img/products/<?= htmlspecialchars($img) ?>"
                            alt="<?= htmlspecialchars($product['title']) ?>"
                        >
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- INFOS PRODUIT -->
    <aside class="card">
        <h1 class="title"><?= htmlspecialchars($product['title']) ?></h1>

        <div class="price">
            <?= number_format($product['price'], 2) ?> $
        </div>

        <p class="muted">
            <?= nl2br(htmlspecialchars($product['description'] ?? 'Aucune description.')) ?>
        </p>

        <p class="muted">
            Stock disponible :
            <strong><?= (int)$product['quantity'] ?></strong>
        </p>

        <?php if ($product['quantity'] > 0): ?>
            <form method="post" action="/index.php?page=cart-add">
                <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">

                <div class="row">
                    <div>
                        <label class="muted">Quantité</label>
                        <input
                            type="number"
                            name="quantity"
                            min="1"
                            max="<?= (int)$product['quantity'] ?>"
                            value="1"
                        >
                    </div>
                </div>

                <div class="actions">
                    <button type="submit" class="btn primary">
                        Ajouter au panier
                    </button>

                    <a href="/index.php?page=catalog" class="btn">
                        Retour
                    </a>
                </div>
            </form>
        <?php else: ?>
            <p class="muted"><strong>Produit en rupture de stock</strong></p>
        <?php endif; ?>
    </aside>

</section>

<?php endif; ?>

</main>

<?php require __DIR__ . '/../layout/footer.php'; ?>
