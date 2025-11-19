<?php
// Démarrer la session pour détecter si  un utilisateur 
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

//Vérifie le role (client ou fournisseur)

$role = $_SESSION['role'] ?? null;
$nomUtilisateur = $_SESSION['nom'] ?? null;

?>


<header id="header">
    <div class="container header-container">
        <a href="index.html" class="active">
            <div class="logo">
                <img src="assets/img/logo.jpg" alt="Logo TechConnect RDC">
                <h1>TechConnect RDC</h1>
            </div>
        </a>
        <nav id="navbar">
            <ul>
                <li><a href="index.html" class="active">Accueil</a></li>
                <li><a href="categories.html">categories</a></li>
                <li><a href="fournisseurs.html">Fournisseurs</a></li>
                <li><a href="contact.html">Contact</a></li>
                <li><a href="inscription-client.html" class="btn-login">Créer un compte</a></li>
            </ul>
        </nav>
    </div>
</header>
