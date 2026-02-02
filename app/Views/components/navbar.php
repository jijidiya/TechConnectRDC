<?php
require_once __DIR__ . '/../../Controllers/UserController.php';
?>


<nav id="navbar">
    <ul>

        <!-- Navigation publique -->
        <li>
            <a href="index.php?page=home"
               class="<?= ($_GET['page'] ?? 'home') === 'home' ? 'active' : '' ?>">
                Accueil
            </a>
        </li>

        <li>
            <a href="index.php?page=products"
               class="<?= ($_GET['page'] ?? '') === 'products' ? 'active' : '' ?>">
                Produits
            </a>
        </li>

        <li>
            <a href="index.php?page=fournisseurs"
               class="<?= ($_GET['page'] ?? '') === 'fournisseurs' ? 'active' : '' ?>">
                Fournisseurs
            </a>
        </li>

        <li>
            <a href="index.php?page=contact"
               class="<?= ($_GET['page'] ?? '') === 'contact' ? 'active' : '' ?>">
                Contact
            </a>
        </li>

        <!-- UTILISATEUR NON CONNECTÉ -->
        <?php if (!UserController::check()): ?>

            <li>
                <a href="index.php?page=register" class="btn-login">
                    Créer un compte
                </a>
            </li>

            <li>
                <a href="index.php?page=login" class="btn-login">
                    Se connecter
                </a>
            </li>

        <!-- UTILISATEUR CONNECTÉ -->
        <?php else: ?>

            <?php if (UserController::isRole('client')): ?>
                <li>
                    <a href="index.php?page=client-dashboard" class="btn-login">
                        Mon espace
                    </a>
                </li>
            <?php endif; ?>

            <?php if (UserController::isRole('fournisseur')): ?>
                <li>
                    <a href="index.php?page=supplier-dashboard" class="btn-login">
                        Dashboard fournisseur
                    </a>
                </li>
            <?php endif; ?>

            <li>
                <a href="index.php?page=logout" class="btn-login">
                    Déconnexion
                </a>
            </li>

        <?php endif; ?>

    </ul>
</nav>
