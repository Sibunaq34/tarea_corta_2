<?php


$id = $_GET["id"];



// Después aquí irá:

// $tareaService->eliminar($id);



echo "Tarea eliminada";



// regresar al listado

header(
    "Location: index.php"
);


exit;
