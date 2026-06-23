<?php include "../layout/header.php"; ?>


<?php

// Esto después vendrá del TareaService

$tarea = [

    "id" => 1,
    "detalle" => "Revisar inventario",
    "prioridad" => "Alta",
    "fecha" => "2026-06-30",
    "responsable" => "Juan"

];

?>


<h2>
Editar tarea
</h2>



<form method="POST">


<input 
type="hidden"
name="id"
value="<?= $tarea["id"] ?>"
>



<label>
Detalle
</label>


<textarea 
name="detalle"
>


<?= $tarea["detalle"] ?>


</textarea>




<label>
Prioridad
</label>


<select name="prioridad">


<option 
value="Alta"
<?= 
$tarea["prioridad"] == "Alta" 
? "selected" 
: "" 
?>
>
Alta
</option>


<option 
value="Media"
<?= 
$tarea["prioridad"] == "Media" 
? "selected" 
: "" 
?>
>
Media
</option>


<option 
value="Baja"
<?= 
$tarea["prioridad"] == "Baja" 
? "selected" 
: "" 
?>
>
Baja
</option>


</select>





<label>
Fecha límite
</label>


<input

type="date"

name="fecha"

value="<?= $tarea["fecha"] ?>"

>




<label>
Responsable
</label>



<select name="responsable">



<option value="">

Sin responsable asignado

</option>




<option

value="Juan"

<?= 
$tarea["responsable"] == "Juan" 
? "selected" 
: "" 
?>

>

Juan

</option>




<option

value="Pedro"

<?= 
$tarea["responsable"] == "Pedro" 
? "selected" 
: "" 
?>

>

Pedro

</option>


</select>




<button type="submit">

Guardar cambios

</button>



</form>



<?php include "../layout/footer.php"; ?>