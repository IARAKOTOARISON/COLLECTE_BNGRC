-- ============================================
-- SCRIPT MINIMUM POUR TESTER LA BASE
-- BNGRC - Gestion des collectes et distributions
-- ============================================



-- ============================================
-- 1. VIDER LES TABLES (pour repartir à zéro)
-- ============================================
SET FOREIGN_KEY_CHECKS=0;
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
-- 3. CATÉGORIES
-- ============================================
INSERT INTO categorieBesoin (nom) VALUES
('Nature'),
('Matériaux'),
('Argent');

-- ============================================
-- 4. TYPES DE BESOIN (simplifié)
-- ============================================
INSERT INTO typeBesoin (idCategorie, nom) VALUES
(1, 'Alimentaire'),
(1, 'Boisson'),
(2, 'Construction'),
(2, 'Équipement');

-- ============================================
-- 5. PRODUITS (minimum)
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
-- 6. RÉGIONS (minimum)
-- ============================================
INSERT INTO region (nom) VALUES 
('Diana'),
('Sava'),
('Analamanga');

-- ============================================
-- 7. VILLES (minimum)
-- ============================================
INSERT INTO ville (idRegion, nom) VALUES
(1, 'Antsiranana'),
(1, 'Ambilobe'),
(2, 'Sambava'),
(2, 'Antalaha'),
(3, 'Antananarivo'),
(3, 'Ambohidratrimo');

-- ============================================
-- 8. BESOINS (10 besoins max)
-- ============================================
INSERT INTO besoin (idVille, idProduit, quantite, idStatus, dateBesoin) VALUES
-- Antsiranana (ville 1)
(1, 1, 500, 1, '2026-01-15 08:30:00'),  -- Riz
(1, 2, 200, 1, '2026-01-15 08:35:00'),  -- Huile

-- Ambilobe (ville 2)
(2, 1, 300, 1, '2026-01-10 10:00:00'),  -- Riz
(2, 7, 150, 1, '2026-01-18 11:30:00'),  -- Clou

-- Sambava (ville 3)
(3, 3, 400, 1, '2026-01-05 07:45:00'),  -- Farine
(3, 5, 1000, 1, '2026-01-22 16:30:00'), -- Eau

-- Antalaha (ville 4)
(4, 6, 200, 1, '2026-01-08 09:00:00'),  -- Tôle
(4, 8, 50, 1, '2026-01-28 13:15:00'),   -- Ciment

-- Antananarivo (ville 5)
(5, 4, 100, 1, '2026-02-01 08:00:00'),  -- Sucre
(5, 9, 30, 1, '2026-02-03 14:15:00');   -- Bâche

-- ============================================
-- 9. DONS (10 dons max)
-- ============================================
INSERT INTO don (idProduit, montant, quantite, dateDon, idStatus, donateur_nom) VALUES
-- Dons en nature (IDs 1-8)
(1, NULL, 1000, '2026-01-02 09:00:00', 1, 'Croix Rouge'),    -- Riz
(2, NULL, 300, '2026-01-04 10:30:00', 1, 'UNICEF'),          -- Huile
(3, NULL, 500, '2026-01-06 11:45:00', 1, 'PAM'),             -- Farine
(6, NULL, 200, '2026-01-09 14:15:00', 1, 'Habitat'),         -- Tôle
(7, NULL, 500, '2026-01-11 15:30:00', 1, 'Entreprise ABC'),  -- Clou
(4, NULL, 150, '2026-01-13 16:45:00', 1, 'Sucre de l Est'), -- Sucre
(5, NULL, 2000, '2026-01-18 09:15:00', 1, 'Eau Vive'),       -- Eau
(8, NULL, 300, '2026-01-20 10:30:00', 1, 'Ciments du Sud'),  -- Ciment

-- Dons en argent (IDs 9-10)
(NULL, 5000000, NULL, '2026-01-05 11:00:00', 1, 'Banque nationale'),
(NULL, 2500000, NULL, '2026-01-12 14:30:00', 1, 'Entreprise Telma');

