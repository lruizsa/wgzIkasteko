CREATE DATABASE top_filmak;

USE top_filmak;

-- Erabiltzaileen taula
CREATE TABLE erabiltzaileak (
    id INT AUTO_INCREMENT PRIMARY KEY,
    izena VARCHAR(50) NOT NULL
);

-- Filmen taula
CREATE TABLE filmak (
    id INT AUTO_INCREMENT PRIMARY KEY,
    izena VARCHAR(100) NOT NULL,
    isan VARCHAR(8) NOT NULL UNIQUE,
    urtea INT
);

-- Erabiltzailearen filmak taula
CREATE TABLE erabiltzaile_filmak (
    erabiltzaile_id INT,
    film_id INT,
    puntuazioa INT CHECK (puntuazioa BETWEEN 0 AND 5),
    PRIMARY KEY (erabiltzaile_id, film_id),
    FOREIGN KEY (erabiltzaile_id) REFERENCES erabiltzaileak(id),
    FOREIGN KEY (film_id) REFERENCES filmak(id)
);
