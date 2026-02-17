<?php
namespace app\models;
use PDO;

class DispatchModel
{
    private $db;
    
    public function __construct($db)
    {
        $this->db = $db;
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    public function getAllDispatches()
    {
        $sql = '
            SELECT 
                * FROM v_don_details_ETU003918
            ORDER BY date_dispatch DESC
        ';
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getDispatchesByDon($idDon)
    {
        $sql = '
            SELECT * FROM bngrc_dispatch_ETU003918 
            WHERE id_don = :id_don
            ORDER BY date_dispatch ASC
        ';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_don' => $idDon]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getQuantiteRestanteDon($idDon)
    {
        $sql = '
            SELECT 
                d.quantite - COALESCE(SUM(disp.quantite_attribuee), 0) as reste
            FROM bngrc_don_ETU003918 d
            LEFT JOIN bngrc_dispatch_ETU003918 disp ON d.id_don = disp.id_don
            WHERE d.id_don = :id_don
            GROUP BY d.id_don, d.quantite
        ';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_don' => $idDon]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (float) $result['reste'] : 0.0;
    }
    
    public function getQuantiteRestanteBesoin($idBesoin)
    {
        $sql = '
            SELECT 
                b.quantite - COALESCE(SUM(disp.quantite_attribuee), 0) as reste
            FROM bngrc_besoin_ETU003918 b
            LEFT JOIN bngrc_dispatch_ETU003918 disp ON b.id_besoin = disp.id_besoin
            WHERE b.id_besoin = :id_besoin
            GROUP BY b.id_besoin, b.quantite
        ';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_besoin' => $idBesoin]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (float) $result['reste'] : 0.0;
    }
    
