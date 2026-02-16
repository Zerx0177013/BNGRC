CREATE OR REPLACE VIEW v_achats_complets_ETU003918 AS
SELECT
    ac.*,
    b.id_ville,
    b.id_article,
    b.quantite as quantite_besoin,
    a.nom_article,
    a.prix_unitaire as prix_article,
    c.nom_categorie,
    v.nom_ville,
    r.nom_region,
    cfg.valeur as frais_percent,
    d.quantite as quantite_don,
    d.date_don
FROM bngrc_achat_ETU003918 ac
JOIN bngrc_besoin_ETU003918 b ON ac.id_besoin = b.id_besoin
JOIN bngrc_article_ETU003918 a ON b.id_article = a.id_article
JOIN bngrc_categorie_besoin_ETU003918 c ON a.id_categorie = c.id_categorie
JOIN bngrc_ville_ETU003918 v ON b.id_ville = v.id_ville
JOIN bngrc_region_ETU003918 r ON v.id_region = r.id_region
JOIN bngrc_config_ETU003918 cfg ON ac.id_config = cfg.id_config
JOIN bngrc_don_ETU003918 d ON ac.id_don = d.id_don;
