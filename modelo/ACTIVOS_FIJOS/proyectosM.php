<?php 
if (!class_exists('db')) {
	include(dirname(__DIR__, 2) . '/db/db.php');
}

/**
 * 
 **/

class proyectosM
{
	private $db;
	
	function __construct()
	{
		$this->db = new db();

	}

	function lista_proyectos($id='')
	{
		$sql = "SELECT ID_PROYECTO as 'id',programa_financiacion as 'pro',entidad_cp as 'enti',denominacion as 'deno',descripcion as 'desc',validez_de as 'valde',validez_a as 'vala',expiracion as 'exp'  FROM PROYECTO WHERE ESTADO='A' ";
		if($id)
		{
			$sql.= ' and ID_PROYECTO = '.$id;
		}
		$sql.=" ORDER BY ID_PROYECTO DESC OFFSET 0 ROWS FETCH NEXT 70 ROWS ONLY;";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function lista_proyectos_todo()
	{
		$sql = "SELECT ID_PROYECTO as 'id',programa_financiacion as 'pro',entidad_cp as 'enti',denominacion as 'deno',descripcion as 'desc',validez_de as 'valde',validez_a as 'vala',expiracion as 'exp',ESTADO  FROM PROYECTO";
		
		$datos = $this->db->datos($sql);
		return $datos;
	}
	function buscar_proyecto($buscar)
	{
		$sql = "SELECT ID_PROYECTO as 'id',programa_financiacion as 'pro',entidad_cp as 'enti',denominacion as 'deno',descripcion as 'desc',validez_de as 'valde',validez_a as 'vala',expiracion as 'exp'  FROM PROYECTO WHERE ESTADO='A' and programa_financiacion+' '+ denominacion LIKE '%".$buscar."%'  ORDER BY ID_PROYECTO desc  OFFSET 0 ROWS FETCH NEXT 70 ROWS ONLY";
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function buscar_proyecto_programa($buscar)
	{
		$sql = "SELECT ID_PROYECTO as 'id',programa_financiacion as 'pro',entidad_cp as 'enti',denominacion as 'deno',descripcion as 'desc',validez_de as 'valde',validez_a as 'vala',expiracion as 'exp'  FROM PROYECTO WHERE programa_financiacion = '".$buscar."'  ORDER BY ID_PROYECTO desc  OFFSET 0 ROWS FETCH NEXT 70 ROWS ONLY";
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function buscar_proyectos_conte($buscar)
	{
		$sql = "SELECT ID_CONTENIDO AS 'id',P.DESCRIPT AS 'nom' FROM CONTENIDO_PROYECTO C LEFT JOIN PLANTILLA_MASIVA P ON C.ID_ARTICULO = P.id_plantilla WHERE ID_PROYECTO = '".$buscar."' ORDER BY ID_CONTENIDO DESC";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function insertar($datos)
	{
		 $rest = $this->db->inserts('PROYECTO',$datos);
		return $rest;
	}
	function insertar_contenido($datos)
	{
		 $rest = $this->db->inserts('CONTENIDO_PROYECTO',$datos);
		return $rest;
	}
	function editar($datos,$where)
	{
		
	    $rest = $this->db->update('PROYECTO',$datos,$where);
		return $rest;
	}
	function eliminar($datos)
	{
		$sql = "UPDATE PROYECTO SET ESTADO='I' WHERE ".$datos[0]['campo']."='".$datos[0]['dato']."';";
		$datos = $this->db->sql_string($sql);
		return $datos;

	    //$rest = $this->db->delete('PROYECTO',$datos);
		//return $rest;
	}
	function eliminar_conte($datos)
	{
		//$sql = "UPDATE PROYECTO SET ESTADO='I' WHERE ".$datos[0]['campo']."='".$datos[0]['dato']."';";
		//$datos = $this->db->sql_string($sql);
		//return $datos;

	    $rest = $this->db->delete('CONTENIDO_PROYECTO',$datos);
		return $rest;
	}
}

?>