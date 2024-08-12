<?php

/**
 *
 * Motor para actualzar IDUKAY
 * Libreria para actualizar y realizar los procesos de Idukay
 *
 */

require_once(dirname(__DIR__, 2) . '/db/db.php');

//Sirve para hacer la llamada a la API
require_once(dirname(__DIR__, 2) . '/lib/IDUKAY/Querys.php');
//Sirve para llamar a las funciones predefinidas 
require_once(dirname(__DIR__, 2) . '/lib/IDUKAY/main.php');



class idukay_actualizacion_datos
{
    protected $db;

    //Idukay conexion 
    private $Idukay_API;
    private $url;
    private $barear_Token;
    private $anio_lectivo;

    private $datos_empresa;
    private $datos_config_cron = array();

    private $nombre_modulo; //= 'ENFERMERIA';
    private $nombre_empresa; //= 'SALUD_DESARROLLO';
    private $url_guardar_bat; //= 'C:\xampp\htdocs\corsinf\CRON';
    private $script_php_motor; //= 'idukay_actualizacion_datos_saint.php';
    private $motor_bat; //= 'SD';

    private $idukay_motor;

    private $usuario;
    private $password;
    private $servidor;
    private $database;
    private $puerto;

    function __construct($usuario, $password, $servidor, $database, $puerto)
    {
        $this->usuario = $usuario;
        $this->password = $password;
        $this->servidor = $servidor;
        $this->database = $database;
        $this->puerto = $puerto;

        $this->db = new db();

        //IDUKAY
        // Asegúrate de que las variables de sesión estén definidas antes de usarlas
        if ($this->conexionEmpresa()) {
            //Datos que vinen desde la tabla empresa para la conexion de idukay
            $this->datos_empresa = $this->datosEmpresa();
            //Aqui se almacena las variables para generar el cron 
            $this->datosArchivosCRON();

            $this->url = $this->datos_empresa['url_api_idukay'] ?? '.';
            $this->barear_Token = $this->datos_empresa['token_idukay'] ?? '.';
            $this->anio_lectivo = $this->datos_empresa['anio_lectivo_idukay'] ?? '.';

            //$this->conn = $this->conexionEmpresa($usuario, $password, $servidor, $database, $puerto);


            // Inicializa los objetos relacionados con IDUKAY
            $this->Idukay_API = new Querys($this->url, $this->barear_Token, $this->anio_lectivo);
            $this->idukay_motor = new main($this);
        } else {
            // Manejo de errores si la sesión no está definida
            throw new Exception("La sesión 'INICIO' no está definida.");
        }
    }

    /***
     * 
     * 
     * Para estudiantes
     * 
     */

    //$estudiatesUP -> sirve para poner todos los estudiantes con estado = 1, 
    //para que se muestre en caso de que no tenga paralelo asignado
    function cargarEstudiantesIdukay()
    {
        $this->crearLOG('Sincronización de Estudiantes con Idukay inicio');

        $data = $this->Idukay_API->lista_Estudiante();
        $selecionar_anio_lectivo = $this->anio_lectivo;
        $desarrollo_idukay = 1; //$this->desarrollo_idukay;
        $estudiatesUP = false;

        $salida = $this->idukay_motor->cargarEstudiantesIdukay($data, $selecionar_anio_lectivo, $desarrollo_idukay, $estudiatesUP);

        if ($salida = 1) {
            $this->crearLOG('Sincronización de Estudiantes con Idukay fin -> Status 200');
            return $salida;
        } else {
            $this->crearLOG('Sincronización de Estudiantes con Idukay fin -> Status 502');
            return $salida;
        }
    }



    function crearLOG($variable)
    {
        date_default_timezone_set('America/Guayaquil');
        // Nombre del archivo con ruta absoluta
        $directory = 'C:\xampp\htdocs\corsinf\CRON\ENFERMERIA\SALUD_DESARROLLO';
        $filename = $directory . '\log_idukay.txt';

        // Crear el directorio si no existe
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Obtener la hora actual
        $current_time = date('Y-m-d H:i:s');

        // Contenido a agregar
        $content = "Hora de creación: $current_time " . $variable . "\n";

        // Agregar el contenido al archivo (lo crea si no existe)
        file_put_contents($filename, $content, FILE_APPEND);

        //echo "El registro ha sido agregado al archivo $filename\n";
    }

