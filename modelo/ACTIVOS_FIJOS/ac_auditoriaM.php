<?php

if (!class_exists('db')) {
    include(dirname(__DIR__, 2) . '/db/db.php');
}

require_once(dirname(__DIR__, 2) . '/db/codigos_globales.php');

class ac_auditoriaM extends BaseModel
{
    private $codigos_globales;

    protected $tabla = 'ac_articulos_auditorio';
    protected $primaryKey = 'id_articulo_auditorio AS _id';

    protected $camposPermitidos = [
        'descripcion',
        'caracteristica',
        'th_per_id',
        'id_articulo',
    ];


    function lista_articulos_auditorio_vista_publica($id_empresa)
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

        if ($id_empresa) {
            $this->codigos_globales = new codigos_globales();
            $sql_publica = $this->codigos_globales->datos_empresa_publica($id_empresa, $sql);
            return isset($sql_publica['datos']) ? $sql_publica['datos'] : [];
        }

        $datos = $this->db->datos($sql);
        return $datos;
    }
}
