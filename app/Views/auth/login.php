<?php
/**
 * Vue : Connexion
 *
 * Cette page affiche le formulaire de connexion.
 * La logique de connexion est gérée par UserController via le router.
 */
?>
 
<?php require __DIR__ . '/../layout/header.php'; ?>
<?php require __DIR__ . '/../components/flash.php'; ?>

<section id="connexion">
    <div class="container form-container">
        <h2 class="title">Connexion</h2>
        <p>Connectez-vous à votre compte pour accéder à la plateforme.</p>

        <!--
            Le formulaire envoie les données vers le router.
            Le traitement est fait dans UserController::login()
        -->
        <form action="index.php?page=login" method="post" id="form-connexion">
            <label for="email">Adresse e-mail</label>
            <input
                type="email"
                id="email"
                name="email"
                required
            >

            <label for="mot_de_passe">Mot de passe</label>
            <input
                type="password"
                id="mot_de_passe"
                name="password"
                required
            >

            <button type="submit" class="btn-primary">
                Se connecter
            </button>

            <p class="login-link">
                Pas encore inscrit ?
                <a href="index.php?page=register">Créer un compte</a>
            </p>
        </form>
    </div>
</section>

<?php require __DIR__ . '/../layout/footer.php'; ?>
