<?php
/**
 * Vue : À propos
 *
 * Page statique de présentation de TechConnect RDC.
 */
?>

<?php require __DIR__ . '/layout/header.php'; ?>
<link rel="stylesheet" href="/assets/css/pages/about.css">
<?php require __DIR__ . '/components/navbar.php'; ?>
<?php require __DIR__ . '/components/flash.php'; ?>

<!-- TITRE PRINCIPAL -->
<h1 class="first-title">TechConnect RDC</h1>

<section class="about-section">

    <!-- BLOC 1 -->
    <div class="bloc">
        <div class="bloc-image">
            <img src="/assets/img/autres/image4.jpg" alt="image de réseaux">
        </div>
        <div class="bloc-texte">
            <h2>Qui sommes-nous ?</h2>
            <p>
                Nous sommes une équipe de cinq jeunes passionnés de technologie,
                réunis depuis nos années universitaires autour d'un objectif commun :
                faciliter l'accès au matériel informatique professionnel en RDC.
            </p>
        </div>
    </div>

    <!-- BLOC 2 (INVERSÉ) -->
    <div class="bloc inverse">
        <div class="bloc-image">
            <img src="/assets/img/autres/image5.jpg" alt="image ordinateur">
        </div>
        <div class="bloc-texte">
            <h2>Notre vision</h2>
            <p>
                Devenir à long terme un acteur majeur du commerce électronique en RDC,
                contribuant à la digitalisation et à la modernisation du secteur
                technologique à travers des outils simples, efficaces et innovants.
            </p>
        </div>
    </div>

    <!-- BLOC 3 -->
    <div class="bloc">
        <div class="bloc-image">
            <img src="/assets/img/autres/image1.jpg" alt="image lampe">
        </div>
        <div class="bloc-texte">
            <h2>Simplicité, fiabilité, efficacité.</h2>
            <p>
                Notre objectif est clair : offrir à chaque entreprise, quelle que soit
                sa taille, des solutions technologiques accessibles, durables et prêtes
                à l'emploi. Grâce à une sélection rigoureuse de marques reconnues et à
                notre expertise technique, nous simplifions vos achats pour que vous
                puissiez vous concentrer sur ce qui compte : votre activité.
            </p>
        </div>
    </div>

    <!-- BLOC 4 (INVERSÉ) -->
    <div class="bloc inverse">
        <div class="bloc-image">
            <img src="/assets/img/autres/image3.jpg" alt="image de boxe">
        </div>
        <div class="bloc-texte">
            <h2>Notre mission : connecter les entreprises à la performance.</h2>
            <p>
                Nous accompagnons les professionnels dans leur transformation digitale
                en leur proposant du matériel informatique fiable, performant et adapté
                à leurs besoins : périphériques, accessoires, réseaux. Nous fournissons
                l'essentiel pour garantir la productivité et l'efficacité au quotidien.
            </p>
        </div>
    </div>

</section>

<h1 class="second-title">Notre équipe dirigeante</h1>

<section class="founders">

    <div class="founder">
        <img src="/assets/img/fondateurs/junior.jpg" alt="Junior Diyabanza Mazono">
        <h3>Junior DIYABANZA MAZONO</h3>
        <p>CO-CEO – Chief Executive Officer</p>
    </div>

    <div class="founder">
        <img src="/assets/img/fondateurs/esther.jpg" alt="Esther Nkisi Asha">
        <h3>Esther NKISI ASHA</h3>
        <p>CO-CEO – Chief Executive Officer</p>
    </div>

    <div class="founder">
        <img src="/assets/img/fondateurs/jeanine.jpg" alt="Jeanine Makunga Tondola">
        <h3>Jeanine MAKUNGA TONDOLA</h3>
        <p>CTO – Chief Technical Officer</p>
    </div>

    <div class="founder">
        <img src="/assets/img/fondateurs/messie.jpg" alt="Messie Wando Nguangua">
        <h3>Messie WANDO NGUANGUA</h3>
        <p>COO – Chief Operating Officer</p>
    </div>

    <div class="founder">
        <img src="/assets/img/fondateurs/astride.jpg" alt="Astride Mbamba Makaya">
        <h3>Astride MBAMBA MAKAYA</h3>
        <p>CMO – Chief Marketing Officer</p>
    </div>

</section>

<?php require __DIR__ . '/layout/footer.php'; ?>
