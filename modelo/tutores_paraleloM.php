<?php
if (!class_exists('db')) {
    include('../db/db.php');
}
/**
 * 
 */
class tutores_paraleloM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    //Para mostrar todos los registros con campos especificos para la vista principal
    function lista_tutor_paralelo_todo()
    {
        $sql =
            "SELECT 
                    tutp.ac_tutor_paralelo_id,
                    tutp.ac_tutor_id,
                    tutp.ac_paralelo_id,
                    tutp.ac_tutor_paralelo_fecha_creacion,
                   
                    cs.sa_sec_id, 
                    cs.sa_sec_nombre, 
                    cg.sa_gra_id, 
                    cg.sa_gra_nombre,
                    cp.sa_par_id, 
                    cp.sa_par_nombre,

                    CONCAT(tut.sa_tut_primer_apellido, ' ', tut.sa_tut_segundo_apellido, ' ', tut.sa_tut_primer_nombre, ' ', tut.sa_tut_segundo_nombre) AS tutor_nombres

                    FROM tutor_paralelo tutp

                    INNER JOIN cat_paralelo cp ON tutp.ac_paralelo_id = cp.sa_par_id
                    INNER JOIN cat_seccion cs ON cp.sa_id_seccion = cs.sa_sec_id
                    INNER JOIN cat_grado cg ON cp.sa_id_grado = cg.sa_gra_id

                    INNER JOIN tutors tut ON tutp.ac_tutor_id = tut.sa_tut_id

                    WHERE 1 = 1";

        $sql .= " ORDER BY ac_tutor_paralelo_id;";
        $datos = $this->db->datos($sql);
        return $datos;
    }

    //Primera - uso
    function lista_tutor_paralelo($id_tutor = '')
    {
        if ($id_tutor != '') {
            $sql =
                "SELECT 
                    tutp.ac_tutor_paralelo_id,
                    tutp.ac_tutor_id,
                    tutp.ac_paralelo_id,
                    tutp.ac_tutor_paralelo_fecha_creacion,
                   
                    cs.sa_sec_id, 
                    cs.sa_sec_nombre, 
                    cg.sa_gra_id, 
                    cg.sa_gra_nombre,
                    cp.sa_par_id, 
                    cp.sa_par_nombre,

                    CONCAT(tut.apellidos, ' ', tut.nombres) AS tutor_nombres

                    FROM tutor_paralelo tutp

                    INNER JOIN cat_paralelo cp ON tutp.ac_paralelo_id = cp.sa_par_id
                    INNER JOIN cat_seccion cs ON cp.sa_id_seccion = cs.sa_sec_id
                    INNER JOIN cat_grado cg ON cp.sa_id_grado = cg.sa_gra_id

                    INNER JOIN usuarios tut ON tutp.ac_tutor_id = tut.id_usuarios

                    WHERE 1 = 1 AND tutp.ac_tutor_id = $id_tutor";

            $sql .= " ORDER BY ac_tutor_paralelo_id;";
            $datos = $this->db->datos($sql);
            return $datos;
        }
    }

    //uso
    function insertar($datos)
    {
        $rest = $this->db->inserts('tutor_paralelo', $datos);
        return $rest;
    }

    function editar($datos, $where)
    {
        $rest = $this->db->update('tutor_paralelo', $datos, $where);
        return $rest;
    }


    function eliminar($datos)
    {
        $sql = "UPDATE tutor_paralelo SET sa_tutp_estado = 0 WHERE " . $datos[0]['campo'] . "='" . $datos[0]['dato'] . "';";
        $datos = $this->db->sql_string($sql);
        return $datos;
    }

    function lista_estudiante_tutor_paralelo($id_paralelo = '')
    {
        if ($id_paralelo != '') {
            $sql =
                "SELECT 
                    tutp.ac_tutor_paralelo_id,
                    tutp.ac_tutor_id,
                    tutp.ac_paralelo_id,
                    tutp.ac_tutor_paralelo_fecha_creacion,
                   
                    cs.sa_sec_id, 
                    cs.sa_sec_nombre, 
                    cg.sa_gra_id, 
                    cg.sa_gra_nombre,
                    cp.sa_par_id, 
                    cp.sa_par_nombre,

                    CONCAT(tut.sa_tut_primer_apellido, ' ', tut.sa_tut_segundo_apellido, ' ', tut.sa_tut_primer_nombre, ' ', tut.sa_tut_segundo_nombre) AS tutor_nombres,
                    CONCAT(cs.sa_sec_nombre, ' / ', cg.sa_gra_nombre, ' / ', cp.sa_par_nombre) AS sec_gra_par

                    FROM tutor_paralelo tutp

                    INNER JOIN cat_paralelo cp ON tutp.ac_paralelo_id = cp.sa_par_id
                    INNER JOIN cat_seccion cs ON cp.sa_id_seccion = cs.sa_sec_id
                    INNER JOIN cat_grado cg ON cp.sa_id_grado = cg.sa_gra_id

                    INNER JOIN tutors tut ON tutp.ac_tutor_id = tut.sa_tut_id

                    WHERE 1 = 1 AND cp.sa_par_id = $id_paralelo";

            $sql .= " ORDER BY ac_tutor_paralelo_id;";
            $datos = $this->db->datos($sql);
            return $datos;
        }
    }

    function lista_estudiantes_representantes_tutor_paralelo($id_tutor = '')
    {
        if ($id_tutor != '') {
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
                INNER JOIN tutor_paralelo dp ON est.sa_id_paralelo = dp.ac_paralelo_id
                INNER JOIN representantes rep ON est.sa_id_representante = rep.sa_rep_id
                WHERE dp.ac_tutor_id = '$id_tutor';";

            $datos = $this->db->datos($sql);
            return $datos;
        }
    }


    /*/////////////////////////////////////////////////////////////////////

    ROL - Tutor
    Para consultar en paralelos_tutores  

    /////////////////////////////////////////////////////////////////////*/

    //Funcion que me retorna los paralelos sin contar los que estan en la tabla paralelo_tutor
    function lista_paralelo_todo_sin_paralelo_tutor($buscar)
    {
        $sql =
            "SELECT 
                cp.sa_par_id, 
                cp.sa_par_nombre, 
                cp.sa_par_estado, 
                cs.sa_sec_id, 
                cs.sa_sec_nombre, 
                cg.sa_gra_id, 
                cg.sa_gra_nombre
            FROM cat_paralelo cp
            INNER JOIN cat_seccion cs ON cp.sa_id_seccion = cs.sa_sec_id
            INNER JOIN cat_grado cg ON cp.sa_id_grado = cg.sa_gra_id
            WHERE cp.sa_par_estado = 1 AND cg.sa_gra_id = " . $buscar . "
            AND NOT EXISTS (
                SELECT 1
                FROM tutor_paralelo tp
                WHERE tp.ac_paralelo_id = cp.sa_par_id
            );";

        $datos = $this->db->datos($sql);
        return $datos;
    }

    function buscar_PARALELO($buscar)
    {
        $sql = "SELECT * FROM tutor_paralelo WHERE ac_paralelo_id = '" . $buscar . "'";
        $datos = $this->db->datos($sql);
        return $datos;
    }
}
