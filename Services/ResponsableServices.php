<?php

require_once __DIR__ . '/../Repository/ResponsableRepository.php';

class ResponsableServices
{
    private $repository;

    public function __construct()
    {
        $this->repository = new ResponsableRepository();
    }

    public function listar($limite = 10, $offset = 0)
    {
        return $this->repository->listar($limite, $offset);
    }

    public function contar()
    {
        return $this->repository->contar();
    }

    public function crear($nombre, $apellidos, $identificacion)
    {
        if (empty($nombre) || empty($apellidos) || empty($identificacion)) {
            return false;
        }

        return $this->repository->crear($nombre, $apellidos, $identificacion);
    }

    public function buscarPorId($id)
    {
        return $this->repository->buscarPorId($id);
    }

    public function editar($id, $nombre, $apellidos, $identificacion)
    {
        if (empty($id) || empty($nombre) || empty($apellidos) || empty($identificacion)) {
            return false;
        }

        return $this->repository->editar($id, $nombre, $apellidos, $identificacion);
    }

    public function eliminar($id)
    {
        if (empty($id)) {
            return false;
        }

        return $this->repository->eliminar($id);
    }
}
