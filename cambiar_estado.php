<?php

require_once __DIR__ . "/Config/BaseDeDatos.php";
require_once __DIR__ . "/Entidad/TareaEntidad.php";
require_once __DIR__ . "/Repository/TareaRepository.php";
require_once __DIR__ . "/Services/TareaServices.php";

try {

    if(
        $_SERVER["REQUEST_METHOD"] !== "POST"
    )
    {
        throw new Exception(
            "Solicitud no permitida"
        );
    }


    $idTarea = $_POST["id_tarea"] ?? null;
    $nuevoEstado = $_POST["nuevo_estado"] ?? null;


    if(
        empty($idTarea) ||
        empty($nuevoEstado)
    )
    {
        throw new Exception(
            "Faltan datos para cambiar el estado"
        );
    }


    $database = new Database();
    $conexion = $database->getConnection();
    $repository = new TareaRepository($conexion);
    $service = new TareaService($repository);


    $service->cambiarEstado(
        $idTarea,
        $nuevoEstado
    );


    header(
        "Location: Views/tareas/index.php"
    );
    exit;

} catch(Exception $e) {

    echo "<p>Error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, "UTF-8") . "</p>";
    echo '<p><a href="Views/tareas/index.php">Volver al listado</a></p>';

}
