<?php

require_once(dirname(__DIR__, 2) . '/modelo/ACTIVOS_FIJOS/reportesM.php');
require_once(dirname(__DIR__, 2) . '/funciones/funciones.php');

/**
 * 
 **/

$controlador =  new reportes();

if (isset($_GET['tipo_reporte'])) {
	echo json_encode($controlador->tipo_reporte());
}

if (isset($_GET['crear_reporte'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->crear_reporte($parametros));
}

if (isset($_GET['datos_reporte'])) {
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->datos_reporte($parametros));
}

if (isset($_GET['guardar_campos'])) {
	$parametros = $_POST;
	echo json_encode($controlador->guardar_campos($parametros));
}

if (isset($_GET['detalle_reporte'])) {
	$parametros = $_POST;
	// print_r($parametros);die();
	echo json_encode($controlador->detalle_reporte($parametros));
}

if (isset($_GET['filtro_reporte'])) {
	$parametros = $_POST;
	// print_r($parametros);die();
	echo json_encode($controlador->filtro_reporte($parametros));
}

if (isset($_GET['lista_reportes'])) {
	// print_r($parametros);die();
	echo json_encode($controlador->lista_reportes());
}

if (isset($_GET['informes_baja'])) {
	// print_r($parametros);die();
	echo json_encode($controlador->informes_baja());
}

if (isset($_GET['informes_terceros'])) {
	// print_r($parametros);die();
	echo json_encode($controlador->informes_terceros());
}

if (isset($_GET['informes_patrimoniales'])) {
	// print_r($parametros);die();
	echo json_encode($controlador->informes_patrimoniales());
}

if (isset($_GET['informes_activos'])) {
	// print_r($parametros);die();
	echo json_encode($controlador->informes_activos());
}

if (isset($_GET['eliminar_reporte'])) {

	echo json_encode($controlador->eliminar_reporte($_POST));
}



class reportes
{
	private $modelo;
	private $funciones;

	function __construct()
	{
		$this->modelo = new reportesM();
		$this->funciones = new funciones();
	}

	function tipo_reporte()
	{
		return $this->modelo->tipo_reporte();
	}

	function crear_reporte($parametros)
	{
		$reg = $this->modelo->buscar_reporte($parametros['tipo'], $parametros['nombre']);
		if (count($reg) == 0) {
			$datos[0]['campo'] = 'NOMBRE_REPORTE';
			$datos[0]['dato'] = $parametros['nombre'];
			$datos[1]['campo'] = 'TIPO_REPORTE';
			$datos[1]['dato'] = $parametros['tipo'];
			// $datos[1]['tipo'] = 'string'; 
			$datos[2]['campo'] = 'DETALLE';
			$datos[2]['dato'] = $parametros['detalle'];

			$ing = $this->modelo->add('ac_reporte', $datos);
			// print_r($ing);die();
			$reg = $this->modelo->buscar_reporte($parametros['tipo'], $parametros['nombre']);
			return array('respuesta' => $ing, 'id' => $reg[0]['ID_REPORTE']);
		} else {
			return array('respuesta' => -2, 'id' => $reg[0]['ID_REPORTE']);;
		}
	}

	function datos_reporte($parametros)
	{
		$id = $parametros['id'];
		$datos = $this->modelo->datos_reporte($id);

		$tablas = $datos[0]['TABLAS_ASOCIADAS'];
		$principal = $datos[0]['TABLA_PRINCIPAL'];
		$titulo =  $datos[0]['NOMBRE_REPORTE'];
		$descripcion =  $datos[0]['DETALLE'];
		$tablas = explode(',', $tablas);
		$div = '';

		if ($principal != '') {
			$div .= '<div class="card border-top border-0 border-4 border-danger">
					<div class="card-body p-3">
						<div class="card-title d-flex align-items-center">
							<div><i class="bx bxs-table me-1 font-22 text-danger"></i>
							</div>
							<h6 class="mb-0 text-danger">' . $this->funciones->equivalente($principal) . '</h6>
						</div>
						<div class="row">';
			$campo = $this->modelo->campos_tabla($principal);
			// print_r($campo);die();
			// imprime los campos en un check
			foreach ($campo as $key1 => $value1) {
				//la mayoria de lso campos que son primary y foreing key son de tipo int asi que no los colocamos
				if ($value1['tipo'] != 'int') {
					$div .= '<div class="col-sm-3">
				 		 <label title="' . $value1['campo'] . '"><input type="checkbox" id="' . $principal . '-' . $value1['campo'] . '" name="' . $principal . '-' . $value1['campo'] . '"> ' . $this->funciones->equivalente($value1['campo']) . '</label>
					</div>';
				}
			}
			$div .= "</div></div></div>";
		}

		//crea la seccion segun la tabla ASOCIADAS

		// print_r($tablas);die();
		foreach ($tablas as $key => $value) {
			if ($value != '') {
				$div .= '<div class="card border-top border-0 border-4 border-danger">
						<div class="card-body p-3">
							<div class="card-title d-flex align-items-center">
								<div><i class="bx bxs-table me-1 font-22 text-danger"></i>
								</div>
								<h6 class="mb-0 text-danger">' . $this->funciones->equivalente($value) . '</h6>
							</div>
							<div class="row">';
				$campo = $this->modelo->campos_tabla($value);
				// print_r($campo);die();
				// imprime los campos en un check
				foreach ($campo as $key1 => $value1) {
					//la mayoria de lso campos que son primary y foreing key son de tipo int asi que no los colocamos
					if ($value1['tipo'] != 'int') {
						$div .= '<div class="col-sm-3">
					 		 <label title="' . $value1['campo'] . '"><input type="checkbox" id="' . $value . '-' . $value1['campo'] . '" name="' . $value . '-' . $value1['campo'] . '"> ' . $this->funciones->equivalente($value1['campo']) . '</label>
						</div>';
					}
				}
				$div .= "</div></div></div>";
			}
		}

		return array('div' => $div, 'campos' => $datos[0]['CAMPOS'], 'titulo' => $titulo, 'detalle' => $descripcion);
	}

