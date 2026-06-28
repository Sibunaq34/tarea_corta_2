<?php

require_once __DIR__ . "/../app/Services/TareaServices.php";


$service = new TareaService();


$service->editar($_POST);


header(
"Location:/tarea_corta_2/app/Views/tareas/index.php?id=".$_POST["id"]."&mensaje=editado"
);

?>