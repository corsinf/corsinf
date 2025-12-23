<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/TALENTO_HUMANO/th_personas_departamentosM.php');
require_once(dirname(__DIR__, 2) . '/db/codigos_globales.php');

$controlador = new th_personas_departamentosC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}
if (isset($_GET['verificar'])) {
    $password = $_POST['password'] ?? '';
    $per_id = $_POST['id'] ?? '';

    // Llama a tu función (asumiendo que $controlador ya está instanciado)
    echo json_encode($controlador->validar_correo($password, $per_id));
    // No pongas nada más aquí porque validar_correo() ya hace exit
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}
// th_personas_departamentosC.php

// Para mover una o varias personas
if (isset($_GET['mover_varios'])) {
    // Indicamos que la respuesta será JSON
    header('Content-Type: application/json; charset=utf-8');

    $ids_raw = isset($_POST['ids']) ? $_POST['ids'] : '[]';
    $ids = json_decode($ids_raw, true); // array de objetos { perdep, person }
    $id_departamento_destino = isset($_POST['id_departamento_destino']) ? $_POST['id_departamento_destino'] : '';
    $txt_visitor = isset($_POST['txt_visitor']) ? $_POST['txt_visitor'] : '';

    // Validación
    if (!is_array($ids) || empty($id_departamento_destino)) {
        echo json_encode([
            'success' => false,
            'message' => 'Parámetros inválidos',
            'exitosos' => 0,
            'duplicados' => 0,
            'fallidos' => 0,
            'errores' => []
        ]);
        exit;
    }

    // Ejecuta la lógica del controlador
    $resultado = $controlador->mover_personas_departamento($ids, $id_departamento_destino, $txt_visitor);

    echo json_encode($resultado);
    exit;
}



