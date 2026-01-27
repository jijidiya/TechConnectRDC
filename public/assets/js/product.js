const laptops = [
    {
    id: "macbook-air-m3",
    name: "Apple MacBook Air M3 (13\")",
    price: 1199.00,
    desc: "Puce Apple M3, écran 13.6\" Liquid Retina, 8–24 Go RAM, SSD 256–2 To. Ultra‑léger et autonome.",
    specs: [
        "Processeur: Apple M3",
        "Écran: 13.6\" Liquid Retina",
        "RAM: 8 / 16 / 24 Go",
        "Stockage: 256 Go – 2 To SSD",
        "Poids: ~1.24 kg"
    ],
    images: [
        "Images/Image1.jpg",
        "Images/Image11.jpg",
        "Images/Image12.jpg",
        "Images/Image13.jpg"
    ]
    },
    {
    id: "dell-xps-13",
    name: "Dell XPS 13 Plus (2024)",
    price: 1399.00,
    desc: "Ultrabook premium, écran 13.4\" OLED optionnel, processeurs Intel Core Ultra, finition aluminium.",
    specs: [
        "Processeur: Intel Core Ultra / Core i7",
        "Écran: 13.4\" OLED / FHD+",
        "RAM: 16–32 Go",
        "Stockage: 512 Go – 2 To SSD",
        "Poids: ~1.2 kg"
    ],
    images: [
        "Images/Image2.jpg",
        "Images/Image21.jpg",
        "Images/Image22.jpg",
        "Images/Image23.jpg"
    ]
    },
    {
    id: "hp-spectre-x360",
    name: "HP Spectre x360 14 (2024)",
    price: 1499.00,
    desc: "Convertible 2‑en‑1, écran OLED 2.8K 120Hz, stylet, châssis premium et autonomie solide.",
    specs: [
        "Processeur: Intel Core i7",
        "Écran: 13.5\" / 14\" OLED 2.8K",
        "RAM: 16 Go",
        "Stockage: 512 Go – 1 To SSD",
        "Fonction: Convertible tactile"
    ],
    images: [
        "Images/Image3.jpg",
        "Images/Image31.jpg",
        "Images/Image32.jpg",
        "Images/Image33.jpg"
    ]
    },
    {
    id: "lenovo-thinkpad-x1",
    name: "Lenovo ThinkPad X1 Carbon Gen 12",
    price: 1599.00,
    desc: "Ultrabook professionnel, châssis carbone, clavier signature ThinkPad, sécurité et robustesse.",
    specs: [
        "Processeur: Intel Core Ultra / i7",
        "Écran: 14\" 2.8K / FHD",
        "RAM: 16–32 Go",
        "Stockage: 512 Go – 2 To SSD",
        "Sécurité: TPM, lecteur d'empreinte"
    ],
    images: [
        "Images/Image4.jpg",
        "Images/Image41.jpg",
        "Images/Image42.jpg",
        "Images/Image43.jpg"
    ]
    },
    {
    id: "asus-rog-zephyrus",
    name: "ASUS ROG Zephyrus G14 (2024)",
    price: 1799.00,
    desc: "Portable gaming 14\", Ryzen / Intel, GPU RTX, écran 120–165Hz, refroidissement performant.",
    specs: [
        "Processeur: AMD Ryzen 9 / Intel",
        "GPU: NVIDIA RTX 40‑series",
        "Écran: 14\" 120–165Hz",
        "RAM: 16–32 Go",
        "Stockage: 1 To SSD"
    ],
    images: [
        "Images/Image5.jpg",
        "Images/Image51.jpg",
        "Images/Image52.jpg",
        "Images/Image53.jpg"
    ]
    }
];

let selected = null;

