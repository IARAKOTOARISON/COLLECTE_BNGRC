-- ============================================
-- SCRIPT MINIMUM POUR TESTER LA BASE
-- BNGRC - Gestion des collectes et distributions
-- Mis à jour: Février 2026
-- ============================================



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

-- Antalaha (ville 4)
(4, 6, 200, 1, '2026-01-08 09:00:00'),  -- Besoin 2: Tôle (don nature dispo)

-- Ambilobe (ville 2)
(2, 1, 300, 1, '2026-01-10 10:00:00'),  -- Besoin 3: Riz (don nature dispo)

-- Antsiranana (ville 1)
(1, 1, 500, 1, '2026-01-15 08:30:00'),  -- Besoin 4: Riz (don nature dispo)
(1, 2, 200, 1, '2026-01-15 08:35:00'),  -- Besoin 5: Huile (don nature dispo)

-- Ambilobe (ville 2)
(2, 7, 150, 1, '2026-01-18 11:30:00'),  -- Besoin 6: Clou (don nature dispo)

-- Sambava (ville 3)
(3, 5, 1000, 1, '2026-01-22 16:30:00'), -- Besoin 7: Eau (don nature dispo)

-- Antalaha (ville 4)
(4, 8, 50, 1, '2026-01-28 13:15:00'),   -- Besoin 8: Ciment (don nature dispo)

-- Antananarivo (ville 5)
(5, 4, 100, 1, '2026-02-01 08:00:00'),  -- Besoin 9: Sucre (don nature dispo)
(5, 9, 30, 1, '2026-02-03 14:15:00');   -- Besoin 10: Bâche (pas de don nature - achat nécessaire)

-- ============================================
-- 10. DONS (12 dons - nature et argent)
-- ============================================
INSERT INTO don (idProduit, montant, quantite, dateDon, idStatus, donateur_nom) VALUES
-- Dons en nature (IDs 1-8)
(1, NULL, 1000, '2026-01-02 09:00:00', 1, 'Croix Rouge'),    -- Don 1: Riz 1000 unités
(2, NULL, 300, '2026-01-04 10:30:00', 1, 'UNICEF'),          -- Don 2: Huile 300 unités
(3, NULL, 200, '2026-01-06 11:45:00', 1, 'PAM'),             -- Don 3: Farine 200 (insuffisant pour besoin 1)
(6, NULL, 200, '2026-01-09 14:15:00', 1, 'Habitat'),         -- Don 4: Tôle 200 unités
(7, NULL, 500, '2026-01-11 15:30:00', 1, 'Entreprise ABC'),  -- Don 5: Clou 500 unités
(4, NULL, 150, '2026-01-13 16:45:00', 1, 'Sucre de l Est'),  -- Don 6: Sucre 150 unités
(5, NULL, 2000, '2026-01-18 09:15:00', 1, 'Eau Vive'),       -- Don 7: Eau 2000 unités
(8, NULL, 300, '2026-01-20 10:30:00', 1, 'Ciments du Sud'),  -- Don 8: Ciment 300 unités

-- Dons en argent (IDs 9-12) - pour achats
(NULL, 5000000, NULL, '2026-01-05 11:00:00', 1, 'Banque nationale'),   -- Don 9: 5M Ar
(NULL, 2500000, NULL, '2026-01-12 14:30:00', 1, 'Entreprise Telma'),   -- Don 10: 2.5M Ar
(NULL, 1000000, NULL, '2026-01-25 09:00:00', 1, 'Fondation XYZ'),      -- Don 11: 1M Ar
(NULL, 800000, NULL, '2026-02-05 16:00:00', 1, 'Association Aide');    -- Don 12: 800k Ar

-- ============================================
-- 11. DISTRIBUTIONS (exemples de dispatch effectués)
-- ============================================
INSERT INTO distribution (idBesoin, idDon, idVille, quantite, montant, dateDistribution, idStatusDistribution, id_achat) VALUES
-- Distribution de Riz (besoin 4, don 1) - Antsiranana
(4, 1, 1, 300, NULL, '2026-01-18 13:30:00', 2, NULL),

-- Distribution de Tôle (besoin 2, don 4) - Antalaha
(2, 4, 4, 150, NULL, '2026-01-20 10:00:00', 2, NULL),

-- Distribution de Clou (besoin 6, don 5) - Ambilobe
(6, 5, 2, 150, NULL, '2026-01-25 14:30:00', 2, NULL),

-- Distribution de Farine (besoin 1, don 3) - Sambava (partiel: 200/400)
(1, 3, 3, 200, NULL, '2026-02-10 08:00:00', 2, NULL);