	function guardar_campos($parametros)
	{
		if (isset($parametros['id']) && $parametros['id'] != '') {
			$campos = '';
			$temTabla = '';
			$arraygrupos = array();
			$selected = '';

			$datos = $this->modelo->datos_reporte($parametros['id']);
			$principal = $datos[0]['TABLA_PRINCIPAL'];
			$detalle = $parametros['txt_detalle'];
			$titulo_rep = $parametros['txt_titulo'];

			unset($parametros['txt_detalle']);
			unset($parametros['txt_titulo']);
			$campos_todos = array();
			foreach ($parametros as $key => $value) {
				if ($key != 'id') {
					$selected .= $key . ',';
					$datos = explode('-', $key);
					if ($temTabla != $datos[0]) {
						$temTabla = $datos[0];
						$arraygrupos[$temTabla] = array($datos[1]);
					} else {
						array_push($arraygrupos[$temTabla], $datos[1]);
					}
					array_push($campos_todos, $datos[1]);
				}
			}

			//analisa cual de los datos esta repetido en nombre
			$campos2 = array_unique($campos_todos);
			$v_comunes1 = array_diff_assoc($campos_todos, $campos2);
			$v_comunes2 = array_unique($v_comunes1);
			$repetidos = implode(',', $v_comunes2);
			$repetidos = explode(',', $repetidos);
			// fin de analizar datos repetidos

			//GENERA EL HTML DE LOS FILTROS
			$fil = array();
			$filtros_html = '';
			foreach ($arraygrupos as $key => $value) {
				$tabla = $key;
				foreach ($value as $key2 => $value2) {
					$campo = $this->modelo->campos_tabla($tabla, $value2);
					// print_r($campo[0]['tipo']);
					$titulo = '';
					foreach ($repetidos as $key3 => $value3) {
						if ($value3 == $value2) {
							$titulo = $tabla;
						}
					}

					if ($campo[0]['tipo'] == 'date' || $campo[0]['tipo'] == 'datetime') {
						$filtros_html .= '<div class="col-sm-3">
						<b style="font-size: 12px;">' . $this->funciones->equivalente($value2) . '</b>
							<div class="row">
								<div class="col-sm-6">
									<input type="date" class="form-control form-control-sm" id="txt_' . $value2 . '-' . $titulo . '" name="txt_' . $value2 . '-' . $titulo . '[]">
								</div>
								<div class="col-sm-6">
									<input type="date" class="form-control form-control-sm" id="txt_' . $value2 . '-' . $titulo . '" name="txt_' . $value2 . '-' . $titulo . '[]">
								</div>
							</div>
						</div>';
					} else if ($campo[0]['tipo'] == 'bit') {
						$filtros_html .= '<div class="col-sm-2">
						<br>
						<label>
							<input type ="checkbox" id="txt_' . $value2 . '-' . $titulo . '" name="txt_' . $value2 . '-' . $titulo . '">
							<b style="font-size: 12px;">' . $value2 . '</b></label>
						</div>';
					} else {
						$filtros_html .= '<div class="col-sm-3">
						<b style="font-size: 12px;">' . $value2 . ' ' . $titulo . '</b>
							<input class="form-control form-control-sm" id="txt_' . $value2 . '-' . $titulo . '" name="txt_' . $value2 . '-' . $titulo . '">
						</div>';
					}
				}
			}

			$filtros_html .= '<div class="col-sm-12 text-end"><button type="button" class="btn btn-danger btn-sm" onclick="detalle_reporte(' . $parametros['id'] . ')">Buscar</button></div>';



			//fin generacion de html


			// print_r('ddd');die();


			//generamos el sql para guardar
			$from = ' FROM ' . $principal . ' A ';
			$join = '';
			$campos = 'SELECT ';
			$iden = 'A';
			$cam = '';
			foreach ($arraygrupos as $key => $value) {
				if ($cam != $key) {
					$cam = $key;

					$iden = substr($cam, 0, 4);
					// $iden++;
				}
				$join .= $this->funciones->join_tabla($principal, 'A', $cam, $iden);
				foreach ($value as $key2 => $value2) {
					if ($cam != $principal) {
						$titulo = '';
						foreach ($repetidos as $key3 => $value3) {
							if ($value3 == $value2) {
								$titulo = $cam;
							}
						}
						$campos .= $iden . '.' . $value2 . ' as "' . $value2 . ' ' . $titulo . '" ,';
					} else {
						$campos .= 'A.' . $value2 . ',';
					}
				}
			}

			$pk =  $this->modelo->PK($principal);

			$campos = substr($campos, 0, -1);

			$sql = $campos . $from . ' ' . $join . ' ORDER BY ' . $pk[0]['PRIMARYKEYCOLUMN'] . ' DESC';

			//guardamos los campos;
			$datosR[0]['campo'] = 'CAMPOS';
			$datosR[0]['dato'] = substr($selected, 0, -1);
			$datosR[1]['campo'] = 'SQL';
			$datosR[1]['dato'] = $sql;
			$datosR[2]['campo'] = 'FILTROS_HTML';
			$datosR[2]['dato'] = $filtros_html;
			$datosR[3]['campo'] = 'DETALLE';
			$datosR[3]['dato'] = $detalle;
			$datosR[4]['campo'] = 'NOMBRE_REPORTE';
			$datosR[4]['dato'] = $titulo_rep;


			$where[0]['campo'] = 'ID_REPORTE';
			$where[0]['dato'] = $parametros['id'];

			return $this->modelo->update('ac_reporte', $datosR, $where);
		}
	}

