
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
-- 5. TYPES DE BESOIN (simplifié)
-- ============================================
INSERT INTO typeBesoin (idCategorie, nom) VALUES
(1, 'Alimentaire'),
(1, 'Boisson'),
(2, 'Construction'),
(2, 'Équipement');

-- ============================================
-- 6. PRODUITS (minimum)
-- ============================================
INSERT INTO produit (idCategorie, nom, prixUnitaire) VALUES
(1, 'Riz', 2500),
(1, 'Huile', 5000),
(1, 'Farine', 2000),
(1, 'Sucre', 3000),
(1, 'Eau', 1000),
(2, 'Tôle', 15000),
(2, 'Clou', 500),
(2, 'Ciment', 25000),
(2, 'Bâche', 20000);


-- ============================================
-- 7. RÉGIONS (minimum)
-- ============================================
INSERT INTO region (nom) VALUES 
('Diana'),
('Sava'),
('Analamanga');

-- ============================================
-- 8. VILLES (minimum)
-- ============================================
INSERT INTO ville (idRegion, nom) VALUES
(1, 'Antsiranana'),
(1, 'Ambilobe'),
(2, 'Sambava'),
(2, 'Antalaha'),
(3, 'Antananarivo'),
(3, 'Ambohidratrimo');


-- ============================================
-- 9. BESOINS (10 besoins max)
-- Tri par date de saisie (ordre chronologique pour simulation)
-- ============================================
INSERT INTO besoin (idVille, idProduit, quantite, idStatus, dateBesoin) VALUES
-- Sambava (ville 3) - le plus ancien
(3, 3, 400, 1, '2026-01-05 07:45:00'),  -- Besoin 1: Farine (pas de don nature)
-- Sambava (ville 3) - le plus ancien
(3, 3, 400, 1, '2026-01-06 07:45:00'),  -- Besoin 1: Farine (pas de don nature)
-- Antalaha (ville 4)
(4, 3, 200, 1, '2026-01-08 09:00:00'),  -- Besoin 2: Tôle (don nature dispo)
-- Antalaha (ville 4)
(4, 3, 200, 1, '2026-01-08 09:00:00');  -- Besoin 2: Tôle (don nature dispo)

INSERT INTO don (idProduit, montant, quantite, dateDon, idStatus, donateur_nom) VALUES 
-- Dons en nature (IDs 1-8)
(1, NULL, 1000, '2026-01-02 09:00:00', 1, 'Croix Rouge'),    -- Don 1: Riz 1000 unités
(2, NULL, 300, '2026-01-04 10:30:00', 1, 'UNICEF'),          -- Don 2: Huile 300 unités
(3, NULL, 1000, '2026-01-02 09:00:00', 1, 'Croix Rouge'),


-- Dons en argent (IDs 9-12) - pour achats
(NULL, 5000000, NULL, '2026-01-05 11:00:00', 1, 'Banque nationale');  -- Don 9: 5M Ar



INSERT INTO besoin (idVille, idProduit, quantite, idStatus, dateBesoin) VALUES
-- Sambava (ville 3) - le plus ancien
(2, 3, 400, 1, '2026-01-05 07:45:00') ; -- Besoin 1: Farine (pas de don nature)


INSERT INTO besoin (idVille, idProduit, quantite, idStatus, dateBesoin) VALUES
-- Sambava (ville 3) - le plus ancien
(6, 3, 200, 1, '2026-01-05 07:45:00'),  -- Besoin 1: Farine (pas de don nature)
-- Sambava (ville 3) - le plus ancien
(6, 3,100, 1, '2026-01-06 07:45:00'),  -- Besoin 1: Farine (pas de don nature)
(6, 3, 200, 1, '2026-01-06 07:45:00');  -- Besoin 1: Farine (pas de don nature)


INSERT INTO besoin (idVille, idProduit, quantite, idStatus, dateBesoin) VALUES
-- Sambava (ville 3) - le plus ancien
(6, 3, 100, 1, '2026-01-05 07:45:00'); -- Besoin 1: Farine (pas de don nature)