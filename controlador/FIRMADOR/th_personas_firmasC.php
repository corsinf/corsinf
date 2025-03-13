<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/FIRMADOR/th_personas_firmasM.php');

$controlador = new th_personas_firmasC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar());
}

if (isset($_GET['listar_persona'])) {
    echo json_encode($controlador->listar_persona($_POST['id']));
}


if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_FILES, $_POST));
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

class th_personas_firmasC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_personas_firmasM();
    }


    function listar_persona($id)
    {
        if ($id) {
            $datos = $this->modelo->where('th_perfir_id', $id)->listar();
        }

        return $datos;
    }

    // Método para listar registros; si se pasa un id, lista ese registro, de lo contrario solo los activos (estado = 1)
    function listar()
    {

        if ($_SESSION['INICIO']['NO_CONCURENTE'] > 1) {
            $id = $_SESSION['INICIO']['NO_CONCURENTE'];
            $datos = $this->modelo->lista_personas_firma($id);
        } else {
            $id = $_SESSION['INICIO']['ID_USUARIO'];
            $datos = $this->modelo->lista_usuario_firma($id);
        }
        return $datos;
    }

    public function buscar($parametros)
    {
        $lista = [];

        if ($_SESSION['INICIO']['NO_CONCURENTE'] > 1) {
            $id = $_SESSION['INICIO']['NO_CONCURENTE'];
            $datos = $this->modelo->lista_personas_firma($id);
        } else {
            $id = $_SESSION['INICIO']['ID_USUARIO'];
            $datos = $this->modelo->lista_usuario_firma($id);
        }

        

        // Filtrar los datos con LIKE en los campos especificados
        $query = array_filter($datos, function ($value) use ($parametros) {
            return stripos($value['th_perfir_identificacion'], $parametros['query']) !== false ||
                stripos($value['th_perfir_id'], $parametros['query']) !== false;
        });

        // Formatear los resultados
        foreach ($query as $value) {
            $identificacion = $value['th_perfir_identificacion'] ?? '';
            $nombreFirma = $value['th_perfir_nombre_firma'] ?? '';
            $descripcionFirma = $value['th_tipfir_descripcion'] ?? ''; // Descripción de la firma

            $text = trim("{$identificacion}  -  {$nombreFirma}  -  {$descripcionFirma}"); // Formato de salida

            $lista[] = [
                'id' => $value['th_perfir_id'] ?? null,
                'text' => $text,
                'data' => $value
            ];
        }


        return $lista;
    }

    function insertar_editar($file, $parametros)
    {
      
        // Construcción del arreglo con los datos a insertar/editar


        $datos = array(
            array('campo' => 'th_per_id', 'dato' => (!empty($parametros['th_per_id'])) ? $parametros['th_per_id'] : NULL),
            array('campo' => 'th_usuarios_id', 'dato' => isset($parametros['th_usuario_id']) ? $parametros['th_usuario_id'] : 2),
            array('campo' => 'th_tipfir_id', 'dato' => isset($parametros['ddl_tipoPersona']) ? $parametros['ddl_tipoPersona'] : 1),
            array('campo' => 'th_perfir_nombre_firma', 'dato' => $parametros['txt_nombreFirma']),
            array('campo' => 'th_perfir_identificacion', 'dato' => $parametros['txt_identidad']),
            array('campo' => 'th_perfir_contrasenia', 'dato' => $parametros['cbx_guardarClave_hidden'] == 1 ?  $parametros['txt_ingresarClave'] : ""),
            array('campo' => 'th_perfir_fecha_creacion', 'dato' => date('Y-m-d H:i:s')),
            array('campo' => 'th_perfir_fecha_inicio', 'dato' => date('Y-m-d H:i:s'), strtotime($parametros['txt_fecha_inicio'])),
            array('campo' => 'th_perfir_fecha_expiracion', 'dato' => date("Y-m-d H:i:s", strtotime($parametros['txt_fecha_expiracion']))),
            array('campo' => 'th_perfir_documento_url', 'dato' => isset($parametros['txt_url_firma']) ? $parametros['txt_url_firma'] : $parametros['doc_subirDocumento']),
            array('campo' => 'th_perfir_politica_de_datos', 'dato' => isset($parametros['cbx_politicaDeDatos']) ? 1 : 0),
            array('campo' => 'th_perfir_estado', 'dato' => 1)
        );

       
        // Si no se envía un _id, se entiende que es un registro nuevo
        if (empty($parametros['_id'])) {

            // Verifica que no exista otro registro con el mismo RUC
            //$this->guardar_archivo($file, $parametros, $datos);
            $datos = $this->modelo->insertar_id($datos);

            $this->guardar_archivo($file, $parametros, $datos);

            return 1;
        } else {


            if (!empty($parametros['_id'])) {

                if (strlen($parametros['txt_url_firma']) > 0) {
                    $where = array(array('campo' => 'th_perfir_id', 'dato' => $parametros['_id']));
                    //$this->guardar_archivo($file, $parametros, $parametros['_id']);
                    $datos = $this->modelo->editar($datos, $where);
                } else {
                    $where = array(array('campo' => 'th_perfir_id', 'dato' => $parametros['_id']));
                    //$this->guardar_archivo($file, $parametros, $parametros['_id']);
                    $datos = $this->modelo->editar($datos, $where);


                    $_id = $parametros['_id'];

                    if ($file['txt_cargar_imagen']['tmp_name'] != '' && $file['txt_cargar_imagen']['tmp_name'] != null) {
                        $datos = $this->guardar_archivo($file, $parametros, $_id);
                    }
                }
            } else {
                return -1; // Código de error: ID no válido
            }
        }

        return $datos;
    }

    private function guardar_archivo($file, $post, $id_insertar_editar)
    {
        $id_empresa = $_SESSION['INICIO']['ID_EMPRESA'];
        $ruta = dirname(__DIR__, 2) . '/REPOSITORIO/TALENTO_HUMANO/' . $id_empresa . '/'; //ruta carpeta donde queremos copiar los archivos
        $ruta .= $post['cedula'] . '/' . 'FIRMAS/';

        if (!file_exists($ruta)) {
            mkdir($ruta, 0777, true);
        }

        if ($this->validar_formato_archivo($file) === 1) {
            $uploadfile_temporal = $file['txt_cargar_imagen']['tmp_name'];
            $extension = pathinfo($file['txt_cargar_imagen']['name'], PATHINFO_EXTENSION);
            //Para CERTIFICACIONES y CAPACITACIONES
            $nombre = 'firmas_electronicas_' . $id_insertar_editar . '.' . $extension;
            $nuevo_nom = $ruta . $nombre;

            $nombre_ruta = '../REPOSITORIO/TALENTO_HUMANO/' . $id_empresa . '/' . $post['cedula'] . '/' . 'FIRMAS/';
            $nombre_ruta .= $nombre;
            //print_r($post); exit(); die();

            if (is_uploaded_file($uploadfile_temporal)) {
                if (move_uploaded_file($uploadfile_temporal, $nuevo_nom)) {

                    $datos = array(
                        array('campo' => 'th_perfir_documento_url', 'dato' => $nombre_ruta),
                    );

                    $where = array(
                        array('campo' => 'th_perfir_id', 'dato' => $id_insertar_editar),
                    );

                    // Ejecutar la actualización en la base de datos
                    $base = $this->modelo->editar($datos, $where);

                    return $base == 1 ? 1 : -1;
                } else {
                    return -1;
                }
            } else {
                return -1;
            }
        } else {
            return -2;
        }
    }

    private function validar_formato_archivo($file)
    {
        switch ($file['txt_cargar_imagen']['type']) {
            case 'application/x-pkcs12': // Tipo MIME para archivos .p12
                return 1;
                break;
            default:
                return -1;
                break;
        }
    }




    // Método para eliminar lógicamente un registro (cambiando el estado a 0)
    function eliminar($id)
    {
        $where = array(
            array('campo' => 'th_perfir_id', 'dato' => $id)
        );
        //cambiar datos por datos
        $datos = $this->modelo->eliminar($where);
        return $datos;
    }
}
