<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class fi_personas_solicitudesM extends BaseModel
{
    protected $tabla = 'fi_personas_solicitudes';
    protected $primaryKey = 'fi_sol_id AS _id';

    protected $camposPermitidos = [
        'th_per_id AS persona_id',
        'fi_tfo_id AS CFomulario_id',
        'fi_sol_razon_social AS razon_social',
        'fi_sol_identificacion AS identificacion',
        'fi_sol_tiempo AS tiempo',
        'fi_sol_direccion_ruc_juridico AS direccion_ruc_juridico',
        'fi_sol_correo_empresarial AS correo_empresarial',
        'fi_sol_realizado AS realizado',
        'fi_sol_realizado_2 AS realizado_2',
        'fi_sol_archivo_foto AS archivo_foto',
        'fi_sol_archivo_cedula AS archivo_cedula',
        'fi_sol_archivo_ruc AS archivo_ruc',
        'fi_sol_archivo_juridico AS archivo_juridico',
        'fi_sol_estado AS estado',
        'fi_sol_fecha_creacion AS fecha_creacion',
        'fi_sol_fecha_modificacion AS fecha_modificacion',
    ];

    function listar_join($id_persona, $id_solicitud = '')
    {
        $sql =
            "SELECT
                fps.fi_sol_id AS _id,
                per.th_per_primer_nombre AS primer_nombre,
                per.th_per_segundo_nombre AS segundo_nombre,
                per.th_per_primer_apellido AS primer_apellido,
                per.th_per_segundo_apellido AS segundo_apellido,
                CONCAT(per.th_per_primer_nombre, ' ' ,per.th_per_segundo_nombre, ' ' ,per.th_per_primer_apellido, ' ' ,per.th_per_segundo_apellido) AS nombres_completos,
                per.th_per_cedula AS cedula,
                per.th_per_correo AS correo,
                per.th_per_telefono_1 AS telefono_1,
                per.th_per_fecha_creacion AS fecha_creacion,
                tfo.fi_tfo_nombre AS nombre_solicitud
            FROM 
                fi_personas_solicitudes fps
            INNER JOIN 
                th_personas per ON fps.th_per_id = per.th_per_id
            INNER JOIN 
                fi_cat_formularios tfo ON fps.fi_tfo_id = tfo.fi_tfo_id";

        if ($id_persona != '') {
            $sql .= " WHERE 
                fps.th_per_id = '$id_persona'";
        }

        if ($id_solicitud != '') {
            $sql .= " AND fps.fi_sol_id = '$id_solicitud'";
        }

        $sql .= " ORDER BY 
                fps.fi_sol_id DESC;";

        $datos = $this->db->datos($sql);
        return $datos;
    }

    function listar_join_pdf($id_persona, $id_solicitud = '')
    {
        $sql =
            "SELECT 
                per.th_per_primer_nombre AS primer_nombre,
                per.th_per_segundo_nombre AS segundo_nombre,
                per.th_per_primer_apellido AS primer_apellido,
                per.th_per_segundo_apellido AS segundo_apellido,
                per.th_per_cedula AS cedula,
                per.th_per_estado_civil AS estado_civil,
                per.th_per_sexo AS sexo,
                per.th_per_fecha_nacimiento AS fecha_nacimiento,
                per.th_per_nacionalidad AS nacionalidad,
                per.th_per_telefono_1 AS telefono_1,
                per.th_per_telefono_2 AS telefono_2,
                per.th_per_correo AS correo,
                per.th_per_direccion AS direccion,
                per.th_per_foto_url AS foto_url,
                per.th_prov_id AS id_provincia,
                per.th_ciu_id AS id_ciudad,
                per.th_parr_id AS id_parroquia,
                per.th_per_postal AS postal,
                per.th_per_observaciones AS observaciones,
                per.th_per_fecha_creacion AS fecha_creacion,
                CONCAT(per.th_per_primer_nombre, ' ' ,per.th_per_segundo_nombre, ' ' ,per.th_per_primer_apellido, ' ' ,per.th_per_segundo_apellido) AS nombres_completos,

                fps.fi_sol_id AS _id,
                fps.fi_sol_razon_social AS razon_social,
                fps.fi_sol_identificacion AS identificacion,
                fps.fi_sol_tiempo AS tiempo,
                fps.fi_sol_direccion_ruc_juridico AS direccion_ruc_juridico,
                fps.fi_sol_correo_empresarial AS correo_empresarial,
                fps.fi_tfo_id AS CFomulario_id,

                tfo.fi_tfo_nombre AS nombre_solicitud,

                pro.th_prov_nombre AS provincia,
                ciu.th_ciu_nombre AS ciudad,
                parr.th_parr_nombre AS parroquia

            FROM 
                fi_personas_solicitudes fps
            INNER JOIN 
                th_personas per ON fps.th_per_id = per.th_per_id
            INNER JOIN 
                fi_cat_formularios tfo ON fps.fi_tfo_id = tfo.fi_tfo_id
            LEFT JOIN 
                th_provincias pro ON per.th_prov_id = pro.th_prov_id
            LEFT JOIN 
                th_ciudad ciu ON per.th_ciu_id = ciu.th_ciu_id
            LEFT JOIN 
                th_parroquias parr ON per.th_parr_id = parr.th_parr_id
            WHERE 1 = 1";

        if ($id_persona != '') {
            $sql .= " AND fps.th_per_id = '$id_persona'";
        }

        if ($id_solicitud != '') {
            $sql .= " AND fps.fi_sol_id = '$id_solicitud'";
        }

        $sql .= " ORDER BY 
                fps.fi_sol_id DESC;";

        $datos = $this->db->datos($sql);
        return $datos;
    }
}
