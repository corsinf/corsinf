<?php
require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');

class lineas_facturasM extends BaseModel
{
    protected $tabla = 'lineas_factura';
    protected $primaryKey = 'id_lineas AS _id';

    protected $camposPermitidos = [
        'producto',
        'cantidad',
        'precio_uni',
        'porc_descuento',
        'descuento',
        'iva',
        'total',
        'id_factura',
        'referencia',
        'porc_iva',
        'Serie_No',
        'subtotal',
        'id_guiaRemi',
        'observacion'
    ];



    function linea_facturas($id)
    {
     $sql= "SELECT id_lineas,producto,cantidad,LF.precio_uni,LF.iva,foto,total,subtotal,descuento,observacion 
     FROM lineas_factura LF 
     INNER JOIN productos P ON LF.referencia = P.referencia
     WHERE id_factura = '".$id."'
     AND LF.Serie_No = '".$_SESSION['INICIO']['SERIE']."' 
     ORDER BY id_lineas DESC";
     // print_r($sql);die();
     $result = $this->db->datos($sql);
        return $result;
    }
    
}
