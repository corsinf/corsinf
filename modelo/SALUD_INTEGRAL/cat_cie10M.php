<?php
if (!class_exists('db')) {
    include('../db/db.php');
}
/**
 * 
 */
class cat_cie10M
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    function lista_cie10()
    {
            $sql =
                "SELECT 
                    sa_cie10_id,
                    sa_cie10_codigo,
                    sa_cie10_descripcion,
                    sa_cie10_estado,
                    sa_cie10_fecha_creacion

                FROM cat_cie10
                WHERE sa_cie10_estado = '1';";

            $datos = $this->db->datos($sql);
            return $datos;
    }

    function buscar_cie10($buscar)
	{
		$sql = "SELECT * FROM cat_cie10 WHERE sa_cie10_estado = 1 and CONCAT(sa_cie10_codigo, ' ', sa_cie10_descripcion) LIKE '%" . $buscar . "%'";
		$datos = $this->db->datos($sql);
		return $datos;
	}
}
