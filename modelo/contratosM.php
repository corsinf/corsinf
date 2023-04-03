<?php
include('../db/db.php');

/**
 * 
 */
class contratosM
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

	function lista_cobertura($cobertura =false,$siniestros=false,$query=false,$id=false,$idSini=false)
	{
		$sql = "SELECT id_riesgos as 'id',nombre_riesgo as 'nombre',cobertura as 'detalle',subriesgo as 'cobertura'  
				FROM RIESGOS 
				WHERE 1=1";
				if($cobertura)
				{
					$sql.=" AND subriesgo is NULL";
				}
				if($siniestros)
				{
					$sql.=" AND subriesgo is NOT NULL";
				}
				if($query)
				{
					$sql.=" AND nombre_riesgo like '%".$query."%'";
				}
				if($idSini)
				{
					$sql.=" AND subriesgo ='".$idSini."'";
				}
				if($id)
				{
					$sql.=" AND id_riesgos ='".$id."'";
				}

		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}
	function lista_proveedores($query=false,$id=false)
	{
		$sql = "SELECT id_proveedor as 'id',nombre,ci_ruc,telefono,direccion,email FROM PROVEEDOR WHERE 1=1 ";
		if($query)
		{
			$sql.=" AND nombre like '%".$query."%'";
		}
		if($id)
		{
			$sql.=' AND id_proveedor='.$id;
		}
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}
	function buscar_seguro($id=false,$prove=false,$desde=false,$hasta=false,$prima=false,$suma_asegurada=False)
	{
		$sql="SELECT * FROM seguros WHERE 1=1";
		if($id)
		{
			$sql.=" AND id_contratos =".$id;
		}
		if($prove)
		{
			$sql.="AND proveedor =".$prove;
		}
		if($desde!=false && $hasta!=false)
		{
			$sql.=" AND desde ='".$desde."' AND hasta = '".$hasta."' ";
		}
		if($prima)
		{
			$sql.=" AND prima='".$prima."' ";
		}
		if($suma_asegurada)
		{
			$sql.=" AND  suma_asegurada = '".$suma_asegurada."'";
		}
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function lista_articulos($query=false)
	{
		$sql = "SELECT id_plantilla,COMPANYCODE,A.TAG_SERIE,P.DESCRIPT,DESCRIPT2,MODELO,SERIE,EMPLAZAMIENTO,L.DENOMINACION,PE.PERSON_NO,PE.PERSON_NOM,M.DESCRIPCION as 'marca',E.DESCRIPCION as 'estado',G.DESCRIPCION as 'genero',C.DESCRIPCION as 'color',FECHA_INV_DATE,ASSETSUPNO,ASSETSUPNO,TAG_ANT,QUANTITY,BASE_UOM,ORIG_ASSET,ORIG_ACQ_YR,ORIG_VALUE,CARACTERISTICA,PROYECTO.programa_financiacion as 'criterio',TAG_UNIQUE,SUBNUMBER,OBSERVACION,IMAGEN  FROM PLANTILLA_MASIVA P
			LEFT JOIN ASSET A ON P.ID_ASSET = A.ID_ASSET
			LEFT JOIN LOCATION L ON P.LOCATION = L.ID_LOCATION
			LEFT JOIN PERSON_NO PE ON P.PERSON_NO = PE.ID_PERSON
			LEFT JOIN MARCAS M ON P.EVALGROUP1 = M.ID_MARCA
			LEFT JOIN ESTADO E ON P.EVALGROUP2 = E.ID_ESTADO
			LEFT JOIN GENERO G ON P.EVALGROUP3 = G.ID_GENERO
			LEFT JOIN COLORES C ON P.EVALGROUP4 = C.ID_COLORES
			LEFT JOIN PROYECTO ON P.EVALGROUP5 = PROYECTO.ID_PROYECTO 
			WHERE 1 = 1 ";
			if($query)
			{
				$sql.="  AND A.TAG_SERIE +' '+DESCRIPT LIKE '%".$query."%'";
			}
		$sql.=" ORDER BY id_plantilla	OFFSET 0 ROWS FETCH NEXT 25 ROWS ONLY;";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function lista_articulos_seguro($contrato=false,$query=false,$id_art=false)
	{
		$sql = "SELECT id_arti_asegurado as 'id',id_plantilla,COMPANYCODE,A.TAG_SERIE,P.DESCRIPT,DESCRIPT2,MODELO,SERIE,EMPLAZAMIENTO,L.DENOMINACION,PE.PERSON_NO,PE.PERSON_NOM,M.DESCRIPCION as 'marca',E.DESCRIPCION as 'estado',G.DESCRIPCION as 'genero',C.DESCRIPCION as 'color',FECHA_INV_DATE,ASSETSUPNO,ASSETSUPNO,TAG_ANT,QUANTITY,BASE_UOM,ORIG_ASSET,ORIG_ACQ_YR,ORIG_VALUE,CARACTERISTICA,PROYECTO.programa_financiacion as 'criterio',TAG_UNIQUE,SUBNUMBER,OBSERVACION,IMAGEN  
			FROM ARTICULOS_ASEGURADOS ASE
			LEFT JOIN PLANTILLA_MASIVA P ON ASE.id_articulo = P.id_plantilla
			LEFT JOIN ASSET A ON P.ID_ASSET = A.ID_ASSET
			LEFT JOIN LOCATION L ON P.LOCATION = L.ID_LOCATION
			LEFT JOIN PERSON_NO PE ON P.PERSON_NO = PE.ID_PERSON
			LEFT JOIN MARCAS M ON P.EVALGROUP1 = M.ID_MARCA
			LEFT JOIN ESTADO E ON P.EVALGROUP2 = E.ID_ESTADO
			LEFT JOIN GENERO G ON P.EVALGROUP3 = G.ID_GENERO
			LEFT JOIN COLORES C ON P.EVALGROUP4 = C.ID_COLORES
			LEFT JOIN PROYECTO ON P.EVALGROUP5 = PROYECTO.ID_PROYECTO 
			WHERE 1 = 1 ";
			if($contrato)
			{
			  $sql.=" AND id_seguro=".$contrato;
			}
			if($query)
			{
				$sql.="  AND A.TAG_SERIE +' '+DESCRIPT LIKE '%".$query."%'";
			}
			if($id_art)
			{
				$sql.= ' AND id_articulo = '.$id_art;
			}
		$sql.=" ORDER BY id_plantilla DESC";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function Articulo_contrato_delete($id)
	{
		$sql = "DELETE FROM ARTICULOS_ASEGURADOS WHERE id_arti_asegurado='".$id."'";
		return $this->db->sql_string($sql);
	}

	function lista_contratos($provedor,$desde,$hasta,$opc=false)
	{
		$sql="SELECT id_contratos as 'id',proveedor,nombre,desde,hasta,prima,suma_asegurada,nombre_riesgo,siniestro,estado 
		FROM SEGUROS S
		INNER JOIN PROVEEDOR P ON S.proveedor = P.id_proveedor
		INNER JOIN  RIESGOS C ON S.cobertura = C.id_riesgos
		WHERE 1=1 ";
		if($provedor)
		{
			$sql.=" AND P.nombre like '%".$provedor."%'";
		}
		
		if($opc)
		{
			// print_r($opc);die();
			if($opc==1)
			{
				if($desde && $hasta)
				{
					$sql.=" AND desde between '".$desde."' AND '".$hasta."'";
				}
			}else
			{
				if($desde && $hasta)
				{
					$sql.=" AND hasta between '".$desde."' AND '".$hasta."'";
				}
			}
		}
		// print_r($sql);die();

		$datos = $this->db->datos($sql);
		return $datos;
	}
	function cargar_datos_seguro_art($id)
	{
		$sql="SELECT * 
			FROM ARTICULOS_ASEGURADOS ASE
			INNER JOIN  SEGUROS S ON ASE.id_seguro = S.id_contratos
			INNER JOIN RIESGOS R ON S.cobertura = R.id_riesgos
			INNER JOIN PROVEEDOR P ON S.proveedor = P.id_proveedor
			WHERE 1=1 ";
			if($id)
			{
				$sql.=" AND id_articulo = '".$id."'";
			}
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function forma_pago()
	{
		$sql = "SELECT id_tipo as 'id',nombre_tipo as 'nombre',comprobante 
		FROM TIPO_PAGO
		WHERE 1=1";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function historial_siniestro($articulo=false,$id=false)
	{
		$sql ="SELECT id_deterioro,articulo,detalle,fecha,D.estado,E.DESCRIPCION,encargado,fecha_siniestro,fecha_alertado,respuesta,estado_proceso,evaluacion
		FROM DETERIORO D
		INNER JOIN ESTADO E ON D.estado = E.ID_ESTADO 
		WHERE 1=1 ";
		if($articulo)
			{
				$sql.=" AND articulo = '".$articulo."' ";
			}
		if($id)
			{
				$sql.=" AND id_deterioro = '".$id."' ";
			}
			$sql.=" ORDER BY id_deterioro DESC";
		$datos = $this->db->datos($sql);
		return $datos;
	}

}

?>