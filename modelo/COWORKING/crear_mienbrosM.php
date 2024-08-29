<?php
include(dirname(__DIR__, 2) . '/db/db.php');

class crear_mienbrosM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    function insertarnombre($parametros)
    {
        $nombre = $parametros['nombre_miembro'];
        $apellido = $parametros['apellido_miembro'];
        $telefono = $parametros['telefono_miembro'];
        $direccion = $parametros['direccion_miembro'];
        $id_espacio = $parametros['id_espacio'];
        

        
        // Validación de campos vacíos
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
        // Verificar si las claves existen en el array $parametros
        $cantidad_compra = isset($parametros['cantidad_compra']) ? $parametros['cantidad_compra'] : null;
        $id_producto = isset($parametros['id_producto']) ? $parametros['id_producto'] : null;
        $pvp_compra = isset($parametros['pvp_compra']) ? $parametros['pvp_compra'] : null;
        $total_compra = isset($parametros['total_compra']) ? $parametros['total_compra'] : null;
        $id_miembro = isset($parametros['id_miembro']) ? $parametros['id_miembro'] : null;
         
        

        if(empty($id_miembro) || empty($id_producto) || empty($cantidad_compra) || empty($pvp_compra) || empty($total_compra)) 
        {
            return "Error: Campos vacíos";
        }
        
        
        $sql = "INSERT INTO co_compra (cantidad_compra, id_producto, pvp_compra, total_compra, id_miembro)
                VALUES ('$cantidad_compra', '$id_producto', '$pvp_compra', '$total_compra', '$id_miembro')";
        $resp = $this->db->sql_string($sql);
        
        return $resp;
    }

    function compraslista()
    {
        $sql = "SELECT 
                miembro.id_miembro,  
                miembro.nombre_miembro, 
                compra.id_producto, 
                compra.cantidad_compra, 
                compra.pvp_compra, 
                compra.total_compra 
            FROM co_compra AS compra
            JOIN co_miembro AS miembro ON compra.id_miembro = miembro.id_miembro;";
        $resp = $this->db->datos($sql);
        return $resp;
    }
    
    
}
?>



