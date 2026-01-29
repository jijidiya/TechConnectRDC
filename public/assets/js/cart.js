/**
 * ======================================================
 *  PANIER - Nextgen Laptops
 * ======================================================
 * Ce fichier gère entièrement la logique du panier :
 *  - lecture / écriture dans localStorage
 *  - affichage des produits
 *  - mise à jour des quantités
 *  - suppression d’articles
 *  - calcul du total, des remises et des garanties
 *
 * Le panier est stocké dans le navigateur sous la clé :
 *      localStorage["cart"]
 *
 * Structure d’un article dans le panier :
 * {
 *   id: "macbook-air-m3",
 *   name: "MacBook Air M3",
 *   price: 1199,
 *   image: "Images/mac.jpg",
 *   quantity: 2,
 *   warrantyYears: 2,
 *   warrantyPrice: 49
 * }
 */


/* ======================================================
   OUTILS
   ====================================================== */

/**
 * Formate un nombre en dollars ($1234.56)
 */
function formatMoney(v){
    return `$${v.toFixed(2)}`;
}

/**
 * Sécurise une chaîne pour l'affichage HTML
 * Empêche les attaques XSS
 */
function escapeHtml(str){
    if(!str) return '';
    return String(str).replace(/[&<>"']/g, m =>
        ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m])
    );
}

/* ======================================================
   GESTION DU PANIER (localStorage)
   ====================================================== */

/**
 * Récupère le panier depuis le navigateur
 */
function getCart(){
    return JSON.parse(localStorage.getItem('cart') || '[]');
}

/**
 * Sauvegarde le panier dans le navigateur
 */
function saveCart(cart){
    localStorage.setItem('cart', JSON.stringify(cart));
}

/* ======================================================
   AFFICHAGE DU PANIER
   ====================================================== */

/**
 * Affiche tous les produits du panier dans la page
 */
function renderCart(){
    const cart = getCart();
    const list = document.getElementById('cart-list');
    list.innerHTML = '';

    // Si le panier est vide
    if(cart.length === 0){
        list.innerHTML = `<div class="empty">Votre panier est vide</div>`;
        updateSummary([]);
        return;
    }

    // Pour chaque produit du panier
    cart.forEach((item, index)=>{

        // Prix total pour cette ligne (produit + garantie)
        const lineTotal =
            (item.price + (item.warrantyPrice || 0)) * item.quantity;

        // Construction de la ligne HTML
        list.innerHTML += `
        <div class="cart-item">
            <div class="thumb">
                <img src="${item.image}" alt="${escapeHtml(item.name)}">
            </div>

            <div>
                <strong>${escapeHtml(item.name)}</strong><br>
                Prix unitaire : ${formatMoney(item.price)}<br>
                Garantie : ${item.warrantyYears || 1} an(s)
            </div>

            <!-- Quantité -->
            <input type="number" value="${item.quantity}" min="1"
                onchange="updateQty(${index},this.value)">

            <!-- Total ligne -->
            <div>${formatMoney(lineTotal)}</div>

            <!-- Supprimer -->
            <button onclick="removeItem(${index})">X</button>
        </div>
        `;
    });

    // Met à jour les totaux
    updateSummary(cart);
}

/* ======================================================
   ACTIONS UTILISATEUR
   ====================================================== */

/**
 * Modifie la quantité d’un article
 */
function updateQty(index, value){
    const cart = getCart();

    // Quantité minimale = 1
    cart[index].quantity = Math.max(1, parseInt(value));

    saveCart(cart);
    renderCart();
}

/**
 * Supprime un produit du panier
 */
function removeItem(index){
    const cart = getCart();

    cart.splice(index, 1); // enlève l’élément
    saveCart(cart);
    renderCart();
}

/* ======================================================
   CALCUL DU RÉCAPITULATIF
   ====================================================== */

/**
 * Calcule les montants (sous-total, garantie, remise, total)
 */
function updateSummary(cart){
    let subtotal = 0;
    let warranty = 0;

    cart.forEach(item=>{
        subtotal += item.price * item.quantity;
        warranty += (item.warrantyPrice || 0) * item.quantity;
    });

    // Règle de remise : 5% si >= 1500$
    let discount = subtotal >= 1500 ? subtotal * 0.05 : 0;

    let total = subtotal + warranty - discount;

    // Mise à jour de l’interface
    document.getElementById("subtotal").textContent = formatMoney(subtotal);
    document.getElementById("discount").textContent = "-" + formatMoney(discount);
    document.getElementById("warranty-total").textContent = formatMoney(warranty);
    document.getElementById("total").textContent = formatMoney(total);
}

/* ======================================================
   PAIEMENT
   ====================================================== */

document.getElementById("checkout-btn").onclick = ()=>{
    const cart = getCart();

    if(cart.length === 0){
        alert("Votre panier est vide.");
        return;
    }

    // Ici tu pourras connecter Stripe, PayPal, API backend, etc.
    alert("Paiement simulé");
};

/* ======================================================
   INITIALISATION
   ====================================================== */

window.onload = renderCart;
