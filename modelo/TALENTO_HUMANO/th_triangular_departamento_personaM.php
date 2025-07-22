<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_triangular_departamento_personaM extends BaseModel
{
    protected $tabla = 'th_triangular_departamento_persona';
    protected $primaryKey = 'th_tdp_id AS _id';

    protected $camposPermitidos = [
        'th_tri_id AS tri_id',
        'th_dep_id AS dep_id', // ← corregido
        'th_per_id AS per_id',
        'th_tdp_estado AS estado',
        'th_tdp_fecha_creacion AS fecha_creacion',
        'th_tdp_fecha_modificacion AS fecha_modificacion'
    ];

    function listarJoin()
    {
        $this->join('th_departamentos', 'th_departamentos.th_dep_id = th_triangular_departamento_persona.th_dep_id'); // ← corregido
        $this->join('th_triangular', 'th_triangular.th_tri_id = th_triangular_departamento_persona.th_tri_id'); // ← corregido
        return $this->listar();
    }

    function Listar_Departamento_Triangulacion()
    {
        $sql = "
        SELECT 
            t.th_tri_id,
            t.th_tri_nombre,
            d.th_dep_id,
            d.th_dep_nombre,
            tdp.th_tdp_id,
            tdp.th_per_id,
            tdp.th_tdp_estado,
            tdp.th_tdp_fecha_creacion
        FROM th_triangular_departamento_persona tdp
        INNER JOIN th_departamentos d ON d.th_dep_id = tdp.th_dep_id
        INNER JOIN th_triangular t ON t.th_tri_id = tdp.th_tri_id
        WHERE tdp.th_tdp_estado = 1
    ";

        $datos = $this->db->datos($sql);
        return $datos;
    }
    function Listar_Personas_Triangulacion()
    {
        $sql = "
        SELECT 
            t.th_tri_id,
            t.th_tri_nombre,
            tdp.th_tdp_id,
            tdp.th_per_id,
            tdp.th_tdp_estado,
            tdp.th_tdp_fecha_creacion,
			p.th_per_primer_nombre + ' '+ p.th_per_primer_apellido AS 'nombre_completo'
        FROM th_triangular_departamento_persona tdp
        INNER JOIN th_triangular t ON t.th_tri_id = tdp.th_tri_id
        INNER JOIN th_personas p ON p.th_per_id = tdp.th_per_id
        WHERE tdp.th_tdp_estado = 1
    ";

        $datos = $this->db->datos($sql);
        return $datos;
    }

    function buscar($parametros)
    {

        $id_departamento = isset($parametros['id_departamento']) ? $parametros['id_departamento'] : null;
        $id_usuario = isset($parametros['id_usuario']) ? $parametros['id_usuario'] : null;

        if ($id_departamento > 0 || $id_usuario == 0) {
            $sql = "
   SELECT tri.th_tri_id, tri.th_tri_nombre
    FROM th_triangular AS tri
    WHERE tri.th_tri_estado = 1 AND usu_id != 2
    AND tri.th_tri_id NOT IN (
        SELECT th_tri_id
        FROM th_triangular_departamento_persona
        WHERE th_dep_id = $id_departamento AND th_tdp_estado = 1 
    )
";
        } else if ($id_usuario > 0 || $id_departamento == 0) {
            $sql = "
  SELECT tri.th_tri_id, tri.th_tri_nombre
    FROM th_triangular AS tri
    WHERE tri.th_tri_estado = 1 AND usu_id != 2
    AND tri.th_tri_id NOT IN (
        SELECT th_tri_id
        FROM th_triangular_departamento_persona
        WHERE th_per_id = $id_usuario AND th_tdp_estado = 1 
    )
";
        }

        $datos = $this->db->datos($sql);


        return $datos;
    }



    function validar_triangulacion($id_persona = '')
    {
        if ($id_persona == '') {
            return [];
        }

        $sql =
            "SELECT 
                t.th_tri_id AS _id,
                t.th_tri_nombre AS nombre,
                -- t.th_tri_descripcion,
                tdp.th_per_id AS persona_origen,
                'PERSONAL' AS origen,
                ti.th_itr_id AS item_id,
                ti.th_itr_latitud AS latitud,
                ti.th_itr_longitud AS longitud,
                ti.th_itr_n_punto AS n_punto
                -- ti.th_itr_fecha_creacion
            FROM th_triangular_departamento_persona tdp
            INNER JOIN th_triangular t ON t.th_tri_id = tdp.th_tri_id
            LEFT JOIN th_triangular_item ti ON ti.th_tri_id = t.th_tri_id
            WHERE tdp.th_per_id = $id_persona AND t.th_tri_estado = 1

            UNION

            SELECT 
                t.th_tri_id AS _id,
                t.th_tri_nombre AS nombre,
                -- t.th_tri_descripcion,
                pd.th_per_id AS persona_origen,
                'POR_DEPARTAMENTO' AS origen,
                ti.th_itr_id AS item_id,
                ti.th_itr_latitud AS latitud,
                ti.th_itr_longitud AS longitud,
                ti.th_itr_n_punto AS n_punto
                -- ti.th_itr_fecha_creacion
            FROM th_triangular_departamento_persona tdp
            INNER JOIN th_triangular t ON t.th_tri_id = tdp.th_tri_id
            INNER JOIN th_personas_departamentos pd ON pd.th_dep_id = tdp.th_dep_id
            LEFT JOIN th_triangular_item ti ON ti.th_tri_id = t.th_tri_id
            WHERE pd.th_per_id = $id_persona AND t.th_tri_estado = 1;";

        // print_r($sql); exit(); die();

        $datos = $this->db->datos($sql);
        return $datos;
    }

    function listar_pdt($id_persona = '')
    {

        if ($id_persona == '') {
            return [];
        }

        $sql =
            "SELECT
                t.th_tri_id AS _id,
                t.th_tri_nombre AS nombre,
                tdp.th_per_id AS persona_origen,
                'PERSONAL' AS origen,
                t.th_tri_descripcion AS descripcion,
                t.th_tri_fecha_creacion AS fecha_creacion
            FROM
                th_triangular_departamento_persona tdp
            INNER JOIN th_triangular t ON t.th_tri_id = tdp.th_tri_id
            WHERE
            tdp.th_per_id = $id_persona  
            AND t.th_tri_estado = 1
            
            UNION

            SELECT
                t.th_tri_id AS _id,
                t.th_tri_nombre AS nombre,
                pd.th_per_id AS persona_origen,
                'POR_DEPARTAMENTO' AS origen,
                t.th_tri_descripcion AS descripcion,
                t.th_tri_fecha_creacion AS fecha_creacion

            FROM
                th_triangular_departamento_persona tdp
            INNER JOIN th_triangular t ON t.th_tri_id = tdp.th_tri_id
            INNER JOIN th_personas_departamentos pd ON pd.th_dep_id = tdp.th_dep_id
            WHERE
            pd.th_per_id = $id_persona 
            AND t.th_tri_estado = 1;";

        // print_r($sql); exit(); die();

        $datos = $this->db->datos($sql);
        return $datos;
    }
}
