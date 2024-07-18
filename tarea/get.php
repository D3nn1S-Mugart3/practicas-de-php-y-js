<?php
header("Access-Control-Allow-Headers: Content-Type");
// Permitir solicitudes desde cualquier origen
header("Access-Control-Allow-Origin: *");
// Permitir solicitudes con los métodos GET, POST, PUT, y OPTIONS
header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS");
// Permitir que las solicitudes incluyan cookies y encabezados HTTP de autenticación
header("Access-Control-Allow-Credentials: true");
// Establecer el tipo de contenido para las solicitudes con origen cruzado
header("Content-Type: application/json");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pc";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Operación POST (Insertar estado)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    // Validar y obtener los datos del cuerpo de la solicitud
    $estado = $data['estado'];

    // Insertar estado en la tabla estadolaptop
    $sqlInsert = "INSERT INTO estadolaptop (estado) VALUES ('$estado')";
    $resultInsert = $conn->query($sqlInsert);

    if ($resultInsert) {
        echo json_encode(array('message' => 'Estado insertado con éxito.'));
    } else {
        echo json_encode(array('message' => 'Error al insertar el estado.'));
    }
}

// Operación GET (Consulta de estados)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT id, estado FROM estadolaptop";

    $result = $conn->query($sql);

    // Obtener los resultados y convertirlos a un array asociativo
    $rows = array();
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }

    // Devolver los resultados como JSON
    header('Content-Type: application/json');
    echo json_encode($rows);
}

// Operación PUT (Actualizar estado)
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents("php://input"), true);

    // Validar y obtener los datos del cuerpo de la solicitud
    $id = $data['id'];
    $estado = $data['estado'];

    // Actualizar estado en la tabla estadolaptop
    $sqlUpdate = "UPDATE estadolaptop SET estado='$estado' WHERE id=$id";
    $resultUpdate = $conn->query($sqlUpdate);

    if ($resultUpdate) {
        echo json_encode(array('message' => 'Estado actualizado con éxito.'));
    } else {
        echo json_encode(array('message' => 'Error al actualizar el estado.'));
    }
}

// Operación DELETE (Eliminar estado)
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);

    // Validar y obtener el ID del cuerpo de la solicitud
    $id = $data['id'];

    // Eliminar estado de la tabla estadolaptop
    $sqlDelete = "DELETE FROM estadolaptop WHERE id=$id";
    $resultDelete = $conn->query($sqlDelete);

    if ($resultDelete) {
        echo json_encode(array('message' => 'Estado eliminado con éxito.'));
    } else {
        echo json_encode(array('message' => 'Error al eliminar el estado.'));
    }
}

// Cerrar la conexión
$conn->close();
?>