<?php
if (!class_exists('db')) {
    include('../db/db.php');
}
/**
 * 
 */
class cat_configuracionGM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    //Para mostrar todos los registros con campos especificos para la vista principal
    function lista_vista_conf_general()
    {
        $sql =
            "SELECT 
                sa_config_id,
                sa_config_nombre,
                sa_config_descripcion,
                sa_config_validar,
                sa_config_estado,
                sa_config_fecha_creacion

                FROM cat_configuracionG
                WHERE 1 = 1;";

        $datos = $this->db->datos($sql);
        return $datos;
    }

    function editar($datos, $where)
    {
        $rest = $this->db->update('cat_configuracionG', $datos, $where);
        return $rest;
    }

    function validacion($validar)
    {
        $sql =
            "SELECT 
                sa_config_id,
                sa_config_nombre,
                sa_config_descripcion,
                sa_config_validar,
                sa_config_estado,
                sa_config_fecha_creacion

                FROM cat_configuracionG
                WHERE sa_config_validar = '$validar';";

        $datos = $this->db->datos($sql);

        $datos[0]['sa_config_estado'];

        return $datos[0]['sa_config_estado'];
    }

    function ejecutarQuery($sql)
    {
        //echo $sql;
        $datos = $this->db->sql_string($sql);
        return $datos;
    }

    function ponerIdCursosDocentes()
    {
        $sql =
            "UPDATE hc
            SET 
                hc.ac_paralelo_id = mappings.sa_par_id
            FROM horario_clases hc
            JOIN (
                SELECT cs.sa_sec_nombre AS seccion_nombre, cg.sa_gra_nombre AS grado_nombre, cp.sa_par_nombre AS paralelo_nombre, sa_sec_id, sa_gra_id, sa_par_id
                FROM cat_paralelo cp
                INNER JOIN cat_seccion cs ON cp.sa_id_seccion = cs.sa_sec_id
                INNER JOIN cat_grado cg ON cp.sa_id_grado = cg.sa_gra_id
                WHERE cp.sa_par_estado = 1
            ) AS mappings ON hc.seccion_idukay = mappings.seccion_nombre AND hc.grado_idukay = mappings.grado_nombre AND hc.paralelo_idukay = mappings.paralelo_nombre;";

        $datos = $this->db->sql_string($sql);
        return $datos;
    }

    function ponerIdDocentes()
    {
        $sql =
            "UPDATE hc SET ac_docente_id = doc.sa_doc_id 
            FROM horario_clases hc
            LEFT JOIN  docentes doc ON id_docente_idukay = sa_doc_id_idukay;";

        $datos = $this->db->sql_string($sql);
        return $datos;
    }

    function guardaDocenteParalelo()
    {
        $sql =
            "WITH CTE AS (
                SELECT 
                    ac_docente_id AS id_doc,
                    ac_paralelo_id AS id_par,
                    ROW_NUMBER() OVER (PARTITION BY id_docente_idukay, paralelo_idukay ORDER BY id_docente_idukay) AS rn
                FROM 
                    horario_clases
            )
            INSERT INTO docente_paralelo (
                ac_docente_id,
                ac_paralelo_id
            )
            SELECT 
                id_doc,
                id_par
            FROM 
                CTE
            WHERE 
                rn = 1
            ORDER BY 
                id_doc, 
                id_par;";

        $datos = $this->db->sql_string($sql);
        return $datos;
    }
}
