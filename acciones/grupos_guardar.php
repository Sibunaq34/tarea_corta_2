<?php

require_once __DIR__ . "/../app/Services/GrupoServices.php";

$service = new GrupoService();

$datos = [
    "nombre" => $_POST["nombre"] ?? "",
    "tareas" => isset($_POST["tareas"]) && is_array($_POST["tareas"]) ? $_POST["tareas"] : []
];

if (trim($datos["nombre"]) == "") {
    header("Location:/tarea_corta_2/app/Views/grupos/crear.php?mensaje=nombre");
    exit;
}

if ($service->crear($datos)) {
    header("Location:/tarea_corta_2/index.php?pagina=grupos&mensaje=creado");
    exit;
}

header("Location:/tarea_corta_2/index.php?pagina=grupos&mensaje=error");
exit;

?>
