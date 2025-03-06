<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/FIRMADOR/th_personas_firmasM.php');

$controlador = new th_personas_firmasC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar());
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_FILES, $_POST));
}

if (isset($_GET['leer_archivo'])) {
    echo json_encode($controlador->leer_archivo());
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

class th_personas_firmasC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_personas_firmasM();
    }

    // Método para listar registros; si se pasa un id, lista ese registro, de lo contrario solo los activos (estado = 1)
    function listar()
    {
        $id = $_SESSION['INICIO']['NO_CONCURENTE'];


        if ($id == '') {
            $datos = $this->modelo->lista_genero();
        } else {
            // Especificar la tabla en el WHERE para evitar ambigüedad
            $datos = $this->modelo->lista_genero($id);
        }

        return $datos;
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


    function leer_archivo()
    {
        // Definir la ruta real en el servidor
        $ruta_p12 = $_SERVER['DOCUMENT_ROOT'] . "/corsinf/REPOSITORIO/TALENTO_HUMANO/3044/128263/FIRMAS/firmas_electronicas_8.p12";
        $clave_p12 = "milton123*"; // Ingresa la contraseña correcta del .p12
        
        // Verificar si el archivo existe
        if (!file_exists($ruta_p12)) {
            die("Error: El archivo .p12 no existe en la ruta: $ruta_p12");
        }
        
        // Leer el contenido del archivo
        $contenido_p12 = file_get_contents($ruta_p12);
        
        $certificados = [];
        if (openssl_pkcs12_read($contenido_p12, $certificados, $clave_p12)) {
            echo "<pre>";
            print_r($certificados); // Muestra la información del archivo .p12
            echo "</pre>";
        } else {
            echo "Error al leer el archivo .p12. Puede que la contraseña sea incorrecta.";
        }
        
    }
}
