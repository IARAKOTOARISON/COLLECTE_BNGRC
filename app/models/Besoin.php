<?php
namespace App\models;

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