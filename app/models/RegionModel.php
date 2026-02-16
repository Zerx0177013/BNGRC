<?php
namespace app\models;
use PDO;

class RegionModel
{
    private $db;
    
    public function __construct($db)
    {
        $this->db = $db;
    }
    
    public function getAllRegions()
    {
        $sql = 'SELECT * FROM bngrc_region_ETU003918 ORDER BY nom_region';
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getRegionById($id)
    {
        $sql = 'SELECT * FROM bngrc_region_ETU003918 WHERE id_region = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function createRegion($nom)
    {
        $sql = 'INSERT INTO bngrc_region_ETU003918 (nom_region) VALUES (:nom)';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':nom' => $nom]);
        return (int) $this->db->lastInsertId();
    }
    
    public function updateRegion($id, $nom)
    {
        $sql = 'UPDATE bngrc_region_ETU003918 SET nom_region = :nom WHERE id_region = :id';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':nom' => $nom
        ]);
    }
    
    public function deleteRegion($id)
    {
        $sql = 'DELETE FROM bngrc_region_ETU003918 WHERE id_region = :id';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    public function getNumberOfRegions()
    {
        $sql = 'SELECT COUNT(*) FROM bngrc_region_ETU003918';
        $stmt = $this->db->query($sql);
        return (int) $stmt->fetchColumn();
    }
}
