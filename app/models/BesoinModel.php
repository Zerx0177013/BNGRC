<?php

namespace app\models;

use PDO;

class BesoinModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllBesoins()
    {
        $sql = '
            SELECT * from v_besoins_complets_ETU003918
            ORDER BY date_saisie ASC
        ';

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Créer un besoin
     */
    public function createBesoin($idVille, $idArticle, $quantite, $dateSaisie)
    {
        if ($dateSaisie) {
            $sql = '
                INSERT INTO bngrc_besoin_ETU003918 (id_ville, id_article, quantite, date_saisie) 
                VALUES (:id_ville, :id_article, :quantite, :date_saisie)
            ';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':id_ville' => $idVille,
                ':id_article' => $idArticle,
                ':quantite' => $quantite,
                ':date_saisie' => $dateSaisie
            ]);
        } else {
            $sql = '
                INSERT INTO bngrc_besoin_ETU003918 (id_ville, id_article, quantite) 
                VALUES (:id_ville, :id_article, :quantite)
            ';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':id_ville' => $idVille,
                ':id_article' => $idArticle,
                ':quantite' => $quantite
            ]);
        }
        return (int) $this->db->lastInsertId();
    }

    /**
     * Modifier un besoin
     */
    public function updateBesoin($id, $idVille, $idArticle, $quantite)
    {
        $sql = '
            UPDATE bngrc_besoin_ETU003918 
            SET id_ville = :id_ville, id_article = :id_article, quantite = :quantite 
            WHERE id_besoin = :id
        ';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':id_ville' => $idVille,
            ':id_article' => $idArticle,
            ':quantite' => $quantite
        ]);
    }

    /**
     * Supprimer un besoin
     */
    public function deleteBesoin($id)
    {
        $sql = 'DELETE FROM bngrc_besoin_ETU003918 WHERE id_besoin = :id';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function getBesoinsByVille($idVille)
    {
        $sql = '
            SELECT * from v_besoins_complets_ETU003918
            WHERE id_ville = :id_ville
            ORDER BY date_saisie ASC
        ';

        $stmt = $this->db->prepare($sql);

        $stmt->execute([':id_ville' => $idVille]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
