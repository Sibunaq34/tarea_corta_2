<?php

require_once __DIR__ . "/../Config/ConexionBD.php";

class ResponsableRepository
{
    private $db;

    public function __construct()
    {
        $this->db = ConexionBD::conectar();
    }

    public function listar($limite = 10, $offset = 0)
    {
        $sql = "SELECT * FROM responsables ORDER BY id_responsable DESC LIMIT ? OFFSET ?";
        $stmt = $this->db->prepare($sql);
        $limite = (int) $limite;
        $offset = (int) $offset;
        $stmt->bind_param("ii", $limite, $offset);
        $stmt->execute();
        $resultado = $stmt->get_result();

        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    public function contar()
    {
        $sql = "SELECT COUNT(*) FROM responsables";
        $resultado = $this->db->query($sql);
        $fila = $resultado->fetch_row();

        return (int) $fila[0];
    }

    public function crear($nombre, $apellidos, $identificacion)
    {
        $sql = "INSERT INTO responsables (nombre, apellidos, identificacion)
                VALUES (?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sss", $nombre, $apellidos, $identificacion);

        return $stmt->execute();
    }

    public function buscarPorId($id)
    {
        $sql = "SELECT * FROM responsables WHERE id_responsable = ?";
        $stmt = $this->db->prepare($sql);
        $id = (int) $id;
        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function editar($id, $nombre, $apellidos, $identificacion)
    {
        $sql = "UPDATE responsables
                SET nombre = ?,
                    apellidos = ?,
                    identificacion = ?
                WHERE id_responsable = ?";

        $stmt = $this->db->prepare($sql);
        $id = (int) $id;
        $stmt->bind_param("sssi", $nombre, $apellidos, $identificacion, $id);

        return $stmt->execute();
    }

    public function eliminar($id)
    {
        $sql = "DELETE FROM responsables WHERE id_responsable = ?";
        $stmt = $this->db->prepare($sql);
        $id = (int) $id;
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }
}
