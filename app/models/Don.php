<?php
namespace App\Models;

class Don {
    private \PDO $db;

    public function __construct(\PDO $db) {
        $this->db = $db;
    }

    /** Récupérer tous les dons disponibles */
    public function getAll(): array {
        $query = "
            SELECT d.id, d.idProduit, d.quantite, d.montant, d.dateDon, d.idStatus,
                   p.nom AS produit
            FROM don d
            LEFT JOIN produit p ON d.idProduit = p.id
            WHERE d.idStatus = 1 -- Disponible
            ORDER BY d.dateDon ASC
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
