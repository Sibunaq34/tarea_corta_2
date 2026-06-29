<?php

require_once __DIR__ . '/../Config/ConexionBD.php';

class ResponsableRepository
{
    private $conexion;

    public function __construct()
    {
        $db = new ConexionBD();
        $this->conexion = $db->getConnection();
    }

    public function listar($limite = 10, $offset = 0)
    {
        $sql = "SELECT * FROM responsables ORDER BY id_responsable DESC LIMIT :limite OFFSET :offset";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(':limite', (int) $limite, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contar()
    {
        $sql = "SELECT COUNT(*) FROM responsables";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function crear($nombre, $apellidos, $identificacion)
    {
        $sql = "INSERT INTO responsables (nombre, apellidos, identificacion)
                VALUES (:nombre, :apellidos, :identificacion)";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            ':nombre' => $nombre,
            ':apellidos' => $apellidos,
            ':identificacion' => $identificacion
        ]);
    }

    public function buscarPorId($id)
    {
        $sql = "SELECT * FROM responsables WHERE id_responsable = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function editar($id, $nombre, $apellidos, $identificacion)
    {
        $sql = "UPDATE responsables
                SET nombre = :nombre,
                    apellidos = :apellidos,
                    identificacion = :identificacion
                WHERE id_responsable = :id";

        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':nombre' => $nombre,
            ':apellidos' => $apellidos,
            ':identificacion' => $identificacion
        ]);
    }

    public function eliminar($id)
    {
        $sql = "DELETE FROM responsables WHERE id_responsable = :id";
        $stmt = $this->conexion->prepare($sql);

        return $stmt->execute([':id' => $id]);
    }
}
