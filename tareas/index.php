<?php include "../layout/header.php"; ?>


<h2> Lista de tareas </h2>


<a href="crear.php"> Nueva tarea </a>



<table>
<tr>
    <th>Detalle </th><th>Prioridad</th><th>Responsable</th><th>Estado</th>
</tr>

<?php foreach($tareas as $tarea): ?>
<tr>
<td><?= $tarea['detalle'] ?></td><td><?= $tarea['prioridad'] ?></td><td><?= $tarea['responsable'] ?? "Sin responsable asignado"?>
</td><td><?= $tarea['estado'] ?></td>
<td>
    <a href="editar.php?id=<?= $tarea['id_tarea'] ?>">Editar</a>
    <a href="eliminar.php?id=<?= $tarea['id_tarea'] ?>"onclick="return confirm('¿Desea eliminar esta tarea?')">Eliminar</a>
</td>
</tr>

</table>



<?php include "../layout/footer.php"; ?>