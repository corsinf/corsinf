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
        $sql = " SELECT count(*) as total from estudiantes where sa_est_estado = 1;";
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
                    LEFT JOIN representantes rep ON (est.sa_id_representante = rep.sa_rep_id OR est.sa_id_representante_2 = rep.sa_rep_id)
                    
                    WHERE pac.sa_pac_estado = 1 
                    AND (est.sa_id_representante = $id_representante OR est.sa_id_representante_2 = $id_representante)
                    AND (rep.sa_rep_id = $id_representante)
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

    //Para consultar las reuniones realizadas por parte del docente y del representante
    function reuniones_realizadas($tipo, $id_busqueda)
    {
        if ($tipo == 'DOCENTES') {
            $select_campos = "hd.ac_docente_id";
            $join_condicion = "INNER JOIN horario_disponible hd ON r.ac_horarioD_id = hd.ac_horarioD_id";
            $where = "hd.ac_docente_id = '$id_busqueda'";
        } elseif ($tipo == 'REPRESENTANTE') {
            $select_campos = "r.ac_representante_id";
            $join_condicion = "";
            $where = "r.ac_representante_id = '$id_busqueda'";
        }

        $sql = "SELECT 
                    r.ac_reunion_motivo AS motivo, 
                    COUNT(r.ac_reunion_motivo) AS total_motivos,  
                    $select_campos
                FROM 
                    reuniones r 
                $join_condicion 
                WHERE $where 
                GROUP BY 
                    r.ac_reunion_motivo, $select_campos";

        $datos = $this->db->datos($sql);
        return $datos;
    }

    function estado_reuniones($tipo, $id_busqueda)
    {

        if ($tipo == 'DOCENTES') {
            $select_campos = "hd.ac_docente_id";
            $join_condicion = "INNER JOIN horario_disponible hd ON r.ac_horarioD_id = hd.ac_horarioD_id";
            $where = "hd.ac_docente_id = '$id_busqueda'";
        } elseif ($tipo == 'REPRESENTANTE') {
            $select_campos = "r.ac_representante_id";
            $join_condicion = "";
            $where = "r.ac_representante_id = '$id_busqueda'";
        }

        $sql = "SELECT 
                    CASE 
                        WHEN r.ac_reunion_estado = 0 THEN 'Pendiente'
                        WHEN r.ac_reunion_estado = 1 THEN 'Completa'
                        WHEN r.ac_reunion_estado = 2 THEN 'Docente Anula'
                        WHEN r.ac_reunion_estado = 3 THEN 'Representante Ausente'
                        ELSE CAST(r.ac_reunion_estado AS VARCHAR)
                    END AS estado,
                    COUNT(r.ac_reunion_estado) AS total_estados,
                    $select_campos
                FROM 
                    reuniones r 
                $join_condicion 
                WHERE $where 
                GROUP BY 
                    CASE 
                        WHEN r.ac_reunion_estado = 0 THEN 'Pendiente'
                        WHEN r.ac_reunion_estado = 1 THEN 'Completa'
                        WHEN r.ac_reunion_estado = 2 THEN 'Docente Anula'
                        WHEN r.ac_reunion_estado = 3 THEN 'Representante Ausente'
                        ELSE CAST(r.ac_reunion_estado AS VARCHAR) 
                    END, 
                    $select_campos";

        $datos = $this->db->datos($sql);
        return $datos;
    }

    function total_horario_dispoible($id_docente, $estado)
    {
        if ($estado != '') {
            $estado = "AND ac_horarioD_estado = '$estado'";
        }

        $sql =
            "SELECT 
                COUNT(*) AS total_estado,
                ac_docente_id
            FROM 
                horario_disponible
            WHERE 
                ac_docente_id = '$id_docente'
                $estado
            GROUP BY 
                ac_docente_id;";

        $datos = $this->db->datos($sql);
        return $datos[0]['total_estado'];
    }

    function total_horario_clases($id_docente)
    {
        $sql =
            "SELECT COUNT(*) AS total 
            FROM 
                horario_clases 
            WHERE 
                ac_docente_id = '$id_docente';";

        $datos = $this->db->datos($sql);
        return $datos[0]['total'];
    }

    function total_clases($id_docente)
    {
        $sql =
            "SELECT COUNT(*) AS total 
            FROM 
                docente_paralelo 
            WHERE 
                ac_docente_id = '$id_docente';";

        $datos = $this->db->datos($sql);
        return $datos[0]['total'];
    }

    function total_historial_estudiantil_docente($id_docente)
    {
        $sql =
            "SELECT COUNT(*) AS total
            FROM 
                consultas_medicas cm
                                
            INNER JOIN ficha_medica fm ON cm.sa_fice_id = fm.sa_fice_id
            INNER JOIN pacientes pac ON fm.sa_fice_pac_id = pac.sa_pac_id
            INNER JOIN estudiantes est ON pac.sa_pac_id_comunidad = est.sa_est_id
            INNER JOIN cat_paralelo par ON est.sa_id_paralelo = par.sa_par_id
            INNER JOIN docente_paralelo dop ON est.sa_id_paralelo = dop.ac_paralelo_id 

            WHERE 
                sa_conp_estado = 1
                AND pac.sa_pac_tabla = 'estudiantes'
                AND dop.ac_docente_id = '$id_docente';";

        $datos = $this->db->datos($sql);
        return $datos[0]['total'];
    }

    function lista_horario_clases_paralelo($id_paralelo = '')
    {
        if ($id_paralelo != '') {
            $sql =
                "SELECT 
                    hcd.ac_horarioC_id,
                    hcd.ac_docente_id,
                    hcd.ac_paralelo_id,
                    hcd.ac_horarioC_inicio,
                    hcd.ac_horarioC_fin,
                    hcd.ac_horarioC_dia,
                    hcd.ac_horarioC_materia,
                    hcd.ac_horarioC_fecha_creacion,
                    hcd.ac_horarioC_fecha_modificacion,
                    hcd.ac_horarioC_estado,

                    cs.sa_sec_id, 
                    cs.sa_sec_nombre, 
                    cg.sa_gra_id, 
                    cg.sa_gra_nombre,
                    cp.sa_par_id, 
                    cp.sa_par_nombre

                    FROM horario_clases hcd

                    INNER JOIN cat_paralelo cp ON hcd.ac_paralelo_id = cp.sa_par_id
                    INNER JOIN cat_seccion cs ON cp.sa_id_seccion = cs.sa_sec_id
                    INNER JOIN cat_grado cg ON cp.sa_id_grado = cg.sa_gra_id

                    WHERE 1 = 1 ";

            if ($id_paralelo != '') {
                $sql .= "AND hcd.ac_paralelo_id = $id_paralelo";
            }

            $sql .= " ORDER BY hcd.ac_horarioC_id;";
            $datos = $this->db->datos($sql);
            return $datos;
        }
    }
}
