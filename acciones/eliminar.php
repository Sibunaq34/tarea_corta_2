<?php require_once __DIR__ . "/../app/Services/TareaServices.php";

$service = new TareaService();
$id = intval($_GET["id"]);


if($service->eliminar($id)){
    header("Location:/tarea_corta_2/app/Views/tareas/index.php?mensaje=eliminada");
    exit;

}else{

    header("Location:/tarea_corta_2/app/Views/tareas/index.php?mensaje=error");
    exit;

}

?>