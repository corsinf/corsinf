<?php
if (!class_exists('db')) {
    include('../db/db.php');
}
/**
 * 
 */
class horario_clasesM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    //Para mostrar todos los registros con campos especificos para la vista principal
    function lista_horario_clases_todo()
    {
        $sql =
            "SELECT 
                    hcd.ac_horarioC_id,
                    hcd.ac_docente_id,
                    hcd.ac_paralelo_id,
                    hcd.ac_horarioC_inicio,
                    hcd.ac_horarioC_fin,
                    hcd.ac_horarioC_dia,
                    hcd.ac_horarioC_materia,
                    hcd.ac_horarioC_fecha_creacion,
                    hcd.ac_horarioC_fecha_modificacion,
                    hcd.ac_horarioC_estado,

                    cs.sa_sec_id, 
                    cs.sa_sec_nombre, 
                    cg.sa_gra_id, 
                    cg.sa_gra_nombre,
                    cp.sa_par_id, 
                    cp.sa_par_nombre

                    FROM horario_clases hcd

                    INNER JOIN cat_paralelo cp ON hcd.ac_paralelo_id = cp.sa_par_id
                    INNER JOIN cat_seccion cs ON cp.sa_id_seccion = cs.sa_sec_id
                    INNER JOIN cat_grado cg ON cp.sa_id_grado = cg.sa_gra_id

                    WHERE 1 = 1";


        $sql .= " ORDER BY hcd.ac_horarioC_id;";
        $datos = $this->db->datos($sql);
        return $datos;
    }

    function lista_horario_clases($id_docente = '', $id_paralelo = '')
    {
        if ($id_docente != '') {
            $sql =
                "SELECT 
                    hcd.ac_horarioC_id,
                    hcd.ac_docente_id,
                    hcd.ac_paralelo_id,
                    hcd.ac_horarioC_inicio,
                    hcd.ac_horarioC_fin,
                    hcd.ac_horarioC_dia,
                    hcd.ac_horarioC_materia,
                    hcd.ac_horarioC_fecha_creacion,
                    hcd.ac_horarioC_fecha_modificacion,
                    hcd.ac_horarioC_estado,

                    cs.sa_sec_id, 
                    cs.sa_sec_nombre, 
                    cg.sa_gra_id, 
                    cg.sa_gra_nombre,
                    cp.sa_par_id, 
                    cp.sa_par_nombre

                    FROM horario_clases hcd

                    INNER JOIN cat_paralelo cp ON hcd.ac_paralelo_id = cp.sa_par_id
                    INNER JOIN cat_seccion cs ON cp.sa_id_seccion = cs.sa_sec_id
                    INNER JOIN cat_grado cg ON cp.sa_id_grado = cg.sa_gra_id

                    WHERE 1 = 1 AND hcd.ac_docente_id = $id_docente";

            if ($id_paralelo != '') {
                $sql .= "AND hcd.ac_paralelo_id = $id_paralelo";
            }

            $sql .= " ORDER BY hcd.ac_horarioC_id;";
            $datos = $this->db->datos($sql);
            return $datos;
        }
    }


    function insertar($datos)
    {
        $rest = $this->db->inserts('horario_clases', $datos);
        return $rest;
    }

    function editar($datos, $where)
    {
        $rest = $this->db->update('horario_clases', $datos, $where);
        return $rest;
    }

    function eliminar($id)
    {
        $sql = "DELETE FROM horario_clases WHERE ac_horarioC_id = $id;";

        //"UPDATE horario_clases SET sa_hcd_estado = 0 WHERE " . $datos[0]['campo'] . "='" . $datos[0]['dato'] . "';";
        $datos = $this->db->sql_string($sql);
        return $datos;
    }
}
