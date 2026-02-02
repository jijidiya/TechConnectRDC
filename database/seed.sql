/* =========================================================
   RESET (OPTIONNEL MAIS RECOMMANDÉ)
   ========================================================= */

SET FOREIGN_KEY_CHECKS = 0;

DELETE FROM avis;
DELETE FROM produits;
DELETE FROM categories;
DELETE FROM fournisseurs;
DELETE FROM users;

SET FOREIGN_KEY_CHECKS = 1;


/* =========================================================
   TABLE USERS
   Rôles : client | fournisseur
   ========================================================= */

INSERT INTO users (role, nom, email, password_hash)
VALUES
('client', 'Client Test', 'client@test.com', 'password'),
('client', 'Entreprise Alpha', 'alpha@test.com', 'password'),

('fournisseur', 'Tech Supplier', 'supplier@test.com', 'password'),
('fournisseur', 'IT Solutions RDC', 'itsolutions@test.com', 'password');


/* =========================================================
   TABLE FOURNISSEURS
   Lien avec users (role = fournisseur)
   ========================================================= */

INSERT INTO fournisseurs (user_id, statut)
VALUES
(3, 'actif'),
(4, 'actif');


/* =========================================================
   TABLE CATEGORIES
   ========================================================= */

INSERT INTO categories (name)
VALUES
('Ordinateurs portables'),
('Réseaux'),
('Imprimantes'),
('Accessoires');


/* =========================================================
   TABLE PRODUITS
   Images stockées en JSON
   Chemins relatifs à /public
   ========================================================= */

INSERT INTO produits
(fournisseur_id, category_id, title, slug, description, price, quantity, images, status)
VALUES
(
  1,
  1,
  'MacBook Pro 14"',
  'macbook-pro-14',
  'Ordinateur portable Apple pour usage professionnel.',
  1800,
  10,
  '["image1.jpg"]',
  'published'
),
(
  1,
  1,
  'Dell XPS 13',
  'dell-xps-13',
  'Ultrabook performant pour entreprises.',
  1450,
  15,
  '["image2.jpg"]',
  'published'
),
(
  2,
  1,
  'HP EliteBook',
  'hp-elitebook',
  'PC portable professionnel HP.',
  1300,
  20,
  '["image3.jpg"]',
  'published'
),
(
  2,
  1,
  'Lenovo ThinkPad',
  'lenovo-thinkpad',
  'Ordinateur robuste pour environnement professionnel.',
  1250,
  12,
  '["image4.jpg"]',
  'published'
),
(
  2,
  1,
  'ASUS ROG Zephyrus',
  'asus-rog-zephyrus',
  'Laptop puissant orienté performance.',
  2100,
  8,
  '["image5.jpg"]',
  'published'
);


/* =========================================================
   VÉRIFICATIONS (OPTIONNEL)
   ========================================================= */

-- Vérifier les utilisateurs
SELECT id, role, nom, email FROM users;

-- Vérifier les fournisseurs
SELECT * FROM fournisseurs;

-- Vérifier les catégories
SELECT * FROM categories;

-- Vérifier les produits
SELECT id, title, price, images, status FROM produits;
