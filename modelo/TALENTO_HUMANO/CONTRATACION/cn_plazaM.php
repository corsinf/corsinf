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
}