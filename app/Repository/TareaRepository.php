<?php

require_once __DIR__ . "/../Config/ConexionBD.php";

class TareaRepository{


    private $db;


    public function __construct(){

        $this->db = ConexionBD::conectar();

    }


    public function listar(){
        $resultado = $this->db->query("CALL ListarTareas()");
        $tareas=[];
        while($fila=$resultado->fetch_assoc()){
            $tareas[]=$fila;

        }
          return $tareas;
    }


    public function crear($detalle,$prioridad,$fecha,$responsable)
    {
        $stmt=$this->db->prepare("CALL CrearTarea(?,?,?,?)");
        $stmt->bind_param("sssi",$detalle,$prioridad,$fecha,$responsable);

        return $stmt->execute();
    }


    public function eliminar($id){

        return $this->db->query("CALL EliminarTarea($id)");
    }


    public function cambiarEstado($id, $estado)
    {
        $stmt=$this->db->prepare("CALL CambiarEstadoTarea(?,?)");
        $stmt->bind_param("is", $id, $estado);


        return $stmt->execute();
    }


    public function obtener($id){


        $resultado=$this->db->query("CALL ObtenerTareaPorId($id)");

        return $resultado->fetch_assoc();
    }


    public function editar($id, $detalle, $prioridad, $fecha, $responsable)
    {

        $stmt = $this->db->prepare("CALL EditarTarea(?,?,?,?,?)");
        $stmt->bind_param("isssi", $id, $detalle, $prioridad, $fecha, $responsable);

        return $stmt->execute();

    }


    public function obtenerResponsables(){
        $resultado = $this->db->query( "CALL ObtenerResponsables()");
        $responsables=[];

        while($fila=$resultado->fetch_assoc())
            {
                $responsables[]=$fila;
            }
        
        return $responsables;
    }


    public function reactivar($id)
    {
        $stmt = $this->db->prepare("CALL ReactivarTarea(?)");
        $stmt->bind_param("i",$id);
        
        return $stmt->execute();

    }


    public function cambiarEstadoAjax($id, $estado)
    {

        $stmt = $this->db->prepare("CALL sp_cambiar_estado_tarea(?, ?, @resultado)");
        $stmt->bind_param("is", $id, $estado);
        $stmt->execute();
        $stmt->close();
        $resultado = $this->db->query("SELECT @resultado AS resultado");
        $fila = $resultado->fetch_assoc();
        return (int)$fila["resultado"];

    }
}


?>