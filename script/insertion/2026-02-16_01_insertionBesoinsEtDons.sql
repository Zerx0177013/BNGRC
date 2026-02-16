USE bngrc;

-- ========== REGIONS ==========
INSERT INTO bngrc_region_ETU003918 (nom_region) VALUES
('Analamanga'),
('Vakinankaratra'),
('Atsinanana'),
('Boeny');

-- ========== VILLES ==========
INSERT INTO bngrc_ville_ETU003918 (nom_ville, id_region) VALUES
('Antananarivo', 1),
('Ambohimangakely', 1),
('Antsirabe', 2),
('Ambatolampy', 2),
('Toamasina', 3),
('Mahajanga', 4);

-- ========== CATEGORIES DE BESOIN ==========
INSERT INTO bngrc_categorie_besoin_ETU003918 (nom_categorie) VALUES
('Nature'),
('Matériaux'),
('Argent');

-- ========== ARTICLES ==========
INSERT INTO bngrc_article_ETU003918 (nom_article, prix_unitaire, id_categorie) VALUES
-- Nature (cat 1)
('Riz (kg)', 2800.00, 1),
('Huile (litre)', 12000.00, 1),
('Sucre (kg)', 5000.00, 1),
('Eau (bidon 20L)', 3000.00, 1),
-- Matériaux (cat 2)
('Tôle (unité)', 45000.00, 2),
('Clou (kg)', 8000.00, 2),
('Bois (planche)', 15000.00, 2),
('Bâche (unité)', 25000.00, 2),
-- Argent (cat 3)
('Argent (Ar)', 1.00, 3);

-- ========== BESOINS PAR VILLE ==========
INSERT INTO bngrc_besoin_ETU003918 (id_ville, id_article, quantite, date_saisie) VALUES
-- Antananarivo : riz, huile, tôle, argent
(1, 1, 500.00, '2026-02-10 08:00:00'),
(1, 2, 100.00, '2026-02-10 08:05:00'),
(1, 5, 50.00,  '2026-02-10 08:10:00'),
(1, 9, 5000000.00, '2026-02-10 08:15:00'),

-- Ambohimangakely : riz, sucre, bâche
(2, 1, 300.00, '2026-02-10 09:00:00'),
(2, 3, 80.00,  '2026-02-10 09:05:00'),
(2, 8, 30.00,  '2026-02-10 09:10:00'),

-- Antsirabe : riz, huile, clou, bois
(3, 1, 400.00, '2026-02-11 10:00:00'),
(3, 2, 150.00, '2026-02-11 10:05:00'),
(3, 6, 60.00,  '2026-02-11 10:10:00'),
(3, 7, 100.00, '2026-02-11 10:15:00'),

-- Ambatolampy : eau, tôle, argent
(4, 4, 200.00, '2026-02-11 11:00:00'),
(4, 5, 40.00,  '2026-02-11 11:05:00'),
(4, 9, 3000000.00, '2026-02-11 11:10:00'),

-- Toamasina : riz, huile, tôle, bâche, argent
(5, 1, 600.00, '2026-02-12 07:00:00'),
(5, 2, 200.00, '2026-02-12 07:05:00'),
(5, 5, 80.00,  '2026-02-12 07:10:00'),
(5, 8, 50.00,  '2026-02-12 07:15:00'),
(5, 9, 8000000.00, '2026-02-12 07:20:00'),

-- Mahajanga : riz, sucre, eau, clou
(6, 1, 350.00, '2026-02-13 14:00:00'),
(6, 3, 100.00, '2026-02-13 14:05:00'),
(6, 4, 150.00, '2026-02-13 14:10:00'),
(6, 6, 40.00,  '2026-02-13 14:15:00');

-- ========== DONS ==========
INSERT INTO bngrc_don_ETU003918 (id_article, quantite, date_don) VALUES
-- Dons de riz
(1, 800.00, '2026-02-13 09:00:00'),
(1, 500.00, '2026-02-14 10:00:00'),

-- Dons d'huile
(2, 200.00, '2026-02-13 09:30:00'),
(2, 100.00, '2026-02-14 11:00:00'),

-- Dons de sucre
(3, 120.00, '2026-02-13 10:00:00'),

-- Dons d'eau
(4, 250.00, '2026-02-14 08:00:00'),

