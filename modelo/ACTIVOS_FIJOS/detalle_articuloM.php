<?php
require_once(dirname(__DIR__, 1) . '/GENERAL/BaseModel.php');
require_once(dirname(__DIR__, 2) . '/db/codigos_globales.php');


/**
 * 
 **/

class detalle_articuloM extends BaseModel
{
	protected $tabla = 'ac_articulos';
	protected $primaryKey = 'id_articulos AS _id';
	private $codigos_globales;

	protected $camposPermitidos = [
		'tag_unique AS tag_unique',
	];

	function guardar($tabla, $datos)
	{
		$datos = $this->db->inserts($tabla, $datos);
		return $datos;
	}

	function update($tabla, $datos, $where)
	{
		$datos = $this->db->update($tabla, $datos, $where);
		return $datos;
	}

	function cargar_datos($id)
	{
		$id = intval($id);

		$sql = "SELECT
					-- Identificación del artículo
					P.id_articulo AS 'id_A',
					P.tag_unique AS 'rfid',
					P.tag_serie AS 'tag_s',
					P.tag_antiguo AS 'ant',
					P.subnumero AS 'subnum',
					P.longitud_rfid AS 'longitud_rfid',
					-- Descripción y características
					P.descripcion AS 'nom',
					P.descripcion_2 AS 'des',
					P.caracteristica AS 'carac',
					P.observaciones AS 'obs',
					P.modelo AS 'mod',
					P.serie AS 'ser',
					-- Cantidad, precio y estado del artículo
					P.cantidad AS 'cant',
					P.precio AS 'prec',
					P.imagen AS 'imagen',
					P.kit AS 'es_kit',
					P.maximo AS 'max',
					P.minimo AS 'min',
					-- Unidades de medida y tipo de artículo
					P.id_unidad_medida AS 'id_unidad_medida',
					CONCAT(UM.ac_nombre, ' - ', UM.ac_simbolo) AS 'unidad_medida',
					P.id_tipo_articulo AS 'id_tipo_articulo',
					-- Localización
					P.id_localizacion AS 'id_loc',
					L.DENOMINACION AS 'loc_nom',
					L.EMPLAZAMIENTO AS 'c_loc',
					-- Custodio (Persona)
					PE.th_per_id AS 'id_person',
					PE.th_per_cedula AS 'person_ci',
					CONCAT(PE.th_per_primer_apellido, ' ', PE.th_per_segundo_apellido, ' ', PE.th_per_primer_nombre, ' ', PE.th_per_segundo_nombre) AS 'person_nom',
					PE.th_per_correo AS 'person_correo',
					PE.th_per_telefono_1 AS 'telefono',
					PE.th_per_direccion AS 'direccion',
					PE.th_per_foto_url AS 'foto',
					PE.th_per_codigo_sap AS 'person_no',
					PE.th_per_unidad_org_sap AS 'unidad_org',
					-- Marca, Estado, Género, Color
					P.id_marca AS 'id_mar',
					M.DESCRIPCION AS 'marca',
					M.CODIGO AS 'c_mar',
					P.id_estado AS 'id_est',
					E.CODIGO AS 'c_est',
					E.DESCRIPCION AS 'estado',
					P.id_genero AS 'id_gen',
					G.DESCRIPCION AS 'genero',
					G.CODIGO AS 'c_gen',
					P.id_color AS 'id_col',
					C.DESCRIPCION AS 'color',
					C.CODIGO AS 'c_col',
					-- Proyecto
					P.id_proyecto AS 'id_pro',
					PR.denominacion AS 'proyecto',
					PR.programa_financiacion AS 'c_pro',
					-- Clase de Movimiento
					P.id_clase_movimiento AS 'id_clase_movimiento',
					CM.DESCRIPCION AS 'movimiento',
					-- Familia y Subfamilia
					P.id_familia AS 'id_fam',
					F.detalle_familia AS 'familia',
					P.id_subfamilia AS 'id_subfam',
					SF.detalle_familia AS 'subfamilia',
					-- Información financiera
					P.companycode AS 'companycode',
					P.centro_costos AS 'centro_costos',
					P.resp_cctr AS 'resp_cctr',
					P.funds_ctr_apc AS 'funds_ctr_apc',
					P.profit_ctr AS 'profit_ctr',
					-- Auditoría y fechas
					P.id_usuario_actualizar AS 'id_usuario_actualizar',
					P.fecha_creacion AS 'fecha_creacion',
					P.fecha_modificacion AS 'fecha_modificacion',
					P.fecha_baja AS 'fecha_baja',
					P.fecha_referencia AS 'fecha_referencia',
					P.fecha_contabilizacion AS 'fecha_contabilizacion',
					TA.ID_TIPO_ARTICULO AS id_tipo_articulo,
					TA.descripcion AS tipo_articulo,
					P.valor_residual AS 'valor_residual',
					P.vida_util AS 'vida_util',
					P.es_it AS 'es_it'
					
				FROM
					ac_articulos P
					LEFT JOIN ac_localizacion L ON P.id_localizacion = L.ID_LOCALIZACION
					LEFT JOIN th_personas PE ON P.th_per_id = PE.th_per_id
					LEFT JOIN ac_marcas M ON P.id_marca = M.ID_MARCA
					LEFT JOIN ac_estado E ON P.id_estado = E.ID_ESTADO
					LEFT JOIN ac_genero G ON P.id_genero = G.ID_GENERO
					LEFT JOIN ac_colores C ON P.id_color = C.ID_COLORES
					LEFT JOIN ac_proyecto PR ON P.id_proyecto = PR.ID_PROYECTO
					LEFT JOIN ac_familias F ON P.id_familia = F.id_familia
					LEFT JOIN ac_familias SF ON P.id_subfamilia = SF.id_familia
					LEFT JOIN ac_clase_movimiento CM ON P.id_clase_movimiento = CM.ID_MOVIMIENTO
					LEFT JOIN ac_cat_tipo_articulo TA ON P.id_tipo_articulo = TA.id_tipo_articulo
					LEFT JOIN ac_cat_unidad_medida UM ON P.id_unidad_medida = UM.ac_id_unidad
				WHERE 
					P.id_articulo = $id;";

		return $this->db->datos($sql);
	}

