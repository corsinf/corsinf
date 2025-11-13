<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 3)  . '/modelo/TALENTO_HUMANO/CONTRATACION/th_contr_plazasM.php');

$controlador = new th_contr_plazasC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['insertar_editar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['_id']));
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


class  th_contr_plazasC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_contr_plazasM();
    }

    function listar($id = '')
    {
       
        if($id == ''){

            $datos = $this->modelo->where('th_pla_estado',1)->listar();

        }else{
             $datos = $this->modelo->listar_plazas_con_horarios($id);
        }
       

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
        $toInt = function ($v) { return ($v === '' || $v === null) ? null : (int)$v; };
        $toFloat = function ($v) { return ($v === '' || $v === null) ? null : (float)$v; };
        $toBoolInt = function ($v) { return ($v === 1 || $v === '1' || $v === true || $v === 'true') ? 1 : 0; };

        // Preparar array de datos según convención tuya
        $datos = array(
            array('campo' => 'th_pla_titulo', 'dato' => $parametros['txt_th_pla_titulo'] ?? ''),
            array('campo' => 'th_pla_descripcion', 'dato' => $parametros['txt_th_pla_descripcion'] ?? ''),
            array('campo' => 'th_pla_tipo', 'dato' => $parametros['ddl_th_pla_tipo'] ?? ''),
            array('campo' => 'th_pla_num_vacantes', 'dato' => $toInt($parametros['txt_th_pla_num_vacantes'] ?? null)),
            array('campo' => 'th_pla_fecha_publicacion', 'dato' => $toDateTime($parametros['txt_th_pla_fecha_publicacion'] ?? null)),
            array('campo' => 'th_pla_fecha_cierre', 'dato' => $toDateTime($parametros['txt_th_pla_fecha_cierre'] ?? null)),
            array('campo' => 'th_pla_jornada_id', 'dato' => $toInt($parametros['ddl_horario'] ?? $parametros['txt_th_pla_jornada_id'] ?? null)),
            array('campo' => 'th_pla_salario_min', 'dato' => $toFloat($parametros['txt_th_pla_salario_min'] ?? null)),
            array('campo' => 'th_pla_salario_max', 'dato' => $toFloat($parametros['txt_th_pla_salario_max'] ?? null)),
            array('campo' => 'th_pla_tiempo_contrato', 'dato' => $parametros['txt_th_pla_tiempo_contrato'] ?? null),
            array('campo' => 'th_pla_prioridad_interna', 'dato' => $toBoolInt($parametros['chk_th_pla_prioridad_interna'] ?? 0)),
            array('campo' => 'th_pla_requiere_documentos', 'dato' => $toBoolInt($parametros['chk_th_pla_requiere_documentos'] ?? 0)),
            array('campo' => 'th_pla_responsable_persona_id', 'dato' => $toInt($parametros['txt_th_pla_responsable_persona_id'] ?? null)),
            array('campo' => 'th_pla_observaciones', 'dato' => $parametros['txt_th_pla_observaciones'] ?? null),
            array('campo' => 'th_pla_estado', 'dato' => $toBoolInt($parametros['chk_th_pla_estado'] ?? 1)),
            array('campo' => 'th_pla_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
        );

        // Inserción (sin validar duplicados)
        if (empty($parametros['_id'])) {
            // agregar fecha de creación
            $datos[] = array('campo' => 'th_pla_fecha_creacion', 'dato' => date('Y-m-d H:i:s'));
            // insertar y obtener id
            $id = $this->modelo->insertar_id($datos);
            // devolver 1 si se insertó correctamente (manteniendo coherencia con tu JS)
            return ($id) ? 1 : 0;
        } else {
            // Edición: actualizar por id
            $where[0]['campo'] = 'th_pla_id';
            $where[0]['dato'] = $parametros['_id'];
            $res = $this->modelo->editar($datos, $where);
            return $res;
        }
    }


    function eliminar($id)
    {

         $datos = array(
            array('campo' => 'th_pla_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'th_pla_id';
        $where[0]['dato'] = $id;

        $datos = $this->modelo->editar($datos, $where);
        return $datos;
        
    }

   

    //Para usar en select2
    public function buscar($parametros)
{
    $lista = [];

    $query = isset($parametros['query']) ? trim($parametros['query']) : '';

    // Obtenemos todas las plazas no asignadas
    $datos =  $this->modelo->listar_plazas_no_asignadas();

    foreach ($datos as $plaza) {
        // texto a buscar: título (puedes concatenar más campos si quieres)
        $titulo = isset($plaza['th_pla_titulo']) ? $plaza['th_pla_titulo'] : '';

        if ($query === '' || stripos($titulo, $query) !== false) {
            $lista[] = [
                'id'   => $plaza['th_pla_id'],
                'text' => $titulo,
            ];
        }
    }

    return $lista;
}
}