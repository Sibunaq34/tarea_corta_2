<?php

require_once __DIR__ . '/../app/Services/ResponsableServices.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $nombre = trim($_POST['nombre'] ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $identificacion = trim($_POST['identificacion'] ?? '');

    $service = new ResponsableServices();
    if ($service->editar($id, $nombre, $apellidos, $identificacion)) {
        header("Location: /tarea_corta_2/app/Views/responsables/index.php?mensaje=editado");
        exit;
    } else {
        header("Location: /tarea_corta_2/app/Views/responsables/editar.php?id=" . urlencode($id) . "&mensaje=error");
        exit;
    }
}

header("Location: /tarea_corta_2/app/Views/responsables/index.php");
exit;
