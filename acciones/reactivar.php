<?php require_once __DIR__ . "/../app/Services/TareaServices.php";


$service = new TareaService();
try
{
    $service->reactivar( $_POST["id"]);
    header("Location:/tarea_corta_2/app/Views/tareas/index.php?=estado");
    exit;
    }
catch(Exception $e){
    header(
        "Location:/tarea_corta_2/app/Views/tareas/index.php?mensaje=errorEstado");
    exit;
}
?>