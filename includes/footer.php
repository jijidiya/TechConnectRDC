<?php 
// Variable 
$email = 'contact@techconnect-rdc.com';
$telephone = '+243 810 000 111';
$adresse = 'Kinshasa, RDC';
?>


<footer id="footer">
    <div class="container footer-container">
        <div class="footer-info">
            <h3>TechConnect RDC</h3>
            <p>La première plateforme e-commerce B2B pour le matériel informatique en RDC.</p>
        </div>

        <div class="footer-links">
            <h4>Navigation</h4>
            <ul>
                <li><a href="index.html">Accueil</a></li>
                <li><a href="produits.html">Produits</a></li>
                <li><a href="contact.html">Contact</a></li>
                <li><a href="about.html">Contact</a></li>
            </ul>
        </div>

        <div class="footer-contact">
            <h4>Contact</h4>
            <p>Email :echo $email </p>
            <p>Téléphone : echo $telephone</p>
            <p>echo $adresse</p>
        </div>
    </div>

    <div class="footer-bottom">
        <p>© 2025 TechConnect RDC — Tous droits réservés</p>
    </div>
</footer>