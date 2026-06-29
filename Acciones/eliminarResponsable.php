<?php

session_start();
require_once __DIR__ . '/../Services/ResponsableServices.php';

$id = $_GET['id'] ?? '';

$service = new ResponsableServices();
if ($service->eliminar($id)) {
    $_SESSION['mensaje'] = 'Responsable eliminado correctamente.';
    $_SESSION['tipo_mensaje'] = 'exito';
} else {
    $_SESSION['mensaje'] = 'No se pudo eliminar el responsable.';
    $_SESSION['tipo_mensaje'] = 'error';
}

header("Location: ../Views/responsables/index.php");
exit;
