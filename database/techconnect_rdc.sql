/*=============================================
  TECHCONNECT RDC - Base de données
  Auteur : @jijidiya (Backend Lead)
  Description : Structure complete du site
  ==============================================
*/



/*______________________________________________
  TABLE 1 : USERS
  Tous les comptes(clients, fournisseurs, admin)
  _______________________________________________*/

CREATE TABLE users (
    id int AUTO_INCREMENT PRIMARY KEY,
    -- role de l'utilisateur
    role ENUM('client', 'fournisseur', 'admin') NOT NULL DEFAULT 'client',

    -- Informations personnelles / professionnelles
    nom VARCHAR(150) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(50) NOT NULL,
    telephone VARCHAR(50),

    adresse TEXT NULL,
    entreprise VARCHAR(255) NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


/*______________________________________________
  TABLE 2 : FOURNISSEURS
  information supplementaire sur les vendeurs B2B
  _______________________________________________*/

CREATE TABLE fournisseurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    --chaque fournisseur est un utilisateur 
    user_id INT NOT NULL,
    description TEXT NULL,
    logo VARCHAR(255) NULL,

    statut ENUM('actif', 'inactif', 'en_attente') DEFAULT 'en_attente',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


/*______________________________________________
  TABLE 3 : CATEGORIES
  Regroupe les produits par categories
  _______________________________________________*/
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,

    name VARCHAR(150) NOT NULL,
    slug VARCHAR(180) UNIQUE,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


/*______________________________________________
  TABLE 4 : PRODUITS
  Les produits vendus sur la  plateforme
  _______________________________________________*/

CREATE TABLE produits (
    id INT AUTO_INCREMENT PRIMARY KEY,

    --lien fournisseur 
    fournisseur_id INT NOT NULL,

    --lien categorie
    category_id INT NULL,

    --infos produit
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE,
    description TEXT NULL,
    price DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    quantity INT DEFAULT 0,

    -- stocker plusieurs images en JSON
    images TEXT NULL,

    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    FOREIGN KEY (fournisseur_id) REFERENCES fournisseur(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

CREATE TABLE commandes (
    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id  INT NOT NULL,

    total DECIMAL(12,2) NOT NULL,
    status ENUM('pending', 'paid', 'shipped', 'canceled') DEFAULT 'pending',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id)    
);

CREATE TABLE commande_items (
    id INT AUTO_INCREMENT PRIMARY KEY,

    commande_id INT NOT NULL,
    produit_id INT NOT NULL,

    quantity INT NOT NULL,
    price DECIMAL(12,2) NOT NULL,

    FOREIGN KEY (commande_id) REFERENCES commandes(id) ON DELETE CASCADE,
    FOREIGN KEY (produit_id) REFERENCES produits(id) ON DELETE CASCADE
);

CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,

    sender_id INT NOT NULL,  --celui qui envoie
    receiver_id INT NOT NULL, --celui qui recoit

    sujet VARCHAR(255),
    body TEXT NOT NULL,

    is_read TINYINT(1) DEFAULT 0,  -- 0 non lu, 1 lu

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (sender_id) REFERENCES users(id),
    FOREIGN KEY (receiver_id) REFERENCES users(id)


    --TO DO : table avis
    --TO DO : table favoris/wishlist 

    --TO DO : faire le reste  de la documentation

);

