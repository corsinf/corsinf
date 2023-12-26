<?php
if (!class_exists('db')) {
    include('../db/db.php');
}
/**
 * 
 */
class ficha_MedicaM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    function lista_ficha_medica($id = '')
    {
        $sql = "SELECT
        fm.sa_fice_id,
        fm.sa_fice_est_id,
        fm.sa_fice_est_primer_apellido,
        fm.sa_fice_est_segundo_apellido,
        fm.sa_fice_est_primer_nombre,
        fm.sa_fice_est_segundo_nombre,
        fm.sa_fice_rep_1_id,
        fm.sa_fice_fecha_creacion,
        COUNT(c.sa_conp_id) AS cantidad_consultas
        FROM
            ficha_medica fm
        LEFT JOIN
            consultas c ON fm.sa_fice_id = c.sa_fice_id AND c.sa_conp_estado = 1
        WHERE
            fm.sa_fice_estado = 1";

        if ($id) {
            $sql .= ' and sa_fice_est_id = ' . $id;
        }

        $sql .= " GROUP BY
        fm.sa_fice_id,
        fm.sa_fice_est_id,
        fm.sa_fice_est_primer_apellido,
        fm.sa_fice_est_segundo_apellido,
        fm.sa_fice_est_primer_nombre,
        fm.sa_fice_est_segundo_nombre,
        fm.sa_fice_rep_1_id,
        fm.sa_fice_fecha_creacion";

        $datos = $this->db->datos($sql);
        return $datos;
    }

    //Solo para el id
    function lista_paciente_ficha_medica($id_paciente = '')
    {
        if ($id_paciente) {
            $sql = "SELECT
                    sa_fice_id,
                    sa_fice_pac_id,
                    sa_fice_pac_fecha_nacimiento,
                    sa_fice_pac_grupo_sangre,
                    sa_fice_pac_direccion_domicilio,
                    sa_fice_pac_seguro_medico,
                    sa_fice_pac_seguro_medico,
                
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
                    
                    sa_fice_estado,
                    sa_fice_fecha_creacion,
                    sa_fice_fecha_modificacion,
                    sa_fice_estado_realizado

                    FROM ficha_medica
                    WHERE sa_fice_estado = 1";


            $sql .= ' AND sa_fice_pac_id = ' . $id_paciente;
            $sql .= " ORDER BY sa_fice_id";
        }


        $datos = $this->db->datos($sql);
        return $datos;
    }

    function lista_ficha_medica_todo($id = '')
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
        
        FROM ficha_medica
        WHERE sa_fice_estado = 1";

        if ($id) {
            $sql .= ' and sa_fice_est_id = ' . $id;
        }

        $sql .= " ORDER BY sa_fice_est_id";
        $datos = $this->db->datos($sql);
        return $datos;
    }

    function buscar_ficha_medica($buscar)
    {
        $sql = "SELECT sa_sec_id, sa_sec_nombre, sa_sec_estado FROM ficha_medica WHERE sa_sec_estado = 1 and sa_sec_nombre + ' ' + sa_sec_id LIKE '%" . $buscar . "%'";
        $datos = $this->db->datos($sql);
        return $datos;
    }

    function buscar_ficha_medica_CODIGO($buscar)
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
        
        FROM ficha_medica
        WHERE sa_fice_id = '" . $buscar . "'";

        $datos = $this->db->datos($sql);
        return $datos;
    }

    function insertar($datos)
    {
        $rest = $this->db->inserts('ficha_medica', $datos);
        return $rest;
    }

    function editar($datos, $where)
    {
        $rest = $this->db->update('ficha_medica', $datos, $where);
        return $rest;
    }

    function eliminar($datos)
    {
        $sql = "UPDATE ficha_medica SET sa_fice_estado = 0 WHERE " . $datos[0]['campo'] . "='" . $datos[0]['dato'] . "';";
        $datos = $this->db->sql_string($sql);
        return $datos;
    }

    //Para ver si existe alguien de la comunidad en la tabla
    function existe_paciente_comunidad($sa_pac_id_comunidad, $sa_pac_tabla)
    {
        $sql = "SELECT 
        CASE WHEN COUNT(*) > 0 THEN 1 ELSE 0 END AS existe_paciente_comunidad
        FROM pacientes
        WHERE sa_pac_id_comunidad = $sa_pac_id_comunidad AND sa_pac_tabla = '$sa_pac_tabla';";

        $datos = $this->db->datos($sql);

        if (!empty($datos) && $datos[0]['existe_paciente_comunidad'] == 1) {
            return true;
        } else {
            return false; // Otra lógica de retorno en caso de no encontrar un paciente
        }

        //return $datos[0]['existe_paciente_comunidad'];

    }

    function obtener_id_tabla_paciente($sa_pac_id_comunidad, $sa_pac_tabla)
    {
        $sql = "SELECT TOP 1 sa_pac_id, sa_pac_tabla
            FROM pacientes
            WHERE sa_pac_id_comunidad = $sa_pac_id_comunidad AND sa_pac_tabla = '$sa_pac_tabla';";

        $datos = $this->db->datos($sql);

        if (!empty($datos) && isset($datos[0]['sa_pac_id'])) {
            $datos = ['sa_pac_id' => $datos[0]['sa_pac_id'], 'sa_pac_tabla' => $datos[0]['sa_pac_tabla']];
            return $datos;
        } else {
            return null; // Otra lógica de retorno en caso de no encontrar un paciente
        }
    }

    //Para crear la ficha medica y el paciente
    function gestion_comunidad_ficha_medica($sa_pac_id_comunidad, $sa_pac_tabla)
    {
        $parametros = array(
            array(&$sa_pac_id_comunidad, SQLSRV_PARAM_IN),
            array(&$sa_pac_tabla, SQLSRV_PARAM_IN)
        );

        //cambiar a la procedure que es
        //validar para que no se repitan los campos de cedula toca revisar la sentencia
        //$sql = "EXEC SP_CREAR_PACIENTE_FICHA_MEDICA_ESTUDIANTE_15 @sa_pac_id_comunidad = ?, @sa_pac_tabla = ?";
        //sin validar para que no se repitan los campos de cedula
        $sql = "EXEC SP_CREAR_PACIENTE_FICHA_MEDICA_ESTUDIANTE_14 @sa_pac_id_comunidad = ?, @sa_pac_tabla = ?";

        $this->db->ejecutar_procesos_almacenados($sql, $parametros);

        $obtener_id_tabla = $this->obtener_id_tabla_paciente($sa_pac_id_comunidad, $sa_pac_tabla);

        $data = ['sa_pac_id' => $obtener_id_tabla['sa_pac_id'] ?? null, 'sa_pac_tabla' => $obtener_id_tabla['sa_pac_tabla'] ?? null];

        return $data;
    }

    function lista_seguros($query=false)
    {
        $sql = "SELECT * FROM SEGUROS WHERE 1=1";
        if($query)
        {
            $sql.=" AND Plan_seguro like '%".$query."%'";
            
        }
        return $this->db->datos($sql);
    }

    
}
