<?php

class ConexionBD
{
    private $host = "localhost";
    private $db = "control_tareas";
    private $user = "root";
    private $password = "";
    private $port = "3307";

    public function getConnection()
    {
        try {

            $conexion = new PDO(
                "mysql:host={$this->host};port={$this->port};dbname={$this->db};charset=utf8",
                $this->user,
                $this->password
            );

            $conexion->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

            return $conexion;

        } catch (PDOException $e) {

            die("Error de conexión: " . $e->getMessage());
        }
    }
}