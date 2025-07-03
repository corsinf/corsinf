<?php

require_once(dirname(__DIR__) . '/GENERAL/BaseModel.php');

class ac_articulos_itM extends BaseModel
{
    protected $tabla = 'ac_articulos_it';
    protected $primaryKey = 'ac_ait_id AS _id';

    protected $camposPermitidos = [
        'ac_ait_id_articulo       AS id_articulo',
        'ac_ait_sistema_op        AS sistema_op',
        'ac_ait_version           AS version',
        'ac_ait_arquitectura      AS arquitectura',
        'ac_ait_service_pack      AS service_pack',
        'ac_ait_kernel            AS kernel',
        'ac_ait_edicion           AS edicion',
        'ac_ait_producto_id       AS producto_id',
        'ac_ait_serie_numero      AS serie_numero',
        'ac_ait_mac_address       AS mac_address',
        'ac_ait_ip_address        AS ip_address',
        'ac_ait_fecha_mantenimiento AS fecha_mantenimiento',
        'ac_ait_fecha_creacion    AS fecha_creacion',
        'ac_ait_fecha_modificacion AS fecha_modificacion',
        'ac_ait_sku               AS ait_sku'
    ];

  
}
