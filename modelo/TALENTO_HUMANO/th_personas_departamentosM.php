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
        $sql =
            "SELECT
                    per_dep.th_perdep_id AS _id,
                    per.th_per_id AS id_persona,
                    per.th_per_id_comunidad AS id_comunidad,
                    per.th_per_primer_apellido AS primer_apellido,
                    per.th_per_segundo_apellido AS segundo_apellido,
                    per.th_per_primer_nombre AS primer_nombre,
                    per.th_per_segundo_nombre AS segundo_nombre,
                    per.th_per_cedula AS cedula,
                    per.th_per_telefono_1 AS telefono_1,
                    per.th_per_correo AS correo,
                    per.th_pos_id AS _id_postulante,
                    dep.th_dep_nombre AS nombre_departamento,
                    per.th_per_fecha_creacion AS fecha_creacion
                FROM
                th_personas_departamentos per_dep
                INNER JOIN th_personas per ON per_dep.th_per_id = per.th_per_id 
                INNER JOIN th_departamentos dep ON per_dep.th_dep_id = dep.th_dep_id
                WHERE per.th_per_estado = 1 ";

        if ($id_departamento != '' && $id_departamento != null && $id_departamento != ' ') {
            $sql .= " AND per_dep.th_dep_id = '$id_departamento'";
        }

        $sql .= ";";

        $datos = $this->db->datos($sql);
        return $datos;
    }

    //Sirve para listar al gruepo de personas que no estan en el departamento
    function listar_personas_modal($id_departamento)
    {
        if (!empty($id_departamento)) {
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
                    _talentoh.th_personas per
                WHERE
                    per.th_per_id NOT IN (
                        SELECT th_per_id
                        FROM _talentoh.th_personas_departamentos
                    ) AND per.th_per_estado = 1;";

            $datos = $this->db->datos($sql, false, false, true);
            return $datos;
        }
        return null;
    }


    function listar_buscar_persona_departamento($id_persona)
    {
        if (!empty($id_persona)) {
            $id_persona = intval($id_persona); // por seguridad

            $sql = "
            SELECT
                per_dep.th_perdep_id AS _id_perdep,
                per.th_per_id AS id_persona,
                per.th_per_primer_apellido AS primer_apellido,
                per.th_per_segundo_apellido AS segundo_apellido,
                per.th_per_primer_nombre AS primer_nombre,
                per.th_per_segundo_nombre AS segundo_nombre,
                per.th_per_estado_civil AS estado_civil,
                per.th_per_sexo AS sexo,
                per.th_per_cedula AS cedula,
                per.th_per_telefono_1 AS telefono_1,
                per.th_per_correo AS correo,
                ISNULL(dep.th_dep_nombre, 'SIN DEPARTAMENTO') AS nombre_departamento,
                ISNULL(per_dep.th_dep_id, 0) AS id_departamento
            FROM
                th_personas per
            LEFT JOIN th_personas_departamentos per_dep ON per.th_per_id = per_dep.th_per_id
            LEFT JOIN th_departamentos dep ON per_dep.th_dep_id = dep.th_dep_id
            WHERE
                per.th_per_id = $id_persona;
        ";

            $datos = $this->db->datos($sql);

            return $datos;
        }

        return null;
    }


    public function obtener_correo_y_password($id_usuario)
    {
        $id_usuario = intval($id_usuario);

        $sql = "
        SELECT
            email,
            password
        FROM USUARIOS
        WHERE id_usuarios = $id_usuario
        AND estado = 'A'
    ";

        $resultado = $this->db->datos($sql);

        return $resultado;
    }
}
