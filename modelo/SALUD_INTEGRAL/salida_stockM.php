<?php
if (!class_exists('db')) {
	include(dirname(__DIR__, 2) . '/db/db.php');
}
/**
 * 
 */
class salida_stockM
{
	private $db;

	function __construct()
	{
		$this->db = new db();
	}

	function insertar($table, $datos)
	{
		$rest = $this->db->inserts($table, $datos);

		return $rest;
	}

	function editar($table, $datos, $where)
	{

		$rest = $this->db->update($table, $datos, $where);
		return $rest;
	}

	function eliminar($table, $datos)
	{
		$sql = "UPDATE ac_marcas SET ESTADO='I' WHERE " . $datos[0]['campo'] . "='" . $datos[0]['dato'] . "';";
		$datos = $this->db->sql_string($sql);
		return $datos;

		//$rest = $this->db->delete('ac_marcas',$datos);
		//return $rest;
	}

	function lista_kardex($entrada = false, $salida = false)
	{
		$sql =
			"SELECT K.sa_kar_fecha AS 'Fecha',
				CASE
					WHEN K.sa_kar_tipo = 'Insumos'  THEN CONCAT(I.sa_cins_nombre_comercial, ' (', I.sa_cins_presentacion, ')')
					WHEN K.sa_kar_tipo = 'Medicamento' THEN CONCAT(M.sa_cmed_nombre_comercial, ' (', M.sa_cmed_presentacion, ')')
					ELSE 'Sin Nombre'  -- O proporciona un valor por defecto
				END AS 'Productos',
					K.sa_kar_tipo AS 'Tipo',
					K.sa_kar_entrada AS'Entrada',
					K.sa_kar_salida AS 'Salida',
					K.sa_kar_valor_unitario AS 'Precio',
					K.sa_kar_existencias AS 'Stock',
					K.sa_kar_serie AS 'Serie',
					K.sa_kar_factura AS 'Factura',
					K.sa_kar_orden_no AS 'Orden',
					K.sa_kar_id_articulo AS 'id_ar',
					K.sa_kar_id_articulo AS 'id_ar',
					U.nombres AS 'Nombre',
					U.apellidos AS 'Apellido',
					K.id_usuarios AS 'Usu_id'

			FROM kardex K
			LEFT JOIN cat_insumos I ON K.sa_kar_tipo = 'Insumos' AND K.sa_kar_id_articulo = I.sa_cins_id
			LEFT JOIN cat_medicamentos M ON K.sa_kar_tipo = 'Medicamento' AND K.sa_kar_id_articulo = M.sa_cmed_id
			LEFT JOIN usuarios U ON K.id_usuarios = U.id_usuarios

			WHERE 1=1 ";

		if ($entrada) {
			$sql .= " AND K.sa_kar_entrada > 0 ";
			//$sql .= " AND TRY_CONVERT(DECIMAL(18,2), K.sa_kar_entrada) > 0 ";
		}
		if ($salida) {
			//$sql .= " AND K.sa_kar_salida > 0 ";
			$sql .= " AND TRY_CONVERT(DECIMAL(18,2), K.sa_kar_salida) > 0 ";
		}
		$sql .= " ORDER BY K.sa_kar_id DESC;";

		//print_r($sql);die();

		return  $this->db->datos($sql);
	}
}
