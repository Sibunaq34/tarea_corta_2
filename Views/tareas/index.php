<?php

require_once __DIR__ . "/../../Config/BaseDeDatos.php";
require_once __DIR__ . "/../../Entidad/TareaEntidad.php";
require_once __DIR__ . "/../../Repository/TareaRepository.php";
require_once __DIR__ . "/../../Services/TareaServices.php";

$database = new Database();
$conexion = $database->getConnection();
$repository = new TareaRepository($conexion);
$service = new TareaService($repository);

$tareas = $service->listar();

usort($tareas, function($a, $b) {
    $aFinalizada = ($a["estado"] ?? "") === "Finalizada";
    $bFinalizada = ($b["estado"] ?? "") === "Finalizada";

    return $aFinalizada <=> $bFinalizada;
});

function mostrarValor($valor)
{
    return htmlspecialchars((string)($valor ?? ""), ENT_QUOTES, "UTF-8");
}

function obtenerCambiosPermitidos($estado)
{
    $cambios = [
        "Pendiente" => [
            "En progreso" => "En progreso"
        ],
        "En progreso" => [
            "Pendiente" => "Pendiente",
            "Bloqueada" => "Bloqueada",
            "Finalizada" => "Finalizada"
        ],
        "Bloqueada" => [
            "En progreso" => "En progreso"
        ],
        "Finalizada" => [
            "En progreso" => "Reactivar"
        ]
    ];

    return $cambios[$estado] ?? [];
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de tareas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #cccccc;
            padding: 8px;
            text-align: left;
        }

        th {
            background: #eeeeee;
        }

        .finalizada {
            text-decoration: line-through;
        }

        .acciones form {
            display: inline;
        }

        button {
            margin: 2px;
            padding: 5px 8px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>Tareas</h1>

    <table>
        <thead>
            <tr>
                <th>Detalle</th>
                <th>Prioridad</th>
                <th>Fecha limite</th>
                <th>Responsable</th>
                <th>Estado</th>
                <th>Fecha finalizacion</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($tareas as $tarea): ?>
                <?php
                    $estado = $tarea["estado"] ?? "";
                    $detalleClase = $estado === "Finalizada" ? "finalizada" : "";
                    $responsable = $tarea["responsable"] ?? $tarea["id_responsable"] ?? "";
                    $idTarea = $tarea["id_tarea"] ?? "";
                ?>
                <tr>
                    <td class="<?php echo $detalleClase; ?>">
                        <?php echo mostrarValor($tarea["detalle"] ?? ""); ?>
                    </td>
                    <td><?php echo mostrarValor($tarea["prioridad"] ?? ""); ?></td>
                    <td><?php echo mostrarValor($tarea["fecha_limite"] ?? ""); ?></td>
                    <td><?php echo mostrarValor($responsable); ?></td>
                    <td><?php echo mostrarValor($estado); ?></td>
                    <td><?php echo mostrarValor($tarea["fecha_finalizacion"] ?? ""); ?></td>
                    <td class="acciones">
                        <?php foreach(obtenerCambiosPermitidos($estado) as $nuevoEstado => $textoBoton): ?>
                            <form method="POST" action="../../cambiar_estado.php">
                                <input type="hidden" name="id_tarea" value="<?php echo mostrarValor($idTarea); ?>">
                                <input type="hidden" name="nuevo_estado" value="<?php echo mostrarValor($nuevoEstado); ?>">
                                <button type="submit"><?php echo mostrarValor($textoBoton); ?></button>
                            </form>
                        <?php endforeach; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
