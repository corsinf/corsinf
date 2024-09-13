<?php
include(dirname(__DIR__, 2) . '/db/db.php');

class crear_mienbrosM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    function insertarnombre($parametros) {
        $nombre = $parametros['nombre_miembro'];
        $apellido = $parametros['apellido_miembro'];
        $telefono = $parametros['telefono_miembro'];
        $direccion = $parametros['direccion_miembro'];
        $id_espacio = intval($parametros['id_espacio']);
        
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
        return $this->db->datos($sql);
    }

    function insertarcomprasala($parametros)
    {
        $id_sala = isset($parametros['id_sala']) ? intval($parametros['id_sala']) : 'NULL';
        $cantidad_compra = isset($parametros['cantidad_compra']) ? intval($parametros['cantidad_compra']) : 'NULL';
        $id_producto = isset($parametros['id_producto']) ? intval($parametros['id_producto']) : 'NULL';
        $pvp_compra = isset($parametros['pvp_compra']) ? floatval($parametros['pvp_compra']) : 'NULL';
        $total_compra = isset($parametros['total_compra']) ? floatval($parametros['total_compra']) : 'NULL';

        if (empty($id_sala) || empty($cantidad_compra) || empty($id_producto) || empty($pvp_compra) || empty($total_compra)) {
            return "Error: Campos vacíos";
        }
        

        
        $sql = "INSERT INTO co_compra (cantidad_compra, id_producto, pvp_compra, total_compra, id_sala) 
                VALUES ($cantidad_compra, $id_producto, $pvp_compra, $total_compra, $id_sala)";
        
        return $this->db->sql_string($sql);
    }

    function listacomprasala()
    {
        $sql = "SELECT 
                    id_compra, 
                    id_producto, 
                    cantidad_compra, 
                    pvp_compra, 
                    total_compra,
                    id_sala
                FROM co_compra";
        
        return $this->db->datos($sql);
    }
    

    function insertarcompra($parametros)
    {
        $id_sala = isset($parametros['id_sala']) ? intval($parametros['id_sala']) : 'NULL';
        $cantidad_compra = isset($parametros['cantidad_compra']) ? intval($parametros['cantidad_compra']) : 'NULL';
        $id_producto = isset($parametros['id_producto']) ? intval($parametros['id_producto']) : 'NULL';
        $pvp_compra = isset($parametros['pvp_compra']) ? floatval($parametros['pvp_compra']) : 'NULL';
        $total_compra = isset($parametros['total_compra']) ? floatval($parametros['total_compra']) : 'NULL';
        $id_miembro = isset($parametros['id_miembro']) ? intval($parametros['id_miembro']) : 'NULL';

        if (empty($id_sala) || empty($cantidad_compra) || empty($id_producto) || empty($pvp_compra) || empty($total_compra || empty($id_miembro))) {
            return "Error: Campos vacíos";
        }
        
        $sql = "INSERT INTO co_compra (cantidad_compra, id_producto, pvp_compra, total_compra, id_miembro, id_sala) 
                VALUES ($cantidad_compra, $id_producto, $pvp_compra, $total_compra, $id_miembro, $id_sala)";
        
        return $this->db->sql_string($sql);
    }

    function compraslista()
    {
        $sql = "SELECT 
                miembro.id_miembro,  
                miembro.nombre_miembro,
                miembro.id_espacio AS id_sala, 
                id_compra, 
                compra.id_producto, 
                compra.cantidad_compra, 
                compra.pvp_compra, 
                compra.total_compra 
            FROM co_compra AS compra
            JOIN co_miembro AS miembro ON compra.id_miembro = miembro.id_miembro";
        return $this->db->datos($sql);
    }

    function eliminar_miembro($id_miembro)
    {
        if (empty($id_miembro)) {
            return "Error: ID de miembro vacío";
        }
    
        $id_miembro = intval($id_miembro);
        $sql = "DELETE FROM co_miembro WHERE id_miembro = $id_miembro";
        return $this->db->sql_string($sql) == 1 ? "Miembro eliminado con éxito" : "Error al eliminar el miembro";
    }

    function eliminar_compra($id_compra)
    {
        if (empty($id_compra)) {
            return "Error: ID de compra vacío";
        }
    
        $id_compra = intval($id_compra);
        $sql = "DELETE FROM co_compra WHERE id_compra = $id_compra";
        return $this->db->sql_string($sql) == 1 ? "Compra eliminada con éxito" : "Error al eliminar la compra";
    }

    function tiene_compras($id_miembro)
    {
        $id_miembro = intval($id_miembro);
        $sql = "SELECT COUNT(*) AS total FROM co_compra WHERE id_miembro = $id_miembro";
        $result = $this->db->datos($sql);
        
        if (isset($result[0]['total'])) {
            return $result[0]['total'] > 0;
        }
    
        return false;
    }
}
?>