    function crearArchivoBatCRON()
    {

        $nombre_modulo = $this->nombre_modulo; //ENFERMERIA
        $nombre_empresa = $this->nombre_empresa; //SALUD_DESARROLLO
        $url_guardar_bat = $this->url_guardar_bat; //C:\xampp\htdocs\corsinf\CRON
        $script_php_motor = $this->script_php_motor; //idukay_actualizacion_datos_saint.php
        $motor_bat = $this->motor_bat; //idukay_actualizacion_datos_saint.php

        // $directory = "C:\xampp\htdocs\corsinf\CRON\'" . $nombre_modulo . "'\SALUD_DESARROLLO";

        $directory = $url_guardar_bat . "\\" . $nombre_modulo . "\\" . $nombre_empresa;


        $directory_motor = $url_guardar_bat . "\\" . $nombre_modulo . "\\" . $nombre_empresa;
        $filename = $directory . '\ejecutar_idukay_' . $motor_bat . '.bat';

        // Crear el directorio si no existe
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Contenido del archivo .bat
        $content = "@echo off\n";
        $content .= "php " . $directory_motor . "\\" . $script_php_motor . "\n";
        $content .= "exit\n";

        // Crear el archivo .bat y agregar el contenido
        file_put_contents($filename, $content);
    }

    function crearArchivoPHP()
    {
        $nombre_modulo = $this->nombre_modulo; // ENFERMERIA
        $nombre_empresa = $this->nombre_empresa; // SALUD_DESARROLLO
        $url_guardar_bat = $this->url_guardar_bat; // C:\xampp\htdocs\corsinf\CRON
        $script_php_motor = $this->script_php_motor; // idukay_actualizacion_datos_saint.php

        // Definir el directorio y el nombre del archivo
        $directory = $url_guardar_bat . "\\" . $nombre_modulo . "\\" . $nombre_empresa;
        $filename = $directory . "\\" . $script_php_motor;

        // Crear el directorio si no existe
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Contenido del archivo .php
        $content = <<<PHP
                    <?php
                    
                    require_once(dirname(__DIR__, 3) . '/lib/IDUKAY/idukay_actualizacion_datos.php');
                    
                    \$usuario = 'sa';
                    \$password = 'Tango456';
                    \$servidor = '186.4.219.172, 1487';
                    \$database = 'SALUD_DESARROLLO';
                    \$puerto = '';
                    
                    // Crear una instancia de la clase y llamar al método
                    \$proceso = new idukay_actualizacion_datos(\$usuario, \$password, \$servidor, \$database, \$puerto);
                    
                    //\$proceso->cargarEstudiantesIdukay();
                    \$proceso->crearLOG('estudiantes');
                    
                
                    PHP;

        // Crear el archivo .php y agregar el contenido
        file_put_contents($filename, $content);
    }

    function crearArchivoPS1()
    {
        $nombre_modulo = $this->nombre_modulo; // ENFERMERIA
        $nombre_empresa = $this->nombre_empresa; // SALUD_DESARROLLO
        $url_guardar_bat = $this->url_guardar_bat; // C:\xampp\htdocs\corsinf\CRON
        $script_php_motor = $this->script_php_motor; // idukay_actualizacion_datos_saint.php
        $motor_bat = $this->motor_bat; //idukay_actualizacion_datos_saint.php


        // Definir el directorio y el nombre del archivo
        $directory = $url_guardar_bat . "\\" . $nombre_modulo . "\\" . $nombre_empresa;
        $scriptPath = $directory . '\ejecutar_idukay_' . $motor_bat . '.ps1';
        $filename = $directory . '\ejecutar_idukay_' . $motor_bat . '.bat';

        // Crear el directorio si no existe
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }


        $content = <<<PS
                        \$action = New-ScheduledTaskAction -Execute $filename
                        \$trigger = New-ScheduledTaskTrigger -Daily -At 17:06
                        \$principal = New-ScheduledTaskPrincipal -UserId "SYSTEM" -LogonType ServiceAccount
                        Register-ScheduledTask -Action \$action -Trigger \$trigger -Principal \$principal -TaskName "CORSINF_Motor_bat_$nombre_empresa" -Description "Actualización de datos desde IDUKAY - CORSINF"
                    PS;

