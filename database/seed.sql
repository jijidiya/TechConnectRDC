/* =====================================================
   SEED TECHCONNECT RDC
   ===================================================== */


SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE favoris;
TRUNCATE TABLE avis;
TRUNCATE TABLE messages;
TRUNCATE TABLE commande_items;
TRUNCATE TABLE commandes;
TRUNCATE TABLE produits;
TRUNCATE TABLE categories;
TRUNCATE TABLE fournisseurs;
TRUNCATE TABLE users;
SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================
-- USERS
-- =====================================================

INSERT INTO users (role, nom, email, password_hash, telephone)
VALUES
('client', 'Client Test', 'client@test.com', '$2y$10$testhashclient', '0990000000'),
('fournisseur', 'Fournisseur Test', 'fournisseur@test.com', '$2y$10$testhashfour', '0810000000'),
('admin', 'Admin TechConnect', 'admin@test.com', '$2y$10$testhashadmin', '0800000000');

-- =====================================================
-- FOURNISSEURS
-- =====================================================

INSERT INTO fournisseurs (user_id, description, statut)
VALUES
(2, 'Fournisseur B2B spécialisé en matériel informatique', 'actif');

-- =====================================================
-- CATEGORIES
-- =====================================================

INSERT INTO categories (name, slug)
VALUES
('Informatique', 'informatique'),
('Téléphonie', 'telephonie');

-- =====================================================
-- PRODUITS
-- =====================================================

INSERT INTO produits (
    fournisseur_id,
    category_id,
    title,
    slug,
    description,
    price,
    quantity,
    status
) VALUES
(1, 1, 'Ordinateur Portable Pro', 'ordinateur-portable-pro',
 'Laptop professionnel pour entreprises', 850.00, 10, 'published'),

(1, 2, 'Smartphone Business', 'smartphone-business',
 'Téléphone robuste pour usage pro', 420.00, 25, 'published');

-- =====================================================
-- COMMANDES
-- =====================================================

INSERT INTO commandes (user_id, total, status)
VALUES
(1, 1270.00, 'paid');

-- =====================================================
-- COMMANDE ITEMS
-- =====================================================

INSERT INTO commande_items (commande_id, produit_id, quantity, price)
VALUES
(1, 1, 1, 850.00),
(1, 2, 1, 420.00);

-- =====================================================
-- MESSAGES
-- IMPORTANT pour tes tests MessageFlow
-- =====================================================

INSERT INTO messages (sender_id, receiver_id, sujet, body)
VALUES
(1, 2, 'Disponibilité produit',
 'Bonjour, le produit est-il disponible en grande quantité ?');

-- =====================================================
-- AVIS
-- =====================================================

INSERT INTO avis (user_id, produit_id, note, commentaires)
VALUES
(1, 1, 5, 'Excellent produit, très satisfait');

-- =====================================================
-- FAVORIS
-- =====================================================

INSERT INTO favoris (user_id, produit_id)
VALUES
(1, 2);
