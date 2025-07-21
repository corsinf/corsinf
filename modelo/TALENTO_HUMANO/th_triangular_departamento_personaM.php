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

    function validar_triangulacion($id_persona = '')
    {
        $sql =
            "SELECT 
                t.th_tri_id,
                t.th_tri_nombre,
                -- t.th_tri_descripcion,
                tdp.th_per_id AS persona_origen,
                'PERSONAL' AS origen,
                ti.th_itr_id,
                ti.th_itr_latitud,
                ti.th_itr_longitud,
                ti.th_itr_n_punto
                -- ti.th_itr_fecha_creacion
            FROM th_triangular_departamento_persona tdp
            INNER JOIN th_triangular t ON t.th_tri_id = tdp.th_tri_id
            LEFT JOIN th_triangular_item ti ON ti.th_tri_id = t.th_tri_id
            WHERE tdp.th_per_id = $id_persona AND t.th_tri_estado = 1

            UNION

            SELECT 
                t.th_tri_id,
                t.th_tri_nombre,
                -- t.th_tri_descripcion,
                pd.th_per_id AS persona_origen,
                'POR_DEPARTAMENTO' AS origen,
                ti.th_itr_id,
                ti.th_itr_latitud,
                ti.th_itr_longitud,
                ti.th_itr_n_punto
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
}
