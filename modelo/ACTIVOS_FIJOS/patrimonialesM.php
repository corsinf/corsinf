<?php
if (!class_exists('db')) {
	include(dirname(__DIR__, 2) . '/db/db.php');
}

/**
 * 
 **/

class patrimonialesM
{
	private $db;

	private $sql_busqueda;
	function __construct()
	{
		$this->db = new db();
	}

	// function lista_articulos($query=false,$loc=false,$cus=false,$pag=false,$whereid=false,$exacto=false,$asset=false,$desde=false,$hasta=false,$multiple=false)
	// {
	// 	$sql = "SELECT id_plantilla as 'id',A.TAG_SERIE as 'tag',A.ID_ASSET,DESCRIPT as 'nom',MODELO as 'modelo',A.TAG_UNIQUE AS 'RFID',SERIE as 'serie',L.ID_LOCATION AS 'IDL',L.DENOMINACION as 'localizacion',PE.ID_PERSON AS 'IDC',PE.PERSON_NOM as 'custodio',M.DESCRIPCION as 'marca',E.DESCRIPCION as 'estado',G.DESCRIPCION as 'genero',C.DESCRIPCION as 'color',IMAGEN,OBSERVACION,FECHA_INV_DATE as 'fecha_in',BAJAS,TERCEROS,PATRIMONIALES,* FROM PLANTILLA_MASIVA P
	// 		LEFT JOIN ASSET A ON P.ID_ASSET = A.ID_ASSET
	// 		LEFT JOIN LOCATION L ON P.LOCATION = L.ID_LOCATION
	// 		LEFT JOIN PERSON_NO PE ON P.PERSON_NO = PE.ID_PERSON
	// 		LEFT JOIN MARCAS M ON P.EVALGROUP1 = M.ID_MARCA
	// 		LEFT JOIN ESTADO E ON P.EVALGROUP2 = E.ID_ESTADO
	// 		LEFT JOIN GENERO G ON P.EVALGROUP3 = G.ID_GENERO
	// 		LEFT JOIN COLORES C ON P.EVALGROUP4 = C.ID_COLORES
	// 		WHERE BAJAS = 0 AND TERCEROS = 0 AND PATRIMONIALES = 1  ";

	// 				// print_r('dd'.$multiple);die();
	// 		if($query!='')
	// 		{
	// 		if($exacto)
	// 		{
	// 			if($asset)
	// 			{
	// 				if($query && $multiple==false || $multiple==0)
	// 				{
	// 				   $sql.=" AND A.TAG_SERIE LIKE '".$query."%'";
	// 				}else
	// 				{
	// 					$sql.=" AND A.TAG_SERIE in (".$query.")";
	// 				}
	// 			}else if($asset==2)
	// 			{
	// 				if($query && $multiple==false || $multiple==0)
	// 				{
	// 					$sql.=" AND P.ORIG_ASSET LIKE '".$query."%'";
	// 				}else
	// 				{
	// 					$sql.=" AND P.ORIG_ASSET in (".$query.")";
	// 				}
	// 			}else
	// 			{
	// 				if($query && $multiple==false || $multiple==0)
	// 				{
	// 					$sql.=" AND A.TAG_UNIQUE LIKE '%".$query."%'";
	// 				}else
	// 				{
	// 					$sql.=" AND A.TAG_UNIQUE in (".$query.")";
	// 				}
	// 			}

	// 		}else{
	// 			if($query )
	// 			{
	// 				$sql.=" AND A.TAG_SERIE +' '+P.DESCRIPT+' '+P.ORIG_ASSET +' '+A.TAG_UNIQUE LIKE '%".$query."%'";
	// 			}
	// 		}
	// 	}

	// 		if($loc)
	// 		{
	// 			$sql.=" AND P.LOCATION = '".$loc."' ";
	// 		}
	// 		if($cus)
	// 		{
	// 			$sql.=" AND PE.ID_PERSON = '".$cus."' ";
	// 		}
	// 		if($whereid)
	// 		{
	// 			$sql.='  AND id_plantilla = '.$whereid.' ';
	// 		}
	// 		if($desde  && $hasta)
	// 		{
	// 			$sql.=" AND FECHA_INV_DATE BETWEEN '".str_replace('-','',$desde)."' AND '".str_replace('-','',$hasta)."'";
	// 		}
	// 		$sql.= " ORDER BY id_plantilla ";
	// 		if($pag)
	// 		{
	// 		     $pagi = explode('-',$pag);
	// 		     $ini =$pagi[0];
	// 		     $fin = $pagi[1];
	// 		     $sql.= "OFFSET ".$ini." ROWS FETCH NEXT ".$fin." ROWS ONLY;";
	// 		}
	//       // print_r($sql);die();
	// 		$this->sql_busqueda = $sql;
	// 	$datos = $this->db->datos($sql);
	// 	return $datos;
	// }

