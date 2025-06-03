<?php
if (!class_exists('db')) {
	include(dirname(__DIR__, 2) . '/db/db.php');
}

require_once(dirname(__DIR__, 2) . '/assets/plugins/datatable/ssp.class.php');


/**
 * 
 **/

class articulosM
{
	private $db;
	private $sql_busqueda;

	function __construct()
	{
		$this->db = new db();
	}

	function lista_articulos_cr($id_articulo)
	{
		$USUARIO_DB = $_SESSION['INICIO']['USUARIO_DB'];
		$PASSWORD_DB = $_SESSION['INICIO']['PASSWORD_DB'];
		$BASEDATO = $_SESSION['INICIO']['BASEDATO'];
		$PUERTO_DB = $_SESSION['INICIO']['PUERTO_DB'];
		$IP_HOST = $_SESSION['INICIO']['IP_HOST'] . ', ' . $PUERTO_DB;

		// Configuración de conexión
		$sql_details = array(
			'user' => $USUARIO_DB,
			'pass' => $PASSWORD_DB,
			'db'   => $BASEDATO,
			'host' => $IP_HOST,
		);

		$table = 'v_articulos_detalle';

		$primaryKey = 'id';

		$columns = array(
			array('db' => 'tag', 'dt' => 0),            // Tag Serie
			array(
				'db' => 'nom',
				'dt' => 1,
				'formatter' => function ($d, $row) {
					// Redirigir con el ID
					return '<a type="button" href="#" onclick="redireccionar(' . "'" . $row['id'] . "'" . ')"><u>' . $row['nom'] . '</u></a>';
				}
			),
			array('db' => 'modelo', 'dt' => 2),         // Modelo
			array('db' => 'serie', 'dt' => 3),          // Serie
			array('db' => 'RFID', 'dt' => 4),           // RFID
			array('db' => 'localizacion', 'dt' => 5),   // Localización
			array('db' => 'custodio', 'dt' => 6),       // Custodio
			array('db' => 'marca', 'dt' => 7),          // Marca
			array('db' => 'estado', 'dt' => 8),         // Estado
			array('db' => 'genero', 'dt' => 9),         // Género
			array('db' => 'color', 'dt' => 10),         // Color
			array('db' => 'fecha_in', 'dt' => 11),      // Fecha Inv.
			array('db' => 'observacion', 'dt' => 12),   // Observación
			array('db' => 'id', 'dt' => 13),			//id del articulo
			array('db' => 'tipo_articulo', 'dt' => 14),	//Tipo de articulo
			array('db' => 'tipo_articulo_COLOR', 'dt' => 15),	//Tipo de articulo COLOR
		);

		$whereResult = ""; //"nom LIKE '%computadora%'"; //"nom LIKE '%computadora%'"; // Condición dinámica

		$whereAll = "";
		if ($id_articulo != '') {
			$whereAll = "id_tipo_articulo = '$id_articulo'";
		}

		//Sirve para buscar las columnas que se necesitan buscar para no sobrecargar la db
		$columnSearch = [0, 1, 4];

		//echo $table;
		return (
			SSP::complex($_POST, $sql_details, $table, $primaryKey, $columns, $whereResult, $whereAll, $columnSearch, true)
		);
		//exit;
	}

