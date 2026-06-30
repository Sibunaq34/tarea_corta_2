<?php
require_once __DIR__ . "/../../Services/TareaServices.php";
require_once __DIR__ . "/../../Services/GrupoServices.php";

$service = new TareaService();
$grupoService = new GrupoService();
$tarea = $service->obtener($_GET["id"]);
$grupos = $grupoService->obtenerGrupos();
?>
<?php require __DIR__ . "/../layout/header.php"; ?>


<h1>Editar tarea</h1>


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
        <option value="0">Sin responsable asignado</option>
        <?php require_once __DIR__ . "/../../Repository/TareaRepository.php"; $repo = new TareaRepository(); $responsables = $repo->obtenerResponsables(); foreach($responsables as $r): ?>
            <option value="<?=$r['id_responsable']?>">
                <?=$r['nombre_completo']?>
            </option>
        <?php endforeach; ?>
    </select>
    
    
    <br><br>

    <label>Grupo</label>
    <select name="grupo">
        <option value="0">Sin grupo</option>
        <?php foreach($grupos as $grupo): ?>
            <option
                value="<?=$grupo['id_grupo']?>"
                <?= ($tarea["id_grupo"] ?? 0) == $grupo["id_grupo"] ? "selected" : "" ?>
            >
                <?=$grupo['nombre']?>
            </option>
        <?php endforeach; ?>
    </select>

    <br><br>

    <button>Guardar cambios</button>

</form>
    <a href="/tarea_corta_2/app/Views/tareas/index.php">
        <button>Volver</button>
    </a>

<?php require __DIR__ . "/../layout/footer.php"; ?>
