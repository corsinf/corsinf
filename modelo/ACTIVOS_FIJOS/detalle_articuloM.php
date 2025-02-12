<?php 
if (!class_exists('db')) {
	include(dirname(__DIR__, 2) . '/db/db.php');
}

/**
 * 
 **/

class detalle_articuloM
{
	private $db;
	
	function __construct()
	{
		$this->db = new db();

	}

	function guardar($tabla,$datos)
	{
		$datos = $this->db->inserts($tabla,$datos);
		return $datos;
	}

	function update($tabla,$datos,$where)
	{
		$datos = $this->db->update($tabla,$datos,$where);
		return $datos;
	}


	function cargar_datos($id)
	{
		$sql="SELECT P.id_plantilla as 'id_A',A.ID_ASSET as 'id_AS',SUBNUMBER,DESCRIPT as 'nom',DESCRIPT2 as 'des',TAG_SERIE as 'tag_s',TAG_UNIQUE as 'rfid',TAG_ANT as 'ant',COMPANYCODE,MODELO,SERIE,FECHA_INV_DATE as 'fecha',P.LOCATION as 'id_loc',L.DENOMINACION as 'DENOMINACION',L.EMPLAZAMIENTO as 'Cloc',P.PERSON_NO as 'id_cus',PERSON_NOM,P.PERSON_NO as 'Ccus',MARCAS.ID_MARCA as 'mar',MARCAS.DESCRIPCION as 'marca',MARCAS.CODIGO as 'Cmar',ESTADO.ID_ESTADO as 'est',ESTADO.CODIGO as 'Cest',ESTADO.DESCRIPCION as 'estado',GENERO.ID_GENERO as 'gen',GENERO.DESCRIPCION as 'genero',GENERO.CODIGO as 'Cgen',COLORES.ID_COLORES as 'col',COLORES.DESCRIPCION as 'color',COLORES.CODIGO as 'Ccol',PROYECTO.ID_PROYECTO as 'idpro',PROYECTO.denominacion as 'proyecto',PROYECTO.programa_financiacion as 'Cpro',OBSERVACION ,IMAGEN,QUANTITY,BASE_UOM,ORIG_ASSET,ORIG_ACQ_YR,ORIG_VALUE,CARACTERISTICA,EVALGROUP5,BAJAS,PATRIMONIALES,TERCEROS,ASSETSUPNO,COMPANYCODE,P.FAMILIA as 'IDF',F.detalle_familia as 'FAMILIA',P.SUBFAMILIA as 'IDSUBF',SUB.detalle_familia as 'SUBFAMILIA',P.CLASE_MOVIMIENTO,CM.DESCRIPCION AS 'MOVIMIENTO',SISTEMA_OP,KERNEL,ARQUITECTURA,PRODUCTO_ID,VERSION,SERVICE_PACK,EDICION FROM PLANTILLA_MASIVA P 
		LEFT JOIN ASSET A ON P.ID_ASSET = A.ID_ASSET 
		LEFT JOIN LOCATION L ON P.LOCATION = L.ID_LOCATION
		LEFT JOIN PERSON_NO PE ON P.PERSON_NO = PE.ID_PERSON 
		LEFT JOIN MARCAS ON P.EVALGROUP1 = MARCAS.ID_MARCA 
		LEFT JOIN ESTADO ON P.EVALGROUP2 = ESTADO.ID_ESTADO 
		LEFT JOIN GENERO ON P.EVALGROUP3 = GENERO.ID_GENERO 
		LEFT JOIN COLORES ON P.EVALGROUP4 = COLORES.ID_COLORES
		LEFT JOIN PROYECTO ON P.EVALGROUP5 = PROYECTO.ID_PROYECTO
		LEFT JOIN FAMILIAS F ON P.FAMILIA = F.id_familia
		LEFT JOIN FAMILIAS SUB ON P.SUBFAMILIA = SUB.id_familia
		LEFT JOIN CLASE_MOVIMIENTO CM ON P.CLASE_MOVIMIENTO = CM.CODIGO 
		WHERE p.id_plantilla = '".$id."'";
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;

	}

