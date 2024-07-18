<?php
// Establecer cabeceras para permitir CORS
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
// Permitir solo solicitudes desde el origen donde se ejecuta la aplicación React
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Detener el procesamiento si es una solicitud OPTIONS
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit;
}
// Establecer la conexión a la base de datos
$pdo = new PDO("mysql:host=localhost;dbname=inventorydb", "root", "");

// Función para manejar las solicitudes GET de ComputerInventory
function handleGetRequest($pdo, $request) {
    if (isset($request[1])) {
        // Si se proporciona un ID en la solicitud, buscar por ID
        $stmt = $pdo->prepare("SELECT * FROM ComputerInventory WHERE id = ?");
        $stmt->execute([$request[1]]);
        $computadora = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($computadora) {
            echo json_encode($computadora);
        } else {
            echo json_encode(['error' => 'No se encontró ninguna computadora con el ID proporcionado']);
        }
    } else {
        // Si no se proporciona ningún ID, obtener todas las computadoras
        $stmt = $pdo->prepare("SELECT * FROM ComputerInventory");
        $stmt->execute();
        $computadoras = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($computadoras);
    }
}

// Función para manejar las solicitudes POST de ComputerInventory
function handlePostRequest($pdo, $input) {
    $stmt = $pdo->prepare("INSERT INTO ComputerInventory (numeroPC, numeroSerie, marca, conMouse, conTeclado) VALUES (:numeroPC, :numeroSerie, :marca, :conMouse, :conTeclado)");
    $stmt->execute($input);
    echo json_encode(['message' => 'Entrada de inventario creada con éxito']);
}

// Función para manejar las solicitudes PUT de ComputerInventory
function handlePutRequest($pdo, $request, $input) {
    $data = array_merge($input, ['id' => $request[1]]); // Agregar el ID al array de datos
    $stmt = $pdo->prepare("UPDATE ComputerInventory SET numeroPC = :numeroPC, numeroSerie = :numeroSerie, marca = :marca, conMouse = :conMouse, conTeclado = :conTeclado WHERE id = :id");
    $stmt->execute($data);
    echo json_encode(['message' => 'Entrada de inventario actualizada con éxito']);
}

// Función para manejar las solicitudes DELETE de ComputerInventory
function handleDeleteRequest($pdo, $request) {
    $stmt = $pdo->prepare("DELETE FROM ComputerInventory WHERE id = ?");
    $stmt->execute([$request[1]]);
    echo json_encode(['message' => 'Entrada de inventario eliminada con éxito']);
}

// Función para manejar las solicitudes GET de ComputerState
function handleGetComputerState($pdo, $request) {
    if (isset($request[1])) {
        // Si se proporciona un ID en la solicitud, buscar por ID
        $stmt = $pdo->prepare("SELECT * FROM ComputerState WHERE ID = ?");
        $stmt->execute([$request[1]]);
        $estados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($estados);
    } else {
        // Si no se proporciona ningún ID, obtener todos los estados de computadoras
        $stmt = $pdo->prepare("SELECT * FROM ComputerState");
        $stmt->execute();
        $estados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($estados);
    }
}

// Función para manejar las solicitudes POST de ComputerState
function handlePostComputerState($pdo, $input) {
    $ci_id = $input['ci_id'];
    $estado = $input['estado'];
    $stmt = $pdo->prepare("INSERT INTO ComputerState (ci_id, estado) VALUES (?, ?)");
    $stmt->execute([$ci_id, $estado]);
    echo json_encode(['message' => 'Estado de computadora creado con éxito']);
}

// Función para manejar las solicitudes PUT de ComputerState
function handlePutComputerState($pdo, $request, $input) {
    $id = $request[1];
    $estado = $input['estado'];
    $stmt = $pdo->prepare("UPDATE ComputerState SET estado = ? WHERE ID = ?");
    $stmt->execute([$estado, $id]);
    echo json_encode(['message' => 'Estado de computadora actualizado con éxito']);
}

// Función para manejar las solicitudes DELETE de ComputerState
function handleDeleteComputerState($pdo, $request) {
    $id = $request[1];
    $stmt = $pdo->prepare("DELETE FROM ComputerState WHERE ID = ?");
    $stmt->execute([$id]);
    echo json_encode(['message' => 'Estado de computadora eliminado con éxito']);
}

