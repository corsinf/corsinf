<?php
if (!class_exists('db_salud')) {
    include('../db/db_salud.php');
}
/**
 * 
 */
class fichas_EstudianteM
{
    private $db_salud;

    function __construct()
    {
        $this->db_salud = new db_salud();
    }

    function lista_fichas_Estudiante($id = '')
    {
        $sql = "SELECT
        sa_fice_id,
        sa_fice_est_id,
        sa_fice_est_primer_apellido,
        sa_fice_est_segundo_apellido,
        sa_fice_est_primer_nombre,
        sa_fice_est_segundo_nombre,
        sa_fice_est_fecha_nacimiento,
        sa_fice_est_grupo_sangre,
        sa_fice_est_direccion_domicilio,
        sa_fice_est_seguro_medico,
        sa_fice_est_nombre_seguro,
    
        sa_fice_rep_1_id,
        sa_fice_rep_1_primer_apellido,
        sa_fice_rep_1_segundo_apellido,
        sa_fice_rep_1_primer_nombre,
        sa_fice_rep_1_segundo_nombre,
        sa_fice_rep_1_parentesco,
        sa_fice_rep_1_telefono_1,
        sa_fice_rep_1_telefono_2,
    
        sa_fice_rep_2_primer_apellido,
        sa_fice_rep_2_segundo_apellido,
        sa_fice_rep_2_primer_nombre,
        sa_fice_rep_2_segundo_nombre,
        sa_fice_rep_2_parentesco,
        sa_fice_rep_2_telefono_1,
        sa_fice_rep_2_telefono_2,
    
        sa_fice_pregunta_1,
        sa_fice_pregunta_1_obs,
    
        sa_fice_pregunta_2,
        sa_fice_pregunta_2_obs,
    
        sa_fice_pregunta_3,
        sa_fice_pregunta_3_obs,
    
        sa_fice_pregunta_4,
        sa_fice_pregunta_4_obs,
        
        sa_fice_pregunta_5_obs,
        
        sa_fice_fecha_creacion,
        sa_fice_fecha_modificar
        
        FROM ficha_estudiante
        WHERE sa_fice_estado = 1";

        if ($id) {
            $sql .= ' and sa_fice_est_id = ' . $id;
        }

        $sql .= " ORDER BY sa_fice_est_id";
        $datos = $this->db_salud->datos($sql);
        return $datos;
    }

    function lista_solo_ficha_Estudiante($id = '')
    {
        $sql = "SELECT
        sa_fice_id,
        sa_fice_est_id,
        sa_fice_est_primer_apellido,
        sa_fice_est_segundo_apellido,
        sa_fice_est_primer_nombre,
        sa_fice_est_segundo_nombre,
        sa_fice_est_fecha_nacimiento,
        sa_fice_est_grupo_sangre,
        sa_fice_est_direccion_domicilio,
        sa_fice_est_seguro_medico,
        sa_fice_est_nombre_seguro,
    
        sa_fice_rep_1_id,
        sa_fice_rep_1_primer_apellido,
        sa_fice_rep_1_segundo_apellido,
        sa_fice_rep_1_primer_nombre,
        sa_fice_rep_1_segundo_nombre,
        sa_fice_rep_1_parentesco,
        sa_fice_rep_1_telefono_1,
        sa_fice_rep_1_telefono_2,
    
        sa_fice_rep_2_primer_apellido,
        sa_fice_rep_2_segundo_apellido,
        sa_fice_rep_2_primer_nombre,
        sa_fice_rep_2_segundo_nombre,
        sa_fice_rep_2_parentesco,
        sa_fice_rep_2_telefono_1,
        sa_fice_rep_2_telefono_2,
    
        sa_fice_pregunta_1,
        sa_fice_pregunta_1_obs,
    
        sa_fice_pregunta_2,
        sa_fice_pregunta_2_obs,
    
        sa_fice_pregunta_3,
        sa_fice_pregunta_3_obs,
    
        sa_fice_pregunta_4,
        sa_fice_pregunta_4_obs,
        
        sa_fice_pregunta_5_obs,
        
        sa_fice_fecha_creacion,
        sa_fice_fecha_modificar
        
        FROM ficha_estudiante
        WHERE sa_fice_estado = 1";

        if ($id) {
            $sql .= ' and sa_fice_id = ' . $id;
        }

        $sql .= " ORDER BY sa_fice_id";
        $datos = $this->db_salud->datos($sql);
        return $datos;
    }

