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
        $sql=" SELECT count(*) as total from pacientes";
        $datos = $this->db->datos($sql);
        return $datos[0]['total'];
    }
    function total_docentes()
    {
        $sql=" SELECT count(*) as total from docentes";
        $datos = $this->db->datos($sql);
        return $datos[0]['total'];
    }
    function total_estudiantes()
    {
        $sql=" SELECT count(*) as total from estudiantes";
        $datos = $this->db->datos($sql);
        return $datos[0]['total'];
    }
    function total_comunidad()
    {
        $sql=" SELECT count(*) as total from comunidad";
        $datos = $this->db->datos($sql);
        return $datos[0]['total'];
    }
    function total_Agendas()
    {
        $sql=" SELECT count(*) as total from consultas_medicas where sa_conp_estado_revision = 0 and sa_conp_fecha_creacion > '".date('Ymd')."'";
        // print_r($sql);die();
        $datos = $this->db->datos($sql);
        return $datos[0]['total'];
    }

    function total_medicamentos()
    {
        $sql=" SELECT count(*) as total from cat_medicamentos";
        $datos = $this->db->datos($sql);
        return $datos[0]['total'];
    }
    function total_insumos()
    {
        $sql=" SELECT count(*) as total from cat_insumos";
        $datos = $this->db->datos($sql);
        return $datos[0]['total'];
    }
    function lista_medicamentos()
    {
        $sql=" SELECT * from cat_medicamentos";
        $datos = $this->db->datos($sql);
        return $datos;
    }
    function lista_insumos()
    {
        $sql=" SELECT * from cat_insumos";
        $datos = $this->db->datos($sql);
        return $datos;
    }
    function total_consultas()
    {
         $sql=" SELECT count(*) as total from consultas_medicas where sa_conp_estado_revision = 1";
        $datos = $this->db->datos($sql);
        return $datos[0]['total'];
    }

    function pacientes_atendidos()
    {
        $sql="SELECT count(*) as total,sa_pac_tabla as tipo FROM pacientes GROUP BY sa_pac_tabla";
          $datos = $this->db->datos($sql);
        return $datos;

    }

}
