<?php require_once __DIR__ . "/../app/Services/TareaServices.php";


$service = new TareaService();
$service->reactivar($_POST["id"]);

header("Location:/tarea_corta_2/index.php?mensaje=estado");

?>