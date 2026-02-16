<?php
namespace App\models;

class Don {
    private $db;

    public function __construct(\PDO $db) {
        $this->db = $db;
    }

    public function getAllDons() {
        $query = "SELECT * FROM don";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
?>