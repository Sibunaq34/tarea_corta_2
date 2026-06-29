<?php

session_start();
require_once __DIR__ . '/../Services/ResponsableServices.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $identificacion = trim($_POST['identificacion'] ?? '');

    $service = new ResponsableServices();
    if ($service->crear($nombre, $apellidos, $identificacion)) {
        $_SESSION['mensaje'] = 'Responsable creado correctamente.';
        $_SESSION['tipo_mensaje'] = 'exito';
    } else {
        $_SESSION['mensaje'] = 'No se pudo crear el responsable. Revise los datos.';
        $_SESSION['tipo_mensaje'] = 'error';
    }
}

header("Location: ../Views/responsables/index.php");
exit;
