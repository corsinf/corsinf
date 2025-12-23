<?php
date_default_timezone_set('America/Guayaquil');

require_once(dirname(__DIR__, 2) . '/modelo/GENERAL/th_personasM.php');

$controlador = new th_personasC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id'] ?? ''));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['insertar_imagen'])) {
    echo json_encode($controlador->insertar_imagen($_FILES, $_POST));
}


class th_personasC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_personasM();
    }

    function listar($id = '')
    {
        $datos = $this->modelo->obtener_persona_con_nombres($id);
        return $datos;
    }

   function insertar_editar($parametros)
{
    try {
        // Validar y preparar fechas
        $txt_fecha_nacimiento = !empty($parametros['txt_fecha_nacimiento']) 
            ? $parametros['txt_fecha_nacimiento'] 
            : null;
        
        $txt_fecha_ingreso = !empty($parametros['txt_fecha_ingreso']) 
            ? $parametros['txt_fecha_ingreso'] 
            : null;

        // Generar nombres completos
        $nombres_completos = trim(
            trim($parametros['txt_primer_apellido'] ?? '') . ' ' . 
            trim($parametros['txt_segundo_apellido'] ?? '') . ' ' . 
            trim($parametros['txt_primer_nombre'] ?? '') . ' ' . 
            trim($parametros['txt_segundo_nombre'] ?? '')
        );

        // Obtener cédula (puede venir de txt_cedula o txt_numero_cedula)
        $cedula = $parametros['txt_cedula_persona'] ?? $parametros['txt_numero_cedula'] ?? '';

        // Array de datos - SOLO CAMPOS QUE EXISTEN EN LA BD
        $datos = array(
            // Datos personales básicos
            array('campo' => 'th_per_primer_apellido', 'dato' => trim($parametros['txt_primer_apellido'] ?? '')),
            array('campo' => 'th_per_segundo_apellido', 'dato' => trim($parametros['txt_segundo_apellido'] ?? '')),
            array('campo' => 'th_per_primer_nombre', 'dato' => trim($parametros['txt_primer_nombre'] ?? '')),
            array('campo' => 'th_per_segundo_nombre', 'dato' => trim($parametros['txt_segundo_nombre'] ?? '')),
            array('campo' => 'th_per_nombres_completos', 'dato' => $nombres_completos),
            array('campo' => 'th_per_cedula', 'dato' => trim($cedula)),
            array('campo' => 'th_per_sexo', 'dato' => $parametros['ddl_sexo'] ?? null),
            array('campo' => 'th_per_fecha_nacimiento', 'dato' => $txt_fecha_nacimiento),
            array('campo' => 'th_per_nacionalidad', 'dato' => $parametros['ddl_nacionalidad'] ?? null),
            array('campo' => 'th_per_estado_civil', 'dato' => $parametros['ddl_estado_civil'] ?? null),
            
            // Datos de contacto
            array('campo' => 'th_per_telefono_1', 'dato' => trim($parametros['txt_telefono_1'] ?? '')),
            array('campo' => 'th_per_telefono_2', 'dato' => trim($parametros['txt_telefono_2'] ?? '')),
            array('campo' => 'th_per_correo', 'dato' => trim($parametros['txt_correo'] ?? '')),
            
            // Ubicación
            array('campo' => 'th_per_direccion', 'dato' => trim($parametros['txt_direccion'] ?? '')),
            array('campo' => 'th_per_postal', 'dato' => trim($parametros['txt_codigo_postal'] ?? $parametros['txt_direccion_postal'] ?? '')),
            array('campo' => 'th_prov_id', 'dato' => !empty($parametros['ddl_provincias']) ? $parametros['ddl_provincias'] : null),
            array('campo' => 'th_ciu_id', 'dato' => !empty($parametros['ddl_ciudad']) ? $parametros['ddl_ciudad'] : null),
            array('campo' => 'th_parr_id', 'dato' => !empty($parametros['ddl_parroquia']) ? $parametros['ddl_parroquia'] : null),
            
            // Información adicional
            array('campo' => 'th_per_tipo_sangre', 'dato' => !empty($parametros['ddl_tipo_sangre']) ? $parametros['ddl_tipo_sangre'] : null),
            array('campo' => 'th_per_observaciones', 'dato' => trim($parametros['txt_observaciones'] ?? '')),
            
            array('campo' => 'id_etnia', 'dato' => !empty($parametros['ddl_etnia']) ? $parametros['ddl_etnia'] : null),
            array('campo' => 'id_orientacion_sexual', 'dato' => !empty($parametros['ddl_religion']) ? $parametros['ddl_religion'] : null),
            array('campo' => 'id_identidad_genero', 'dato' => !empty($parametros['ddl_orientacion_sexual']) ? $parametros['ddl_orientacion_sexual'] : null),
            array('campo' => 'id_religion', 'dato' => !empty($parametros['ddl_identidad_genero']) ? $parametros['ddl_identidad_genero'] : null),
            array('campo' => 'th_per_correo_personal_1', 'dato' => !empty($parametros['txt_per_correo_personal_1']) ? $parametros['txt_per_correo_personal_1'] : null),
            array('campo' => 'th_per_correo_personal_2', 'dato' => !empty($parametros['txt_per_correo_personal_2']) ? $parametros['txt_per_correo_personal_2'] : null),

            // Metadata
            array('campo' => 'th_per_fecha_modificacion', 'dato' => date('Y-m-d H:i:s')),
            array('campo' => 'th_per_estado', 'dato' => '1'), // 1 = Activo
        );

        // Verificar si es inserción o edición
        if (empty($parametros['_id'])) {
            // === INSERCIÓN ===
            // Verificar que no exista la cédula
            $existe = $this->modelo
                ->where('th_per_cedula', trim($cedula))
                ->where('th_per_estado', '1')
                ->listar();
            
            if (count($existe) == 0) {
                // Agregar campos solo para inserción
                $datos[] = array('campo' => 'th_per_fecha_creacion', 'dato' => date('Y-m-d H:i:s'));
                
                // Opcional: Encriptar contraseña por defecto
                // $datos[] = array('campo' => 'PASS', 'dato' => $this->cod_global->enciptar_clave($cedula));
                
                $resultado = $this->modelo->insertar($datos);
                return $resultado;
            } else {
                return -2; // Cédula duplicada
            }
        } else {
           
                $where = array(
                    array('campo' => 'th_per_id', 'dato' => $parametros['_id'])
                );
                
                $resultado = $this->modelo->editar($datos, $where);
                return $resultado ? 1 : -2;
        }
    } catch (Exception $e) {
        error_log("Error en insertar_editar: " . $e->getMessage());
        return -1;
    }
}


    function eliminar($id)
    {
        $datos = array(
            array('campo' => 'th_per_estado', 'dato' => 0),
        );

        $where[0]['campo'] = 'th_per_id';
        $where[0]['dato'] = $id;

        $datos = $this->modelo->editar($datos, $where);
        return $datos;
    }

    //Para colocar una imagen a una persona existente
