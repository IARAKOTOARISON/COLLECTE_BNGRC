-- ============================================
-- Script de création de la base de données
-- Structure selon votre schéma
-- ============================================

CREATE DATABASE IF NOT EXISTS BNGRC;
USE BNGRC;

-- ============================================
-- Table: region
-- ============================================
CREATE TABLE region (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL UNIQUE
) ;

-- ============================================
-- Table: ville
-- ============================================
CREATE TABLE ville (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    idRegion INT NOT NULL,
    FOREIGN KEY (idRegion) REFERENCES region(id),
    UNIQUE KEY unique_ville_region (nom, idRegion)
) ;

-- ============================================
-- Table: categorieBesoin
-- ============================================
CREATE TABLE categorieBesoin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL UNIQUE
) ;

-- ============================================
-- Table: typeBesoin
-- ============================================
CREATE TABLE typeBesoin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    idCategorie INT NOT NULL,
    nom VARCHAR(50) NOT NULL,
    FOREIGN KEY (idCategorie) REFERENCES categorieBesoin(id),
    UNIQUE KEY unique_type_categorie (nom, idCategorie)
) ;

-- ============================================
-- Table: produit
-- ============================================
CREATE TABLE produit (
    id INT PRIMARY KEY AUTO_INCREMENT,
    idCategorie INT NOT NULL,
    nom VARCHAR(100) NOT NULL,
    prixUnitaire DECIMAL(15,2) NOT NULL,
    FOREIGN KEY (idCategorie) REFERENCES categorieBesoin(id),
    UNIQUE KEY unique_produit (nom, idCategorie)
) ;

-- ============================================
-- Table: statusBesoin
-- ============================================
CREATE TABLE statusBesoin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL UNIQUE
) ;

-- ============================================
-- Table: besoin
-- ============================================
CREATE TABLE besoin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    idVille INT NOT NULL,
    idProduit INT NOT NULL,
    quantite DECIMAL(15,2) NOT NULL,
    idStatus INT NOT NULL,
    dateBesoin DATETIME NOT NULL,
    FOREIGN KEY (idVille) REFERENCES ville(id),
    FOREIGN KEY (idProduit) REFERENCES produit(id),
    FOREIGN KEY (idStatus) REFERENCES statusBesoin(id),
    INDEX idx_besoin_date (dateBesoin)
) ;

-- ============================================
-- Table: statusDon
-- ============================================
CREATE TABLE statusDon (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL UNIQUE
) ;

-- ============================================
-- Table: don
-- ============================================
CREATE TABLE don (
    id INT PRIMARY KEY AUTO_INCREMENT,
    idProduit INT NULL,
    montant DECIMAL(15,2) NULL,
    quantite DECIMAL(15,2) NULL,
    dateDon DATETIME NOT NULL,
    idStatus INT NOT NULL,
    donateur_nom VARCHAR(200),
    FOREIGN KEY (idProduit) REFERENCES produit(id),
    FOREIGN KEY (idStatus) REFERENCES statusDon(id),
    INDEX idx_don_date (dateDon),
    -- Contrainte: soit don en nature (produit + quantite), soit en argent (montant)
    CHECK (
        (idProduit IS NOT NULL AND quantite IS NOT NULL AND montant IS NULL) OR
        (idProduit IS NULL AND montant IS NOT NULL AND quantite IS NULL)
    )
) ;

-- ============================================
-- Table: statusDistribution
-- ============================================
CREATE TABLE statusDistribution (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL UNIQUE
) ;

