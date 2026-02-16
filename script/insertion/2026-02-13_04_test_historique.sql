-- Données de test pour historiqueEchange afin de tester la vue v_historique_objet
-- Date: 2026-02-13

USE takalo;

-- Insertion des échanges historiques basés sur les demandes acceptées
-- Chaque échange accepté génère une entrée dans historiqueEchange

INSERT INTO historiqueEchange (idDemande, idDemandeur, idOffreur, idObjetOffert, idObjetDemande, dateEchange) VALUES
-- Échange 1: Bob offre Encyclopédie (8) pour Collection Jules Verne (3) d'Alice - Accepté le 2026-02-10
(1, 2, 1, 8, 3, '2026-02-10 15:30:00'),

-- Échange 2: Emma offre Set de valises (20) pour Manteau d'hiver (11) de Claire - Accepté le 2026-02-09
(2, 5, 3, 20, 11, '2026-02-09 18:45:00'),

-- Échange 3: David offre Bibliothèque (14) pour Vélo électrique (2) d'Alice - Accepté le 2026-02-08
(3, 4, 1, 14, 2, '2026-02-08 14:20:00'),

-- Échange 4: François offre Costume Hugo Boss (24) pour PS5 (13) de David - Accepté le 2026-02-07
(4, 6, 4, 24, 13, '2026-02-07 19:00:00'),

-- Échange 5: Claire offre Robot Thermomix (12) pour Set de golf (7) de Bob - Accepté le 2026-02-06
(5, 3, 2, 12, 7, '2026-02-06 16:30:00'),

-- Échange supplémentaire pour tester la chaîne de propriété
-- Alice offre son iPhone 13 (1) pour le MacBook de Bob (5) - Accepté récemment
(6, 1, 2, 1, 5, '2026-02-12 10:00:00'),

-- Claire offre sa Table à manger (10) pour le Vélo électrique d'Alice (2) - Accepté récemment
(7, 3, 1, 10, 2, '2026-02-11 12:00:00'),

-- David offre sa PS5 (13) pour le Samsung de Claire (9) - Accepté récemment
(8, 4, 3, 13, 9, '2026-02-10 08:00:00');

-- Test de la vue v_historique_objet
-- Cette vue devrait montrer l'historique de propriété pour chaque objet
-- Par exemple, pour l'objet "Vélo électrique" (idItem=2):
-- - Propriétaire initial: Alice
-- - Échangé avec David le 2026-02-08 (offre Bibliothèque)
-- - Échangé avec Claire le 2026-02-11 (offre Table à manger)
-- Propriétaire actuel: Claire