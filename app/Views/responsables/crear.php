<?php require_once __DIR__ . '/../layout/header.php'; ?>

<h1>Crear responsable</h1>

<?php if (isset($_GET["mensaje"]) && $_GET["mensaje"] == "error"): ?>
    <p style="color:red">Debe completar todos los datos del responsable</p>
<?php endif; ?>

<form action="/tarea_corta_2/Acciones/crearResponsable.php" method="POST">
    <label>Nombre:</label><br>
    <input type="text" name="nombre" required><br><br>

    <label>Apellidos:</label><br>
    <input type="text" name="apellidos" required><br><br>

    <label>Identificacion:</label><br>
    <input type="text" name="identificacion" required><br><br>

    <button type="submit">Guardar</button>
</form>

<br>

<a href="/tarea_corta_2/app/Views/responsables/index.php">Volver</a>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
