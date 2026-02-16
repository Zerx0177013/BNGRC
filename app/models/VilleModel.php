<?php
namespace app\models;

class VilleModel
{
    private $db;
    
    public function __construct($db)
    {
        $this->db = $db;
    }
    
    public function getAllVilles(): array
    {
        $stmt = $this->db->query('
            SELECT v.*, r.nom_region 
            FROM bngrc_ville_ETU003918 v
            LEFT JOIN bngrc_region_ETU003918 r ON v.id_region = r.id_region
            ORDER BY v.nom_ville
        ');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function getVilleById(int $id)
    {
        $stmt = $this->db->prepare('
            SELECT v.*, r.nom_region 
            FROM bngrc_ville_ETU003918 v
            LEFT JOIN bngrc_region_ETU003918 r ON v.id_region = r.id_region
            WHERE v.id_ville = :id
        ');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    
    public function getVillesByRegion(int $idRegion): array
    {
        $stmt = $this->db->prepare('
            SELECT * FROM bngrc_ville_ETU003918 
            WHERE id_region = :id_region 
            ORDER BY nom_ville
        ');
        $stmt->execute([':id_region' => $idRegion]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function createVille(string $nom, int $idRegion): int
    {
        $stmt = $this->db->prepare('
            INSERT INTO bngrc_ville_ETU003918 (nom_ville, id_region) 
            VALUES (:nom, :id_region)
        ');
        $stmt->execute([
            ':nom' => $nom,
            ':id_region' => $idRegion
        ]);
        return (int) $this->db->lastInsertId();
    }
    
    public function updateVille(int $id, string $nom, int $idRegion): bool
    {
        $stmt = $this->db->prepare('
            UPDATE bngrc_ville_ETU003918 
            SET nom_ville = :nom, id_region = :id_region 
            WHERE id_ville = :id
        ');
        return $stmt->execute([
            ':id' => $id,
            ':nom' => $nom,
            ':id_region' => $idRegion
        ]);
    }
    
    public function deleteVille(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM bngrc_ville_ETU003918 WHERE id_ville = :id');
        return $stmt->execute([':id' => $id]);
    }
    
    public function getNumberOfVilles(): int
    {
        $stmt = $this->db->query('SELECT COUNT(*) FROM bngrc_ville_ETU003918');
        return (int) $stmt->fetchColumn();
    }
}
