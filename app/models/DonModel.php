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

    /**
     * Dons groupés par jour
     */
    public function getDonsParJour()
    {
        $sql = '
            SELECT DATE(date_don) as jour, SUM(quantite) as total
            FROM bngrc_don_ETU003918
            GROUP BY DATE(date_don)
            ORDER BY jour ASC
        ';
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Dons groupés par article avec catégorie
     */
    public function getDonsParArticle()
    {
        $sql = '
            SELECT a.nom_article, SUM(d.quantite) as total_quantite, c.nom_categorie
            FROM bngrc_don_ETU003918 d
            JOIN bngrc_article_ETU003918 a ON d.id_article = a.id_article
            JOIN bngrc_categorie_besoin_ETU003918 c ON a.id_categorie = c.id_categorie
            GROUP BY a.nom_article, c.nom_categorie
            ORDER BY total_quantite DESC
        ';
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Stats totales des dons (nombre + quantité totale)
     */
    public function getStatsTotaux()
    {
        $sql = 'SELECT COUNT(*) as nb, COALESCE(SUM(quantite), 0) as total FROM bngrc_don_ETU003918';
        $stmt = $this->db->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Dispatches groupés par catégorie
     */
    public function getDispatchParCategorie()
    {
        $sql = '
            SELECT c.nom_categorie, SUM(dp.quantite_attribuee) as total
            FROM bngrc_dispatch_ETU003918 dp
            JOIN bngrc_don_ETU003918 d ON dp.id_don = d.id_don
            JOIN bngrc_article_ETU003918 a ON d.id_article = a.id_article
            JOIN bngrc_categorie_besoin_ETU003918 c ON a.id_categorie = c.id_categorie
            GROUP BY c.nom_categorie
            ORDER BY c.nom_categorie ASC
        ';
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Stats totales des dispatches (nombre + quantité totale)
     */
    public function getStatsDispatches()
    {
        $sql = 'SELECT COUNT(*) as nb, COALESCE(SUM(quantite_attribuee), 0) as total FROM bngrc_dispatch_ETU003918';
        $stmt = $this->db->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get dispatches grouped by ville and article for dashboard table
     */
    public function getDispatchesParVilleArticle()
    {
        // Get all regions with their villes
        $sql = '
            SELECT r.id_region, r.nom_region, v.id_ville, v.nom_ville
            FROM bngrc_region_ETU003918 r
            LEFT JOIN bngrc_ville_ETU003918 v ON r.id_region = v.id_region
            ORDER BY r.nom_region, v.nom_ville
        ';
        $stmt = $this->db->query($sql);
        $villes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get all articles with categories
        $sql = '
            SELECT a.id_article, a.nom_article, c.nom_categorie
            FROM bngrc_article_ETU003918 a
            LEFT JOIN bngrc_categorie_besoin_ETU003918 c ON a.id_categorie = c.id_categorie
            ORDER BY c.nom_categorie, a.nom_article
        ';
        $stmt = $this->db->query($sql);
        $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get dispatches matrix
        $sql = '
            SELECT dp.id_ville, d.id_article, SUM(dp.quantite_attribuee) as quantite
            FROM bngrc_dispatch_ETU003918 dp
            JOIN bngrc_don_ETU003918 d ON dp.id_don = d.id_don
            GROUP BY dp.id_ville, d.id_article
        ';
        $stmt = $this->db->query($sql);
        $dispatches = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Transform dispatches into matrix [ville_id][article_id] = quantite
        $dispatchMatrix = [];
        foreach ($dispatches as $d) {
            $dispatchMatrix[$d['id_ville']][$d['id_article']] = $d['quantite'];
        }

        return [
            'villes' => $villes,
            'articles' => $articles,
            'matrix' => $dispatchMatrix
        ];
    }

    /**
     * Get dons grouped by category for chart comparison
     */
    public function getDonsParCategorie()
    {
        $sql = '
            SELECT c.nom_categorie, SUM(d.quantite) as total
            FROM bngrc_don_ETU003918 d
            JOIN bngrc_article_ETU003918 a ON d.id_article = a.id_article
            JOIN bngrc_categorie_besoin_ETU003918 c ON a.id_categorie = c.id_categorie
            GROUP BY c.nom_categorie
            ORDER BY c.nom_categorie ASC
        ';
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
