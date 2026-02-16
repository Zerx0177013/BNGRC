<?php
namespace app\models;
use PDO;

class AchatModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllAchats()
    {
        $sql = 'SELECT * FROM v_achats_complets_ETU003918 ORDER BY date_achat DESC';
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAchatById($id)
    {
        $sql = 'SELECT * FROM v_achats_complets_ETU003918 WHERE id_achat = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAchatsByVille($idVille)
    {
        $sql = 'SELECT * FROM v_achats_complets_ETU003918 WHERE id_ville = :id_ville ORDER BY date_achat DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_ville' => $idVille]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getConfig()
    {
        $sql = 'SELECT * FROM bngrc_config_ETU003918 ORDER BY id_config DESC LIMIT 1';
        $stmt = $this->db->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getDonsArgentDisponibles()
    {
        $sql = '
            SELECT 
                d.*,
                a.nom_article,
                c.nom_categorie,
                d.quantite - COALESCE(
                    (SELECT SUM(ac.montant_total) FROM bngrc_achat_ETU003918 ac WHERE ac.id_don = d.id_don), 0
                ) - COALESCE(
                    (SELECT SUM(disp.quantite_attribuee) FROM bngrc_dispatch_ETU003918 disp WHERE disp.id_don = d.id_don), 0
                ) as montant_restant
            FROM bngrc_don_ETU003918 d
            JOIN bngrc_article_ETU003918 a ON d.id_article = a.id_article
            JOIN bngrc_categorie_besoin_ETU003918 c ON a.id_categorie = c.id_categorie
            WHERE c.nom_categorie = \'Argent\'
            HAVING montant_restant > 0
            ORDER BY d.date_don ASC
        ';
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBesoinsRestantsNonArgent()
    {
        $sql = '
            SELECT 
                b.*,
                a.nom_article,
                a.prix_unitaire,
                c.nom_categorie,
                v.nom_ville,
                b.quantite - COALESCE(
                    (SELECT SUM(disp.quantite_attribuee) FROM bngrc_dispatch_ETU003918 disp WHERE disp.id_besoin = b.id_besoin), 0
                ) - COALESCE(
                    (SELECT SUM(ac.quantite) FROM bngrc_achat_ETU003918 ac WHERE ac.id_besoin = b.id_besoin), 0
                ) as quantite_restante
            FROM bngrc_besoin_ETU003918 b
            JOIN bngrc_article_ETU003918 a ON b.id_article = a.id_article
            JOIN bngrc_categorie_besoin_ETU003918 c ON a.id_categorie = c.id_categorie
            JOIN bngrc_ville_ETU003918 v ON b.id_ville = v.id_ville
            WHERE c.nom_categorie != \'Argent\'
            HAVING quantite_restante > 0
            ORDER BY b.date_saisie ASC
        ';
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function donExistePourArticle($idArticle)
    {
        $sql = '
            SELECT d.id_don
            FROM bngrc_don_ETU003918 d
            JOIN bngrc_article_ETU003918 a ON d.id_article = a.id_article
            JOIN bngrc_categorie_besoin_ETU003918 c ON a.id_categorie = c.id_categorie
            WHERE d.id_article = :id_article
            AND c.nom_categorie != \'Argent\'
            AND d.quantite > COALESCE(
                (SELECT SUM(disp.quantite_attribuee) FROM bngrc_dispatch_ETU003918 disp WHERE disp.id_don = d.id_don), 0
            )
            LIMIT 1
        ';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_article' => $idArticle]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }

    public function getArticleIdByBesoin($idBesoin)
    {
        $sql = 'SELECT id_article FROM bngrc_besoin_ETU003918 WHERE id_besoin = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $idBesoin]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['id_article'] : null;
    }

    public function getPrixUnitaireByBesoin($idBesoin)
    {
        $sql = '
            SELECT a.prix_unitaire 
            FROM bngrc_besoin_ETU003918 b 
            JOIN bngrc_article_ETU003918 a ON b.id_article = a.id_article 
            WHERE b.id_besoin = :id
        ';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $idBesoin]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (float) $row['prix_unitaire'] : null;
    }

    public function getDonArgentById($idDon)
    {
        $donsArgent = $this->getDonsArgentDisponibles();
        foreach ($donsArgent as $d) {
            if ($d['id_don'] == $idDon) {
                return $d;
            }
        }
        return null;
    }

    public function createAchat($idBesoin, $idDon, $idConfig, $quantite, $prixUnitaire, $montantTotal)
    {
        $sql = '
            INSERT INTO bngrc_achat_ETU003918 (id_besoin, id_don, id_config, quantite, prix_unitaire, montant_total)
            VALUES (:id_besoin, :id_don, :id_config, :quantite, :prix_unitaire, :montant_total)
        ';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id_besoin' => $idBesoin,
            ':id_don' => $idDon,
            ':id_config' => $idConfig,
            ':quantite' => $quantite,
            ':prix_unitaire' => $prixUnitaire,
            ':montant_total' => $montantTotal
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function deleteAchat($id)
    {
        $sql = 'DELETE FROM bngrc_achat_ETU003918 WHERE id_achat = :id';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
