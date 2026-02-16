-- Insertion des objets pour Takalo-Takalo
-- Date: 2026-02-13

USE takalo;

-- Insertion des objets
-- Catégories: 1=Électronique, 2=Livres, 3=Vêtements, 4=Meubles, 5=Sports et Loisirs, 6=Jouets, 7=Jardinage, 8=Cuisine

INSERT INTO item (name, description, idUser, idcategorie, price) VALUES
-- Objects de alice_martin (idUser: 1)
('iPhone 13 Pro', 'Smartphone Apple en excellent état, 256GB, couleur bleu pacifique. Avec boîte et accessoires d\'origine.', 1, 1, 850000.00),
('Vélo électrique', 'Vélo électrique Decathlon, autonomie 50km, très peu utilisé, batterie neuve', 1, 5, 450000.00),
('Collection Jules Verne', 'Ensemble de 10 livres de Jules Verne, édition reliée, état comme neuf', 1, 2, 75000.00),
('Robe de soirée', 'Robe longue noire, taille 38, portée une seule fois pour un mariage', 1, 3, 120000.00),

-- Objects de bob_dupont (idUser: 2)
('MacBook Air M2', 'Ordinateur portable Apple 2023, 13 pouces, 8GB RAM, 512GB SSD, garantie valide', 2, 1, 1200000.00),
('Canapé 3 places', 'Canapé en tissu gris clair, très confortable, excellent état, style moderne', 2, 4, 380000.00),
('Set de golf complet', 'Sac de golf avec 14 clubs Callaway, très bon état, idéal pour débutant', 2, 5, 320000.00),
('Encyclopédie Larousse', '12 volumes de l\'encyclopédie Larousse édition 2024, état neuf', 2, 2, 180000.00),

-- Objects de claire_rousseau (idUser: 3)
('Samsung Galaxy S23', 'Smartphone Samsung, 128GB, couleur crème, avec coque et verre trempé', 3, 1, 680000.00),
('Table à manger', 'Table en chêne massif pour 6 personnes, style scandinave, excellent état', 3, 4, 290000.00),
('Manteau d\'hiver femme', 'Manteau long beige, taille M, marque Zara, porté une saison', 3, 3, 95000.00),
('Robot cuisine Thermomix', 'Robot de cuisine multifonction, modèle TM6, utilisé 2 mois, comme neuf', 3, 8, 1100000.00),
('Tapis de yoga premium', 'Tapis de yoga épais avec sac de transport, parfait état', 3, 5, 35000.00),

-- Objects de david_bernard (idUser: 4)
('PlayStation 5', 'Console PS5 avec lecteur disque, 2 manettes DualSense, 5 jeux inclus', 4, 1, 520000.00),
('Bibliothèque bois', 'Grande bibliothèque en bois massif, 5 étagères, hauteur 2m', 4, 4, 180000.00),
('Coffret manga One Piece', 'Tomes 1 à 50 de One Piece en parfait état, édition française', 4, 2, 250000.00),
('Tondeuse à gazon', 'Tondeuse thermique Honda, largeur de coupe 46cm, démarrage facile', 4, 7, 220000.00),

-- Objects de emma_petit (idUser: 5)
('iPad Pro 11"', 'Tablette Apple avec Apple Pencil 2, 128GB, Wi-Fi, garantie Apple Care+', 5, 1, 780000.00),
('Commode vintage', 'Commode ancienne restaurée, 4 tiroirs, style vintage industriel', 5, 4, 165000.00),
('Appareil photo Canon', 'Canon EOS R6 avec objectif 24-105mm, sac et accessoires', 5, 1, 1450000.00),
('Set de valises', 'Ensemble de 3 valises rigides, couleur rose gold, jamais utilisées', 5, 3, 145000.00),
('Plantes d\'intérieur', 'Lot de 5 grandes plantes d\'intérieur (Monstera, Ficus, etc.) avec cache-pots', 5, 7, 85000.00),

-- Objects de francois_durand (idUser: 6)
('Nintendo Switch OLED', 'Console Nintendo Switch modèle OLED, avec 3 jeux et housse de transport', 6, 1, 340000.00),
('Batterie électronique', 'Batterie électronique Alesis, casque inclus, parfait pour débutant', 6, 5, 280000.00),
('Collection BD Tintin', 'Collection complète des aventures de Tintin, 24 albums, état impeccable', 6, 2, 195000.00),
('Costume homme', 'Costume 3 pièces bleu marine, taille 50, marque Hugo Boss, très peu porté', 6, 3, 240000.00),
('Machine à café expresso', 'Machine Nespresso Vertuo avec mousseur de lait, utilisée 6 mois', 6, 8, 125000.00);

-- Insertion des images pour quelques objets
INSERT INTO imageItem (idItem, imageURL) VALUES
(1, '/assets/images/items/iphone13pro.jpg'),
(2, '/assets/images/items/velo_electrique.jpg'),
(5, '/assets/images/items/macbook_air.jpg'),
(9, '/assets/images/items/samsung_s23.jpg'),
(13, '/assets/images/items/ps5.jpg'),
(17, '/assets/images/items/ipad_pro.jpg'),
(21, '/assets/images/items/switch_oled.jpg');

-- Note: Les images sont des placeholders. Remplacez par de vraies URLs d'images si nécessaire.
