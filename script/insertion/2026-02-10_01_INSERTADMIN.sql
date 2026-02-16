USE takalo;
-- Ajout d'administrateurs
INSERT INTO admin(`idUser`) VALUES (1);
INSERT INTO admin(`idUser`) VALUES (2);
INSERT INTO admin(`idUser`) VALUES (3);

-- Historique des actions d'administration
INSERT INTO historique_admin(`idAdmin`, `action`, `date_action`) VALUES
  (1, 'Création de la catégorie "Informatique"', '2026-02-10 09:00:00'),
  (2, 'Suppression de l\'utilisateur "user_test"', '2026-02-10 10:15:00'),
  (1, 'Ajout d\'un nouvel objet "Ordinateur HP"', '2026-02-10 11:30:00'),
  (3, 'Modification du profil admin', '2026-02-10 13:00:00'),
  (2, 'Validation d\'une demande d\'échange', '2026-02-10 14:45:00'),
  (1, 'Blocage d\'un utilisateur pour fraude', '2026-02-10 16:20:00');