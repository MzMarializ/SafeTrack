<?php
include "db.php";

if ($_POST) {
    $titulo = $_POST['titulo'];
    $tipo = $_POST['tipo'];
    $descripcion = $_POST['descripcion'];
    $provincia = $_POST['provincia'];
    $municipio = $_POST['municipio'];
    $barrio = $_POST['barrio'];
    $fecha = $_POST['fecha'];

    $foto = "";
    if (!empty($_FILES['foto']['name'])) {
        $foto = time() . "_" . basename($_FILES['foto']['name']);
        move_uploaded_file($_FILES['foto']['tmp_name'], "uploads/" . $foto);
    }

    $sql = "INSERT INTO incidencias (fecha,titulo,tipo,descripcion,provincia,municipio,barrio,foto)
            VALUES ('$fecha','$titulo','$tipo','$descripcion','$provincia','$municipio','$barrio','$foto')";
    $conn->query($sql);
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Nueva Incidencia</title>
</head>
<body>
    <h1>Registrar Incidencia</h1>
    <form method="post" enctype="multipart/form-data">
        <label>Fecha: <input type="date" name="fecha" required></label><br>
        <label>Título: <input type="text" name="titulo" required></label><br>
        <label>Tipo: <input type="text" name="tipo" required></label><br>
        <label>Descripción:<br><textarea name="descripcion"></textarea></label><br>
        <label>Provincia: <input type="text" name="provincia"></label><br>
        <label>Municipio: <input type="text" name="municipio"></label><br>
        <label>Barrio: <input type="text" name="barrio"></label><br>
        <label>Foto: <input type="file" name="foto"></label><br><br>
        <button type="submit">Guardar</button>
    </form>
    <a href="index.php">⬅ Volver</a>
</body>
</html>