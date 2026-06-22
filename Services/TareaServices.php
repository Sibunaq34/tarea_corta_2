<?php

class TareaService
{

    private TareaRepository $repository;



    public function __construct(
        TareaRepository $repository
    )
    {
        $this->repository = $repository;
    }




    public function listar()
    {

        return $this->repository->listar();

    }




    public function crear(Tarea $tarea)
    {


        if(
            empty($tarea->detalle)
        )
        {
            throw new Exception(
                "El detalle es obligatorio"
            );
        }



        if(
            empty($tarea->prioridad)
        )
        {
            throw new Exception(
                "Debe seleccionar prioridad"
            );
        }



        // responsable opcional

        if(
            empty($tarea->idResponsable)
        )
        {
            $tarea->idResponsable = null;
        }



        $this->repository->crear($tarea);

    }



    public function editar(Tarea $tarea)
    {

        $this->repository->editar($tarea);

    }



    public function eliminar($id)
    {

        $this->repository->eliminar($id);

    }

}