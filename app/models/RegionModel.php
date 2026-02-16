<?php
namespace app\models;

class RegionModel
{
    private $db;
    
    public function __construct($db)
    {
        $this->db = $db;
    }
    
    public function getAllRegions(): array
    {
        $stmt = $this->db->query('SELECT * FROM bngrc_region_ETU003918 ORDER BY nom_region');
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function getRegionById(int $id)
    {
        $stmt = $this->db->prepare('SELECT * FROM bngrc_region_ETU003918 WHERE id_region = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    
    public function createRegion(string $nom): int
    {
        $stmt = $this->db->prepare('INSERT INTO bngrc_region_ETU003918 (nom_region) VALUES (:nom)');
        $stmt->execute([':nom' => $nom]);
        return (int) $this->db->lastInsertId();
    }
    
    public function updateRegion(int $id, string $nom): bool
    {
        $stmt = $this->db->prepare('UPDATE bngrc_region_ETU003918 SET nom_region = :nom WHERE id_region = :id');
        return $stmt->execute([
            ':id' => $id,
            ':nom' => $nom
        ]);
    }
    
    public function deleteRegion(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM bngrc_region_ETU003918 WHERE id_region = :id');
        return $stmt->execute([':id' => $id]);
    }
    
    public function getNumberOfRegions(): int
    {
        $stmt = $this->db->query('SELECT COUNT(*) FROM bngrc_region_ETU003918');
        return (int) $stmt->fetchColumn();
    }
}
