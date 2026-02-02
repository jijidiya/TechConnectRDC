
INSERT INTO produits
(fournisseur_id, title, description, price, quantity, images, status)
VALUES

(
    1,
    'Cisco Switch 24 ports',
    'Switch réseau professionnel Cisco 24 ports, idéal pour les infrastructures d’entreprise et les réseaux locaux.',
    2100.00,
    12,
    '["cisco-switch.jfif"]',
    'published'
),

(
    1,
    'HP LaserJet Pro',
    'Imprimante laser HP LaserJet Pro, conçue pour les environnements professionnels avec un volume d’impression élevé.',
    950.00,
    8,
    '["imprimante1.jpg"]',
    'published'
),
(
    1,
    'HP LaserJet Enterprise M507',
    'Imprimante laser professionnelle HP LaserJet Enterprise M507, conçue pour les entreprises avec un volume d’impression élevé et des exigences de sécurité avancées.',
    1350.00,
    6,
    '["imprimante2.jpg"]',
    'published'
);

UPDATE fournisseurs
SET
    description = 'Fournisseur spécialisé en solutions réseaux et infrastructures informatiques professionnelles.',
    logo = 'f2.jpg'
WHERE id = 1;


UPDATE fournisseurs
SET
    description = 'Fournisseur agréé HP pour imprimantes et équipements informatiques professionnels.',
    logo = 'f3.jpg'
WHERE id = 2;