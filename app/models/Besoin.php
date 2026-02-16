<?php
namespace App\models;

class Besoin {
    private $db;

    public function __construct(\PDO $db) {
        $this->db = $db;
    }

    public function getAll() {
        $query = "SELECT b.id, b.idVille, b.idProduit, b.quantite, b.idStatus, b.dateBesoin,
                         v.nom AS ville, p.nom AS produit, s.nom AS status
                  FROM besoin b
                  INNER JOIN ville v ON b.idVille = v.id
                  INNER JOIN produit p ON b.idProduit = p.id
                  INNER JOIN statusBesoin s ON b.idStatus = s.id
                  ORDER BY b.dateBesoin ASC";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
?>