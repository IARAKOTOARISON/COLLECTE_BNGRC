<?php
namespace App\Models;

class Besoin {
    private \PDO $db;

    public function __construct(\PDO $db) {
        $this->db = $db;
    }

    /** Récupérer tous les besoins avec produit et ville */
    public function getAll(): array {
        $query = "
            SELECT b.id, b.idVille, b.idProduit, b.quantite, b.idStatus, b.dateBesoin,
                   v.nom AS ville, p.nom AS produit, s.nom AS status
            FROM besoin b
            JOIN ville v ON b.idVille = v.id
            JOIN produit p ON b.idProduit = p.id
            JOIN statusBesoin s ON b.idStatus = s.id
            ORDER BY b.dateBesoin ASC
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
