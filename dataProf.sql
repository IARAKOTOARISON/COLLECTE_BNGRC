-- ============================================
-- 1. VIDER LES TABLES (pour repartir à zéro)
-- ============================================
SET FOREIGN_KEY_CHECKS=0;
TRUNCATE TABLE achat_details;
TRUNCATE TABLE achat;
TRUNCATE TABLE distribution;
TRUNCATE TABLE don;
TRUNCATE TABLE besoin;
TRUNCATE TABLE ville;
TRUNCATE TABLE region;
TRUNCATE TABLE produit;
TRUNCATE TABLE typeBesoin;
TRUNCATE TABLE categorieBesoin;
TRUNCATE TABLE statusDistribution;
TRUNCATE TABLE statusDon;
TRUNCATE TABLE statusBesoin;
TRUNCATE TABLE parametres;
SET FOREIGN_KEY_CHECKS=1;


-- ============================================
-- 2. STATUS (tables de référence)
-- ============================================

INSERT INTO statusBesoin (nom) VALUES
('En attente'),
('Partiellement satisfait'),
('Satisfait');

INSERT INTO statusDon (nom) VALUES
('Disponible'),
('Alloué'),
('Distribué');

INSERT INTO statusDistribution (nom) VALUES
('Planifié'),
('Effectué');

-- ============================================
-- 3. PARAMÈTRES SYSTÈME (frais d'achat)
-- ============================================
INSERT INTO parametres (cle, valeur, description) VALUES
('frais_achat_pourcentage', '5', 'Pourcentage de frais appliqué sur les achats (ex: 5 pour 5%)'),
('devise', 'Ariary', 'Devise utilisée pour les montants'),
('nom_organisation', 'BNGRC', 'Bureau National de Gestion des Risques et Catastrophes'),
('email_contact', 'contact@bngrc.mg', 'Email de contact principal');

-- ============================================
-- 4. CATÉGORIES
-- ============================================
INSERT INTO categorieBesoin (nom) VALUES
('Nature'),
('Matériaux'),
('Argent');


-- ============================================
-- 6. PRODUITS (minimum)
-- ============================================
INSERT INTO produit (idCategorie, nom, prixUnitaire) VALUES
(1, 'Riz', 3000),
(1, 'Eau', 1000),
(2, 'Tôle', 25000),
(1, 'Huile', 6000),
(1, 'Farine', 2000),
(1, 'Sucre', 3000),
(2, 'Clou', 8000),
(2, 'Ciment', 25000),
(3, 'Argent', 25000),
(2, 'Bâche', 15000);
(2, 'Bois', 15000);
