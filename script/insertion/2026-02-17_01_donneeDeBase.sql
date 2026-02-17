-- =============================================================
--  BNGRC - Script d'insertion des données de base
--  Date: 2026-02-17
-- =============================================================
USE bngrc;

DELETE FROM bngrc_region_ETU003918;
DELETE FROM bngrc_ville_ETU003918;
DELETE FROM bngrc_categorie_besoin_ETU003918;
DELETE FROM bngrc_article_ETU003918;
DELETE FROM bngrc_besoin_ETU003918;
DELETE FROM bngrc_don_ETU003918;
DELETE FROM bngrc_dispatch_ETU003918;

-- ========== REGIONS ==========
INSERT INTO bngrc_region_ETU003918 (id_region, nom_region) VALUES
(1, 'Atsinanana'),
(2, 'Vatovavy-Fitovinany'),
(3, 'Atsimo-Atsinanana'),
(4, 'Diana'),
(5, 'Menabe');

-- ========== VILLES ==========
INSERT INTO bngrc_ville_ETU003918 (id_ville, nom_ville, id_region) VALUES
(1, 'Toamasina', 1),
(2, 'Mananjary', 2),
(3, 'Farafangana', 3),
(4, 'Nosy Be', 4),
(5, 'Morondava', 5);

-- ========== CATEGORIES DE BESOIN ==========
INSERT INTO bngrc_categorie_besoin_ETU003918 (id_categorie, nom_categorie) VALUES
(1, 'nature'),
(2, 'materiel'),
(3, 'argent');

-- ========== ARTICLES ==========
-- Category: nature (id_categorie = 1)
INSERT INTO bngrc_article_ETU003918 (id_article, nom_article, prix_unitaire, id_categorie) VALUES
(1, 'Riz (kg)', 3000.00, 1),
(2, 'Eau (L)', 1000.00, 1),
(3, 'Huile (L)', 6000.00, 1),
(4, 'Haricots', 4000.00, 1);

-- Category: materiel (id_categorie = 2)
INSERT INTO bngrc_article_ETU003918 (id_article, nom_article, prix_unitaire, id_categorie) VALUES
(5, 'Tôle', 25000.00, 2),
(6, 'Bâche', 15000.00, 2),
(7, 'Clous (kg)', 8000.00, 2),
(8, 'Bois', 10000.00, 2),
(9, 'groupe', 6750000.00, 2);

-- Category: argent (id_categorie = 3)
INSERT INTO bngrc_article_ETU003918 (id_article, nom_article, prix_unitaire, id_categorie) VALUES
(10, 'Argent', 1.00, 3);

