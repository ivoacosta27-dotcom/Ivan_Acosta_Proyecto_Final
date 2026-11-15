CREATE DATABASE IF NOT EXISTS maoui_fragance CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE maoui_fragance;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    dni VARCHAR(10) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    rol ENUM('usuario', 'admin') NOT NULL DEFAULT 'usuario',
    creado_en DATETIME DEFAULT CURRENT_TIMESTAMP,
    archivado_en DATETIME NULL
);