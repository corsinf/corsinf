<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_contr_plaza_cargoM extends BaseModel
{
    protected $tabla = 'th_contr_plaza_cargo';

    protected $primaryKey = 'th_pc_id AS _id';
    
    protected $camposPermitidos = [
        'th_pla_id AS plaza_id',
        'th_car_id AS cargo_id',
        'th_pc_cantidad AS cantidad',
        'th_pc_salario_ofertado AS salario_ofertado',
        'th_pc_estado AS estado',
        'th_pc_fecha_creacion AS fecha_creacion',
        'th_pc_fecha_modificacion AS fecha_modificacion'
    ];


    public function listar_plaza_cargo($id_pc = '')
{
    $sql = "
        SELECT
            pc.th_pc_id AS _id,
            pc.th_pla_id AS plaza_id,
            pc.th_car_id AS cargo_id,
            pc.th_pc_cantidad AS cantidad,
            pc.th_pc_salario_ofertado AS salario_ofertado,
            pc.th_pc_estado AS estado,
            pc.th_pc_fecha_creacion AS fecha_creacion,
            pc.th_pc_fecha_modificacion AS fecha_modificacion,
            c.th_car_id AS cargo_th_car_id,
            c.th_car_nombre AS cargo_nombre,
            c.th_car_descripcion AS cargo_descripcion,
            c.th_car_nivel AS cargo_nivel,
            c.th_car_area AS cargo_area,
            c.th_car_estado AS cargo_estado,
            c.th_car_fecha_creacion AS cargo_fecha_creacion,
            c.th_car_fecha_modificacion AS cargo_fecha_modificacion,
            p.th_pla_id AS plaza_th_pla_id,
            p.th_pla_titulo AS plaza_titulo,
            p.th_pla_descripcion AS plaza_descripcion,
            p.th_pla_tipo AS plaza_tipo,
            p.th_pla_num_vacantes AS plaza_num_vacantes,
            p.th_pla_fecha_publicacion AS plaza_fecha_publicacion,
            p.th_pla_fecha_cierre AS plaza_fecha_cierre,
            p.th_pla_jornada_id AS plaza_jornada_id,
            p.th_pla_salario_min AS plaza_salario_min,
            p.th_pla_salario_max AS plaza_salario_max,
            p.th_pla_tiempo_contrato AS plaza_tiempo_contrato,
            p.th_pla_prioridad_interna AS plaza_prioridad_interna,
            p.th_pla_requiere_documentos AS plaza_requiere_documentos,
            p.th_pla_responsable_persona_id AS plaza_responsable_persona_id,
            p.th_pla_observaciones AS plaza_observaciones,
            p.th_pla_estado AS plaza_estado,
            p.th_pla_fecha_creacion AS plaza_fecha_creacion,
            p.th_pla_fecha_modificacion AS plaza_fecha_modificacion
        FROM
            th_contr_plaza_cargo pc
        INNER JOIN th_contr_cargos c ON pc.th_car_id = c.th_car_id
        INNER JOIN th_contr_plazas p ON pc.th_pla_id = p.th_pla_id
        WHERE pc.th_pc_estado = 1
    ";

    if ($id_pc !== '') {
        $id = (int) $id_pc;
        $sql .= " AND pc.th_pc_id = {$id}";
    }

    $sql .= " ORDER BY pc.th_pc_fecha_creacion DESC";

    $datos = $this->db->datos($sql);
    return $datos;
}


}