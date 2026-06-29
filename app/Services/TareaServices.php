<?php require_once __DIR__ . "/../Repository/TareaRepository.php";

class TareaService{


    private $repo;


    public function __construct(){

        $this->repo = new TareaRepository();
    }


    public function obtenerTareas(){

        return $this->repo->listar();

    }


    public function crear($datos){



        if(empty($datos["detalle"])){

            die("Debe ingresar detalle");

        }

        return $this->repo->crear($datos["detalle"],$datos["prioridad"],$datos["fecha"],
        $datos["responsable"]);

    }


    public function eliminar($id){

        return $this->repo->eliminar($id);

    }


public function cambiarEstado($idTarea, $nuevoEstado)
{
    $tarea = $this->repo->obtener($idTarea);

    if (!$tarea) {
        throw new Exception("La tarea no existe");
    }

    $estadoActual = $tarea["estado"];

    $transicionesPermitidas = [
        "Pendiente" => ["En progreso"],
        "En progreso" => ["Pendiente", "Bloqueada", "Finalizada"],
        "Bloqueada" => ["En progreso"],
        "Finalizada" => ["En progreso"]
    ];

    if (
        !isset($transicionesPermitidas[$estadoActual]) ||
        !in_array($nuevoEstado, $transicionesPermitidas[$estadoActual])
    ) {
        throw new Exception("Transición de estado no permitida");
    }

    return $this->repo->cambiarEstado($idTarea, $nuevoEstado);
}


    public function obtener($id){

        return $this->repo->obtener($id);

    }
    

    public function editar($datos){

        if(empty($datos["detalle"]))
            {
                die("El detalle es obligatorio");
            }
            
            return $this->repo->editar(
            $datos["id"],
            $datos["detalle"],
            $datos["prioridad"],
            $datos["fecha"],
            $datos["responsable"]);
    }
    

    public function reactivar($id)
    {
        return $this->repo->reactivar($id);
    }


    public function cambiarEstadoAjax($id_tarea, $nuevo_estado)
    {

        try {

            return $this->repo->cambiarEstado(
                $id_tarea,
                $nuevo_estado
            ) ? 0 : -1;


        } catch(mysqli_sql_exception $e){


            if(str_contains($e->getMessage(),"Transición"))
            {
                return 1;
            }


            return -1;


        } catch(Throwable $e){


            return -1;

        }

    }
}

?>