<?php require_once __DIR__ . "/../../Services/TareaServices.php"; $service = new TareaService(); $tareas = $service->obtenerTareas();?>
<?php require __DIR__ . "/../layout/header.php"; ?>


<h1>Tareas</h1>


<a href="/tarea_corta_2/app/Views/tareas/crear.php">
    <button>Crear Tarea</button>
</a>

<br><br>


<?php if(isset($_GET["mensaje"])): ?>

    <?php if($_GET["mensaje"]=="creada"): ?>
        <p style="color:green">La tarea fue creada correctamente</p>
    <?php endif; ?>


    <?php if($_GET["mensaje"]=="error"): ?>
        <p style="color:red">Error al crear la tarea</p>
    <?php endif; ?>
    
<?php endif; ?>


<?php if(isset($_GET["mensaje"])): ?>

    <?php if($_GET["mensaje"]=="editado"): ?>
        <p style="color:green">La tarea fue editada correctamente</p>
    <?php endif; ?>

<?php endif; ?>


<?php if(isset($_GET["mensaje"])): ?>

    <?php if($_GET["mensaje"]=="eliminada"): ?>
        <p style="color:green">Tarea eliminada correctamente</p>
    <?php endif; ?>


    <?php if($_GET["mensaje"]=="error"): ?>
        <p style="color:red">Error al eliminar la tarea</p>
    <?php endif; ?>

<?php endif; ?>


<?php if(isset($_GET["mensaje"])): ?>

    <?php if($_GET["mensaje"]=="estado"): ?>
        <p style="color:green">Estado actualizado correctamente</p>
    <?php endif; ?>
    <?php if($_GET["mensaje"]=="errorEstado"): ?>
        <p style="color:red"> Cambio de estado no permitido </p>
    <?php endif; ?>

<?php endif; ?>


<table border="1">

    <tr>
        <th>Detalle</th><th>Prioridad</th><th>Responsable</th><th>Estado</th><th>Acciones</th>
    </tr>


    <?php foreach($tareas as $t): ?>
        <tr>
            <td>
            <?php if($t["estado"]=="Finalizada")
            {
                echo "<s>".$t["detalle"]."</s>";
                }
                else
                {
                    echo $t["detalle"];
                }?>
            </td>
            <td>
                <?=$t["prioridad"]?>
            </td>
            <td><?=$t["responsable"] ?? "Sin responsable asignado"?>
            </td>
            <td>
                <?=$t["estado"]?>
            </td>
            <td>

                <a  href="/tarea_corta_2/acciones/eliminar.php?id=<?=$t['id_tarea']?>"
                onclick="return confirm('¿Desea eliminar esta tarea?')"> Eliminar</a>
                <a href="/tarea_corta_2/app/Views/tareas/editar.php?id=<?=$t['id_tarea']?>"> Editar </a>
                <br>

                <?php if($t["estado"]=="Finalizada"): ?>
                    <form  action="/tarea_corta_2/acciones/reactivar.php" method="POST">
                        <input type="hidden"name="id" value="<?=$t['id_tarea']?>">
                        <button>Reactivar</button>
                    </form>
                <?php endif; ?>
                
                <?php if($t["estado"]!="Finalizada"): ?>
                <form action="/tarea_corta_2/acciones/estado.php" method="POST" >
                    <input  type="hidden" name="id" value="<?=$t['id_tarea']?>">
                    <select name="estado">
                        <option value="Pendiente"> Pendiente </option>
                        <option value="En progreso"> En progreso </option>
                        <option value="Bloqueada"> Bloqueada </option>
                        <option value="Finalizada"> Finalizada </option>
                    </select>
                    <button> Cambiar estado</button>
                </form>
                <?php endif; ?>


            </td>
        </tr><?php endforeach; ?>
    </table>

<?php require __DIR__ . "/../layout/footer.php"; ?>