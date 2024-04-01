<?php
if (!class_exists('db')) {
    include('../db/db.php');
}
/**
 * 
 */
class consultasM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    //Para buscar las consultas_medicas en base a la ficha medica
    function lista_consultas_ficha($id_ficha = '')
    {
        if ($id_ficha) {

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
                        pac.sa_pac_tabla

                        FROM consultas_medicas cm
                        
                        INNER JOIN ficha_medica fm ON cm.sa_fice_id = fm.sa_fice_id
                        INNER JOIN pacientes pac ON fm.sa_fice_pac_id = pac.sa_pac_id
                        
                        WHERE sa_conp_estado = 1";


            $sql .= ' and fm.sa_fice_id = ' . $id_ficha;

            $sql .= " ORDER BY cm.sa_conp_id;";
            $datos = $this->db->datos($sql);
            return $datos;
        }
    }

    function lista_solo_consultas($id = '')
    {
        if ($id) {
            $sql = "SELECT  sa_conp_id,
                            sa_fice_id,
                            sa_conp_nivel,
                            sa_conp_paralelo,
                            sa_conp_edad,
                            sa_conp_peso,
                            sa_conp_altura,
                            sa_conp_temperatura,
                            sa_conp_presion_ar,
                            sa_conp_frec_cardiaca,
                            sa_conp_frec_respiratoria,

                            sa_conp_fecha_ingreso,
                            sa_conp_desde_hora,
                            sa_conp_hasta_hora,
                            sa_conp_tiempo_aten,
                            sa_conp_CIE_10_1,
                            sa_conp_diagnostico_1,
                            sa_conp_CIE_10_2,
                            sa_conp_diagnostico_2,

                            sa_conp_salud_certificado,
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
                            sa_conp_permiso_tipo,
                            sa_conp_permiso_seguro_traslado,
                            sa_conp_permiso_telefono_padre,
                            sa_conp_permiso_telefono_seguro,

                            sa_conp_notificacion_envio_representante,
                            sa_id_representante,
                            sa_conp_notificacion_envio_docente,
                            sa_id_docente,
                            sa_conp_notificacion_envio_inspector,
                            sa_id_inspector,
                            sa_conp_notificacion_envio_guardia,
                            sa_id_guardia,

                            sa_conp_observaciones,
                            sa_conp_motivo_consulta,
                            sa_conp_tratamiento,

                            sa_conp_tipo_consulta,
                            sa_conp_enfermedad_actual,
                            sa_conp_saturacion,
                            sa_examen_fisico_regional,

                            sa_conp_estado,
                            sa_conp_estado_revision,
                            sa_conp_fecha_creacion,
                            sa_conp_fecha_modificacion

                    FROM consultas_medicas
                    WHERE sa_conp_estado = 1";

            $sql .= ' and sa_conp_id = ' . $id;
            $sql .= " ORDER BY sa_conp_id;";

<<<<<<< HEAD
        $sql .= " ORDER BY sa_conp_id";
<<<<<<< HEAD

        // print_r($sql);die();
=======
>>>>>>> f975ff57302e9fcddee9c8879ae90e7325aab8d1
        $datos = $this->db_salud->datos($sql);
        return $datos;
=======
            $datos = $this->db->datos($sql);
            return $datos;
        }
>>>>>>> c9a234889f7443a040d28d13f82e35ef88467ae7
    }

    function lista_consultas_todo($tabla, $fecha_inicio, $fecha_fin)
    {
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
                    
                    WHERE sa_conp_estado = 1 AND sa_conp_estado_revision = 1";

        if ($tabla != '') {
            $sql .= " AND pac.sa_pac_tabla = '$tabla' ";
        }

        if ($fecha_inicio != '' && $fecha_fin != '') {
            $sql .= " AND CONVERT(DATE, sa_conp_fecha_creacion) BETWEEN '$fecha_inicio' AND '$fecha_fin' ";
        }

        $sql .= " ORDER BY cm.sa_conp_id;";
        $datos = $this->db->datos($sql);
        return $datos;
    }

    function buscar_consultas_CODIGO($buscar)
    {
        $sql = "SELECT 
        sa_conp_id
        
        FROM consultas_medicas
        WHERE sa_conp_estado = 1 and sa_conp_id = ' " . $buscar . "';";

        $datos = $this->db->datos($sql);
        return $datos;
    }

    function insertar($datos)
    {
        // print_r($datos);die();
        $rest = $this->db->inserts('consultas_medicas', $datos);


        return $rest;
    }

    function insertar_id($datos)
    {
        // print_r($datos);die();
        $rest = $this->db->inserts_id('consultas_medicas', $datos);
        return $rest;
    }

    function ver_id()
    {
        $sql = "SELECT SCOPE_IDENTITY() AS id_ultimo;";

        $datos = $this->db->datos($sql);
        return $datos;
    }

    function editar($datos, $where)
    {
        $rest = $this->db->update('consultas_medicas', $datos, $where);
        return $rest;
    }

    function eliminar($datos)
    {
        $sql = "UPDATE consultas_medicas SET sa_conp_estado = 0 WHERE " . $datos[0]['campo'] . "='" . $datos[0]['dato'] . "';";
        $datos = $this->db->sql_string($sql);
        return $datos;
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    function carga_datos_consultas($id_consulta)
    {
        $sql = "SELECT 
                    cm.sa_conp_id,
                    cm.sa_conp_fecha_ingreso,
                    cm.sa_conp_desde_hora,
                    cm.sa_conp_hasta_hora,
                    cm.sa_conp_tipo_consulta,
                    cm.sa_fice_id,
                    pac.sa_pac_id,
                    CONCAT(pac.sa_pac_apellidos, ' ', pac.sa_pac_nombres) AS nombres
                FROM consultas_medicas cm
                INNER JOIN ficha_medica fm ON cm.sa_fice_id = fm.sa_fice_id
                INNER JOIN pacientes pac ON fm.sa_fice_pac_id = pac.sa_pac_id
                WHERE cm.sa_conp_id = $id_consulta;";

        return $this->db->datos($sql);
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Consulta para docentes, 

    /*busca las consultas de estudiantes de acuerdo al paralelo al que pertenece el docente.*/
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////
    function lista_consultas_estudiantes($id_paralelo)
    {

        $sql = "SELECT 
                    cm.sa_conp_id,
                    cm.sa_fice_id,
                    cm.sa_conp_nivel,
                    sa_conp_paralelo,
                                    
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
                    pac.sa_pac_tabla,

                    est.sa_id_paralelo
                
                FROM consultas_medicas cm
                                    
                INNER JOIN ficha_medica fm ON cm.sa_fice_id = fm.sa_fice_id
                INNER JOIN pacientes pac ON fm.sa_fice_pac_id = pac.sa_pac_id
                INNER JOIN estudiantes est ON pac.sa_pac_id_comunidad = est.sa_est_id
                INNER JOIN cat_paralelo par ON est.sa_id_paralelo = par.sa_par_id
                                    
                WHERE sa_conp_estado = 1 
                    AND pac.sa_pac_tabla = 'estudiantes'
                    AND par.sa_par_id = $id_paralelo;";

        return $this->db->datos($sql);
    }

    /*busca las consultas de estudiantes de acuerdo al docente y para las notificaciones*/
    function lista_consultas_estudiantes_docente($id_docente, $fecha_actual_estado)
    {
        $sql = "SELECT 
                    cm.sa_conp_id,
                    cm.sa_fice_id,
                    cm.sa_conp_nivel,
                    sa_conp_paralelo,
                                    
                    cm.sa_conp_fecha_ingreso,
                    cm.sa_conp_desde_hora,
                    cm.sa_conp_hasta_hora,
                    cm.sa_conp_permiso_salida,
                    cm.sa_conp_estado_revision,
                                    
                    cm.sa_conp_notificacion_envio_representante,
                    sa_conp_notificacion_envio_docente,
                    cm.sa_conp_notificacion_envio_inspector,
                    cm.sa_conp_notificacion_envio_guardia,
                
                    cm.sa_conp_tipo_consulta,
                    cm.sa_conp_estado,
                    cm.sa_conp_fecha_creacion,
                
                    pac.sa_pac_id,
                    pac.sa_pac_cedula,
                    pac.sa_pac_apellidos,
                    pac.sa_pac_nombres,
                    pac.sa_pac_tabla, 

                    est.sa_id_paralelo,

                    doc.sa_doc_id
                
                FROM consultas_medicas cm
                                    
                INNER JOIN ficha_medica fm ON cm.sa_fice_id = fm.sa_fice_id
                INNER JOIN pacientes pac ON fm.sa_fice_pac_id = pac.sa_pac_id
                INNER JOIN estudiantes est ON pac.sa_pac_id_comunidad = est.sa_est_id
                INNER JOIN cat_paralelo par ON est.sa_id_paralelo = par.sa_par_id
                INNER JOIN docente_paralelo dop ON est.sa_id_paralelo = dop.ac_paralelo_id 
                INNER JOIN docentes doc ON dop.ac_docente_id= doc.sa_doc_id
                
                                    
                WHERE sa_conp_estado = 1
                        
                        AND pac.sa_pac_tabla = 'estudiantes'
                        AND doc.sa_doc_id = $id_docente";

        if ($fecha_actual_estado == 1) {
            $sql .= " AND CONVERT(DATE, cm.sa_conp_fecha_creacion) = CONVERT(DATE, GETDATE())";
        }

        $sql .= " ORDER BY cm.sa_conp_fecha_creacion DESC;";

        //print_r($sql);exit();
        return $this->db->datos($sql);
    }

    function contar_consultas_estudiantes_docente($id_docente)
    {
        $sql = "SELECT 
                    COUNT(cm.sa_conp_id) AS contador_consultas
                
                FROM consultas_medicas cm
                                    
                INNER JOIN ficha_medica fm ON cm.sa_fice_id = fm.sa_fice_id
                INNER JOIN pacientes pac ON fm.sa_fice_pac_id = pac.sa_pac_id
                INNER JOIN estudiantes est ON pac.sa_pac_id_comunidad = est.sa_est_id
                INNER JOIN cat_paralelo par ON est.sa_id_paralelo = par.sa_par_id
                INNER JOIN docente_paralelo dop ON est.sa_id_paralelo = dop.ac_paralelo_id 
                INNER JOIN docentes doc ON dop.ac_docente_id= doc.sa_doc_id
                
                                    
                WHERE sa_conp_estado = 1 
                        
                    AND pac.sa_pac_tabla = 'estudiantes'
                    AND doc.sa_doc_id = $id_docente
                    AND CONVERT(DATE, cm.sa_conp_fecha_creacion) = CONVERT(DATE, GETDATE());";

        return $this->db->datos($sql);
    }
}
