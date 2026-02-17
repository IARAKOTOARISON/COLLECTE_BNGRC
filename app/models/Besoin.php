<?php
namespace app\models;

class Besoin {
    private $db;

    public function __construct(\PDO $db) {
        $this->db = $db;
    }

    public function getAllBesoin() {
        $query = "SELECT * FROM besoin";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer tous les besoins avec détails (ville, produit, status)
     * @return array
     */
    public function getAllBesoinsAvecDetails() {
        $query = "
            SELECT 
                b.id,
                b.quantite,
                b.dateBesoin,
                v.nom AS ville_nom,
                p.nom AS produit_nom,
                s.nom AS status_nom
            FROM besoin b
            INNER JOIN ville v ON b.idVille = v.id
            INNER JOIN produit p ON b.idProduit = p.id
            INNER JOIN statusBesoin s ON b.idStatus = s.id
            ORDER BY b.dateBesoin DESC, b.id DESC
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer les besoins non satisfaits avec quantités restantes calculées
     * @return array
     */
    public function getBesoinsNonSatisfaits() {
        $query = "
            SELECT 
                b.id,
                b.idVille,
                b.idProduit,
                b.quantite,
                b.dateBesoin,
                v.nom AS ville_nom,
                p.nom AS produit_nom,
                -- Calculer la quantité déjà distribuée
                COALESCE(SUM(dist.quantite), 0) AS quantite_distribuee,
                -- Calculer la quantité restante
                b.quantite - COALESCE(SUM(dist.quantite), 0) AS quantite_restante
            FROM besoin b
            INNER JOIN ville v ON b.idVille = v.id
            INNER JOIN produit p ON b.idProduit = p.id
            LEFT JOIN distribution dist ON b.id = dist.idBesoin
            GROUP BY b.id, b.idVille, b.idProduit, b.quantite, b.dateBesoin, v.nom, p.nom
            HAVING quantite_restante > 0
            ORDER BY b.dateBesoin ASC, b.id ASC
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer les besoins restants (même logique que non satisfaits)
     * @return array
     */
    public function getBesoinsRestants() {
        return $this->getBesoinsNonSatisfaits();
    }

    /**
     * Récupérer les besoins d'une ville donnée
     * @param int $idVille
     * @return array
     */
    public function getBesoinsByVille($idVille) {
        $query = "
            SELECT 
                b.id,
                b.idVille,
                b.idProduit,
                b.quantite,
                b.dateBesoin,
                v.nom AS ville_nom,
                p.nom AS produit_nom,
                COALESCE(SUM(dist.quantite), 0) AS quantite_distribuee,
                b.quantite - COALESCE(SUM(dist.quantite), 0) AS quantite_restante
            FROM besoin b
            INNER JOIN ville v ON b.idVille = v.id
            INNER JOIN produit p ON b.idProduit = p.id
            LEFT JOIN distribution dist ON b.id = dist.idBesoin
            WHERE b.idVille = :idVille
            GROUP BY b.id, b.idVille, b.idProduit, b.quantite, b.dateBesoin, v.nom, p.nom
            ORDER BY b.dateBesoin ASC, b.id ASC
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':idVille' => $idVille]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer un besoin par son ID
     * @param int $id
     * @return array|null
     */
    public function getById($id) {
        $query = "SELECT * FROM besoin WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Créer un nouveau besoin
     * @param array $data
     * @return bool
     */
    public function create($data) {
        $query = "INSERT INTO besoin (idVille, idProduit, quantite, idStatus, dateBesoin)
                  VALUES (:idVille, :idProduit, :quantite, :idStatus, :dateBesoin)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }

    /**
     * Mettre à jour un besoin
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data) {
        $updates = [];
        $params = [':id' => $id];
        
        foreach ($data as $key => $value) {
            $updates[] = "$key = :$key";
            $params[":$key"] = $value;
        }

        if (empty($updates)) {
            return false;
        }

        $query = "UPDATE besoin SET " . implode(', ', $updates) . " WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute($params);
    }

    /**
     * Supprimer un besoin
     * @param int $id
     * @return bool
     */
    public function delete($id) {
        $query = "DELETE FROM besoin WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':id' => $id]);
    }
}
?>