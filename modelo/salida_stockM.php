<?php 
if(!class_exists('db'))
{
	include_once('../db/db.php');
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

	function insertar($table,$datos)
	{
		 $rest = $this->db->inserts($table,$datos);
	   
		return $rest;
	}
	function editar($table,$datos,$where)
	{
		
	    $rest = $this->db->update($table,$datos,$where);
		return $rest;
	}
	function eliminar($table,$datos)
	{
		$sql = "UPDATE MARCAS SET ESTADO='I' WHERE ".$datos[0]['campo']."='".$datos[0]['dato']."';";
		$datos = $this->db->sql_string($sql);
		return $datos;

	    //$rest = $this->db->delete('MARCAS',$datos);
		//return $rest;
	}
	
	function lista_kardex($entrada=false,$salida=false)
	{
		$sql="SELECT K.sa_kar_fecha as 'Fecha' ,CASE
        WHEN K.sa_kar_tipo = 'Insumos'  THEN I.sa_cins_presentacion
        WHEN K.sa_kar_tipo = 'Medicamento' THEN M.sa_cmed_presentacion
        ELSE 'Sin Nombre'  -- O proporciona un valor por defecto
   		END AS 'Productos',K.sa_kar_tipo as 'Tipo',sa_kar_entrada as'Entrada',sa_kar_salida as 'Salida',sa_kar_valor_unitario as 'Precio',sa_kar_existencias as 'Stock',sa_kar_serie as 'Serie',sa_kar_factura as 'Factura',k.sa_kar_orden_no as 'Orden',K.sa_kar_id_articulo as 'id_ar'
		FROM kardex K
		LEFT JOIN cat_insumos I ON K.sa_kar_tipo = 'Insumos' AND K.sa_kar_id_articulo = I.sa_cins_id
		LEFT JOIN cat_medicamentos M ON K.sa_kar_tipo = 'Medicamento' AND K.sa_kar_id_articulo = M.sa_cmed_id
		WHERE 1=1 ";
		if($entrada)
		{
			$sql.=" AND K.sa_kar_entrada>0 ";
		}
		if($salida)
		{
			$sql.=" AND K.sa_kar_salida>0 ";
		}
		$sql.=" ORDER BY K.sa_kar_id DESC";

		// print_r($sql);die();

		return  $this->db->datos($sql);
	}
}

?>