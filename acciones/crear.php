<?php require_once __DIR__ . "/../app/Services/TareaServices.php";

$service = new TareaService();

$datos=[
    "detalle"=>$_POST["detalle"],
    "prioridad"=>$_POST["prioridad"],
    "fecha"=>$_POST["fecha"],
    "responsable"=>$_POST["responsable"]
];



if($service->crear($datos))
{
    header("Location:/tarea_corta_2/app/Views/tareas/index.php?mensaje=creada");
}
else
{
    header("Location:/tarea_corta_2/app/Views/tareas/index.php?mensaje=error");
}

?>