	function buscar_plantilla_masiva($idAsset=false)
	{
		$sql = "SELECT * FROM PLANTILLA_MASIVA WHERE 1 = 1 ";
		if($idAsset)
		{
			$sql.=" AND ID_ASSET = '".$idAsset." '";
		}
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function buscar_asset($tag=false,$ant=false,$rfid=false)
	{
		$sql="SELECT * FROM ASSET WHERE 1=1 ";
		if($tag)
		{
		  $sql.=" AND TAG_SERIE ='".$tag."' ";
		}
		if($ant)
		{ 
		  $sql.=" AND TAG_ANT = '".$ant."' ";
		}
		
		if($rfid)
		{
		  $sql.=" AND TAG_UNIQUE = '".$rfid."'";
		}
		// print_r($sql);
		return $this->db->datos($sql);
	}

	function cargar_tarjeta($id)
	{
		// $sql="SELECT * FROM TARJETA_INFO WHERE id_articulo = '".$id."'";
		// $datos = $this->db->datos($sql);
		// return $datos;

		$sql="SELECT * FROM DATOS_PATRIMONIAL WHERE ARTICULO = '".$id."'";
		$datos = $this->db->datos($sql);
		return $datos;


	}

	function movimientos($id,$desde=false,$hasta=false)
	{
		$sql="SELECT obs_movimiento as 'ob',fecha_movimiento as 'fe',dato_anterior as 'dante',dato_nuevo as 'dnuevo',responsable,codigo_ant,codigo_nue  FROM MOVIMIENTO WHERE id_plantilla = '".$id."' ";
		if($desde!=false && $hasta!=false)
		{
			$sql.=" AND fecha_movimiento BETWEEN '".$desde."' AND '".$hasta."'";
		}
		$sql.=" ORDER BY id_movimiento desc";
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function update_data($tabla,$datos,$where)
	{
		$datos = $this->db->update($tabla,$datos,$where);
		return $datos;
	}
	function img_guardar($name,$codigo)
	{
		$tabla = 'PLANTILLA_MASIVA';
		$datos[0]['campo']='IMAGEN';
		$datos[0]['dato']=$name;

		$where[0]['campo']='id_plantilla';
		$where[0]['dato'] = $codigo;
		$datos = $this->db->update($tabla,$datos,$where);
		if($datos==1)
		{
			return 1;
		}else
		{
			return -1;
		}

	}
	function navegacion($query,$loc,$cus,$pag=false,$whereid=false,$exacto=false,$asset=false)
	{
		$sql = "SELECT id_plantilla as 'id',A.TAG_SERIE as 'tag',A.ID_ASSET,DESCRIPT as 'nom',MODELO as 'modelo',SERIE as 'serie',L.DENOMINACION as 'localizacion',PE.PERSON_NOM as 'custodio',M.DESCRIPCION as 'marca',E.DESCRIPCION as 'estado',G.DESCRIPCION as 'genero',C.DESCRIPCION as 'color',IMAGEN,OBSERVACION,FECHA_INV_DATE as 'fecha_in' FROM PLANTILLA_MASIVA P
			LEFT JOIN ASSET A ON P.ID_ASSET = A.ID_ASSET
			LEFT JOIN LOCATION L ON P.LOCATION = L.ID_LOCATION
			LEFT JOIN PERSON_NO PE ON P.PERSON_NO = PE.ID_PERSON
			LEFT JOIN MARCAS M ON P.EVALGROUP1 = M.ID_MARCA
			LEFT JOIN ESTADO E ON P.EVALGROUP2 = E.ID_ESTADO
			LEFT JOIN GENERO G ON P.EVALGROUP3 = G.ID_GENERO
			LEFT JOIN COLORES C ON P.EVALGROUP4 = C.ID_COLORES
			WHERE ";
			if($exacto)
			{
				if($asset)
				{
				   $sql.=" A.TAG_SERIE LIKE '%".$query."'";

				}else
				{
					$sql.=" P.ORIG_ASSET LIKE '%".$query."'";
				}

			}else{
				$sql.=" A.TAG_SERIE +' '+P.DESCRIPT+' '+P.ORIG_ASSET LIKE '%".$query."%'";
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