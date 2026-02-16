<?php
namespace app\models;

class ArticleModel
{
    private $db;
    
    public function __construct($db)
    {
        $this->db = $db;
    }
    
    public function getAllArticles(): array
    {
        $stmt = $this->db->query('
            SELECT a.*, c.nom_categorie 
            FROM bngrc_article_ETU003918 a
            LEFT JOIN bngrc_categorie_besoin_ETU003918 c ON a.id_categorie = c.id_categorie
            ORDER BY a.nom_article
        ');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function getArticleById(int $id)
    {
        $stmt = $this->db->prepare('
            SELECT a.*, c.nom_categorie 
            FROM bngrc_article_ETU003918 a
            LEFT JOIN bngrc_categorie_besoin_ETU003918 c ON a.id_categorie = c.id_categorie
            WHERE a.id_article = :id
        ');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    
    public function getArticlesByCategorie(int $idCategorie): array
    {
        $stmt = $this->db->prepare('
            SELECT * FROM bngrc_article_ETU003918 
            WHERE id_categorie = :id_categorie 
            ORDER BY nom_article
        ');
        $stmt->execute([':id_categorie' => $idCategorie]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function createArticle(string $nom, float $prix, int $idCategorie): int
    {
        $stmt = $this->db->prepare('
            INSERT INTO bngrc_article_ETU003918 (nom_article, prix_unitaire, id_categorie) 
            VALUES (:nom, :prix, :id_categorie)
        ');
        $stmt->execute([
            ':nom' => $nom,
            ':prix' => $prix,
            ':id_categorie' => $idCategorie
        ]);
        return (int) $this->db->lastInsertId();
    }
    
    public function updateArticle(int $id, string $nom, float $prix, int $idCategorie): bool
    {
        $stmt = $this->db->prepare('
            UPDATE bngrc_article_ETU003918 
            SET nom_article = :nom, prix_unitaire = :prix, id_categorie = :id_categorie 
            WHERE id_article = :id
        ');
        return $stmt->execute([
            ':id' => $id,
            ':nom' => $nom,
            ':prix' => $prix,
            ':id_categorie' => $idCategorie
        ]);
    }
    
    public function deleteArticle(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM bngrc_article_ETU003918 WHERE id_article = :id');
        return $stmt->execute([':id' => $id]);
    }
    
    public function getNumberOfArticles(): int
    {
        $stmt = $this->db->query('SELECT COUNT(*) FROM bngrc_article_ETU003918');
        return (int) $stmt->fetchColumn();
    }
}
