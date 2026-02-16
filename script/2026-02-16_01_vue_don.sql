CREATE OR REPLACE view v_info_dons_ETU003918 AS
SELECT d.*, a.nom_article, a.prix_unitaire, c.nom_categorie, (d.quantite * a.prix_unitaire) AS montant_total
FROM
    bngrc_don_ETU003918 d
    LEFT JOIN bngrc_article_ETU003918 a ON d.id_article = a.id_article
    LEFT JOIN bngrc_categorie_besoin_ETU003918 c ON a.id_categorie = c.id_categorie;
