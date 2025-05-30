<?php

if (!class_exists('db')) {
    include(dirname(__DIR__, 2) . '/db/db.php');
}

class auditoriaM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    function lista_articulos_auditorio()
    {
        $sql = "SELECT 
				id_articulo_auditorio,
				descripcion,
				caracteristica,
				th_per_id,
				id_articulo,
				id_localizacion,
				id_estado_articulo,
				tag_unique
			FROM ac_articulos_auditorio";

        $datos = $this->db->datos($sql);
        return $datos;
    }
}
