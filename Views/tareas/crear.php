<?php
require_once __DIR__ . '/../../Services/ResponsableServices.php';
require_once __DIR__ . '/../Layaout/header.php';

$responsableService = new ResponsableServices();
$responsables = $responsableService->listar();
?>

<h1>Crear tarea</h1>

<form action="../../Acciones/crearTarea.php" method="POST">
    <label>Detalle:</label><br>
    <textarea name="detalle" required></textarea><br><br>

    <label>Prioridad:</label><br>
    <select name="prioridad" required>
        <option value="">Seleccione</option>
        <option value="Alta">Alta</option>
        <option value="Media">Media</option>
        <option value="Baja">Baja</option>
    </select><br><br>

    <label>Fecha limite:</label><br>
    <input type="date" name="fecha_limite"><br><br>

    <label>Responsable:</label><br>
    <select name="id_responsable">
        <option value="">Sin responsable asignado</option>
        <?php foreach ($responsables as $responsable): ?>
            <option value="<?php echo htmlspecialchars($responsable['id_responsable']); ?>">
                <?php
                echo htmlspecialchars(
                    $responsable['nombre'] . ' ' . $responsable['apellidos']
                );
                ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <button type="submit">Guardar</button>
</form>

<br>

<a href="index.php">Volver</a>

<?php require_once __DIR__ . '/../Layaout/footer.php'; ?>
