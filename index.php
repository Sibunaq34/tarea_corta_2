<?php


$pagina = $_GET['pagina'] ?? 'inicio';



if($pagina == "tareas"){


    require "app/Config/ConexionBD.php";
    require "app/Repository/TareaRepository.php";
    require "app/Services/TareaServices.php";


    $conexionDB = new ConexionBD();
    $conexion = ConexionBD::conectar();
    $repository = new TareaRepository($conexion);
    $service = new TareaService($repository);
    $tareas = $service->obtenerTareas();
    include "app/Views/tareas/index.php";
}elseif($pagina == "grupos"){
    include "app/Views/grupos/index.php";
}else{
    include "app/Views/layout/header.php";
    echo "Página de inicio";

    include "app/Views/layout/footer.php";


}
