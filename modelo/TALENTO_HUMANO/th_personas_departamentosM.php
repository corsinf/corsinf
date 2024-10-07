<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_personas_departamentosM extends BaseModel
{
    protected $tabla = 'th_personas_departamentos';
    protected $primaryKey = 'th_perdep_id AS _id';

    protected $camposPermitidos = [
        'th_per_id AS id_persona',
        'th_dep_id AS id_departamento',
        'th_perdep_visitor AS visitor',
        'th_perdep_fecha_creacion AS fecha_creacion',
    ];

    function listar_personas_departamentos($id_departamento)
    {
        if ($id_departamento != '' && $id_departamento != null) {
            $sql =
                "SELECT
                    per_dep.th_perdep_id AS _id,
                    per.th_per_id AS id_persona,
                    per.th_per_primer_apellido AS primer_apellido,
                    per.th_per_segundo_apellido AS segundo_apellido,
                    per.th_per_primer_nombre AS primer_nombre,
                    per.th_per_segundo_nombre AS segundo_nombre,
                    per.th_per_cedula AS cedula,
                    per.th_per_telefono_1 AS telefono_1,
                    per.th_per_correo AS correo
                FROM
                th_personas_departamentos per_dep
                INNER JOIN th_personas per ON per_dep.th_per_id = per.th_per_id 
                WHERE
                per_dep.th_dep_id = '$id_departamento';";

            $datos = $this->db->datos($sql);
            return $datos;
        }
        return null;
    }

    //Sirve para listar al gruepo de personas que no estan en el departamento
    function listar_personas_modal($id_departamento)
    {
        if ($id_departamento != '' && $id_departamento != null) {
            $sql =
                "SELECT
                    per.th_per_id AS _id,
                    per.th_per_primer_apellido AS primer_apellido,
                    per.th_per_segundo_apellido AS segundo_apellido,
                    per.th_per_primer_nombre AS primer_nombre,
                    per.th_per_segundo_nombre AS segundo_nombre,
                    per.th_per_cedula AS cedula,
                    per.th_per_telefono_1 AS telefono_1,
                    per.th_per_correo AS correo
                FROM
                    th_personas per
                LEFT JOIN
                    th_personas_departamentos per_dep
                ON
                    per.th_per_id = per_dep.th_per_id
                AND 
                    per_dep.th_dep_id = '$id_departamento'
                WHERE
                    per_dep.th_perdep_id IS NULL;";

            $datos = $this->db->datos($sql);
            return $datos;
        }
        return null;
    }
}
