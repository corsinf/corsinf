<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class cn_plazaM extends BaseModel
{
    protected $tabla = 'cn_plaza';
    protected $primaryKey = 'cn_pla_id AS _id';
    protected $camposPermitidos = [
        'cn_pla_titulo',
        'id_cargo',
        'cn_pla_descripcion',
        'id_tipo_seleccion',
        'cn_pla_num_vacantes',
        'cn_pla_fecha_publicacion',
        'cn_pla_fecha_cierre',
        'th_dep_id',
        'cn_pla_req_disponibilidad',
        'cn_pla_salario_min',
        'cn_pla_salario_max',
        'id_nomina',
        'cn_pla_req_prioridad_interna',
        'cn_pla_req_documentos',
        'th_per_id_responsable',
        'cn_pla_observaciones',
        'cn_pla_estado',
        'cn_pla_fecha_creacion',
        'cn_pla_fecha_modificacion',
    ];

    public function listar_plaza_por_id($id)
    {
        $id = intval($id);
        $sql = "
            SELECT
                p.cn_pla_id AS _id,
                p.cn_pla_titulo,
                p.id_cargo,
                p.cn_pla_descripcion,
                p.id_tipo_seleccion,
                p.cn_pla_num_vacantes,
                p.cn_pla_fecha_publicacion,
                p.cn_pla_fecha_cierre,
                p.th_dep_id,
                p.cn_pla_req_disponibilidad,
                p.cn_pla_salario_min,
                p.cn_pla_salario_max,
                p.id_nomina,
                p.cn_pla_req_prioridad_interna,
                p.cn_pla_req_documentos,
                p.th_per_id_responsable,
                p.cn_pla_observaciones,
                p.cn_pla_estado,
                p.cn_pla_fecha_creacion,
                p.cn_pla_fecha_modificacion,
                c.nombre AS descripcion_cargo,
                d.th_dep_nombre AS descripcion_departamento,
                ts.descripcion AS descripcion_tipo_seleccion,
                n.nombre AS descripcion_nomina,
                per.th_per_cedula AS per_cedula,
                per.th_per_nombres_completos AS per_nombre_completo
            FROM cn_plaza p
            LEFT JOIN th_cat_cargo c ON p.id_cargo = c.id_cargo
            LEFT JOIN th_departamentos d ON p.th_dep_id = d.th_dep_id
            LEFT JOIN cn_cat_tipo_seleccion ts ON p.id_tipo_seleccion = ts.id_tipo_seleccion
            LEFT JOIN th_cat_nomina n ON p.id_nomina = n.id_nomina
            LEFT JOIN th_personas per ON p.th_per_id_responsable = per.th_per_id
            WHERE p.cn_pla_id = $id
        ";
        return $this->db->datos($sql);
    }


    public function listar_plaza_resumen($id)
    {
        $id = intval($id);
        $sql = "
        SELECT
            p.cn_pla_id                     AS _id,
            p.cn_pla_titulo,
            p.id_cargo,
            p.cn_pla_descripcion,
            p.id_tipo_seleccion,
            p.cn_pla_num_vacantes,
            p.cn_pla_fecha_publicacion,
            p.cn_pla_fecha_cierre,
            p.th_dep_id,
            p.cn_pla_req_disponibilidad,
            p.cn_pla_salario_min,
            p.cn_pla_salario_max,
            p.id_nomina,
            p.cn_pla_req_prioridad_interna,
            p.cn_pla_req_documentos,
            p.th_per_id_responsable,
            p.cn_pla_observaciones,
            p.cn_pla_estado,
            p.cn_pla_fecha_creacion,
            p.cn_pla_fecha_modificacion,
            c.nombre                        AS descripcion_cargo,
            d.th_dep_nombre                 AS descripcion_departamento,
            ts.descripcion                  AS descripcion_tipo_seleccion,
            n.nombre                        AS descripcion_nomina,
            per.th_per_cedula               AS per_cedula,
            per.th_per_nombres_completos    AS per_nombre_completo,

            (SELECT STRING_AGG(rrd.descripcion, '||') WITHIN GROUP (ORDER BY rrd.descripcion ASC)
             FROM cn_plaza_reqr_responsabilidades rr
             LEFT JOIN th_cat_reqr_responsabilidades_detalle rrd ON rrd.id_req_res_det = rr.id_req_res_det
             WHERE rr.cn_pla_id = p.cn_pla_id AND rr.cn_reqr_estado = 1)
                                            AS responsabilidades,

            (SELECT STRING_AGG(na.descripcion, '||') WITHIN GROUP (ORDER BY na.descripcion ASC)
             FROM cn_plaza_reqi_instruccion ri
             LEFT JOIN th_cat_pos_nivel_academico na ON na.id_nivel_academico = ri.id_nivel_academico
             WHERE ri.cn_pla_id = p.cn_pla_id AND ri.cn_reqi_estado = 1)
                                            AS instruccion,

            (SELECT STRING_AGG(ae_cat.descripcion, '||') WITHIN GROUP (ORDER BY ae_cat.descripcion ASC)
             FROM cn_plaza_reqi_area_estudio ae
             LEFT JOIN th_cat_area_estudio ae_cat ON ae_cat.id_area_estudio = ae.id_area_estudio
             WHERE ae.cn_pla_id = p.cn_pla_id AND ae.cn_reqia_estado = 1)
                                            AS area_estudio,

            (SELECT STRING_AGG(CONCAT(rp.nombre, '|', ISNULL(CAST(rp.min_anios_exp AS VARCHAR),''), '|', ISNULL(CAST(rp.max_anios_exp AS VARCHAR),'')), '||') WITHIN GROUP (ORDER BY rp.min_anios_exp ASC)
             FROM cn_plaza_reqi_experiencia re
             LEFT JOIN th_cat_rango_profesional rp ON rp.id_rango_profesional = re.id_rango_profesional
             WHERE re.cn_pla_id = p.cn_pla_id AND re.cn_reqe_estado = 1)
                                            AS experiencia,

            (SELECT STRING_AGG(CONCAT(idi.descripcion, '|', ISNULL(idin.descripcion,'')), '||') WITHIN GROUP (ORDER BY idi.descripcion ASC)
             FROM cn_plaza_reqi_idiomas ri2
             LEFT JOIN th_cat_idiomas idi ON idi.id_idiomas = ri2.id_idiomas
             LEFT JOIN th_cat_idiomas_nivel idin ON idin.id_idiomas_nivel = ri2.id_idiomas_nivel
             WHERE ri2.cn_pla_id = p.cn_pla_id AND ri2.cn_reqid_estado = 1)
                                            AS idiomas,

            (SELECT STRING_AGG(hh.th_hab_nombre, '||') WITHIN GROUP (ORDER BY hh.th_hab_nombre ASC)
             FROM cn_plaza_reqi_aptitud ra
             LEFT JOIN th_cat_habilidades hh ON hh.th_hab_id = ra.cn_hab_id
             WHERE ra.cn_pla_id = p.cn_pla_id AND ra.cn_reqa_estado = 1 AND hh.th_tiph_id = 1)
                                            AS habilidades_tecnicas,

            (SELECT STRING_AGG(hh.th_hab_nombre, '||') WITHIN GROUP (ORDER BY hh.th_hab_nombre ASC)
             FROM cn_plaza_reqi_aptitud ra
             LEFT JOIN th_cat_habilidades hh ON hh.th_hab_id = ra.cn_hab_id
             WHERE ra.cn_pla_id = p.cn_pla_id AND ra.cn_reqa_estado = 1 AND hh.th_tiph_id = 2)
                                            AS habilidades_blandas,

            (SELECT STRING_AGG(ci.descripcion, '||') WITHIN GROUP (ORDER BY ci.descripcion ASC)
             FROM cn_plaza_reqi_iniciativa ini
             LEFT JOIN th_cat_reqi_iniciativa ci ON ci.id_req_iniciativa = ini.id_req_iniciativa
             WHERE ini.cn_pla_id = p.cn_pla_id AND ini.cn_reqini_estado = 1)
                                            AS iniciativas,

            (SELECT STRING_AGG(td.descripcion, '||') WITHIN GROUP (ORDER BY td.descripcion ASC)
             FROM cn_plaza_reqct_trabajo pt
             LEFT JOIN th_cat_reqct_trabajo_detalle td ON td.id_req_trabajo = pt.id_req_trabajo
             WHERE pt.cn_pla_id = p.cn_pla_id AND pt.cn_reqct_estado = 1)
                                            AS condiciones_trabajo,

            (SELECT STRING_AGG(rrd2.descripcion, '||') WITHIN GROUP (ORDER BY rrd2.descripcion ASC)
             FROM cn_plaza_reqct_riesgos pr
             LEFT JOIN th_cat_reqct_riesgos_detalle rrd2 ON rrd2.id_req_riesgo = pr.id_req_riesgo
             WHERE pr.cn_pla_id = p.cn_pla_id AND pr.cn_reqr_estado = 1)
                                            AS riesgos,

            (SELECT STRING_AGG(CONCAT(rfc.descripcion, '|', rfd.descripcion), '||') WITHIN GROUP (ORDER BY rfc.descripcion ASC, rfd.descripcion ASC)
             FROM cn_plaza_reqf_fisicos rf
             LEFT JOIN th_cat_reqf_fisicos_detalle rfd ON rfd.id_req_fisico_det = rf.id_req_fisico_det
             LEFT JOIN th_cat_reqf_fisicos rfc ON rfc.id_req_fisico = rfd.id_req_fisico
             WHERE rf.cn_pla_id = p.cn_pla_id AND rf.cn_reqf_estado = 1)
                                            AS requisitos_fisicos

        FROM cn_plaza p
            LEFT JOIN th_cat_cargo          c   ON p.id_cargo              = c.id_cargo
            LEFT JOIN th_departamentos      d   ON p.th_dep_id             = d.th_dep_id
            LEFT JOIN cn_cat_tipo_seleccion ts  ON p.id_tipo_seleccion     = ts.id_tipo_seleccion
            LEFT JOIN th_cat_nomina         n   ON p.id_nomina             = n.id_nomina
            LEFT JOIN th_personas           per ON p.th_per_id_responsable = per.th_per_id
        WHERE p.cn_pla_id = $id
    ";

        return $this->db->datos($sql);
    }
}
