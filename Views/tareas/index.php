<?php
require_once __DIR__ . '/../../Services/TareaServices.php';
require_once __DIR__ . '/../Layaout/header.php';

$service = new TareaServices();
$registrosPorPagina = 10;
$paginaActual = max(1, (int) ($_GET['pagina'] ?? 1));
$totalRegistros = $service->contar();
$totalPaginas = max(1, (int) ceil($totalRegistros / $registrosPorPagina));

if ($paginaActual > $totalPaginas) {
    $paginaActual = $totalPaginas;
}

$offset = ($paginaActual - 1) * $registrosPorPagina;
$tareas = $service->listar($registrosPorPagina, $offset);
?>

<h1>Tareas</h1>

<a class="boton" href="crear.php">Crear tarea</a>

<br><br>

<div class="tabla-responsive">
<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Detalle</th>
        <th>Prioridad</th>
        <th>Fecha limite</th>
        <th>Estado</th>
        <th>Responsable</th>
    </tr>

    <?php foreach ($tareas as $tarea): ?>
        <tr>
            <td><?php echo htmlspecialchars($tarea['id_tarea']); ?></td>
            <td><?php echo htmlspecialchars($tarea['detalle']); ?></td>
            <td><?php echo htmlspecialchars($tarea['prioridad']); ?></td>
            <td><?php echo htmlspecialchars($tarea['fecha_limite'] ?? ''); ?></td>
            <td><?php echo htmlspecialchars($tarea['estado']); ?></td>
            <td>
                <?php
                echo htmlspecialchars(
                    $tarea['responsable'] ?: 'Sin responsable asignado'
                );
                ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
</div>

<div class="paginacion">
    <?php if ($paginaActual > 1): ?>
        <a href="?pagina=<?php echo $paginaActual - 1; ?>">Anterior</a>
    <?php else: ?>
        <strong class="deshabilitado">Anterior</strong>
    <?php endif; ?>

    <?php for ($pagina = 1; $pagina <= $totalPaginas; $pagina++): ?>
        <?php if ($pagina === $paginaActual): ?>
            <strong><?php echo $pagina; ?></strong>
        <?php else: ?>
            <a href="?pagina=<?php echo $pagina; ?>"><?php echo $pagina; ?></a>
        <?php endif; ?>
    <?php endfor; ?>

    <?php if ($paginaActual < $totalPaginas): ?>
        <a href="?pagina=<?php echo $paginaActual + 1; ?>">Siguiente</a>
    <?php else: ?>
        <strong class="deshabilitado">Siguiente</strong>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../Layaout/footer.php'; ?>
