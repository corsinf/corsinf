<?php
/* ----------------------------------------------------------------

Todo lo relacionado con IDUKAY especificamente de la libreria
IDUKAY 
--SIN TENER RELACION CON CONFIGURACIONES

----------------------------------------------------------------*/

require_once(dirname(__DIR__, 1) . '/modelo/cat_configuracionGM.php');

//Idukay
//Sirve para hacer la llamada a la API
require_once(dirname(__DIR__, 1) . '/lib/IDUKAY/Querys.php');
//Sirve para llamar a las funciones predefinidas 
require_once(dirname(__DIR__, 1) . '/lib/IDUKAY/main.php');
//Para llamar a la libreria donde se va a ejecutar los comandos de CRON
require_once(dirname(__DIR__, 1) . '/lib/IDUKAY/idukay_actualizacion_datos.php');
//Para buscar el id de IDUKAY de un estudiante
require_once(dirname(__DIR__, 1) . '/modelo/estudiantesM.php');



$controlador = new cat_configuracionGC_IDUKAY();

if (isset($_GET['idukay_estudiantes'])) {
    echo ($controlador->cargarEstudiantesIdukay());
    //print_r($_POST['parametros']); exit;
}

if (isset($_GET['idukay_actualizar_estudiante'])) {
    echo json_encode($controlador->actualizarEstudiantesIdukay($_POST['id_estudiante']));
}

if (isset($_GET['idukay_representantes'])) {
    echo ($controlador->cargarRepresentantesIdukay());
    //print_r($_POST['parametros']); exit;
}

if (isset($_GET['idukay_sincronizar'])) {
    echo ($controlador->sincronizarIdukay());
    //print_r($_POST['parametros']); exit;
}

if (isset($_GET['listar_idukay_estudiantes'])) {
    echo json_encode($controlador->consultaIdukayEstudiantes_listar());
}

if (isset($_GET['idukay_docentes'])) {
    echo json_encode($controlador->cargarDocentesIdukay());
}

if (isset($_GET['idukay_horario_docentes'])) {
    echo json_encode($controlador->cargarHorariosDocentesIdukay());
}

if (isset($_GET['crear_documentos_CRON'])) {
    echo json_encode($controlador->crear_documentos_CRON());
}

if (isset($_GET['ejecutar_PW_programador_tareas'])) {
    echo ($controlador->ejecutar_PW_programador_tareas());
}

if (isset($_GET['leer_archivo_log'])) {
    echo ($controlador->leer_archivo_log());
}



class cat_configuracionGC_IDUKAY
{
    private $modelo;
    private $cod_global;

    //Idukay
    private $Idukay_API;
    private $url;
    private $barear_Token;
    private $anio_lectivo;
    private $idukay_motor;

    private $desarrollo_idukay; //Para poner desarrollo a los correos
    private $estudiante_paralelos_idukay; //Sirve para poner los estudiantes que ya tienen asignado un paralelo



