<?php
// ajax/cambiar_estado.php — Endpoint AJAX para cambiar estado de una tarea
// Consume sp_cambiar_estado_tarea (propietario: Persona C)

require_once __DIR__ . '/../includes/db.php';

header('Content-Type: application/json');

// Validación de entrada
$tarea_id     = (int)  ($_POST['tarea_id']     ?? 0);
$nuevo_estado =  trim(  $_POST['nuevo_estado'] ?? '');

if ($tarea_id <= 0 || $nuevo_estado === '') {
    echo json_encode(['ok' => false, 'codigo' => -1, 'mensaje' => 'Datos inválidos']);
    exit;
}

$estados_permitidos = ['Pendiente', 'En progreso', 'Bloqueada', 'Finalizada'];
if (!in_array($nuevo_estado, $estados_permitidos, true)) {
    echo json_encode(['ok' => false, 'codigo' => -1, 'mensaje' => 'Estado no reconocido']);
    exit;
}

try {
    $pdo  = getDB();

    // Llama al SP de Persona C que valida la transición y actualiza el estado
    $stmt = $pdo->prepare('CALL sp_cambiar_estado_tarea(:id, :estado, @resultado)');
    $stmt->execute([':id' => $tarea_id, ':estado' => $nuevo_estado]);
    $stmt->closeCursor();

    $res    = $pdo->query('SELECT @resultado AS resultado')->fetch();
    $codigo = (int) ($res['resultado'] ?? -1);

    $mensaje = match ($codigo) {
        0       => 'Estado actualizado',
        1       => 'Transición de estado no permitida',
        2       => 'Tarea no encontrada',
        default => 'Error desconocido',
    };

    echo json_encode([
        'ok'      => $codigo === 0,
        'codigo'  => $codigo,
        'mensaje' => $mensaje,
    ]);

} catch (Exception $e) {
    echo json_encode([
        'ok'      => false,
        'codigo'  => -1,
        'mensaje' => 'Error al procesar la solicitud: ' . $e->getMessage(),
    ]);
}
