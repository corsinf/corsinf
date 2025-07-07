<?php
if (!class_exists('db')) {
    include(dirname(__DIR__, 3) . '/db/db.php');
}
/**
 * 
 */
class ac_descargasM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    //Para mostrar todos los registros con campos especificos para la vista principal
    function listar()
    {
        $sql =
            "SELECT *
                    FROM ac_articulos 
                    WHERE id_articulo = 1";

        $sql .= ";";
        $datos = $this->db->datos($sql);
        return $datos;
    }

    function listar_datos_lote($lote, $query = false)
    {
        $sql =
            "SELECT 
                {$lote} AS numero_lote
            FROM ac_articulos
            WHERE {$lote} IS NOT NULL";

        if ($query) {
            $query_escaped = addslashes($query);
            $sql .= " AND {$lote} = '{$query_escaped}'";
        }

        $sql .= " GROUP BY {$lote}
            ORDER BY numero_lote ASC;";

        $datos = $this->db->datos($sql, false, true);
        return $datos;
    }

    function buscar_lote($lote_atributo, $buscar_lote)
    {
        $sql = "SELECT id_articulo, tag_serie FROM ac_articulos WHERE {$lote_atributo} IS NOT NULL
                AND {$lote_atributo} = '{$buscar_lote}'";
        $datos = $this->db->datos($sql, false, true);
        return $datos;
    }
}