//* Escritorio

// Función para manejar las solicitudes GET de ComputerInventory
function handleGetEscritorio($pdo, $request) {
    if (isset($request[1])) {
        // Si se proporciona un ID en la solicitud, buscar por ID
        $stmt = $pdo->prepare("SELECT * FROM Escritorio WHERE id = ?");
        $stmt->execute([$request[1]]);
        $escritorio = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($escritorio) {
            echo json_encode($escritorio);
        } else {
            echo json_encode(['error' => 'No se encontró ningun escritorio con el ID proporcionado']);
        }
    } else {
        // Si no se proporciona ningún ID, obtener todas las computadoras
        $stmt = $pdo->prepare("SELECT * FROM Escritorio");
        $stmt->execute();
        $escritorio = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($escritorio);
    }
}

// Función para manejar las solicitudes POST de Escritorio
function handlePostEscritorio($pdo, $input) {
    $stmt = $pdo->prepare("INSERT INTO Escritorio (nombre, ubicacionX, ubicacionY) VALUES (:nombre, :ubicacionX, :ubicacionY)");
    $stmt->execute($input);
    echo json_encode(['message' => 'Escritorio creado con éxito']);
}

// Función para manejar las solicitudes PUT de Escritorio
function handlePutEscritorio($pdo, $request, $input) {
    $data = array_merge($input, ['id' => $request[1]]); // Agregar el ID al array de datos
    $stmt = $pdo->prepare("UPDATE Escritorio SET nombre = :nombre, ubicacionX = :ubicacionX, ubicacionY = :ubicacionY WHERE id = :id");
    $stmt->execute($data);
    echo json_encode(['message' => 'Escritorio actualizado con éxito']);
}

// Función para manejar las solicitudes DELETE de Escritorio
function handleDeleteEscritorio($pdo, $request) {
    $stmt = $pdo->prepare("DELETE FROM Escritorio WHERE id = ?");
    $stmt->execute([$request[1]]);
    echo json_encode(['message' => 'Se ha eliminado el escritorio con éxito']);
}

// Leer el método de la solicitud
$method = $_SERVER['REQUEST_METHOD'];

// Obtener la ruta de la solicitud
$request = explode('/', trim($_SERVER['PATH_INFO'], '/'));

// Obtener los datos de la solicitud
$input = json_decode(file_get_contents('php://input'), true);

// Realizar las operaciones CRUD para ComputerInventory
if ($request[0] === 'ComputerInventory') {
    switch ($method) {
        case 'GET':
            handleGetRequest($pdo, $request);
            break;
        case 'POST':
            handlePostRequest($pdo, $input);
            break;
        case 'PUT':
            handlePutRequest($pdo, $request, $input);
            break;
        case 'DELETE':
            handleDeleteRequest($pdo, $request);
            break;
        default:
            http_response_code(405); // Método no permitido
            echo json_encode(['error' => 'Método no permitido']);
            break;
    }
} elseif ($request[0] === 'ComputerState') {
    // Realizar las operaciones CRUD para ComputerState
    switch ($method) {
        case 'GET':
            handleGetComputerState($pdo, $request);
            break;
        case 'POST':
            handlePostComputerState($pdo, $input);
            break;
        case 'PUT':
            handlePutComputerState($pdo, $request, $input);
            break;
        case 'DELETE':
            handleDeleteComputerState($pdo, $request);
            break;
        default:
            http_response_code(405); // Método no permitido
            echo json_encode(['error' => 'Método no permitido']);
            break;
    }
} elseif ($request[0] === 'Escritorio') {
    // Operaciones CRUD para Escritorio
    switch ($method) {
        case 'GET':
            handleGetEscritorio($pdo, $request);
            break;
        case 'POST':
            handlePostEscritorio($pdo, $input);
            break;
        case 'PUT':
            handlePutEscritorio($pdo, $request, $input);
            break;
        case 'DELETE':
            handleDeleteEscritorio($pdo, $request);
            break;
        default:
            http_response_code(405); // Método no permitido
            echo json_encode(['error' => 'Método no permitido']);
            break;
    }
} 
else {
    // Ruta no válida
    http_response_code(404);
    echo json_encode(['error' => 'Ruta no encontrada']);
}

// Cerrar la conexión a la base de datos
$pdo = null;

?>