	function detalle_reporte($parametros)
	{
		$filtro_para = false;
		// print_r($parametros);die();
		if (isset($parametros['id']) && $parametros['id'] != '') {
			$datos = $this->modelo->datos_reporte($parametros['id']);
			$sql = $datos[0]['SQL'];
			$detalle = $datos[0]['DETALLE'];
			$nombre =  $datos[0]['NOMBRE_REPORTE'];

			$sql = $this->funciones->generar_sql($parametros, $sql, $para_vista = true);
			// print_r($sql);die();
			$campos = $datos[0]['CAMPOS'];
			$campos = explode(',', $campos);
			$cabe = array();
			// $tbl = ' <thead><tr>';                    

			foreach ($campos as $key => $value) {
				$cam = explode('-', $value);
				$cabe[] = $cam[1];
				$c[] = array('data' => $cam[1]);
				// $tbl.='<th>'.$cam[1].'</th>';
			}
			$tbl = '';
			// print_r($sql);die();
			$data = $this->modelo->realizar_consulta($sql['sql_normal']);
			$total = $this->modelo->realizar_consulta($sql['sql_total']);
			$total = $total[0]['total'];

			// print_r($data);
			// print_r($total);die();

			//-----------------generar paginacion--------------

			$pag2 = explode('-', $parametros['pag']);
			$pag = explode('-', $parametros['pag2']);
			// var pag2 = $('#txt_pag').val().split('-');

			$pagi = '';
			if ($total > $pag[1]) {
				$pagi .= '<li class="paginate_button page-item" onclick="guias_pag(\'-\')"><a class="page-link" href="#"> << </a></li>';

				$num = $total / $pag[1];
				if ($num > 10) {
					if ($pag2[0] / $pag[1] < 9) {
						for ($i = 1; $i < 11; $i++) {
							$pos = $pag[1]; //pag[1]*i;
							$ini = $pag[0] + ($pag[1] * $i) - $pag[1];
							$pa = $ini . '-' . $pos;
							if ($parametros['pag'] == $pa) {
								$pagi .= '<li class="paginate_button page-item active" onclick="paginacion(\'' . $pa . '\')"><a class="page-link" href="#">' . $i . '</a></li>';
							} else {
								$pagi .= '<li class="paginate_button page-item" onclick="paginacion(\'' . $pa . '\')"><a class="page-link" href="#">' . $i . '</a></li>';
							}
						}
					} else {
						$pagi .= '<li class="paginate_button page-item" onclick="paginacion(\'0-25\')"><a class="page-link" href="#">1</a></li>';
						for ($i = $pag2[0] / 25 + 1; $i < ($pag2[0] / 25) + 10; $i++) {
							$pos = $pag[1]; //pag[1]*i;
							$ini = $pag[0] + ($pag[1] * $i) - $pag[1];
							$pa  = $ini . '-' . $pos;
							if ($parametros['pag'] == $pa) {
								$pagi .= '<li class="paginate_button page-item active" onclick="paginacion(\'' . $pa . '\')"><a class="page-link" href="#">' . $i . '</a></li>';
							} else {
								$pagi .= '<li class="paginate_button page-item" onclick="paginacion(\'' . $pa . '\')"><a class="page-link" href="#">' . $i . '</a></li>';
							}
						}
					}
					$pagi .= '<li class="paginate_button page-item" onclick="guias_pag(\'+\')"><a class="page-link" href="#"> >> </a></li>';
				} else {

					for ($i = 1; $i < $num + 1; $i++) {
						$pos = $pag[1]; //pag[1]*i;
						$ini = $pag[0] + ($pag[1] * $i) - $pag[1];
						$pa = $ini . '-' . $pos;
						if ($parametros['pag'] == $pa) {
							$pagi .= '<li class="paginate_button page-item active"  onclick="paginacion(\'' . $pa . '\')"><a class="page-link" href="#">' . $i . '</a></li>';
						} else {
							$pagi .= '<li class="paginate_button page-item"  onclick="paginacion(\'' . $pa . '\')"><a class="page-link" href="#">' . $i . '</a></li>';
						}
					}
				}
			}
			//-------------fin generar paginacion--------------

			// print_r($pagi);die();


			//--------------lineas de detalle--------------------
			foreach ($data as $key => $value) {

				$arraysIndividuales = array_map(function ($elemento) {
					return array($elemento);
				}, $value);

				// print_r($arraysIndividuales);die();

				$tbl .= '<tr>';
				foreach ($arraysIndividuales as $key2 => $value2) {
					$value2 = $value2[0];
					// print_r($value2);die();
					if (!is_object($value2)) {
						$tbl .= '<td>' . $value2 . '</td>';
					} else {
						// print_r($value2);die();
						$tbl .= '<td>' . $value2->format('Y-m-d') . '</td>';
					}
				}
				$tbl .= '</tr>';
			}
			//---------------fin linea de detalles--------------------

			return array('body' => $tbl, 'head' => $c, 'paginacion' => $pagi, 'detalle' => $detalle, 'nombre' => $nombre);
			// print_r($tbl);die();
		}
	}

