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
    $sql = "
        SELECT
            dep.th_dep_id AS _id,
            dep.th_dep_nombre AS nombre,
            COUNT(per.th_per_id) AS total_personas
        FROM th_departamentos dep
        LEFT JOIN th_personas_departamentos per_dep
            ON per_dep.th_dep_id = dep.th_dep_id
        LEFT JOIN th_personas per
            ON per.th_per_id = per_dep.th_per_id
            AND per.th_per_estado = 1
        WHERE dep.th_dep_estado = 1
        GROUP BY
            dep.th_dep_id,
            dep.th_dep_nombre
    ";

    return $this->db->datos($sql);
}



public function obtener_departamento_completo($dep_id)
{
    $dep_id = intval($dep_id);

    $sql = "
        SELECT
            d.th_dep_id AS departamento_id,
            d.th_dep_nombre AS departamento_nombre,
            c.th_car_id,
            c.th_car_nombre,
            c.th_car_descripcion,
            c.th_car_estado,
            c.th_niv_id,
            ai.th_carasp_id,
            ai.th_carasp_nivel_cargo,
            ai.th_carasp_subordinacion,
            ai.th_carasp_subordinacion_id,
            ai.th_carasp_supervision,
            ai.th_carasp_supervision_id,
            ai.th_carasp_comunicaciones_colaterales,
            ai.th_carasp_comunicaciones_id,
            ai.th_carasp_estado
        FROM th_departamentos d
        LEFT JOIN th_contr_cargos c
            ON c.th_dep_id = d.th_dep_id
            AND c.th_car_estado = 1
        LEFT JOIN th_contr_cargo_aspectos_intrinsecos ai
            ON ai.th_car_id = c.th_car_id
            AND ai.th_carasp_estado = 1
        WHERE d.th_dep_estado = 1
          AND d.th_dep_id = {$dep_id}
        ORDER BY c.th_car_nombre, ai.th_carasp_id
    ";

    $rows = $this->db->datos($sql); // usando tu wrapper actual

    // Agrupar filas en estructura anidada
    $departamento = null;
    $cargos_map = []; // key: th_car_id -> index in cargos array

    if (!empty($rows)) {
        foreach ($rows as $r) {
            // Inicializar departamento (solo una fila por dep en la consulta)
            if ($departamento === null) {
                $departamento = [
                    'departamento_id'   => $r['departamento_id'],
                    'departamento_nombre' => $r['departamento_nombre'],
                    'cargos'            => []
                ];
            }

            // Si no hay cargo en esta fila (puede pasar si no hay cargos)
            if (empty($r['th_car_id'])) {
                continue;
            }

            $car_id = $r['th_car_id'];

            // Si el cargo aún no existe en el array, crearlo
            if (!isset($cargos_map[$car_id])) {
                $cargo = [
                    'th_car_id' => $r['th_car_id'],
                    'th_car_nombre' => $r['th_car_nombre'],
                    'th_car_descripcion' => $r['th_car_descripcion'],
                    'th_car_estado' => $r['th_car_estado'],
                    'th_niv_id' => $r['th_niv_id'],
                    'aspectos' => []
                ];
                $departamento['cargos'][] = $cargo;
                $cargos_map[$car_id] = count($departamento['cargos']) - 1;
            }

            // Añadir aspecto si existe
            if (!empty($r['th_carasp_id'])) {
                $aspecto = [
                    'th_carasp_id' => $r['th_carasp_id'],
                    'th_carasp_nivel_cargo' => $r['th_carasp_nivel_cargo'],
                    'th_carasp_subordinacion' => $r['th_carasp_subordinacion'],
                    'th_carasp_subordinacion_id' => $r['th_carasp_subordinacion_id'],
                    'th_carasp_supervision' => $r['th_carasp_supervision'],
                    'th_carasp_supervision_id' => $r['th_carasp_supervision_id'],
                    'th_carasp_comunicaciones_colaterales' => $r['th_carasp_comunicaciones_colaterales'],
                    'th_carasp_comunicaciones_id' => $r['th_carasp_comunicaciones_id'],
                    'th_carasp_estado' => $r['th_carasp_estado']
                ];
                $idx = $cargos_map[$car_id];
                $departamento['cargos'][$idx]['aspectos'][] = $aspecto;
            }
        }
    }

    // Si no se encontró departamento devolver array vacío (igual que antes)
    if ($departamento === null) {
        return [];
    }

    // Devolver como un array de departamentos (coherente con tu versión anterior)
    return [ $departamento ];
}



public function obtener_departamento_cargos_personas($dep_id)
{
    $dep_id = intval($dep_id);

    $sql = "
        SELECT
            d.th_dep_id   AS departamento_id,
            d.th_dep_nombre AS departamento_nombre,
            c.th_car_id,
            c.th_car_nombre,
            c.th_car_descripcion,
            c.th_car_estado,
            c.th_niv_id,
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
        FROM th_departamentos d
        LEFT JOIN th_contr_cargos c
            ON c.th_dep_id = d.th_dep_id
            AND c.th_car_estado = 1
        LEFT JOIN th_personas p
            ON p.th_car_id = c.th_car_id
            AND p.th_per_estado = 1
        WHERE d.th_dep_estado = 1
          AND d.th_dep_id = {$dep_id}
        ORDER BY c.th_car_nombre, p.th_per_nombres_completos;
    ";

    $rows = $this->db->datos($sql);

    // Agrupar filas en estructura anidada
    $departamento = null;
    $cargos_map = []; // key: th_car_id -> index en cargos array

    if (!empty($rows)) {
        foreach ($rows as $r) {
            // Inicializar departamento (solo se hace una vez)
            if ($departamento === null) {
                $departamento = [
                    'departamento_id' => $r['departamento_id'],
                    'departamento_nombre' => $r['departamento_nombre'],
                    'cargos' => []
                ];
            }

            // Si no hay cargo en esta fila (puede pasar si departamento sin cargos)
            if (empty($r['th_car_id'])) {
                continue;
            }

            $car_id = $r['th_car_id'];

            // Si el cargo aún no existe en el array, crearlo
            if (!isset($cargos_map[$car_id])) {
                $cargo = [
                    'th_car_id' => $r['th_car_id'],
                    'th_car_nombre' => $r['th_car_nombre'],
                    'th_car_descripcion' => $r['th_car_descripcion'],
                    'th_car_estado' => $r['th_car_estado'],
                    'th_niv_id' => $r['th_niv_id'],
                    'personas' => []
                ];
                $departamento['cargos'][] = $cargo;
                $cargos_map[$car_id] = count($departamento['cargos']) - 1;
            }

            // Añadir persona si existe
            if (!empty($r['th_per_id'])) {
                $persona = [
                    'th_per_id' => $r['th_per_id'],
                    'nombre_completo' => $r['nombre_completo'],
                    'cedula' => $r['cedula'],
                    'nombres_completos' => $r['nombres_completos']
                ];
                $idx = $cargos_map[$car_id];
                $departamento['cargos'][$idx]['personas'][] = $persona;
            }
        }
    }

    // Si no se encontró departamento, devolver array vacío (coherente con otras funciones)
    if ($departamento === null) {
        return [];
    }

    // Devolver como array de departamentos (mantiene la misma forma que tu versión con JSON)
    return [ $departamento ];
}





    
}