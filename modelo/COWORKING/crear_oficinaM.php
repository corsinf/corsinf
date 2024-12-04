<?php
require_once(dirname(__DIR__, 2).'/db/db.php');

class crear_oficinaM {
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

    function listardebaseFiltros($nombre = false, $rango_precio = false, $estado = false) { 
        // Consulta base
        $sql = "SELECT e.*, c.nombre_categoria
                FROM co_espacio e
                LEFT JOIN co_categoria c ON e.id_categoria = c.id_categoria
                WHERE 1 = 1";
        
        // Filtrar por nombre si está disponible
        if ($nombre) {
            $sql .= " AND e.nombre_espacio LIKE '%" . $nombre . "%'";
        }
    
        // Filtrar por rango de precio si está disponible
        if ($rango_precio) {
            if ($rango_precio == 1) {
                $sql .= " AND e.precio_espacio BETWEEN 1 AND 100";
            } elseif ($rango_precio == 2) {
                $sql .= " AND e.precio_espacio BETWEEN 101 AND 500";
            }
        }
        
        // Filtrar por estado si está disponible
        if ($estado) {
            $sql .= " AND e.estado_espacio = '" . $estado . "'"; 
        }
    
        // Ordenar los resultados
        $sql .= " ORDER BY e.id_espacio DESC";
        
        // Ejecutar la consulta y devolver los resultados
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
    function insertarEvento($datos) {
        $sql = "INSERT INTO co_agenda (
                    co_agenda_titulo, 
                    co_agenda_detalle, 
                    Id_espacio, 
                    co_agenda_fechaIni, 
                    co_agenda_fechaFin, 
                    co_agenda_estado_pago, 
                    co_agenda_contacto, 
                    co_agenda_responsable
                ) VALUES (
                    '" . $datos['titulo'] . "', 
                    '" . $datos['detalle'] . "', 
                    " . intval($datos['id_espacio']) . ", 
                    '" . $datos['fechaInicio'] . "', 
                    '" . $datos['fechaFin'] . "', 
                    " . intval($datos['estado_pago']) . ", 
                    '" . $datos['contacto'] . "', 
                    '" . $datos['responsable'] . "'
                )";
        return $this->db->sql_string($sql);
    }
    function obtenerEventos() {
        $sql = "SELECT 
                    co_agenda_titulo AS title, 
                    co_agenda_fechaIni AS start, 
                    co_agenda_fechaFin AS end 
                FROM co_agenda";
        return $this->db->datos($sql); 
    }
    
    
    
}
?>