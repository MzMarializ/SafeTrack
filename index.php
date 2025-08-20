<?php include "db.php"; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>SafeTrack - Incidencias</title>
</head>
<body>
    <h1>Listado de Incidencias</h1>
    <a href="nueva.php">➕ Registrar nueva incidencia</a>
    <hr>
    <?php
    $res = $conn->query("SELECT * FROM incidencias ORDER BY fecha DESC");
    while ($row = $res->fetch_assoc()):
    ?>
        <div style="border:1px solid #ccc; margin:10px; padding:10px;">
            <h3><?= htmlspecialchars($row['titulo']) ?> (<?= $row['tipo'] ?>)</h3>
            <p><b>Fecha:</b> <?= $row['fecha'] ?></p>
            <p><b>Descripción:</b> <?= nl2br(htmlspecialchars($row['descripcion'])) ?></p>
            <p><b>Lugar:</b> <?= $row['provincia'] ?>, <?= $row['municipio'] ?>, <?= $row['barrio'] ?></p>
            <?php if ($row['foto']): ?>
                <img src="uploads/<?= $row['foto'] ?>" width="200">
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
</body>
</html>