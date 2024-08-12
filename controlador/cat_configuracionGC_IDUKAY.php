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


$controlador = new cat_configuracionGC_IDUKAY();

if (isset($_GET['idukay_estudiantes'])) {
    echo ($controlador->cargarEstudiantesIdukay());
    //print_r($_POST['parametros']); exit;
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



class cat_configuracionGC_IDUKAY
{
    private $modelo;
    private $cod_global;

    //Idukay
    private $Idukay_API;
    private $url;
    private $barear_Token;
    private $anio_lectivo;
    private $desarrollo_idukay;
    private $idukay_motor;


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

        $validacion_generalGC = $this->modelo->validacion('desarrollo_idukay');

        $this->desarrollo_idukay = '';
        if ($validacion_generalGC == 1) {
            $this->desarrollo_idukay = 'desarrollo_';
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
        $estudiatesUP = false;

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
        //Poner variables de la BDD
        $usuario = 'sa';
        $password = 'Tango456';
        $servidor = '186.4.219.172, 1487';
        $database = 'SALUD_DESARROLLO';
        $puerto = '';

        $proceso = new idukay_actualizacion_datos($usuario, $password, $servidor, $database, $puerto);

        //$proceso->cargarEstudiantesIdukay();

        // Prueba para crear log
        $proceso->crearLOG('Creando archivo .bat .php .txt .ps1');
        $proceso->crearArchivoBatCRON();
        $proceso->crearArchivoPHP();
        $proceso->crearArchivoPS1();

        return true;
    }

    function ejecutar_PW_programador_tareas()
    {
        //Poner variables de la BDD
        $usuario = 'sa';
        $password = 'Tango456';
        $servidor = '186.4.219.172, 1487';
        $database = 'SALUD_DESARROLLO';
        $puerto = '';

        $proceso = new idukay_actualizacion_datos($usuario, $password, $servidor, $database, $puerto);

        //$proceso->cargarEstudiantesIdukay();

        // Prueba para crear log

        return $proceso->ejecutarPW_programador_tareas();

        //return 'no manches';
    }
}
