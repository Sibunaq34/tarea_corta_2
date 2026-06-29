<?php

class TareaService
{
    private TareaRepository $repository;

    public function __construct(TareaRepository $repository)
    {
        $this->repository = $repository;
    }

    public function listar()
    {
        return $this->repository->listar();
    }

    public function obtenerPorId($id)
    {
        return $this->repository->obtenerPorId($id);
    }

    public function cambiarEstado($idTarea, $nuevoEstado)
    {
        $tarea = $this->obtenerPorId($idTarea);

        if (!$tarea) {
            throw new Exception("No se encontró la tarea indicada");
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
            throw new Exception(
                "No se permite cambiar una tarea de " . $estadoActual . " a " . $nuevoEstado
            );
        }

        $this->repository->cambiarEstado($idTarea, $nuevoEstado);
    }

    public function crear(Tarea $tarea)
    {
        if (empty($tarea->detalle)) {
            throw new Exception("El detalle es obligatorio");
        }

        if (empty($tarea->prioridad)) {
            throw new Exception("Debe seleccionar prioridad");
        }

        if (empty($tarea->idResponsable)) {
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
