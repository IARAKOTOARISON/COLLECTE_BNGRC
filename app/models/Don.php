<?php
namespace App\models;

class Don {
    private $db;

    public function __construct(\PDO $db) {
        $this->db = $db;
    }

    public function getAllDon() {
        $query = "SELECT * FROM don";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer un don par son ID
     * @param int $id
     * @return array|null
     */
    public function getById($id) {
        $query = "SELECT * FROM don WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Créer un nouveau don
     * @param array $data
     * @return bool
     */
    public function create($data) {
        $query = "INSERT INTO don (donateur, type, description, montant, date, ville, statut)
                  VALUES (:donateur, :type, :description, :montant, :date, :ville, :statut)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }

    /**
     * Mettre à jour un don
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

        $query = "UPDATE don SET " . implode(', ', $updates) . " WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute($params);
    }

    /**
     * Supprimer un don
     * @param int $id
     * @return bool
     */
    public function delete($id) {
        $query = "DELETE FROM don WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':id' => $id]);
    }
}
?>