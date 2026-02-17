-- =============================================================
--  BNGRC - Données de test pour le calcul proportionnel
--  Exemple : Stock de 5, demandes de 1, 3, 3
-- =============================================================

USE bngrc;

-- Nettoyage des données existantes
DELETE FROM bngrc_dispatch_ETU003918;
DELETE FROM bngrc_achat_ETU003918;
DELETE FROM bngrc_don_ETU003918;
DELETE FROM bngrc_besoin_ETU003918;
DELETE FROM bngrc_article_ETU003918;
DELETE FROM bngrc_categorie_besoin_ETU003918;
DELETE FROM bngrc_ville_ETU003918;
DELETE FROM bngrc_region_ETU003918;

-- =============================================================
-- 1. REGIONS ET VILLES
-- =============================================================

INSERT INTO bngrc_region_ETU003918 (nom_region) VALUES 
('Region A'),
('Region B');

INSERT INTO bngrc_ville_ETU003918 (nom_ville, id_region) VALUES 
('Ville Alpha', 1),    -- Ville qui demande 1
('Ville Beta', 1),     -- Ville qui demande 3
('Ville Gamma', 2);    -- Ville qui demande 3

-- =============================================================
-- 2. CATEGORIES ET ARTICLES
-- =============================================================

INSERT INTO bngrc_categorie_besoin_ETU003918 (nom_categorie) VALUES 
('Nature'),
('Matériaux'),
('Argent');

-- Article de test pour l'exemple proportionnel
INSERT INTO bngrc_article_ETU003918 (nom_article, prix_unitaire, id_categorie) VALUES 
('Riz (kg)', 2500.00, 1),
('Tôle (unité)', 15000.00, 2),
('Argent (Ar)', 1.00, 3);

-- =============================================================
-- 3. BESOINS - Exemple : 1, 3, 3 (total = 7)
-- =============================================================

-- Ville Alpha demande 1 kg de riz
INSERT INTO bngrc_besoin_ETU003918 (id_ville, id_article, quantite, date_saisie) VALUES 
(1, 1, 1.00, '2026-02-17 08:00:00');

-- Ville Beta demande 3 kg de riz
INSERT INTO bngrc_besoin_ETU003918 (id_ville, id_article, quantite, date_saisie) VALUES 
(2, 1, 3.00, '2026-02-17 08:30:00');

-- Ville Gamma demande 3 kg de riz
INSERT INTO bngrc_besoin_ETU003918 (id_ville, id_article, quantite, date_saisie) VALUES 
(3, 1, 3.00, '2026-02-17 09:00:00');

-- =============================================================
-- 4. DONS - Stock disponible : 5 kg de riz
-- =============================================================

INSERT INTO bngrc_don_ETU003918 (id_article, quantite, date_don) VALUES 
(1, 5.00, '2026-02-17 10:00:00');

-- =============================================================
-- VERIFICATION DES DONNEES
-- =============================================================

SELECT '=== VERIFICATION DES DONNEES ===' as info;

SELECT 'Villes créées :' as info;
SELECT v.id_ville, v.nom_ville, r.nom_region 
FROM bngrc_ville_ETU003918 v
JOIN bngrc_region_ETU003918 r ON v.id_region = r.id_region
ORDER BY v.id_ville;

SELECT '' as '';
SELECT 'Articles créés :' as info;
SELECT a.id_article, a.nom_article, c.nom_categorie, a.prix_unitaire 
FROM bngrc_article_ETU003918 a
JOIN bngrc_categorie_besoin_ETU003918 c ON a.id_categorie = c.id_categorie
ORDER BY a.id_article;

SELECT '' as '';
SELECT 'Besoins (total demandé) :' as info;
SELECT 
    v.nom_ville,
    a.nom_article,
    b.quantite as 'Quantité demandée',
    b.date_saisie
FROM bngrc_besoin_ETU003918 b
JOIN bngrc_ville_ETU003918 v ON b.id_ville = v.id_ville
JOIN bngrc_article_ETU003918 a ON b.id_article = a.id_article
ORDER BY b.date_saisie;

SELECT '' as '';
SELECT 'Total des besoins :' as info;
SELECT 
    a.nom_article,
    SUM(b.quantite) as 'Total demandé'
FROM bngrc_besoin_ETU003918 b
JOIN bngrc_article_ETU003918 a ON b.id_article = a.id_article
GROUP BY a.nom_article;

SELECT '' as '';
SELECT 'Dons disponibles (stock) :' as info;
SELECT 
    d.id_don,
    a.nom_article,
    d.quantite as 'Stock disponible',
    d.date_don
FROM bngrc_don_ETU003918 d
JOIN bngrc_article_ETU003918 a ON d.id_article = a.id_article
ORDER BY d.date_don;