	function cargar_datos_vista_publica($id, $id_empresa)
	{
		$id = intval($id);

		$datos_sql_terceros = "SELECT
					P.id_articulo AS 'id_A',
					P.tag_unique AS 'rfid',
					P.tag_serie AS 'tag_s',
					P.tag_antiguo AS 'ant',
					P.subnumero AS 'subnum',
					
					P.descripcion AS 'nom',
					P.descripcion_2 AS 'des',
					P.caracteristica AS 'carac',
					P.observaciones AS 'obs',
					P.modelo AS 'mod',
					P.serie AS 'ser',
					
					P.cantidad AS 'cant',
					P.precio AS 'prec',
					P.imagen AS 'imagen',
					P.kit AS 'es_kit',
					P.maximo AS 'max',
					P.minimo AS 'min',
					
					P.id_localizacion AS 'id_loc',
					L.DENOMINACION AS 'loc_nom',
					L.EMPLAZAMIENTO AS 'c_loc',
					
					P.id_marca AS 'id_mar',
					M.DESCRIPCION AS 'marca',
					M.CODIGO AS 'c_mar',
					P.id_estado AS 'id_est',
					E.CODIGO AS 'c_est',
					E.DESCRIPCION AS 'estado',
					P.id_genero AS 'id_gen',
					G.DESCRIPCION AS 'genero',
					G.CODIGO AS 'c_gen',
					P.id_color AS 'id_col',
					C.DESCRIPCION AS 'color',
					C.CODIGO AS 'c_col',
					
					TA.DESCRIPCION AS 'tipo_articulo'
					
				FROM
					ac_articulos P
					LEFT JOIN ac_localizacion L ON P.id_localizacion = L.ID_LOCALIZACION
					LEFT JOIN th_personas PE ON P.th_per_id = PE.th_per_id
					LEFT JOIN ac_marcas M ON P.id_marca = M.ID_MARCA
					LEFT JOIN ac_estado E ON P.id_estado = E.ID_ESTADO
					LEFT JOIN ac_genero G ON P.id_genero = G.ID_GENERO
					LEFT JOIN ac_colores C ON P.id_color = C.ID_COLORES
					LEFT JOIN ac_proyecto PR ON P.id_proyecto = PR.ID_PROYECTO
					LEFT JOIN ac_familias F ON P.id_familia = F.id_familia
					LEFT JOIN ac_familias SF ON P.id_subfamilia = SF.id_familia
					LEFT JOIN ac_clase_movimiento CM ON P.id_clase_movimiento = CM.ID_MOVIMIENTO
					LEFT JOIN ac_cat_tipo_articulo TA ON P.id_tipo_articulo = TA.ID_TIPO_ARTICULO
					LEFT JOIN ac_cat_unidad_medida UM ON P.id_unidad_medida = UM.ac_id_unidad
				WHERE 
					P.id_articulo = $id;";

		$this->codigos_globales = new codigos_globales();

		$sql_publica = $this->codigos_globales->datos_empresa_publica($id_empresa, $datos_sql_terceros);

		$database = $sql_publica['empresa']['Base_datos'];
		$ruta_img_relativa = $sql_publica['empresa']['ruta_img_relativa'];

		$data = $sql_publica['datos'];
		$data[0]['ruta_imagen'] = $ruta_img_relativa . "emp=$database&dir=activos&nombre=" .  $data[0]['imagen'];

		return $data;
	}

