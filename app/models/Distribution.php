<?php
namespace App\Models;

class Distribution {
    private \PDO $db;

    public function __construct(\PDO $db) {
        $this->db = $db;
    }

    /** Trier un tableau par date */
    private function sortByDate(array $array, string $key, string $order = 'ASC'): array {
        usort($array, function($a, $b) use ($key, $order) {
            $timeA = strtotime($a[$key]);
            $timeB = strtotime($b[$key]);
            return ($order === 'ASC') ? ($timeA - $timeB) : ($timeB - $timeA);
        });
        return $array;
    }

    /** Filtrer besoins par ville */
    private function filterBesoinsByVille(array $besoins, int $villeId): array {
        return array_filter($besoins, fn($b) => $b['idVille'] == $villeId);
    }

    /** Déterminer type de besoin (quantité ou montant) */
    private function getTypeBesoin(array $besoin): string {
        return isset($besoin['quantite']) ? 'quantite' : 'montant';
    }

    /** Distribuer un don à un besoin */
    private function distribuerBesoinDon(array &$besoin, array &$don, string $typeBesoin): ?array {
        $besoinRestant = $besoin[$typeBesoin] ?? 0;
        $donRestant = $don[$typeBesoin] ?? 0;

        if ($besoinRestant <= 0 || $donRestant <= 0) return null;

        $attribue = min($besoinRestant, $donRestant);

        // Mettre à jour besoin et don
        $besoin[$typeBesoin] -= $attribue;
        $don[$typeBesoin] -= $attribue;

        // Statuts
        $besoin['idStatus'] = ($besoin[$typeBesoin] <= 0) ? 3 : 2; // Satisfait / Partiellement
        $don['idStatus'] = ($don[$typeBesoin] <= 0) ? 3 : 2; // Distribué / Alloué partiellement

        return [
            'idVille' => $besoin['idVille'],
            'idBesoin' => $besoin['id'],
            'idDon' => $don['id'],
            $typeBesoin => $attribue,
            'dateDistribution' => date('Y-m-d H:i:s'),
            'idStatusDistribution' => 2 // Effectué
        ];
    }

    /** Simuler la distribution pour toutes les villes */
    public function distribuer(array $allVille, array $allDon, array $allBesoin): array {
        $distributions = [];

        $allBesoin = $this->sortByDate($allBesoin, 'dateBesoin');
        $allDon = $this->sortByDate($allDon, 'dateDon');

        foreach ($allVille as &$ville) {
            $villeId = $ville['id'];
            $besoinsVille = $this->filterBesoinsByVille($allBesoin, $villeId);

            foreach ($besoinsVille as &$besoin) {
                $typeBesoin = $this->getTypeBesoin($besoin);

                foreach ($allDon as &$don) {
                    if (($don[$typeBesoin] ?? 0) <= 0 || $don['idStatus'] != 1) continue;

                    while (($besoin[$typeBesoin] ?? 0) > 0 && ($don[$typeBesoin] ?? 0) > 0) {
                        $result = $this->distribuerBesoinDon($besoin, $don, $typeBesoin);
                        if ($result) $distributions[] = $result;
                    }
                }

                // Calcul du reste pour le tableau de bord
                $besoin['reste'] = $besoin[$typeBesoin] ?? 0;
                $besoin['progression'] = $besoin['quantite'] > 0
                    ? round((($besoin['quantite'] - $besoin['reste']) / $besoin['quantite']) * 100)
                    : 0;
                // Statut visuel pour la vue
                $besoin['statutVisuel'] = match(true) {
                    $besoin['progression'] >= 100 => 'Complet',
                    $besoin['progression'] >= 50 => 'En cours',
                    default => 'Urgent',
                };
            }
        }

        return $allBesoin;
    }
}
