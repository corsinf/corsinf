<?php 
if(!class_exists('db'))
{
 include(dirname(__DIR__,1).'/db/db.php');
}


/**
 * 
 */
class actasM
{
	
	private $db;
	
	function __construct()
	{
		$this->db = new db();

	}

	function add($tabla,$datos)
	{
		return $this->db->inserts($tabla,$datos);
	}

	function update($tabla,$datos,$Where)
	{
		return $this->db->update($tabla,$datos,$Where);
	}

	function existe_en_lista($id,$usuario)
	{
		$sql = "SELECT * FROM ARTICULOS_ACTAS WHERE id_articulo = '".$id."' AND usuario = '".$usuario."';";
		// print_r($sql);die();
		return $this->db->datos($sql);
	}

	function eliminar_lista($id=false)
	{
		$sql = "DELETE FROM ARTICULOS_ACTAS WHERE usuario='".$_SESSION['INICIO']['ID_USUARIO']."'";
		if($id)
		{
			$sql.=" AND id_acta_art = '".$id."'";
		}

		// print_r($sql);die();
		return $this->db->sql_string($sql);
	}


	function secuencial_acta($query)
	{
		$sql = "SELECT * FROM SECUENCIALES WHERE DETALLE = '".$query."'";
		$datos =  $this->db->datos($sql);
		if(count($datos)==0)
		{
			$sql2 = "INSERT INTO SECUENCIALES (DETALLE,NUMERO)VALUES('".$query."',1)";
			$this->db->sql_string($sql2);
			$datos =  $this->db->datos($sql);
		}

		// print_r($datos);die();
		return $datos;
	}

	function secuencial_acta_update($query)
	{
		$sql = "SELECT * FROM SECUENCIALES WHERE DETALLE = '".$query."'";
		$datos =  $this->db->datos($sql);
		if(count($datos)>0)
		{
			$sql2 = "UPDATE SECUENCIALES SET NUMERO = ".($datos[0]['NUMERO']+1)."WHERE DETALLE = '".$query."'";
			$this->db->sql_string($sql2);			
		}
	}

