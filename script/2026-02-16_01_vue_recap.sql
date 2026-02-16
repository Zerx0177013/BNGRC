-- Vue pour la récapitulation des besoins
CREATE OR REPLACE VIEW v_recap_besoins_ETU003918 AS
SELECT 
    -- Montant total des besoins
    COALESCE(SUM(b.quantite * a.prix_unitaire), 0) as montant_total,
    -- Montant total des besoins satisfait
    COALESCE(SUM(
        (COALESCE(d.total_dispatche, 0) + COALESCE(ac.total_achete, 0)) * a.prix_unitaire
    ), 0) as montant_satisfait
    
FROM bngrc_besoin_ETU003918 b
JOIN bngrc_article_ETU003918 a ON b.id_article = a.id_article
LEFT JOIN (
    SELECT id_besoin, SUM(quantite_attribuee) as total_dispatche
    FROM bngrc_dispatch_ETU003918
    GROUP BY id_besoin
) d ON d.id_besoin = b.id_besoin
LEFT JOIN (
    SELECT id_besoin, SUM(quantite) as total_achete
    FROM bngrc_achat_ETU003918
    GROUP BY id_besoin
) ac ON ac.id_besoin = b.id_besoin;
