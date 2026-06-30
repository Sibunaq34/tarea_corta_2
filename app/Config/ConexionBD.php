<?php

class ConexionBD {

    public static function conectar(){

        $conexion = new mysqli(
            "localhost",
            "root",
            "",
            "control_tareas",
            3306
        );


        if($conexion->connect_error){

            die("Error de conexión: ".$conexion->connect_error);

        }


        return $conexion;
        

    }

}

?>