-- ============================================
-- 12. ACHATS (exemples d'achats avec dons argent)
-- Frais appliqués: 5% selon paramètres
-- ============================================
INSERT INTO achat (id_don, date_achat, montant_total, frais_appliques) VALUES
-- Achat pour compléter besoin de Farine (besoin 1: 400 - 200 distribués = 200 restants)
-- Prix Farine: 2000 Ar, Quantité: 200, Montant HT: 400000, Frais 5%: 20000
(9, '2026-02-12 10:00:00', 420000, 20000),

-- Achat de Bâches pour Antananarivo (besoin 10: 30 bâches)
-- Prix Bâche: 20000 Ar, Quantité: 30, Montant HT: 600000, Frais 5%: 30000
(9, '2026-02-14 14:30:00', 630000, 30000);

-- ============================================
-- 13. DÉTAILS DES ACHATS
-- ============================================
INSERT INTO achat_details (id_achat, id_produit, quantite, prix_unitaire) VALUES
-- Détails achat 1: Farine
(1, 3, 200, 2000),
-- Détails achat 2: Bâche
(2, 9, 30, 20000);

-- ============================================
-- 14. DISTRIBUTIONS LIÉES AUX ACHATS
-- ============================================
INSERT INTO distribution (idBesoin, idDon, idVille, quantite, montant, dateDistribution, idStatusDistribution, id_achat) VALUES
-- Distribution Farine achetée (complément besoin 1)
(1, 9, 3, 200, NULL, '2026-02-12 11:00:00', 2, 1),

-- Distribution Bâches achetées (besoin 10)
(10, 9, 5, 30, NULL, '2026-02-14 15:00:00', 2, 2);

-- ============================================
-- 15. MISE À JOUR DES STATUTS DES BESOINS
-- ============================================
-- Besoin 1 (Farine): 200 nature + 200 achat = 400 → Satisfait
UPDATE besoin SET idStatus = 3 WHERE id = 1;

-- Besoin 2 (Tôle): 150 distribués sur 200 → Partiellement satisfait
UPDATE besoin SET idStatus = 2 WHERE id = 2;

-- Besoin 4 (Riz): 300 distribués sur 500 → Partiellement satisfait
UPDATE besoin SET idStatus = 2 WHERE id = 4;

-- Besoin 6 (Clou): 150 distribués sur 150 → Satisfait
UPDATE besoin SET idStatus = 3 WHERE id = 6;

-- Besoin 10 (Bâche): 30 achetées et distribuées → Satisfait
UPDATE besoin SET idStatus = 3 WHERE id = 10;

-- ============================================
-- 16. MISE À JOUR DES STATUTS DES DONS
-- ============================================
-- Don 1 (Riz): 300 distribués sur 1000 → reste 700 disponible
UPDATE don SET idStatus = 2 WHERE id = 1;

-- Don 3 (Farine): 200 distribués sur 200 → tout utilisé
UPDATE don SET idStatus = 3 WHERE id = 3;

-- Don 4 (Tôle): 150 distribués sur 200 → reste 50
UPDATE don SET idStatus = 2 WHERE id = 4;

-- Don 5 (Clou): 150 distribués sur 500 → reste 350
UPDATE don SET idStatus = 2 WHERE id = 5;

-- Don 9 (Argent): utilisé pour 2 achats (420000 + 630000 = 1 050 000) sur 5M
UPDATE don SET idStatus = 2 WHERE id = 9;

-- ============================================
-- 17. VÉRIFICATION - COMPTER LES ENREGISTREMENTS
-- ============================================
-- SELECT 'STATUS BESOIN' AS Tableau, COUNT(*) AS Total FROM statusBesoin
-- UNION ALL SELECT 'STATUS DON', COUNT(*) FROM statusDon
-- UNION ALL SELECT 'STATUS DISTRIBUTION', COUNT(*) FROM statusDistribution
-- UNION ALL SELECT 'CATÉGORIES', COUNT(*) FROM categorieBesoin
-- UNION ALL SELECT 'TYPES BESOIN', COUNT(*) FROM typeBesoin
-- UNION ALL SELECT 'PRODUITS', COUNT(*) FROM produit
-- UNION ALL SELECT 'RÉGIONS', COUNT(*) FROM region
-- UNION ALL SELECT 'VILLES', COUNT(*) FROM ville
-- UNION ALL SELECT 'BESOINS', COUNT(*) FROM besoin
-- UNION ALL SELECT 'DONS', COUNT(*) FROM don
-- UNION ALL SELECT 'DISTRIBUTIONS', COUNT(*) FROM distribution
-- UNION ALL SELECT 'ACHATS', COUNT(*) FROM achat
-- UNION ALL SELECT 'ACHAT_DETAILS', COUNT(*) FROM achat_details
-- UNION ALL SELECT 'PARAMETRES', COUNT(*) FROM parametres;

-- ============================================
-- 18. REQUÊTES DE TEST
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

-- -- Test 4: Liste des achats avec frais
-- SELECT 
--     a.id AS id_achat,
--     d.donateur_nom,
--     a.date_achat,
--     a.montant_total,
--     a.frais_appliques,
--     (a.montant_total - a.frais_appliques) AS montant_net
-- FROM achat a
-- LEFT JOIN don d ON a.id_don = d.id
-- ORDER BY a.date_achat;

-- -- Test 5: Récap besoins en montant (conformément au sujet)
-- SELECT 
--     'Montant Total' AS categorie,
--     SUM(b.quantite * p.prixUnitaire) AS montant_ariary
-- FROM besoin b
-- JOIN produit p ON b.idProduit = p.id
-- UNION ALL
-- SELECT 
--     'Montant Satisfait',
--     SUM(b.quantite * p.prixUnitaire)
-- FROM besoin b
-- JOIN produit p ON b.idProduit = p.id
-- WHERE b.idStatus = 3
-- UNION ALL
-- SELECT 
--     'Montant Restant',
--     SUM(b.quantite * p.prixUnitaire)
-- FROM besoin b
-- JOIN produit p ON b.idProduit = p.id
-- WHERE b.idStatus IN (1, 2);

-- ============================================
-- RÉSUMÉ DES DONNÉES DE TEST
-- ============================================
-- 10 besoins (3 satisfaits, 2 partiels, 5 en attente)
-- 12 dons (8 nature, 4 argent totalisant 9.3M Ar)
-- 6 distributions (4 nature directes, 2 via achats)
-- 2 achats (Farine + Bâches avec 5% de frais)
-- 4 paramètres système
-- ============================================
