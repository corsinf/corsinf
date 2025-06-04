<?php

if (!class_exists('db')) {
    include(dirname(__DIR__, 2) . '/db/db.php');
}

class ac_auditoriaM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    function lista_articulos_auditorio_vista_publica()
    {
        $sql = "SELECT 
                aa.id_articulo_auditorio,
                aa.descripcion,
                aa.caracteristica,
                aa.th_per_id,
                aa.id_articulo,
                aa.id_localizacion,
                aa.id_estado_articulo,
                aa.tag_unique,
                loc.EMPLAZAMIENTO,
                CONCAT(per.th_per_primer_nombre, ' ', per.th_per_primer_apellido) AS Nombrepersona
            FROM ac_articulos_auditorio aa
            INNER JOIN ac_localizacion loc ON aa.id_localizacion = loc.ID_LOCALIZACION
            INNER JOIN th_personas per ON aa.th_per_id = per.th_per_id";

        $datos = $this->db->datos($sql);
        return $datos;
    }
}
