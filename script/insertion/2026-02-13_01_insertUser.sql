-- Insertion des utilisateurs pour Takalo-Takalo
-- Date: 2026-02-13

USE takalo;

-- Insertion des utilisateurs
-- Mot de passe: tous utilisent 'password123' hashé avec password_hash()
-- INSERT INTO user (username, email, password) VALUES
-- ('alice_martin', 'alice.martin@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
-- ('bob_dupont', 'bob.dupont@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
-- ('claire_rousseau', 'claire.rousseau@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
-- ('david_bernard', 'david.bernard@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
-- ('emma_petit', 'emma.petit@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
-- ('francois_durand', 'francois.durand@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

INSERT INTO user (username, email, password) VALUES
('admin', 'admin@gmail.com', 'password123'),
('alice_martin', 'alice.martin@email.com', 'password123'),
('bob_dupont', 'bob.dupont@email.com', 'password123'),
('claire_rousseau', 'claire.rousseau@email.com', 'password123'),
('david_bernard', 'david.bernard@email.com', 'password123'),
('emma_petit', 'emma.petit@email.com', 'password123'),
('francois_durand', 'francois.durand@email.com', 'password123');

-- Note: Tous les mots de passe sont 'password123'
-- Pour tester la connexion: utilisez n'importe quel username ci-dessus avec 'password123'