// Mantén tu función original para otros usos
if (isset($_GET['insertar_editar_persona'])) {
    echo json_encode($controlador->insertar_editar_persona_departamento($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['listar_personas_modal'])) {
    echo json_encode($controlador->listar_personas_modal($_POST['id']));
}


class th_personas_departamentosC
{
    private $modelo;
    private $codigo_globales;

    function __construct()
    {
        $this->modelo = new th_personas_departamentosM();
        $this->codigo_globales = new codigos_globales();
    }

    function validar_correo($password_validar, $id)
    {

        $email = $_SESSION['INICIO']['EMAIL'];
        $id_usuario = $_SESSION['INICIO']['ID_USUARIO'];

        $usuario = $this->modelo->obtener_correo_y_password($id_usuario);


        $password = $this->codigo_globales->desenciptar_clave(trim($usuario[0]['password'] ?? ''));



        if ($password_validar == $password) {

            $where = array(
                array('campo' => 'th_per_id', 'dato' => $id),
            );

            $datos = $this->modelo->eliminar($where);
            return $datos;
        } else {

            return -3;
        }
    }

    function listar($id = '')
    {
        if ($id == '') {
            $datos = $this->modelo->listar_personas_departamentos($id);
        } else {
            //Busqueda por departamento
            $datos = $this->modelo->listar_personas_departamentos($id);
        }

        return $datos;
    }

    function listar_personas_modal($id_departamento = '')
    {
        $datos = $this->modelo->listar_personas_modal($id_departamento);
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $salida = '';
        if ($parametros['_id'] != '') {
            if (isset($parametros['personas_seleccionadas'])) {

                foreach ($parametros['personas_seleccionadas'] as $persona_id) {
                    // Crear el array $datos para cada persona seleccionada
                    $datos = array(
                        array('campo' => 'th_per_id', 'dato' => $persona_id), // Usar el ID de la persona seleccionada
                        array('campo' => 'th_dep_id', 'dato' => $parametros['_id']),
                        array('campo' => 'th_perdep_visitor', 'dato' => $parametros['txt_visitor']),
                    );

                    $contar_personas = count($this->modelo->where('th_per_id', $persona_id)->where('th_dep_id', $parametros['_id'])->listar());
                    if ($contar_personas == 0) {
                        $salida .= $persona_id . ' - ' . $contar_personas . '<br>';
                        $datos = $this->modelo->insertar($datos);
                    }

                    $this->modelo->reset();
                }
            } else {
                return -2;
            }

            return 1;
        }
    }

    function insertar_editar_persona_departamento($parametros)
    {
        $datos = array(
            array('campo' => 'th_per_id', 'dato' => $parametros['id_persona']),
            array('campo' => 'th_dep_id', 'dato' => $parametros['id_departamento']),
            array('campo' => 'th_perdep_visitor', 'dato' => $parametros['txt_visitor']),
        );

        if ($parametros['_id'] == '') {
            // Inserción: verificar que no exista la relación persona-departamento
            $existe = $this->modelo
                ->where('th_per_id', $parametros['id_persona'])
                ->where('th_dep_id', $parametros['id_departamento'])
                ->listar();

            if (count($existe) == 0) {
                $datos[] = array('campo' => 'th_perdep_fecha_creacion', 'dato' => date('Y-m-d H:i:s'));
                $resultado = $this->modelo->insertar_id($datos);
                return 1;
            } else {
                return -2; // Ya existe la relación
            }
        } else {
            // Edición: verificar que no exista otra relación igual
            $existe = $this->modelo
                ->where('th_per_id', $parametros['id_persona'])
                ->where('th_dep_id', $parametros['id_departamento'])
                ->where('th_perdep_id !', $parametros['_id'])
                ->listar();

            if (count($existe) == 0) {
                $where[0]['campo'] = 'th_perdep_id';
                $where[0]['dato'] = $parametros['_id'];
                $resultado = $this->modelo->editar($datos, $where);
                return $resultado;
            } else {
                return -2; // Ya existe la relación
            }
        }
    }

    function mover_personas_departamento($ids_personas, $id_departamento_destino, $txt_visitor = '')
    {
        $exitosos = 0;
        $fallidos = 0;
        $duplicados = 0;
        $errores = [];

        foreach ($ids_personas as $item) {
            // Normaliza
            $perdep = isset($item['perdep']) ? trim($item['perdep']) : '';
            $person = isset($item['person']) ? trim($item['person']) : '';

            try {
                if ($perdep !== '') {
                    // Caso 1: tenemos el id de la relación (th_perdep_id) -> Actualizar esa fila
                    // Verificar que no exista otra relación igual (misma persona y mismo depto con distinto perdep_id)
                    $existe = $this->modelo
                        ->where('th_per_id', $person)
                        ->where('th_dep_id', $id_departamento_destino)
                        ->where('th_perdep_id !', $perdep)
                        ->listar();

                    if (count($existe) > 0) {
                        // Ya existe otra relación igual -> conteo duplicado
                        $duplicados++;
                        continue;
                    }

                    // Preparar datos para editar
                    $datos = array(
                        array('campo' => 'th_dep_id', 'dato' => $id_departamento_destino),
                        array('campo' => 'th_perdep_visitor', 'dato' => $txt_visitor)
                    );
                    $where[0]['campo'] = 'th_perdep_id';
                    $where[0]['dato'] = $perdep;

                    $res = $this->modelo->editar($datos, $where);
                    if ($res > 0) $exitosos++;
                    else {
                        $fallidos++;
                        $errores[] = "No se pudo actualizar relación perdep_id {$perdep}";
                    }
                } elseif ($person !== '') {
                    // Caso 2: solo tenemos person id -> intentar insertar nueva relación (si no existe)
                    // Verifica si la relación ya existe
                    $existe = $this->modelo
                        ->where('th_per_id', $person)
                        ->where('th_dep_id', $id_departamento_destino)
                        ->listar();

                    if (count($existe) > 0) {
                        $duplicados++;
                        continue;
                    }

                    // Inserción (usar la misma estructura que insertar_editar_persona_departamento)
                    $datos = array(
                        array('campo' => 'th_per_id', 'dato' => $person),
                        array('campo' => 'th_dep_id', 'dato' => $id_departamento_destino),
                        array('campo' => 'th_perdep_fecha_creacion', 'dato' => date('Y-m-d H:i:s')),
                        array('campo' => 'th_perdep_visitor', 'dato' => $txt_visitor)
                    );

                    $resIns = $this->modelo->insertar_id($datos);
                    if ($resIns > 0) $exitosos++;
                    else {
                        $fallidos++;
                        $errores[] = "Fallo al insertar relación para persona {$person}";
                    }
                } else {
                    $fallidos++;
                    $errores[] = "Item inválido (sin perdep ni person)";
                }
            } catch (Exception $e) {
                $fallidos++;
                $errores[] = "Error en item (perdep={$perdep}, person={$person}): " . $e->getMessage();
            }
        }

        $mensaje = "Operación completada. ";
        if ($exitosos > 0) $mensaje .= "$exitosos persona(s) movida(s). ";
        if ($duplicados > 0) $mensaje .= "$duplicados ya estaba(n) en el departamento. ";
        if ($fallidos > 0) $mensaje .= "$fallidos falló/fallaron. ";

        return [
            'success' => $exitosos > 0,
            'exitosos' => $exitosos,
            'duplicados' => $duplicados,
            'fallidos' => $fallidos,
            'message' => $mensaje,
            'errores' => $errores
        ];
    }


    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_perdep_id', 'dato' => $id),
        );

        $datos = $this->modelo->eliminar($datos);
        return $datos;
    }
}
