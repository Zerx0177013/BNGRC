<?php
namespace app\models;
use PDO;

class ArticleModel
{
    private $db;
    
    public function __construct($db)
    {
        $this->db = $db;
    }
    
    public function getAllArticles()
    {
        $sql = '
            SELECT a.*, c.nom_categorie 
            FROM bngrc_article_ETU003918 a
            LEFT JOIN bngrc_categorie_besoin_ETU003918 c ON a.id_categorie = c.id_categorie
            ORDER BY a.nom_article
        ';
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getArticleById($id)
    {
        $sql = '
            SELECT a.*, c.nom_categorie 
            FROM bngrc_article_ETU003918 a
            LEFT JOIN bngrc_categorie_besoin_ETU003918 c ON a.id_categorie = c.id_categorie
            WHERE a.id_article = :id
        ';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getArticlesByCategorie($idCategorie)
    {
        $sql = '
            SELECT * FROM bngrc_article_ETU003918 
            WHERE id_categorie = :id_categorie 
            ORDER BY nom_article
        ';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_categorie' => $idCategorie]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function createArticle($nom, $prix, $idCategorie)
    {
        $sql = '
            INSERT INTO bngrc_article_ETU003918 (nom_article, prix_unitaire, id_categorie) 
            VALUES (:nom, :prix, :id_categorie)
        ';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':nom' => $nom,
            ':prix' => $prix,
            ':id_categorie' => $idCategorie
        ]);
        return (int) $this->db->lastInsertId();
    }
    
    public function updateArticle($id, $nom, $prix, $idCategorie)
    {
        $sql = '
            UPDATE bngrc_article_ETU003918 
            SET nom_article = :nom, prix_unitaire = :prix, id_categorie = :id_categorie 
            WHERE id_article = :id
        ';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':nom' => $nom,
            ':prix' => $prix,
            ':id_categorie' => $idCategorie
        ]);
    }
    
    public function deleteArticle($id)
    {
        $sql = 'DELETE FROM bngrc_article_ETU003918 WHERE id_article = :id';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    public function getNumberOfArticles()
    {
        $sql = 'SELECT COUNT(*) FROM bngrc_article_ETU003918';
        $stmt = $this->db->query($sql);
        return (int) $stmt->fetchColumn();
    }
}
