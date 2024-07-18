<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilo.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        h1 {
            text-aling: center;
            color: #333;
        }

        form {
            max-width: 400px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .color-text form {
            color: #000;
        }

        input[type="number"],
        input[type="text"] {
            color: #000000;
            width: 100%;
            padding: 10px;
            margin-botton: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"],
        button{
            background-color: #4caf50;
            color: #fff;
            padding: 10px 15px;
            border-radius: 4px; 
            cursor: pointer;
        }

        input[type="submit"]:hover,
        button:hover {
            background-color: #45a049;
        }
    </style> 
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark">
        <div class="container-fluid">
          <a class="navbar-brand" href="#">
            Pagina de Maestros
          </a>
          <ul class="nav justify-content-end">
          <li class="nav-item">
                <a class="nav-link" href="index.html">Inicio</a>
              </li>
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="RegistroAlumnos.php">Registro Alumnos</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="RegistroMaestros.php">Registro Maestros</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="Mi_escuela.html">Mi Escuela</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="Login.html">Login</a>
            </li>
          </ul>
        </div>
      </nav>
    <div class="container mt-4 color-text">
        <form action="RegistroMaestros.php" method="post">
            <input type="number" name="NumeroMaestros" placeholder="NO.Maestros" class="form control">
            <div class="text-center">
                <button type="submit" class="btn btn-success mt-2" value="crear">Crear</button>          
            </div>
        </form>

        <form action="mostrar-maestros.php" method="post">
            <?php
            if (isset($_POST['NumeroMaestros'])) {
                for($maestro= 0; $maestro < $_POST['NumeroMaestros']; $maestro++) {
                    echo'<input placeholder="Escribe aqui un nombre" type="text" name="nombre" class="form control mt-2">'.
                    '<input placeholder="Escribe aqui tu matricula" type="number" name="matricula" class="form control mt-2">'.
                    '<input placeholder="Escribe aqui la asignatura" type="text" name="asignatura" class="form control mt-2">';

                }
            }
            ?>
            <div class="text-center">
                <button type="submit" class="btn btn-info mt-2">Enviar</button>
            </div>
        </form>
    </div>
</body>
</html>