<?php
require_once __DIR__ . '/../../Services/ResponsableServices.php';
require_once __DIR__ . '/../Layaout/header.php';

$id = $_GET['id'] ?? '';
$service = new ResponsableServices();
$responsable = $service->buscarPorId($id);

if (!$responsable) {
    echo "<p>Responsable no encontrado.</p>";
    echo '<a href="index.php">Volver</a>';
    require_once __DIR__ . '/../Layaout/footer.php';
    exit;
}
?>

<h1>Editar responsable</h1>

<form action="../../Acciones/editarResponsable.php" method="POST">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($responsable['id_responsable']); ?>">

    <label>Nombre:</label><br>
    <input type="text" name="nombre" value="<?php echo htmlspecialchars($responsable['nombre']); ?>" required><br><br>

    <label>Apellidos:</label><br>
    <input type="text" name="apellidos" value="<?php echo htmlspecialchars($responsable['apellidos']); ?>" required><br><br>

    <label>Identificacion:</label><br>
    <input type="text" name="identificacion" value="<?php echo htmlspecialchars($responsable['identificacion']); ?>" required><br><br>

    <button type="submit">Guardar cambios</button>
</form>

<br>

<a href="index.php">Volver</a>

<?php require_once __DIR__ . '/../Layaout/footer.php'; ?>
