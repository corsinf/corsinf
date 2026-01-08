<?php
require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_per_parientesM.php');

$controlador = new th_per_parientesC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id']));
}

if (isset($_GET['listar_modal'])) {
    echo json_encode($controlador->listar_modal($_POST['id']));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

class th_per_parientesC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_per_parientesM();
    }

    function listar($id)
    {
        $datos = $this->modelo->listar_parientes_por_persona($id);
        $texto = '';

        foreach ($datos as $value) {
            $texto .= <<<HTML
                <div class="row mb-col">
                    <div class="col-10">
                        <p class="m-0"><strong>Parentesco:</strong> {$value['parentesco_nombre']}</p>
                        <p class="m-0"><strong>Nombre:</strong> {$value['nombres']} {$value['apellidos']}</p>
                    </div>
                    <div class="col-2 d-flex justify-content-end">
                        <button class="btn icon-hover" onclick="abrir_modal_pariente('{$value['_id']}');">
                            <i class="bx bx-pencil bx-sm text-dark"></i>
                        </button>
                    </div>
                </div>
                <hr>
            HTML;
        }

        if (empty($datos)) {
            $texto = '<div class="alert alert-info">No hay registros de parientes.</div>';
        }

        return $texto;
    }

    function listar_modal($id)
    {
        return $this->modelo->listar_pariente_por_id($id);
    }

    function insertar_editar($parametros)
    {
        $id_parentesco = $parametros['ddl_parentesco'];
        $per_id = $parametros['per_id'];
        $id_registro = $parametros['_id'];

        // Validar límites según tipo de parentesco
        $validacion = $this->validar_limites_parentesco($id_parentesco, $per_id, $id_registro);
        
        if ($validacion !== true) {
            return $validacion; // Retorna código de error
        }

        $datos = [
            ['campo' => 'th_per_id', 'dato' => $per_id],
            ['campo' => 'id_parentesco', 'dato' => $id_parentesco],
            ['campo' => 'th_ppa_nombres', 'dato' => $parametros['txt_nombres']],
            ['campo' => 'th_ppa_apellidos', 'dato' => $parametros['txt_apellidos']]
        ];

        if ($id_registro == '') {
            // INSERTAR
            $datos[] = ['campo' => 'th_ppa_fecha_creacion', 'dato' => date('Y-m-d H:i:s')];
            return $this->modelo->insertar($datos);
        } else {
            // EDITAR
            $datos[] = ['campo' => 'th_ppa_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')];
            
            $where[0]['campo'] = 'th_ppa_id';
            $where[0]['dato'] = $id_registro;
            
            return $this->modelo->editar($datos, $where);
        }
    }

    function validar_limites_parentesco($id_parentesco, $per_id, $id_registro_actual = '')
    {
        $parentesco_info = $this->modelo->obtener_parentesco_por_id($id_parentesco);
        
        if (empty($parentesco_info)) {
            return -3; // Parentesco no válido
        }

        $nombre_parentesco = strtolower($parentesco_info[0]['parentesco_nombre']);

        $this->modelo->reset();
        $query = $this->modelo
            ->where('th_per_id', $per_id)
            ->where('id_parentesco', $id_parentesco)
            ->where('th_ppa_estado', 1);

        if ($id_registro_actual != '') {
            $query = $query->where('th_ppa_id !', $id_registro_actual);
        }

        $existentes = $query->listar();
        $cantidad_existente = count($existentes);

        if (stripos($nombre_parentesco, 'espos') !== false || 
            stripos($nombre_parentesco, 'cónyuge') !== false ||
            stripos($nombre_parentesco, 'conyugue') !== false) {
            
            if ($cantidad_existente >= 1) {
                return -4; // Ya existe un esposo/cónyuge
            }
        }

        // Padre/Madre: máximo 2 en total
        if (stripos($nombre_parentesco, 'padre') !== false || 
            stripos($nombre_parentesco, 'madre') !== false) {
            
            // Contar todos los padres (padre y madre)
            $this->modelo->reset();
            $query_padres = $this->modelo
                ->where('th_per_id', $per_id)
                ->where('th_ppa_estado', 1);

            if ($id_registro_actual != '') {
                $query_padres = $query_padres->where('th_ppa_id !', $id_registro_actual);
            }

            $todos_parientes = $query_padres->listar();
            
            $contador_padres = 0;
            foreach ($todos_parientes as $pariente) {
                $parentesco_actual = $this->modelo->obtener_parentesco_por_id($pariente['id_parentesco']);
                if (!empty($parentesco_actual)) {
                    $nombre_par = strtolower($parentesco_actual[0]['parentesco_nombre']);
                    if (stripos($nombre_par, 'padre') !== false || stripos($nombre_par, 'madre') !== false) {
                        $contador_padres++;
                    }
                }
            }

            if ($contador_padres >= 2) {
                return -5; // Ya existen 2 padres
            }
        }

        
        return true;
    }

    function eliminar($id)
    {
        $datos = [
            ['campo' => 'th_ppa_id', 'dato' => $id]
        ];
        return $this->modelo->eliminar($datos);
    }
}