-- ============================================
-- Table: distribution
-- ============================================
CREATE TABLE distribution (
    id INT PRIMARY KEY AUTO_INCREMENT,
    idBesoin INT NOT NULL,
    idDon INT NOT NULL,
    idVille INT NOT NULL,
    quantite DECIMAL(15,2) NULL,
    montant DECIMAL(15,2) NULL,
    dateDistribution DATETIME NOT NULL,
    idStatusDistribution INT NOT NULL,
    FOREIGN KEY (idBesoin) REFERENCES besoin(id),
    FOREIGN KEY (idDon) REFERENCES don(id),
    FOREIGN KEY (idVille) REFERENCES ville(id),
    FOREIGN KEY (idStatusDistribution) REFERENCES statusDistribution(id),
    INDEX idx_distribution_date (dateDistribution),
    -- Contrainte: soit distribution en nature (quantite), soit en argent (montant)
    CHECK (
        (quantite IS NOT NULL AND montant IS NULL) OR
        (montant IS NOT NULL AND quantite IS NULL)
    )
) ;

















-----------------------------------------------------------------------------------------------------------------------------------------------------



-- ============================================
-- Vue pour le tableau de bord
-- ============================================
CREATE VIEW vue_tableau_bord AS
SELECT 
    v.id AS ville_id,
    v.nom AS ville_nom,
    r.nom AS region_nom,
    
    -- Statistiques des besoins
    COUNT(DISTINCT b.id) AS total_besoins,
    SUM(CASE WHEN sb.nom != 'Satisfait' THEN 1 ELSE 0 END) AS besoins_non_satisfaits,
    COALESCE(SUM(b.quantite * p.prixUnitaire), 0) AS valeur_totale_besoins,
    
    -- Valeur totale des dons reçus
    COALESCE((
        SELECT SUM(
            COALESCE(d.quantite * p2.prixUnitaire, d.montant, 0)
        )
        FROM distribution dist
        JOIN don d ON dist.idDon = d.id
        LEFT JOIN produit p2 ON d.idProduit = p2.id
        WHERE dist.idVille = v.id
    ), 0) AS valeur_totale_dons_recus,
    
    -- Dernière distribution
    MAX(dist.dateDistribution) AS derniere_distribution
    
FROM ville v
JOIN region r ON v.idRegion = r.id
LEFT JOIN besoin b ON v.id = b.idVille
LEFT JOIN produit p ON b.idProduit = p.id
LEFT JOIN statusBesoin sb ON b.idStatus = sb.id
LEFT JOIN distribution dist ON v.id = dist.idVille
GROUP BY v.id, v.nom, r.nom;

-- ============================================
-- Exemples de requêtes utiles
-- ============================================

-- 1. Liste des besoins non satisfaits par ville
SELECT 
    v.nom AS ville,
    p.nom AS produit,
    b.quantite,
    sb.nom AS status
FROM besoin b
JOIN ville v ON b.idVille = v.id
JOIN produit p ON b.idProduit = p.id
JOIN statusBesoin sb ON b.idStatus = sb.id
WHERE sb.nom != 'Satisfait'
ORDER BY v.nom, b.dateBesoin;

-- 2. Dons disponibles
SELECT 
    d.id,
    CASE 
        WHEN d.idProduit IS NOT NULL THEN p.nom
        ELSE 'Argent'
    END AS type_don,
    COALESCE(d.quantite, d.montant) AS valeur,
    d.dateDon,
    sd.nom AS status
FROM don d
LEFT JOIN produit p ON d.idProduit = p.id
JOIN statusDon sd ON d.idStatus = sd.id
WHERE sd.nom = 'Disponible'
ORDER BY d.dateDon;

-- 3. Historique des distributions par ville
SELECT 
    v.nom AS ville,
    CASE 
        WHEN d.idProduit IS NOT NULL THEN p.nom
        ELSE 'Argent'
    END AS type_distribue,
    COALESCE(dist.quantite, dist.montant) AS valeur,
    dist.dateDistribution,
    sdist.nom AS status
FROM distribution dist
JOIN ville v ON dist.idVille = v.id
LEFT JOIN don d ON dist.idDon = d.id
LEFT JOIN produit p ON d.idProduit = p.id
JOIN statusDistribution sdist ON dist.idStatusDistribution = sdist.id
ORDER BY dist.dateDistribution DESC;

