<?php
// Configuración de la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "LaboratorioDB";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Obtener información de las computadoras y su estado
    $sql = "SELECT c.*, e.nombre AS estado_nombre FROM Computadoras c 
            INNER JOIN Estado e ON c.estado_id = e.id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $computadoras = array();
        while ($row = $result->fetch_assoc()) {
            $computadoras[] = $row;
        }
        // Convertir los resultados a formato JSON y enviarlos como respuesta
        echo json_encode($computadoras);
    } else {
        echo "0 resultados";
    }
}

// Manejar solicitud POST para actualizar el estado de una computadora
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recuperar los datos enviados por la solicitud POST
    $id_computadora = $_POST['id_computadora'];
    $nuevo_estado_id = $_POST['nuevo_estado_id'];

    // Actualizar el estado de la computadora en la base de datos
    $sql = "UPDATE Computadoras SET estado_id = $nuevo_estado_id WHERE id = $id_computadora";
    if ($conn->query($sql) === TRUE) {
        echo "Estado de la computadora actualizado correctamente";
    } else {
        echo "Error al actualizar el estado de la computadora: " . $conn->error;
    }
}

// Manejar solicitud PUT para actualizar información de una computadora
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Recuperar los datos enviados por la solicitud PUT
    $put_vars = file_get_contents("php://input");
    parse_str($put_vars, $put_data);
    
    // Verificar si se recibieron todos los datos esperados
    if (isset($put_data['id_computadora']) && isset($put_data['nuevo_numero_pc']) && isset($put_data['nuevo_numero_serie']) && isset($put_data['nueva_marca'])) {
        $id_computadora = $put_data['id_computadora'];
        $nuevo_numero_pc = $put_data['nuevo_numero_pc'];
        $nuevo_numero_serie = $put_data['nuevo_numero_serie'];
        $nueva_marca = $put_data['nueva_marca'];

        // Actualizar la información de la computadora en la base de datos
        $sql = "UPDATE Computadoras SET numero_pc = '$nuevo_numero_pc', numero_serie = '$nuevo_numero_serie', marca = '$nueva_marca' WHERE id = $id_computadora";
        if ($conn->query($sql) === TRUE) {
            echo "Información de la computadora actualizada correctamente";
        } else {
            echo "Error al actualizar la información de la computadora: " . $conn->error;
        }
    } else {
        echo "No se proporcionaron todos los datos necesarios para actualizar la computadora";
    }
}


// Manejar solicitud DELETE para eliminar una computadora
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Recuperar los datos enviados por la solicitud DELETE (se puede usar file_get_contents("php://input") para DELETE)
    parse_str(file_get_contents("php://input"), $delete_vars);
    $id_computadora = $delete_vars['id_computadora'];
    // Eliminar la computadora de la base de datos
    $sql = "DELETE FROM Computadoras WHERE id = $id_computadora";
    if ($conn->query($sql) === TRUE) {
        echo "Computadora eliminada correctamente";
    } else {
        echo "Error al eliminar la computadora: " . $conn->error;
    }
}

?>
