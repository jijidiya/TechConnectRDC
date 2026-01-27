<?php
/**
 * Flash messages
 *
 * Affiche les messages stockés en session
 * puis les supprime après affichage.
 */
?>

<?php if (!empty($_SESSION['success'])): ?>
    <div class="flash flash-success">
        <?= htmlspecialchars($_SESSION['success']) ?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['error'])): ?>
    <div class="flash flash-error">
        <?= htmlspecialchars($_SESSION['error']) ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>
