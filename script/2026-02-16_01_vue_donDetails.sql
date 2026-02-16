USE bngrc;

CREATE OR REPLACE view v_don_details_ETU003918 AS
SELECT
    disp.*,
    d.id_article,
    d.quantite as quantite_don,
    d.date_don,
    b.quantite as quantite_besoin,
    b.date_saisie,
    v.nom_ville,
    a.nom_article
FROM
    bngrc_dispatch_ETU003918 disp
    JOIN bngrc_don_ETU003918 d ON disp.id_don = d.id_don
    JOIN bngrc_besoin_ETU003918 b ON disp.id_besoin = b.id_besoin
    JOIN bngrc_ville_ETU003918 v ON disp.id_ville = v.id_ville
    JOIN bngrc_article_ETU003918 a ON d.id_article = a.id_article