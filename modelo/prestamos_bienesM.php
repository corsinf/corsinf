<?php 
if(!class_exists('db'))
{
 include('../db/db.php');
}
/**
 * 
 */
class prestamos_bienesM
{
	private $db;
	
	function __construct()
	{
		$this->db = new db();

	}

	function buscar_bien($query=false)
	{
		$sql = "select top 25 id_plantilla as 'id',A.TAG_SERIE,DESCRIPT,MODELO,SERIE FROM ac_articulos PM LEFT JOIN ac_asset A ON PM.ID_ASSET = A.ID_ASSET";
		if($query)
		{
			$sql = "SELECT TOP 25 id_plantilla as 'id',A.TAG_SERIE,DESCRIPT,MODELO,SERIE FROM ac_articulos PM 
			LEFT JOIN ac_asset A ON PM.ID_ASSET = A.ID_ASSET
			WHERE DESCRIPT+''+MODELO+''+SERIE+''+A.TAG_SERIE like '%".$query."%'";
		}
		return $this->db->datos($sql);
	}

	function datos_solicitud($id=false,$solicitante=false,$fecha=false,$fecha_salida=false,$fecha_regreso=false,$observacion=false,$estado=false)
	{
		$sql = "SELECT * FROM ac_solicitud_salida WHERE 1=1 ";
		if($solicitante)
		{
			$sql.=" AND solicitante = '".$solicitante."' ";
		}
		if($fecha)
		{
			$sql.=" AND fecha = '".$fecha."' ";
		}
		if($fecha_salida)
		{
			$sql.=" AND fecha_salida = '".$fecha_salida."' ";
		}
		if($fecha_regreso)
		{
			$sql.=" AND fecha_regreso = '".$fecha_regreso."' ";
		}
		if($observacion)
		{
			$sql.=" AND observacion = '".$observacion."' ";
		}
		if($estado!=false)
		{
			if($estado=='null')
			{
				$sql.="AND estado is null";
			}else{
				$sql.=" AND estado = '".$estado."' ";
			}
		}
		if($id)
		{
			$sql.=" AND id_solicitud = '".$id."'";
		}
		// print_r($sql);die();
		return $this->db->datos($sql);
	}

	function datos_solicitud_all($id=false,$solicitante=false,$fecha=false,$fecha_salida=false,$fecha_regreso=false,$observacion=false,$estado=false,$paso=false)
	{
		$sql = "SELECT id_solicitud,PN.PERSON_NOM,fecha,fecha_salida,fecha_regreso,observacion,paso,fecha_update,SS.estado FROM ac_solicitud_salida SS 
		INNER JOIN th_personas PN ON SS.solicitante = PN.PERSON_NO 
		WHERE 1=1 ";
		if($solicitante)
		{
			$sql.=" AND solicitante = '".$solicitante."' ";
		}
		if($fecha)
		{
			$sql.=" AND fecha = '".$fecha."' ";
		}
		if($fecha_salida)
		{
			$sql.=" AND fecha_salida = '".$fecha_salida."' ";
		}
		if($fecha_regreso)
		{
			$sql.=" AND fecha_regreso = '".$fecha_regreso."' ";
		}
		if($observacion)
		{
			$sql.=" AND observacion = '".$observacion."' ";
		}
		if($estado==false)
		{
			$sql.=" AND SS.estado = '".$estado."' ";
		}
		if($id)
		{
			$sql.=" AND id_solicitud = '".$id."'";
		}
		if($paso)
		{
			$sql.=" AND paso is null ";
		}
		// print_r($sql);die();
		return $this->db->datos($sql);
	}

	function lista_notificaciones($fecha)
	{
		$sql = "SELECT * FROM ac_solicitud_salida SS 
		INNER JOIN th_personas PE ON SS.solicitante = PE.PERSON_NO 
		WHERE fecha_regreso <= '".$fecha."' AND paso = 4 AND SS.estado = 0";
		return $this->db->datos($sql);

	}


	function lineas_solicitud($id)
	{
		$sql ="	SELECT * 
				FROM ac_solicitud_salida SS 
				INNER JOIN ac_lineas_solicitud L ON SS.id_solicitud=L.id_solicitud 
				INNER JOIN ac_articulos PM ON L.id_activo = PM.id_plantilla 
				INNER JOIN ac_asset A ON PM.ID_ASSET = A.ID_ASSET 
				WHERE SS.id_solicitud = '".$id."' ";

	
		return $this->db->datos($sql);
	}

	function add($tabla,$datos)
	{
		return $this->db->inserts($tabla,$datos);
	}
	function update($tabla,$datos,$where)
	{
		return $this->db->update($tabla,$datos,$where);
	}

	function lista_solicitudes_null()
	{
		$sql = "SELECT * FROM ac_solicitud_salida WHERE estado is NULL";
		return $this->db->datos($sql);

	}
	function eliminar_solicitud($id)
	{
		$sql="DELETE FROM ac_solicitud_salida WHERE id_solicitud = '".$id."' AND estado is null;";
		$sql2="DELETE FROM ac_lineas_solicitud WHERE id_solicitud = '".$id."';";

		// print_r($sql);die();
		$this->db->sql_string($sql);
		return $this->db->sql_string($sql2);
	}

	function delete_lineas($id)
	{
		$sql2="DELETE FROM ac_lineas_solicitud WHERE id_linea_salida = '".$id."';";
		// print_r($sql);die();
		return $this->db->sql_string($sql2);
	}

	function lineas_salidas()
	{
		$sql="SELECT LS.* FROM ac_lineas_solicitud LS
		INNER JOIN ac_solicitud_salida SS ON LS.id_solicitud = SS.id_solicitud
		WHERE salida_verificada = 1 AND entrada_verificada = 0";
		return $this->db->datos($sql);
		
	}
	
}

?>