-- Dons de tôle
(5, 100.00, '2026-02-13 11:00:00'),
(5, 50.00,  '2026-02-15 09:00:00'),

-- Dons de clou
(6, 70.00, '2026-02-14 14:00:00'),

-- Dons de bois
(7, 60.00, '2026-02-14 15:00:00'),

-- Dons de bâche
(8, 40.00, '2026-02-13 16:00:00'),

-- Dons d'argent
(9, 10000000.00, '2026-02-13 12:00:00'),
(9, 5000000.00,  '2026-02-15 10:00:00');

-- ========== DISPATCH (simulation par ordre de date_saisie) ==========
INSERT INTO bngrc_dispatch_ETU003918 (id_don, id_besoin, quantite_attribuee, date_dispatch) VALUES

-- Don riz #1 (800kg) → dispatché par ordre de saisie des besoins
(1, 1, 500.00, '2026-02-14 08:00:00'),   -- Antananarivo reçoit ses 500kg
(1, 5, 300.00, '2026-02-14 08:01:00'),   -- Ambohimangakely reçoit 300 sur 300

-- Don riz #2 (500kg)
(2, 8, 400.00, '2026-02-15 08:00:00'),   -- Antsirabe reçoit ses 400kg
(2, 16, 100.00, '2026-02-15 08:01:00'),  -- Toamasina reçoit 100 sur 600

-- Don huile #3 (200L)
(3, 2, 100.00, '2026-02-14 08:10:00'),   -- Antananarivo reçoit ses 100L
(3, 9, 100.00, '2026-02-14 08:11:00'),   -- Antsirabe reçoit 100 sur 150

-- Don huile #4 (100L)
(4, 9, 50.00,  '2026-02-15 08:10:00'),   -- Antsirabe reçoit les 50 restants
(4, 17, 50.00, '2026-02-15 08:11:00'),   -- Toamasina reçoit 50 sur 200

-- Don sucre #5 (120kg)
(5, 6, 80.00,  '2026-02-14 08:20:00'),   -- Ambohimangakely reçoit ses 80kg
(5, 21, 40.00, '2026-02-14 08:21:00'),   -- Mahajanga reçoit 40 sur 100

-- Don eau #6 (250 bidons)
(6, 13, 200.00, '2026-02-15 08:20:00'),  -- Ambatolampy reçoit ses 200
(6, 22, 50.00,  '2026-02-15 08:21:00'),  -- Mahajanga reçoit 50 sur 150

-- Don tôle #7 (100 unités)
(7, 3, 50.00,  '2026-02-14 08:30:00'),   -- Antananarivo reçoit ses 50
(7, 14, 40.00, '2026-02-14 08:31:00'),   -- Ambatolampy reçoit ses 40
(7, 18, 10.00, '2026-02-14 08:32:00'),   -- Toamasina reçoit 10 sur 80

-- Don tôle #8 (50 unités)
(8, 18, 50.00, '2026-02-16 08:00:00'),   -- Toamasina reçoit 50 de plus (total 60/80)

-- Don clou #9 (70kg)
(9, 10, 60.00, '2026-02-15 08:30:00'),   -- Antsirabe reçoit ses 60kg
(9, 23, 10.00, '2026-02-15 08:31:00'),   -- Mahajanga reçoit 10 sur 40

-- Don bois #10 (60 planches)
(10, 11, 60.00, '2026-02-15 08:40:00'),  -- Antsirabe reçoit ses 60 sur 100

-- Don bâche #11 (40 unités)
(11, 7, 30.00,  '2026-02-14 08:40:00'),  -- Ambohimangakely reçoit ses 30
(11, 19, 10.00, '2026-02-14 08:41:00'),  -- Toamasina reçoit 10 sur 50

-- Don argent #12 (10M Ar)
(12, 4, 5000000.00,  '2026-02-14 09:00:00'),  -- Antananarivo reçoit ses 5M
(12, 15, 3000000.00, '2026-02-14 09:01:00'),  -- Ambatolampy reçoit ses 3M
(12, 20, 2000000.00, '2026-02-14 09:02:00'),  -- Toamasina reçoit 2M sur 8M

-- Don argent #13 (5M Ar)
(13, 20, 5000000.00, '2026-02-16 09:00:00');  -- Toamasina reçoit 5M de plus (total 7M/8M)
