<?php require_once __DIR__ . '/../Layaout/header.php'; ?>

<h1>Crear responsable</h1>

<form action="../../Acciones/crearResponsable.php" method="POST">
    <label>Nombre:</label><br>
    <input type="text" name="nombre" required><br><br>

    <label>Apellidos:</label><br>
    <input type="text" name="apellidos" required><br><br>

    <label>Identificacion:</label><br>
    <input type="text" name="identificacion" required><br><br>

    <button type="submit">Guardar</button>
</form>

<br>

<a href="index.php">Volver</a>

<?php require_once __DIR__ . '/../Layaout/footer.php'; ?>
