<?php

include "conexion.php";


$responsables = $conexion->query(
"CALL ObtenerResponsables()"
);


?>


<h1>Nueva tarea</h1>


<form action="guardar.php" method="POST">


Detalle:

<br>

<textarea name="detalle"></textarea>


<br>


Prioridad:

<select name="prioridad">

<option>Alta</option>
<option>Media</option>
<option>Baja</option>

</select>


<br>


Fecha límite:

<input type="date" name="fecha">


<br>


Responsable:


<select name="responsable">


<option value="0">
Sin responsable asignado
</option>


<?php while($r=$responsables->fetch_assoc()): ?>


<option value="<?=$r['id_responsable']?>">

<?=$r['nombre_completo']?>

</option>


<?php endwhile; ?>


</select>


<br>


<button>
Guardar
</button>


</form>