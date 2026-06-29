<?php
require_once __DIR__ . '/../../Services/ResponsableServices.php';
require_once __DIR__ . '/../Layaout/header.php';

$service = new ResponsableServices();
$registrosPorPagina = 10;
$paginaActual = max(1, (int) ($_GET['pagina'] ?? 1));
$totalRegistros = $service->contar();
$totalPaginas = max(1, (int) ceil($totalRegistros / $registrosPorPagina));

if ($paginaActual > $totalPaginas) {
    $paginaActual = $totalPaginas;
}

$offset = ($paginaActual - 1) * $registrosPorPagina;
$responsables = $service->listar($registrosPorPagina, $offset);
?>

<h1>Responsables</h1>

<a class="boton" href="crear.php">Crear responsable</a>

<br><br>

<div class="tabla-responsive">
<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Apellidos</th>
        <th>Identificacion</th>
        <th>Acciones</th>
    </tr>

    <?php foreach ($responsables as $responsable): ?>
        <tr>
            <td><?php echo htmlspecialchars($responsable['id_responsable']); ?></td>
            <td><?php echo htmlspecialchars($responsable['nombre']); ?></td>
            <td><?php echo htmlspecialchars($responsable['apellidos']); ?></td>
            <td><?php echo htmlspecialchars($responsable['identificacion']); ?></td>
            <td class="acciones">
                <a href="editar.php?id=<?php echo htmlspecialchars($responsable['id_responsable']); ?>">Editar</a>
                |
                <a href="../../Acciones/eliminarResponsable.php?id=<?php echo htmlspecialchars($responsable['id_responsable']); ?>"
                   data-modal-eliminar
                   data-mensaje="Seguro que desea eliminar este responsable?">
                   Eliminar
                </a>
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
