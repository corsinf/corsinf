<?php
if (!class_exists('db')) {
    include(dirname(__DIR__, 2) . '/db/db.php');
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
}
