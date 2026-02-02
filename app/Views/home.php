<?php
/**
 * Vue : Accueil
 *
 * Page d’accueil publique de TechConnect RDC.
 */
?>

<?php require __DIR__ . '/layout/header.php'; ?>

<!-- CSS SPÉCIFIQUE À LA PAGE D’ACCUEIL -->
<link rel="stylesheet" href="assets/css/pages/cards.css">

<?php require __DIR__ . '/components/flash.php'; ?>

<!-- ==================== SECTION HERO ==================== -->
<section id="hero">
    <img src="assets/img/banner.png" class="hero-image" alt="Banner">

    <div class="hero-content">
        <h2>Connecter les entreprises aux fournisseurs IT en RDC</h2>
        <p>
            Plateforme B2B dédiée à l’achat de matériel informatique en gros
            pour les entreprises, écoles et startups.
        </p>
        <a href="/index.php?page=catalog" class="btn-primary">Voir les produits</a>
    </div>
</section>

<!-- ==================== SECTION CATEGORIES ==================== -->
<section id="categories">
    <div class="container">
        <h2 class="section-title">Nos catégories de produits</h2>

        <div class="category-grid">

            <a href="index.php?page=catalog" class="category-card">
                <img src="assets/img/produits/computer1.jpg" alt="Ordinateurs">
                <h3>Ordinateurs & Laptops</h3>
                <p>PC portables et de bureau pour entreprises et institutions.</p>
            </a>

            <a href="index.php?page=catalog" class="category-card">
                <img src="assets/img/produits/computer1.jpg" alt="Réseaux">
                <h3>Réseaux & Télécoms</h3>
                <p>Routeurs, switchs et équipements réseau professionnels.</p>
            </a>

            <a href="index.php?page=catalog" class="category-card">
                <img src="assets/img/produits/Imprimante2.jpg" alt="Imprimantes">
                <h3>Imprimantes & Périphériques</h3>
                <p>Solutions d’impression et équipements bureautiques.</p>
            </a>

            <a href="index.php?page=catalog" class="category-card">
                <img src="assets/img/produits/computer1.jpg" alt="Accessoires">
                <h3>Accessoires informatiques</h3>
                <p>Câbles, écrans, claviers et périphériques divers.</p>
            </a>

        </div>
    </div>
</section>

<!-- ==================== SECTION PRODUITS EN VEDETTE ==================== -->
<section id="featured-products">
    <div class="container">
        <h2 class="section-title">Produits en vedette</h2>
        <p class="section-subtitle">
            Sélection de matériel informatique disponible à la vente en gros.
        </p>

        <div class="products-grid">

            <div class="product-card">
                <img src="assets/img/produits/computer1.jpg" alt="Ordinateur Dell">
                <h3>Dell OptiPlex 7090</h3>
                <p class="product-desc">PC professionnel pour entreprises et administrations.</p>
                <p class="product-price">Prix indicatif : 1 250 $</p>
                <a href="index.php?page=product&id=1" class="btn-secondary">Ajouter au panier</a>
            </div>

            <div class="product-card">
                <img src="assets/img/produits/computer2.jpg" alt="Switch réseau">
                <h3>Cisco Switch 24 ports</h3>
                <p class="product-desc">Équipement réseau haute performance.</p>
                <p class="product-price">Prix indicatif : 2 100 $</p>
                <a href="index.php?page=product&id=2" class="btn-secondary">Ajouter au panier</a>
            </div>

            <div class="product-card">
                <img src="assets/img/produits/Imprimante1.jpg" alt="Imprimante HP">
                <h3>HP LaserJet Pro</h3>
                <p class="product-desc">Imprimante laser professionnels.</p>
                <p class="product-price">Prix indicatif : 950 $</p>
                <a href="index.php?page=product&id=3" class="btn-secondary">Ajouter au panier</a>
            </div>

            <div class="product-card">
                <img src="assets/img/produits/computer2.jpg" alt="Accessoires informatiques">
                <h3>Kit Logitech</h3>
                <p class="product-desc">Clavier et souris pour postes professionnels.</p>
                <p class="product-price">Prix indicatif : 45 $</p>
                <a href="index.php?page=product&id=4" class="btn-secondary">Ajouter au panier</a>
            </div>

        </div>
    </div>
</section>

<!-- ==================== SECTION COMMENT ÇA MARCHE ==================== -->
<section id="how-it-works">
    <div class="container">
        <h2 class="section-title">Comment ça marche</h2>

        <div class="steps-grid">
            <div class="step-card">
                <span class="step-number">1</span>
                <h3>Choisissez une catégorie</h3>
                <p>Parcourez nos catégories de matériel informatique professionnel.</p>
            </div>

            <div class="step-card">
                <span class="step-number">2</span>
                <h3>Sélectionnez vos produits</h3>
                <p>Consultez les produits disponibles à la vente en gros.</p>
            </div>

            <div class="step-card">
                <span class="step-number">3</span>
                <h3>Demandez un devis</h3>
                <p>Envoyez une demande selon vos quantités et besoins.</p>
            </div>

            <div class="step-card">
                <span class="step-number">4</span>
                <h3>Livraison & suivi</h3>
                <p>Le fournisseur valide et assure la livraison ou le retrait.</p>
            </div>
        </div>
    </div>
</section>

<!-- ==================== SECTION SERVICES ==================== -->
<section id="services">
    <div class="container">
        <h2 class="section-title">Nos services B2B</h2>

        <div class="services-grid">
            <div class="service-card">
                <img src="assets/img/autres/service1.jpg" alt="Garantie">
                <h3>Garantie professionnelle</h3>
                <p>Tous nos produits bénéficient d’une garantie allant de 3 à 12 mois.</p>
            </div>

            <div class="service-card">
                <img src="assets/img/autres/service2.jpg" alt="Livraison">
                <h3>Livraison & distribution</h3>
                <p>Livraison à Kinshasa ou retrait dans nos points partenaires.</p>
            </div>

            <div class="service-card">
                <img src="assets/img/autres/service3.jpg" alt="Maintenance">
                <h3>Support & maintenance</h3>
                <p>Assistance technique et maintenance pour les équipements achetés.</p>
            </div>
        </div>
    </div>
</section>

<!-- ==================== APPEL À ACTION FOURNISSEUR ==================== -->
<section id="cta-section">
    <div class="container">
        <h2>Vous êtes un fournisseur ?</h2>
        <p>Rejoignez TechConnect RDC et touchez des clients professionnels.</p>
        <a href="index.php?page=register" class="btn-primary">
            Créer un compte fournisseur
        </a>
    </div>
</section>

<!-- ==================== FOURNISSEURS PARTENAIRES ==================== -->
<section id="fournisseurs-section">
    <div class="container">
        <h2 class="section-title">Nos fournisseurs partenaires</h2>

        <div class="fournisseurs-grid">
            <div class="fournisseur-card">
                <img src="assets/img/fournisseurs/f1.jpg" alt="Fournisseur 1">
                <h3>ElectroTech Congo</h3>
                <p>Distributeur agréé de matériel professionnel.</p>
            </div>

            <div class="fournisseur-card">
                <img src="assets/img/fournisseurs/f2.jpg" alt="Fournisseur 2">
                <h3>Global Systems</h3>
                <p>Solutions informatiques et réseaux d’entreprise.</p>
            </div>

            <div class="fournisseur-card">
                <img src="assets/img/fournisseurs/f3.jpg" alt="Fournisseur 3">
                <h3>KinTech RDC</h3>
                <p>Spécialiste en ordinateurs et serveurs professionnels.</p>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/layout/footer.php'; ?>
