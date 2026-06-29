<?php require_once __DIR__ . "/../../Services/GrupoServices.php"; $service = new GrupoService(); $grupos = $service->obtenerGrupos(); ?>
<?php require __DIR__ . "/../layout/header.php"; ?>

<h1>Grupos de tareas</h1>

<a href="/tarea_corta_2/app/Views/grupos/crear.php">
    <button>Crear grupo</button>
</a>

<br><br>

<?php if (isset($_GET["mensaje"])): ?>
    <?php if ($_GET["mensaje"] == "creado"): ?>
        <p style="color:green">Grupo creado correctamente</p>
    <?php endif; ?>

    <?php if ($_GET["mensaje"] == "eliminado"): ?>
        <p style="color:green">Grupo eliminado correctamente. Las tareas quedaron sin grupo</p>
    <?php endif; ?>

    <?php if ($_GET["mensaje"] == "error"): ?>
        <p style="color:red">No se pudo completar la accion</p>
    <?php endif; ?>
<?php endif; ?>

<?php if (empty($grupos)): ?>
    <p>No existen grupos registrados</p>
<?php else: ?>
    <table border="1">
        <tr>
            <th>Nombre</th>
            <th>Cantidad de tareas</th>
            <th>Acciones</th>
        </tr>

        <?php foreach ($grupos as $grupo): ?>
            <tr>
                <td><?= htmlspecialchars($grupo["nombre"]) ?></td>
                <td><?= htmlspecialchars($grupo["cantidad_tareas"]) ?></td>
                <td>
                    <a href="/tarea_corta_2/app/Views/grupos/ver_tareas.php?id=<?= $grupo["id_grupo"] ?>">Ver tareas</a>
                    <a
                        href="/tarea_corta_2/acciones/grupos_eliminar.php?id=<?= $grupo["id_grupo"] ?>"
                        onclick="return confirm('Desea eliminar este grupo? Las tareas no se eliminaran')"
                    >Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<?php require __DIR__ . "/../layout/footer.php"; ?>
