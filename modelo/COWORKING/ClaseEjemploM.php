<?php
include(dirname(__DIR__, 2).'/db/db.php');

class claseEjemploM {
    private $db;

    function __construct() {
        $this->db = new db();
    }

    function insertarnombre($param) {
        $estado = ($param['estado'] == 'Activo') ? 'A' : 'I'; // A para Activo, I para Inactivo
        $sql = "INSERT INTO co_espacio (nombre_espacio, aforo_espacio, precio_espacio, estado_espacio, id_categoria) 
                VALUES ('".$param['nombre']."', '".$param['aforo']."', '".$param['precio']."', '".$estado."', '".$param['categoria']."')";
        $resp = $this->db->sql_string($sql);
        return $resp;
    }
   // Elimina un espacio
    function eliminarEspacio($id_espacio) {
        $sql = "DELETE FROM co_espacio WHERE id_espacio = " . intval($id_espacio);
        $resp = $this->db->sql_string($sql);
        return $resp;
    }

// Actualiza un espacio
function actualizarEspacio($param) {
    $estado = ($param['estado'] == 'Activo') ? 'A' : 'I';
    $sql = "UPDATE co_espacio SET 
                nombre_espacio = '" . $param['nombre'] . "',
                aforo_espacio = '" . $param['aforo'] . "',
                precio_espacio = '" . $param['precio'] . "',
                estado_espacio = '" . $estado . "',
                id_categoria = '" . $param['categoria'] . "'
            WHERE id_espacio = " . intval($param['id_espacio']);

    $resp = $this->db->sql_string($sql);
    return $resp;
}

    //Obtiene espacios
    
    function listardebase() {
        $sql = "SELECT e.*, c.nombre_categoria
                FROM co_espacio e
                INNER JOIN co_categoria c ON e.id_categoria = c.id_categoria
                ORDER BY id_espacio DESC";
        $resp = $this->db->datos($sql);
        return $resp;
    }

    // Inserta un nuevo mobiliario
    function insertarMobiliario($datos) {
        $sql = "INSERT INTO co_mobiliario (detalle_mobiliario, cantidad, id_espacio) 
                VALUES ('".$datos['detalle']."', ".$datos['cantidad'].", ".$datos['id_espacio'].")";
        $resp = $this->db->sql_string($sql);
        return $resp;
    }

   // Obtiene un espacio específico
    function obtenerEspacio($id_espacio) {
        $sql = "SELECT * FROM co_espacio WHERE id_espacio = " . intval($id_espacio);
        $resp = $this->db->datos($sql);
        return $resp[0];
    }

     // Insertar una nueva categoría
     function insertarCategoria($datos, $tabla) {
        $stmt = $this->db->inserts($tabla, $datos);
        return $stmt;
    }

    // Listar categorías
    function listarCategorias() {
        $stmt = $this->db->datos("SELECT id_categoria, nombre_categoria FROM co_categoria");
        return $stmt;
    }
    
    function listarMobiliario($id_espacio) {
        
        $sql = "SELECT id_mobiliario, id_espacio, cantidad, detalle_mobiliario 
        FROM co_mobiliario 
        WHERE id_espacio = " . intval($id_espacio);
        //print_r($sql); die();
        $resp = $this->db->datos($sql);
        return $resp;
    }
    
    
}
?>