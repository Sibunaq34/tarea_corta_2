<?php
require_once __DIR__ . '/../app/Config/ConexionBD.php';

$tarea_id     = (int)($_POST['tarea_id']     ?? 0);
$nuevo_estado =       ($_POST['nuevo_estado'] ?? '');

if ($tarea_id <= 0 || $nuevo_estado === '') {
    header('Location: ../tablero.php?error=-1');
    exit;
}

try {
    $db   = ConexionBD::conectar();
    $stmt = $db->prepare('CALL CambiarEstadoTarea(?, ?)');
    $stmt->bind_param('is', $tarea_id, $nuevo_estado);

    $resultado = $stmt->execute();
    $stmt->close();

    header('Location: ../tablero.php?error=' . ($resultado ? 0 : -1));
    exit;

} catch (mysqli_sql_exception $e) {
    $msg = $e->getMessage();

    if (str_contains($msg, 'Transición') || str_contains($msg, 'transicion') || str_contains($msg, 'Transicion')) {
        header('Location: ../tablero.php?error=1');
    } elseif (str_contains($msg, 'existe')) {
        header('Location: ../tablero.php?error=2');
    } else {
        header('Location: ../tablero.php?error=-1');
    }
    exit;
}
