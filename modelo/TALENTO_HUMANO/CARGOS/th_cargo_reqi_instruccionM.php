<?php

require_once(dirname(__DIR__, 2) . '/GENERAL/BaseModel.php');

class th_cargo_reqi_instruccionM extends BaseModel
{
    protected $tabla = 'th_cargo_reqi_instruccion';

    protected $primaryKey = 'th_reqi_instruccion_id AS _id';

    protected $camposPermitidos = [
        'id_cargo',
        'id_nivel_academico',
        'th_reqi_estado AS estado',
        'th_reqi_fecha_creacion AS fecha_creacion',
        'th_reqi_fecha_modificacion AS fecha_modificacion'
    ];


    public function listar_cargo_instruccion($id_instruccion = '', $id_cargo = '')
    {
        $sql = "SELECT 
                ri.th_reqi_instruccion_id AS _id,
                ri.id_cargo,
                ri.id_nivel_academico,
                ri.th_reqi_estado,
                ri.th_reqi_fecha_creacion,
                ri.th_reqi_fecha_modificacion,
                na.descripcion AS nivel_academico_descripcion
            FROM th_cargo_reqi_instruccion ri
            LEFT JOIN th_cat_pos_nivel_academico na 
                ON ri.id_nivel_academico = na.id_nivel_academico
            WHERE ri.th_reqi_estado = 1 ";

        if (!empty($id_instruccion)) {
            $id_instruccion = intval($id_instruccion);
            $sql .= " AND ri.th_reqi_instruccion_id = $id_instruccion";
        }

        if (!empty($id_cargo)) {
            $id_cargo = intval($id_cargo);
            $sql .= " AND ri.id_cargo = $id_cargo";
        }

        $sql .= " ORDER BY na.descripcion ASC";

        return $this->db->datos($sql);
    }


    public function listar_niveles_no_asignados($id_cargo, $id_plaza = 0)
    {
        $id_c = intval($id_cargo);
        $id_p = intval($id_plaza);

        if ($id_p != 0) {
            // CON PLAZA: mostrar solo los del cargo que NO están aún en la plaza
            $sql = "
    SELECT 
        na.id_nivel_academico,
        na.descripcion,
        na.estado
    FROM th_cat_pos_nivel_academico na
    LEFT JOIN th_cargo_reqi_instruccion ri_cargo
        ON ri_cargo.id_nivel_academico = na.id_nivel_academico
        AND ri_cargo.id_cargo = $id_c
        AND ri_cargo.th_reqi_estado = 1
    LEFT JOIN cn_plaza_reqi_instruccion ri_plaza
        ON ri_plaza.id_nivel_academico = na.id_nivel_academico
        AND ri_plaza.cn_pla_id = $id_p
        AND ri_plaza.cn_reqi_estado = 1
        AND $id_p > 0
    WHERE na.estado = 1
      AND ri_cargo.id_nivel_academico IS NULL
      AND ($id_p = 0 OR ri_plaza.id_nivel_academico IS NULL)
    ORDER BY na.id_nivel_academico;
    ";
        } else {
            // SIN PLAZA: comportamiento original — niveles NO asignados al cargo
            $sql = "
            SELECT 
                na.id_nivel_academico,
                na.descripcion,
                na.estado
            FROM th_cat_pos_nivel_academico na
            LEFT JOIN th_cargo_reqi_instruccion ri_cargo
                ON ri_cargo.id_nivel_academico = na.id_nivel_academico
                AND ri_cargo.id_cargo = $id_c
                AND ri_cargo.th_reqi_estado = 1
            WHERE na.estado = 1
              AND ri_cargo.id_nivel_academico IS NULL
            ORDER BY na.id_nivel_academico
        ";
        }

        return $this->db->datos($sql);
    }
}
