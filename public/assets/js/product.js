/**
 * product.js
 *
 * Gère l’affichage dynamique de la page produit :
 *  - produits populaires
 *  - sélection d’un produit
 *  - affichage des détails
 *  - ajout au panier (localStorage)
 *
 * Les données viennent de la base de données (injectées par PHP).
 */

/**
 * Tableau des produits injecté par PHP
 *
 * Structure attendue pour chaque produit :
 * {
 *   id: number|string,
 *   title: string,
 *   description: string,
 *   price: number,
 *   images: string[]
 * }
 */
const laptops = products;

/**
 * Produit actuellement sélectionné
 */
let selected = null;

/**
 * Sécurise l’affichage de texte HTML
 */
function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/[&<>"']/g, function (m) {
        return {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;'
        }[m];
    });
}

/**
 * Affiche la liste des produits populaires
 */
function renderCatalog() {
    const grid = document.getElementById("catalog");
    grid.innerHTML = "";

    laptops.forEach((p, index) => {
        const card = document.createElement("article");
        card.className = "product-card";
        card.dataset.id = p.id;

        card.innerHTML = `
            <div class="product-thumb">
                <img
                    src="${p.images[0] ?? ''}"
                    alt="${escapeHtml(p.title)}"
                    loading="lazy"
                />
            </div>
            <div class="product-body">
                <div class="product-name">${escapeHtml(p.title)}</div>
                <div class="product-meta">
                    <span>$${Number(p.price).toFixed(2)}</span>
                    <span class="muted">Produit B2B</span>
                </div>
            </div>
        `;

        card.addEventListener("click", () => selectProduct(index, true));
        grid.appendChild(card);
    });
}

/**
 * Sélectionne un produit et met à jour l’affichage principal
 */
function selectProduct(index, focus = false) {
    const p = laptops[index];
    selected = p;

    // Titre, prix et description
    document.getElementById("hero-title").textContent = p.title;
    document.getElementById("hero-price").textContent = `$${Number(p.price).toFixed(2)}`;
    document.getElementById("hero-desc").textContent = p.description ?? '';

    // Image principale
    const mainImg = document.getElementById("hero-main-img");
    mainImg.src = p.images[0] ?? '';
    mainImg.alt = p.title;

    // Miniatures
    const thumbs = document.getElementById("hero-thumbs");
    thumbs.innerHTML = "";

    p.images.forEach((src, i) => {
        const thumb = document.createElement("div");
        thumb.className = "thumb";
        thumb.innerHTML = `
            <img src="${src}" alt="${escapeHtml(p.title)} vue ${i + 1}">
        `;
        thumb.addEventListener("click", () => {
            mainImg.src = src;
        });
        thumbs.appendChild(thumb);
    });

    // Message utilisateur
    document.getElementById("hero-feedback").textContent =
        "Produit sélectionné. Choisissez la quantité et ajoutez au panier.";

    document.getElementById("hero-qty").value = 1;

    if (focus) {
        document.querySelector('.product-hero')
            .scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

/**
 * Ajoute le produit sélectionné au panier (localStorage)
 */
function addToCart() {
    if (!selected) {
        document.getElementById("hero-feedback").textContent =
            "Veuillez sélectionner un produit.";
        return;
    }

    const qty = Math.max(
        1,
        parseInt(document.getElementById("hero-qty").value || "1", 10)
    );

    const item = {
        id: selected.id,
        title: selected.title,
        price: selected.price,
        quantity: qty,
        image: selected.images[0] ?? ''
    };

    const cart = JSON.parse(localStorage.getItem("cart") || "[]");
    const existing = cart.find(i => i.id === item.id);

    if (existing) {
        existing.quantity += item.quantity;
    } else {
        cart.push(item);
    }

    localStorage.setItem("cart", JSON.stringify(cart));

    document.getElementById("hero-feedback").textContent =
        "Produit ajouté au panier ✔";
}

/**
 * Initialisation de la page
 */
document.getElementById("add-btn").addEventListener("click", addToCart);

// Affichage initial
renderCatalog();
selectProduct(0);