	function buscar_plantilla_masiva($idAsset = false)
	{
		$sql = "SELECT * FROM ac_articulos WHERE 1 = 1 ";
		if ($idAsset) {
			$sql .= " AND ID_ASSET = '" . $idAsset . " '";
		}
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function buscar_asset($tag = false, $ant = false, $rfid = false)
	{
		$sql = "SELECT * FROM ac_asset WHERE 1=1 ";
		if ($tag) {
			$sql .= " AND TAG_SERIE ='" . $tag . "' ";
		}
		if ($ant) {
			$sql .= " AND TAG_ANT = '" . $ant . "' ";
		}

		if ($rfid) {
			$sql .= " AND TAG_UNIQUE = '" . $rfid . "'";
		}
		// print_r($sql);
		return $this->db->datos($sql);
	}

	function cargar_tarjeta($id)
	{
		// $sql="SELECT * FROM ac_tarjeta_info WHERE id_articulo = '".$id."'";
		// $datos = $this->db->datos($sql);
		// return $datos;

		$sql = "SELECT * FROM ac_datos_patrimonial WHERE ARTICULO = '" . $id . "'";
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function movimientos($id, $desde = false, $hasta = false)
	{
		$sql = "SELECT obs_movimiento as 'ob',fecha_movimiento as 'fe',dato_anterior as 'dante',dato_nuevo as 'dnuevo',responsable,codigo_ant,codigo_nue  FROM ac_movimiento WHERE id_plantilla = '" . $id . "' ";
		if ($desde != false && $hasta != false) {
			$sql .= " AND CONVERT(DATE, fecha_movimiento) BETWEEN '" . $desde . "' AND '" . $hasta . "'";
		}
		$sql .= " ORDER BY id_movimiento desc";
		// print_r($sql);die();
		$datos = $this->db->datos($sql);
		return $datos;
	}

	function update_data($tabla, $datos, $where)
	{
		$datos = $this->db->update($tabla, $datos, $where);
		return $datos;
	}

	function img_guardar($name, $codigo)
	{
		$tabla = 'ac_articulos';
		$datos[0]['campo'] = 'IMAGEN';
		$datos[0]['dato'] = $name;

		$where[0]['campo'] = 'id_articulo';
		$where[0]['dato'] = $codigo;
		$datos = $this->db->update($tabla, $datos, $where);
		if ($datos == 1) {
			return 1;
		} else {
			return -1;
		}
	}

	function navegacion($query, $loc, $cus, $pag = false, $whereid = false, $exacto = false, $asset = false)
	{
		$sql = "SELECT id_articulo as 'id',A.TAG_SERIE as 'tag',A.ID_ASSET,DESCRIPT as 'nom',MODELO as 'modelo',SERIE as 'serie',L.DENOMINACION as 'localizacion',PE.PERSON_NOM as 'custodio',M.DESCRIPCION as 'marca',E.DESCRIPCION as 'estado',G.DESCRIPCION as 'genero',C.DESCRIPCION as 'color',IMAGEN,OBSERVACION,FECHA_INV_DATE as 'fecha_in' FROM ac_articulos P
			LEFT JOIN ac_asset A ON P.ID_ASSET = A.ID_ASSET
			LEFT JOIN ac_localizacion L ON P.LOCATION = L.ID_LOCATION
			LEFT JOIN th_personas PE ON P.PERSON_NO = PE.ID_PERSON
			LEFT JOIN ac_marcas M ON P.EVALGROUP1 = M.ID_MARCA
			LEFT JOIN ac_estado E ON P.EVALGROUP2 = E.ID_ESTADO
			LEFT JOIN ac_genero G ON P.EVALGROUP3 = G.ID_GENERO
			LEFT JOIN ac_colores C ON P.EVALGROUP4 = C.ID_COLORES
			WHERE ";
		if ($exacto) {
			if ($asset) {
				$sql .= " A.TAG_SERIE LIKE '%" . $query . "'";
			} else {
				$sql .= " P.ORIG_ASSET LIKE '%" . $query . "'";
			}
		} else {
			$sql .= " A.TAG_SERIE +' '+P.DESCRIPT+' '+P.ORIG_ASSET LIKE '%" . $query . "%'";
		}

		if ($loc != '') {
			$sql .= " AND P.LOCATION = '" . $loc . "' ";
		}
		if ($cus != '') {
			$sql .= " AND PE.ID_PERSON = '" . $cus . "' ";
		}
		if ($whereid) {
			$sql .= '  AND id_articulo = ' . $whereid . ' ';
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
}
