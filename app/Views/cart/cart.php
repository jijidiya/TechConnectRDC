<?php
/**
 * Vue : Panier
 *
 * Le panier est géré côté client via localStorage.
 * Cette vue affiche simplement l’interface.
 */
?>

<?php require __DIR__ . '/../layout/header.php'; ?>
<link rel="stylesheet" href="assets/css/pages/cart.css">

<main class="container">
    <h1 class="title">Votre panier</h1>

    <div class="cart-grid">
        <div>
            <div
                class="cart-list"
                id="cart-list"
                aria-live="polite"
            ></div>
        </div>

        <aside class="summary">
            <div class="summary-title">
                Récapitulatif de la commande
            </div>

            <div class="row">
                <span>Sous-total</span>
                <span id="subtotal">$0.00</span>
            </div>

            <div class="row">
                <span>Remise</span>
                <span id="discount">-$0.00</span>
            </div>

            <div class="row">
                <span>Livraison</span>
                <span id="shipping">GRATUITE</span>
            </div>

            <div class="row">
                <span>Garantie totale</span>
                <span id="warranty-total">$0.00</span>
            </div>

            <div class="row total">
                <span>Total</span>
                <span id="total">$0.00</span>
            </div>

            <div class="actions">
                <button class="btn primary" id="checkout-btn">
                    Payer maintenant
                </button>
                <a href="/index.php?page=product" class="btn">
                    Continuer vos achats
                </a>
            </div>
        </aside>
    </div>
</main>

<script src="assets/js/cart.js"></script>

<?php require __DIR__ . '/../layout/footer.php'; ?>
