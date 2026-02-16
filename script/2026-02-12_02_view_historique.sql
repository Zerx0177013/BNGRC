-- Vue simple pour l'historique d'appartenance d'un objet
CREATE OR REPLACE VIEW v_historique_objet AS
SELECT 
    h.idObjetOffert AS idItem,
    item.name AS nom_objet,
    h.dateEchange,
    u_from.username AS ancien_proprietaire,
    u_to.username AS nouveau_proprietaire
FROM historiqueEchange h
JOIN user u_from ON h.idDemandeur = u_from.idUser
JOIN user u_to ON h.idOffreur = u_to.idUser
JOIN item ON h.idObjetOffert = item.idItem
UNION ALL
SELECT 
    h.idObjetDemande AS idItem,
    item.name AS nom_objet,
    h.dateEchange,
    u_from.username AS ancien_proprietaire,
    u_to.username AS nouveau_proprietaire
FROM historiqueEchange h
JOIN user u_from ON h.idOffreur = u_from.idUser
JOIN user u_to ON h.idDemandeur = u_to.idUser
JOIN item ON h.idObjetDemande = item.idItem
ORDER BY dateEchange ASC;
