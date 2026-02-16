USE bngrc;

CREATE OR REPLACE view v_besoins_complets_ETU003918 AS
SELECT b.*, v.nom_ville, r.nom_region, a.nom_article, a.prix_unitaire, c.nom_categorie, (b.quantite * a.prix_unitaire) AS montant_total
FROM
    bngrc_besoin_ETU003918 b
    LEFT JOIN bngrc_ville_ETU003918 v ON b.id_ville = v.id_ville
    LEFT JOIN bngrc_region_ETU003918 r ON v.id_region = r.id_region
    LEFT JOIN bngrc_article_ETU003918 a ON b.id_article = a.id_article
    LEFT JOIN bngrc_categorie_besoin_ETU003918 c ON a.id_categorie = c.id_categorie;