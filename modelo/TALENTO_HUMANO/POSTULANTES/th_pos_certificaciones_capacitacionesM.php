<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_pos_certificaciones_capacitacionesM extends BaseModel
{
    protected $tabla = 'th_pos_certificaciones_capacitaciones';
    protected $primaryKey = 'th_cert_id AS _id';

    protected $camposPermitidos = [
        'th_cert_id',
        'th_pos_id',
        'th_cert_nombre_curso',
        'th_cert_ruta_archivo',
        'th_cert_estado',
        'th_cert_fecha_creacion',
        'th_cert_fecha_modificacion',
        'th_cert_duracion_horas',
        'th_cert_fecha_desde',
        'th_cert_fecha_hasta',
        'id_certificado',
        'id_evento_cert',
        'id_pais',
        'th_cert_sigue_cursando',
        'th_cert_institucion_auspiciante',
    ];

    public function listar_certificaciones_postulante($th_pos_id = null, $th_cert_id = null)
    {
        $sql = "
    SELECT 
        t.th_cert_id AS _id,
        t.th_pos_id,
        t.th_cert_nombre_curso,
        t.th_cert_ruta_archivo,
        t.th_cert_estado AS estado,
        t.th_cert_duracion_horas,
        t.th_cert_fecha_desde,
        t.th_cert_fecha_hasta,
        t.th_cert_sigue_cursando,
        t.id_pais,
        t.th_cert_institucion_auspiciante,
        p.nombre AS nombre_pais,
        t.id_evento_cert,
        ec.descripcion AS nombre_evento_certificado,
        t.id_certificado,
        tc.descripcion AS nombre_certificado 
    FROM th_pos_certificaciones_capacitaciones t
    INNER JOIN th_cat_pais p 
        ON t.id_pais = p.id_pais
    INNER JOIN th_cat_tipo_evento_certificado ec 
        ON t.id_evento_cert = ec.id_evento_cert
    INNER JOIN th_cat_tipo_certificado tc 
        ON t.id_certificado = tc.id_certificado
    WHERE t.th_cert_estado = 1
    ";

        // Filtro por ID de Postulante (listado de la tabla)
        if (!empty($th_pos_id)) {
            $th_pos_id = intval($th_pos_id);
            $sql .= " AND t.th_pos_id = $th_pos_id";
        }

        // Filtro por ID de Certificación (para cargar el modal de edición)
        if (!empty($th_cert_id)) {
            $th_cert_id = intval($th_cert_id);
            $sql .= " AND t.th_cert_id = $th_cert_id";
        }

        $sql .= " ORDER BY t.th_cert_fecha_creacion DESC";

        return $this->db->datos($sql);
    }
}