	function filtro_reporte($parametros)
	{
		$filtro_para = false;
		// print_r($parametros);die();
		if (isset($parametros['id']) && $parametros['id'] != '') {
			$datos = $this->modelo->datos_reporte($parametros['id']);
			$sql = $datos[0]['SQL'];
			$filtros = $datos[0]['FILTROS_HTML'];

			return $filtros;
		}
	}

	function lista_reportes()
	{
		$datos = $this->modelo->buscar_reporte($tipo = false, $nombre = false);
		$html = '';
		foreach ($datos as $key => $value) {
			$html .= '<div class="col">
					<div class="card">
						<div class="card-body">
							<div>
								<h5 class="card-title">' . strtoupper($value['NOMBRE_REPORTE']) . '</h5>
							</div>
							<p class="card-text">Informe del total de activos registrados.</p>
							<div class="alert border-0 border-start border-5 border-warning alert-dismissible fade show py-2">
								<div class="d-flex align-items-center">
									<div class="font-35 text-warning"><i class="bx bx-info-circle"></i>
									</div>
									<div class="ms-3">
										<h6 class="mb-0 text-warning">Advertencia</h6>
										<div>Este proceso podria durar varios minutos</div>
									</div>
								</div>
							</div>
							<div class="col text-end">
										<div class="btn-group" role="group" aria-label="Basic example">
											<a href="inicio.php?acc=reporte_detalle&id=' . $value['ID_REPORTE'] . '" id="" class="btn btn-outline-dark btn-sm"><i class="bx bx-show-alt"></i></a>
										<a href="inicio.php?acc=nuevo_reporte&id=' . $value['ID_REPORTE'] . '" id="" class="btn btn-primary btn-sm" id=""><i class="bx bx-pencil"></i></a>
											<button type="button" id="btn_eliminar" class="btn btn-danger btn-sm" onclick="eliminar_reporte(' . $value['ID_REPORTE'] . ')"><i class="bx bx-trash"></i></button>
										</div>
							</div>
						</div>
					</div>
				</div>';
			// print_r($datos);die();
		}

		return $html;
	}

