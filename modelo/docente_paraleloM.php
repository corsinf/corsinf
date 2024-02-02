<?php
if (!class_exists('db')) {
    include('../db/db.php');
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
}