    function lista_fichas_Estudiante_todo($id = '')
    {
        $sql = "SELECT  sa_sec_id, sa_sec_nombre, sa_sec_estado FROM ficha_estudiante WHERE 1 = 1 ";

        if ($id) {
            $sql .= ' and sa_sec_id= ' . $id;
        }

        $sql .= " ORDER BY sa_sec_id ";
        $datos = $this->db_salud->datos($sql);
        return $datos;
    }

    function buscar_fichas_Estudiante($buscar)
    {
        $sql = "SELECT sa_sec_id, sa_sec_nombre, sa_sec_estado FROM ficha_estudiante WHERE sa_sec_estado = 1 and sa_sec_nombre + ' ' + sa_sec_id LIKE '%" . $buscar . "%'";
        $datos = $this->db_salud->datos($sql);
        return $datos;
    }

    function buscar_fichas_Estudiante_CODIGO($buscar)
    {
        $sql = "SELECT
        sa_fice_id,
        sa_fice_est_id,
        sa_fice_est_primer_apellido,
        sa_fice_est_segundo_apellido,
        sa_fice_est_primer_nombre,
        sa_fice_est_segundo_nombre,
        sa_fice_est_fecha_nacimiento,
        sa_fice_est_grupo_sangre,
        sa_fice_est_direccion_domicilio,
        sa_fice_est_seguro_medico,
        sa_fice_est_nombre_seguro,
    
        sa_fice_rep_1_id,
        sa_fice_rep_1_primer_apellido,
        sa_fice_rep_1_segundo_apellido,
        sa_fice_rep_1_primer_nombre,
        sa_fice_rep_1_segundo_nombre,
        sa_fice_rep_1_parentesco,
        sa_fice_rep_1_telefono_1,
        sa_fice_rep_1_telefono_2,
    
        sa_fice_rep_2_primer_apellido,
        sa_fice_rep_2_segundo_apellido,
        sa_fice_rep_2_primer_nombre,
        sa_fice_rep_2_segundo_nombre,
        sa_fice_rep_2_parentesco,
        sa_fice_rep_2_telefono_1,
        sa_fice_rep_2_telefono_2,
    
        sa_fice_pregunta_1,
        sa_fice_pregunta_1_obs,
    
        sa_fice_pregunta_2,
        sa_fice_pregunta_2_obs,
    
        sa_fice_pregunta_3,
        sa_fice_pregunta_3_obs,
    
        sa_fice_pregunta_4,
        sa_fice_pregunta_4_obs,
        
        sa_fice_pregunta_5_obs,
        
        sa_fice_fecha_creacion,
        sa_fice_fecha_modificar
        
        FROM ficha_estudiante
        WHERE sa_fice_id = '" . $buscar . "'";

        $datos = $this->db_salud->datos($sql);
        return $datos;
    }

    function insertar($datos)
    {
        $rest = $this->db_salud->inserts('ficha_estudiante', $datos);
        return $rest;
    }

    function editar($datos, $where)
    {
        $rest = $this->db_salud->update('ficha_estudiante', $datos, $where);
        return $rest;
    }

    function eliminar($datos)
    {
        $sql = "UPDATE ficha_estudiante SET sa_fice_estado = 0 WHERE " . $datos[0]['campo'] . "='" . $datos[0]['dato'] . "';";
        $datos = $this->db_salud->sql_string($sql);
        return $datos;
    }
}