SELECT '' as '';
SELECT 'CALCUL PROPORTIONNEL ATTENDU :' as info;
SELECT '-----------------------------------' as '';
SELECT 'Stock disponible : 5' as '';
SELECT 'Demandes : 1, 3, 3 (total = 7)' as '';
SELECT '' as '';
SELECT 'Proportions : 1/7, 3/7, 3/7' as '';
SELECT 'Calcul : 5 × 1/7 = 0.714, 5 × 3/7 = 2.142, 5 × 3/7 = 2.142' as '';
SELECT '' as '';
SELECT 'Avec floor() : 0, 2, 2 (total = 4)' as '';
SELECT 'Restes : 0.714, 0.142, 0.142' as '';
SELECT '' as '';
SELECT 'Redistribution du reste (1 unité) :' as '';
SELECT '  - Ville Alpha : 0.714 (plus grand reste) → +1' as '';
SELECT '' as '';
SELECT 'DISTRIBUTION FINALE ATTENDUE :' as '';
SELECT '  - Ville Alpha : 1 kg' as '';
SELECT '  - Ville Beta  : 2 kg' as '';
SELECT '  - Ville Gamma : 2 kg' as '';
SELECT '  - TOTAL       : 5 kg ✓' as '';
SELECT '-----------------------------------' as '';

-- =============================================================
-- CAS DE TEST SUPPLEMENTAIRES
-- =============================================================

-- Cas 2 : Argent avec demandes égales
INSERT INTO bngrc_besoin_ETU003918 (id_ville, id_article, quantite, date_saisie) VALUES 
(1, 3, 100000.00, '2026-02-17 11:00:00'),  -- Ville Alpha : 100,000 Ar
(2, 3, 100000.00, '2026-02-17 11:10:00'),  -- Ville Beta : 100,000 Ar
(3, 3, 100000.00, '2026-02-17 11:20:00');  -- Ville Gamma : 100,000 Ar

-- Don : 250,000 Ar disponible
INSERT INTO bngrc_don_ETU003918 (id_article, quantite, date_don) VALUES 
(3, 250000.00, '2026-02-17 12:00:00');

SELECT '' as '';
SELECT '=== CAS 2 : ARGENT AVEC DEMANDES EGALES ===' as info;
SELECT 'Stock disponible : 250,000 Ar' as '';
SELECT 'Demandes : 100,000, 100,000, 100,000 (total = 300,000 Ar)' as '';
SELECT 'Calcul proportionnel : 250,000 × 1/3 = 83,333.33 pour chacun' as '';
SELECT 'Avec floor() : 83,333 × 3 = 249,999 (reste 1 Ar)' as '';
SELECT 'Restes : 0.33, 0.33, 0.33 (égaux → choisir la plus grande demande)' as '';
SELECT 'Distribution finale attendue : 83,334, 83,333, 83,333 = 250,000 Ar ✓' as '';

-- Cas 3 : Tôles avec proportions variées
INSERT INTO bngrc_besoin_ETU003918 (id_ville, id_article, quantite, date_saisie) VALUES 
(1, 2, 5.00, '2026-02-17 13:00:00'),   -- Ville Alpha : 5 tôles
(2, 2, 10.00, '2026-02-17 13:10:00'),  -- Ville Beta : 10 tôles
(3, 2, 15.00, '2026-02-17 13:20:00');  -- Ville Gamma : 15 tôles

-- Don : 20 tôles disponibles
INSERT INTO bngrc_don_ETU003918 (id_article, quantite, date_don) VALUES 
(2, 20.00, '2026-02-17 14:00:00');

SELECT '' as '';
SELECT '=== CAS 3 : TOLES AVEC PROPORTIONS VARIEES ===' as info;
SELECT 'Stock disponible : 20 tôles' as '';
SELECT 'Demandes : 5, 10, 15 (total = 30 tôles)' as '';
SELECT 'Proportions : 1/6, 1/3, 1/2' as '';
SELECT 'Calcul : 20 × 1/6 = 3.33, 20 × 1/3 = 6.66, 20 × 1/2 = 10.00' as '';
SELECT 'Avec floor() : 3, 6, 10 = 19 (reste 1)' as '';
SELECT 'Restes : 0.33, 0.66, 0.00' as '';
SELECT 'Distribution finale attendue : 3, 7, 10 = 20 tôles ✓' as '';

SELECT '' as '';
SELECT '============================================' as '';
SELECT 'Pour tester le dispatch proportionnel :' as '';
SELECT '1. Accéder à la page de dispatch' as '';
SELECT '2. Sélectionner la stratégie "Proportionnelle"' as '';
SELECT '3. Vérifier que les résultats correspondent aux calculs ci-dessus' as '';
SELECT '============================================' as '';