function escapeHtml(str){
    if(!str) return '';
    return String(str).replace(/[&<>"']/g, function(m){ return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'})[m]; });
}

function renderCatalog(){
    const grid = document.getElementById("catalog");
    grid.innerHTML = "";
    laptops.forEach((p, idx) => {
    const card = document.createElement("article");
    card.className = "product-card";
    card.setAttribute("data-id", p.id);
    card.innerHTML = `
        <div class="product-thumb">
        <img src="${p.images[0]}" alt="${escapeHtml(p.name)}" loading="lazy" onerror="this.style.opacity=.6; this.style.objectFit='contain';" />
        </div>
        <div class="product-body">
        <div class="product-name">${escapeHtml(p.name)}</div>
        <div class="product-meta">
            <span>$${p.price.toFixed(2)}</span>
            <span class="muted">Modèle réel</span>
        </div>
        </div>
    `;
    card.addEventListener("click", () => selectProduct(idx, true));
    grid.appendChild(card);
    });
}

function selectProduct(index, focus = false){
    const p = laptops[index];
    selected = p;
    document.getElementById("hero-title").textContent = p.name;
    document.getElementById("hero-price").textContent = `$${p.price.toFixed(2)}`;
    document.getElementById("hero-desc").textContent = p.desc;
    const mainImg = document.getElementById("hero-main-img");
    mainImg.src = p.images[0];
    mainImg.alt = p.name;
    const thumbs = document.getElementById("hero-thumbs");
    thumbs.innerHTML = "";
    p.images.forEach((src, i) => {
    const t = document.createElement("div");
    t.className = "thumb";
    t.innerHTML = `<img src="${src}" alt="${escapeHtml(p.name)} vue ${i+1}" loading="lazy" onerror="this.style.opacity=.6; this.style.objectFit='contain';" />`;
    t.addEventListener("click", () => { mainImg.src = src; });
    thumbs.appendChild(t);
    });
    const specsEl = document.getElementById("hero-specs");
    specsEl.innerHTML = "";
    p.specs.forEach(s => {
    const div = document.createElement("div");
    div.className = "spec";
    div.innerHTML = `<div class="muted">${escapeHtml(s)}</div><div style="font-weight:700;color:#0b1220"></div>`;
    specsEl.appendChild(div);
    });
    document.getElementById("hero-feedback").textContent = "Modèle sélectionné. Choisissez la quantité et ajoutez au panier.";
    document.getElementById("hero-qty").value = 1;
    if(focus){
    setTimeout(()=> {
        document.querySelector('.product-hero').scrollIntoView({behavior:'smooth', block:'start'});
    }, 80);
    }
    try { localStorage.removeItem('lastSelectedProduct'); } catch(e){}
}

function addToCart(){
    if(!selected){
    document.getElementById("hero-feedback").textContent = "Veuillez sélectionner un modèle dans le catalogue.";
    return;
    }
    const qty = Math.max(1, parseInt(document.getElementById("hero-qty").value || "1", 10));
    const warranty = parseInt(document.getElementById("hero-warranty").value, 10);
    let warrantyPrice = 0;
    if(warranty === 2) warrantyPrice = 79;
    if(warranty === 3) warrantyPrice = 129;
    const item = {
    id: selected.id,
    name: selected.name,
    price: selected.price,
    quantity: qty,
    warrantyYears: warranty,
    warrantyPrice: warrantyPrice,
    image: selected.images[0]
    };
    const cart = JSON.parse(localStorage.getItem("cart") || "[]");
    const existing = cart.find(i => i.id === item.id && i.warrantyYears === item.warrantyYears);
    if(existing){ existing.quantity += item.quantity; } else { cart.push(item); }
    localStorage.setItem("cart", JSON.stringify(cart));
    document.getElementById("hero-feedback").textContent = "Produit ajouté au panier ✔";
    flashButton("#add-btn");
}

function flashButton(selector){
    const btn = document.querySelector(selector);
    if(!btn) return;
    const original = btn.style.transform;
    btn.style.transform = "scale(0.98)";
    setTimeout(()=> btn.style.transform = original, 120);
}

// Pré-sélection depuis Panier.html si lastSelectedProduct est défini
function preselectFromStorage(){
    try {
    const id = localStorage.getItem('lastSelectedProduct');
    if(!id) return false;
    const idx = laptops.findIndex(p => p.id === id);
    if(idx >= 0){
        selectProduct(idx, true);
        return true;
    }
    } catch(e){}
    return false;
}

// Initialisation
document.getElementById("add-btn").addEventListener("click", addToCart);
renderCatalog();
const pre = preselectFromStorage();
if(!pre) selectProduct(0);
