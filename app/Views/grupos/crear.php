<?php require_once __DIR__ . "/../../Services/GrupoServices.php"; $service = new GrupoService(); $tareas = $service->obtenerTareasPendientes(); ?>
<?php require __DIR__ . "/../layout/header.php"; ?>

<h1>Crear grupo</h1>

<?php if (isset($_GET["mensaje"]) && $_GET["mensaje"] == "nombre"): ?>
    <p style="color:red">El nombre del grupo es obligatorio</p>
<?php endif; ?>

<form action="/tarea_corta_2/acciones/grupos_guardar.php" method="POST">
    <label>Nombre del grupo</label>
    <br>
    <input type="text" name="nombre" required>

    <br><br>

    <h3>Tareas pendientes</h3>

    <?php if (empty($tareas)): ?>
        <p>No existen tareas pendientes. Puede crear el grupo igualmente.</p>
    <?php else: ?>
        <table border="1">
            <tr>
                <th>Seleccionar</th>
                <th>Detalle</th>
                <th>Prioridad</th>
                <th>Fecha limite</th>
            </tr>

            <?php foreach ($tareas as $tarea): ?>
                <tr>
                    <td>
                        <input type="checkbox" name="tareas[]" value="<?= $tarea["id_tarea"] ?>">
                    </td>
                    <td><?= htmlspecialchars($tarea["detalle"]) ?></td>
                    <td><?= htmlspecialchars($tarea["prioridad"]) ?></td>
                    <td><?= htmlspecialchars($tarea["fecha_limite"] ?? "") ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <br>
    <button>Guardar grupo</button>
</form>

<a href="/tarea_corta_2/index.php?pagina=grupos">
    <button>Volver</button>
</a>

<?php require __DIR__ . "/../layout/footer.php"; ?>
