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

// Operación POST (Insertar datos)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    // Validar y obtener los datos del cuerpo de la solicitud
    $numeroserie = $data['numeroserie'];
    $marca = $data['marca'];
    $mouse = $data['mouse'];
    $teclado = $data['teclado'];

    // Insertar datos en la tabla laptop
    $sqlInsert = "INSERT INTO laptop (numeroserie, marca, mouse, teclado) VALUES ('$numeroserie', '$marca', '$mouse', '$teclado')";
    $resultInsert = $conn->query($sqlInsert);

    if ($resultInsert) {
        echo json_encode(array('message' => 'Registro insertado con éxito.'));
    } else {
        echo json_encode(array('message' => 'Error al insertar el registro.'));
    }
}

// Operación PUT (Actualizar datos)
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents("php://input"), true);

    // Validar y obtener los datos del cuerpo de la solicitud
    $id = $data['id'];
    $numeroserie = $data['numeroserie'];
    $marca = $data['marca'];
    $mouse = $data['mouse'];
    $teclado = $data['teclado'];

    // Actualizar datos en la tabla laptop
    $sqlUpdate = "UPDATE laptop SET numeroserie='$numeroserie', marca='$marca', mouse='$mouse', teclado='$teclado' WHERE id=$id";
    $resultUpdate = $conn->query($sqlUpdate);

    if ($resultUpdate) {
        echo json_encode(array('message' => 'Registro actualizado con éxito.'));
    } else {
        echo json_encode(array('message' => 'Error al actualizar el registro.'));
    }
}

// Operación DELETE (Eliminar datos)
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);

    // Validar y obtener el ID del cuerpo de la solicitud
    $id = $data['id'];

    // Eliminar datos de la tabla laptop
    $sqlDelete = "DELETE FROM laptop WHERE id=$id";
    $resultDelete = $conn->query($sqlDelete);

    if ($resultDelete) {
        echo json_encode(array('message' => 'Registro eliminado con éxito.'));
    } else {
        echo json_encode(array('message' => 'Error al eliminar el registro.'));
    }
}

// Operación GET (Consulta)
$sql = "SELECT laptop.id, laptop.numeroserie, laptop.marca, laptop.mouse, laptop.teclado, estadolaptop.estado
        FROM laptop
        LEFT JOIN estadolaptop ON laptop.id = estadolaptop.id";

$result = $conn->query($sql);

// Obtener los resultados y convertirlos a un array asociativo
$rows = array();
while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
}

// Cerrar la conexión
$conn->close();

echo json_encode($rows);
?>
