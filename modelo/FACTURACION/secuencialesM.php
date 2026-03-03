<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class secuencialesM extends BaseModel
{
    protected $tabla = 'SECUENCIALES';
    protected $primaryKey = 'ID_SECUENCIALES AS _id';

    protected $camposPermitidos = [
        'DETALLE AS descripcion',
        'NUMERO AS numero',
        'FIRMADOS AS firmado',
        'id_empresa AS empresa'
    ];

    function validar_mas_series($query=FALSE)
    {
        $sql = "SELECT * 
        FROM SECUENCIALES
        WHERE id_empresa = '".$_SESSION['INICIO']['ID_EMPRESA']."' ";
        if($query)
        {
            $sql.=" AND DETALLE like '%".$query."%'";
        }
            $respuest  = $this->db->datos($sql);
        return $respuest;
    }

    
}
