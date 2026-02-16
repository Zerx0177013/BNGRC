CREATE OR REPLACE view v_details_demandes AS
SELECT 
                d.*,
                u_demandeur.username AS demandeur_username,
                u_receveur.username AS receveur_username,
                item_offert.name AS objet_offert_name,
                item_demande.name AS objet_demande_name
            FROM demande d
            INNER JOIN user u_demandeur ON d.idDemandeur = u_demandeur.idUser
            INNER JOIN user u_receveur ON d.idReceveur = u_receveur.idUser
            INNER JOIN item item_offert ON d.idObjetOffert = item_offert.idItem
            INNER JOIN item item_demande ON d.idObjetDemande = item_demande.idItem
            ORDER BY d.createdAt DESC

