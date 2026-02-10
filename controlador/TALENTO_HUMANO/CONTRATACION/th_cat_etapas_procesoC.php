<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/CONTRATACION/th_cat_etapas_procesoM.php');

$controlador = new th_cat_etapas_procesoC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['organizar'])) {
    $ordenes_json = $_POST['ordenes'] ?? '';
    echo json_encode($controlador->organizar($ordenes_json));
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


class th_cat_etapas_procesoC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_cat_etapas_procesoM();
    }

    function listar($id = '')
    {
        if($id == ''){
            $datos = $this->modelo->where('estado',1)->listar();
        }else{
             $datos = $this->modelo->where('id',$id)->where('estado',1)->listar();
        }
        return $datos;
    }

    function insertar_editar($parametros)
    {

        
        $ddl_etapa_tipo = $parametros['ddl_etapa_tipo'] ?? $parametros['ddl_etapa_tipo'] ?? null;
        
        
        $datos = array(
            array('campo' => 'nombre', 'dato' => $parametros['txt_nombre'] ?? ''),
            array('campo' => 'tipo', 'dato' => $ddl_etapa_tipo),
            array('campo' => 'orden', 'dato' => ($parametros['txt_orden'] ?? '') !== '' ? (int)$parametros['txt_orden'] : null),
            array('campo' => 'obligatoria', 'dato' => isset($parametros['chk_obligatoria']) ? ($parametros['chk_obligatoria'] ? 1 : 0) : 0),
            array('campo' => 'descripcion', 'dato' => $parametros['txt_descripcion'] ?? ''),
            array('campo' => 'estado', 'dato' => 1),
        );

        // Inserción (sin validar duplicados)
        if (empty($parametros['_id'])) {
            // agregar fecha de creación
            $datos[] = array('campo' => 'fecha_creacion', 'dato' => date('Y-m-d H:i:s'));
            // insertar y obtener id
            $id = $this->modelo->insertar_id($datos);
            // devolver 1 si se insertó correctamente (manteniendo coherencia con tu JS)
            return ($id) ? 1 : 0;
        } else {
            // Edición: actualizar por id
            $where[0]['campo'] = 'id';
            $where[0]['dato'] = $parametros['_id'];
            $res = $this->modelo->editar($datos, $where);
             return ($parametros['_id']) ? 1 : 0;
        }
        return -2;
        
    }

    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'id';
        $where[0]['dato'] = $id;

        $datos = $this->modelo->editar($datos, $where);
        return $datos;
    }

    // Para usar en select2
    function buscar($parametros)
    {
        $lista = array();
        $concat = "nombre, descripcion";
        $datos = $this->modelo->where('estado', 1)->like($concat, $parametros['query']);

        foreach ($datos as $key => $value) {
            $text = $value['nombre'];
            $lista[] = array('id' => ($value['id']), 'text' => ($text)/*, 'data' => $value */);
        }

        return $lista;
    }

    public function organizar($ordenes_json)
{
    if (empty($ordenes_json)) return -1;

    // Intentar decodificar
    $ordenes = json_decode($ordenes_json, true);
    if (!is_array($ordenes)) return -1;

    // Puedes usar transacción si tu DB wrapper la soporta; aquí intento un enfoque simple
    $ok = true;
    foreach ($ordenes as $o) {
        $id = isset($o['id']) ? (int)$o['id'] : 0;
        $orden = isset($o['orden']) ? (int)$o['orden'] : null;
        if ($id <= 0 || $orden === null) {
            $ok = false;
            break;
        }
        $res = $this->actualizar_orden($id, $orden);
        if ($res !== 1 && $res !== true) { // dependiendo de lo que retorne tu modelo
            $ok = false;
            break;
        }
    }

    return $ok ? 1 : -1;
}
public function actualizar_orden($id, $orden)
{
    $datos = [
        ['campo' => 'orden', 'dato' => (int)$orden],
        ['campo' => 'fecha_modificacion', 'dato' => date('Y-m-d H:i:s')]
    ];

    $where = [
        ['campo' => 'id', 'dato' => (int)$id]
    ];

    return $this->modelo->editar($datos, $where);
}

/**
 * Opcional: actualizar múltiples ordenes en batch (recibe array [{id,orden},...])
 */
public function actualizar_ordenes_batch($ordenes)
{
    if (!is_array($ordenes)) return false;

    // Si tu $this->db soporta transacciones, úsala aquí.
    $allOk = true;
    foreach ($ordenes as $o) {
        $id = (int)($o['id'] ?? 0);
        $orden = (int)($o['orden'] ?? 0);
        if ($id <= 0) { $allOk = false; break; }
        $res = $this->actualizar_orden($id, $orden);
        if (!$res) { $allOk = false; break; }
    }
    return $allOk ? 1 : 0;
}
}