	function informes_baja()
	{
		$OP = '';
		$opciones = array();
		$reportes = $this->modelo->datos_reporte();
		foreach ($reportes as $key => $value) {
			$sql = strtoupper($value['CAMPOS']);
			if (strpos($sql, 'BAJAS') !== false) {
				$OP .= ' <li class="dropdown-item">				
              	<a class="dropdown-item" href="#" id="imprimir_excel_sap" style="padding-left:0px" >' . $value['NOMBRE_REPORTE'] . '  </a>
              	<div class="text-end">
                
                   <button class="btn btn-sm btn btn-outline-primary" onclick="ver_informe_pdf(' . $value['ID_REPORTE'] . ')">
                     <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path fill="currentColor" d="M17.924 7.154h-.514l.027-1.89a.464.464 0 0 0-.12-.298L12.901.134A.393.393 0 0 0 12.618 0h-9.24a.8.8 0 0 0-.787.784v6.37h-.515c-.285 0-.56.118-.76.328A1.14 1.14 0 0 0 1 8.275v5.83c0 .618.482 1.12 1.076 1.12h.515v3.99A.8.8 0 0 0 3.38 20h13.278c.415 0 .78-.352.78-.784v-3.99h.487c.594 0 1.076-.503 1.076-1.122v-5.83c0-.296-.113-.582-.315-.792a1.054 1.054 0 0 0-.76-.328ZM3.95 1.378h6.956v4.577a.4.4 0 0 0 .11.277a.37.37 0 0 0 .267.115h4.759v.807H3.95V1.378Zm0 17.244v-3.397h12.092v3.397H3.95ZM12.291 1.52l.385.434l2.58 2.853l.143.173h-2.637c-.2 0-.325-.033-.378-.1c-.053-.065-.084-.17-.093-.313V1.52ZM3 14.232v-6h1.918c.726 0 1.2.03 1.42.09c.34.09.624.286.853.588c.228.301.343.69.343 1.168c0 .368-.066.678-.198.93c-.132.25-.3.447-.503.59a1.72 1.72 0 0 1-.62.285c-.285.057-.698.086-1.239.086h-.779v2.263H3Zm1.195-4.985v1.703h.654c.471 0 .786-.032.945-.094a.786.786 0 0 0 .508-.762a.781.781 0 0 0-.19-.54a.823.823 0 0 0-.48-.266c-.142-.027-.429-.04-.86-.04h-.577Zm4.04-1.015h2.184c.493 0 .868.038 1.127.115c.347.103.644.288.892.552c.247.265.436.589.565.972c.13.384.194.856.194 1.418c0 .494-.06.92-.182 1.277c-.148.437-.36.79-.634 1.06c-.207.205-.487.365-.84.48c-.263.084-.616.126-1.057.126H8.235v-6ZM9.43 9.247v3.974h.892c.334 0 .575-.019.723-.057c.194-.05.355-.132.482-.25c.128-.117.233-.31.313-.579c.081-.269.121-.635.121-1.099c0-.464-.04-.82-.12-1.068a1.377 1.377 0 0 0-.34-.581a1.132 1.132 0 0 0-.553-.283c-.167-.038-.494-.057-.98-.057H9.43Zm4.513 4.985v-6H18v1.015h-2.862v1.42h2.47v1.015h-2.47v2.55h-1.195Z"/></svg>
                   </button>
                   <button class="btn btn-sm btn btn-outline-primary" onclick="ver_informe_excel(' . $value['ID_REPORTE'] . ')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 512 512"><path fill="currentColor" d="M453.547 273.449H372.12v-40.714h81.427v40.714zm0 23.264H372.12v40.714h81.427v-40.714zm0-191.934H372.12v40.713h81.427V104.78zm0 63.978H372.12v40.713h81.427v-40.713zm0 191.934H372.12v40.714h81.427V360.69zm56.242 80.264c-2.326 12.098-16.867 12.388-26.58 12.796H302.326v52.345h-36.119L0 459.566V52.492L267.778 5.904h34.548v46.355h174.66c9.83.407 20.648-.291 29.197 5.583c5.991 8.608 5.41 19.543 5.817 29.43l-.233 302.791c-.29 16.925 1.57 34.2-1.978 50.892zm-296.51-91.256c-16.052-32.57-32.395-64.909-48.39-97.48c15.82-31.698 31.408-63.512 46.937-95.327c-13.203.64-26.406 1.454-39.55 2.385c-9.83 23.904-21.288 47.169-28.965 71.888c-7.154-23.323-16.634-45.774-25.3-68.515c-12.796.698-25.592 1.454-38.387 2.21c13.493 29.78 27.86 59.15 40.946 89.104c-15.413 29.081-29.837 58.57-44.785 87.825c12.737.523 25.475 1.047 38.212 1.221c9.074-23.148 20.357-45.424 28.267-69.038c7.096 25.359 19.135 48.798 29.023 73.051c14.017.99 27.976 1.862 41.993 2.676zM484.26 79.882H302.326v24.897h46.53v40.713h-46.53v23.265h46.53v40.713h-46.53v23.265h46.53v40.714h-46.53v23.264h46.53v40.714h-46.53v23.264h46.53v40.714h-46.53v26.897H484.26V79.882z"/></svg>
                   </button>
                   </div>
              </li>';
			}
		}
		return $OP;
	}

