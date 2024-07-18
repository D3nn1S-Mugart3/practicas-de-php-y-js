-- Active: 1707247047623@@127.0.0.1@3306
CREATE DATABASE LaboratoryDB;

USE LaboratoryDB; 

CREATE TABLE ComputerInventory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numeroPC INT,
    numeroSerie VARCHAR(6),
    marca VARCHAR(20),
    conMouse BOOLEAN,
    conTeclado BOOLEAN
);

CREATE TABLE ComputerState (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ci_id INT,
    estado VARCHAR(20),
    FOREIGN KEY (ci_id) REFERENCES ComputerInventory(id)
);

INSERT INTO ComputerInventory (numeroPC, numeroSerie, marca, conMouse, conTeclado)
VALUES (1, '123456', 'Apple', true, true);

INSERT INTO ComputerState (ci_id, estado) VALUES (1, 'Disponible');