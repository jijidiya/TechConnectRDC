<?php
/**
 * Vue : FAQ / Questions fréquentes
 */
?>

<?php require __DIR__ . '/../layout/header.php'; ?>

<!-- CSS SPÉCIFIQUE FAQ -->
<link rel="stylesheet" href="assets/css/pages/faq.css">

<!-- JS SPÉCIFIQUE FAQ -->
<script src="assets/js/faq.js" defer></script>


<?php require __DIR__ . '/../components/flash.php'; ?>

<h1 class="section-title">FAQ - Questions fréquentes</h1>

<section class="faq-container">

    <div class="faq-item">
        <div class="question">Comment passer une commande ?</div>
        <div class="answer">
            Pour passer une commande, choisissez vos produits,
            ajoutez-les au panier et suivez le processus de paiement.
        </div>
    </div>

    <div class="faq-item">
        <div class="question">Quels moyens de paiement sont acceptés ?</div>
        <div class="answer">
            Nous acceptons les cartes bancaires, PayPal et le paiement à la livraison.
        </div>
    </div>

    <div class="faq-item">
        <div class="question">Comment suivre ma commande ?</div>
        <div class="answer">
            Vous recevrez un email contenant un lien de suivi dès l’expédition.
        </div>
    </div>

    <div class="faq-item">
        <div class="question">Puis-je annuler ou modifier ma commande ?</div>
        <div class="answer">
            Oui, tant que la commande n’a pas été expédiée.
            Contactez le service client le plus vite possible.
        </div>
    </div>

    <div class="faq-item">
        <div class="question">Quels sont les délais de livraison ?</div>
        <div class="answer">
            Les livraisons prennent entre 3 et 7 jours ouvrables selon votre localisation.
        </div>
    </div>

    <div class="faq-item">
        <div class="question">Proposez-vous des retours ou échanges ?</div>
        <div class="answer">
            Oui, sous 14 jours, à condition que le produit soit dans son état d’origine.
        </div>
    </div>

</section>

<?php require __DIR__ . '/../layout/footer.php'; ?>
