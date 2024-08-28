<?php
if (!class_exists('db')) {
    include('../db/db.php');
}
/**
 * 
 */
class Comunidad_TablasM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    function lista_comunidad_tablas()
    {
        $sql = "SELECT 
        sa_tbl_pac_id, 
        sa_tbl_pac_nombre,
        sa_tbl_pac_prefijo, 
        sa_tbl_pac_estado
        FROM cat_comunidad_tablas 
        WHERE sa_tbl_pac_estado = 1";

        $sql .= " ORDER BY sa_tbl_pac_id;";
        $datos = $this->db->datos($sql);
        return $datos;
    }
}