	function informes_terceros()
	{
		$OP = '';
		$opciones = array();
		$reportes = $this->modelo->datos_reporte();
		foreach ($reportes as $key => $value) {
			$sql = strtoupper($value['CAMPOS']);
			if (strpos($sql, 'TERCEROS') !== false) {
				$OP .= ' <li class="dropdown-item">				
              	<a class="dropdown-item" href="#" id="imprimir_excel_sap" style="padding-left:0px" >' . $value['NOMBRE_REPORTE'] . '  </a>
              	<div class="text-end">
                
                   <button class="btn btn-sm btn btn-outline-primary" onclick="ver_informe_pdf(' . $value['ID_REPORTE'] . ')">
                     <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path fill="currentColor" d="M17.924 7.154h-.514l.027-1.89a.464.464 0 0 0-.12-.298L12.901.134A.393.393 0 0 0 12.618 0h-9.24a.8.8 0 0 0-.787.784v6.37h-.515c-.285 0-.56.118-.76.328A1.14 1.14 0 0 0 1 8.275v5.83c0 .618.482 1.12 1.076 1.12h.515v3.99A.8.8 0 0 0 3.38 20h13.278c.415 0 .78-.352.78-.784v-3.99h.487c.594 0 1.076-.503 1.076-1.122v-5.83c0-.296-.113-.582-.315-.792a1.054 1.054 0 0 0-.76-.328ZM3.95 1.378h6.956v4.577a.4.4 0 0 0 .11.277a.37.37 0 0 0 .267.115h4.759v.807H3.95V1.378Zm0 17.244v-3.397h12.092v3.397H3.95ZM12.291 1.52l.385.434l2.58 2.853l.143.173h-2.637c-.2 0-.325-.033-.378-.1c-.053-.065-.084-.17-.093-.313V1.52ZM3 14.232v-6h1.918c.726 0 1.2.03 1.42.09c.34.09.624.286.853.588c.228.301.343.69.343 1.168c0 .368-.066.678-.198.93c-.132.25-.3.447-.503.59a1.72 1.72 0 0 1-.62.285c-.285.057-.698.086-1.239.086h-.779v2.263H3Zm1.195-4.985v1.703h.654c.471 0 .786-.032.945-.094a.786.786 0 0 0 .508-.762a.781.781 0 0 0-.19-.54a.823.823 0 0 0-.48-.266c-.142-.027-.429-.04-.86-.04h-.577Zm4.04-1.015h2.184c.493 0 .868.038 1.127.115c.347.103.644.288.892.552c.247.265.436.589.565.972c.13.384.194.856.194 1.418c0 .494-.06.92-.182 1.277c-.148.437-.36.79-.634 1.06c-.207.205-.487.365-.84.48c-.263.084-.616.126-1.057.126H8.235v-6ZM9.43 9.247v3.974h.892c.334 0 .575-.019.723-.057c.194-.05.355-.132.482-.25c.128-.117.233-.31.313-.579c.081-.269.121-.635.121-1.099c0-.464-.04-.82-.12-1.068a1.377 1.377 0 0 0-.34-.581a1.132 1.132 0 0 0-.553-.283c-.167-.038-.494-.057-.98-.057H9.43Zm4.513 4.985v-6H18v1.015h-2.862v1.42h2.47v1.015h-2.47v2.55h-1.195Z"/></svg>
                   </button>
                   <button class="btn btn-sm btn btn-outline-primary" onclick="ver_informe_excel(' . $value['ID_REPORTE'] . ')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 512 512"><path fill="currentColor" d="M453.547 273.449H372.12v-40.714h81.427v40.714zm0 23.264H372.12v40.714h81.427v-40.714zm0-191.934H372.12v40.713h81.427V104.78zm0 63.978H372.12v40.713h81.427v-40.713zm0 191.934H372.12v40.714h81.427V360.69zm56.242 80.264c-2.326 12.098-16.867 12.388-26.58 12.796H302.326v52.345h-36.119L0 459.566V52.492L267.778 5.904h34.548v46.355h174.66c9.83.407 20.648-.291 29.197 5.583c5.991 8.608 5.41 19.543 5.817 29.43l-.233 302.791c-.29 16.925 1.57 34.2-1.978 50.892zm-296.51-91.256c-16.052-32.57-32.395-64.909-48.39-97.48c15.82-31.698 31.408-63.512 46.937-95.327c-13.203.64-26.406 1.454-39.55 2.385c-9.83 23.904-21.288 47.169-28.965 71.888c-7.154-23.323-16.634-45.774-25.3-68.515c-12.796.698-25.592 1.454-38.387 2.21c13.493 29.78 27.86 59.15 40.946 89.104c-15.413 29.081-29.837 58.57-44.785 87.825c12.737.523 25.475 1.047 38.212 1.221c9.074-23.148 20.357-45.424 28.267-69.038c7.096 25.359 19.135 48.798 29.023 73.051c14.017.99 27.976 1.862 41.993 2.676zM484.26 79.882H302.326v24.897h46.53v40.713h-46.53v23.265h46.53v40.713h-46.53v23.265h46.53v40.714h-46.53v23.264h46.53v40.714h-46.53v23.264h46.53v40.714h-46.53v26.897H484.26V79.882z"/></svg>
                   </button>
                   </div>
              </li>';
			}
		}
		return $OP;
	}

