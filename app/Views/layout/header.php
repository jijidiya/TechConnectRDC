<?php
/**
 * Layout : Header global
 *
 * Contient le <head> HTML et l’en-tête commun du site.
 * Les CSS/JS spécifiques aux pages sont chargés dans les vues.
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechConnect RDC - Plateforme B2B</title>

    <!-- CSS GLOBAUX -->
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/components.css">
    <link rel="stylesheet" href="assets/css/layout.css">

    <!-- Logo -->
    <link rel="icon" href="assets/img/logo.png">
</head>

<body>

<header id="header">
    <div class="container header-container">
        <div class="logo">
            <img src="assets/img/logo.png" alt="Logo TechConnect RDC">
            <h1>TechConnect RDC</h1>
        </div>

        <?php require __DIR__ . '/../components/navbar.php'; ?>
    </div>
</header>
