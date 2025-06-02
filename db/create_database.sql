CREATE DATABASE IF NOT EXISTS recettes;
USE recettes;
-- Création de la table users
CREATE TABLE IF NOT EXISTS users (
    id_user INT(11) NOT NULL AUTO_INCREMENT,
    nom VARCHAR(128) NOT NULL,
    prenom VARCHAR(128) NOT NULL,
    age INT NOT NULL,
    sexe ENUM('Homme', 'Femme', 'Autre') NOT NULL,
    pays VARCHAR(100) NOT NULL,
    mail VARCHAR(255) NOT NULL UNIQUE,
    domaine VARCHAR(100),
    -- NULL pour visiteurs
    experience INT,
    -- NULL pour visiteurs
    abonnement VARCHAR(100),
    -- NULL pour visiteurs
    password CHAR(60) NOT NULL,
    -- mot de passe hashé
    type_user INT NOT NULL,
    -- 0 = visiteur, 1 = chef cuisinier
    PRIMARY KEY (id_user)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
CREATE TABLE IF NOT EXISTS recipes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chef_id VARCHAR(255) NOT NULL,
    titre VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
CREATE TABLE IF NOT EXISTS votes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    recipe_id INT,
    type ENUM('like', 'dislike'),
    visitor_id VARCHAR(255),
    date_vote DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;