-- ========== BESOINS (ordonnés par ordre d'insertion) ==========
-- Ordre 1: Toamasina - Bâche
INSERT INTO bngrc_besoin_ETU003918 (id_ville, id_article, quantite, date_saisie) VALUES
(1, 6, 200.00, '2026-02-15 00:00:00');

-- Ordre 2: Nosy Be - Tôle
INSERT INTO bngrc_besoin_ETU003918 (id_ville, id_article, quantite, date_saisie) VALUES
(4, 5, 40.00, '2026-02-15 00:00:00');

-- Ordre 3: Mananjary - Argent
INSERT INTO bngrc_besoin_ETU003918 (id_ville, id_article, quantite, date_saisie) VALUES
(2, 10, 6000000.00, '2026-02-15 00:00:00');

-- Ordre 4: Toamasina - Eau (L)
INSERT INTO bngrc_besoin_ETU003918 (id_ville, id_article, quantite, date_saisie) VALUES
(1, 2, 1500.00, '2026-02-15 00:00:00');

-- Ordre 5: Nosy Be - Riz (kg)
INSERT INTO bngrc_besoin_ETU003918 (id_ville, id_article, quantite, date_saisie) VALUES
(4, 1, 300.00, '2026-02-15 00:00:00');

-- Ordre 6: Mananjary - Tôle
INSERT INTO bngrc_besoin_ETU003918 (id_ville, id_article, quantite, date_saisie) VALUES
(2, 5, 80.00, '2026-02-15 00:00:00');

-- Ordre 7: Nosy Be - Argent
INSERT INTO bngrc_besoin_ETU003918 (id_ville, id_article, quantite, date_saisie) VALUES
(4, 10, 4000000.00, '2026-02-15 00:00:00');

-- Ordre 8: Farafangana - Bâche
INSERT INTO bngrc_besoin_ETU003918 (id_ville, id_article, quantite, date_saisie) VALUES
(3, 6, 150.00, '2026-02-16 00:00:00');

-- Ordre 9: Mananjary - Riz (kg)
INSERT INTO bngrc_besoin_ETU003918 (id_ville, id_article, quantite, date_saisie) VALUES
(2, 1, 500.00, '2026-02-15 00:00:00');

-- Ordre 10: Farafangana - Argent
INSERT INTO bngrc_besoin_ETU003918 (id_ville, id_article, quantite, date_saisie) VALUES
(3, 10, 8000000.00, '2026-02-16 00:00:00');

-- Ordre 11: Morondava - Riz (kg)
INSERT INTO bngrc_besoin_ETU003918 (id_ville, id_article, quantite, date_saisie) VALUES
(5, 1, 700.00, '2026-02-16 00:00:00');

-- Ordre 12: Toamasina - Argent
INSERT INTO bngrc_besoin_ETU003918 (id_ville, id_article, quantite, date_saisie) VALUES
(1, 10, 12000000.00, '2026-02-16 00:00:00');

-- Ordre 13: Morondava - Argent
INSERT INTO bngrc_besoin_ETU003918 (id_ville, id_article, quantite, date_saisie) VALUES
(5, 10, 10000000.00, '2026-02-16 00:00:00');

-- Ordre 14: Farafangana - Eau (L)
INSERT INTO bngrc_besoin_ETU003918 (id_ville, id_article, quantite, date_saisie) VALUES
(3, 2, 1000.00, '2026-02-15 00:00:00');

-- Ordre 15: Morondava - Bâche
INSERT INTO bngrc_besoin_ETU003918 (id_ville, id_article, quantite, date_saisie) VALUES
(5, 6, 180.00, '2026-02-16 00:00:00');

-- Ordre 16: Toamasina - groupe
INSERT INTO bngrc_besoin_ETU003918 (id_ville, id_article, quantite, date_saisie) VALUES
(1, 9, 3.00, '2026-02-15 00:00:00');

-- Ordre 17: Toamasina - Riz (kg)
INSERT INTO bngrc_besoin_ETU003918 (id_ville, id_article, quantite, date_saisie) VALUES
(1, 1, 800.00, '2026-02-16 00:00:00');

-- Ordre 18: Nosy Be - Haricots
INSERT INTO bngrc_besoin_ETU003918 (id_ville, id_article, quantite, date_saisie) VALUES
(4, 4, 200.00, '2026-02-16 00:00:00');

-- Ordre 19: Mananjary - Clous (kg)
INSERT INTO bngrc_besoin_ETU003918 (id_ville, id_article, quantite, date_saisie) VALUES
(2, 7, 60.00, '2026-02-16 00:00:00');

-- Ordre 20: Morondava - Eau (L)
INSERT INTO bngrc_besoin_ETU003918 (id_ville, id_article, quantite, date_saisie) VALUES
(5, 2, 1200.00, '2026-02-15 00:00:00');

-- Ordre 21: Farafangana - Riz (kg)
INSERT INTO bngrc_besoin_ETU003918 (id_ville, id_article, quantite, date_saisie) VALUES
(3, 1, 600.00, '2026-02-16 00:00:00');

-- Ordre 22: Morondava - Bois
INSERT INTO bngrc_besoin_ETU003918 (id_ville, id_article, quantite, date_saisie) VALUES
(5, 8, 150.00, '2026-02-15 00:00:00');

-- Ordre 23: Toamasina - Tôle
INSERT INTO bngrc_besoin_ETU003918 (id_ville, id_article, quantite, date_saisie) VALUES
(1, 5, 120.00, '2026-02-16 00:00:00');

-- Ordre 24: Nosy Be - Clous (kg)
INSERT INTO bngrc_besoin_ETU003918 (id_ville, id_article, quantite, date_saisie) VALUES
(4, 7, 30.00, '2026-02-16 00:00:00');

-- Ordre 25: Mananjary - Huile (L)
INSERT INTO bngrc_besoin_ETU003918 (id_ville, id_article, quantite, date_saisie) VALUES
(2, 3, 120.00, '2026-02-16 00:00:00');

-- Ordre 26: Farafangana - Bois
INSERT INTO bngrc_besoin_ETU003918 (id_ville, id_article, quantite, date_saisie) VALUES
(3, 8, 100.00, '2026-02-15 00:00:00');

-- ========== DONS ==========
INSERT INTO bngrc_don_ETU003918 (id_article, quantite, date_don) VALUES
(10, 5000000.00, '2026-02-16 00:00:00'),
(10, 3000000.00, '2026-02-16 00:00:00'),
(10, 4000000.00, '2026-02-17 00:00:00'),
(10, 1500000.00, '2026-02-17 00:00:00'),
(10, 6000000.00, '2026-02-17 00:00:00'),
(1, 400.00, '2026-02-16 00:00:00'),
(2, 600.00, '2026-02-16 00:00:00'),
(5, 50.00, '2026-02-17 00:00:00'),
(6, 70.00, '2026-02-17 00:00:00'),
(4, 100.00, '2026-02-17 00:00:00'),
(1, 2000.00, '2026-02-18 00:00:00'),
(5, 300.00, '2026-02-18 00:00:00'),
(2, 5000.00, '2026-02-18 00:00:00'),
(10, 20000000.00, '2026-02-19 00:00:00'),
(6, 500.00, '2026-02-19 00:00:00'),
(4, 88.00, '2026-02-17 00:00:00');
