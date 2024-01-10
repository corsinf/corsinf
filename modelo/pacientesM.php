<?php
if (!class_exists('db')) {
    include('../db/db.php');
}
/**
 * 
 */
class pacientesM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
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

        $datos = $this->db->datos($sql);
        return $datos;
    }

    // --------------------------------------------------------------------
    // Funciones validas
    // --------------------------------------------------------------------

    function obtener_informacion_pacienteM($sa_pac_id)
    {

        $parametros = array(
            array(&$sa_pac_id, SQLSRV_PARAM_IN)
        );

        $sql = "EXEC SP_OBTENER_INFORMACION_PACIENTE_6 @sa_pac_id = ?";

        return $this->db->ejecutar_procedimiento_con_retorno_1($sql, $parametros);
    }

    function lista_pacientes_todo()
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
                WHERE 1 = 1 AND sa_pac_estado = 1";

        $sql .= " ORDER BY sa_pac_id DESC;";
        $datos = $this->db->datos($sql);
        return $datos;
    }

    function obtener_idFicha_paciente($id_paciente = '')
    {
        $sql = "SELECT 
                    --p.sa_pac_id,
                    --f.sa_fice_pac_id,
                    f.sa_fice_id
                    
                FROM pacientes p
                LEFT JOIN ficha_medica f ON p.sa_pac_id = f.sa_fice_pac_id
                WHERE p.sa_pac_id = $id_paciente";

        $datos = $this->db->datos($sql);

        $data = [
            'sa_fice_id' => $datos[0]['sa_fice_id'],
            //'sa_pac_id' => $datos[0]['sa_pac_id'],
            //'sa_fice_pac_id' => $datos[0]['sa_fice_pac_id'],
        ];

        return $data;
    }

    function buscar_pacientes($buscar)
    {
        $sql = "SELECT 
                    pac.sa_pac_id,
                    pac.sa_pac_cedula,
                    pac.sa_pac_nombres,
                    pac.sa_pac_apellidos,
                    pac.sa_pac_fecha_nacimiento,
                    pac.sa_pac_correo,
                    pac.sa_pac_id_comunidad,
                    pac.sa_pac_tabla,
                    f.sa_fice_id
                FROM pacientes pac
                LEFT JOIN ficha_medica f ON pac.sa_pac_id = f.sa_fice_pac_id
                WHERE pac.sa_pac_estado = 1 
                AND CONCAT(pac.sa_pac_apellidos, ' ', pac.sa_pac_nombres, ' ', 
                        pac.sa_pac_cedula, ' ', pac.sa_pac_correo) LIKE '%" . $buscar . "%'";

        $datos = $this->db->datos($sql);
        return $datos;
    }

    function buscar_pacientes_ficha_medica($id_paciente)
    {
        $sql = "SELECT 
                    pac.sa_pac_id,
                    pac.sa_pac_cedula,
                    pac.sa_pac_nombres,
                    pac.sa_pac_apellidos,
                    pac.sa_pac_fecha_nacimiento,
                    pac.sa_pac_correo,
                    pac.sa_pac_id_comunidad,
                    pac.sa_pac_tabla,
                    f.sa_fice_id
                FROM pacientes pac
                LEFT JOIN ficha_medica f ON pac.sa_pac_id = f.sa_fice_pac_id
                WHERE pac.sa_pac_estado = 1 
                AND pac.sa_pac_id = $id_paciente";

        $datos = $this->db->datos($sql);
        return $datos;
    }
}
