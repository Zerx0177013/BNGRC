DROP DATABASE IF EXISTS takalo;

CREATE DATABASE takalo;

USE takalo;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE user (
    idUser INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE OR REPLACE TABLE item (
    idItem INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    idUser INT,
    idcategorie INT,
    price NUMERIC(10, 2) DEFAULT NULL,
    FOREIGN KEY (idUser) REFERENCES user (idUser),
    FOREIGN KEY (idcategorie) REFERENCES categorie (idcategorie)
);

CREATE OR REPLACE TABLE categorie (
    idcategorie INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL
);

CREATE OR REPLACE TABLE demande (
    idDemande INT AUTO_INCREMENT PRIMARY KEY,
    idDemandeur INT,
    idReceveur INT,
    idObjetOffert INT,
    idObjetDemande INT,
    idDemandeStatus INT,
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    statusAt DATETIME DEFAULT NULL,
    FOREIGN KEY (idDemandeur) REFERENCES user (idUser),
    FOREIGN KEY (idReceveur) REFERENCES user (idUser),
    FOREIGN KEY (idObjetOffert) REFERENCES item (idItem),
    FOREIGN KEY (idObjetDemande) REFERENCES item (idItem),
    FOREIGN KEY (idDemandeStatus) REFERENCES demandeStatus (idStatus)
);

CREATE OR REPLACE TABLE historiqueEchange (
    idEchange INT AUTO_INCREMENT PRIMARY KEY,
    idDemande INT,
    idDemandeur INT,
    idOffreur INT,
    idObjetOffert INT,
    idObjetDemande INT,
    dateEchange DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idDemande) REFERENCES demande (idDemande),
    FOREIGN KEY (idDemandeur) REFERENCES user (idUser),
    FOREIGN KEY (idOffreur) REFERENCES user (idUser),
    FOREIGN KEY (idObjetOffert) REFERENCES item (idItem),
    FOREIGN KEY (idObjetDemande) REFERENCES item (idItem)
);

CREATE TABLE imageItem (
    idImage INT AUTO_INCREMENT PRIMARY KEY,
    idItem INT,
    imageURL VARCHAR(255) NOT NULL,
    FOREIGN KEY (idItem) REFERENCES item (idItem)
);

CREATE TABLE demandeStatus(
    idStatus INT AUTO_INCREMENT PRIMARY KEY,
    statusName VARCHAR(255) NOT NULL
);

CREATE TABLE admin (
    idAdmin INT AUTO_INCREMENT PRIMARY KEY,
    idUser INT,
    FOREIGN KEY (idUser) REFERENCES user (idUser)
);
