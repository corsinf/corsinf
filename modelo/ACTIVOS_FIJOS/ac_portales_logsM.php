<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class ac_controladoraM extends BaseModel
{
    protected $tabla = 'ac_portales_logs';
    protected $primaryKey = 'ac_plog_id AS _id';

    protected $camposPermitidos = [
        'ac_plog_controladora',
        'ac_plog_rfid',
        'ac_plog_antena',
        'ac_plog_fecha_creacion',
    ];

    function log_portal_articulo()
    {
        $sql =
            "SELECT
                POR.ac_plog_controladora AS 'controladora',
                POR.ac_plog_antena AS 'id_antena',
                POR.ac_plog_fecha_creacion AS 'fecha_log',
                POR.ac_plog_rfid AS 'RFID_CONTROLADORA',
                PO.nombre_portal AS 'nombre_controladora',
                A.id_articulo AS id,
                A.tag_serie AS tag,
                A.tag_unique AS RFID,
                A.serie,
                A.descripcion AS nom,
                A.modelo,
                A.imagen,
                A.observaciones AS observacion,
                A.fecha_referencia AS fecha_in,
                A.fecha_baja,
                L.id_localizacion AS IDL,
                L.denominacion AS localizacion,
                P.th_per_id AS IDC,
                CONCAT ( P.th_per_primer_apellido, ' ', P.th_per_segundo_apellido, ' ', P.th_per_primer_nombre, ' ', P.th_per_segundo_nombre ) AS custodio,
                M.descripcion AS marca,
                E.descripcion AS estado,
                G.descripcion AS genero,
                C.descripcion AS color,
                TA.descripcion AS tipo_articulo 
                FROM
                ac_portales_logs POR
                LEFT JOIN ac_portales PO ON POR.ac_plog_controladora = PO.id_portal
                LEFT JOIN ac_articulos A ON POR.ac_plog_rfid = A.tag_unique
                LEFT JOIN ac_localizacion L ON A.id_localizacion = L.id_localizacion
                LEFT JOIN th_personas P ON A.th_per_id = P.th_per_id
                LEFT JOIN ac_marcas M ON A.id_marca = M.id_marca
                LEFT JOIN ac_estado E ON A.id_estado = E.id_estado
                LEFT JOIN ac_genero G ON A.id_genero = G.id_genero
                LEFT JOIN ac_colores C ON A.id_color = C.id_colores
                LEFT JOIN ac_cat_tipo_articulo TA ON A.id_tipo_articulo = TA.id_tipo_articulo 
                ORDER BY
                POR.ac_plog_fecha_creacion DESC;";

        $datos = $this->db->datos($sql);
        return $datos;
    }
}
