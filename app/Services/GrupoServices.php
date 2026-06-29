<?php

require_once __DIR__ . "/../Repository/GrupoRepository.php";

class GrupoService
{
    private $repo;

    public function __construct()
    {
        $this->repo = new GrupoRepository();
    }

    public function obtenerGrupos()
    {
        return $this->repo->listar();
    }

    public function obtenerGrupo($id)
    {
        return $this->repo->obtenerPorId($id);
    }

    public function obtenerTareasPendientes()
    {
        return $this->repo->listarTareasPendientes();
    }

    public function crear($datos)
    {
        $nombre = trim($datos["nombre"] ?? "");
        $tareas = $datos["tareas"] ?? [];

        if ($nombre == "") {
            return false;
        }

        $this->repo->iniciarTransaccion();

        try {
            $idGrupo = $this->repo->crear($nombre);

            foreach ($tareas as $idTarea) {
                $this->repo->asociarTarea($idGrupo, intval($idTarea));
            }

            $this->repo->confirmarTransaccion();

            return true;
        } catch (Throwable $error) {
            $this->repo->cancelarTransaccion();

            return false;
        }
    }

    public function obtenerTareasPorGrupo($idGrupo)
    {
        return $this->repo->listarTareasPorGrupo($idGrupo);
    }

    public function eliminar($idGrupo)
    {
        $this->repo->iniciarTransaccion();

        try {
            $this->repo->quitarGrupoDeTareas($idGrupo);
            $resultado = $this->repo->eliminar($idGrupo);
            $this->repo->confirmarTransaccion();

            return $resultado;
        } catch (Throwable $error) {
            $this->repo->cancelarTransaccion();

            return false;
        }
    }
}

?>
