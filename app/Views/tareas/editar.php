<?php require_once __DIR__ . "/../../Services/TareaServices.php"; $service = new TareaService(); $tarea = $service->obtener($_GET["id"]); ?>
<?php require __DIR__ . "/../layout/header.php"; ?>


<h1>Editar tarea</h1>


<?php if(isset($_GET["mensaje"])): ?>
    <p style="color:green">La tarea fue editada correctamente</p>
<?php endif; ?>


<form action="/tarea_corta_2/acciones/actualizar.php" method="POST">


    <input  type="hidden" name="id" value="<?=$tarea["id_tarea"]?>">
    <label>Detalle</label>
    

    <br>
    <textarea name="detalle"><?=$tarea["detalle"]?></textarea>
    <br><br>
    

    <label>Prioridad</label>
    <select name="prioridad">
        <option <?= $tarea["prioridad"]=="Alta" ? "selected" : "" ?>>Alta</option>
        <option <?= $tarea["prioridad"]=="Media" ? "selected" : "" ?>>Media</option>
        <option <?= $tarea["prioridad"]=="Baja" ? "selected" : "" ?>>Baja</option>
    </select>
    

    <br><br>
    <label>Fecha límite</label>
    <input type="date" name="fecha" value="<?=$tarea["fecha_limite"]?>">
    

    <br><br>
    <label>Responsable</label>
    <select name="responsable">
        <option value="0"> Sin responsable asignado </option>
    </select>
    
    
    <br><br>
    <button>Guardar cambios</button>

</form>

<?php require __DIR__ . "/../layout/footer.php"; ?>