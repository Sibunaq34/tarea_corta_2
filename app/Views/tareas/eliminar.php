<?php require_once __DIR__ . "/../../Services/TareaServices.php"; $service=new TareaService();

$service->eliminar($_GET["id"]);
header("Location:/index.php");

?>