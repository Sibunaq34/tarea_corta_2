<?php require __DIR__ . "/../layout/header.php"; ?>

<h1>Crear tarea</h1>

<form action="/tarea_corta_2/acciones/crear.php" method="POST">
    
    <label>Detalle</label>
    <br>
    <textarea name="detalle" required ></textarea>
    <br><br>
    <label>Prioridad</label>


    <select name="prioridad">
        <option value="Alta">Alta</option>
        <option value="Media">Media</option>
        <option value="Baja">Baja</option>
    </select>


    <br><br>
    <label>Fecha límite (opcional)</label>
    <input type="date" name="fecha">
    <br><br>

    <label>Responsable</label>
    <select name="responsable">
        <option value="0">Sin responsable asignado</option>
        <?php require_once __DIR__ . "/../../Repository/TareaRepository.php"; $repo = new TareaRepository(); $responsables = $repo->obtenerResponsables(); foreach($responsables as $r): ?>
            <option value="<?=$r['id_responsable']?>">
                <?=$r['nombre_completo']?>
            </option>
        <?php endforeach; ?>
    </select>

    <br><br>

    <button>Crear tarea</button>

</form>


<?php require __DIR__ . "/../layout/footer.php"; ?>