    public function getDonsWithReste()
    {
        $sql = '
            SELECT 
                d.*,
                a.nom_article,
                a.prix_unitaire,
                c.nom_categorie,
                d.quantite - COALESCE(SUM(disp.quantite_attribuee), 0) as quantite_restante
            FROM bngrc_don_ETU003918 d
            JOIN bngrc_article_ETU003918 a ON d.id_article = a.id_article
            JOIN bngrc_categorie_besoin_ETU003918 c ON a.id_categorie = c.id_categorie
            LEFT JOIN bngrc_dispatch_ETU003918 disp ON d.id_don = disp.id_don
            WHERE EXISTS (
                SELECT 1 FROM bngrc_besoin_ETU003918 b
                WHERE b.id_article = d.id_article
                AND b.quantite > COALESCE(
                    (SELECT SUM(d2.quantite_attribuee) FROM bngrc_dispatch_ETU003918 d2 WHERE d2.id_besoin = b.id_besoin),
                    0
                )
            )
            GROUP BY d.id_don, d.id_article, d.quantite, d.date_don, a.nom_article, a.prix_unitaire, c.nom_categorie
            HAVING quantite_restante > 0
            ORDER BY d.date_don ASC
        ';
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function getDonById($idDon)
    {
        $sql = 'SELECT * FROM bngrc_don_ETU003918 WHERE id_don = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $idDon]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    private function getBesoinsByArticle($idArticle)
    {
        $sql = '
            SELECT b.*, v.nom_ville
            FROM bngrc_besoin_ETU003918 b
            JOIN bngrc_ville_ETU003918 v ON b.id_ville = v.id_ville
            WHERE b.id_article = :id_article
            ORDER BY b.date_saisie ASC
        ';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_article' => $idArticle]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function insertDispatch($idDon, $idBesoin, $quantite, $idVille)
    {
        $sql = '
            INSERT INTO bngrc_dispatch_ETU003918 
            (id_don, id_besoin, quantite_attribuee, id_ville, date_dispatch) 
            VALUES (:id_don, :id_besoin, :quantite, :id_ville, NOW())
        ';
        $stmt = $this->db->prepare($sql);
        $success = $stmt->execute([
            ':id_don' => $idDon,
            ':id_besoin' => $idBesoin,
            ':quantite' => $quantite,
            ':id_ville' => $idVille
        ]);
        
        if (!$success) {
            $errorInfo = $stmt->errorInfo();
            throw new \Exception("Erreur insertion dispatch: " . ($errorInfo[2] ?? 'Unknown error'));
        }
        
        return $success;
    }
    
    public function simulerDispatch($idsDons)
    {
        $results = [];
        
        foreach ($idsDons as $idDon) {
            $quantiteRestante = $this->getQuantiteRestanteDon($idDon);
            
            if ($quantiteRestante <= 0) {
                continue;
            }
            
            $don = $this->getDonById($idDon);
            
            if (!$don) {
                continue;
            }
            
            $besoins = $this->getBesoinsByArticle($don['id_article']);
            
            foreach ($besoins as $besoin) {
                if ($quantiteRestante <= 0) {
                    break;
                }
                
                $besoinRestant = $this->getQuantiteRestanteBesoin($besoin['id_besoin']);
                
                if ($besoinRestant <= 0) {
                    continue;
                }
                
                $quantiteAttribuee = min($quantiteRestante, $besoinRestant);
                
                $this->insertDispatch(
                    $idDon,
                    $besoin['id_besoin'],
                    $quantiteAttribuee,
                    $besoin['id_ville']
                );
                
                $results[] = [
                    'id_don' => $idDon,
                    'id_besoin' => $besoin['id_besoin'],
                    'ville' => $besoin['nom_ville'],
                    'quantite_attribuee' => $quantiteAttribuee
                ];
                
                $quantiteRestante -= $quantiteAttribuee;
            }
        }
        
        return $results;
    }
    
    public function deleteDispatch($id)
    {
        $sql = 'DELETE FROM bngrc_dispatch_ETU003918 WHERE id_dispatch = :id';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    public function clearAllDispatches()
    {
        $sql = 'DELETE FROM bngrc_dispatch_ETU003918';
        return $this->db->exec($sql);
    }

    public function simulateDispatchOnly($idsDons)
    {
        $results = [];
        
        foreach ($idsDons as $idDon) {
            $quantiteRestante = $this->getQuantiteRestanteDon($idDon);
            
            if ($quantiteRestante <= 0) {
                continue;
            }
            
            $don = $this->getDonById($idDon);
            
            if (!$don) {
                continue;
            }
            
            $sql = '
                SELECT c.nom_categorie
                FROM bngrc_article_ETU003918 a
                JOIN bngrc_categorie_besoin_ETU003918 c ON a.id_categorie = c.id_categorie
                WHERE a.id_article = :id_article
            ';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id_article' => $don['id_article']]);
            $catInfo = $stmt->fetch(PDO::FETCH_ASSOC);
            $nomCategorie = $catInfo ? $catInfo['nom_categorie'] : 'Inconnu';
            
            $besoins = $this->getBesoinsByArticle($don['id_article']);
            
            foreach ($besoins as $besoin) {
                if ($quantiteRestante <= 0) {
                    break;
                }
                
                $besoinRestant = $this->getQuantiteRestanteBesoin($besoin['id_besoin']);
                
                if ($besoinRestant <= 0) {
                    continue;
                }
                
                $quantiteAttribuee = min($quantiteRestante, $besoinRestant);
                
                $results[] = [
                    'id_don' => $idDon,
                    'id_besoin' => $besoin['id_besoin'],
                    'id_ville' => $besoin['id_ville'],
                    'ville' => $besoin['nom_ville'],
                    'id_article' => $don['id_article'],
                    'nom_categorie' => $nomCategorie,
                    'quantite_attribuee' => $quantiteAttribuee
                ];
                
                $quantiteRestante -= $quantiteAttribuee;
            }
        }
        
        return $results;
    }

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

    public function getSimulatedDispatchesParVilleArticle($simulatedDispatches)
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

        $articleCategories = [];
        foreach ($articles as $art) {
            $articleCategories[$art['id_article']] = $art['nom_categorie'];
        }

        $dispatchMatrix = [];
        foreach ($simulatedDispatches as $sim) {
            if (!isset($dispatchMatrix[$sim['id_ville']])) {
                $dispatchMatrix[$sim['id_ville']] = [];
            }
            if (!isset($dispatchMatrix[$sim['id_ville']][$sim['id_article']])) {
                $dispatchMatrix[$sim['id_ville']][$sim['id_article']] = 0;
            }
            $dispatchMatrix[$sim['id_ville']][$sim['id_article']] += $sim['quantite_attribuee'];
            
            if (isset($articleCategories[$sim['id_article']])) {
                $sim['nom_categorie'] = $articleCategories[$sim['id_article']];
            }
        }

        return [
            'villes' => $villes,
            'articles' => $articles,
            'matrix' => $dispatchMatrix
        ];
    }

