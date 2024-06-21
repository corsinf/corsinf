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
                    sa_est_rep_parentesco,


                    sa_id_representante_2,
                    sa_est_rep_parentesco_2,
                   
                    est.sa_est_tabla,
                    sa_est_direccion,

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

                    sa_id_representante_2,
                    sa_est_rep_parentesco_2,

                    est.sa_est_tabla,
                    est.sa_est_correo,
                    est.sa_est_estado,
                    est.sa_est_fecha_creacion,
                    est.sa_est_fecha_modificacion,
                    sa_est_direccion,

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

                    sa_id_representante_2,
                    sa_est_rep_parentesco_2,
                    sa_est_direccion,

                    est.sa_est_tabla,
                    est.sa_est_correo,
                    est.sa_est_estado,
                    est.sa_est_fecha_creacion,
                    est.sa_est_fecha_modificacion,
                    est.sa_est_foto,

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

    function add($tabla, $datos)
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

                    sa_id_representante_2,
                    sa_est_rep_parentesco_2,

                    est.sa_est_tabla,
                    est.sa_est_correo,
                    est.sa_est_estado,
                    est.sa_est_fecha_creacion,
                    est.sa_est_fecha_modificacion,
                    est.sa_est_foto_url,
                    sa_est_direccion,
                    
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
            $datos = 'Falta ID Representante';
        }


        return $datos;
    }

    function cargaMasivaIdukay($sql)
    {
        //echo $sql;
        $datos = $this->db->sql_string($sql);
        return $datos;
    }

    function ponerRepresentantesEstudiantes()
    {
        $sql =
            "UPDATE e

            SET e.sa_id_representante = r.sa_rep_id

            FROM estudiantes e

            INNER JOIN representantes r

            ON e.sa_id_rep_idukay = r.sa_id_rep_idukay;";


        $sql .=
            "UPDATE e

            SET e.sa_id_representante_2 = r.sa_rep_id
            
            FROM estudiantes e
            
            INNER JOIN representantes r
            
            ON e.sa_id_rep_idukay_2 = r.sa_id_rep_idukay;";

        $datos = $this->db->sql_string($sql);
        return $datos;
    }

    function ponerIdCursos()
    {
        $sql =
            "UPDATE e
            SET e.sa_id_seccion = mappings.sa_sec_id,
                e.sa_id_grado = mappings.sa_gra_id,
                e.sa_id_paralelo = mappings.sa_par_id
            FROM estudiantes e
            JOIN (
                SELECT cs.sa_sec_nombre AS seccion_nombre, cg.sa_gra_nombre AS grado_nombre, cp.sa_par_nombre AS paralelo_nombre, sa_sec_id, sa_gra_id, sa_par_id
                FROM cat_paralelo cp
                INNER JOIN cat_seccion cs ON cp.sa_id_seccion = cs.sa_sec_id
                INNER JOIN cat_grado cg ON cp.sa_id_grado = cg.sa_gra_id
                WHERE cp.sa_par_estado = 1
            ) AS mappings ON e.seccion_estudiante_idukay = mappings.seccion_nombre AND e.grado_estudiante_idukay = mappings.grado_nombre AND e.paralelo_estudiante_idukay = mappings.paralelo_nombre;";

        $datos = $this->db->sql_string($sql);
        return $datos;
    }

    function datosEstudianteRepresentante($id)
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

                sa_id_representante_2,
                sa_est_rep_parentesco_2,

                est.sa_est_tabla,
                est.sa_est_correo,
                est.sa_est_estado,
                est.sa_est_fecha_creacion,
                est.sa_est_fecha_modificacion,
                sa_est_direccion,

                cs.sa_sec_id, 
                cs.sa_sec_nombre, 
                cg.sa_gra_id, 
                cg.sa_gra_nombre,
                pr.sa_par_id, 
                pr.sa_par_nombre,

                rep.sa_rep_id AS sa_rep_id,
                CONCAT(rep.sa_rep_primer_apellido, ' ', rep.sa_rep_segundo_apellido, ' ', rep.sa_rep_primer_nombre, ' ', rep.sa_rep_segundo_nombre) AS sa_pac_temp_nombre_completo_rep,
                rep.sa_rep_telefono_1 AS sa_pac_temp_telefono_1,
                rep.sa_rep_telefono_2 AS sa_pac_temp_telefono_2,
                rep.sa_rep_correo AS sa_pac_temp_correo_rep,

                rep2.sa_rep_id AS sa_rep2_id,
                CONCAT(rep2.sa_rep_primer_apellido, ' ', rep2.sa_rep_segundo_apellido, ' ', rep2.sa_rep_primer_nombre, ' ', rep2.sa_rep_segundo_nombre) AS sa_pac_temp_nombre_completo_rep2,
                rep2.sa_rep_telefono_1 AS sa_pac_temp_telefono_2_1,
                rep2.sa_rep_telefono_2 AS sa_pac_temp_telefono_2_2,
                rep2.sa_rep_correo AS sa_pac_temp_correo_rep2,
                
                CONCAT(
                    est.sa_est_primer_apellido, ' ', 
                    est.sa_est_segundo_apellido, ' ', 
                    est.sa_est_primer_nombre, ' ', 
                    est.sa_est_segundo_nombre) AS NombreCompleto


                FROM estudiantes est
                INNER JOIN cat_seccion cs ON est.sa_id_seccion = cs.sa_sec_id
                INNER JOIN cat_grado cg ON est.sa_id_grado = cg.sa_gra_id
                INNER JOIN cat_paralelo pr ON est.sa_id_paralelo = pr.sa_par_id
                LEFT JOIN representantes rep ON est.sa_id_representante = rep.sa_rep_id
                LEFT JOIN representantes rep2 ON est.sa_id_representante_2 = rep2.sa_rep_id

                WHERE est.sa_est_estado = 1";


            $sql .= ' and sa_est_id = ' . $id;
            $sql .= " ORDER BY sa_est_id;";
            $datos = $this->db->datos($sql);
            return $datos;
        }

        return null;
    }
}
