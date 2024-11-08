CREATE DATABASE eguraldia;

USE eguraldia;

CREATE TABLE herria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    izena VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE iragarpena_eguna (
    id INT AUTO_INCREMENT PRIMARY KEY,
    herria_id INT,
    eguna DATE,  /* DATE formatua: YYYY-MM-DD */
    iragarpen_testua VARCHAR(250),
    eguraldia INT,   /* (1:oskarbi, 2:hodei-gutxi, ...) */
    tenperatura_minimoa INT,
    tenperatura_maximoa INT,
    FOREIGN KEY (herria_id) REFERENCES herria(id) ON DELETE CASCADE
);

CREATE TABLE iragarpena_orduko (
    id INT AUTO_INCREMENT PRIMARY KEY,
    iragarpena_eguna_id INT,
    ordua TIME,  /* HH:MM:SS */
    eguraldia INT,   /* (1:oskarbi, 2:hodei-gutxi, ...) */
    tenperatura INT,
    prezipitazioa INT,
    haizea_nondik INT,
    haizea_kmh INT,
    FOREIGN KEY (iragarpena_eguna_id) REFERENCES iragarpena_eguna(id) ON DELETE CASCADE
);
