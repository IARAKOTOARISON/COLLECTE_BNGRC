<?php
namespace App\models;

class Region {
    private $db;

    public function __construct(\PDO $db) {
        $this->db = $db;
    }

    public function getAllRegions() {
        $query = "SELECT * FROM region";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
?>