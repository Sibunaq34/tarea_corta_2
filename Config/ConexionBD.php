<?php

class ConexionBD
{
    private $host = "localhost";
    private $db = "nombre_bd";
    private $user = "root";
    private $password = "Sibu123.";

    public function getConnection()
    {
        try {

            $conexion = new PDO(
                "mysql:host={$this->host};dbname={$this->db};charset=utf8",
                $this->user,
                $this->password
            );


            $conexion->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );


            return $conexion;


        } catch(PDOException $e){

            die(
                "Error de conexión: " . $e->getMessage()
            );
        }
    }
}