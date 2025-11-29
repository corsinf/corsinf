<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_contr_cargosM extends BaseModel
{
    // Nombre real de la tabla en la base de datos
    protected $tabla = 'th_contr_cargos';

    // Clave primaria
    protected $primaryKey = 'th_car_id AS _id';

    // Campos que puedes insertar o actualizar
  protected $camposPermitidos = [
    'th_car_nombre AS nombre',
    'th_car_descripcion AS descripcion',
    'th_dep_id AS dep_id',
    'th_niv_id AS niv_id',
    'th_car_estado AS estado',
    'th_car_fecha_creacion AS fecha_creacion',
    'th_car_fecha_modificacion AS fecha_modificacion'
];

function listar_cargos_con_departamentos($id_cargo = null)
{
    $filtro = "";
    if ($id_cargo !== null && $id_cargo !== '') {
        $id_cargo = intval($id_cargo);
        $filtro = " AND c.th_car_id = {$id_cargo}";
    }

    $sql = "
        SELECT
            c.th_car_id AS _id,
            c.th_car_nombre AS nombre,
            c.th_car_descripcion AS descripcion,
            n.th_niv_nombre AS nivel,
            n.th_niv_id,
            c.th_car_estado,
            c.th_car_fecha_creacion,
            c.th_car_fecha_modificacion,
            c.th_dep_id,
            d.th_dep_nombre AS departamento
        FROM th_contr_cargos c
        LEFT JOIN th_departamentos d ON c.th_dep_id = d.th_dep_id
        LEFT JOIN th_contr_niveles_cargo n ON c.th_niv_id = n.th_niv_id
        WHERE c.th_car_estado = 1 {$filtro}
        ORDER BY c.th_car_fecha_creacion DESC;
    ";

    return $this->db->datos($sql);
}



}