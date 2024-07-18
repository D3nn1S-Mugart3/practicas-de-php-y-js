<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laboratory Equipment Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
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

        /* Estilos para la ventana emergente */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: -2% auto;
            padding: 25px;
            border-radius: 25px;
            border: 1px solid #888;
            width: 30%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .text-h2 {
            text-align: center;
        }
    </style>
</head>

<body>

    <h1>Gestión de equipos de laboratorio</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Numero PC</th>
                <th>Estado</th>
                <th>Numero de Serie</th>
                <th>Marca</th>
                <th>Con Mouse</th>
                <th>Con Teclado</th>
            </tr>
        </thead>
        <tbody id="equipmentTableBody">
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

            // GET - Obtener todos los usuarios
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $sql = "SELECT * FROM ComputerInventory";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$row['id']}</td>";
                        echo "<td>{$row['numeroPC']}</td>";
                        echo "<td>{$row['estado']}</td>";
                        echo "<td>{$row['numeroSerie']}</td>";
                        echo "<td>{$row['marca']}</td>";
                        echo "<td>{$row['conMouse']}</td>";
                        echo "<td>{$row['conTeclado']}</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No hay equipos disponibles</td></tr>";
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
        </tbody>
    </table>

    <button onclick="openAddModal()">Añadir</button>
    <button type="button" onclick="updateEquipment()">Actualizar Equipo</button>
    <button type="button" onclick="deleteEquipment()">Eliminar Equipo</button>

    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeAddModal()">&times; </span>
            <h2 class="text-h2">Añadir equipo</h2>
            <form id="addForm" method="POST" action="">
                <label for="numPC">Numero PC:</label>
                <input type="text" id="numPC" name="numeroPC" required>

                <label for="estado">Estado:</label>
                <select id="estado" name="estado" required>
                    <option value="Disponible">Disponible</option>
                    <option value="Fuera de servicio">Fuera de servicio</option>
                </select>

                <label for="numSerie">Numero de Serie:</label>
                <input type="text" id="numSerie" name="numeroSerie" required>

                <label for="marca">Marca:</label>
                <input type="text" id="marca" name="marca" required>

                <label for="conMouse">Con Mouse:</label>
                <select id="conMouse" name="conMouse" required>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>

                <label for="conTeclado">Con Teclado:</label>
                <select id="conTeclado" name="conTeclado" required>
                    <option value="1">Sí</option>
                    <option value="0">No</option>
                </select>
                <br>

                <button type="submit">Añadir Equipo</button>
            </form>
        </div>
    </div>

    <script>
        // JavaScript para las funciones de Actualizar y Eliminar
        function updateEquipment() {
            alert("Implementa la lógica de actualización aquí");
        }

        function deleteEquipment() {
            alert("Implementa la lógica de eliminación aquí");
        }

        function openAddModal() {
            document.getElementById('addModal').style.display = 'block';
        }

        function closeAddModal() {
            document.getElementById('addModal').style.display = 'none';
        }
    </script>
</body>

</html>