	function lista_articulos_new($query = false, $loc = false, $cus = false, $pag = false, $desde = false, $hasta = false, $coincidencia = false, $multiple = false, $buscar_por = false)
	{

		// print_r($exacto);die();
		$sql = "SELECT id_plantilla as 'id',A.TAG_SERIE as 'tag',A.ID_ASSET,DESCRIPT as 'nom',MODELO as 'modelo',A.TAG_UNIQUE AS 'RFID',SERIE as 'serie',L.ID_LOCATION AS 'IDL',L.DENOMINACION as 'localizacion',PE.ID_PERSON AS 'IDC',PE.PERSON_NOM as 'custodio',M.DESCRIPCION as 'marca',E.DESCRIPCION as 'estado',G.DESCRIPCION as 'genero',C.DESCRIPCION as 'color',IMAGEN,OBSERVACION,FECHA_INV_DATE as 'fecha_in',BAJAS,TERCEROS,PATRIMONIALES,* FROM PLANTILLA_MASIVA P
			LEFT JOIN ASSET A ON P.ID_ASSET = A.ID_ASSET
			LEFT JOIN LOCATION L ON P.LOCATION = L.ID_LOCATION
			LEFT JOIN PERSON_NO PE ON P.PERSON_NO = PE.ID_PERSON
			LEFT JOIN MARCAS M ON P.EVALGROUP1 = M.ID_MARCA
			LEFT JOIN ESTADO E ON P.EVALGROUP2 = E.ID_ESTADO
			LEFT JOIN GENERO G ON P.EVALGROUP3 = G.ID_GENERO
			LEFT JOIN COLORES C ON P.EVALGROUP4 = C.ID_COLORES
			LEFT JOIN PROYECTO PR ON P.EVALGROUP5 = PR.ID_PROYECTO
			LEFT JOIN CLASE_MOVIMIENTO CL ON P.CLASE_MOVIMIENTO = CL.CODIGO
			WHERE BAJAS = 0 AND TERCEROS = 0 AND PATRIMONIALES = 1  ";

		if ($loc) {
			$sql .= " AND P.LOCATION = '" . $loc . "' ";
		}
		if ($cus) {
			$sql .= " AND PE.ID_PERSON = '" . $cus . "' ";
		}
		if ($desde && $hasta) {
			$sql .= " AND FECHA_INV_DATE BETWEEN '" . str_replace('-', '', $desde) . "' AND '" . str_replace('-', '', $hasta) . "'";
		}
		if ($coincidencia) {
			//coincidencia exacta
			if ($multiple) {
				switch ($buscar_por) {
					case '1':
						// por asset
						$query = explode(',', $query);
						if (count($query) > 1) {
							$in = '';
							foreach ($query as $key => $value) {
								if ($value != '') {
									$in .= "'" . $value . "',";
								}
							}
							$in = substr($in, 0, -1);
							$sql .= " AND A.TAG_SERIE in (" . $in . ")";
						} else {
							$sql .= " AND A.TAG_SERIE = '" . $query[0] . "'";
						}
						break;
					case '2':
						// por Aset original
						$query = explode(',', $query);
						if (count($query) > 1) {
							$in = '';
							foreach ($query as $key => $value) {
								if ($value != '') {
									$in .= "'" . $value . "',";
								}
							}
							$in = substr($in, 0, -1);
							$sql .= " AND  P.ORIG_ASSET in (" . $in . ")";
						} else {
							$sql .= " AND  P.ORIG_ASSET = '" . $query[0] . "'";
						}
						break;
					case '3':
						// por RFID
						$query = explode(',', $query);
						if (count($query) > 1) {
							$in = '';
							foreach ($query as $key => $value) {
								if ($value != '') {
									$in .= "'" . $value . "',";
								}
							}
							$in = substr($in, 0, -1);
							$sql .= " AND A.TAG_UNIQUE in (" . $in . ")";
						} else {
							$sql .= " AND A.TAG_UNIQUE = '" . $query[0] . "'";
						}
						break;

					default:
						//por detalle (opcion ninguno)
						$query = explode(',', $query);
						if (count($query) > 1) {
							$in = '';
							foreach ($query as $key => $value) {
								if ($value != '') {
									$in .= "'" . $value . "',";
								}
							}
							$in = substr($in, 0, -1);
							$sql .= " AND DESCRIPT in (" . $in . ")";
						} else {
							$sql .= " AND DESCRIPT = '" . $query[0] . "'";
						}
						break;
				}
			} else {
				// coincidencia exacta unico dato
				switch ($buscar_por) {
					case '1':
						// por asset
						$sql .= " AND A.TAG_SERIE = '" . $query . "'";
						break;
					case '2':
						// por Aset original						
						$sql .= " AND P.ORIG_ASSET = '" . $query . "'";
						break;
					case '3':
						// por RFID
						$sql .= " AND A.TAG_UNIQUE = '" . $query . "'";
						break;

					default:
						//por detalle (opcion ninguno)
						$sql .= " AND DESCRIPT = '" . $query . "'";
						break;
				}
			}
		} else {
			// coincidencia aproximada
			if ($multiple) {
				// coincidencia aproximada dato multiple
				switch ($buscar_por) {
					case '1':
						// por asset
						$query = explode(',', $query);
						if (count($query) > 1) {
							$like = '';
							foreach ($query as $key => $value) {
								if ($value != '') {
									$like .= " A.TAG_SERIE like '%" . $value . "%'  or";
								}
							}
							$like = substr($like, 0, -2);
							$sql .= " AND " . $like;
						} else {
							$sql .= " AND A.TAG_SERIE like '%" . $query[0] . "%'";
						}
						break;
					case '2':
						// por Aset original
						$query = explode(',', $query);
						if (count($query) > 1) {
							$like = '';
							foreach ($query as $key => $value) {
								if ($value != '') {
									$like .= " P.ORIG_ASSET like '%" . $value . "%'  or";
								}
							}
							$like = substr($like, 0, -2);
							$sql .= " AND " . $like;
						} else {
							$sql .= " AND P.ORIG_ASSET like '%" . $query[0] . "%'";
						}
						break;
					case '3':
						//por RFID
						$query = explode(',', $query);
						if (count($query) > 1) {
							$like = '';
							foreach ($query as $key => $value) {
								if ($value != '') {
									$like .= " A.TAG_UNIQUE like '%" . $value . "%'  or";
								}
							}
							$like = substr($like, 0, -2);
							$sql .= " AND " . $like;
						} else {
							$sql .= " AND A.TAG_UNIQUE like '%" . $query[0] . "%'";
						}
						break;

					default:
						$query = explode(',', $query);
						if (count($query) > 1) {
							$like = '';
							foreach ($query as $key => $value) {
								if ($value != '') {
									$like .= " DESCRIPT like '%" . $value . "%'  or";
								}
							}
							$like = substr($like, 0, -2);
							$sql .= " AND " . $like;
						} else {
							$sql .= " AND DESCRIPT like '%" . $query[0] . "%'";
						}
						break;
				}
			} else {
				// coincidencia aproximada unico dato
				switch ($buscar_por) {
					case '1':
						// por asset
						$sql .= " AND A.TAG_SERIE like '%" . $query . "%'";
						break;
					case '2':
						// por Aset original						
						$sql .= " AND P.ORIG_ASSET like '%" . $query . "%'";
						break;
					case '3':
						// por RFID
						$sql .= " AND A.TAG_UNIQUE like '%" . $query . "%'";
						break;

					default:
						//por detalle (opcion ninguno)
						$sql .= " AND DESCRIPT like '%" . $query . "%'";
						break;
				}
			}
		}



		$sql .= " ORDER BY id_plantilla ";

		if ($pag) {
			$pagi = explode('-', $pag);
			$ini = $pagi[0];
			$fin = $pagi[1];
			$sql .= "OFFSET " . $ini . " ROWS FETCH NEXT " . $fin . " ROWS ONLY;";
		} else {
			$sql .= "OFFSET 0 ROWS FETCH NEXT 50 ROWS ONLY;";
		}

		$this->sql_busqueda = $sql;
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function cantidad_registros_new($query = false, $loc = false, $cus = false, $pag = false, $desde = false, $hasta = false, $coincidencia = false, $multiple = false, $buscar_por = false)
	{

		// print_r($exacto);die();
		$sql = "SELECT count(id_plantilla) as numreg FROM PLANTILLA_MASIVA P
			LEFT JOIN ASSET A ON P.ID_ASSET = A.ID_ASSET
			LEFT JOIN LOCATION L ON P.LOCATION = L.ID_LOCATION
			LEFT JOIN PERSON_NO PE ON P.PERSON_NO = PE.ID_PERSON
			LEFT JOIN MARCAS M ON P.EVALGROUP1 = M.ID_MARCA
			LEFT JOIN ESTADO E ON P.EVALGROUP2 = E.ID_ESTADO
			LEFT JOIN GENERO G ON P.EVALGROUP3 = G.ID_GENERO
			LEFT JOIN COLORES C ON P.EVALGROUP4 = C.ID_COLORES
			LEFT JOIN PROYECTO PR ON P.EVALGROUP5 = PR.ID_PROYECTO
			LEFT JOIN CLASE_MOVIMIENTO CL ON P.CLASE_MOVIMIENTO = CL.CODIGO
			WHERE BAJAS = 0 AND TERCEROS = 0 AND PATRIMONIALES = 1  ";

		if ($loc) {
			$sql .= " AND P.LOCATION = '" . $loc . "' ";
		}
		if ($cus) {
			$sql .= " AND PE.ID_PERSON = '" . $cus . "' ";
		}
		if ($desde && $hasta) {
			$sql .= " AND FECHA_INV_DATE BETWEEN '" . str_replace('-', '', $desde) . "' AND '" . str_replace('-', '', $hasta) . "'";
		}
		if ($coincidencia) {
			//coincidencia exacta
			if ($multiple) {
				switch ($buscar_por) {
					case '1':
						// por asset
						$query = explode(',', $query);
						if (count($query) > 1) {
							$in = '';
							foreach ($query as $key => $value) {
								if ($value != '') {
									$in .= "'" . $value . "',";
								}
							}
							$in = substr($in, 0, -1);
							$sql .= " AND A.TAG_SERIE in (" . $in . ")";
						} else {
							$sql .= " AND A.TAG_SERIE = '" . $query[0] . "'";
						}
						break;
					case '2':
						// por Aset original
						$query = explode(',', $query);
						if (count($query) > 1) {
							$in = '';
							foreach ($query as $key => $value) {
								if ($value != '') {
									$in .= "'" . $value . "',";
								}
							}
							$in = substr($in, 0, -1);
							$sql .= " AND  P.ORIG_ASSET in (" . $in . ")";
						} else {
							$sql .= " AND  P.ORIG_ASSET = '" . $query[0] . "'";
						}
						break;
					case '3':
						// por RFID
						$query = explode(',', $query);
						if (count($query) > 1) {
							$in = '';
							foreach ($query as $key => $value) {
								if ($value != '') {
									$in .= "'" . $value . "',";
								}
							}
							$in = substr($in, 0, -1);
							$sql .= " AND A.TAG_UNIQUE in (" . $in . ")";
						} else {
							$sql .= " AND A.TAG_UNIQUE = '" . $query[0] . "'";
						}
						break;

					default:
						//por detalle (opcion ninguno)
						$query = explode(',', $query);
						if (count($query) > 1) {
							$in = '';
							foreach ($query as $key => $value) {
								if ($value != '') {
									$in .= "'" . $value . "',";
								}
							}
							$in = substr($in, 0, -1);
							$sql .= " AND DESCRIPT in (" . $in . ")";
						} else {
							$sql .= " AND DESCRIPT = '" . $query[0] . "'";
						}
						break;
				}
			} else {
				// coincidencia exacta unico dato
				switch ($buscar_por) {
					case '1':
						// por asset
						$sql .= " AND A.TAG_SERIE = '" . $query . "'";
						break;
					case '2':
						// por Aset original						
						$sql .= " AND P.ORIG_ASSET = '" . $query . "'";
						break;
					case '3':
						// por RFID
						$sql .= " AND A.TAG_UNIQUE = '" . $query . "'";
						break;

					default:
						//por detalle (opcion ninguno)
						$sql .= " AND DESCRIPT = '" . $query . "'";
						break;
				}
			}
		} else {
			// coincidencia aproximada
			if ($multiple) {
				// coincidencia aproximada dato multiple
				switch ($buscar_por) {
					case '1':
						// por asset
						$query = explode(',', $query);
						if (count($query) > 1) {
							$like = '';
							foreach ($query as $key => $value) {
								if ($value != '') {
									$like .= " A.TAG_SERIE like '%" . $value . "%'  or";
								}
							}
							$like = substr($like, 0, -2);
							$sql .= " AND " . $like;
						} else {
							$sql .= " AND A.TAG_SERIE like '%" . $query[0] . "%'";
						}
						break;
					case '2':
						// por Aset original
						$query = explode(',', $query);
						if (count($query) > 1) {
							$like = '';
							foreach ($query as $key => $value) {
								if ($value != '') {
									$like .= " P.ORIG_ASSET like '%" . $value . "%'  or";
								}
							}
							$like = substr($like, 0, -2);
							$sql .= " AND " . $like;
						} else {
							$sql .= " AND P.ORIG_ASSET like '%" . $query[0] . "%'";
						}
						break;
					case '3':
						//por RFID
						$query = explode(',', $query);
						if (count($query) > 1) {
							$like = '';
							foreach ($query as $key => $value) {
								if ($value != '') {
									$like .= " A.TAG_UNIQUE like '%" . $value . "%'  or";
								}
							}
							$like = substr($like, 0, -2);
							$sql .= " AND " . $like;
						} else {
							$sql .= " AND A.TAG_UNIQUE like '%" . $query[0] . "%'";
						}
						break;

					default:
						$query = explode(',', $query);
						if (count($query) > 1) {
							$like = '';
							foreach ($query as $key => $value) {
								if ($value != '') {
									$like .= " DESCRIPT like '%" . $value . "%'  or";
								}
							}
							$like = substr($like, 0, -2);
							$sql .= " AND " . $like;
						} else {
							$sql .= " AND DESCRIPT like '%" . $query[0] . "%'";
						}
						break;
				}
			} else {
				// coincidencia aproximada unico dato
				switch ($buscar_por) {
					case '1':
						// por asset
						$sql .= " AND A.TAG_SERIE like '%" . $query . "%'";
						break;
					case '2':
						// por Aset original						
						$sql .= " AND P.ORIG_ASSET like '%" . $query . "%'";
						break;
					case '3':
						// por RFID
						$sql .= " AND A.TAG_UNIQUE like '%" . $query . "%'";
						break;

					default:
						//por detalle (opcion ninguno)
						$sql .= " AND DESCRIPT like '%" . $query . "%'";
						break;
				}
			}
		}


		$this->sql_busqueda = $sql;
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function lista_kit($id_activo)
	{
		$sql = "SELECT * FROM PLANTILLA_MASIVA WHERE KIT = '" . $id_activo . "'";
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function cantidad_registros($query = false, $loc = false, $cus = false, $pag = false, $whereid = false, $desde = false, $hasta = false)
	{
		$sql = "SELECT COUNT(id_plantilla) as 'numreg' FROM PLANTILLA_MASIVA P
			LEFT JOIN ASSET A ON P.ID_ASSET = A.ID_ASSET
			LEFT JOIN LOCATION L ON P.LOCATION = L.ID_LOCATION
			LEFT JOIN PERSON_NO PE ON P.PERSON_NO = PE.ID_PERSON
			LEFT JOIN MARCAS M ON P.EVALGROUP1 = M.ID_MARCA
			LEFT JOIN ESTADO E ON P.EVALGROUP2 = E.ID_ESTADO
			LEFT JOIN GENERO G ON P.EVALGROUP3 = G.ID_GENERO
			LEFT JOIN COLORES C ON P.EVALGROUP4 = C.ID_COLORES
			WHERE BAJAS = 0 AND TERCEROS = 0 AND PATRIMONIALES = 1 ";
		if ($desde != '' && $hasta != '' && $desde != false && $hasta != false) {
			$sql .= " AND FECHA_INV_DATE BETWEEN '" . str_replace('-', '', $desde) . "' AND '" . str_replace('-', '', $hasta) . "' ";
		}
		if ($query) {
			$sql .= " AND A.TAG_SERIE +' '+P.DESCRIPT+' '+P.ORIG_ASSET LIKE '%" . $query . "%'";
		}
		if ($loc != '') {
			$sql .= " AND P.LOCATION = '" . $loc . "' ";
		}
		if ($cus != '') {
			$sql .= " AND PE.ID_PERSON = '" . $cus . "' ";
		}
		if ($whereid) {
			$sql .= '  AND id_plantilla = ' . $whereid . ' ';
		}
		if ($pag) {
			$pagi = explode('-', $pag);
			$ini = $pagi[0];
			$fin = $pagi[1];
			$sql .= "OFFSET " . $ini . " ROWS FETCH NEXT " . $fin . " ROWS ONLY;";
		}
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function cantidad_etiquetas()
	{
		$sql = "SELECT count(*) as 'eti' FROM ASSET WHERE TAG_UNIQUE <>''";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function cantidad_registros_patrimoniales($query, $loc, $cus, $pag = false, $whereid = false)
	{
		$sql = "SELECT COUNT(id_plantilla) as 'numreg' FROM PLANTILLA_MASIVA P
			LEFT JOIN ASSET A ON P.ID_ASSET = A.ID_ASSET
			LEFT JOIN LOCATION L ON P.LOCATION = L.ID_LOCATION
			LEFT JOIN PERSON_NO PE ON P.PERSON_NO = PE.ID_PERSON
			LEFT JOIN MARCAS M ON P.EVALGROUP1 = M.ID_MARCA
			LEFT JOIN ESTADO E ON P.EVALGROUP2 = E.ID_ESTADO
			LEFT JOIN GENERO G ON P.EVALGROUP3 = G.ID_GENERO
			LEFT JOIN COLORES C ON P.EVALGROUP4 = C.ID_COLORES
			WHERE 1=1  AND PATRIMONIALES = 1";
		if ($query) {
			$sql .= " AND A.TAG_SERIE +' '+P.DESCRIPT+' '+P.ORIG_ASSET LIKE '%" . $query . "%'";
		}
		if ($loc != '') {
			$sql .= " AND P.LOCATION = '" . $loc . "' ";
		}
		if ($cus != '') {
			$sql .= " AND PE.ID_PERSON = '" . $cus . "' ";
		}
		if ($whereid) {
			$sql .= '  AND id_plantilla = ' . $whereid . ' ';
		}
		if ($pag) {
			$pagi = explode('-', $pag);
			$ini = $pagi[0];
			$fin = $pagi[1];
			$sql .= "OFFSET " . $ini . " ROWS FETCH NEXT " . $fin . " ROWS ONLY;";
		}
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function lista_articulos_sap($query, $loc, $cus, $pag = false, $whereid = false, $mes = false, $desde = false, $hasta = false)
	{
		$sql = "SELECT id_plantilla,COMPANYCODE,A.TAG_SERIE,DESCRIPT,DESCRIPT2,MODELO,SERIE,EMPLAZAMIENTO,L.DENOMINACION,PE.PERSON_NO,PE.PERSON_NOM,M.DESCRIPCION as 'marca',E.DESCRIPCION as 'estado',G.DESCRIPCION as 'genero',C.DESCRIPCION as 'color',FECHA_INV_DATE,ASSETSUPNO,ASSETSUPNO,TAG_ANT,QUANTITY,BASE_UOM,ORIG_ASSET,ORIG_ACQ_YR,ORIG_VALUE,CARACTERISTICA,PROYECTO.programa_financiacion as 'criterio',TAG_UNIQUE,SUBNUMBER,OBSERVACION,IMAGEN  FROM PLANTILLA_MASIVA P
			LEFT JOIN ASSET A ON P.ID_ASSET = A.ID_ASSET
			LEFT JOIN LOCATION L ON P.LOCATION = L.ID_LOCATION
			LEFT JOIN PERSON_NO PE ON P.PERSON_NO = PE.ID_PERSON
			LEFT JOIN MARCAS M ON P.EVALGROUP1 = M.ID_MARCA
			LEFT JOIN ESTADO E ON P.EVALGROUP2 = E.ID_ESTADO
			LEFT JOIN GENERO G ON P.EVALGROUP3 = G.ID_GENERO
			LEFT JOIN COLORES C ON P.EVALGROUP4 = C.ID_COLORES
			LEFT JOIN PROYECTO ON P.EVALGROUP5 = PROYECTO.ID_PROYECTO 
			WHERE A.TAG_SERIE +' '+P.DESCRIPT LIKE '%" . $query . "%'";
		if ($loc != '') {
			$sql .= " AND L.ID_LOCATION = '" . $loc . "' ";
		}
		if ($cus != '') {
			$sql .= " AND PE.ID_PERSON = '" . $cus . "' ";
		}
		if ($whereid) {
			$sql .= '  AND id_plantilla = ' . $whereid . ' ';
		}
		if ($mes) {
			$sql .= " AND FECHA_INV_DATE BETWEEN '" . $desde . "' AND '" . $hasta . "' ";
		}

		$sql .= " ORDER BY id_plantilla ";
		if ($pag) {
			$pagi = explode('-', $pag);
			$ini = $pagi[0];
			$fin = $pagi[1];
			$sql .= "OFFSET " . $ini . " ROWS FETCH NEXT " . $fin . " ROWS ONLY;";
		}
		//print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function lista_articulos_sap_multiples($query = false, $loc = false, $cus = false, $pag = false, $whereid = false, $exacto = false, $asset = false, $bajas = false, $terceros = false, $patrimoniales = false, $desde = false, $hasta = false, $multiple = false)
	{
		$sql = "SELECT id_plantilla,COMPANYCODE,A.TAG_SERIE,DESCRIPT,DESCRIPT2,MODELO,SERIE,EMPLAZAMIENTO,L.DENOMINACION,PE.PERSON_NO,PE.PERSON_NOM,M.CODIGO as 'marca',E.CODIGO as 'estado',G.CODIGO as 'genero',C.CODIGO as 'color',FECHA_INV_DATE,ASSETSUPNO,ASSETSUPNO,TAG_ANT,QUANTITY,BASE_UOM,ORIG_ASSET,ORIG_ACQ_YR,ORIG_VALUE,CARACTERISTICA,PROYECTO.programa_financiacion as 'criterio',TAG_UNIQUE,SUBNUMBER,OBSERVACION,IMAGEN,ACTU_POR,BAJAS,FECHA_BAJA,FECHA_CONTA,FECHA_REFERENCIA,PERIODO,P.CLASE_MOVIMIENTO,CM.DESCRIPCION AS 'MOVIMIENTO'  FROM PLANTILLA_MASIVA P
			LEFT JOIN ASSET A ON P.ID_ASSET = A.ID_ASSET
			LEFT JOIN LOCATION L ON P.LOCATION = L.ID_LOCATION
			LEFT JOIN PERSON_NO PE ON P.PERSON_NO = PE.ID_PERSON
			LEFT JOIN MARCAS M ON P.EVALGROUP1 = M.ID_MARCA
			LEFT JOIN ESTADO E ON P.EVALGROUP2 = E.ID_ESTADO
			LEFT JOIN GENERO G ON P.EVALGROUP3 = G.ID_GENERO
			LEFT JOIN COLORES C ON P.EVALGROUP4 = C.ID_COLORES
			LEFT JOIN PROYECTO ON P.EVALGROUP5 = PROYECTO.ID_PROYECTO
			LEFT JOIN CLASE_MOVIMIENTO CM ON P.CLASE_MOVIMIENTO = CM.CODIGO 
			WHERE 1=1";

		// print_r('dd'.$multiple);die();
		if ($exacto) {
			if ($asset) {
				if ($query && $multiple == false || $multiple == 0) {
					$sql .= " AND A.TAG_SERIE LIKE '" . $query . "%'";
				} else {
					$sql .= " AND A.TAG_SERIE in (" . $query . ")";
				}
			} else if ($asset == 2) {
				if ($query && $multiple == false || $multiple == 0) {
					$sql .= " AND P.ORIG_ASSET LIKE '" . $query . "%'";
				} else {
					$sql .= " AND P.ORIG_ASSET in (" . $query . ")";
				}
			} else {
				if ($query && $multiple == false || $multiple == 0) {
					$sql .= " AND A.TAG_UNIQUE LIKE '%" . $query . "%'";
				} else {
					$sql .= " AND A.TAG_UNIQUE in (" . $query . ")";
				}
			}
		} else {
			if ($query) {
				$sql .= " AND A.TAG_SERIE +' '+P.DESCRIPT+' '+P.ORIG_ASSET +' '+A.TAG_UNIQUE LIKE '%" . $query . "%'";
			}
		}

		if ($loc) {
			$sql .= " AND P.LOCATION = '" . $loc . "' ";
		}
		if ($cus) {
			$sql .= " AND PE.ID_PERSON = '" . $cus . "' ";
		}
		if ($whereid) {
			$sql .= '  AND id_plantilla = ' . $whereid . ' ';
		}
		if ($bajas) {
			$sql .= ' AND  BAJAS = 1';
		}
		if ($terceros) {
			$sql .= ' AND  TERCEROS= 1';
		}
		if ($patrimoniales) {
			$sql .= ' AND  PATRIMONIALES = 1';
		}
		if ($desde  && $hasta) {
			$sql .= ' AND FECHA_INV_DATE BETWEEN ' . $desde . ' AND ' . $hasta;
		}
		$sql .= " ORDER BY id_plantilla ";
		if ($pag) {
			$pagi = explode('-', $pag);
			$ini = $pagi[0];
			$fin = $pagi[1];
			$sql .= "OFFSET " . $ini . " ROWS FETCH NEXT " . $fin . " ROWS ONLY;";
		}
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function total_activos($query = false, $loc = false, $cus = false, $pag = false, $whereid = false, $desde = false, $hasta = false, $bajas = false, $terceros = false, $patrimoniales = false)
	{
		$sql = "SELECT COUNT(*) as 'total' FROM PLANTILLA_MASIVA P
			LEFT JOIN ASSET A ON P.ID_ASSET = A.ID_ASSET
			LEFT JOIN LOCATION L ON P.LOCATION = L.ID_LOCATION
			LEFT JOIN PERSON_NO PE ON P.PERSON_NO = PE.ID_PERSON
			LEFT JOIN MARCAS M ON P.EVALGROUP1 = M.ID_MARCA
			LEFT JOIN ESTADO E ON P.EVALGROUP2 = E.ID_ESTADO
			LEFT JOIN GENERO G ON P.EVALGROUP3 = G.ID_GENERO
			LEFT JOIN COLORES C ON P.EVALGROUP4 = C.ID_COLORES
			LEFT JOIN PROYECTO ON P.EVALGROUP5 = PROYECTO.ID_PROYECTO
			LEFT JOIN CLASE_MOVIMIENTO CM ON P.CLASE_MOVIMIENTO = CM.CODIGO
			WHERE 1=1 ";

		if ($query) {
			$sql .= "A.TAG_SERIE +' '+P.DESCRIPT LIKE '%" . $query . "%' ";
		}
		if ($loc != '') {
			$sql .= " AND L.ID_LOCATION = '" . $loc . "' ";
		}
		if ($cus != '') {
			$sql .= " AND PE.ID_PERSON = '" . $cus . "' ";
		}
		if ($whereid) {
			$sql .= '  AND id_plantilla = ' . $whereid . ' ';
		}
		if ($desde != '' && $hasta != '') {
			$sql .= " AND FECHA_INV_DATE BETWEEN '" . $desde . "' AND '" . $hasta . "' ";
		}
		if ($bajas) {
			$sql .= " AND BAJAS =1";
			if ($desde  && $hasta) {
				$sql .= " AND FECHA_BAJA BETWEEN '" . $desde . "' AND '" . $hasta . "'";
			}
		}
		if ($patrimoniales) {
			$sql .= " AND PATRIMONIALES = '1' ";
		}
		if ($terceros) {
			$sql .= " AND TERCEROS = '1' ";
		}
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		// print_r($datos);die();
		return $datos;
	}

	function lista_articulos_sap_codigos($query = false, $loc = false, $cus = false, $pag = false, $whereid = false, $mes = false, $desde = false, $hasta = false, $bajas = false, $terceros = false, $patrimoniales = false)
	{
		$sql = "SELECT id_plantilla,COMPANYCODE,A.TAG_SERIE,DESCRIPT,DESCRIPT2,MODELO,SERIE,EMPLAZAMIENTO,L.DENOMINACION,PE.PERSON_NO,PE.PERSON_NOM,M.CODIGO as 'marca',E.CODIGO as 'estado',G.CODIGO as 'genero',C.CODIGO as 'color',FECHA_INV_DATE,ASSETSUPNO,ASSETSUPNO,TAG_ANT,QUANTITY,BASE_UOM,ORIG_ASSET,ORIG_ACQ_YR,ORIG_VALUE,CARACTERISTICA,PROYECTO.programa_financiacion as 'criterio',TAG_UNIQUE,SUBNUMBER,OBSERVACION,IMAGEN,ACTU_POR,BAJAS,FECHA_BAJA,FECHA_CONTA,FECHA_REFERENCIA,PERIODO,P.CLASE_MOVIMIENTO,CM.DESCRIPCION AS 'MOVIMIENTO',FECHA_INV_DATE  FROM PLANTILLA_MASIVA P
			LEFT JOIN ASSET A ON P.ID_ASSET = A.ID_ASSET
			LEFT JOIN LOCATION L ON P.LOCATION = L.ID_LOCATION
			LEFT JOIN PERSON_NO PE ON P.PERSON_NO = PE.ID_PERSON
			LEFT JOIN MARCAS M ON P.EVALGROUP1 = M.ID_MARCA
			LEFT JOIN ESTADO E ON P.EVALGROUP2 = E.ID_ESTADO
			LEFT JOIN GENERO G ON P.EVALGROUP3 = G.ID_GENERO
			LEFT JOIN COLORES C ON P.EVALGROUP4 = C.ID_COLORES
			LEFT JOIN PROYECTO ON P.EVALGROUP5 = PROYECTO.ID_PROYECTO
			LEFT JOIN CLASE_MOVIMIENTO CM ON P.CLASE_MOVIMIENTO = CM.CODIGO 
			WHERE 1=1 ";

		if ($query) {
			$sql .= "A.TAG_SERIE +' '+P.DESCRIPT LIKE '%" . $query . "%' ";
		}
		if ($loc != '') {
			$sql .= " AND L.ID_LOCATION = '" . $loc . "' ";
		}
		if ($cus != '') {
			$sql .= " AND PE.ID_PERSON = '" . $cus . "' ";
		}
		if ($whereid) {
			$sql .= '  AND id_plantilla = ' . $whereid . ' ';
		}
		if ($mes) {
			if ($desde != '' && $hasta != '') {
				$desde = str_replace('-', '', $desde);
				$hasta = str_replace('-', '', $hasta);
				$sql .= " AND FECHA_INV_DATE BETWEEN '" . $desde . "' AND '" . $hasta . "' ";
			}
		}
		if ($bajas) {
			$sql .= " AND BAJAS ='1'";
			if ($desde != ''  && $hasta != '') {
				$sql .= " AND FECHA_BAJA BETWEEN '" . $desde . "' AND '" . $hasta . "'";
			}
		}
		if ($patrimoniales) {
			$sql .= " AND PATRIMONIALES = '1' ";
		}
		if ($terceros) {
			$sql .= " AND TERCEROS = '1' ";
		}

		$sql .= " ORDER BY id_plantilla ";
		if ($pag) {
			$pagi = explode('-', $pag);
			$ini = $pagi[0];
			$fin = $pagi[1];
			$sql .= "OFFSET " . $ini . " ROWS FETCH NEXT " . $fin . " ROWS ONLY;";
		}
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function buscar_acticulos_existente($asset)
	{
		$sql = "SELECT id_plantilla,COMPANYCODE,TAG_SERIE,A.ID_ASSET,SUBNUMBER,DESCRIPT,DESCRIPT2,MODELO,SERIE,A.TAG_UNIQUE,FECHA_INV_DATE,QUANTITY,BASE_UOM,LOCATION,PERSON_NO,EVALGROUP1,EVALGROUP2,EVALGROUP3,EVALGROUP4,EVALGROUP5,ASSETSUPNO,ORIG_ASSET,ORIG_ACQ_YR,ORIG_VALUE,OBSERVACION,BAJAS,CARACTERISTICA,IMAGEN,ACTU_POR FROM ASSET A
		INNER JOIN PLANTILLA_MASIVA PM ON A.ID_ASSET = PM.ID_ASSET
		WHERE A.TAG_SERIE = '" . $asset . "'";
		return $this->db->datos($sql);
	}

	function meses_modificado()
	{
		$sql = "Select DISTINCT DateName(month,FECHA_INV_DATE) as 'mes',MONTH(FECHA_INV_DATE) as 'num' FROM PLANTILLA_MASIVA WHERE FECHA_INV_DATE IS NOT NULL ORDER BY num";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function lista_articulos_pag()
	{
		$sql = "SELECT ID_MARCA,CODIGO,DESCRIPCION FROM articulos ";
		// $sql = "SELECT TOP() ID_MARCA,CODIGO,DESCRIPCION FROM articulos ";
		// if($id)
		// {
		// 	$sql.= ' WHERE ID_MARCA= '.$id;
		// }
		$sql .= " ORDER BY ID_MARCA DESC";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function buscar_articulos($buscar)
	{
		$sql = "SELECT ID_MARCA,CODIGO,DESCRIPCION FROM articulos WHERE DESCRIPCION +' '+CODIGO LIKE '%" . $buscar . "%'";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function insertar($datos, $tabla = 'articulos')
	{
		$rest = $this->db->inserts($tabla, $datos);
		return $rest;
	}

	function editar($datos, $where)
	{

		$rest = $this->db->update('articulos', $datos, $where);
		return $rest;
	}

	function update($tabla, $datos, $where)
	{
		$rest = $this->db->update($tabla, $datos, $where);
		return $rest;
	}

	function editar_asser($datos, $where)
	{
		// print_r($datos);die();
		$rest = $this->db->update('ASSET', $datos, $where);
		return $rest;
	}

	function eliminar($datos, $tabla = 'articulos')
	{
		$rest = $this->db->delete($tabla, $datos);
		return $rest;
	}

	function existe($datos)
	{
		$sql = "SELECT COUNT(ID_ASSET) from ASSET WHERE TAG_UNIQUE ='" . $datos . "'";
		$rest = $this->db->existente($sql);
		return $rest;
	}

	function existe_datos()
	{
		$sql = "SELECT * FROM IMPRIMIR_TAGS";
		$rest = $this->db->existente($sql);
		//print_r($rest);die();
		return $rest;
	}

	function asset($datos)
	{
		$sql = "SELECT ID_ASSET,TAG_SERIE,TAG_ANT,TAG_UNIQUE from ASSET WHERE TAG_SERIE ='" . $datos . "'";
		$rest = -1;
		if ($this->db->existente($sql) == 1) {
			$rest = $this->db->datos($sql);
		}
		return $rest;
	}

	function cambiar_masivo($tipo, $ids, $despues)
	{
		if ($tipo == 'C') {
			$sql = 'UPDATE PLANTILLA_MASIVA SET PERSON_NO = ' . $despues . ' WHERE id_plantilla IN (' . $ids . ')';
			// print_r($sql);die();
			return $this->db->sql_string($sql);
		} else {
			$sql = 'UPDATE PLANTILLA_MASIVA SET LOCATION = ' . $despues . ' WHERE id_plantilla IN (' . $ids . ')';
			// print_r($sql);die();
			return $this->db->sql_string($sql);
		}
	}

	function log_activo($fecha)
	{
		$sql = "SELECT * FROM log_activos WHERE estado = 0 AND fecha = '" . $fecha . "' ORDER by id_log desc ";
		// print_r($sql);die();
		$re = $this->db->datos($sql);
		return $re;
	}

	function cambios($desde = false, $hasta = false)
	{
		$sql = "SELECT M.id_plantilla,A.TAG_SERIE,P.DESCRIPT,fecha_movimiento,dato_anterior,codigo_ant,dato_nuevo,codigo_nue,responsable,obs_movimiento FROM MOVIMIENTO M
			INNER JOIN PLANTILLA_MASIVA P ON M.id_plantilla = P.id_plantilla 
       LEFT JOIN ASSET A ON P.ID_ASSET = A.ID_ASSET
			WHERE 1=1";
		if ($desde != false && $hasta != false) {
			$sql .= " AND fecha_movimiento BETWEEN '" . $desde . "' AND '" . $hasta . "' ";
		}
		$sql .= " ORDER BY id_movimiento DESC";
		// print_r($sql);die();
		$re = $this->db->datos($sql);
		return $re;
	}

	function set_get_sql()
	{
		// print_r($this->sql_busqueda);die();
		return $this->sql_busqueda;
	}

	function tabla_campo($campo)
	{
		return $this->db->en_tabla($campo);
	}

	function ejecutar_sql($sql)
	{
		// print_r($sql);die();
		return $this->db->datos($sql);
	}
}
