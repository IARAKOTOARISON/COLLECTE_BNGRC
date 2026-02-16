<?php
namespace App\models;

class TypeBesoin {
    private $db;

    public function __construct(\PDO $db) {
        $this->db = $db;
    }

    public function getAllTypesBesoin() {
        $query = "SELECT * FROM type_besoin";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
?>