<?php require_once __DIR__ . "/../app/Services/TareaServices.php";

$service = new TareaService();
$id = intval($_GET["id"]);


if($service->eliminar($id)){

    header(
    "Location: /tarea_corta_2/index.php?mensaje=eliminada"
    );

}else{

    header(
    "Location: /tarea_corta_2/index.php?mensaje=error"
    );

}

?>