	function informes_patrimoniales()
	{
		$OP = '';
		$opciones = array();
		$reportes = $this->modelo->datos_reporte();
		foreach ($reportes as $key => $value) {
			$sql = strtoupper($value['CAMPOS']);
			if (strpos($sql, 'PATRIMONIALES') !== false) {
				$OP .= ' <li class="dropdown-item">				
              	<a class="dropdown-item" href="#" id="imprimir_excel_sap" style="padding-left:0px" >' . $value['NOMBRE_REPORTE'] . '  </a>
              	<div class="text-end">
                
                   <button class="btn btn-sm btn btn-outline-primary" onclick="ver_informe_pdf(' . $value['ID_REPORTE'] . ')">
                     <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path fill="currentColor" d="M17.924 7.154h-.514l.027-1.89a.464.464 0 0 0-.12-.298L12.901.134A.393.393 0 0 0 12.618 0h-9.24a.8.8 0 0 0-.787.784v6.37h-.515c-.285 0-.56.118-.76.328A1.14 1.14 0 0 0 1 8.275v5.83c0 .618.482 1.12 1.076 1.12h.515v3.99A.8.8 0 0 0 3.38 20h13.278c.415 0 .78-.352.78-.784v-3.99h.487c.594 0 1.076-.503 1.076-1.122v-5.83c0-.296-.113-.582-.315-.792a1.054 1.054 0 0 0-.76-.328ZM3.95 1.378h6.956v4.577a.4.4 0 0 0 .11.277a.37.37 0 0 0 .267.115h4.759v.807H3.95V1.378Zm0 17.244v-3.397h12.092v3.397H3.95ZM12.291 1.52l.385.434l2.58 2.853l.143.173h-2.637c-.2 0-.325-.033-.378-.1c-.053-.065-.084-.17-.093-.313V1.52ZM3 14.232v-6h1.918c.726 0 1.2.03 1.42.09c.34.09.624.286.853.588c.228.301.343.69.343 1.168c0 .368-.066.678-.198.93c-.132.25-.3.447-.503.59a1.72 1.72 0 0 1-.62.285c-.285.057-.698.086-1.239.086h-.779v2.263H3Zm1.195-4.985v1.703h.654c.471 0 .786-.032.945-.094a.786.786 0 0 0 .508-.762a.781.781 0 0 0-.19-.54a.823.823 0 0 0-.48-.266c-.142-.027-.429-.04-.86-.04h-.577Zm4.04-1.015h2.184c.493 0 .868.038 1.127.115c.347.103.644.288.892.552c.247.265.436.589.565.972c.13.384.194.856.194 1.418c0 .494-.06.92-.182 1.277c-.148.437-.36.79-.634 1.06c-.207.205-.487.365-.84.48c-.263.084-.616.126-1.057.126H8.235v-6ZM9.43 9.247v3.974h.892c.334 0 .575-.019.723-.057c.194-.05.355-.132.482-.25c.128-.117.233-.31.313-.579c.081-.269.121-.635.121-1.099c0-.464-.04-.82-.12-1.068a1.377 1.377 0 0 0-.34-.581a1.132 1.132 0 0 0-.553-.283c-.167-.038-.494-.057-.98-.057H9.43Zm4.513 4.985v-6H18v1.015h-2.862v1.42h2.47v1.015h-2.47v2.55h-1.195Z"/></svg>
                   </button>
                   <button class="btn btn-sm btn btn-outline-primary" onclick="ver_informe_excel(' . $value['ID_REPORTE'] . ')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 512 512"><path fill="currentColor" d="M453.547 273.449H372.12v-40.714h81.427v40.714zm0 23.264H372.12v40.714h81.427v-40.714zm0-191.934H372.12v40.713h81.427V104.78zm0 63.978H372.12v40.713h81.427v-40.713zm0 191.934H372.12v40.714h81.427V360.69zm56.242 80.264c-2.326 12.098-16.867 12.388-26.58 12.796H302.326v52.345h-36.119L0 459.566V52.492L267.778 5.904h34.548v46.355h174.66c9.83.407 20.648-.291 29.197 5.583c5.991 8.608 5.41 19.543 5.817 29.43l-.233 302.791c-.29 16.925 1.57 34.2-1.978 50.892zm-296.51-91.256c-16.052-32.57-32.395-64.909-48.39-97.48c15.82-31.698 31.408-63.512 46.937-95.327c-13.203.64-26.406 1.454-39.55 2.385c-9.83 23.904-21.288 47.169-28.965 71.888c-7.154-23.323-16.634-45.774-25.3-68.515c-12.796.698-25.592 1.454-38.387 2.21c13.493 29.78 27.86 59.15 40.946 89.104c-15.413 29.081-29.837 58.57-44.785 87.825c12.737.523 25.475 1.047 38.212 1.221c9.074-23.148 20.357-45.424 28.267-69.038c7.096 25.359 19.135 48.798 29.023 73.051c14.017.99 27.976 1.862 41.993 2.676zM484.26 79.882H302.326v24.897h46.53v40.713h-46.53v23.265h46.53v40.713h-46.53v23.265h46.53v40.714h-46.53v23.264h46.53v40.714h-46.53v23.264h46.53v40.714h-46.53v26.897H484.26V79.882z"/></svg>
                   </button>
                   </div>
              </li>';
			}
		}
		return $OP;
	}

