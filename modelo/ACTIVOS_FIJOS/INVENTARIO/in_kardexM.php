<?php 

require_once(dirname(__DIR__,2) . '/GENERAL/BaseModel.php');

class in_kardexM extends BaseModel
{
    protected $tabla = 'in_kardex';
    protected $primaryKey = 'in_kar_id AS _id';

    protected $camposPermitidos = [
        'in_kar_id       AS kar_id',
        'in_kar_codigo_referencia as codigo_referencia',
        'in_kar_fecha as fecha',
        'in_kar_entrada as entrada',
        'in_kar_salida as salida',
        'in_kar_valor_unitario as valor_unitario',
        'in_kar_valor_total as valor_total',
        'in_kar_existencias as existencias',
        'id_proveedor as id_proveedor',
        'in_kar_orden_no as orden_no',
        'in_kar_total_iva as total_iva',
        'in_kar_tipo as tipo',
        'in_kar_factura as factura',
        'in_kar_serie as serie',
        'in_kar_fecha_exp as fecha_exp',
        'in_kar_procedencia as procedencia',
        'in_kar_lote as lote',
        'in_kar_fecha_elaboracion as fecha_elaboracion',
        'in_kar_registro_sanitario as registro_sanitario',
        'in_kar_unidad_medida as unidad_medida',
        'in_kar_total_desc as total_desc',
        'in_kar_subtotal as subtotal',
        'in_kar_id_articulo as id_articulo',
        'id_usuarios as id_usuarios',
    ];

    function listarJoin($desde,$hasta)
    {
        // Construir la parte JOIN de la consulta
        $this->join("ac_articulos","in_kardex.in_kar_id_articulo=ac_articulos.id_articulo",'INNER');
        $this->join('USUARIOS', 'USUARIOS.id_usuarios = in_kardex.id_usuarios');
        $datos = $this->between("in_kar_fecha", $desde,$hasta)->listar();
        // $datos = $this->between("in_kar_fecha", $desde,$hasta)->listar();
        return $datos;
    }
  
}
?>