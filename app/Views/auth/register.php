<?php
/**
 * Vue : Inscription
 *
 * Permet à un utilisateur de créer un compte
 * en tant que client ou fournisseur.
 * Le traitement est géré par UserController via le router.
 */
?>

<?php require __DIR__ . '/../layout/header.php'; ?>
<?php require __DIR__ . '/../components/navbar.php'; ?>
<?php require __DIR__ . '/../components/flash.php'; ?>

<section id="inscription">
    <div class="container form-container">
        <h2>Créer un compte</h2>
        <p>Rejoignez la plateforme TechConnect RDC.</p>

        <!--
            Le formulaire est traité par :
            UserController::register() via le router
        -->
        <form action="/index.php?page=register" method="post" id="form-inscription">

            <!-- Choix du type de compte -->
            <label for="role">Type de compte</label>
            <select id="role" name="role" required>
                <option value="">-- Choisir --</option>
                <option value="client">Client</option>
                <option value="fournisseur">Fournisseur</option>
            </select>

            <label for="nom">Nom / Entreprise</label>
            <input
                type="text"
                id="nom"
                name="nom"
                required
            >

            <label for="email">Adresse e-mail</label>
            <input
                type="email"
                id="email"
                name="email"
                required
            >

            <label for="telephone">Numéro de téléphone</label>
            <input
                type="tel"
                id="telephone"
                name="telephone"
                placeholder="+243 XXX XXX XXX"
                required
            >

            <label for="password">Mot de passe</label>
            <input
                type="password"
                id="password"
                name="password"
                required
            >

            <label for="confirm_password">Confirmer le mot de passe</label>
            <input
                type="password"
                id="confirm_password"
                name="confirm_password"
                required
            >

            <button type="submit" class="btn-primary">
                Créer mon compte
            </button>

            <p class="login-link">
                Déjà inscrit ?
                <a href="/index.php?page=login">Se connecter</a>
            </p>
        </form>
    </div>
</section>

<?php require __DIR__ . '/../layout/footer.php'; ?>

</body>
</html>