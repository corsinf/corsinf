<?php
if (!class_exists('db_salud')) {
    include('../db/db_salud.php');
}
/**
 * 
 */
class consultasM
{
    private $db_salud;

    function __construct()
    {
        $this->db_salud = new db_salud();
    }

    function lista_consultas($id = '')
    {
        $sql = "SELECT 
        sa_conp_id,
        sa_fice_id,
        sa_conp_nombres,
        sa_conp_nivel,
        sa_conp_paralelo,
        sa_conp_edad,
        sa_conp_correo,
        sa_conp_telefono,
        sa_conp_fecha_ingreso,
        sa_conp_desde_hora,
        sa_conp_hasta_hora,
        sa_conp_tiempo_aten,
        sa_conp_CIE_10_1,
        sa_conp_diagnostico_1,
        sa_conp_CIE_10_2,
        sa_conp_diagnostico_2,
        sa_conp_medicina_1,
        sa_conp_dosis_1,
        sa_conp_medicina_2,
        sa_conp_dosis_2,
        sa_conp_medicina_3,
        sa_conp_dosis_3,
        sa_conp_certificado_salud,
        sa_conp_motivo_certificado,
        sa_conp_CIE_10_certificado,
        sa_conp_diagnostico_certificado,
        sa_conp_fecha_entrega_certificado,
        sa_conp_fecha_inicio_falta_certificado,
        sa_conp_fecha_fin_alta_certificado,
        sa_conp_dias_permiso_certificado,
        sa_conp_permiso_salida,
        sa_conp_fecha_permiso_salud_salida,
        sa_conp_hora_permiso_salida,
        sa_conp_notificacion_envio_representante,
        sa_conp_notificacion_envio_inspector,
        sa_conp_notificacion_envio_guardia,
        sa_conp_observaciones,
        sa_conp_tipo_consulta,
        sa_conp_estado,
        sa_conp_fecha_creacion,
        sa_conp_fecha_modificar
        
        FROM consultas
        WHERE sa_conp_estado = 1";

        if ($id) {
            $sql .= ' and sa_fice_id = ' . $id;
        }

        $sql .= " ORDER BY sa_conp_id";
        $datos = $this->db_salud->datos($sql);
        return $datos;
    }

    function lista_solo_consultas($id = '')
    {
        $sql = "SELECT 
        sa_conp_id,
        sa_fice_id,
        sa_conp_nombres,
        sa_conp_nivel,
        sa_conp_paralelo,
        sa_conp_edad,
        sa_conp_correo,
        sa_conp_telefono,
        sa_conp_fecha_ingreso,
        sa_conp_desde_hora,
        sa_conp_hasta_hora,
        sa_conp_tiempo_aten,
        sa_conp_CIE_10_1,
        sa_conp_diagnostico_1,
        sa_conp_CIE_10_2,
        sa_conp_diagnostico_2,
        sa_conp_medicina_1,
        sa_conp_dosis_1,
        sa_conp_medicina_2,
        sa_conp_dosis_2,
        sa_conp_medicina_3,
        sa_conp_dosis_3,
        sa_conp_certificado_salud,
        sa_conp_motivo_certificado,
        sa_conp_CIE_10_certificado,
        sa_conp_diagnostico_certificado,
        sa_conp_fecha_entrega_certificado,
        sa_conp_fecha_inicio_falta_certificado,
        sa_conp_fecha_fin_alta_certificado,
        sa_conp_dias_permiso_certificado,
        sa_conp_permiso_salida,
        sa_conp_fecha_permiso_salud_salida,
        sa_conp_hora_permiso_salida,
        sa_conp_notificacion_envio_representante,
        sa_conp_notificacion_envio_inspector,
        sa_conp_notificacion_envio_guardia,
        sa_conp_observaciones,
        sa_conp_tipo_consulta,
        sa_conp_estado,
        sa_conp_fecha_creacion,
        sa_conp_fecha_modificar
        
        FROM consultas
        WHERE sa_conp_estado = 1";

        if ($id) {
            $sql .= ' and sa_conp_id = ' . $id;
        }

        $sql .= " ORDER BY sa_conp_id";
        $datos = $this->db_salud->datos($sql);
        return $datos;
    }

    function lista_consultas_todo($id = '')
    {
        $sql = "SELECT  sa_sec_id, sa_sec_nombre, sa_sec_estado FROM consultas WHERE 1 = 1 ";

        if ($id) {
            $sql .= ' and sa_sec_id= ' . $id;
        }

        $sql .= " ORDER BY sa_sec_id ";
        $datos = $this->db_salud->datos($sql);
        return $datos;
    }

    function buscar_consultas($buscar)
    {
        $sql = "SELECT sa_sec_id, sa_sec_nombre, sa_sec_estado FROM consultas WHERE sa_sec_estado = 1 and sa_sec_nombre + ' ' + sa_sec_id LIKE '%" . $buscar . "%'";
        $datos = $this->db_salud->datos($sql);
        return $datos;
    }

    function buscar_consultas_CODIGO($buscar)
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
        
        FROM consultas
        WHERE sa_fice_id = '" . $buscar . "'";

        $datos = $this->db_salud->datos($sql);
        return $datos;
    }

    function insertar($datos)
    {
        $rest = $this->db_salud->inserts('consultas', $datos);
        return $rest;
    }

    function editar($datos, $where)
    {
        $rest = $this->db_salud->update('consultas', $datos, $where);
        return $rest;
    }

    function eliminar($datos)
    {
        $sql = "UPDATE consultas SET sa_fice_estado = 0 WHERE " . $datos[0]['campo'] . "='" . $datos[0]['dato'] . "';";
        $datos = $this->db_salud->sql_string($sql);
        return $datos;
    }
}