-- ============================================
-- 10. DISTRIBUTIONS (5 distributions max)
-- ============================================
INSERT INTO distribution (idBesoin, idDon, idVille, quantite, montant, dateDistribution, idStatusDistribution) VALUES
-- Distribution de Riz (besoin 1, don 1)
(1, 1, 1, 200, NULL, '2026-01-18 13:30:00', 2),

-- Distribution de Tôle (besoin 7, don 4)
(7, 4, 4, 150, NULL, '2026-01-20 10:00:00', 2),

-- Distribution de Clou (besoin 4, don 5)
(4, 5, 2, 100, NULL, '2026-01-25 14:30:00', 2),

-- Distribution d'argent (besoin 10, don 10)
(10, 10, 5, NULL, 500000, '2026-02-02 11:00:00', 1),

-- Distribution de Farine (besoin 5, don 3)
(5, 3, 3, 200, NULL, '2026-02-10 08:00:00', 1);

-- ============================================
-- 11. VÉRIFICATION - COMPTER LES ENREGISTREMENTS
-- ============================================
-- SELECT 'STATUS BESOIN' AS Table, COUNT(*) AS Total FROM statusBesoin
-- UNION ALL SELECT 'STATUS DON', COUNT(*) FROM statusDon
-- UNION ALL SELECT 'STATUS DISTRIBUTION', COUNT(*) FROM statusDistribution
-- UNION ALL SELECT 'CATÉGORIES', COUNT(*) FROM categorieBesoin
-- UNION ALL SELECT 'TYPES BESOIN', COUNT(*) FROM typeBesoin
-- UNION ALL SELECT 'PRODUITS', COUNT(*) FROM produit
-- UNION ALL SELECT 'RÉGIONS', COUNT(*) FROM region
-- UNION ALL SELECT 'VILLES', COUNT(*) FROM ville
-- UNION ALL SELECT 'BESOINS', COUNT(*) FROM besoin
-- UNION ALL SELECT 'DONS', COUNT(*) FROM don
-- UNION ALL SELECT 'DISTRIBUTIONS', COUNT(*) FROM distribution;

-- ============================================
-- 12. REQUÊTES DE TEST
-- ============================================

-- Test 1: Liste des besoins avec leurs statuts
-- SELECT b.id, v.nom AS ville, p.nom AS produit, b.quantite, s.nom AS statut, b.dateBesoin
-- FROM besoin b
-- JOIN ville v ON b.idVille = v.id
-- JOIN produit p ON b.idProduit = p.id
-- JOIN statusBesoin s ON b.idStatus = s.id
-- ORDER BY b.dateBesoin;

-- -- Test 2: Liste des dons disponibles
-- SELECT d.id, 
--        CASE WHEN d.idProduit IS NOT NULL THEN p.nom ELSE 'Argent' END AS type,
--        COALESCE(d.quantite, d.montant) AS valeur,
--        d.dateDon, s.nom AS statut
-- FROM don d
-- LEFT JOIN produit p ON d.idProduit = p.id
-- JOIN statusDon s ON d.idStatus = s.id
-- WHERE s.nom = 'Disponible'
-- ORDER BY d.dateDon;

-- -- Test 3: Tableau de bord simplifié (villes avec besoins et dons)
-- SELECT 
--     v.nom AS ville,
--     COUNT(DISTINCT b.id) AS nb_besoins,
--     COALESCE(SUM(b.quantite * p.prixUnitaire), 0) AS valeur_besoins,
--     COUNT(DISTINCT dist.id) AS nb_distributions
-- FROM ville v
-- LEFT JOIN besoin b ON v.id = b.idVille
-- LEFT JOIN produit p ON b.idProduit = p.id
-- LEFT JOIN distribution dist ON v.id = dist.idVille
-- GROUP BY v.id, v.nom
-- ORDER BY v.nom;

