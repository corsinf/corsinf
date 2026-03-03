<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_faltasM extends BaseModel
{
    protected $tabla = 'asis_faltas';
    protected $primaryKey = 'asi_faltas_id AS _id';

    protected $camposPermitidos = [
        'th_per_id',
        'th_dep_id',
        'asi_faltas_fecha_inicio',
        'asi_faltas_fecha_fin',
        'asi_faltas_total_min',
        'asi_faltas_justi'
    ];

    function lista_faltas($persona,$tabla,$desde,$hasta)
    {
        $sql = "SELECT asi_faltas_id AS _id,th_per_id,th_dep_id,asi_faltas_fecha_inicio,asi_faltas_fecha_fin,asi_faltas_total_min
        FROM _asistencias.".$tabla."
        WHERE th_per_id = '".$persona."'
        AND CAST(asi_faltas_fecha_inicio AS DATE) BETWEEN '".$desde."' and '".$hasta."'
        AND ISNULL(asi_faltas_justi, 0) <> 1";
        // print_r($sql);die();
        $resultado = $this->db->datos($sql,false,false,true);
        return $resultado;
    }

    function updateJustificacionFaltas($id,$fecha="")
    {
        $table = "asis_faltas";
        if($fecha!=''){ $table = "asis_faltas_".$fecha; }

        $sql = "UPDATE  _asistencias.".$table." SET asi_faltas_justi = 1
        WHERE asi_faltas_id = '".$id."'";
        $resultado = $this->db->sql_string($sql,false,true);
        return $resultado;
    }

    function deleteFaltas($id=false,$fecha="")
    {
        $table = "asis_faltas";
        if($fecha!=''){ $table = "asis_faltas_".$fecha; }

        $sql = "DELETE  FROM _asistencias.".$table."
        WHERE 1=1 ";
        if($id){ $sql.=" AND  th_per_id = '".$id."'";}
        $resultado = $this->db->sql_string($sql,false,true);
        return $resultado;
    }
}