	function informes_activos()
	{
		$OP = '';
		$opciones = array();
		$reportes = $this->modelo->datos_reporte();

		$informes = array();
		$encontrado = 0;
		$diferente_a = array('BAJAS', 'TERCEROS', 'PATRIMONIALES');
		foreach ($reportes as $key => $value) {
			$sql = strtoupper($value['CAMPOS']);
			$encontrado = 0;
			foreach ($diferente_a as $key2 => $value2) {
				if (strpos($sql, $value2) !== false) {
					$encontrado = 1;
					break;
				}
			}
			if ($encontrado == 0) {
				if (strpos($sql, 'PLANTILLA_MASIVA') !== false) {
					$informes[] = $value;
				}
			}
		}

		foreach ($informes as $key => $value) {
			// print_r($value);die();
			$OP .= ' <li class="dropdown-item">				
              	<a class="dropdown-item" href="#" id="imprimir_excel_sap" style="padding-left:0px" >' . $value['NOMBRE_REPORTE'] . '  </a>
              	<div class="text-end">
                
                   <button class="btn btn-sm btn btn-outline-primary" onclick="ver_informe_pdf(' . $value['ID_REPORTE'] . ')">
                     <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path fill="currentColor" d="M17.924 7.154h-.514l.027-1.89a.464.464 0 0 0-.12-.298L12.901.134A.393.393 0 0 0 12.618 0h-9.24a.8.8 0 0 0-.787.784v6.37h-.515c-.285 0-.56.118-.76.328A1.14 1.14 0 0 0 1 8.275v5.83c0 .618.482 1.12 1.076 1.12h.515v3.99A.8.8 0 0 0 3.38 20h13.278c.415 0 .78-.352.78-.784v-3.99h.487c.594 0 1.076-.503 1.076-1.122v-5.83c0-.296-.113-.582-.315-.792a1.054 1.054 0 0 0-.76-.328ZM3.95 1.378h6.956v4.577a.4.4 0 0 0 .11.277a.37.37 0 0 0 .267.115h4.759v.807H3.95V1.378Zm0 17.244v-3.397h12.092v3.397H3.95ZM12.291 1.52l.385.434l2.58 2.853l.143.173h-2.637c-.2 0-.325-.033-.378-.1c-.053-.065-.084-.17-.093-.313V1.52ZM3 14.232v-6h1.918c.726 0 1.2.03 1.42.09c.34.09.624.286.853.588c.228.301.343.69.343 1.168c0 .368-.066.678-.198.93c-.132.25-.3.447-.503.59a1.72 1.72 0 0 1-.62.285c-.285.057-.698.086-1.239.086h-.779v2.263H3Zm1.195-4.985v1.703h.654c.471 0 .786-.032.945-.094a.786.786 0 0 0 .508-.762a.781.781 0 0 0-.19-.54a.823.823 0 0 0-.48-.266c-.142-.027-.429-.04-.86-.04h-.577Zm4.04-1.015h2.184c.493 0 .868.038 1.127.115c.347.103.644.288.892.552c.247.265.436.589.565.972c.13.384.194.856.194 1.418c0 .494-.06.92-.182 1.277c-.148.437-.36.79-.634 1.06c-.207.205-.487.365-.84.48c-.263.084-.616.126-1.057.126H8.235v-6ZM9.43 9.247v3.974h.892c.334 0 .575-.019.723-.057c.194-.05.355-.132.482-.25c.128-.117.233-.31.313-.579c.081-.269.121-.635.121-1.099c0-.464-.04-.82-.12-1.068a1.377 1.377 0 0 0-.34-.581a1.132 1.132 0 0 0-.553-.283c-.167-.038-.494-.057-.98-.057H9.43Zm4.513 4.985v-6H18v1.015h-2.862v1.42h2.47v1.015h-2.47v2.55h-1.195Z"/></svg>
                   </button>
                   <button class="btn btn-sm btn btn-outline-primary" onclick="ver_informe_excel(' . $value['ID_REPORTE'] . ')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 512 512"><path fill="currentColor" d="M453.547 273.449H372.12v-40.714h81.427v40.714zm0 23.264H372.12v40.714h81.427v-40.714zm0-191.934H372.12v40.713h81.427V104.78zm0 63.978H372.12v40.713h81.427v-40.713zm0 191.934H372.12v40.714h81.427V360.69zm56.242 80.264c-2.326 12.098-16.867 12.388-26.58 12.796H302.326v52.345h-36.119L0 459.566V52.492L267.778 5.904h34.548v46.355h174.66c9.83.407 20.648-.291 29.197 5.583c5.991 8.608 5.41 19.543 5.817 29.43l-.233 302.791c-.29 16.925 1.57 34.2-1.978 50.892zm-296.51-91.256c-16.052-32.57-32.395-64.909-48.39-97.48c15.82-31.698 31.408-63.512 46.937-95.327c-13.203.64-26.406 1.454-39.55 2.385c-9.83 23.904-21.288 47.169-28.965 71.888c-7.154-23.323-16.634-45.774-25.3-68.515c-12.796.698-25.592 1.454-38.387 2.21c13.493 29.78 27.86 59.15 40.946 89.104c-15.413 29.081-29.837 58.57-44.785 87.825c12.737.523 25.475 1.047 38.212 1.221c9.074-23.148 20.357-45.424 28.267-69.038c7.096 25.359 19.135 48.798 29.023 73.051c14.017.99 27.976 1.862 41.993 2.676zM484.26 79.882H302.326v24.897h46.53v40.713h-46.53v23.265h46.53v40.713h-46.53v23.265h46.53v40.714h-46.53v23.264h46.53v40.714h-46.53v23.264h46.53v40.714h-46.53v26.897H484.26V79.882z"/></svg>
                   </button>
                   </div>
              </li>';
		}
		return $OP;
	}

	function eliminar_reporte($parametros)
	{
		// print_r($id);die();
		return $this->modelo->eliminar_reportes($parametros['id']);
	}
}
