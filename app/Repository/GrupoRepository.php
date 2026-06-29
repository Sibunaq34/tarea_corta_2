<?php

require_once __DIR__ . "/../Config/ConexionBD.php";

class GrupoRepository
{
    private $db;

    public function __construct()
    {
        $this->db = ConexionBD::conectar();
    }

    public function listar()
    {
        $sql = "
            SELECT g.id_grupo, g.nombre, COUNT(t.id_tarea) AS cantidad_tareas
            FROM grupos g
            LEFT JOIN tareas t ON g.id_grupo = t.id_grupo
            GROUP BY g.id_grupo, g.nombre
            ORDER BY g.id_grupo DESC
        ";

        $resultado = $this->db->query($sql);
        $grupos = [];

        while ($fila = $resultado->fetch_assoc()) {
            $grupos[] = $fila;
        }

        return $grupos;
    }

    public function obtenerPorId($id)
    {
        $stmt = $this->db->prepare("
            SELECT id_grupo, nombre
            FROM grupos
            WHERE id_grupo = ?
        ");

        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function listarTareasPendientes()
    {
        $sql = "
            SELECT id_tarea, detalle, prioridad, fecha_limite
            FROM tareas
            WHERE estado = 'Pendiente'
            ORDER BY id_tarea DESC
        ";

        $resultado = $this->db->query($sql);
        $tareas = [];

        while ($fila = $resultado->fetch_assoc()) {
            $tareas[] = $fila;
        }

        return $tareas;
    }

    public function crear($nombre)
    {
        $stmt = $this->db->prepare("INSERT INTO grupos (nombre) VALUES (?)");
        $stmt->bind_param("s", $nombre);
        $stmt->execute();

        return $this->db->insert_id;
    }

    public function asociarTarea($idGrupo, $idTarea)
    {
        $stmt = $this->db->prepare("
            UPDATE tareas
            SET id_grupo = ?
            WHERE id_tarea = ?
        ");

        $stmt->bind_param("ii", $idGrupo, $idTarea);

        return $stmt->execute();
    }

    public function listarTareasPorGrupo($idGrupo)
    {
        $stmt = $this->db->prepare("
            SELECT id_tarea, detalle, prioridad, fecha_limite, estado
            FROM tareas
            WHERE id_grupo = ?
            ORDER BY id_tarea DESC
        ");

        $stmt->bind_param("i", $idGrupo);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $tareas = [];

        while ($fila = $resultado->fetch_assoc()) {
            $tareas[] = $fila;
        }

        return $tareas;
    }

    public function quitarGrupoDeTareas($idGrupo)
    {
        $stmt = $this->db->prepare("
            UPDATE tareas
            SET id_grupo = NULL
            WHERE id_grupo = ?
        ");

        $stmt->bind_param("i", $idGrupo);

        return $stmt->execute();
    }

    public function eliminar($idGrupo)
    {
        $stmt = $this->db->prepare("DELETE FROM grupos WHERE id_grupo = ?");
        $stmt->bind_param("i", $idGrupo);

        return $stmt->execute();
    }

    public function iniciarTransaccion()
    {
        $this->db->begin_transaction();
    }

    public function confirmarTransaccion()
    {
        $this->db->commit();
    }

    public function cancelarTransaccion()
    {
        $this->db->rollback();
    }
}

?>
