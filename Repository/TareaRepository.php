<?php

class TareaRepository
{

    private PDO $conexion;


    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }



    public function listar()
    {

        $sql = "CALL ListarTareas()";

        $stmt = $this->conexion->prepare($sql);

        $stmt->execute();


        return $stmt->fetchAll(
            PDO::FETCH_ASSOC
        );

    }



    public function obtenerPorId($id)
    {

        $sql = "CALL ObtenerTareaPorId(?)";


        $stmt = $this->conexion->prepare($sql);


        $stmt->execute([
            $id
        ]);


        $tarea = $stmt->fetch(
            PDO::FETCH_ASSOC
        );


        $stmt->closeCursor();


        return $tarea;

    }



    public function cambiarEstado($idTarea, $nuevoEstado)
    {

        $sql = "CALL CambiarEstadoTarea(?, ?)";


        $stmt = $this->conexion->prepare($sql);


        $stmt->execute([

            $idTarea,

            $nuevoEstado

        ]);

    }



    public function crear(Tarea $tarea)
    {

        $sql = "
        CALL CrearTarea(?,?,?,?)
        ";


        $stmt = $this->conexion->prepare($sql);


        $stmt->execute([

            $tarea->detalle,

            $tarea->prioridad,

            $tarea->fechaLimite,

            $tarea->idResponsable

        ]);

    }




    public function editar(Tarea $tarea)
    {

        $sql = "
        CALL EditarTarea(?,?,?,?,?)
        ";


        $stmt = $this->conexion->prepare($sql);


        $stmt->execute([

            $tarea->idTarea,

            $tarea->detalle,

            $tarea->prioridad,

            $tarea->fechaLimite,

            $tarea->idResponsable

        ]);

    }



    public function eliminar($id)
    {

        $sql = "
        CALL EliminarTarea(?)
        ";


        $stmt = $this->conexion->prepare($sql);


        $stmt->execute([
            $id
        ]);

    }

}
