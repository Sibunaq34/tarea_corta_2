<?php require_once __DIR__ . "/../app/Services/TareaServices.php";

header('Content-Type: application/json');

$id_tarea = (int)($_POST["tarea_id"] ?? 0);

$nuevo_estado = trim(
    $_POST["nuevo_estado"] ?? ""
);


if($tarea_id <= 0 || $nuevo_estado=="")
{

    echo json_encode(["ok"=>false, "codigo"=>-1, "mensaje"=>"Datos inválidos"]);
    exit;

}


try{


    $service = new TareaService();


    $codigo = $service->cambiarEstadoAjax( $tarea_id, $nuevo_estado);


    $mensaje = match($codigo){
        0 => "Estado actualizado correctamente",
        1 => "Transición de estado no permitida",
        2 => "La tarea no existe",
        default => "Error desconocido"

    };



    echo json_encode(["ok"=>$codigo===0, "codigo"=>$codigo, "mensaje"=>$mensaje]);

}catch(Exception $e){

    echo json_encode(["ok"=>false, "codigo"=>-1,"mensaje"=>$e->getMessage()

    ]);


}

?>