	function lista_actas()
	{
		$sql = "SELECT AC.id_acta_art as 'id',TAG_SERIE as 'asset',DESCRIPT as 'articulo',ORIG_VALUE as 'valor',SERIE,MODELO,TAG_UNIQUE,PERSON_NOM,DENOMINACION FROM Articulos_actas AC
				INNER JOIN PLANTILLA_MASIVA P ON AC.id_articulo = P.id_plantilla
				INNER JOIN ASSET A ON A.ID_ASSET = P.ID_ASSET
				LEFT JOIN PERSON_NO C ON C.ID_PERSON = P.PERSON_NO
				LEFT JOIN LOCATION L ON L.ID_LOCATION = P.LOCATION
				WHERE usuario = '".$_SESSION['INICIO']['ID_USUARIO']."';";
		// print_r($sql);die();
		return $this->db->datos($sql);
	}
	function cantidad_registros($query=false,$loc=false,$cus=false,$pag=false,$whereid=false,$bajas=false,$patrimoniales=false,$terceros=false)
	{
		$sql="SELECT COUNT(id_plantilla) as 'numreg' FROM PLANTILLA_MASIVA P
			LEFT JOIN ASSET A ON P.ID_ASSET = A.ID_ASSET
			LEFT JOIN LOCATION L ON P.LOCATION = L.ID_LOCATION
			LEFT JOIN PERSON_NO PE ON P.PERSON_NO = PE.ID_PERSON
			LEFT JOIN MARCAS M ON P.EVALGROUP1 = M.ID_MARCA
			LEFT JOIN ESTADO E ON P.EVALGROUP2 = E.ID_ESTADO
			LEFT JOIN GENERO G ON P.EVALGROUP3 = G.ID_GENERO
			LEFT JOIN COLORES C ON P.EVALGROUP4 = C.ID_COLORES
			WHERE 1=1";
			if($query)
			{ 
			   $sql.=" AND A.TAG_SERIE +' '+P.DESCRIPT+' '+P.ORIG_ASSET LIKE '%".$query."%'";
			}
		if($loc !='')
			{
				$sql.=" AND P.LOCATION = '".$loc."' ";
			}
			if($cus != '')
			{
				$sql.=" AND PE.ID_PERSON = '".$cus."' ";
			}
			if($whereid)
			{
				$sql.='  AND id_plantilla = '.$whereid.' ';
			}
			if($bajas)
			{				
				$sql.=' AND  BAJAS = 1';
			}
			if($terceros)
			{
				if ($bajas) {
					$sql.=' OR  TERCEROS = 1';
				}else{
				$sql.=' AND  TERCEROS= 1';
				}
			}
			if($patrimoniales)
			{
				if ($terceros || $bajas) {
					$sql.=' OR PATRIMONIALES = 1';
				}else{
				$sql.=' AND PATRIMONIALES = 1';
				}
			}
			if($pag)
			{
			     $pagi = explode('-',$pag);
			     $ini =$pagi[0];
			     $fin = $pagi[1];
			     $sql.= "OFFSET ".$ini." ROWS FETCH NEXT ".$fin." ROWS ONLY;";
			}
	      // print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}


	function articulo($asset)
	{
		$sql = "SELECT P.id_plantilla,PE.PERSON_NO,PERSON_NOM,EMPLAZAMIENTO,DENOMINACION, * FROM PLANTILLA_MASIVA P
		INNER JOIN ASSET ON P.ID_ASSET = ASSET.ID_ASSET
		LEFT JOIN LOCATION L ON P.LOCATION = L.ID_LOCATION
		LEFT JOIN PERSON_NO PE ON P.PERSON_NO = PE.ID_PERSON
		WHERE TAG_SERIE = '".$asset."'";
		$datos = $this->db->datos($sql);
		return $datos;

	}

	function lista_articulos($query=false,$loc=false,$cus=false,$pag=false,$whereid=false,$exacto=false,$asset=false,$bajas=false,$terceros=false,$patrimoniales=false,$desde=false,$hasta=false)
	{
		$sql = "SELECT id_plantilla as 'id',A.TAG_SERIE as 'tag',A.ID_ASSET,DESCRIPT as 'nom',MODELO as 'modelo',A.TAG_UNIQUE AS 'RFID',SERIE as 'serie',L.ID_LOCATION AS 'IDL',L.DENOMINACION as 'localizacion',PE.ID_PERSON AS 'IDC',PE.PERSON_NOM as 'custodio',M.DESCRIPCION as 'marca',E.DESCRIPCION as 'estado',G.DESCRIPCION as 'genero',C.DESCRIPCION as 'color',IMAGEN,OBSERVACION,FECHA_INV_DATE as 'fecha_in',BAJAS,TERCEROS,PATRIMONIALES,ORIG_VALUE as 'valor' FROM PLANTILLA_MASIVA P
			LEFT JOIN ASSET A ON P.ID_ASSET = A.ID_ASSET
			LEFT JOIN LOCATION L ON P.LOCATION = L.ID_LOCATION
			LEFT JOIN PERSON_NO PE ON P.PERSON_NO = PE.ID_PERSON
			LEFT JOIN MARCAS M ON P.EVALGROUP1 = M.ID_MARCA
			LEFT JOIN ESTADO E ON P.EVALGROUP2 = E.ID_ESTADO
			LEFT JOIN GENERO G ON P.EVALGROUP3 = G.ID_GENERO
			LEFT JOIN COLORES C ON P.EVALGROUP4 = C.ID_COLORES
			WHERE 1 = 1 ";
			if($exacto)
			{
				if($asset)
				{
					if($query)
					{
					   $sql.=" AND A.TAG_SERIE LIKE '".$query."%'";
					}
				}else if($asset==2)
				{
					if($query)
					{
						$sql.=" AND P.ORIG_ASSET LIKE '".$query."%'";
					}
				}else
				{
					if($query)
					{
						$sql.=" AND A.TAG_UNIQUE LIKE '%".$query."%'";
					}
				}

			}else{
				if($query)
				{
					$sql.=" AND A.TAG_SERIE +' '+P.DESCRIPT+' '+P.ORIG_ASSET +' '+A.TAG_UNIQUE LIKE '%".$query."%'";
				}
			}

			if($loc)
			{
				$sql.=" AND P.LOCATION = '".$loc."' ";
			}
			if($cus)
			{
				$sql.=" AND PE.ID_PERSON = '".$cus."' ";
			}
			if($whereid)
			{
				$sql.='  AND id_plantilla = '.$whereid.' ';
			}

			if($bajas)
			{				
				$sql.=' AND  BAJAS = 1';
			}
			if($terceros)
			{
				if ($bajas) {
					$sql.=' OR  TERCEROS = 1';
				}else{
				$sql.=' AND  TERCEROS= 1';
				}
			}
			if($patrimoniales)
			{
				if ($terceros || $bajas) {
					$sql.=' OR PATRIMONIALES = 1';
				}else{
				$sql.=' AND PATRIMONIALES = 1';
				}
			}
			if($desde  && $hasta)
			{
				$sql.=' AND FECHA_INV_DATE BETWEEN '.$desde.' AND '.$hasta;
			}
			$sql.= " ORDER BY id_plantilla ";
			if($pag)
			{
			     $pagi = explode('-',$pag);
			     $ini =$pagi[0];
			     $fin = $pagi[1];
			     $sql.= "OFFSET ".$ini." ROWS FETCH NEXT ".$fin." ROWS ONLY;";
			}
	      // print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

}

?>