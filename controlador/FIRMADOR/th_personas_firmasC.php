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
        $id = $_SESSION['INICIO']['NO_CONCURENTE'];


        if ($id == '') {
            $datos = $this->modelo->lista_personas_firma();
        } else {
            // Especificar la tabla en el WHERE para evitar ambigüedad
            $datos = $this->modelo->lista_personas_firma($id);
        }

        return $datos;
    }

    public function buscar($parametros)
    {
        $lista = [];

        // Obtener el ID de la sesión
        $id = $_SESSION['INICIO']['NO_CONCURENTE'] ?? null;

        $camposBusqueda = [
            'th_perfir_id',
            'th_perfir_identificacion'
        ];

        // Construimos la consulta
        $query = $this->modelo->where('th_perfir_estado', 1);

        // Solo filtrar por th_per_id si el ID no es null
        if ($id > 0) {
            $query = $query->where('th_per_id', $id);
        }

        // Filtramos los datos con LIKE en los campos especificados
        $datos = $query->like(implode(',', $camposBusqueda), $parametros['query']);

        // Formatear los resultados
        foreach ($datos as $value) {
            $text = "{$value['th_perfir_identificacion']}";
            $lista[] = [
                'id' => $value['th_perfir_id'],
                'text' => $text,
                'data' => $value
            ];
        }

        return $lista;
    }





    function insertar_editar($file, $parametros)
    {
        //print_r($file); exit();
        // Construcción del arreglo con los datos a insertar/editar
        $datos = array(
            array('campo' => 'th_per_id', 'dato' => $parametros['th_per_id']),
            array('campo' => 'th_tipfir_id', 'dato' => isset($parametros['ddl_tipoPersona']) ? $parametros['ddl_tipoPersona'] : 1),
            array('campo' => 'th_perfir_nombre_firma', 'dato' => $parametros['txt_nombreFirma']),
            array('campo' => 'th_perfir_identificacion', 'dato' => $parametros['txt_identidad']),
            array('campo' => 'th_perfir_contrasenia', 'dato' => $parametros['txt_clave']),
            array('campo' => 'th_perfir_fecha_creacion', 'dato' => date('Y-m-d H:i:s')),
            array('campo' => 'th_perfir_fecha_archivo', 'dato' => date('Y-m-d H:i:s')),
            array('campo' => 'th_perfir_fecha_expiracion', 'dato' => date('Y-m-d H:i:s')),
            array('campo' => 'th_perfir_documento_url', 'dato' => isset($parametros['doc_subirDocumento']) ? $parametros['doc_subirDocumento'] : "documento.pdf"),
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
                $where = array(array('campo' => 'th_perfir_id', 'dato' => $parametros['_id']));
                //$this->guardar_archivo($file, $parametros, $parametros['_id']);
                $datos = $this->modelo->editar($datos, $where);
                $_id = $parametros['_id'];

                if ($file['txt_ruta_archivo']['tmp_name'] != '' && $file['txt_ruta_archivo']['tmp_name'] != null) {
                    $datos = $this->guardar_archivo($file, $parametros, $_id);
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
            $uploadfile_temporal = $file['txt_ruta_archivo']['tmp_name'];
            $extension = pathinfo($file['txt_ruta_archivo']['name'], PATHINFO_EXTENSION);
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
        switch ($file['txt_ruta_archivo']['type']) {
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