        // Crear el archivo .ps1 y agregar el contenido
        file_put_contents($scriptPath, $content);
    }

    function ejecutarPW_programador_tareas()
    {
        $nombre_modulo = $this->nombre_modulo; // ENFERMERIA
        $nombre_empresa = $this->nombre_empresa; // SALUD_DESARROLLO
        $url_guardar_bat = $this->url_guardar_bat; // C:\xampp\htdocs\corsinf\CRON
        $script_php_motor = $this->script_php_motor; // idukay_actualizacion_datos_saint.php
        $motor_bat = $this->motor_bat; //idukay_actualizacion_datos_saint.php

        // Definir el directorio y el nombre del archivo
        $directory = $url_guardar_bat . "\\" . $nombre_modulo . "\\" . $nombre_empresa;
        $filename = $directory . '\ejecutar_idukay_' . $motor_bat . '.ps1';

        // Ruta al script de PowerShell
        $scriptPath = $filename;//'C:\\xampp\\htdocs\\pruebas_cron\\CrearTarea.ps1';

        // Comando para ejecutar el script de PowerShell
        $command = "powershell -ExecutionPolicy Bypass -File \"$scriptPath\" 2>&1";

        // Ejecutar el comando
        $output = shell_exec($command);

        // Mostrar el resultado de la ejecución
        return "<pre>$output</pre>";
    }

    //Prueba para llegar al automatizado
    function crearArchivoBat()
    {
        $directory = 'C:\xampp\htdocs\corsinf\CRON\ENFERMERIA\SALUD_DESARROLLO';
        $filename = $directory . '\ejecutar_idukay.bat';

        // Crear el directorio si no existe
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Contenido del archivo .bat
        $content = "@echo off\n";
        $content .= "php C:\\xampp\\htdocs\\corsinf\\CRON\\ENFERMERIA\\idukay_actualizacion_datos_saint.php\n";
        $content .= "exit\n";

        // Crear el archivo .bat y agregar el contenido
        file_put_contents($filename, $content);
    }


    /***********************************************************************************
     * 
     * 
     * Fucniones para conexion de la empresa
     * 
     * 
     ***********************************************************************************/

    //Para tomar los valores de idukay
    public function conexionEmpresa()
    {
        $usuario = $this->usuario;
        $password = $this->password;
        $servidor = $this->servidor;
        $database = $this->database;
        $puerto = $this->puerto;

        $con = $this->db->conexion_db_terceros(
            $database,
            $usuario,
            $password,
            $servidor,
            $puerto
        );

        return $con;
    }

    public function datosEmpresa()
    {
        // Si los datos ya han sido cargados, retorna los datos almacenados
        if (!isset($this->datos_empresa)) {
            $sql_1 = "SELECT * FROM empresas;";

            $datos = $this->datos($sql_1, $this->conexionEmpresa());

            $this->datos_empresa = [
                'url_api_idukay' => $datos[0]['url_api_idukay'],
                'token_idukay' => $datos[0]['token_idukay'],
                'anio_lectivo_idukay' => $datos[0]['anio_lectivo_idukay'],
            ];
        }

        return $this->datos_empresa;
    }

    //Aqui se almacena las variables para generar el cron 
    public function datosArchivosCRON()
    {
        // Ejecuta la consulta SQL
        $sql_1 = "SELECT sa_config_validar, sa_config_valor FROM cat_configuracionG WHERE sa_config_nombre = 'idukay_cron';";

        // Obtiene los datos de la base de datos
        $datos = $this->datos($sql_1, $this->conexionEmpresa());

        // Verifica si se obtuvieron datos y asigna valores directamente
        if (is_array($datos) && !empty($datos)) {
            foreach ($datos as $fila) {
                $validar = $fila['sa_config_validar'];
                $valor = $fila['sa_config_valor'];

                // Asigna los valores correspondientes a las propiedades de la clase
                if ($validar === 'nombre_modulo') {
                    $this->nombre_modulo = $valor;
                } elseif ($validar === 'nombre_empresa') {
                    $this->nombre_empresa = $valor;
                } elseif ($validar === 'url_guardar_bat') {
                    $this->url_guardar_bat = $valor;
                } elseif ($validar === 'script_php_motor') {
                    $this->script_php_motor = $valor;
                } elseif ($validar === 'motor_bat') {
                    $this->motor_bat = $valor;
                }
            }
        }
    }

    //Para ejecutar los sql
    function datos($sql, $master = false)
    {
        //$this->parametros_conexion($master);
        $conn = $this->conexionEmpresa();
        $result = array();

        // print_r($sql);
        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $result[] = $row;
            }
            $conn = null;
            return $result;
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }

    function sql_string($sql, $master = false)
    {

        //$this->parametros_conexion($master);
        $conn = $this->conexionEmpresa();
        // print_r($sql);

        try {
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $conn = null;
            return 1;
        } catch (Exception $e) {
            return -1;
            die(print_r(sqlsrv_errors(), true));
        }
    }
}

// $usuario = 'sa';
// $password = 'Tango456';
// $servidor = '186.4.219.172, 1487';
// $database = 'SALUD_DESARROLLO';
// $puerto = '';

// // Crear una instancia de la clase y llamar al método
// $proceso = new idukay_actualizacion_datos_saint($usuario, $password, $servidor, $database, $puerto);

//$proceso->cargarEstudiantesIdukay();

//Prueba para crear log
// $proceso->crearLOG('crearArchivoBat');
// $proceso->crearArchivoBatCRON();
// $proceso->crearArchivoPHP();
