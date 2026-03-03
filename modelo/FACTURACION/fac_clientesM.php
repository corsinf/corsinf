<?php

require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class fac_clientesM extends BaseModel
{
    protected $tabla = 'cliente';
    protected $primaryKey = 'id_cliente AS _id';

    protected $camposPermitidos = [
        'nombre',
        'telefono',
        'mail',
        'direccion',
        'id_empresa',
        'ci_ruc',
        'Razon_Social',
        'tipo',
        'password',
        'td',
        'foto',
        'estado'
    ];

    function lista_clientes($query=false,$ci=false,$id=false,$tipo='C')
    {

        $sql = "SELECT id_cliente as 'id',nombre,ci_ruc,telefono,mail,direccion,Razon_Social as 'razon',foto,estado 
                FROM cliente 
                WHERE tipo = '".$tipo."' ";
        if($id)
        {
            $sql.=" AND id_cliente = '".$id."'";
        }
        if($query)
        {
            $sql.=" and nombre LIKE '%".$query."%'";
        }
        if($ci)
        {
            $sql.=" and ci_ruc LIKE '%".$ci."%'";
        }
        $sql.=' ORDER by id_cliente DESC';
        // print_r($sql);die();
        return $this->db->datos($sql);
    }

    
}

?>