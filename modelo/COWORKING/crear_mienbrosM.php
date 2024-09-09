<?php
include(dirname(__DIR__, 2) . '/db/db.php');

class crear_mienbrosM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    function get_id_espacio($id_miembro)
{
    try {
        $query = "SELECT id_espacio FROM co_miembro WHERE id_miembro = ?";
        $stmt = $this->db->conexion()->prepare($query);
        $stmt->bind_param('i', $id_miembro);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        if ($row) {
            return $row['id_espacio'];
        } else {
            return null;
        }
    } catch (Exception $e) {
        error_log('Error en get_id_espacio: ' . $e->getMessage());
        return null;
    }
}

    function insertarnombre($parametros)
    {
        $nombre = $parametros['nombre_miembro'];
        $apellido = $parametros['apellido_miembro'];
        $telefono = $parametros['telefono_miembro'];
        $direccion = $parametros['direccion_miembro'];
        $id_espacio = $parametros['id_espacio'];
        
        if(empty($nombre) || empty($apellido) || empty($telefono) || empty($direccion) || empty($id_espacio)) {
            return "Error: Campos vacíos";
        }

        $sql = "INSERT INTO co_miembro (nombre_miembro, apellido_miembro, telefono_miembro, direccion_miembro, id_espacio)
                VALUES ('$nombre', '$apellido', '$telefono', '$direccion', '$id_espacio')";
        $resp = $this->db->sql_string($sql);
        return $resp;
    }

    function listardebase()
    {
        $sql = "SELECT * FROM co_miembro";
        $resp = $this->db->datos($sql);
        return $resp;
    }
    
    function insertarcompra($parametros)
    {
        $id_sala = isset($parametros['id_sala']) ? intval($parametros['id_sala']) : null;
        $id_compra = isset($parametros['id_compra']) ? intval($parametros['id_compra']) : null;
        $cantidad_compra = isset($parametros['cantidad_compra']) ? intval($parametros['cantidad_compra']) : null;
        $id_producto = isset($parametros['id_producto']) ? intval($parametros['id_producto']) : null;
        $pvp_compra = isset($parametros['pvp_compra']) ? floatval($parametros['pvp_compra']) : null;
        $total_compra = isset($parametros['total_compra']) ? floatval($parametros['total_compra']) : null;
        $id_miembro = isset($parametros['id_miembro']) ? intval($parametros['id_miembro']) : null;
    
        if (empty($cantidad_compra) || empty($id_producto) || empty($pvp_compra) || empty($total_compra) || empty($id_miembro)) {
            return "Error: Campos vacíos";
        }
    
        // Construye la consulta SQL con placeholders
        $sql = "INSERT INTO co_compra (cantidad_compra, id_producto, pvp_compra, total_compra, id_miembro, id_sala) 
        VALUES ($cantidad_compra, $id_producto, $pvp_compra, $total_compra, $id_miembro, $id_sala)";

    
        // Ejecuta la consulta
        $resp = $this->db->sql_string($sql);
    
        return $resp;
    }
    
    

    function compraslista()
    {
        $sql = "SELECT 
                miembro.id_miembro,  
                miembro.nombre_miembro,
                miembro.id_espacio AS id_sala, 
                compra.id_compra , 
                compra.id_producto, 
                compra.cantidad_compra, 
                compra.pvp_compra, 
                compra.total_compra 
            FROM co_compra AS compra
            JOIN co_miembro AS miembro ON compra.id_miembro = miembro.id_miembro;";
        $resp = $this->db->datos($sql);
        return $resp;
    }

    function eliminar_miembro($id_miembro)
    {
        if (empty($id_miembro)) {
            return "Error: ID de miembro vacío";
        }
    
        $datos = [
            ['campo' => 'id_miembro', 'dato' => $id_miembro]
        ];
        $resultado = $this->db->delete('co_miembro', $datos);
    
        return $resultado == 1 ? "Miembro eliminado con éxito" : "Error al eliminar el miembro";
    }

    function eliminar_compra($id_compra)
    {
        if (empty($id_compra)) {
            return "Error: ID de compra vacío";
        }
    
        $datos = [
            ['campo' => 'id_compra', 'dato' => $id_compra]
        ];
        $resultado = $this->db->delete('co_compra', $datos);
    
        return $resultado == 1 ? "Compra eliminada con éxito" : "Error al eliminar la compra";
    }

    function tiene_compras($id_miembro)
    {
        $id_miembro = intval($id_miembro); 
        
        $sql = "SELECT COUNT(*) AS total FROM co_compra WHERE id_miembro = $id_miembro";
        
        $result = $this->db->datos($sql);
        
        // Comprobar el resultado de la consulta
        if (isset($result[0]['total'])) {
            return $result[0]['total'] > 0;
        }
    
        return false;
    }
}
?>

