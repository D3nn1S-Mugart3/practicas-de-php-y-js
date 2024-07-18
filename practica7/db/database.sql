CREATE DATABASE InventoryDB;

USE InventoryDB; 

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

CREATE TABLE Escritorio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50),
    ubicacionX INT,
    ubicacionY INT
);


ALTER TABLE ComputerInventory
ADD COLUMN escritorio_id INT,
ADD FOREIGN KEY (escritorio_id) REFERENCES Escritorios(id);

INSERT INTO ComputerInventory (numeroPC, numeroSerie, marca, conMouse, conTeclado)
VALUES (1, '123456', 'Apple', true, true);

INSERT INTO ComputerState (ci_id, estado) VALUES (1, 'Disponible');