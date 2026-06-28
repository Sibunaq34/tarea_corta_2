<?php require_once __DIR__ . "/../app/Services/TareaServices.php";

$service = new TareaService();

try
{
    $service->cambiarEstado( $_POST["id"], $_POST["estado"]);
    header("Location:/tarea_corta_2/index.php?mensaje=estado");
}
catch(Exception $e){
    header(
        "Location:/tarea_corta_2/index.php?mensaje=errorEstado");
}


?>