    public function getSimulatedDispatchParCategorie($simulatedDispatches)
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
        $existing = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $totals = [];
        foreach ($existing as $row) {
            $totals[$row['nom_categorie']] = (float)$row['total'];
        }

        foreach ($simulatedDispatches as $sim) {
            $sql = '
                SELECT c.nom_categorie
                FROM bngrc_article_ETU003918 a
                JOIN bngrc_categorie_besoin_ETU003918 c ON a.id_categorie = c.id_categorie
                WHERE a.id_article = :id_article
            ';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id_article' => $sim['id_article']]);
            $cat = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($cat) {
                $catName = $cat['nom_categorie'];
                if (!isset($totals[$catName])) {
                    $totals[$catName] = 0;
                }
                $totals[$catName] += $sim['quantite_attribuee'];
            }
        }

        $result = [];
        foreach ($totals as $nom_categorie => $total) {
            $result[] = [
                'nom_categorie' => $nom_categorie,
                'total' => $total
            ];
        }

        return $result;
    }

    public function dispatchAllDonsChronologically()
    {
        $sql = '
            SELECT d.id_don
            FROM bngrc_don_ETU003918 d
            JOIN bngrc_article_ETU003918 a ON d.id_article = a.id_article
            WHERE EXISTS (
                SELECT 1 FROM bngrc_besoin_ETU003918 b
                WHERE b.id_article = d.id_article
                AND b.quantite > COALESCE(
                    (SELECT SUM(d2.quantite_attribuee) FROM bngrc_dispatch_ETU003918 d2 WHERE d2.id_besoin = b.id_besoin),
                    0
                )
            )
            AND d.quantite > COALESCE(
                (SELECT SUM(disp.quantite_attribuee) FROM bngrc_dispatch_ETU003918 disp WHERE disp.id_don = d.id_don),
                0
            )
            ORDER BY d.date_don ASC
        ';
        $stmt = $this->db->query($sql);
        $dons = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $idsDons = array_column($dons, 'id_don');
        
        if (empty($idsDons)) {
            return [];
        }
        
        return $this->simulerDispatch($idsDons);
    }

    public function simulateDispatchAllChronologically()
    {
        $sql = '
            SELECT d.id_don
            FROM bngrc_don_ETU003918 d
            JOIN bngrc_article_ETU003918 a ON d.id_article = a.id_article
            WHERE EXISTS (
                SELECT 1 FROM bngrc_besoin_ETU003918 b
                WHERE b.id_article = d.id_article
                AND b.quantite > COALESCE(
                    (SELECT SUM(d2.quantite_attribuee) FROM bngrc_dispatch_ETU003918 d2 WHERE d2.id_besoin = b.id_besoin),
                    0
                )
            )
            AND d.quantite > COALESCE(
                (SELECT SUM(disp.quantite_attribuee) FROM bngrc_dispatch_ETU003918 disp WHERE disp.id_don = d.id_don),
                0
            )
            ORDER BY d.date_don ASC
        ';
        $stmt = $this->db->query($sql);
        $dons = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $idsDons = array_column($dons, 'id_don');
        
        if (empty($idsDons)) {
            return [];
        }
        
        return $this->simulateDispatchOnly($idsDons);
    }
}
