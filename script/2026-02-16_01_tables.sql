-- Active: 1770652281308@@127.0.0.1@3306@bngrc
CREATE DATABASE IF NOT EXISTS bngrc;

USE bngrc;

-- ========== REGIONS ==========
CREATE TABLE bngrc_region_ETU003918(
    id_region INT AUTO_INCREMENT PRIMARY KEY,
    nom_region VARCHAR(100) NOT NULL
);

-- ========== VILLES ==========
CREATE TABLE bngrc_ville_ETU003918(
    id_ville INT AUTO_INCREMENT PRIMARY KEY,
    nom_ville VARCHAR(100) NOT NULL,
    id_region INT NOT NULL,
    FOREIGN KEY (id_region) REFERENCES bngrc_region_ETU003918(id_region)
);

-- ========== CATEGORIES DE BESOIN ==========
-- nature, matériaux, argent
CREATE TABLE bngrc_categorie_besoin_ETU003918(
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    nom_categorie VARCHAR(50) NOT NULL
);

-- ========== ARTICLES (types de besoin concrets) ==========
-- Ex: riz, huile, tôle, clou, argent
CREATE TABLE bngrc_article_ETU003918(
    id_article INT AUTO_INCREMENT PRIMARY KEY,
    nom_article VARCHAR(100) NOT NULL,
    prix_unitaire DECIMAL(12,2) NOT NULL,
    id_categorie INT NOT NULL,
    FOREIGN KEY (id_categorie) REFERENCES bngrc_categorie_besoin_ETU003918(id_categorie)
);

-- ========== BESOINS PAR VILLE ==========
CREATE TABLE bngrc_besoin_ETU003918(
    id_besoin INT AUTO_INCREMENT PRIMARY KEY,
    id_ville INT NOT NULL,
    id_article INT NOT NULL,
    quantite DECIMAL(12,2) NOT NULL,
    date_saisie DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_ville) REFERENCES bngrc_ville_ETU003918(id_ville),
    FOREIGN KEY (id_article) REFERENCES bngrc_article_ETU003918(id_article)
);

-- ========== DONS ==========
CREATE TABLE bngrc_don_ETU003918(
    id_don INT AUTO_INCREMENT PRIMARY KEY,
    id_article INT NOT NULL,
    quantite DECIMAL(12,2) NOT NULL,
    date_don DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_article) REFERENCES bngrc_article_ETU003918(id_article)
);

-- ========== DISPATCH (attribution des dons aux villes) ==========
CREATE OR REPLACE TABLE bngrc_dispatch_ETU003918(
    id_dispatch INT AUTO_INCREMENT PRIMARY KEY,
    id_don INT NOT NULL,
    id_besoin INT NOT NULL,
    quantite_attribuee DECIMAL(12,2) NOT NULL,
    id_ville INT NOT NULL,
    date_dispatch DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_don) REFERENCES bngrc_don_ETU003918(id_don),
    FOREIGN KEY (id_besoin) REFERENCES bngrc_besoin_ETU003918(id_besoin),
    FOREIGN KEY (id_ville) REFERENCES bngrc_ville_ETU003918(id_ville)
);