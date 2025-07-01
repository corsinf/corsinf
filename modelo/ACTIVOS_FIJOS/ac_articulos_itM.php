<?php

if (! class_exists('db')) {
    include dirname(__DIR__, 2) . '/db/db.php';
}

class articulositM
{
    private $db;

    public function __construct()
    {
        $this->db = new db();
    }

    /**
     * Listar artículos (sólo activos)
     */
    public function cargar_datos_it($id)
{
    $id = intval($id);

    $sql = "
        SELECT
            ac_ait_id                 AS id,
            ac_ait_id_articulo       AS id_articulo,
            ac_ait_sistema_op        AS sistema_op,
            ac_ait_version           AS version,
            ac_ait_arquitectura      AS arquitectura,
            ac_ait_service_pack      AS service_pack,
            ac_ait_kernel            AS kernel,
            ac_ait_edicion           AS edicion,
            ac_ait_producto_id       AS producto_id,
            ac_ait_serie_numero      AS serie_numero,
            ac_ait_mac_address       AS mac_address,
            ac_ait_ip_address        AS ip_address,
            ac_ait_fecha_mantenimiento AS fecha_mantenimiento,
            ac_ait_fecha_creacion    AS fecha_creacion,
            ac_ait_fecha_modificacion AS fecha_modificacion
        FROM ac_articulos_it
        WHERE ac_ait_id_articulo = $id
    ";

    return $this->db->datos($sql);
}



    /**
     * Buscar artículos por sistema operativo o serie (LIKE '%q%')
     */
    public function buscar_articulos($q)
    {
        $buscar = trim($q);
        $sql = "
            SELECT TOP 1000
                ac_ait_id               AS id,
                ac_ait_sistema_op       AS sistema_op,
                ac_ait_version          AS version,
                ac_ait_arquitectura     AS arquitectura,
                ac_ait_service_pack     AS service_pack,
                ac_ait_kernel           AS kernel,
                ac_ait_edicion          AS edicion,
                ac_ait_producto_id      AS producto_id,
                ac_ait_serie_numero     AS serie_numero,
                ac_ait_mac_address      AS mac_address,
                ac_ait_ip_address       AS ip_address,
                ac_ait_id_articulo      AS id_articulo,
                ac_ait_fecha_creacion   AS created_at,
                ac_ait_fecha_modificacion AS updated_at
            FROM [ACTIVOS_DESARROLLO].[dbo].[ac_articulos_it]
            WHERE ac_ait_sistema_op   LIKE '%" . $buscar . "%'
               OR ac_ait_serie_numero LIKE '%" . $buscar . "%'
            ORDER BY ac_ait_id
        ";
        return $this->db->datos($sql);
    }

    /**
     * Obtener artículo por MAC exacta
     */
    public function buscar_por_mac($mac)
    {
        $m = trim($mac);
        $sql = "
            SELECT TOP 1000
                ac_ait_id               AS id,
                ac_ait_sistema_op       AS sistema_op,
                ac_ait_version          AS version,
                ac_ait_arquitectura     AS arquitectura,
                ac_ait_service_pack     AS service_pack,
                ac_ait_kernel           AS kernel,
                ac_ait_edicion          AS edicion,
                ac_ait_producto_id      AS producto_id,
                ac_ait_serie_numero     AS serie_numero,
                ac_ait_mac_address      AS mac_address,
                ac_ait_ip_address       AS ip_address,
                ac_ait_id_articulo      AS id_articulo,
                ac_ait_fecha_creacion   AS created_at,
                ac_ait_fecha_modificacion AS updated_at
            FROM [ACTIVOS_DESARROLLO].[dbo].[ac_articulos_it]
            WHERE ac_ait_mac_address = '" . $m . "'
            ORDER BY ac_ait_id
        ";
        return $this->db->datos($sql);
    }

    /**
     * Insertar nuevo artículo TI
     */
    public function insertar(array $datos)
    {
        return $this->db->inserts('ac_articulos_it', $datos);
    }

    /**
     * Editar artículo TI
     */
    public function editar(array $datos, array $where)
    {
        return $this->db->update('ac_articulos_it', $datos, $where);
    }

    /**
     * Eliminar (o inactivar) artículo TI
     */
    public function eliminar(int $id)
    {
        $sql = "
            DELETE FROM [ACTIVOS_DESARROLLO].[dbo].[ac_articulos_it]
            WHERE ac_ait_id = " . intval($id) . "
        ";
        return $this->db->sql_string($sql);
    }
}
