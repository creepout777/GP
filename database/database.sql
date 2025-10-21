-- database.sql
-- Script SQL pour créer les tables 'utilisateurs' et 'taches'
CREATE DATABASE IF NOT EXISTS gestion_taches CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE gestion_taches;

CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE taches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(150) NOT NULL,
    description TEXT,
    priorite ENUM('Basse','Moyenne','Haute') DEFAULT 'Moyenne',
    status ENUM('En attente','En cours','Terminé') DEFAULT 'En attente',
    utilisateurId INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (utilisateurId) REFERENCES utilisateurs(id) ON DELETE CASCADE
);
