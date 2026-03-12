<?php
require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class EMPLEADOSM extends BaseModel
{
    protected $tabla = 'EMPLEADOS';
    protected $primaryKey = 'id_empleado AS _id';

    protected $camposPermitidos = [
        'th_per_id',
        'PERFIL',
        'PASS',
        'POLITICAS_ACEPTACION',
        'DELETE_LOGIC',
    ];

    function listar_empleados_eliminados()
    {
        $sql = "
            SELECT
                emp.id_empleado AS _id,
                emp.th_per_id AS id_persona,
                emp.PERFIL AS perfil,
                emp.POLITICAS_ACEPTACION AS politicas_aceptacion,
                emp.DELETE_LOGIC AS delete_logic,
                per.th_per_primer_apellido AS primer_apellido,
                per.th_per_segundo_apellido AS segundo_apellido,
                per.th_per_primer_nombre AS primer_nombre,
                per.th_per_segundo_nombre AS segundo_nombre,
                per.th_per_cedula AS cedula,
                per.th_per_correo AS correo,
                per.th_per_telefono_1 AS telefono_1,
                ISNULL(dep.th_dep_nombre, 'SIN DEPARTAMENTO') AS nombre_departamento,
                per.th_per_fecha_creacion AS fecha_creacion
            FROM EMPLEADOS emp
            INNER JOIN th_personas per ON emp.th_per_id = per.th_per_id
            LEFT JOIN th_personas_departamentos per_dep ON per.th_per_id = per_dep.th_per_id
            LEFT JOIN th_departamentos dep ON per_dep.th_dep_id = dep.th_dep_id
            WHERE emp.DELETE_LOGIC = 1;
        ";

        $datos = $this->db->datos($sql);
        return $datos;
    }
   
}
