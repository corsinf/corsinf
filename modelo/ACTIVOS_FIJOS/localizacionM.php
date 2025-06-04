<?php

require_once(dirname(__DIR__) . '/GENERAL/BaseModel.php');

/**
 * 
 **/

class localizacionM extends BaseModel
{

	protected $tabla = 'ac_localizacion';
	protected $primaryKey = 'ID_LOCALIZACION AS _id';

	protected $camposPermitidos = [
		'CENTRO',
		'EMPLAZAMIENTO',
		'DENOMINACION',
		'FAMILIA',
		'SUBFAMILIA',
		'ESTADO',
	];

	/**
	 * @todo Revisar este archivo
	 * @note Actualmente se mantiene como respaldo
	 * @warning No modificar este archivo sin autorización.
	 */

	function lista_localizacion($query, $ini = 0, $fin = 25)
	{
		// $sql = "SELECT ID_MARCA,CODIGO,DESCRIPCION FROM localizacion ";
		$sql = "SELECT ID_LOCALIZACION,CENTRO,EMPLAZAMIENTO,DENOMINACION FROM ac_localizacion WHERE ESTADO='A' and DENOMINACION+''+EMPLAZAMIENTO LIKE '%" . $query . "%' ORDER BY ID_LOCALIZACION ASC OFFSET " . $ini . " ROWS FETCH NEXT " . $fin . " ROWS ONLY;";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function lista_localizacion_todo()
	{
		// $sql = "SELECT ID_MARCA,CODIGO,DESCRIPCION FROM localizacion ";
		$sql = "SELECT ID_LOCALIZACION,CENTRO,EMPLAZAMIENTO,DENOMINACION,ESTADO FROM ac_localizacion";
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function lista_localizacion_count($query)
	{
		// $sql = "SELECT ID_MARCA,CODIGO,DESCRIPCION FROM localizacion ";
		$sql = "SELECT COUNT(ID_LOCALIZACION) as 'cant' FROM ac_localizacion WHERE DENOMINACION LIKE '%" . $query . "%'";
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function buscar_localizacion($buscar)
	{
		$sql = "SELECT ID_LOCALIZACION,CENTRO,EMPLAZAMIENTO,DENOMINACION FROM ac_localizacion WHERE ESTADO='A' and ID_LOCALIZACION ='" . $buscar . "'";
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}
	function buscar_localizacion_vista_publica($buscar)
	{
		$sql = "SELECT ID_LOCALIZACION,CENTRO,EMPLAZAMIENTO,DENOMINACION FROM ac_localizacion WHERE ESTADO='A' and ID_LOCALIZACION ='" . $buscar . "'";
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function buscar_localizacion_($buscar)
	{
		$sql = "SELECT ID_LOCALIZACION,CENTRO,EMPLAZAMIENTO,DENOMINACION FROM ac_localizacion WHERE  EMPLAZAMIENTO LIKE '" . $buscar . "'";
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function buscar_localizacion_cant()
	{
		$sql = "SELECT count(ID_LOCALIZACION) as 'cant' FROM ac_localizacion WHERE 1=1 ";
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function buscar_localizacion_codigo($buscar)
	{
		$sql = "SELECT ID_LOCALIZACION,CENTRO,EMPLAZAMIENTO,DENOMINACION FROM ac_localizacion WHERE  EMPLAZAMIENTO='" . $buscar . "'";
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function insertar($datos)
	{
		$rest = $this->db->inserts('ac_localizacion', $datos);
		return $rest;
	}

	function editar($datos, $where)
	{
		$rest = $this->db->update('ac_localizacion', $datos, $where);
		return $rest;
	}

	function eliminar($datos)
	{
		$sql = "UPDATE ac_localizacion SET ESTADO='I' WHERE " . $datos[0]['campo'] . "='" . $datos[0]['dato'] . "';";
		$datos = $this->db->sql_string($sql);
		return $datos;

		//$rest = $this->db->delete('ac_localizacion',$datos);
		//return $rest;	   	   
	}
}
