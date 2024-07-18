<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laboratory Equipment Management</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
    }

    .equipment-container {
        display: flex;
        flex-wrap: wrap;
    }

    .equipment-button {
        margin: 5px;
        padding: 10px;
        width: 100px;
        height: 45px;
        border: none;
        cursor: pointer;
        border-radius: 5px;
        color: black;
        position: relative;
    }

    .equipment-button:hover {
        opacity: 0.8;
    }

    .popup {
        display: none;
        position: absolute;
        background-color: #f1f1f1;
        width: 130px;
        height: 170px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        z-index: 1;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    table,
    th,
    td {
        border: 1px solid #ddd;
    }

    th,
    td {
        padding: 10px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }

    form {
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 5px;
    }

    input,
    select {
        margin-bottom: 10px;
        padding: 8px;
    }

    button {
        padding: 10px;
        background-color: #4CAF50;
        color: white;
        border: none;
        cursor: pointer;
    }

    button:hover {
        background-color: #45a049;
    }
</style>

<body>
    <h1>Gestión de equipos de laboratorio</h1>
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

    // GET - Obtener todos los equipos
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $sql = "SELECT CI.*, CS.estado 
        FROM ComputerInventory CI 
        LEFT JOIN ComputerState CS ON CI.id = CS.ci_id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $availableCount = 0;
            $outOfServiceCount = 0;

            echo "<div class='equipment-container'>";
            while ($row = $result->fetch_assoc()) {
                $status = $row['estado'];
                $buttonColor = ($status == 'Disponible') ? 'green' : 'gray';

                if ($status == 'Disponible') {
                    $availableCount++;
                } else {
                    $outOfServiceCount++;
                }

                echo "<button class='equipment-button' style='background-color: $buttonColor;' onmouseover='showPopup(this)' onmouseout='hidePopup(this)' onclick='changeStatus(" . $row['id'] . ", \"" . $status . "\")' data-info='" . json_encode($row) . "'>";
                // echo $row['numeroPC'];
                echo "<div class='popup'>";
                echo "<p><strong>Número PC:</strong> " . (isset($row['numeroPC']) ? $row['numeroPC'] : '') . "</p>";
                echo "<p><strong>Número de Serie:</strong> " . (isset($row['numeroSerie']) ? $row['numeroSerie'] : '') . "</p>";
                echo "<p><strong> Estado:</strong>" . (isset($row['estado']) ? $row['estado'] : '') . "</p>";
                echo "<p><strong>Marca:</strong> " . (isset($row['marca']) ? $row['marca'] : '') . "</p>";
                echo "<p><strong>Con Mouse:</strong> " . (isset($row['conMouse']) ? ($row['conMouse'] ? 'Sí' : 'No') : '') . "</p>";
                echo "<p><strong>Con Teclado:</strong> " . (isset($row['conTeclado']) ? ($row['conTeclado'] ? 'Sí' : 'No') : '') . "</p>";
                echo "</div>";
                echo "</button>";
            }
            echo "</div>";

            echo "<div class='info-container'>";
            echo "<p>Total de equipos: " . ($availableCount + $outOfServiceCount) . "</p>";
            echo "<p>Equipos disponibles: $availableCount</p>";
            echo "<p>Equipos fuera de servicio: $outOfServiceCount</p>";
            echo "</div>";
        } else {
            echo "<p>No hay equipos disponibles</p>";
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['newStatus'])) {
        // POST - Actualizar estado de una PC
        $id = $_POST['id'];
        $newStatus = $_POST['newStatus'];

        $sql = "UPDATE ComputerState SET estado = '$newStatus' WHERE ci_id = $id";

        if ($conn->query($sql) === TRUE) {
            echo "Estado actualizado correctamente";
        } else {
            echo "Error al actualizar el estado: " . $conn->error;
        }
    }

    // Cerrar conexión
    $conn->close();
    ?>

    <script>
        function showPopup(button) {
            const popup = button.querySelector('.popup');
            popup.style.display = 'block';
        }

        function hidePopup(button) {
            const popup = button.querySelector('.popup');
            popup.style.display = 'none';
        }

        function changeStatus(id, status) {
            var newStatus = (status === 'Disponible') ? 'Fuera de servicio' : 'Disponible';
            var button = document.querySelector("[data-info='{\"id\":" + id + "}']");
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    if (status === 'Disponible') {
                        button.style.backgroundColor = 'gray';
                    } else {
                        button.style.backgroundColor = 'green';
                    }
                    alert(this.responseText);
                }
            };
            xhttp.open("POST", "", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("id=" + id + "&newStatus=" + newStatus);
        }
    </script>
</body>

</html>