function insertar_imagen($file, $parametros)
{
    $id_persona = $parametros['txt_persona_id'];

    if ($id_persona != '') {
        if ($file['txt_copia_cambiar_foto']['tmp_name'] != '' && $file['txt_copia_cambiar_foto']['tmp_name'] != null) {
            $datos = $this->guardar_archivo($file, $parametros, $id_persona);
        }
    }

    return $datos;
}

private function guardar_archivo($file, $post, $id_insertar_editar)
{
    // Obtener el ID de la empresa desde la sesión
    $id_empresa = $_SESSION['INICIO']['ID_EMPRESA'];

    // Definir la ruta donde se guardarán las imágenes
    $ruta = dirname(__DIR__, 2) . '/REPOSITORIO/TALENTO_HUMANO/' . $id_empresa . '/'; 
    $ruta .= $post['txt_cedula'] . '/' . 'FOTO_PERFIL/';

    // Verificar si la carpeta existe, si no, crearla
    if (!file_exists($ruta)) {
        mkdir($ruta, 0777, true);
    }

    // Validar formato de la imagen
    if ($this->validar_formato($file) === 1) {
        
        // Obtener la ubicación temporal del archivo cargado
        $uploadfile_temporal = $file['txt_copia_cambiar_foto']['tmp_name'];
        // Obtener la extensión del archivo
        $extension = pathinfo($file['txt_copia_cambiar_foto']['name'], PATHINFO_EXTENSION);

        // Crear un nuevo nombre para la imagen
        $nombre = 'foto_perfil_' . $id_insertar_editar . '.' . $extension;
        $nuevo_nom = $ruta . $nombre;

        // Ruta que se almacenará en la base de datos
        $nombre_ruta = '../REPOSITORIO/TALENTO_HUMANO/' . $id_empresa . '/' . $post['txt_cedula'] . '/' . 'FOTO_PERFIL/';
        $nombre_ruta .= $nombre;

        // Verificar si el archivo ha sido cargado correctamente
        if (is_uploaded_file($uploadfile_temporal)) {
            // Mover el archivo de su ubicación temporal al destino final
            if (move_uploaded_file($uploadfile_temporal, $nuevo_nom)) {

                // Datos para actualizar la URL de la foto en la base de datos
                $datos = array(
                    array('campo' => 'th_per_foto_url', 'dato' => $nombre_ruta),
                );

                // Condición para identificar la persona que se debe actualizar
                $where = array(
                    array('campo' => 'th_per_id', 'dato' => $id_insertar_editar),
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
        return -2; // Formato inválido
    }
}

//Sirve para validar imágenes 
function validar_formato($file)
{
    switch ($file['txt_copia_cambiar_foto']['type']) {
        case 'image/jpeg':
        case 'image/pjpeg':
        case 'image/gif':
        case 'image/png':
        case 'image/jpg':
            return 1;
            break;
        default:
            return -1;
            break;
    }
}
}