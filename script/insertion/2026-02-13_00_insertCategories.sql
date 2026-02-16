-- Insertion des catégories et statuts pour Takalo-Takalo
-- Date: 2026-02-13

USE takalo;

-- Insertion des catégories
INSERT INTO categorie (idcategorie, name) VALUES
(1, 'Électronique'),
(2, 'Livres'),
(3, 'Vêtements'),
(4, 'Meubles'),
(5, 'Sports et Loisirs'),
(6, 'Jouets'),
(7, 'Jardinage'),
(8, 'Cuisine');

-- Insertion des statuts de demande
INSERT INTO demandeStatus (idStatus, statusName) VALUES
(1, 'En attente'),
(2, 'Acceptée'),
(3, 'Refusée'),
(4, 'Terminée');

-- Confirmation
SELECT 'Catégories et statuts insérés avec succès!' AS Message;
