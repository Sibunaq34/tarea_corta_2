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


    public function cambiarEstado($id, $estado)
    {
        return $this->repo->cambiarEstado($id,$estado);
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
    public function cambiarEstadoAjax($id, $estado)
    {

    $estados = ["Pendiente", "En progreso", "Bloqueada", "Finalizada"];

    if(!in_array($estado, $estados))
    {

        return -1;

    }

    return $this->repo->cambiarEstadoAjax($id, $estado);

    }   
}

?>