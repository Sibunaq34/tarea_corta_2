<?php include "../layout/header.php"; ?>


<h2>
Crear tarea
</h2>


<form method="POST">


<label>
Detalle
</label>


<textarea 
name="detalle">
</textarea>



<label>
Prioridad
</label>


<select name="prioridad">


<option>
Alta
</option>


<option>
Media
</option>


<option>
Baja
</option>


</select>



<label>
Fecha límite
</label>


<input 
type="date"
name="fecha">



<label>
Responsable
</label>


<select name="responsable">


<option value="">
Sin responsable asignado
</option>


<option>
Juan
</option>


<option>
Pedro
</option>


</select>



<button>
Guardar
</button>



</form>



<?php include "../layout/footer.php"; ?>