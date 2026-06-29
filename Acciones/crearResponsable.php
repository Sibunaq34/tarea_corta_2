<?php

require_once __DIR__ . '/../app/Services/ResponsableServices.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $identificacion = trim($_POST['identificacion'] ?? '');

    $service = new ResponsableServices();
    if ($service->crear($nombre, $apellidos, $identificacion)) {
        header("Location: /tarea_corta_2/app/Views/responsables/index.php?mensaje=creado");
        exit;
    } else {
        header("Location: /tarea_corta_2/app/Views/responsables/crear.php?mensaje=error");
        exit;
    }
}

header("Location: /tarea_corta_2/app/Views/responsables/crear.php");
exit;
