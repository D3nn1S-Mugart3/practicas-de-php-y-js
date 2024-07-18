-- Active: 1707003384862@@127.0.0.1@3306

CREATE DATABASE LaboratoryEquipmentDB;

USE LaboratoryEquipmentDB

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

INSERT INTO ComputerState (ci_id, estado) VALUES (1, 'Disponible');
DROP TABLE ComputerState;
INSERT INTO ComputerInventory (numeroPC, numeroSerie, marca, conMouse, conTeclado)
VALUES (1, '123456', 'Apple', true, true);

INSERT INTO ComputerInventory (numeroPC, numeroSerie, marca, conMouse, conTeclado)
VALUES (2, '789012', 'Apple', false, true);


SELECT * FROM ComputerInventory;

SELECT * FROM ComputerState;