<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Conexión a la base de datos
$mysqli = new mysqli("localhost", "root", "", "laboratoryequipmentdb");

if ($mysqli->connect_errno) {
    die("Error al conectarse a MySQL: " . $mysqli->connect_error);
}

// Leer método de la solicitud
$method = $_SERVER['REQUEST_METHOD'];

// Obtener la ruta de la solicitud
$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));

// Obtener datos de la solicitud
$input = json_decode(file_get_contents('php://input'), true);

// Operaciones CRUD para ComputerInventory
switch ($request[0]) {
    case 'ComputerInventory':
        switch ($method) {
            case 'GET':
                // Leer inventario de computadoras
                $result = $mysqli->query("SELECT * FROM ComputerInventory");
                $computadoras = [];
                while ($row = $result->fetch_assoc()) {
                    $computadoras[] = $row;
                }
                echo json_encode($computadoras);
                break;
            case 'POST':
                // Crear una nueva entrada en el inventario de computadoras
                $numeroPC = $input['numeroPC'];
                $numeroSerie = $input['numeroSerie'];
                $marca = $input['marca'];
                $conMouse = $input['conMouse'];
                $conTeclado = $input['conTeclado'];
                $mysqli->query("INSERT INTO ComputerInventory (numeroPC, numeroSerie, marca, conMouse, conTeclado) 
                                VALUES ('$numeroPC', '$numeroSerie', '$marca', '$conMouse', '$conTeclado')");
                echo json_encode(['message' => 'Entrada de inventario creada con éxito']);
                break;
            case 'PUT':
                // Actualizar una entrada en el inventario de computadoras
                $id = $request[1];
                $numeroPC = $input['numeroPC'];
                $numeroSerie = $input['numeroSerie'];
                $marca = $input['marca'];
                $conMouse = $input['conMouse'];
                $conTeclado = $input['conTeclado'];
                $mysqli->query("UPDATE ComputerInventory SET numeroPC='$numeroPC', numeroSerie='$numeroSerie', marca='$marca', conMouse='$conMouse', conTeclado='$conTeclado' WHERE ID=$id");
                echo json_encode(['message' => 'Entrada de inventario actualizada con éxito']);
                break;
            case 'DELETE':
                // Eliminar una entrada en el inventario de computadoras
                $id = $request[1];
                $mysqli->query("DELETE FROM ComputerInventory WHERE ID=$id");
                echo json_encode(['message' => 'Entrada de inventario eliminada con éxito']);
                break;
        }
        break;
    
    // Operaciones CRUD para ComputerState
    case 'ComputerState':
        switch ($method) {
            case 'GET':
                // Leer estado de computadoras
                $result = $mysqli->query("SELECT * FROM ComputerState");
                $estados = [];
                while ($row = $result->fetch_assoc()) {
                    $estados[] = $row;
                }
                echo json_encode($estados);
                break;
            case 'POST':
                // Crear un nuevo estado de computadora
                $ci_id = $input['ci_id'];
                $estado = $input['estado'];
                $mysqli->query("INSERT INTO ComputerState (ci_id, estado) VALUES ('$ci_id', '$estado')");
                echo json_encode(['message' => 'Estado de computadora creado con éxito']);
                break;
            case 'PUT':
                // Actualizar un estado de computadora
                $id = $request[1];
                $estado = $input['estado'];
                $mysqli->query("UPDATE ComputerState SET estado='$estado' WHERE ID=$id");
                echo json_encode(['message' => 'Estado de computadora actualizado con éxito']);
                break;
            case 'DELETE':
                // Eliminar un estado de computadora
                $id = $request[1];
                $mysqli->query("DELETE FROM ComputerState WHERE ID=$id");
                echo json_encode(['message' => 'Estado de computadora eliminado con éxito']);
                break;
        }
        break;
}

// Cerrar conexión
$mysqli->close();
?>
