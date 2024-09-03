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

    //Para buscar todas las consultas asosiadas al tutor de acuerdo a su id
    function lista_consultas_todo($paralelo, $fecha_inicio, $fecha_fin)
    {
        $datos = [];
        if ($paralelo != '') {
            $sql = "SELECT
                    cm.sa_conp_id,
                    cm.sa_fice_id,
                    cm.sa_conp_nivel,
                    
                    cm.sa_conp_fecha_ingreso,
                    cm.sa_conp_desde_hora,
                    cm.sa_conp_hasta_hora,
                    cm.sa_conp_permiso_salida,
                    cm.sa_conp_estado_revision,
                    
                    cm.sa_conp_notificacion_envio_representante,
                    cm.sa_conp_notificacion_envio_inspector,
                    cm.sa_conp_notificacion_envio_guardia,

                    cm.sa_conp_tipo_consulta,
                    cm.sa_conp_estado,
                    cm.sa_conp_fecha_creacion,

                    pac.sa_pac_id,
					pac.sa_pac_cedula,
					pac.sa_pac_apellidos,
					pac.sa_pac_nombres,
					pac.sa_pac_tabla

                    FROM consultas_medicas cm
                    
                    INNER JOIN ficha_medica fm ON cm.sa_fice_id = fm.sa_fice_id
                    INNER JOIN pacientes pac ON fm.sa_fice_pac_id = pac.sa_pac_id
                    INNER JOIN estudiantes est ON pac.sa_pac_id_comunidad = est.sa_est_id
                    
                    WHERE sa_conp_estado = 1 AND sa_conp_estado_revision = 1";


            $sql .= " AND est.sa_id_paralelo = '$paralelo' ";

            if ($fecha_inicio != '' && $fecha_fin != '') {
                $sql .= " AND CONVERT(DATE, sa_conp_fecha_creacion) BETWEEN '$fecha_inicio' AND '$fecha_fin' ";
            }

            $sql .= " ORDER BY cm.sa_conp_id;";
            $datos = $this->db->datos($sql);
        }

        return $datos;
    }
}
