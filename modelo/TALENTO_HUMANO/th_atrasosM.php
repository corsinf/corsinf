<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class th_atrasosM extends BaseModel
{
    protected $tabla = 'asis_atrasos';
    protected $primaryKey = 'asi_atrasos_id AS _id';

    protected $camposPermitidos = [
        'th_per_id',
        'asi_fecha_parametrizada',
        'asi_hora_parametrizada',
        'asi_atrasos_fecha_marcacion',
        'asi_atrasos_hora_marcacion',
        'asi_atrasos_total_min',
        'asi_atrasos_justi'
    ];

    function lista_atrasos($persona,$tabla,$desde,$hasta)
    {
        $sql = "SELECT asi_atrasos_id AS _id,th_per_id,asi_fecha_parametrizada,asi_hora_parametrizada,asi_atrasos_fecha_marcacion,asi_atrasos_hora_marcacion,asi_atrasos_total_min
        FROM _asistencias.".$tabla."
        WHERE th_per_id = '".$persona."'
        AND CAST( asi_fecha_parametrizada AS DATE) BETWEEN '".$desde."' and '".$hasta."'
        AND ISNULL(asi_atrasos_justi, 0) <> 1";
        $resultado = $this->db->datos($sql,false,false,true);
        return $resultado;
    }

    function updateJustificacionAtraso($id,$fecha = "")
    {

        $table = "asis_atrasos";
        if($fecha!=''){ $table = "asis_atrasos_".$fecha; }

        $sql = "UPDATE  _asistencias.".$table." SET asi_atrasos_justi = 1
        WHERE asi_atrasos_id = '".$id."'";
        // print_r($sql);die();
        $resultado = $this->db->sql_string($sql,false,true);
        return $resultado;
    }

    function deleteAtraso($id=false,$fecha = "")
    {

        $table = "asis_atrasos";
        if($fecha!=''){ $table = "asis_atrasos_".$fecha; }

        $sql = "DELETE FROM _asistencias.".$table."
        WHERE 1=1 ";
        if($id){ $sql.=" AND th_per_id = '".$id."'";}
        // print_r($sql);die();
        $resultado = $this->db->sql_string($sql,false,true);
        return $resultado;
    }
}