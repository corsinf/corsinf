<?php
if (!class_exists('db')) {
    include('../db/db.php');
}
/**
 * 
 */
class index_saludM
{
    private $db;

    function __construct()
    {
        $this->db = new db();
    }

    function total_pacientes()
    {
        $sql = " SELECT count(*) as total from pacientes";
        $datos = $this->db->datos($sql);
        return $datos[0]['total'];
    }

    function total_docentes()
    {
        $sql = " SELECT count(*) as total from docentes";
        $datos = $this->db->datos($sql);
        return $datos[0]['total'];
    }

    function total_estudiantes()
    {
        $sql = " SELECT count(*) as total from estudiantes";
        $datos = $this->db->datos($sql);
        return $datos[0]['total'];
    }

    function total_comunidad()
    {
        $sql = " SELECT count(*) as total from comunidad";
        $datos = $this->db->datos($sql);
        return $datos[0]['total'];
    }

    function total_Agendas()
    {
        //sa_conp_fecha_creacion
        $sql = " SELECT count(*) as total from consultas_medicas where sa_conp_estado_revision = 0 and sa_conp_fecha_ingreso = '" . date('Ymd') . "'";
        // print_r($sql);die();
        $datos = $this->db->datos($sql);
        return $datos[0]['total'];
    }

    function total_medicamentos()
    {
        $sql = " SELECT count(*) as total from cat_medicamentos";
        $datos = $this->db->datos($sql);
        return $datos[0]['total'];
    }

    function total_insumos()
    {
        $sql = " SELECT count(*) as total from cat_insumos";
        $datos = $this->db->datos($sql);
        return $datos[0]['total'];
    }

    function lista_medicamentos()
    {
        $sql = " SELECT * from cat_medicamentos";
        $datos = $this->db->datos($sql);
        return $datos;
    }

    function lista_insumos()
    {
        $sql = " SELECT * from cat_insumos";
        $datos = $this->db->datos($sql);
        return $datos;
    }

    function total_consultas()
    {
        $sql = " SELECT count(*) as total from consultas_medicas where sa_conp_estado_revision = 1";
        $datos = $this->db->datos($sql);
        return $datos[0]['total'];
    }

    function pacientes_atendidos()
    {
        $sql = "SELECT count(*) as total,sa_pac_tabla as tipo FROM pacientes GROUP BY sa_pac_tabla";
        $datos = $this->db->datos($sql);
        return $datos;
    }

    function estudiantes_atendidos($id_representante)
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
                        f.sa_fice_id,
                    
                        est.sa_est_id,
                        est.sa_est_primer_apellido,
                        est.sa_est_segundo_apellido,
                        est.sa_est_primer_nombre,
                        est.sa_est_segundo_nombre,
                        CONCAT(est.sa_est_primer_apellido, ' ', est.sa_est_primer_nombre) AS nombre_estudiante,
                        est.sa_est_cedula,
                        est.sa_est_sexo,
                        est.sa_est_fecha_nacimiento,
                        est.sa_id_representante,
                        est.sa_est_rep_parentesco,
                        est.sa_est_tabla,
                        est.sa_est_correo,
                        est.sa_est_foto_url,
                    
                        rep.sa_rep_primer_apellido,
                        rep.sa_rep_primer_nombre,
                    
                    
                        COUNT(cm.sa_conp_id) AS consultas_total
                    
                    FROM consultas_medicas cm
                    INNER JOIN ficha_medica f ON cm.sa_fice_id = f.sa_fice_pac_id
                    INNER JOIN pacientes pac ON f.sa_fice_pac_id = pac.sa_pac_id
                    
                    LEFT JOIN estudiantes est ON pac.sa_pac_id_comunidad = est.sa_est_id
                    LEFT JOIN representantes rep ON est.sa_id_representante = rep.sa_rep_id
                    
                    WHERE pac.sa_pac_estado = 1 
                    AND est.sa_id_representante = $id_representante
                    AND sa_pac_tabla = 'estudiantes'
                    
                    GROUP BY 
                        pac.sa_pac_id,
                        pac.sa_pac_cedula,
                        pac.sa_pac_nombres,
                        pac.sa_pac_apellidos,
                        pac.sa_pac_fecha_nacimiento,
                        pac.sa_pac_correo,
                        pac.sa_pac_id_comunidad,
                        pac.sa_pac_tabla,
                        f.sa_fice_id,
                        est.sa_est_id,
                        est.sa_est_primer_apellido,
                        est.sa_est_segundo_apellido,
                        est.sa_est_primer_nombre,
                        est.sa_est_segundo_nombre,
                        est.sa_est_cedula,
                        est.sa_est_sexo,
                        est.sa_est_fecha_nacimiento,
                        est.sa_id_representante,
                        est.sa_est_rep_parentesco,
                        est.sa_est_tabla,
                        est.sa_est_correo,
                        est.sa_est_foto_url,
                        rep.sa_rep_primer_apellido,
                        rep.sa_rep_primer_nombre;";
                
            $datos = $this->db->datos($sql);
            return $datos;
        
    }
}
