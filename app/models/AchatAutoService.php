<?php
namespace app\models;

/**
 * Service léger d'achat automatique pour couvrir un besoin à partir de dons / fonds
 * Implémentation minimale — logique de base, à améliorer selon règles métier.
 */
class AchatAutoService {
    private \PDO $db;
    private Besoin $besoinModel;
    private Don $donModel;
    private Achat $achatModel;
    private AchatDetails $achatDetailsModel;

    public function __construct(\PDO $db) {
        $this->db = $db;
        $this->besoinModel = new Besoin($db);
        $this->donModel = new Don($db);
        $this->achatModel = new Achat($db);
        $this->achatDetailsModel = new AchatDetails($db);
    }

    /** Retourne les besoins prioritaires (les plus anciens non satisfaits) */
    public function getBesoinsPrioritaires(int $limit = 10) {
        $query = "SELECT * FROM besoin ORDER BY dateBesoin ASC LIMIT :lim";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':lim', (int)$limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /** Vérifier s'il existe des dons utilisables pour un besoin (produit ou argent) */
    public function verifierDonsDisponibles(array $besoin) {
        // Si besoin de produit, chercher dons nature du même produit
        if (!empty($besoin['idProduit'])) {
            $query = "SELECT * FROM don WHERE idProduit = :idProduit ORDER BY dateDon ASC";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':idProduit' => $besoin['idProduit']]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
        // Sinon retourner dons argent
        return $this->donModel->getDonsArgentDisponibles();
    }

    /** Calculer un coût total approximatif pour satisfaire un besoin (quantité * prixUnitaire + frais) */
    public function calculerCoutTotal(array $besoin) {
        $query = "SELECT prixUnitaire FROM produit WHERE id = :idProduit LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':idProduit' => $besoin['idProduit']]);
        $prod = $stmt->fetch(\PDO::FETCH_ASSOC);
        $prix = $prod['prixUnitaire'] ?? 0;
        $total = ($besoin['quantite'] ?? 0) * $prix;
        return $total;
    }

    /** Vérifier si un don (argent) est suffisant pour un coût total */
    public function verifierAchatPossible(int $idDon, float $coutTotal) {
        $don = $this->donModel->getById($idDon);
        if (!$don) return false;
        $montant = $don['montant'] ?? 0;
        return ($montant >= $coutTotal);
    }

    /** Exécuter un achat: crée achat et achat_details (transactionnel hors scope) */
    public function executerAchat(array $achatData, array $details) {
        try {
            $this->db->beginTransaction();
            $idAchat = $this->achatModel->createAchat($achatData);
            if (!$idAchat) throw new \Exception('Impossible de créer achat');
            foreach ($details as $d) {
                $d['id_achat'] = $idAchat;
                $this->achatDetailsModel->create($d);
            }
            $this->db->commit();
            return $idAchat;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    /** Créer distribution(s) depuis un achat: lie la distribution à l'achat */
    public function creerDistributionDepuisAchat(int $idAchat, array $mappingDistributions) {
        // mappingDistributions: array of ['idBesoin','idDon','idVille','quantite']
        $stmt = $this->db->prepare("INSERT INTO distribution (idBesoin,idDon,idVille,quantite,idStatusDistribution,dateDistribution,id_achat) VALUES (:idBesoin,:idDon,:idVille,:quantite,:idStatusDistribution,:dateDistribution,:id_achat)");
        foreach ($mappingDistributions as $m) {
            $stmt->execute([
                ':idBesoin' => $m['idBesoin'],
                ':idDon' => $m['idDon'],
                ':idVille' => $m['idVille'],
                ':quantite' => $m['quantite'],
                ':idStatusDistribution' => $m['idStatusDistribution'] ?? 2,
                ':dateDistribution' => $m['dateDistribution'] ?? date('Y-m-d H:i:s'),
                ':id_achat' => $idAchat,
            ]);
        }
        return true;
    }

    /** Haute-niveau: acheter un besoin en utilisant un don financier */
    public function acheterBesoin(array $besoin, int $idDon) {
        $cout = $this->calculerCoutTotal($besoin);
        if (!$this->verifierAchatPossible($idDon, $cout)) return false;
        $achatData = [
            'id_don' => $idDon,
            'date_achat' => date('Y-m-d H:i:s'),
            'montant_total' => $cout,
            'frais_appliques' => 0
        ];
        // créer achat + details minimal (on peut ajouter details réels si disponibles)
        $idAchat = $this->executerAchat($achatData, []);
        if (!$idAchat) return false;
        return $idAchat;
    }
}
