<?php
namespace App\models;

class Achat {
    private \PDO $db;

    public function __construct(\PDO $db) {
        $this->db = $db;
    }

    /** Créer un enregistrement d'achat */
    public function create(array $data) {
        $query = "INSERT INTO achat (idBesoin, idDon, idVille, idProduit, quantiteAchetee, montant_sans_frais, frais, montant_total, dateAchat)
                  VALUES (:idBesoin, :idDon, :idVille, :idProduit, :quantiteAchetee, :montant_sans_frais, :frais, :montant_total, :dateAchat)";
        $stmt = $this->db->prepare($query);
        $result = $stmt->execute([
            ':idBesoin' => $data['idBesoin'],
            ':idDon' => $data['idDon'],
            ':idVille' => $data['idVille'],
            ':idProduit' => $data['idProduit'],
            ':quantiteAchetee' => $data['quantiteAchetee'],
            ':montant_sans_frais' => $data['montant_sans_frais'],
            ':frais' => $data['frais'],
            ':montant_total' => $data['montant_total'],
            ':dateAchat' => $data['dateAchat']
        ]);

        return $result ? $this->db->lastInsertId() : false;
    }

    /** Récupérer la liste des achats avec détails, optionnellement filtrée par ville */
    public function getAllAvecDetails(?int $idVille = null) {
        $where = '';
        $params = [];
        if ($idVille !== null) {
            $where = 'WHERE a.idVille = :idVille';
            $params[':idVille'] = $idVille;
        }

        $query = "SELECT
                    a.id,
                    a.idBesoin,
                    a.idDon,
                    a.idVille,
                    a.idProduit,
                    a.quantiteAchetee,
                    a.montant_sans_frais,
                    a.frais,
                    a.montant_total,
                    a.dateAchat,
                    v.nom AS ville_nom,
                    p.nom AS produit_nom,
                    d.donateur_nom
                  FROM achat a
                  INNER JOIN ville v ON a.idVille = v.id
                  INNER JOIN produit p ON a.idProduit = p.id
                  INNER JOIN don d ON a.idDon = d.id
                  $where
                  ORDER BY a.dateAchat DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}

?>
