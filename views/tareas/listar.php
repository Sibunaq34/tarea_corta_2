<?php

require "../../config/Database.php";
require "../../repository/TareaRepository.php";
require "../../services/TareaService.php";



$database = new ConexionBD();

$conexion = $database->getConnection();



$repository = new TareaRepository(
    $conexion
);



$service = new TareaService(
    $repository
);



$tareas = $service->listar();



include "index.php";