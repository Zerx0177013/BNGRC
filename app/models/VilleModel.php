<?php
namespace app\models;
use PDO;

class VilleModel
{
    private $db;
    
    public function __construct($db)
    {
        $this->db = $db;
    }
    
    public function getAllVilles()
    {
        $sql = '
            SELECT v.*, r.nom_region 
            FROM bngrc_ville_ETU003918 v
            LEFT JOIN bngrc_region_ETU003918 r ON v.id_region = r.id_region
            ORDER BY v.nom_ville
        ';
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getVilleById($id)
    {
        $sql = '
            SELECT v.*, r.nom_region 
            FROM bngrc_ville_ETU003918 v
            LEFT JOIN bngrc_region_ETU003918 r ON v.id_region = r.id_region
            WHERE v.id_ville = :id
        ';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getVillesByRegion($idRegion)
    {
        $sql = '
            SELECT * FROM bngrc_ville_ETU003918 
            WHERE id_region = :id_region 
            ORDER BY nom_ville
        ';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_region' => $idRegion]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function createVille($nom, $idRegion)
    {
        $sql = '
            INSERT INTO bngrc_ville_ETU003918 (nom_ville, id_region) 
            VALUES (:nom, :id_region)
        ';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':nom' => $nom,
            ':id_region' => $idRegion
        ]);
        return (int) $this->db->lastInsertId();
    }
    
    public function updateVille($id, $nom, $idRegion)
    {
        $sql = '
            UPDATE bngrc_ville_ETU003918 
            SET nom_ville = :nom, id_region = :id_region 
            WHERE id_ville = :id
        ';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':nom' => $nom,
            ':id_region' => $idRegion
        ]);
    }
    
    public function deleteVille($id)
    {
        $sql = 'DELETE FROM bngrc_ville_ETU003918 WHERE id_ville = :id';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    public function getNumberOfVilles()
    {
        $sql = 'SELECT COUNT(*) FROM bngrc_ville_ETU003918';
        $stmt = $this->db->query($sql);
        return (int) $stmt->fetchColumn();
    }
}
