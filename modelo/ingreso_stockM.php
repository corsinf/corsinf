<?php
if(!class_exists('db'))
{
	include('../db/db.php');
}

/**
 * 
 */
class ingreso_stockM
{
	private $db;
	
	function __construct()
	{
		$this->db = new db();
	}

	function update($tabla,$datos,$where)
	{
		return $this->db->update($tabla,$datos,$where);
	}
	function guardar($tabla,$datos)
	{
		return $this->db->inserts($tabla,$datos);
	}

	function total_stock($id,$tipo)
	{
		$sql = "SELECT SUM(CONVERT(float, sa_kar_entrada))-SUM(CONVERT(float, sa_kar_salida)) as total 
		FROM kardex 
		WHERE sa_kar_id_articulo = ".$id." AND sa_kar_tipo = '".$tipo."'";

		return  $this->db->datos($sql);
	}

	function stock($id,$tipo)
	{
		$sql = "SELECT *
		FROM kardex 
		WHERE sa_kar_id_articulo = ".$id." AND sa_kar_tipo = '".$tipo."'
		ORDER BY sa_kar_id desc ";

		return  $this->db->datos($sql);
	}


	function lista_kardex()
	{
		$sql="SELECT K.sa_kar_fecha as 'Fecha' ,CASE
        WHEN K.sa_kar_tipo = 'Insumos'  THEN I.sa_cins_presentacion
        WHEN K.sa_kar_tipo = 'Medicamento' THEN M.sa_cmed_presentacion
        ELSE 'Sin Nombre'  -- O proporciona un valor por defecto
   		END AS 'Productos',K.sa_kar_tipo as 'Tipo',sa_kar_entrada as 'Entrada',sa_kar_salida as 'Salida',sa_kar_valor_unitario as 'Precio',sa_kar_existencias as 'Stock',sa_kar_serie as 'Serie',sa_kar_factura as 'Factura',K.sa_kar_id_articulo as 'id_ar'
		FROM kardex K
		LEFT JOIN cat_insumos I ON K.sa_kar_tipo = 'Insumos' AND K.sa_kar_id_articulo = I.sa_cins_id
		LEFT JOIN cat_medicamentos M ON K.sa_kar_tipo = 'Medicamento' AND K.sa_kar_id_articulo = M.sa_cmed_id
		WHERE K.sa_kar_entrada>0
		ORDER BY K.sa_kar_id DESC";

		return  $this->db->datos($sql);
	}

}

?>