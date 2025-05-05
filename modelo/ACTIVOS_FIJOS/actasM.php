<?php
if (!class_exists('db')) {
	include(dirname(__DIR__, 2) . '/db/db.php');
}

/**
 * 
 **/

class actasM
{

	private $db;

	function __construct()
	{
		$this->db = new db();
	}

	function add($tabla, $datos)
	{
		return $this->db->inserts($tabla, $datos);
	}

	function update($tabla, $datos, $Where)
	{
		return $this->db->update($tabla, $datos, $Where);
	}

	function existe_en_lista($id, $usuario)
	{
		$sql = "SELECT * FROM ac_articulos_actas WHERE id_articulo = '" . $id . "' AND usuario = '" . $usuario . "';";
		// print_r($sql);die();
		return $this->db->datos($sql);
	}

	function eliminar_lista($id = false)
	{
		$sql = "DELETE FROM ac_articulos_actas WHERE usuario='" . $_SESSION['INICIO']['ID_USUARIO'] . "'";
		if ($id) {
			$sql .= " AND id_acta_art = '" . $id . "'";
		}

		// print_r($sql);die();
		return $this->db->sql_string($sql);
	}

	function secuencial_acta($query)
	{
		$sql = "SELECT * FROM ac_secuenciales WHERE DETALLE = '" . $query . "'";
		$datos =  $this->db->datos($sql);
		if (count($datos) == 0) {
			$sql2 = "INSERT INTO ac_secuenciales (DETALLE,NUMERO)VALUES('" . $query . "',1,1)";
			$this->db->sql_string($sql2);
			$datos =  $this->db->datos($sql);
		}

		// print_r($datos);die();
		return $datos;
	}

	function secuencial_acta_update($query)
	{
		$sql = "SELECT * FROM ac_secuenciales WHERE DETALLE = '" . $query . "'";
		$datos =  $this->db->datos($sql);
		if (count($datos) > 0) {
			$sql2 = "UPDATE ac_secuenciales SET NUMERO = " . ($datos[0]['NUMERO'] + 1) . "WHERE DETALLE = '" . $query . "'";
			$this->db->sql_string($sql2);
		}
	}

	function lista_actas($usuario = '')
	{
		$sql = "SELECT 
					AC.id_acta_art AS id,
					A.tag_serie AS tag,
					A.tag_unique AS RFID,

					A.tag_antiguo,
					A.descripcion AS articulo,
					A.precio AS valor,

					A.SERIE,
					A.MODELO,
					A.fecha_contabilizacion AS FECHA_CONTA,
					A.fecha_referencia AS FECHA_COMPRA,

					CONCAT(P.th_per_primer_apellido, ' ',P.th_per_segundo_apellido, ' ',P.th_per_primer_nombre, ' ',P.th_per_segundo_nombre) AS 'PERSON_NOM',
					L.DENOMINACION
				FROM 
					ac_articulos_actas AC
				INNER JOIN 
					ac_articulos A ON AC.id_articulo = A.id_articulo
				LEFT JOIN 
					th_personas P ON A.th_per_id = P.th_per_id
				LEFT JOIN 
					ac_localizacion L ON A.id_localizacion = L.id_localizacion
				WHERE 
					AC.usuario = '" . $usuario . "';";
		// print_r($sql);die();
		return $this->db->datos($sql);
	}

	function articulo($asset)
	{
		$sql = "SELECT 
					P.id_articulo,
					PE.th_per_codigo_sap AS PERSON_NO,
					CONCAT(PE.th_per_primer_apellido, ' ',PE.th_per_segundo_apellido, ' ',PE.th_per_primer_nombre, ' ',PE.th_per_segundo_nombre) AS 'PERSON_NOM',
					L.EMPLAZAMIENTO,
					L.DENOMINACION,
					*
				FROM ac_articulos P
				LEFT JOIN ac_localizacion L ON P.id_localizacion = L.id_localizacion
				LEFT JOIN th_personas PE ON P.th_per_id = PE.th_per_id
				WHERE TAG_SERIE = '" . $asset . "';";

		$datos = $this->db->datos($sql);
		return $datos;
	}

