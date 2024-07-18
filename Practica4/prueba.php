<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "laboratoryequipmentdb";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Manejar las operaciones CRUD

// GET - Obtener todos los usuarios
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT * FROM ComputerInventory";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        echo json_encode($rows);
    } else {
        echo json_encode(array());
    }
}

// POST - Agregar un nuevo usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numPC = $_POST['numeroPC'];
    $estado = $_POST['estado'];
    $numSerie = $_POST['numeroSerie'];
    $marca = $_POST['marca'];   
    $mouse = $_POST['conMouse'];
    $teclado = $_POST['conTeclado'];   

    $sql = "INSERT INTO ComputerInventory (numeroPC, estado, numeroSerie, marca, conMouse, conTeclado) VALUES ('$numPC', '$estado', '$numSerie', '$marca', '$mouse', '$teclado')";
    if ($conn->query($sql) === TRUE) {
        echo "Usuario agregado con éxito";
    } else {
        echo "Error al agregar usuario: " . $conn->error;
    }
}

// PUT - Actualizar un usuario existente
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $put_vars);
    $id = $put_vars['id'];
    $numPC = $put_vars['numeroPC'];
    $estado = $put_vars['estado'];
    $numSerie = $put_vars['numeroSerie'];
    $marca = $put_vars['marca'];   
    $mouse = $put_vars['conMouse'];
    $teclado = $put_vars['conTeclado'];  

    $sql = "UPDATE ComputerInventory SET PC='$numPC', estado='$estado', serie='$numSerie', marcaPC='$marca', Mouse='$mouse', Teclado='$teclado' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "Actualizado con éxito";
    } else {
        echo "Error al actualizar: " . $conn->error;
    }
}

// DELETE - Eliminar un usuario
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"), $delete_vars);
    $id = $delete_vars['id'];

    $sql = "DELETE FROM ComputerInventory WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "Eliminado con éxito";
    } else {
        echo "Error al eliminar: " . $conn->error;
    }
}

// Cerrar conexión
$conn->close();
?>