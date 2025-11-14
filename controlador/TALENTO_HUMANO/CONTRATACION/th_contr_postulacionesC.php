<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/CONTRATACION/th_contr_postulacionesM.php');
require_once('../../../modelo/TALENTO_HUMANO/POSTULANTES/th_postulantesM.php');
require_once('../../../modelo/TALENTO_HUMANO/th_personasM.php');

$controlador = new th_contr_postulacionesC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['listar_plaza_postulados'])) {
    echo json_encode($controlador->listar_plaza_postulados($_POST['id'] ?? ''));
}

if (isset($_GET['insertar_editar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['buscar'])) {
    $query = '';

    if (isset($_GET['q'])) {
        $query = $_GET['q'];
    }

    $parametros = array(
        'query' => $query,
    );

    echo json_encode($controlador->buscar($parametros));
}



class th_contr_postulacionesC
{
private $modelo;

function __construct()
{
$this->modelo = new th_contr_postulacionesM();
}

function listar($id = '')
{

if($id == ''){

$datos = $this->modelo->listar_postulaciones();

}else{
$datos = $this->modelo->where('th_posu_id',$id)->where('th_posu_estado', 1)->listar();
}


return $datos;

}
function listar_plaza_postulados($id = '')
{
$datos = $this->modelo->listar_postulaciones_por_plaza($id);
return $datos;

}

function insertar_editar($parametros)
{
// Helpers
$toDateTime = function ($val) {
if ($val === null || $val === '') return null;
$val = str_replace('T', ' ', $val);
if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/', $val)) {
$val .= ':00';
}
$ts = strtotime($val);
return $ts ? date('Y-m-d H:i:s', $ts) : null;
};

$toInt = function ($v) {
if ($v === '' || $v === null || $v === 0 || $v === '0') return null;
return (int)$v;
};

$toFloat = function ($v) {
if ($v === '' || $v === null) return null;
return (float)$v;
};

// Campos comunes
$th_pla_id = $toInt($parametros['th_pla_id'] ?? null);
$tipo_postulante = $parametros['ddl_tipo_postulante'] ?? 'interno'; // 'interno' o 'externo'

// Si vienen varias personas seleccionadas -> insertar en lote
if (!empty($parametros['personas_seleccionadas']) && is_array($parametros['personas_seleccionadas'])) {
$insertados = 0;
foreach ($parametros['personas_seleccionadas'] as $raw_id) {
// normalizar id
$id = $toInt($raw_id);

// crear datos base para insertar
$datos = array(
array('campo' => 'th_pla_id', 'dato' => $th_pla_id),
// uno de los dos campos será NULL según el tipo
array('campo' => 'th_persona_id', 'dato' => ($tipo_postulante === 'interno') ? $id : null),
array('campo' => 'th_postulante_id', 'dato' => ($tipo_postulante === 'externo') ? $id : null),
array('campo' => 'th_posu_fuente', 'dato' => null), // opcional: ajustar si traes datos
array('campo' => 'th_posu_curriculum_url', 'dato' => null),
array('campo' => 'th_posu_score', 'dato' => null),
array('campo' => 'th_posu_prioridad', 'dato' => null),
array('campo' => 'th_posu_observaciones', 'dato' => null),
array('campo' => 'th_posu_fecha_creacion', 'dato' => date('Y-m-d H:i:s')),
array('campo' => 'th_posu_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
);

// comprobar duplicado: buscamos por th_pla_id y el campo correspondiente (persona o postulante)
if ($tipo_postulante === 'interno') {
$contar_personas = count($this->modelo->where('th_persona_id', $id)->where('th_pla_id',
$th_pla_id)->where('th_posu_estado', 1)->listar());

} else {
$contar_personas = count($this->modelo->where('th_postulante_id', $id)->where('th_pla_id',
$th_pla_id)->where('th_posu_estado', 1)->listar());
}

if ($contar_personas == 0) {
// insertar y contar
$newId = $this->modelo->insertar_id($datos);
if ($newId) $insertados++;
}

// reset del modelo entre iteraciones (si tu ORM lo requiere)
$this->modelo->reset();
}

if ($insertados > 0) return 1; // al menos uno insertado
return -2; // ninguno insertado (ej. todos duplicados)
}

// Caso normal: editar o insertar único (como ya tenías)
// Obtener los IDs separados y convertir 0 a NULL
$th_persona_id = $toInt($parametros['postulante_per_id'] ?? null);
$th_postulante_id = $toInt($parametros['postulante_pos_id'] ?? null);

// Preparar array de datos para registro único
$datos = array(
array('campo' => 'th_pla_id', 'dato' => $toInt($parametros['th_pla_id'] ?? $th_pla_id)),
array('campo' => 'th_persona_id', 'dato' => $th_persona_id),
array('campo' => 'th_postulante_id', 'dato' => $th_postulante_id),
array('campo' => 'th_posu_fecha', 'dato' => $toDateTime($parametros['th_posu_fecha'] ?? null)),
array('campo' => 'th_posu_estado_descrip', 'dato' => $parametros['th_posu_estado'] ?? null),
array('campo' => 'th_posu_fuente', 'dato' => $parametros['th_posu_fuente'] ?? null),
array('campo' => 'th_posu_curriculum_url', 'dato' => $parametros['th_posu_curriculum_url'] ?? null),
array('campo' => 'th_posu_score', 'dato' => $toFloat($parametros['th_posu_score'] ?? null)),
array('campo' => 'th_posu_prioridad', 'dato' => $parametros['th_posu_prioridad'] ?? null),
array('campo' => 'th_posu_observaciones', 'dato' => $parametros['th_posu_observaciones'] ?? null),
array('campo' => 'th_posu_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
);

// Inserción o edición única
if (empty($parametros['_id'])) {
$datos[] = array('campo' => 'th_posu_fecha_creacion', 'dato' => date('Y-m-d H:i:s'));
$id = $this->modelo->insertar_id($datos);
return ($id) ? 1 : 0;
} else {
$where[0]['campo'] = 'th_posu_id';
$where[0]['dato'] = $parametros['_id'];
$res = $this->modelo->editar($datos, $where);
return $res;
}
}


function eliminar($id)
{
$datos = array(
array('campo' => 'th_posu_estado', 'dato' => 0),
array('campo' => 'th_posu_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
);

$where[0]['campo'] = 'th_posu_id';
$where[0]['dato'] = $id;

$result = $this->modelo->editar($datos, $where);
return $result;
}
//Para usar en select2
function buscar($parametros)
{



}
}