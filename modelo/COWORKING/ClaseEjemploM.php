<?php
include(dirname(__DIR__, 2).'/db/db.php');

class claseEjemploM {
    private $db;

    function __construct() {
        $this->db = new db();
    }

    // Inserta un nuevo espacio en la base de datos
    function insertarnombre($param) {
        $sql = "INSERT INTO co_espacio (nombre_espacio, aforo_espacio, precio_espacio, estado_espacio, id_categoria) 
                VALUES ('".$param['nombre']."', '".$param['aforo']."', '".$param['precio']."', '".$param['estado']."', '".$param['categoria']."')";
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
        $sql = "UPDATE co_espacio SET 
                nombre_espacio = '".$param['nombre']."', 
                aforo_espacio = '".$param['aforo']."', 
                precio_espacio = '".$param['precio']."', 
                estado_espacio = '".$param['estado']."', 
                id_categoria = '".$param['categoria']."'
                WHERE id_espacio = ".$param['id_espacio'];
        $resp = $this->db->sql_string($sql);
        return $resp;
    }

    // Obtiene todos los espacios
    function listardebase() {
        $sql = "SELECT * FROM co_espacio";
        $resp = $this->db->datos($sql);
        return $resp;
    }

    // Inserta un nuevo mobiliario
    function insertarMobiliario($datos) {
        $sql = "INSERT INTO co_mobiliario (nombre_mobilario, cantidad, id_espacio) 
                VALUES ('".$datos['nombre']."', ".$datos['cantidad'].", ".$datos['id_espacio'].")";
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
     function insertarCategoria($datos) {
        $stmt = $this->db->prepare("INSERT INTO co_categoria (nombre_categoria) VALUES (:nombre)");
        $stmt->bindParam(':nombre', $datos['nombre']);
        return $stmt->execute();
    }

    // Listar categorías
    function listarCategorias() {
        $stmt = $this->db->query("SELECT id_categoria, nombre_categoria FROM co_categoria");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>