	function cantidad_registros($query = false, $loc = false, $cus = false, $pag = false, $whereid = false, $asset = false, $exacto = false, $masivo = false, $masivo_cus = false, $masivo_loc = false, $tipo_articulo = false)
	{
		$sql = "SELECT COUNT(id_articulo) as 'numreg' FROM ac_articulos A
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
		if ($exacto) {
			if ($query != '') {
				if ($asset) {
					if ($query && $masivo == false || $masivo == 0) {
						$sql .= " AND A.TAG_SERIE LIKE '" . $query . "%'";
					} else {
						$sql .= " AND A.TAG_SERIE in (" . $query . ")";
					}
				} else {
					if ($query && $masivo == false || $masivo == 0) {
						$sql .= " AND A.TAG_UNIQUE LIKE '%" . $query . "%'";
					} else {
						$sql .= " AND A.TAG_UNIQUE in (" . $query . ")";
					}
				}
			}
		} else {
			if ($query) {
				$sql .= " AND A.descripcion + ' ' + A.tag_serie + ' ' + A.tag_unique LIKE '%" . $query . "%'";
			}
		}

		if ($loc) {
			if ($masivo_loc) {
				$sql .= " AND L.id_localizacion IN (" . $loc . ") ";
			} else {
				$sql .= " AND L.id_localizacion = '" . $loc . "' ";
			}
		}

		if ($cus) {
			if ($masivo_cus) {
				$sql .= " AND P.th_per_id IN (" . $cus . ") ";
			} else {
				$sql .= " AND P.th_per_id = '" . $cus . "' ";
			}
		}

		if ($whereid) {
			$sql .= '  AND id_articulo = ' . $whereid . ' ';
		}

		if ($tipo_articulo) {
			$sql .= ' AND  A.id_tipo_articulo = ' . $tipo_articulo . ' ';
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

	function lista_articulos($query = false, $loc = false, $cus = false, $pag = false, $whereid = false, $exacto = false, $asset = false, $desde = false, $hasta = false, $masivo = false, $masivo_cus = false, $masivo_loc = false, $tipo_articulo = false)
	{
		// print_r($asset); exit(); die();

		$sql = "SELECT 
					A.id_articulo AS 'id',
					A.tag_serie AS 'tag',
					A.tag_unique AS 'RFID',
					A.serie,
					A.descripcion AS 'nom',
					A.modelo,
					A.imagen,
					A.observaciones AS 'observacion',
					A.fecha_referencia AS 'fecha_in',
					A.fecha_baja,
					A.id_proyecto,
					A.id_clase_movimiento,
					A.precio AS 'valor',
	
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
		if ($exacto) {
			if ($asset == 1) {
				if ($query) {
					if ($masivo) {
						$sql .= " AND A.tag_serie IN (" . $query . ")";
					} else {
						$sql .= " AND A.tag_serie LIKE '" . $query . "%'";
					}
				}
			} else if ($asset == 2) {
				if ($query) {
					if ($masivo) {
						$sql .= " AND A.tag_unique IN (" . $query . ")";
					} else {
						$sql .= " AND A.tag_unique LIKE '" . $query . "%'";
					}
				}
			}
		} else {
			if ($query) {
				$sql .= " AND A.descripcion + ' ' + A.tag_serie + ' ' + A.tag_unique LIKE '%" . $query . "%'";
			}
		}

		if ($loc) {
			if ($masivo_loc) {
				$sql .= " AND L.id_localizacion IN (" . $loc . ") ";
			} else {
				$sql .= " AND L.id_localizacion = '" . $loc . "' ";
			}
		}

		if ($cus) {
			if ($masivo_cus) {
				$sql .= " AND P.th_per_id IN (" . $cus . ") ";
			} else {
				$sql .= " AND P.th_per_id = '" . $cus . "' ";
			}
		}

		if ($whereid) {
			$sql .= '  AND id_articulo = ' . $whereid . ' ';
		}

		if ($tipo_articulo) {
			$sql .= ' AND  A.id_tipo_articulo = ' . $tipo_articulo . ' ';
		}

		if ($desde  && $hasta) {
			$sql .= ' AND fecha_referencia BETWEEN ' . $desde . ' AND ' . $hasta;
		}
		$sql .= " ORDER BY id_articulo ";
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

	function lineas_solicitud($id)
	{
		$sql = "SELECT LS.id_linea_salida as idls,P.id_articulo as id,A.TAG_SERIE as codigo,A.TAG_ANT AS ori ,A.TAG_UNIQUE as 'rfid',M.DESCRIPCION as marca,P.DESCRIPT as item,P.SERIE as serie,P.MODELO as modelo,observacion_salida as 'salida',observacion_entrada as 'entrada' FROM ac_lineas_solicitud LS 
		INNER JOIN ac_articulos P ON LS.id_activo = P.id_articulo
		INNER JOIN ac_asset A ON P.ID_ASSET = A.ID_ASSET 
		INNER JOIN ac_marcas M ON P.EVALGROUP1 = M.ID_MARCA
		WHERE id_solicitud = '" . $id . "'";
		return $this->db->datos($sql);
	}

	function solicitud($id)
	{
		$sql = "SELECT * FROM ac_solicitud_salida SS 
		INNER JOIN th_personas P ON SS.solicitante = P.PERSON_NO
		WHERE id_solicitud = '" . $id . "'";
		return $this->db->datos($sql);
	}
}
