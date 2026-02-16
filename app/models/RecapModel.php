<?php

namespace app\models;

use PDO;

class RecapModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Récapitulatif des besoins (montants totaux, satisfaits, restants)
     */
    public function getRecapitulatif()
    {
        $sql = 'SELECT * FROM v_recap_besoins_ETU003918';

        $stmt = $this->db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $montantTotal = floatval($result['montant_total']);
        $montantSatisfait = floatval($result['montant_satisfait']);
        $montantRestant = $montantTotal - $montantSatisfait;
        $pourcentageSatisfait = $montantTotal > 0 ? ($montantSatisfait / $montantTotal) * 100 : 0;

        return [
            'montant_total' => $montantTotal,
            'montant_satisfait' => $montantSatisfait,
            'montant_restant' => $montantRestant,
            'pourcentage_satisfait' => round($pourcentageSatisfait, 2)
        ];
    }
}
