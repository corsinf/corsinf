<?php
include(dirname(__DIR__, 2).'/db/db.php');

class claseEjemploM {
    private $db;

    function __construct() {
        $this->db = new db();
    }   

    function insertarnombre($param) {
        $sql = "INSERT INTO co_espacio (nombre_espacio, aforo_espacio, precio_espacio, estado_espacio, id_categoria) VALUES ('".$param['nombre']."', '".$param['aforo']."', '".$param['precio']."', '".$param['estado']."', '".$param['categoria']."')";
        // print_r($sql);die();
        $resp = $this->db->sql_string($sql);
        print_r($resp);die();
        return $resp;
    }

    function listardebase() {
        $sql = "SELECT * FROM co_espacio";
        $resp = $this->db->datos($sql); 
        return $resp;
    }
    function insertarMobiliario($datos) {
        $sql = "INSERT INTO co_mobiliario (nombre_mobilario, cantidad, id_espacio, detalle_mobiliario) VALUES ('".$datos['nombre']."', ".$datos['cantidad'].", ".$datos['id_espacio'].")";
        $resp = $this->db->sql_string($sql);
        return $resp;
    }

    function listarMobiliario($id_espacio) {
        $sql = "SELECT * FROM co_mobiliario WHERE id_espacio = ".$id_espacio;
        $resp = $this->db->datos($sql);
        return $resp;
    }
}

?>
