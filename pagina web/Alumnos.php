<html>
<head>
    <title>Alumnos</title>
</head>
<body>
<h1>Registo de Alumnos</h1>
<form action="Alumnos.php" method="post">
<input type="number" name="NumeroAlumnos" placeholder="No.Alumnos">
<input type="submit" name="" value="crear">
</form>
<?php

if(isset($_POST['NumeroAlumnos'])){
for ($alumno =0; $alumno <$_POST['NumeroAlumnos']; $alumno++){
    echo '<input placeholder="Escribe aqui nombre" type="text" name="nombre"><br>'.

    '<input placeholder="Escribe aqui apellido" type="text name="apellido"><br>'.

    '<input placeholder="Escribe aqui edad" type="number" name="edad"<br><br>';
}
}
?>
<button type="submit" name="submit">enviar</button>
</body>
</html>