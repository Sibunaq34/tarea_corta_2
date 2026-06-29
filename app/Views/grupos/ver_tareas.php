<?php
require_once __DIR__ . "/../../Services/GrupoServices.php";

$service = new GrupoService();
$idGrupo = intval($_GET["id"] ?? 0);
$grupo = $service->obtenerGrupo($idGrupo);
$tareas = $grupo ? $service->obtenerTareasPorGrupo($idGrupo) : [];
?>
<?php require __DIR__ . "/../layout/header.php"; ?>

<?php if (!$grupo): ?>
    <p style="color:red">Grupo no encontrado</p>
<?php else: ?>
    <h1>Tareas del grupo: <?= htmlspecialchars($grupo["nombre"]) ?></h1>

    <?php if (empty($tareas)): ?>
        <p>Este grupo no tiene tareas asociadas</p>
    <?php else: ?>
        <table border="1">
            <tr>
                <th>Detalle</th>
                <th>Prioridad</th>
                <th>Fecha limite</th>
                <th>Estado</th>
            </tr>

            <?php foreach ($tareas as $tarea): ?>
                <tr>
                    <td><?= htmlspecialchars($tarea["detalle"]) ?></td>
                    <td><?= htmlspecialchars($tarea["prioridad"]) ?></td>
                    <td><?= htmlspecialchars($tarea["fecha_limite"] ?? "") ?></td>
                    <td><?= htmlspecialchars($tarea["estado"]) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
<?php endif; ?>

<br>
<a href="/tarea_corta_2/index.php?pagina=grupos">
    <button>Volver</button>
</a>

<?php require __DIR__ . "/../layout/footer.php"; ?>
