<?php
namespace app\models;

class CategorieBesoinModel
{
    private $db;
    
    public function __construct($db)
    {
        $this->db = $db;
    }
    
    public function getAllCategories(): array
    {
        $stmt = $this->db->query('SELECT * FROM bngrc_categorie_besoin_ETU003918 ORDER BY nom_categorie');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function getCategoryById(int $id)
    {
        $stmt = $this->db->prepare('SELECT * FROM bngrc_categorie_besoin_ETU003918 WHERE id_categorie = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    
    public function createCategory(string $nom): int
    {
        $stmt = $this->db->prepare('INSERT INTO bngrc_categorie_besoin_ETU003918 (nom_categorie) VALUES (:nom)');
        $stmt->execute([':nom' => $nom]);
        return (int) $this->db->lastInsertId();
    }
    
    public function updateCategory(int $id, string $nom): bool
    {
        $stmt = $this->db->prepare('UPDATE bngrc_categorie_besoin_ETU003918 SET nom_categorie = :nom WHERE id_categorie = :id');
        return $stmt->execute([
            ':id' => $id,
            ':nom' => $nom
        ]);
    }
    
    public function deleteCategory(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM bngrc_categorie_besoin_ETU003918 WHERE id_categorie = :id');
        return $stmt->execute([':id' => $id]);
    }
    
    public function getNumberOfCategories(): int
    {
        $stmt = $this->db->query('SELECT COUNT(*) FROM bngrc_categorie_besoin_ETU003918');
        return (int) $stmt->fetchColumn();
    }
}