    function __construct()
    {
        $this->modelo = new cat_configuracionGM();
        $this->cod_global = new codigos_globales();

        //IDUKAY
        // Asegúrate de que las variables de sesión estén definidas antes de usarlas
        if (isset($_SESSION['INICIO'])) {
            $this->url = $_SESSION['INICIO']['IDUKAY_URL'] ?? '.';
            $this->barear_Token = $_SESSION['INICIO']['IDUKAY_TOKEN'] ?? '.';
            $this->anio_lectivo = $_SESSION['INICIO']['IDUKAY_ANIO_LEC'] ?? '.';

            // Inicializa los objetos relacionados con IDUKAY
            $this->Idukay_API = new Querys($this->url, $this->barear_Token, $this->anio_lectivo);
            $this->idukay_motor = new main($this);
        } else {
            // Manejo de errores si la sesión no está definida
            throw new Exception("La sesión 'INICIO' no está definida.");
        }

        $validacion_generalGC_desarrollo_idukay = $this->modelo->validacion('desarrollo_idukay');

        $this->desarrollo_idukay = '';
        if ($validacion_generalGC_desarrollo_idukay == 1) {
            $this->desarrollo_idukay = 'desarrollo_';
        }

        $validacion_generalGC_estudiantes_paralelos_idukay = $this->modelo->validacion('estudiantes_paralelos_idukay');

        $this->estudiante_paralelos_idukay = '';
        if ($validacion_generalGC_estudiantes_paralelos_idukay == 1) {
            $this->estudiante_paralelos_idukay = true;
        } else {
            $this->estudiante_paralelos_idukay = false;
        }
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////

    //API IDUKAY - Insertar

    //////////////////////////////////////////////////////////////////////////////////////////////////

    //Sincroniza estudiantes y representantes
    function sincronizarIdukay()
    {
        $estudiantes = $this->cargarEstudiantesIdukay();

        if ($estudiantes != 1) {
            return -1;
            exit();
        }

        $representantes = $this->cargarRepresentantesIdukay();
        if ($representantes != 1) {
            return -2;
            exit();
        }

        return 1;
    }

    //$estudiatesUP -> sirve para poner todos los estudiantes con estado = 1, 
    //para que se muestre en caso de que no tenga paralelo asignado
    function cargarEstudiantesIdukay()
    {
        $data = $this->Idukay_API->lista_Estudiante();
        $selecionar_anio_lectivo = $this->anio_lectivo;
        $desarrollo_idukay = $this->desarrollo_idukay;
        $estudiatesUP = $this->estudiante_paralelos_idukay;

        return $this->idukay_motor->cargarEstudiantesIdukay($data, $selecionar_anio_lectivo, $desarrollo_idukay, $estudiatesUP);
    }

    function actualizarEstudiantesIdukay($id_estudiante)
    {
        $estudiante = new estudiantesM();
        $estudiante = $estudiante->lista_estudiantes($id_estudiante);
        $estudiante = $estudiante[0]['sa_id_est_idukay'];

        //return($estudiante) ;exit();die();

        $data = $this->Idukay_API->lista_Estudiante_especifico($estudiante);
        $selecionar_anio_lectivo = $this->anio_lectivo;
        $desarrollo_idukay = $this->desarrollo_idukay;
        $estudiatesUP = $this->estudiante_paralelos_idukay;

        return $this->idukay_motor->cargarEstudiantesIdukay($data, $selecionar_anio_lectivo, $desarrollo_idukay, $estudiatesUP);
    }

    function cargarRepresentantesIdukay()
    {
        $data = $this->Idukay_API->lista_Padres();
        $desarrollo_idukay = $this->desarrollo_idukay;

        return $this->idukay_motor->cargarRepresentantesIdukay($data, $desarrollo_idukay);
    }

    function cargarDocentesIdukay()
    {
        $data = $this->Idukay_API->lista_Docentes();
        $desarrollo_idukay = $this->desarrollo_idukay;

        return $this->idukay_motor->cargarDocentesIdukay($data, $desarrollo_idukay);
    }

    function cargarHorariosDocentesIdukay()
    {
        $data = $this->Idukay_API->lista_HorariosDocentes();

        return $this->idukay_motor->cargarHorariosDocentesIdukay($data);
    }

    function consultaIdukayEstudiantes_listar()
    {
        $estudiante = $this->Idukay_API->lista_Estudiante();
        //$estudiante = $this->Idukay_API->lista_Padres();

        return ($estudiante);
    }

    ///////////////////////////////////////////
    /*SQL base para la conexion de la bdd*/
    ///////////////////////////////////////////

    function sql_string($sql)
    {
        return $this->modelo->sql_string($sql);
    }

    /////////////////////////////////////////////////////

    /*

    Funciones para ejecucion de cron

    */

    function crear_documentos_CRON()
    {

        $usuario = $_SESSION['INICIO']['USUARIO_DB'] ?? '.'; //'sa';
        $password = $_SESSION['INICIO']['PASSWORD_DB'] ?? '.'; //'Tango456';
        $servidor = $_SESSION['INICIO']['IP_HOST'] . ', ' .  $_SESSION['INICIO']['PUERTO_DB'] ?? '.'; //'186.4.219.172, 1487';
        $database = $_SESSION['INICIO']['BASEDATO'] ?? '.'; //'SALUD_DESARROLLO';
        $puerto = '';

        //print_r($database); exit(); die();

        $proceso = new idukay_actualizacion_datos($usuario, $password, $servidor, $database, $puerto);

        //$proceso->cargarEstudiantesIdukay();

        // Prueba para crear log
        $proceso->crearLOG('Creando archivo .bat .php .txt .ps1');
        $proceso->crearArchivoBatCRON();
        $proceso->crearArchivoPHP();
        $proceso->crearArchivoPS1();

        return 1;
    }

    function ejecutar_PW_programador_tareas()
    {
        //Poner variables de la BDD
        $usuario = $_SESSION['INICIO']['USUARIO_DB'] ?? '.'; //'sa';
        $password = $_SESSION['INICIO']['PASSWORD_DB'] ?? '.'; //'Tango456';
        $servidor = $_SESSION['INICIO']['IP_HOST'] . ', ' .  $_SESSION['INICIO']['PUERTO_DB'] ?? '.'; //'186.4.219.172, 1487';
        $database = $_SESSION['INICIO']['BASEDATO'] ?? '.'; //'SALUD_DESARROLLO';
        $puerto = '';

        $proceso = new idukay_actualizacion_datos($usuario, $password, $servidor, $database, $puerto);

        //$proceso->cargarEstudiantesIdukay();

        // Prueba para crear log

        return $proceso->ejecutarPW_programador_tareas();

        //return 'no manches';
    }

    function leer_archivo_log()
    {
        $nombre_modulo = '';
        $nombre_empresa = '';
        $url_guardar_bat = '';
        $script_php_motor = '';
        $motor_bat = '';
        $hora_ejecucion_PW = '';

        // Ejecuta la consulta SQL
        $sql_1 = "SELECT sa_config_validar, sa_config_valor FROM cat_configuracionG WHERE sa_config_nombre = 'idukay_cron';";

        // Obtiene los datos de la base de datos
        $datos = $this->modelo->datos($sql_1);

        // Verifica si se obtuvieron datos y asigna valores directamente
        if (is_array($datos) && !empty($datos)) {
            foreach ($datos as $fila) {
                $validar = $fila['sa_config_validar'];
                $valor = $fila['sa_config_valor'];

                // Asigna los valores correspondientes a las propiedades de la clase
                if ($validar === 'nombre_modulo') {
                    $nombre_modulo = $valor;
                } elseif ($validar === 'nombre_empresa') {
                    $nombre_empresa = $valor;
                } elseif ($validar === 'url_guardar_bat') {
                    $url_guardar_bat = $valor;
                } elseif ($validar === 'script_php_motor') {
                    $script_php_motor = $valor;
                } elseif ($validar === 'motor_bat') {
                    $motor_bat = $valor;
                } elseif ($validar === 'hora_ejecucion_PW') {
                    $hora_ejecucion_PW = $valor;
                }
            }
        }

        // Definir el directorio y el nombre del archivo
        $directory = $url_guardar_bat . "\\" . $nombre_modulo . "\\" . $nombre_empresa;
        $filename = $directory . "\\" . 'log_idukay.txt';


        // Abrir el archivo para lectura
        $handle = fopen($filename, 'r');

        if ($handle) {
            // Leer cada línea del archivo
            while (($line = fgets($handle)) !== false) {
                echo $line . '</br>';
            }

            // Cerrar el archivo
            fclose($handle);
        } else {
            echo "Error al abrir el archivo.";
        }
    }
}
