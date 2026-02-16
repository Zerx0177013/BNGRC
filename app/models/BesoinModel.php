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

    public function getStatsTotaux()
    {
        $sql = 'SELECT COALESCE(SUM(quantite), 0) as total FROM bngrc_besoin_ETU003918';
        $stmt = $this->db->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getBesoinsParVilleArticle()
    {
        $sql = '
            SELECT r.id_region, r.nom_region, v.id_ville, v.nom_ville
            FROM bngrc_region_ETU003918 r
            LEFT JOIN bngrc_ville_ETU003918 v ON r.id_region = v.id_region
            ORDER BY r.nom_region, v.nom_ville
        ';
        $stmt = $this->db->query($sql);
        $villes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $sql = '
            SELECT a.id_article, a.nom_article, c.nom_categorie
            FROM bngrc_article_ETU003918 a
            LEFT JOIN bngrc_categorie_besoin_ETU003918 c ON a.id_categorie = c.id_categorie
            ORDER BY c.nom_categorie, a.nom_article
        ';
        $stmt = $this->db->query($sql);
        $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $sql = '
            SELECT id_ville, id_article, SUM(quantite) as quantite
            FROM bngrc_besoin_ETU003918
            GROUP BY id_ville, id_article
        ';
        $stmt = $this->db->query($sql);
        $besoins = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $besoinMatrix = [];
        foreach ($besoins as $b) {
            $besoinMatrix[$b['id_ville']][$b['id_article']] = $b['quantite'];
        }

        return [
            'villes' => $villes,
            'articles' => $articles,
            'matrix' => $besoinMatrix
        ];
    }
}
