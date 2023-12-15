<?php
if (!class_exists('db_salud')) {
    include('../db/db_salud.php');
}
/**
 * 
 */
class pacientesM
{
    private $db_salud;

    function __construct()
    {
        $this->db_salud = new db_salud();
    }

    function buscar_paciente($sa_pac_id_comunidad, $sa_pac_tabla)
    {
        $data = array();
        if ($sa_pac_tabla === 'estudiantes') {

            $estudiante = $this->buscar_estudiante($sa_pac_id_comunidad);

            $data = [
                'sa_pac_id' => $estudiante[0]['sa_pac_id'],
                'sa_pac_id_comunidad' => $estudiante[0]['sa_pac_id_comunidad'],
                'sa_pac_primer_apellido' => $estudiante[0]['sa_est_primer_apellido'],
                'sa_pac_segundo_apellido' => $estudiante[0]['sa_est_segundo_apellido'],
                'sa_pac_primer_nombre' => $estudiante[0]['sa_est_primer_nombre'],
                'sa_pac_segundo_nombre' => $estudiante[0]['sa_est_segundo_nombre'],
                'sa_pac_cedula' => $estudiante[0]['sa_est_cedula'],
                'sa_pac_sexo' => $estudiante[0]['sa_est_sexo'],
                'sa_pac_fecha_nacimiento' => $estudiante[0]['sa_est_fecha_nacimiento'],
                'sa_id_seccion' => $estudiante[0]['sa_id_seccion'],
                'sa_id_grado' => $estudiante[0]['sa_id_grado'],
                'sa_id_paralelo' => $estudiante[0]['sa_id_paralelo'],
                'sa_id_representante' => $estudiante[0]['sa_id_representante'],
                'sa_est_rep_parentesco' => $estudiante[0]['sa_est_rep_parentesco'],
                'sa_pac_tabla' => $estudiante[0]['sa_est_tabla'],
                'sa_pac_correo' => $estudiante[0]['sa_est_correo'],

                'sa_sec_nombre' => $estudiante[0]['sa_sec_nombre'],
                'sa_gra_nombre' => $estudiante[0]['sa_gra_nombre'],
                'sa_par_nombre' => $estudiante[0]['sa_par_nombre'],
            ];

            return $data;
        }
    }

    function buscar_estudiante($sa_pac_id_comunidad)
    {
        $sql = "SELECT 
                    pac.sa_pac_id,
                    pac.sa_pac_id_comunidad,
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

                    cs.sa_sec_nombre, 
                    cg.sa_gra_nombre,
                    pr.sa_par_nombre
        
                FROM pacientes pac
                INNER JOIN estudiantes est ON pac.sa_pac_id_comunidad = est.sa_est_id
                INNER JOIN cat_seccion cs ON est.sa_id_seccion = cs.sa_sec_id
                INNER JOIN cat_grado cg ON est.sa_id_grado = cg.sa_gra_id
                INNER JOIN cat_paralelo pr ON est.sa_id_paralelo = pr.sa_par_id
                WHERE pac.sa_pac_id = $sa_pac_id_comunidad;";

        $datos = $this->db_salud->datos($sql);
        return $datos;
    }

    function obtener_informacion_pacienteM($sa_pac_id)
    {

        $parametros = array(
            array(&$sa_pac_id, SQLSRV_PARAM_IN)
        );

        $sql = "EXEC SP_OBTENER_INFORMACION_PACIENTE_6 @sa_pac_id = ?";

        return $this->db_salud->ejecutar_procedimiento_con_retorno_1($sql, $parametros);
    }

    function lista_pacientes_todo($id = '')
    {
        $sql = "SELECT
                    sa_pac_id,
                    sa_pac_cedula,
                    sa_pac_nombres,
                    sa_pac_apellidos,
                    sa_pac_fecha_nacimiento,
                    sa_pac_correo,
                    sa_pac_id_comunidad,
                    sa_pac_tabla
                FROM
                    pacientes
                WHERE 1 = 1";

        $sql .= " ORDER BY sa_pac_id;";
        $datos = $this->db_salud->datos($sql);
        return $datos;
    }
}

