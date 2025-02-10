<?php
if (!class_exists('db')) {
    include(dirname(__DIR__, 2) . '/db/db.php');
}
/**
 * 
 */
class docente_paraleloM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    //Para mostrar todos los registros con campos especificos para la vista principal
    function lista_docente_paralelo_todo()
    {
        $sql =
            "SELECT 
                    docp.ac_docente_paralelo_id,
                    docp.ac_docente_id,
                    docp.ac_paralelo_id,
                    docp.ac_docente_paralelo_fecha_creacion,
                   
                    cs.sa_sec_id, 
                    cs.sa_sec_nombre, 
                    cg.sa_gra_id, 
                    cg.sa_gra_nombre,
                    cp.sa_par_id, 
                    cp.sa_par_nombre,

                    CONCAT(doc.sa_doc_primer_apellido, ' ', doc.sa_doc_segundo_apellido, ' ', doc.sa_doc_primer_nombre, ' ', doc.sa_doc_segundo_nombre) AS docente_nombres

                    FROM docente_paralelo docp

                    INNER JOIN cat_paralelo cp ON docp.ac_paralelo_id = cp.sa_par_id
                    INNER JOIN cat_seccion cs ON cp.sa_id_seccion = cs.sa_sec_id
                    INNER JOIN cat_grado cg ON cp.sa_id_grado = cg.sa_gra_id

                    INNER JOIN docentes doc ON docp.ac_docente_id = doc.sa_doc_id

                    WHERE 1 = 1";

        $sql .= " ORDER BY ac_docente_paralelo_id;";
        $datos = $this->db->datos($sql);
        return $datos;
    }

    function lista_docente_paralelo($id_docente = '')
    {
        if ($id_docente != '') {
            $sql =
                "SELECT 
                    docp.ac_docente_paralelo_id,
                    docp.ac_docente_id,
                    docp.ac_paralelo_id,
                    docp.ac_docente_paralelo_fecha_creacion,
                   
                    cs.sa_sec_id, 
                    cs.sa_sec_nombre, 
                    cg.sa_gra_id, 
                    cg.sa_gra_nombre,
                    cp.sa_par_id, 
                    cp.sa_par_nombre,

                    CONCAT(doc.sa_doc_primer_apellido, ' ', doc.sa_doc_segundo_apellido, ' ', doc.sa_doc_primer_nombre, ' ', doc.sa_doc_segundo_nombre) AS docente_nombres

                    FROM docente_paralelo docp

                    INNER JOIN cat_paralelo cp ON docp.ac_paralelo_id = cp.sa_par_id
                    INNER JOIN cat_seccion cs ON cp.sa_id_seccion = cs.sa_sec_id
                    INNER JOIN cat_grado cg ON cp.sa_id_grado = cg.sa_gra_id

                    INNER JOIN docentes doc ON docp.ac_docente_id = doc.sa_doc_id

                    WHERE 1 = 1 AND docp.ac_docente_id = $id_docente";

            $sql .= " ORDER BY ac_docente_paralelo_id;";
            $datos = $this->db->datos($sql);
            return $datos;
        }
    }


    function insertar($datos)
    {
        $rest = $this->db->inserts('docente_paralelo', $datos);
        return $rest;
    }

    function editar($datos, $where)
    {
        $rest = $this->db->update('docente_paralelo', $datos, $where);
        return $rest;
    }

    function eliminar($datos)
    {
        $sql = "UPDATE docente_paralelo SET sa_docp_estado = 0 WHERE " . $datos[0]['campo'] . "='" . $datos[0]['dato'] . "';";
        $datos = $this->db->sql_string($sql);
        return $datos;
    }

    function lista_estudiante_docente_paralelo($id_paralelo = '')
    {
        if ($id_paralelo != '') {
            $sql =
                "SELECT 
                    docp.ac_docente_paralelo_id,
                    docp.ac_docente_id,
                    docp.ac_paralelo_id,
                    docp.ac_docente_paralelo_fecha_creacion,
                   
                    cs.sa_sec_id, 
                    cs.sa_sec_nombre, 
                    cg.sa_gra_id, 
                    cg.sa_gra_nombre,
                    cp.sa_par_id, 
                    cp.sa_par_nombre,

                    CONCAT(doc.sa_doc_primer_apellido, ' ', doc.sa_doc_segundo_apellido, ' ', doc.sa_doc_primer_nombre, ' ', doc.sa_doc_segundo_nombre) AS docente_nombres,
                    CONCAT(cs.sa_sec_nombre, ' / ', cg.sa_gra_nombre, ' / ', cp.sa_par_nombre) AS sec_gra_par

                    FROM docente_paralelo docp

                    INNER JOIN cat_paralelo cp ON docp.ac_paralelo_id = cp.sa_par_id
                    INNER JOIN cat_seccion cs ON cp.sa_id_seccion = cs.sa_sec_id
                    INNER JOIN cat_grado cg ON cp.sa_id_grado = cg.sa_gra_id

                    INNER JOIN docentes doc ON docp.ac_docente_id = doc.sa_doc_id

                    WHERE 1 = 1 AND cp.sa_par_id = $id_paralelo";

            $sql .= " ORDER BY ac_docente_paralelo_id;";
            $datos = $this->db->datos($sql);
            return $datos;
        }
    }

    function lista_estudiantes_representantes_docente_paralelo($id_docente = '')
    {
        if ($id_docente != '') {
            $sql =
                "SELECT 
                    est.sa_est_id,
                    est.sa_est_cedula,
            
                    CONCAT(est.sa_est_primer_apellido, ' ', est.sa_est_segundo_apellido, ' ', est.sa_est_primer_nombre, ' ', est.sa_est_segundo_nombre) AS estudiante_nombres,
                    est.sa_id_paralelo,
                    est.sa_id_representante,
                    est.sa_est_rep_parentesco,
                    est.sa_est_tabla,
            
                    rep.sa_rep_cedula,
                    CONCAT(rep.sa_rep_primer_apellido, ' ', rep.sa_rep_segundo_apellido, ' ', rep.sa_rep_primer_nombre, ' ', rep.sa_rep_segundo_nombre) AS representante_nombres,
                    rep.sa_rep_correo
              
                FROM estudiantes est
                INNER JOIN docente_paralelo dp ON est.sa_id_paralelo = dp.ac_paralelo_id
                INNER JOIN representantes rep ON est.sa_id_representante = rep.sa_rep_id
                WHERE dp.ac_docente_id = '$id_docente';";

            $datos = $this->db->datos($sql);
            return $datos;
        }
    }
}
