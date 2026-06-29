<?php

require_once __DIR__ . '/../app/Services/ResponsableServices.php';

$id = $_GET['id'] ?? '';

$service = new ResponsableServices();
if ($service->eliminar($id)) {
    header("Location: /tarea_corta_2/app/Views/responsables/index.php?mensaje=eliminado");
    exit;
} else {
    header("Location: /tarea_corta_2/app/Views/responsables/index.php?mensaje=error");
    exit;
}

exit;
