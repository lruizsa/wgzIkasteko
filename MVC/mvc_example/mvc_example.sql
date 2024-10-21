CREATE DATABASE mvc_example;
USE mvc_example;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL
);

INSERT INTO users (name, email) VALUES ('Jon', 'jon@example.com'), ('Ane', 'ane@example.com');