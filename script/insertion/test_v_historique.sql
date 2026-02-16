-- Script de test pour la vue v_historique_objet
-- Date: 2026-02-13

USE takalo;

-- Test 1: Afficher tout l'historique des objets
SELECT * FROM v_historique_objet ORDER BY idItem, dateEchange;

-- Test 2: Historique pour un objet spécifique (ex: Vélo électrique, idItem=2)
SELECT * FROM v_historique_objet WHERE idItem = 2 ORDER BY dateEchange;

-- Test 3: Historique pour un objet spécifique (ex: iPhone 13, idItem=1)
SELECT * FROM v_historique_objet WHERE idItem = 1 ORDER BY dateEchange;

-- Test 4: Compter le nombre d'échanges par objet
SELECT idItem, nom_objet, COUNT(*) as nombre_echanges
FROM v_historique_objet
GROUP BY idItem, nom_objet
ORDER BY nombre_echanges DESC;

-- Test 5: Propriétaires actuels (dernière entrée pour chaque objet)
SELECT h.idItem, h.nom_objet, h.nouveau_proprietaire as proprietaire_actuel, h.dateEchange as date_dernier_echange
FROM v_historique_objet h
INNER JOIN (
    SELECT idItem, MAX(dateEchange) as max_date
    FROM v_historique_objet
    GROUP BY idItem
) latest ON h.idItem = latest.idItem AND h.dateEchange = latest.max_date
ORDER BY h.idItem;