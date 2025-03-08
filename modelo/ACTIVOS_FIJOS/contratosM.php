<?php

if (!class_exists('db')) {
	include(dirname(__DIR__, 2) . '/db/db.php');
}

/**
 * 
 **/

class contratosM
{
	private $db;

	function __construct()
	{
		$this->db = new db();
	}

	function update($tabla, $datos, $where)
	{
		return $this->db->update($tabla, $datos, $where);
	}

	function guardar($tabla, $datos)
	{
		return $this->db->inserts($tabla, $datos);
	}

	function lista_cobertura($cobertura = false, $siniestros = false, $query = false, $id = false, $idSini = false)
	{
		$sql = "SELECT id_riesgos as 'id',nombre_riesgo as 'nombre',cobertura as 'detalle',subriesgo as 'cobertura'  
				FROM ac_riesgos 
				WHERE 1=1";
		if ($cobertura) {
			$sql .= " AND subriesgo is NULL";
		}
		if ($siniestros) {
			$sql .= " AND subriesgo is NOT NULL";
		}
		if ($query) {
			$sql .= " AND nombre_riesgo like '%" . $query . "%'";
		}
		if ($idSini) {
			$sql .= " AND subriesgo ='" . $idSini . "'";
		}
		if ($id) {
			$sql .= " AND id_riesgos ='" . $id . "'";
		}

		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function lista_proveedores($query = false, $id = false)
	{
		$sql = "SELECT id_proveedor as 'id',nombre,ci_ruc,telefono,direccion,email FROM PROVEEDOR WHERE 1=1 ";
		if ($query) {
			$sql .= " AND nombre like '%" . $query . "%'";
		}
		if ($id) {
			$sql .= ' AND id_proveedor=' . $id;
		}
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function buscar_seguro($id = false, $prove = false, $desde = false, $hasta = false, $prima = false, $suma_asegurada = False, $plan = false, $terceros = null)
	{
		$sql = "SELECT * FROM seguros WHERE 1=1 ";
		if ($terceros) {
			$sql .= " AND terceros = " . $terceros;
		} else {
			$sql .= " AND terceros is NULL";
		}
		if ($id) {
			$sql .= " AND id_contratos =" . $id;
		}
		if ($prove) {
			$sql .= " AND proveedor =" . $prove;
		}
		if ($desde != false && $hasta != false) {
			$sql .= " AND desde ='" . $desde . "' AND hasta = '" . $hasta . "' ";
		}
		if ($prima) {
			$sql .= " AND prima='" . $prima . "' ";
		}
		if ($suma_asegurada) {
			$sql .= " AND  suma_asegurada = '" . $suma_asegurada . "'";
		}
		if ($plan) {
			$sql .= " AND Plan_seguro='" . $plan . "' ";
		}
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function lista_articulos($query = false)
	{
		$sql = "SELECT TOP(100) 
					id_articulo AS 'id_plantilla',
					tag_serie AS 'TAG_SERIE',
					descripcion AS 'DESCRIPT',
					descripcion_2 AS 'DESCRIPT2',
					modelo AS 'MODELO',
					serie AS 'SERIE',
					th_per_id AS 'PERSON_NO',
					caracteristica AS 'CARACTERISTICA',
					observaciones AS 'OBSERVACION',
					tag_antiguo AS 'TAG_ANT',
					tag_unique AS 'TAG_UNIQUE',
					subnumero AS 'SUBNUMBER',
					cantidad AS 'QUANTITY',
					precio AS 'ORIG_VALUE',
					NULL AS 'COMPANYCODE',
					NULL AS 'EMPLAZAMIENTO',
					NULL AS 'DENOMINACION',
					NULL AS 'PERSON_NOM',
					NULL AS 'marca',
					NULL AS 'estado',
					NULL AS 'genero',
					NULL AS 'color',
					NULL AS 'FECHA_INV_DATE',
					NULL AS 'ASSETSUPNO',
					NULL AS 'BASE_UOM',
					NULL AS 'ORIG_ASSET',
					NULL AS 'ORIG_ACQ_YR',
					NULL AS 'criterio',
					NULL AS 'IMAGEN'
				FROM ac_articulos 
				WHERE 1 = 1";

		if ($query) {
			$sql .= " AND tag_serie + ' ' + descripcion LIKE '%" . $query . "%'";
		}

		return $this->db->datos($sql);
	}


	function asignar_a_seguro($tabla, $campo, $query = false)
	{
		$sql = "SELECT Top(100) *," . $campo . " as texto FROM " . $tabla . " where  1=1 ";

		if ($query) {
			$sql .= " AND " . $campo . " like '%" . $query . "%'";
		}

		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function lista_articulos_seguro($contrato = false, $query = false, $id_art = false)
	{
		$sql = "SELECT 
					ASE.id_arti_asegurados AS 'id',
					P.id_articulo AS 'id_plantilla',
					P.tag_serie AS 'TAG_SERIE',
					P.descripcion AS 'DESCRIPT',
					P.descripcion_2 AS 'DESCRIPT2',
					P.modelo AS 'MODELO',
					P.serie AS 'SERIE',
					P.th_per_id AS 'PERSON_NO',
					P.caracteristica AS 'CARACTERISTICA',
					P.observaciones AS 'OBSERVACION',
					P.tag_antiguo AS 'TAG_ANT',
					P.tag_unique AS 'TAG_UNIQUE',
					P.subnumero AS 'SUBNUMBER',
					P.cantidad AS 'QUANTITY',
					P.precio AS 'ORIG_VALUE',
					NULL AS 'COMPANYCODE',
					NULL AS 'EMPLAZAMIENTO',
					NULL AS 'DENOMINACION',
					NULL AS 'PERSON_NOM',
					NULL AS 'marca',
					NULL AS 'estado',
					NULL AS 'genero',
					NULL AS 'color',
					NULL AS 'FECHA_INV_DATE',
					NULL AS 'ASSETSUPNO',
					NULL AS 'BASE_UOM',
					NULL AS 'ORIG_ASSET',
					NULL AS 'ORIG_ACQ_YR',
					NULL AS 'criterio',
					NULL AS 'IMAGEN'
				FROM ARTICULOS_ASEGURADOS ASE
				LEFT JOIN ac_articulos P ON ASE.id_articulos = P.id_articulo
				WHERE 1 = 1";

		if ($contrato) {
			$sql .= " AND ASE.id_seguro = " . intval($contrato);
		}
		if ($query) {
			$sql .= " AND P.tag_serie + ' ' + P.descripcion LIKE '%" . $query . "%'";
		}
		if ($id_art) {
			$sql .= " AND ASE.id_articulos = " . intval($id_art);
		}

		$sql .= " ORDER BY P.id_articulo DESC";

		return $this->db->datos($sql);
	}

	function lista_articulos_seguro2($tabla, $id, $modulo, $seguro = false)
	{
		$sql = "SELECT * 
		FROM ARTICULOS_ASEGURADOS 
		WHERE id_articulos = '" . $id . "' 
		AND tabla = '" . $tabla . "' 
		AND modulo='" . $modulo . "'";
		if ($seguro) {
			$sql .= " AND id_seguro='" . $seguro . "'";
		}
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function lista_articulos_seguro_detalle($tabla, $id, $modulo, $id_tabla, $seguro = false, $id_arti_seguro = false)
	{
		$sql = "SELECT * 
		FROM ARTICULOS_ASEGURADOS ARS
		INNER JOIN SEGUROS S ON ARS.id_seguro = S.id_contratos 
		INNER JOIN PROVEEDOR P ON P.id_proveedor = S.proveedor 
		INNER JOIN " . $tabla . " E ON ARS.id_articulos = E." . $id_tabla . "
		WHERE id_articulos = '" . $id . "' 
		AND tabla = '" . $tabla . "' 
		AND modulo='" . $modulo . "'";
		if ($seguro) {
			$sql .= " AND id_seguro='" . $seguro . "'";
		}
		if ($id_arti_seguro) {
			$sql .= " AND id_arti_asegurados='" . $id_arti_seguro . "'";
		}

		$sql .= " ORDER BY terceros ASC";
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function Articulo_contrato_delete($id)
	{
		$sql = "DELETE FROM ARTICULOS_ASEGURADOS WHERE id_arti_asegurados='" . $id . "'";
		// print_r($sql);die();
		return $this->db->sql_string($sql);
	}

	function lista_contratos($provedor, $desde, $hasta, $opc = false)
	{
		$sql = "SELECT id_contratos as 'id',proveedor,nombre,desde,hasta,prima,suma_asegurada,nombre_riesgo,siniestro,estado 
		FROM SEGUROS S
		INNER JOIN PROVEEDOR P ON S.proveedor = P.id_proveedor
		INNER JOIN  ac_riesgos C ON S.cobertura = C.id_riesgos
		WHERE 1=1 ";
		if ($provedor) {
			$sql .= " AND P.nombre like '%" . $provedor . "%'";
		}

		if ($opc) {
			// print_r($opc);die();
			if ($opc == 1) {
				if ($desde && $hasta) {
					$sql .= " AND desde between '" . $desde . "' AND '" . $hasta . "'";
				}
			} else {
				if ($desde && $hasta) {
					$sql .= " AND hasta between '" . $desde . "' AND '" . $hasta . "'";
				}
			}
		}
		// print_r($sql);die();

		$datos = $this->db->datos($sql);
		return $datos;
	}
	function cargar_datos_seguro_art($id)
	{
		$sql = "SELECT * 
			FROM ARTICULOS_ASEGURADOS ASE
			INNER JOIN  SEGUROS S ON ASE.id_seguro = S.id_contratos
			INNER JOIN ac_riesgos R ON S.cobertura = R.id_riesgos
			INNER JOIN PROVEEDOR P ON S.proveedor = P.id_proveedor
			WHERE 1=1 ";
		if ($id) {
			$sql .= " AND id_articulos = '" . $id . "'";
		}

		// print_r($sql);die();
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

	function historial_siniestro($articulo = false, $id = false)
	{
		$sql = "SELECT id_deterioro,articulo,detalle,fecha,D.estado,E.DESCRIPCION,encargado,fecha_siniestro,fecha_alertado,respuesta,estado_proceso,evaluacion
		FROM ac_deterioro D
		INNER JOIN ac_estado E ON D.estado = E.ID_ESTADO 
		WHERE 1=1 ";
		if ($articulo) {
			$sql .= " AND articulo = '" . $articulo . "' ";
		}
		if ($id) {
			$sql .= " AND id_deterioro = '" . $id . "' ";
		}
		$sql .= " ORDER BY id_deterioro DESC";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function tablas_aseguradas($tabla = false, $contrato = false, $idArticulo = false, $ligar = false, $id_tabla = false)
	{
		$sql = "SELECT * FROM ARTICULOS_ASEGURADOS AR";
		if ($ligar) {
			$sql .= " INNER JOIN " . $tabla . " T ON AR.id_articulos = T." . $id_tabla;
		}
		$sql .= " WHERE  modulo = '" . $_SESSION['INICIO']['MODULO_SISTEMA'] . "'";
		if ($tabla) {
			$sql .= " AND	tabla = '" . $tabla . "'";
		}
		if ($contrato) {
			$sql .= " AND id_seguro = '" . $contrato . "'";
		}
		if ($idArticulo) {
			$sql .= " AND id_articulos='" . $idArticulo . "'";
		}

		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function lista_id_tabla($tabla, $contrato)
	{
		$sql = "SELECT id_articulos FROM ARTICULOS_ASEGURADOS 
		WHERE tabla = '" . $tabla . "'
		AND id_seguro = '" . $contrato . "'";
		$datos = $this->db->datos($sql);

		// print_r($sql);
		return $datos;
	}

	function itemAsegurado($tabla, $idtbl, $ids)
	{
		$sql = "SELECT * FROM " . $tabla . " WHERE " . $idtbl . " in (" . $ids . ")";
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}
}
