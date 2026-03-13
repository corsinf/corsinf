<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class fac_clientesM extends BaseModel
{
    protected $tabla = 'CLIENTES';
    protected $primaryKey = 'id_clientes AS _id';

    protected $camposPermitidos = [
        'th_per_id',
        'PERFIL',
        'PASS',
        'POLITICAS_ACEPTACION',
        'DELETE_LOGIC'
    ];

    function lista_clientes($query=false,$ci=false,$id=false,$tipo='C')
    {

        $sql = "SELECT  id_clientes as id,P.th_per_id as idPersona,th_per_nombres_completos,th_per_cedula,th_per_foto_url,th_per_correo,th_per_telefono_1,th_per_direccion 
        FROM CLIENTES C
                INNER JOIN th_personas P ON C.th_per_id = P.th_per_id
                WHERE 1=1 ";
        if($id)
        {
            $sql.=" AND P.th_per_id = '".$id."'";
        }
        if($query)
        {
            $sql.=" and P.th_per_nombres_completos  LIKE '%".$query."%'";
        }
        if($ci)
        {
            $sql.=" and P.th_per_cedula LIKE '%".$ci."%'";
        }
        $sql.=' ORDER by P.th_per_id DESC';
        // print_r($sql);die();
        return $this->db->datos($sql);
    }

    
}

?>