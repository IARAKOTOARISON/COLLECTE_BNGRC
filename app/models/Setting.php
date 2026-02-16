<?php
namespace App\models;

class Setting {
    private \PDO $db;

    public function __construct(\PDO $db) {
        $this->db = $db;
    }

    /** Retourne la valeur d'une clé ou $default si absente */
    public function get(string $key, $default = null) {
        $stmt = $this->db->prepare('SELECT `value` FROM setting WHERE `key` = :k LIMIT 1');
        $stmt->execute([':k' => $key]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? $row['value'] : $default;
    }

    /** Définit ou met à jour une clé */
    public function set(string $key, $value): bool {
        $stmt = $this->db->prepare('INSERT INTO setting (`key`, `value`) VALUES (:k, :v) ON DUPLICATE KEY UPDATE `value` = :v2');
        return $stmt->execute([':k' => $key, ':v' => (string)$value, ':v2' => (string)$value]);
    }
}

?>
