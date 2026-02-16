<?php
namespace App\models;

class Ville{
       private $db;

    public function __construct(\PDO $db) {
        $this->db = $db;
    }

    public function getAllVilles() {
        $query = "SELECT * FROM ville";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
?>