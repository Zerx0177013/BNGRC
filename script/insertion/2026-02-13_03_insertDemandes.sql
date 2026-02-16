-- Insertion des demandes d'échange pour Takalo-Takalo
-- Date: 2026-02-13

USE takalo;

-- Insertion des statuts de demande (si pas encore créés)
INSERT IGNORE INTO demandeStatus (idStatus, statusName) VALUES
(1, 'EN_ATTENTE'),
(2, 'ACCEPTEE'),
(3, 'REFUSEE'),
(4, 'ANNULEE');

-- Insertion des demandes d'échange
-- Format: (idDemandeur, idReceveur, idObjetOffert, idObjetDemande, idDemandeStatus, createdAt, statusAt)

INSERT INTO demande (idDemandeur, idReceveur, idObjetOffert, idObjetDemande, idDemandeStatus, createdAt, statusAt) VALUES
-- Demandes EN_ATTENTE (status 1)
-- Bob veut l'iPhone 13 d'Alice, offre son MacBook Air
(2, 1, 5, 1, 1, '2026-02-12 09:30:00', NULL),

-- Claire veut le vélo électrique d'Alice, offre sa table à manger
(3, 1, 10, 2, 1, '2026-02-12 14:15:00', NULL),

-- David veut le Samsung de Claire, offre sa PS5
(4, 3, 13, 9, 1, '2026-02-13 10:45:00', NULL),

-- Emma veut le canapé de Bob, offre sa commode vintage
(5, 2, 18, 6, 1, '2026-02-13 11:20:00', NULL),

-- François veut l'iPad Pro d'Emma, offre sa Nintendo Switch
(6, 5, 21, 17, 1, '2026-02-13 13:00:00', NULL),

-- Alice veut le MacBook de Bob, offre son iPhone 13
(1, 2, 1, 5, 1, '2026-02-11 16:30:00', NULL),

-- Demandes ACCEPTEES (status 2)
-- Bob voulait la collection Jules Verne d'Alice, offrait son encyclopédie - ACCEPTÉ
(2, 1, 8, 3, 2, '2026-02-10 10:00:00', '2026-02-10 15:30:00'),

-- Emma voulait le manteau d'hiver de Claire, offrait son set de valises - ACCEPTÉ
(5, 3, 20, 11, 2, '2026-02-09 11:00:00', '2026-02-09 18:45:00'),

-- David voulait le vélo électrique d'Alice, offrait sa bibliothèque - ACCEPTÉ
(4, 1, 14, 2, 2, '2026-02-08 09:15:00', '2026-02-08 14:20:00'),

-- François voulait la PS5 de David, offrait son costume Hugo Boss - ACCEPTÉ
(6, 4, 24, 13, 2, '2026-02-07 13:30:00', '2026-02-07 19:00:00'),

-- Claire voulait le set de golf de Bob, offrait son robot Thermomix - ACCEPTÉ
(3, 2, 12, 7, 2, '2026-02-06 10:45:00', '2026-02-06 16:30:00'),

-- Demandes REFUSEES (status 3)
-- Alice voulait la batterie de François, offrait sa robe de soirée - REFUSÉ
(1, 6, 4, 22, 3, '2026-02-11 14:00:00', '2026-02-11 20:15:00'),

-- Bob voulait l'appareil photo d'Emma, offrait son canapé - REFUSÉ
(2, 5, 6, 19, 3, '2026-02-10 15:30:00', '2026-02-11 09:00:00'),

-- David voulait le MacBook de Bob, offrait son coffret manga - REFUSÉ
(4, 2, 15, 5, 3, '2026-02-09 11:20:00', '2026-02-09 17:45:00'),

-- Emma voulait la table à manger de Claire, offrait ses plantes - REFUSÉ
(5, 3, 21, 10, 3, '2026-02-08 16:00:00', '2026-02-09 10:30:00'),

-- Demandes ANNULEES (status 4)
-- Claire voulait l'iPad d'Emma, offrait son Samsung - ANNULÉ par le demandeur
(3, 5, 9, 17, 4, '2026-02-07 10:00:00', '2026-02-07 12:30:00'),

-- François voulait le vélo électrique d'Alice, offrait sa collection BD - ANNULÉ
(6, 1, 23, 2, 4, '2026-02-06 14:15:00', '2026-02-06 18:00:00'),

-- Bob voulait les plantes d'Emma, offrait son encyclopédie - ANNULÉ
(2, 5, 8, 21, 4, '2026-02-05 11:45:00', '2026-02-05 15:20:00');

-- Résumé des demandes:
-- 6 demandes EN_ATTENTE (à traiter)
-- 5 demandes ACCEPTEES (échanges réussis)
-- 4 demandes REFUSEES (échanges refusés)
-- 3 demandes ANNULEES (annulées par les utilisateurs)
-- Total: 18 demandes
