-- ============================================
-- Insertion des données de status
-- ============================================

-- Status pour les besoins
INSERT INTO statusBesoin (nom) VALUES
('En attente'),
('Partiellement satisfait'),
('Satisfait');

-- Status pour les dons
INSERT INTO statusDon (nom) VALUES
('Disponible'),
('Alloué'),
('Distribué');

-- Status pour les distributions
INSERT INTO statusDistribution (nom) VALUES
('Planifié'),
('Effectué');

-- ============================================
-- Insertion des catégories
-- ============================================
INSERT INTO categorieBesoin (nom) VALUES
('Nature'),
('Matériaux'),
('Argent');

-- ============================================
-- Insertion des types de besoin (optionnel)
-- ============================================
INSERT INTO typeBesoin (idCategorie, nom) VALUES
(1, 'Alimentaire'),
(1, 'Boisson'),
(2, 'Construction'),
(2, 'Équipement');

-- ============================================
-- Insertion des produits avec prix unitaires
-- ============================================
INSERT INTO produit (idCategorie, nom, prixUnitaire) VALUES
(1, 'Riz', 2500),
(1, 'Huile', 5000),
(1, 'Farine', 2000),
(1, 'Sucre', 3000),
(1, 'Lait', 4000),
(1, 'Eau', 1000),
(2, 'Tôle', 15000),
(2, 'Clou', 500),
(2, 'Ciment', 25000),
(2, 'Bâche', 20000),
(2, 'Moustiquaire', 8000);

-- ============================================
-- Insertion des régions
-- ============================================
INSERT INTO region (nom) VALUES 
('Diana'),
('Sava'),
('Itasy'),
('Analamanga'),
('Vakinankaratra'),
('Haute Matsiatra');

-- ============================================
-- Insertion des villes
-- ============================================
INSERT INTO ville (idRegion, nom) VALUES
(1, 'Antsiranana'),
(1, 'Ambilobe'),
(1, 'Nosy Be'),
(2, 'Sambava'),
(2, 'Antalaha'),
(2, 'Andapa'),
(3, 'Miarinarivo'),
(3, 'Soavinandriana'),
(4, 'Antananarivo'),
(4, 'Ambohidratrimo'),
(4, 'Antanifotsy'),
(5, 'Antsirabe'),
(5, 'Ambatolampy'),
(6, 'Fianarantsoa'),
(6, 'Ambohimahasoa');
