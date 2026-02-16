<?php
namespace App\models;

class Distribution {
    private $db;

    public function __construct(\PDO $db) {
        $this->db = $db;
    }

    /**
     * Récupérer toutes les distributions
     * @return array
     */
    public function getAll() {
        $query = "
            SELECT d.id, d.idBesoin, d.idDon, d.idVille, d.quantite, d.montant, d.dateDistribution, d.idStatusDistribution,
                   v.nom AS ville,
                   b.idProduit AS produitId, p.nom AS produit,
                   s.nom AS statutBesoin,
                   ds.nom AS statutDistribution
            FROM distribution d
            INNER JOIN ville v ON d.idVille = v.id
            INNER JOIN besoin b ON d.idBesoin = b.id
            INNER JOIN don dn ON d.idDon = dn.id
            INNER JOIN produit p ON b.idProduit = p.id
            INNER JOIN statusBesoin s ON b.idStatus = s.id
            INNER JOIN statusDistribution ds ON d.idStatusDistribution = ds.id
            ORDER BY d.dateDistribution ASC
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
