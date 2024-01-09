<?php
if (!class_exists('db')) {
    include('../db/db.php');
}
/**
 * 
 */
class estudiantesM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }
    
    //Para mostrar todos los registros con campos especificos para la vista principal
    function lista_estudiantes_todo()
    {
        $sql =
            "SELECT 
                    est.sa_est_id,
                    est.sa_est_primer_apellido,
                    est.sa_est_segundo_apellido,
                    est.sa_est_primer_nombre,
                    est.sa_est_segundo_nombre,
                    est.sa_est_cedula,
                    est.sa_est_fecha_nacimiento,
                    est.sa_id_seccion,
                    est.sa_id_grado,
                    est.sa_id_paralelo,
                    est.sa_id_representante,
                   
                    est.sa_est_tabla,

                    cs.sa_sec_id, 
                    cs.sa_sec_nombre, 
                    cg.sa_gra_id, 
                    cg.sa_gra_nombre,
                    pr.sa_par_id, 
                    pr.sa_par_nombre

                    FROM estudiantes est
                    INNER JOIN cat_seccion cs ON est.sa_id_seccion = cs.sa_sec_id
                    INNER JOIN cat_grado cg ON est.sa_id_grado = cg.sa_gra_id
                    INNER JOIN cat_paralelo pr ON est.sa_id_paralelo = pr.sa_par_id
                    WHERE est.sa_est_estado = 1";

        $sql .= " ORDER BY sa_est_id;";
        $datos = $this->db->datos($sql);
        return $datos;
    }

    function lista_estudiantes($id = '')
    {
        $sql =
            "SELECT 
                    est.sa_est_id,
                    est.sa_est_primer_apellido,
                    est.sa_est_segundo_apellido,
                    est.sa_est_primer_nombre,
                    est.sa_est_segundo_nombre,
                    est.sa_est_cedula,
                    est.sa_est_sexo,
                    est.sa_est_fecha_nacimiento,
                    est.sa_id_seccion,
                    est.sa_id_grado,
                    est.sa_id_paralelo,
                    est.sa_id_representante,
                    est.sa_est_rep_parentesco,
                    est.sa_est_tabla,
                    est.sa_est_correo,
                    est.sa_est_estado,
                    est.sa_est_fecha_creacion,
                    est.sa_est_fecha_modificacion,

                    cs.sa_sec_id, 
                    cs.sa_sec_nombre, 
                    cg.sa_gra_id, 
                    cg.sa_gra_nombre,
                    pr.sa_par_id, 
                    pr.sa_par_nombre

                    FROM estudiantes est
                    INNER JOIN cat_seccion cs ON est.sa_id_seccion = cs.sa_sec_id
                    INNER JOIN cat_grado cg ON est.sa_id_grado = cg.sa_gra_id
                    INNER JOIN cat_paralelo pr ON est.sa_id_paralelo = pr.sa_par_id
                    WHERE est.sa_est_estado = 1";

        if ($id) {
            $sql .= ' and sa_est_id = ' . $id;
        }

        $sql .= " ORDER BY sa_est_id;";
        $datos = $this->db->datos($sql);
        return $datos;
    }

    function buscar_estudiantes($buscar)
    {
        $sql = "SELECT 
                    est.sa_est_id,
                    est.sa_est_primer_apellido,
                    est.sa_est_segundo_apellido,
                    est.sa_est_primer_nombre,
                    est.sa_est_segundo_nombre,
                    est.sa_est_cedula,
                    est.sa_est_sexo,
                    est.sa_est_fecha_nacimiento,
                    est.sa_id_seccion,
                    est.sa_id_grado,
                    est.sa_id_paralelo,
                    est.sa_id_representante,
                    est.sa_est_rep_parentesco,
                    est.sa_est_tabla,
                    est.sa_est_correo,
                    est.sa_est_estado,
                    est.sa_est_fecha_creacion,
                    est.sa_est_fecha_modificacion,

                    cs.sa_sec_id, 
                    cs.sa_sec_nombre, 
                    cg.sa_gra_id, 
                    cg.sa_gra_nombre,
                    pr.sa_par_id, 
                    pr.sa_par_nombre
            FROM estudiantes est
            INNER JOIN cat_seccion cs ON est.sa_id_seccion = cs.sa_sec_id
            INNER JOIN cat_grado cg ON est.sa_id_grado = cg.sa_gra_id
            INNER JOIN cat_paralelo pr ON est.sa_id_paralelo = pr.sa_par_id
            WHERE est.sa_est_estado = 1 
            AND CONCAT(est.sa_est_primer_apellido, ' ', est.sa_est_segundo_apellido, ' ', 
                       est.sa_est_primer_nombre, ' ', est.sa_est_segundo_nombre, ' ',
                       est.sa_est_cedula, ' ',          
                       est.sa_est_correo,
                       cs.sa_sec_nombre, ' ', 
                       cg.sa_gra_nombre, ' ', 
                       pr.sa_par_nombre) LIKE '%" . $buscar . "%'";

        $datos = $this->db->datos($sql);
        return $datos;
    }

    function buscar_estudiantes_CEDULA($buscar)
    {
        $sql = "SELECT sa_est_id, sa_est_cedula, sa_est_primer_apellido, sa_est_primer_nombre FROM estudiantes WHERE sa_est_cedula = '" . $buscar . "'";
        $datos = $this->db->datos($sql);
        return $datos;
    }

    function insertar($datos)
    {
        $rest = $this->db->inserts('estudiantes', $datos);
        return $rest;
    }

    function add($tabla,$datos)
    {
        $rest = $this->db->inserts($tabla, $datos);
        return $rest;
    }

    function editar($datos, $where)
    {
        $rest = $this->db->update('estudiantes', $datos, $where);
        return $rest;
    }

    function eliminar($datos)
    {
        $sql = "UPDATE estudiantes SET sa_est_estado = 0 WHERE " . $datos[0]['campo'] . "='" . $datos[0]['dato'] . "';";
        $datos = $this->db->sql_string($sql);
        return $datos;
    }

    function buscar_estudiantes_representante($id)
    {
        if ($id) {
            $sql =
                "SELECT 
                    est.sa_est_id,
                    est.sa_est_primer_apellido,
                    est.sa_est_segundo_apellido,
                    est.sa_est_primer_nombre,
                    est.sa_est_segundo_nombre,
                    est.sa_est_cedula,
                    est.sa_est_sexo,
                    est.sa_est_fecha_nacimiento,
                    est.sa_id_seccion,
                    est.sa_id_grado,
                    est.sa_id_paralelo,
                    est.sa_id_representante,
                    est.sa_est_rep_parentesco,
                    est.sa_est_tabla,
                    est.sa_est_correo,
                    est.sa_est_estado,
                    est.sa_est_fecha_creacion,
                    est.sa_est_fecha_modificacion,
                    
                    cs.sa_sec_id, 
                    cs.sa_sec_nombre, 
                    cg.sa_gra_id, 
                    cg.sa_gra_nombre,
                    pr.sa_par_id, 
                    pr.sa_par_nombre
                    FROM estudiantes est
                    INNER JOIN cat_seccion cs ON est.sa_id_seccion = cs.sa_sec_id
                    INNER JOIN cat_grado cg ON est.sa_id_grado = cg.sa_gra_id
                    INNER JOIN cat_paralelo pr ON est.sa_id_paralelo = pr.sa_par_id
                    WHERE est.sa_est_estado = 1";
            $sql .= ' and est.sa_id_representante = ' . $id;
            $sql .= " ORDER BY sa_est_id;";
            $datos = $this->db->datos($sql);
        } else {
            $datos = 'Falta ID Respresentante';
        }


        return $datos;
    }

}
