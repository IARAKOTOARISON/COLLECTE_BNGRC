<?php
namespace App\Models;

class Ville {
    private \PDO $db;

    public function __construct(\PDO $db) {
        $this->db = $db;
    }

    /** Récupérer toutes les villes avec leur région */
    public function getAll(): array {
        $query = "
            SELECT v.id, v.nom AS ville, r.nom AS region
            FROM ville v
            JOIN region r ON v.idRegion = r.id
            ORDER BY v.nom ASC
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
