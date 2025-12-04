<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_departamentosM extends BaseModel
{
    protected $tabla = 'th_departamentos';
    protected $primaryKey = 'th_dep_id AS _id';

    protected $camposPermitidos = [
        'th_dep_nombre AS nombre',
        'th_dep_desactivar_ADE AS desactivar_ADE',
        'th_dep_contingencia AS contingencia',
        'th_dep_tiempo_maximo_dentro AS tiempo_maximo_dentro',
        'th_dept_id AS tipo_id',
        'th_dep_estado AS estado',
        'th_dep_fecha_creacion AS fecha_creacion',
        'th_dep_fecha_modificacion AS fecha_modificacion',
    ];

    function listar_departamentos_contar_personas()
    {
        $sql =
            "SELECT
                dep.th_dep_id AS _id,
                dep.th_dep_nombre AS nombre,
                COUNT ( per_dep.th_per_id ) AS total_personas 
            FROM th_departamentos dep
            LEFT JOIN th_personas_departamentos per_dep ON per_dep.th_dep_id = dep.th_dep_id 
            WHERE dep.th_dep_estado = 1 
            GROUP BY
            dep.th_dep_id,
            dep.th_dep_nombre;";

        $datos = $this->db->datos($sql);
        return $datos;
    }


function obtener_departamento_completo($dep_id)
{
    $dep_id = intval($dep_id);

    $sql = "
        SELECT
            d.th_dep_id AS departamento_id,
            d.th_dep_nombre AS departamento_nombre,
            (
                SELECT
                    c.th_car_id,
                    c.th_car_nombre,
                    c.th_car_descripcion,
                    c.th_car_estado,
                    c.th_niv_id,
                    (
                        SELECT
                            ai.th_carasp_id,
                            ai.th_carasp_nivel_cargo,
                            ai.th_carasp_subordinacion,
                            ai.th_carasp_subordinacion_id,
                            ai.th_carasp_supervision,
                            ai.th_carasp_supervision_id,
                            ai.th_carasp_comunicaciones_colaterales,
                            ai.th_carasp_comunicaciones_id,
                            ai.th_carasp_estado
                        FROM th_contr_cargo_aspectos_intrinsecos ai
                        WHERE ai.th_car_id = c.th_car_id
                        FOR JSON PATH
                    ) AS aspectos_json
                FROM th_contr_cargos c
                WHERE c.th_dep_id = d.th_dep_id
                  AND c.th_car_estado = 1
                FOR JSON PATH
            ) AS cargos_json
        FROM th_departamentos d
        WHERE d.th_dep_estado = 1
          AND d.th_dep_id = {$dep_id};
    ";

    $resultado = $this->db->datos($sql);
    
    // Normalizar los datos JSON anidados
    if (!empty($resultado)) {
        foreach ($resultado as &$departamento) {
            // Decodificar cargos_json
            if (isset($departamento['cargos_json']) && is_string($departamento['cargos_json'])) {
                $departamento['cargos'] = json_decode($departamento['cargos_json'], true);
                unset($departamento['cargos_json']);
                
                // Decodificar aspectos_json dentro de cada cargo
                if (is_array($departamento['cargos'])) {
                    foreach ($departamento['cargos'] as &$cargo) {
                        if (isset($cargo['aspectos_json']) && is_string($cargo['aspectos_json'])) {
                            $cargo['aspectos'] = json_decode($cargo['aspectos_json'], true);
                            unset($cargo['aspectos_json']);
                        }
                    }
                }
            }
        }
    }
    
    return $resultado;
}


function obtener_departamento_cargos_personas($dep_id)
{
    $dep_id = intval($dep_id);

    $sql = "
        SELECT
            d.th_dep_id AS departamento_id,
            d.th_dep_nombre AS departamento_nombre,
            (
                SELECT
                    c.th_car_id,
                    c.th_car_nombre,
                    c.th_car_descripcion,
                    c.th_car_estado,
                    c.th_niv_id,

                    -- PERSONAS EN CADA CARGO
                    (
                        SELECT
                        
                            p.th_per_id,
                            p.th_per_nombres_completos AS nombre_completo,
                            p.th_per_cedula AS cedula,
                            RTRIM(
                            CONCAT(
                                COALESCE(p.th_per_primer_apellido, ''), ' ',
                                COALESCE(p.th_per_segundo_apellido, ''), ' ',
                                COALESCE(p.th_per_primer_nombre, ''), ' ',
                                COALESCE(p.th_per_segundo_nombre, '')
                            )
                        ) AS nombres_completos
                        FROM th_personas p
                        WHERE p.th_car_id = c.th_car_id
                          AND p.th_per_estado = 1
                        FOR JSON PATH
                    ) AS personas_json

                FROM th_contr_cargos c
                WHERE c.th_dep_id = d.th_dep_id
                  AND c.th_car_estado = 1
                FOR JSON PATH
            ) AS cargos_json
        FROM th_departamentos d
        WHERE d.th_dep_estado = 1
          AND d.th_dep_id = {$dep_id};
    ";

    $resultado = $this->db->datos($sql);

    // DECODIFICACIÓN JSON
    if (!empty($resultado)) {
        foreach ($resultado as &$departamento) {

            // CARGOS
            if (isset($departamento['cargos_json']) && is_string($departamento['cargos_json'])) {
                $departamento['cargos'] = json_decode($departamento['cargos_json'], true);
                unset($departamento['cargos_json']);

                // PERSONAS dentro de cada cargo
                foreach ($departamento['cargos'] as &$cargo) {
                    if (isset($cargo['personas_json']) && is_string($cargo['personas_json'])) {
                        $cargo['personas'] = json_decode($cargo['personas_json'], true);
                        unset($cargo['personas_json']);
                    } else {
                        $cargo['personas'] = [];
                    }
                }
            }
        }
    }

    return $resultado;
}




    
}