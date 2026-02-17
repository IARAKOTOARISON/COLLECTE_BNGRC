<?php
namespace app\models;

class Achat {
    private \PDO $db;

    public function __construct(\PDO $db) {
        $this->db = $db;
    }

    public function getAllAchats() {
        $stmt = $this->db->prepare("SELECT * FROM achat ORDER BY date_achat DESC");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAchatsByDon($idDon) {
        $stmt = $this->db->prepare("SELECT * FROM achat WHERE id_don = :idDon ORDER BY date_achat DESC");
        $stmt->execute([':idDon' => $idDon]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer achats liés à une ville via distributions
     */
    public function getAchatsByVille($idVille) {
        $query = "
            SELECT a.*
            FROM achat a
            JOIN distribution d ON d.id_achat = a.id
            WHERE d.idVille = :idVille
            ORDER BY a.date_achat DESC
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':idVille' => $idVille]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function createAchat(array $data) {
        $query = "INSERT INTO achat (id_don, date_achat, montant_total, frais_appliques) VALUES (:id_don, :date_achat, :montant_total, :frais_appliques)";
        $stmt = $this->db->prepare($query);
        $ok = $stmt->execute([
            ':id_don' => $data['id_don'] ?? null,
            ':date_achat' => $data['date_achat'] ?? date('Y-m-d H:i:s'),
            ':montant_total' => $data['montant_total'] ?? 0,
            ':frais_appliques' => $data['frais_appliques'] ?? 0,
        ]);
        return $ok ? (int)$this->db->lastInsertId() : false;
    }
}
