<?php
namespace app\models;
use PDO;
use PDOException;
class DonModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Tous les dons avec article + catégorie
     */
    public function getAllDons()
    {
        $sql = 'SELECT * from v_info_dons_ETU003918 ORDER BY date_don ASC';
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Un don par ID
     */
    public function getDonById($id)
    {
        $sql = 'SELECT * from v_info_dons_ETU003918 WHERE id_don = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Dons par article
     */
    public function getDonsByArticle($idArticle)
    {
        $sql = '
            SELECT d.*
            FROM bngrc_don_ETU003918 d
            WHERE d.id_article = :id_article
            ORDER BY d.date_don ASC
        ';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_article' => $idArticle]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Créer un don
     */
    public function createDon($idArticle, $quantite, $dateDon = null)
    {
        if ($dateDon) {
            $sql = '
                INSERT INTO bngrc_don_ETU003918 (id_article, quantite, date_don) 
                VALUES (:id_article, :quantite, :date_don)
            ';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':id_article' => $idArticle,
                ':quantite' => $quantite,
                ':date_don' => $dateDon
            ]);
        } else {
            $sql = '
                INSERT INTO bngrc_don_ETU003918 (id_article, quantite) 
                VALUES (:id_article, :quantite)
            ';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':id_article' => $idArticle,
                ':quantite' => $quantite
            ]);
        }
        return (int) $this->db->lastInsertId();
    }

 
    public function updateDon($id, $idArticle, $quantite)
    {
        $sql = '
            UPDATE bngrc_don_ETU003918 
            SET id_article = :id_article, quantite = :quantite 
            WHERE id_don = :id
        ';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':id_article' => $idArticle,
            ':quantite' => $quantite
        ]);
    }

 
    public function deleteDon($id)
    {
        $sql = 'DELETE FROM bngrc_don_ETU003918 WHERE id_don = :id';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

}
