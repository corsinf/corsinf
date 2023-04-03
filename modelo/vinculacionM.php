<?php 
if(!class_exists('db'))
{
 include('../db/db.php');
}
/**
 * 
 */
class vinculacionM
{
	private $db;
	
	function __construct()
	{
		$this->db = new db();

	}

	function insertar($tabla,$datos)
	{
		 $rest = $this->db->inserts($tabla,$datos);
	   
		return $rest;
	}
	function editar($tabla,$datos,$where)
	{		
	    $rest = $this->db->update($tabla,$datos,$where);
		return $rest;
	}
	function eliminar($datos)
	{
		$sql = "UPDATE COLORES SET ESTADO='I' WHERE ".$datos[0]['campo']."='".$datos[0]['dato']."';";
		$datos = $this->db->sql_string($sql);
		return $datos;

	    //$rest = $this->db->delete('COLORES',$datos);
		//return $rest;
	}

	function lista_desvinculados($tipo,$query=false,$custodio=false,$location=false)
	{
		$sql="SELECT A.TAG_SERIE as 'tag',A.ID_ASSET,V.articulo_id as 'id',DESCRIPT as 'nom',V.custodio_id,PERSON_NOM as 'cus',LOCATION,DENOMINACION as 'loc',IMAGEN,tipo FROM VINCULACION V
		INNER JOIN PLANTILLA_MASIVA P ON V.articulo_id = P.id_plantilla
		LEFT JOIN ASSET A ON P.ID_ASSET = A.ID_ASSET
		LEFT JOIN LOCATION L ON P.LOCATION = L.ID_LOCATION
		LEFT JOIN PERSON_NO PE ON P.PERSON_NO = PE.ID_PERSON		
		WHERE tipo = '".$tipo."'";
		
			if($query)
			{
				if(is_numeric($query))
				{
					$sql.=" AND TAG_SERIE like '".$query."%'";
				}else
				{
					$sql.=" AND DESCRIPT like '%".$query."%'";
				}
			}
			if($custodio)
			{
				$sql.=" AND V.custodio_id = '".$custodio."'";
			}
			if($location)
			{
				$sql.=" AND V.localizacion_id = '".$location."'";
			}
// print_r($sql);die();


		return $this->db->datos($sql);
	}

	function delete_vin($id,$tipo)
	{
		$sql = "DELETE FROM VINCULACION WHERE articulo_id = '".$id."' AND tipo = '".$tipo."'";		
		return $this->db->sql_string($sql);
	}

	function validar_asegurado($id=false)
	{
		$sql = "SELECT * FROM ARTICULOS_ASEGURADOS WHERE 1=1";
		if($id)
		{
			$sql.=" AND id_articulo='".$id."'";
		}
		return $this->db->datos($sql);
	}
}

?>