	function listar_articulos_id($id)
	{
		$sql = "SELECT * FROM v_articulos_detalle WHERE id = $id";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function lista_articulos_new($query = false, $loc = false, $cus = false, $pag = false, $desde = false, $hasta = false, $coincidencia = false, $multiple = false, $buscar_por = false)
	{
		$sql = "SELECT 
					A.id_articulo AS 'id',
					A.tag_serie AS 'tag',
					A.tag_unique AS 'RFID',
					A.serie,
					A.descripcion AS 'nom',
					A.caracteristica,
					A.modelo,
					A.imagen,
					A.observaciones AS 'observacion',
					A.fecha_referencia AS 'fecha_in',
					A.fecha_contabilizacion AS 'fecha_compra',
					A.fecha_baja,
					A.id_proyecto,
					A.id_clase_movimiento,
					A.precio,
	
					L.id_localizacion AS 'IDL',
					L.denominacion AS 'localizacion',
	
					P.th_per_id AS 'IDC',
					CONCAT(P.th_per_primer_apellido, ' ',P.th_per_segundo_apellido, ' ',P.th_per_primer_nombre, ' ',P.th_per_segundo_nombre) AS 'custodio',
	
					M.descripcion AS 'marca',
					E.descripcion AS 'estado',
					G.descripcion AS 'genero',
					C.descripcion AS 'color',
					TA.descripcion AS 'tipo_articulo'
				FROM ac_articulos A
				LEFT JOIN ac_localizacion L ON A.id_localizacion = L.id_localizacion
				LEFT JOIN th_personas P ON A.th_per_id = P.th_per_id
				LEFT JOIN ac_marcas M ON A.id_marca = M.id_marca
				LEFT JOIN ac_estado E ON A.id_estado = E.id_estado
				LEFT JOIN ac_genero G ON A.id_genero = G.id_genero
				LEFT JOIN ac_colores C ON A.id_color = C.id_colores
				LEFT JOIN ac_proyecto PR ON A.id_proyecto = PR.id_proyecto
				LEFT JOIN ac_clase_movimiento CL ON A.id_clase_movimiento = CL.id_movimiento
				LEFT JOIN ac_cat_tipo_articulo TA ON A.id_tipo_articulo = TA.id_tipo_articulo
				WHERE 1 = 1";  // Solo listar artículos activos.

		if ($loc) {
			$sql .= " AND A.id_localizacion = '" . $loc . "' ";
		}
		if ($cus) {
			$sql .= " AND P.th_per_id = '" . $cus . "' ";
		}
		if ($desde && $hasta) {
			$sql .= " AND A.fecha_referencia BETWEEN '" . str_replace('-', '', $desde) . "' AND '" . str_replace('-', '', $hasta) . "'";
		}
		if ($coincidencia) {
			if ($multiple) {
				switch ($buscar_por) {
					case '1':
						$sql .= " AND A.tag_serie IN ('" . implode("','", array_filter(explode(',', $query))) . "')";
						break;
					case '2':
						$sql .= " AND A.tag_unique IN ('" . implode("','", array_filter(explode(',', $query))) . "')";
						break;
					case '3':
						$sql .= " AND A.serie IN ('" . implode("','", array_filter(explode(',', $query))) . "')";
						break;
					default:
						$sql .= " AND A.descripcion IN ('" . implode("','", array_filter(explode(',', $query))) . "')";
						break;
				}
			} else {
				switch ($buscar_por) {
					case '1':
						$sql .= " AND A.tag_serie = '" . $query . "'";
						break;
					case '2':
						$sql .= " AND A.tag_unique = '" . $query . "'";
						break;
					case '3':
						$sql .= " AND A.serie = '" . $query . "'";
						break;
					default:
						$sql .= " AND A.descripcion = '" . $query . "'";
						break;
				}
			}
		} else {
			if ($multiple) {
				switch ($buscar_por) {
					case '1':
						$sql .= " AND A.tag_serie LIKE '%" . str_replace(',', "%' OR A.tag_serie LIKE '%", $query) . "%'";
						break;
					case '2':
						$sql .= " AND A.tag_unique LIKE '%" . str_replace(',', "%' OR A.tag_unique LIKE '%", $query) . "%'";
						break;
					case '3':
						$sql .= " AND A.serie LIKE '%" . str_replace(',', "%' OR A.serie LIKE '%", $query) . "%'";
						break;
					default:
						$sql .= " AND A.descripcion LIKE '%" . str_replace(',', "%' OR A.descripcion LIKE '%", $query) . "%'";
						break;
				}
			} else {
				switch ($buscar_por) {
					case '1':
						$sql .= " AND A.tag_serie LIKE '%" . $query . "%'";
						break;
					case '2':
						$sql .= " AND A.tag_unique LIKE '%" . $query . "%'";
						break;
					case '3':
						$sql .= " AND A.serie LIKE '%" . $query . "%'";
						break;
					default:
						$sql .= " AND A.descripcion LIKE '%" . $query . "%'";
						break;
				}
			}
		}

		$sql .= " ORDER BY A.id_articulo ";

		if ($pag) {
			$pagi = explode('-', $pag);
			$ini = $pagi[0];
			$fin = $pagi[1];
			$sql .= "OFFSET " . $ini . " ROWS FETCH NEXT " . $fin . " ROWS ONLY;";
		} else {
			$sql .= "OFFSET 0 ROWS FETCH NEXT 50 ROWS ONLY;";
		}

		//$this->sql_busqueda = $sql;
		//echo $sql; exit;
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function cantidad_registros_new($query = false, $loc = false, $cus = false, $desde = false, $hasta = false, $coincidencia = false, $multiple = false, $buscar_por = false)
	{
		$sql = "SELECT COUNT(A.id_articulo) as numreg
            FROM ac_articulos A
            LEFT JOIN ac_localizacion L ON A.id_localizacion = L.id_localizacion
            LEFT JOIN th_personas P ON A.th_per_id = P.th_per_id
            LEFT JOIN ac_marcas M ON A.id_marca = M.id_marca
            LEFT JOIN ac_estado E ON A.id_estado = E.id_estado
            LEFT JOIN ac_genero G ON A.id_genero = G.id_genero
            LEFT JOIN ac_colores C ON A.id_color = C.id_colores
            LEFT JOIN ac_proyecto PR ON A.id_proyecto = PR.id_proyecto
            LEFT JOIN ac_clase_movimiento CL ON A.id_clase_movimiento = CL.ID_MOVIMIENTO
            WHERE 1 = 1";  // Solo contar artículos activos.

		if ($loc) {
			$sql .= " AND A.id_localizacion = '" . $loc . "' ";
		}
		if ($cus) {
			$sql .= " AND P.th_per_id = '" . $cus . "' ";
		}
		if ($desde && $hasta) {
			$sql .= " AND A.fecha_referencia BETWEEN '" . str_replace('-', '', $desde) . "' AND '" . str_replace('-', '', $hasta) . "'";
		}
		if ($coincidencia) {
			if ($multiple) {
				switch ($buscar_por) {
					case '1':
						$sql .= " AND A.tag_serie IN ('" . implode("','", array_filter(explode(',', $query))) . "')";
						break;
					case '2':
						$sql .= " AND A.tag_unique IN ('" . implode("','", array_filter(explode(',', $query))) . "')";
						break;
					case '3':
						$sql .= " AND A.serie IN ('" . implode("','", array_filter(explode(',', $query))) . "')";
						break;
					default:
						$sql .= " AND A.descripcion IN ('" . implode("','", array_filter(explode(',', $query))) . "')";
						break;
				}
			} else {
				switch ($buscar_por) {
					case '1':
						$sql .= " AND A.tag_serie = '" . $query . "'";
						break;
					case '2':
						$sql .= " AND A.tag_unique = '" . $query . "'";
						break;
					case '3':
						$sql .= " AND A.serie = '" . $query . "'";
						break;
					default:
						$sql .= " AND A.descripcion = '" . $query . "'";
						break;
				}
			}
		} else {
			if ($multiple) {
				switch ($buscar_por) {
					case '1':
						$sql .= " AND A.tag_serie LIKE '%" . str_replace(',', "%' OR A.tag_serie LIKE '%", $query) . "%'";
						break;
					case '2':
						$sql .= " AND A.tag_unique LIKE '%" . str_replace(',', "%' OR A.tag_unique LIKE '%", $query) . "%'";
						break;
					case '3':
						$sql .= " AND A.serie LIKE '%" . str_replace(',', "%' OR A.serie LIKE '%", $query) . "%'";
						break;
					default:
						$sql .= " AND A.descripcion LIKE '%" . str_replace(',', "%' OR A.descripcion LIKE '%", $query) . "%'";
						break;
				}
			} else {
				switch ($buscar_por) {
					case '1':
						$sql .= " AND A.tag_serie LIKE '%" . $query . "%'";
						break;
					case '2':
						$sql .= " AND A.tag_unique LIKE '%" . $query . "%'";
						break;
					case '3':
						$sql .= " AND A.serie LIKE '%" . $query . "%'";
						break;
					default:
						$sql .= " AND A.descripcion LIKE '%" . $query . "%'";
						break;
				}
			}
		}

		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function lista_kit($id_activo)
	{
		$sql = "SELECT * FROM ac_articulos WHERE KIT = '" . $id_activo . "'";
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function cantidad_etiquetas()
	{
		$sql = "SELECT count(*) as 'eti' FROM ac_asset WHERE TAG_UNIQUE <>''";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function total_activo()
	{
		$sql = "SELECT COUNT(id_plantilla) as 'cantidad' FROM ac_articulos WHERE BAJAS=0 AND TERCEROS = 0 and PATRIMONIALES = 0";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function total_bajas()
	{
		$sql = "SELECT COUNT(id_plantilla) as 'cantidad' FROM ac_articulos WHERE BAJAS=1 AND TERCEROS = 0 and PATRIMONIALES = 0";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function total_patrimoniales()
	{
		$sql = "SELECT COUNT(id_plantilla) as 'cantidad' FROM ac_articulos WHERE BAJAS=0 AND TERCEROS = 0 and PATRIMONIALES = 1";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function total_terceros()
	{
		$sql = "SELECT COUNT(id_plantilla) as 'cantidad' FROM ac_articulos WHERE BAJAS=0 AND TERCEROS = 1 and PATRIMONIALES = 0";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function cantidad_registros_patrimoniales($query, $loc, $cus, $pag = false, $whereid = false)
	{
		$sql = "SELECT COUNT(id_plantilla) as 'numreg' FROM ac_articulos P
			LEFT JOIN ac_asset A ON P.ID_ASSET = A.ID_ASSET
			LEFT JOIN ac_localizacion L ON P.LOCATION = L.ID_LOCATION
			LEFT JOIN th_personas PE ON P.PERSON_NO = PE.ID_PERSON
			LEFT JOIN ac_marcas M ON P.EVALGROUP1 = M.ID_MARCA
			LEFT JOIN ac_estado E ON P.EVALGROUP2 = E.ID_ESTADO
			LEFT JOIN ac_genero G ON P.EVALGROUP3 = G.ID_GENERO
			LEFT JOIN ac_colores C ON P.EVALGROUP4 = C.ID_COLORES
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
		$sql = "SELECT id_plantilla,COMPANYCODE,A.TAG_SERIE,DESCRIPT,DESCRIPT2,MODELO,SERIE,EMPLAZAMIENTO,L.DENOMINACION,PE.PERSON_NO,PE.PERSON_NOM,M.DESCRIPCION as 'marca',E.DESCRIPCION as 'estado',G.DESCRIPCION as 'genero',C.DESCRIPCION as 'color',FECHA_INV_DATE,ASSETSUPNO,ASSETSUPNO,TAG_ANT,QUANTITY,BASE_UOM,ORIG_ASSET,ORIG_ACQ_YR,ORIG_VALUE,CARACTERISTICA,ac_proyecto.programa_financiacion as 'criterio',TAG_UNIQUE,SUBNUMBER,OBSERVACION,IMAGEN  FROM ac_articulos P
			LEFT JOIN ac_asset A ON P.ID_ASSET = A.ID_ASSET
			LEFT JOIN ac_localizacion L ON P.LOCATION = L.ID_LOCATION
			LEFT JOIN th_personas PE ON P.PERSON_NO = PE.ID_PERSON
			LEFT JOIN ac_marcas M ON P.EVALGROUP1 = M.ID_MARCA
			LEFT JOIN ac_estado E ON P.EVALGROUP2 = E.ID_ESTADO
			LEFT JOIN ac_genero G ON P.EVALGROUP3 = G.ID_GENERO
			LEFT JOIN ac_colores C ON P.EVALGROUP4 = C.ID_COLORES
			LEFT JOIN ac_proyecto ON P.EVALGROUP5 = ac_proyecto.ID_PROYECTO 
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
		$sql = "SELECT id_plantilla,COMPANYCODE,A.TAG_SERIE,DESCRIPT,DESCRIPT2,MODELO,SERIE,EMPLAZAMIENTO,L.DENOMINACION,PE.PERSON_NO,PE.PERSON_NOM,M.CODIGO as 'marca',E.CODIGO as 'ac_estado',G.CODIGO as 'genero',C.CODIGO as 'color',FECHA_INV_DATE,ASSETSUPNO,ASSETSUPNO,TAG_ANT,QUANTITY,BASE_UOM,ORIG_ASSET,ORIG_ACQ_YR,ORIG_VALUE,CARACTERISTICA,ac_proyecto.programa_financiacion as 'criterio',TAG_UNIQUE,SUBNUMBER,OBSERVACION,IMAGEN,ACTU_POR,BAJAS,FECHA_BAJA,FECHA_CONTA,FECHA_REFERENCIA,PERIODO,P.CLASE_MOVIMIENTO,CM.DESCRIPCION AS 'MOVIMIENTO'  FROM ac_articulos P
			LEFT JOIN ac_asset A ON P.ID_ASSET = A.ID_ASSET
			LEFT JOIN ac_localizacion L ON P.LOCATION = L.ID_LOCATION
			LEFT JOIN th_personas PE ON P.PERSON_NO = PE.ID_PERSON
			LEFT JOIN ac_marcas M ON P.EVALGROUP1 = M.ID_MARCA
			LEFT JOIN ac_estado E ON P.EVALGROUP2 = E.ID_ESTADO
			LEFT JOIN ac_genero G ON P.EVALGROUP3 = G.ID_GENERO
			LEFT JOIN ac_colores C ON P.EVALGROUP4 = C.ID_COLORES
			LEFT JOIN ac_proyecto ON P.EVALGROUP5 = ac_proyecto.ID_PROYECTO
			LEFT JOIN ac_clase_movimiento CM ON P.CLASE_MOVIMIENTO = CM.CODIGO 
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
		$sql = "SELECT COUNT(*) as 'total' FROM ac_articulos P
			LEFT JOIN ac_asset A ON P.ID_ASSET = A.ID_ASSET
			LEFT JOIN ac_localizacion L ON P.LOCATION = L.ID_LOCATION
			LEFT JOIN th_personas PE ON P.PERSON_NO = PE.ID_PERSON
			LEFT JOIN ac_marcas M ON P.EVALGROUP1 = M.ID_MARCA
			LEFT JOIN ac_estado E ON P.EVALGROUP2 = E.ID_ESTADO
			LEFT JOIN ac_genero G ON P.EVALGROUP3 = G.ID_GENERO
			LEFT JOIN ac_colores C ON P.EVALGROUP4 = C.ID_COLORES
			LEFT JOIN ac_proyecto ON P.EVALGROUP5 = ac_proyecto.ID_PROYECTO
			LEFT JOIN ac_clase_movimiento CM ON P.CLASE_MOVIMIENTO = CM.CODIGO
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
		$sql = "SELECT id_plantilla,COMPANYCODE,A.TAG_SERIE,DESCRIPT,DESCRIPT2,MODELO,SERIE,EMPLAZAMIENTO,L.DENOMINACION,PE.PERSON_NO,PE.PERSON_NOM,M.CODIGO as 'marca',E.CODIGO as 'ac_estado',G.CODIGO as 'genero',C.CODIGO as 'color',FECHA_INV_DATE,ASSETSUPNO,ASSETSUPNO,TAG_ANT,QUANTITY,BASE_UOM,ORIG_ASSET,ORIG_ACQ_YR,ORIG_VALUE,CARACTERISTICA,ac_proyecto.programa_financiacion as 'criterio',TAG_UNIQUE,SUBNUMBER,OBSERVACION,IMAGEN,ACTU_POR,BAJAS,FECHA_BAJA,FECHA_CONTA,FECHA_REFERENCIA,PERIODO,P.CLASE_MOVIMIENTO,CM.DESCRIPCION AS 'MOVIMIENTO',FECHA_INV_DATE  FROM ac_articulos P
			LEFT JOIN ac_asset A ON P.ID_ASSET = A.ID_ASSET
			LEFT JOIN ac_localizacion L ON P.LOCATION = L.ID_LOCATION
			LEFT JOIN th_personas PE ON P.PERSON_NO = PE.ID_PERSON
			LEFT JOIN ac_marcas M ON P.EVALGROUP1 = M.ID_MARCA
			LEFT JOIN ac_estado E ON P.EVALGROUP2 = E.ID_ESTADO
			LEFT JOIN ac_genero G ON P.EVALGROUP3 = G.ID_GENERO
			LEFT JOIN ac_colores C ON P.EVALGROUP4 = C.ID_COLORES
			LEFT JOIN ac_proyecto ON P.EVALGROUP5 = ac_proyecto.ID_PROYECTO
			LEFT JOIN ac_clase_movimiento CM ON P.CLASE_MOVIMIENTO = CM.CODIGO 
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
		$sql = "SELECT id_plantilla,COMPANYCODE,TAG_SERIE,A.ID_ASSET,SUBNUMBER,DESCRIPT,DESCRIPT2,MODELO,SERIE,A.TAG_UNIQUE,FECHA_INV_DATE,QUANTITY,BASE_UOM,LOCATION,PERSON_NO,EVALGROUP1,EVALGROUP2,EVALGROUP3,EVALGROUP4,EVALGROUP5,ASSETSUPNO,ORIG_ASSET,ORIG_ACQ_YR,ORIG_VALUE,OBSERVACION,BAJAS,CARACTERISTICA,IMAGEN,ACTU_POR FROM ac_asset A
		INNER JOIN ac_articulos PM ON A.ID_ASSET = PM.ID_ASSET
		WHERE A.TAG_SERIE = '" . $asset . "'";
		return $this->db->datos($sql);
	}

	function meses_modificado()
	{
		$sql = "Select DISTINCT DateName(month,FECHA_INV_DATE) as 'mes',MONTH(FECHA_INV_DATE) as 'num' FROM ac_articulos WHERE FECHA_INV_DATE IS NOT NULL ORDER BY num";
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
		$rest = $this->db->update('ac_asset', $datos, $where);
		return $rest;
	}

	function eliminar($datos, $tabla = 'articulos')
	{
		$rest = $this->db->delete($tabla, $datos);
		return $rest;
	}

	function existe($datos)
	{
		$sql = "SELECT COUNT(ID_ASSET) from ac_asset WHERE TAG_UNIQUE ='" . $datos . "'";
		$rest = $this->db->existente($sql);
		return $rest;
	}

	function existe_datos()
	{
		$sql = "SELECT * FROM ac_imprimir_tags";
		$rest = $this->db->existente($sql);
		//print_r($rest);die();
		return $rest;
	}

	function asset($datos)
	{
		$sql = "SELECT ID_ASSET,TAG_SERIE,TAG_ANT,TAG_UNIQUE from ac_asset WHERE TAG_SERIE ='" . $datos . "'";
		$rest = -1;
		if ($this->db->existente($sql) == 1) {
			$rest = $this->db->datos($sql);
		}
		return $rest;
	}

	function cambiar_masivo($tipo, $ids, $despues)
	{
		if ($tipo == 'C') {
			$sql = 'UPDATE ac_articulos SET PERSON_NO = ' . $despues . ' WHERE id_plantilla IN (' . $ids . ')';
			// print_r($sql);die();
			return $this->db->sql_string($sql);
		} else {
			$sql = 'UPDATE ac_articulos SET LOCATION = ' . $despues . ' WHERE id_plantilla IN (' . $ids . ')';
			// print_r($sql);die();
			return $this->db->sql_string($sql);
		}
	}

	function log_activo($fecha)
	{
		$sql = "SELECT * FROM ac_log_activos WHERE ac_estado = 0 AND fecha = '" . $fecha . "' ORDER by id_log desc ";
		// print_r($sql);die();
		$re = $this->db->datos($sql);
		return $re;
	}

	function cambios($desde = false, $hasta = false)
	{
		$sql = "SELECT M.id_plantilla,A.TAG_SERIE,P.DESCRIPT,fecha_movimiento,dato_anterior,codigo_ant,dato_nuevo,codigo_nue,responsable,obs_movimiento FROM ac_movimiento M
			INNER JOIN ac_articulos P ON M.id_plantilla = P.id_plantilla 
       LEFT JOIN ac_asset A ON P.ID_ASSET = A.ID_ASSET
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

	function existe_RFID_impreso($datos)
	{
		$sql = "SELECT Codigo from ac_rfid_impresos WHERE Codigo ='" . $datos . "'";
		$rest = $this->db->existente($sql);
		return $rest;
	}

	function existe_RFID($datos)
	{
		$sql = "SELECT TAG_UNIQUE from ac_asset WHERE TAG_UNIQUE ='" . $datos . "' 
		UNION SELECT Codigo FROM ac_rfid_impresos  WHERE Codigo = '" . $datos . "'";
		// print_r($sql);die();
		$rest = $this->db->existente($sql);
		// print_r($rest);die();
		return $rest;
	}

	function listar_articulos($th_per_id = null, $id_localizacion = null)
	{
		$sql = "SELECT * FROM ac_articulos WHERE 1=1";

		if (!empty($th_per_id)) {
			$sql .= " AND th_per_id = '" . $th_per_id . "'";
		}

		if (!empty($id_localizacion)) {
			$sql .= " AND id_localizacion = '" . $id_localizacion . "'";
		}

		$sql .= ";";
		// print_r($sql); die();
		$datos = $this->db->datos($sql, false, true);
		return $datos;
	}
}
