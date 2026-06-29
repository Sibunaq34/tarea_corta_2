<?php

session_start();
require_once __DIR__ . '/../Services/TareaServices.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $detalle = trim($_POST['detalle'] ?? '');
    $prioridad = trim($_POST['prioridad'] ?? '');
    $fechaLimite = trim($_POST['fecha_limite'] ?? '');
    $idResponsable = trim($_POST['id_responsable'] ?? '');

    $service = new TareaServices();
    if ($service->crear($detalle, $prioridad, $fechaLimite, $idResponsable)) {
        $_SESSION['mensaje'] = 'Tarea creada correctamente.';
        $_SESSION['tipo_mensaje'] = 'exito';
    } else {
        $_SESSION['mensaje'] = 'No se pudo crear la tarea. Revise los datos.';
        $_SESSION['tipo_mensaje'] = 'error';
    }
}

header("Location: ../Views/tareas/index.php");
exit;
