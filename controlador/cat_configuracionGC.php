<?php
include('../modelo/cat_configuracionGM.php');

//Idukay
include('../lib/IDUKAY/Querys.php');

//Estudiantes
include('../modelo/estudiantesM.php');

$controlador = new cat_configuracionGC();

//Para mostrar todos los registros con campos especificos para la vista principal
if (isset($_GET['listar_config_general'])) {
    echo json_encode($controlador->lista_vista_med_ins());
}

if (isset($_GET['vista_mod'])) {
    echo json_encode($controlador->editar($_POST['parametros']));
    //print_r($_POST['parametros']); exit;
}

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

class cat_configuracionGC
{
    private $modelo;
    private $Idukay_API;
    private $estudiantes;

    private $url = 'https://staging.idukay.net/api';
    private $barear_Token = 'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJ3b3JraW5nX3NjaG9vbCI6IjVjZDM3OTY1MDQwOTJlYTUwNDgxMmZmOCIsInRpbWVfem9uZSI6Ii0wNTowMCIsImlhdCI6MTY5NDY5OTg4OX0.Ya0sIq6xQ-XNkevoJuFnGqRrZvdVHQ-Oz7OiwfaB7lOqrbkPErJdOETZ_ZPyb_BszA2kcaztGKakw5U5izAxO_15k7OejZ_L4EsQp3F4_EpRMEQooTznquuxxWelxsSvz8Fkv9RmGvoNLNbB4Sllt9X_if4PCXZ0zaJWRfw2MD1uASbtFC7JjkjZYXzXNqzXyoZkm-OFKEtctfwHUHNPWYootRdzpDZ0zBWWTMP4JG7XZlEouusoxgy-0lVzq-n0GmD6EgGWzNa1Jkl6c6DUBkzRdxaLTn1jZX6vvqM00-7x_Su6k_mbs-Zf1loUvKKzx3qInXxUOhcR9kLKS6XnDg';
    function __construct()
    {
        $this->modelo = new cat_configuracionGM();

        $this->Idukay_API = new Querys($this->url, $this->barear_Token);

        $this->estudiantes = new estudiantesM();
    }

    function lista_vista_med_ins()
    {
        $datos = $this->modelo->lista_vista_conf_general();
        return $datos;
    }

    function editar($parametros)
    {
        $datos = array(
            array('campo' => 'sa_config_estado', 'dato' => $parametros['sa_config_estado']),
        );

        $where[0]['campo'] = 'sa_config_id';
        $where[0]['dato'] = $parametros['sa_config_id'];
        $datos = $this->modelo->editar($datos, $where);

        return $datos;
    }








    //////////////////////////////////////////////////////////////////////////////////////////////////

    //API IDUKAY

    //////////////////////////////////////////////////////////////////////////////////////////////////

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

    function cargarEstudiantesIdukay()
    {
        $data = $this->Idukay_API->lista_Estudiante();

        if ($data != -11) {
            $parametros = [];
            foreach ($data['response'] as $item) {

                $sa_est_sexo = $item['user']['gender'] ?? '';

                if ($sa_est_sexo === 'M') {
                    $sa_est_sexo = 'Masculino';
                } elseif ($sa_est_sexo === 'F') {
                    $sa_est_sexo = 'Femenino';
                }

                $parametros[] = [
                    'sa_est_primer_apellido' => $item['user']['surname'] ?? '',
                    'sa_est_segundo_apellido' => $item['user']['second_surname'] ?? '',
                    'sa_est_primer_nombre' => $item['user']['name'] ?? '',
                    'sa_est_segundo_nombre' => $item['user']['second_name'] ?? '',
                    'sa_est_cedula' => $item['user']['id_card'] ?? '',
                    'sa_est_sexo' => $sa_est_sexo,
                    'sa_est_fecha_nacimiento' => $item['user']['birthday'] ?? '',
                    'sa_id_representante' => -1,
                    'sa_est_correo' => $item['user']['email'] ?? '',
                    'sa_est_direccion' => $item['user']['address'] ?? '',
                    'sa_id_seccion' => 1,
                    'sa_id_grado' => 1,
                    'sa_id_paralelo' => 1,

                    'sa_id_est_idukay' => $item['_id'],

                    'sa_id_rep_idukay' => $item['relatives'][0]['parent'] ?? '',
                    'sa_est_rep_parentesco' => $item['relatives'][0]['relationship'] ?? '',

                    'sa_id_rep_idukay_2' => $item['relatives'][1]['parent'] ?? '',
                    'sa_est_rep_parentesco_2' => $item['relatives'][1]['relationship'] ?? '',

                ];
            }

            //print_r($parametros); exit();die();

            $datos = array();
            foreach ($parametros as $parametro) {
                $datos[] = array(
                    array('campo' => 'sa_est_primer_apellido', 'dato' => $parametro['sa_est_primer_apellido']),
                    array('campo' => 'sa_est_segundo_apellido', 'dato' => $parametro['sa_est_segundo_apellido']),
                    array('campo' => 'sa_est_primer_nombre', 'dato' => $parametro['sa_est_primer_nombre']),
                    array('campo' => 'sa_est_segundo_nombre', 'dato' => $parametro['sa_est_segundo_nombre']),
                    array('campo' => 'sa_est_cedula', 'dato' => $parametro['sa_est_cedula']),
                    array('campo' => 'sa_est_sexo', 'dato' => $parametro['sa_est_sexo']),
                    array('campo' => 'sa_est_fecha_nacimiento', 'dato' =>  date('Y-m-d', $parametro['sa_est_fecha_nacimiento']) ?? ''),
                    array('campo' => 'sa_id_seccion', 'dato' => $parametro['sa_id_seccion']),
                    array('campo' => 'sa_id_grado', 'dato' => $parametro['sa_id_grado']),
                    array('campo' => 'sa_id_paralelo', 'dato' => $parametro['sa_id_paralelo']),
                    array('campo' => 'sa_id_representante', 'dato' => $parametro['sa_id_representante']),
                    array('campo' => 'sa_est_rep_parentesco', 'dato' => $parametro['sa_est_rep_parentesco']),
                    array('campo' => 'sa_est_correo', 'dato' => $parametro['sa_est_correo']),
                    array('campo' => 'sa_est_direccion', 'dato' => $parametro['sa_est_direccion']),

                    array('campo' => 'sa_id_est_idukay', 'dato' => $parametro['sa_id_est_idukay']),
                    array('campo' => 'sa_id_rep_idukay', 'dato' => $parametro['sa_id_rep_idukay']),

                    array('campo' => 'sa_id_rep_idukay_2', 'dato' => $parametro['sa_id_rep_idukay_2']),
                    array('campo' => 'sa_est_rep_parentesco_2', 'dato' => $parametro['sa_est_rep_parentesco_2']),
                );
                //break;
            }

            //print_r($datos);

            // Dividir los datos en grupos de 300
            $grupos = array_chunk($datos, 300);

            // Insertar cada grupo en la base de datos
            $contador = 0;
            foreach ($grupos as $grupo) {
                $sql = array();

                foreach ($grupo as $dato) {
                    $sql[] = array($this->inserts('estudiantes', $dato));
                }

                // Construir la sentencia SQL para el grupo actual
                $sentenciaSql = '';
                foreach ($sql as $dato) {
                    foreach ($dato as $consulta) {
                        $sentenciaSql .= $consulta . " ";
                    }
                }

                // Ejecutar la inserción del grupo actual
                $resultado = $this->estudiantes->cargaMasivaIdukay($sentenciaSql);

                // Verificar el resultado
                if ($resultado !== 1) {
                    // Manejar el error o detener el proceso si falla la inserción
                    //echo "Error al insertar grupo de datos en la base de datos.";
                    return -10;
                    break;
                } else {
                    $contador++;
                }
            }


            return 1;
        } else {
            return -11;
        }
    }

    function cargarRepresentantesIdukay()
    {
        $data = $this->Idukay_API->lista_Padres();

        if ($data != -11) {
            $parametros = [];
            foreach ($data['response'] as $item) {

                $sa_est_sexo = $item['user']['gender'] ?? '';

                if ($sa_est_sexo === 'M') {
                    $sa_est_sexo = 'Masculino';
                } elseif ($sa_est_sexo === 'F') {
                    $sa_est_sexo = 'Femenino';
                }

                $parametros[] = [
                    'sa_rep_primer_apellido' => $item['user']['surname'] ?? '',
                    'sa_rep_segundo_apellido' => $item['user']['second_surname'] ?? '',
                    'sa_rep_primer_nombre' => $item['user']['name'] ?? '',
                    'sa_rep_segundo_nombre' => $item['user']['second_name'] ?? '',
                    'sa_rep_cedula' => $item['user']['id_card'] ?? '',
                    'sa_rep_sexo' => $sa_est_sexo,
                    'sa_rep_fecha_nacimiento' => $item['user']['birthday'] ?? '',
                    'sa_rep_correo' => $item['user']['email'] ?? '',
                    'sa_rep_telefono_1' => $item['phones']['mobile']['number'] ?? '',
                    'sa_rep_telefono_2' => '',
                    'sa_rep_direccion' => $item['user']['address'] ?? '',

                    'sa_id_rep_idukay' => $item['_id'],
                ];
            }

            //print_r($parametros);

            $datos = array();
            foreach ($parametros as $parametro) {

                $fecha_nacimiento = '1900-01-12';
                if ($parametro['sa_rep_fecha_nacimiento'] != null && $parametro['sa_rep_fecha_nacimiento'] != '') {
                    $fecha_nacimiento = date('Y-m-d', $parametro['sa_rep_fecha_nacimiento']);
                }

                $datos[] = array(
                    array('campo' => 'sa_rep_primer_apellido', 'dato' => $parametro['sa_rep_primer_apellido']),
                    array('campo' => 'sa_rep_segundo_apellido', 'dato' => $parametro['sa_rep_segundo_apellido']),
                    array('campo' => 'sa_rep_primer_nombre', 'dato' => $parametro['sa_rep_primer_nombre']),
                    array('campo' => 'sa_rep_segundo_nombre', 'dato' => $parametro['sa_rep_segundo_nombre']),
                    array('campo' => 'sa_rep_cedula', 'dato' => $parametro['sa_rep_cedula']),
                    array('campo' => 'sa_rep_sexo', 'dato' => $parametro['sa_rep_sexo']),
                    array('campo' => 'sa_rep_fecha_nacimiento', 'dato' => $fecha_nacimiento),
                    array('campo' => 'sa_rep_correo', 'dato' => $parametro['sa_rep_correo']),
                    array('campo' => 'sa_rep_telefono_1', 'dato' => $parametro['sa_rep_telefono_1']),
                    array('campo' => 'sa_rep_telefono_2', 'dato' => $parametro['sa_rep_telefono_2']),
                    array('campo' => 'sa_rep_direccion', 'dato' => $parametro['sa_rep_direccion']),

                    array('campo' => 'PASS', 'dato' => md5($parametro['sa_rep_cedula'])),

                    array('campo' => 'sa_id_rep_idukay', 'dato' => $parametro['sa_id_rep_idukay']),
                );

                //break;
            }

            //print_r($datos);

            // Dividir los datos en grupos de 300
            $grupos = array_chunk($datos, 150);

            // Insertar cada grupo en la base de datos
            $contador = 0;
            foreach ($grupos as $grupo) {
                $sql = array();

                foreach ($grupo as $dato) {
                    $sql[] = array($this->inserts('representantes', $dato));
                }

                // Construir la sentencia SQL para el grupo actual
                $sentenciaSql = '';
                foreach ($sql as $dato) {
                    foreach ($dato as $consulta) {
                        $sentenciaSql .= $consulta . " ";

                        // echo $consulta . "-- " . $contador . "<br/> ";
                        // $contador++;
                    }
                }

                // Ejecutar la inserción del grupo actual
                $resultado = $this->estudiantes->cargaMasivaIdukay($sentenciaSql);

                // Verificar el resultado
                if ($resultado !== 1) {
                    // Manejar el error o detener el proceso si falla la inserción
                    //echo "Error al insertar grupo de datos en la base de datos.";
                    return -10;
                    break;
                } else {
                    $contador++;
                }
            }

            $query = $this->estudiantes->ponerRepresentantesEstudiantes();
            return $query;
        } else {
            return -11;
        }
    }

    function consultaIdukayEstudiantes_listar()
    {
        $estudiante = $this->Idukay_API->lista_Estudiante();
        //$estudiante = $this->Idukay_API->lista_Padres();

        return ($estudiante);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /*
    Funciones para carga masiva
    */

    function inserts($tabla, $datos)
    {
        $valores = '';
        $campos = '';
        $sql = 'INSERT INTO ' . $tabla;

        foreach ($datos as $key => $value) {
            $campos .= $value['campo'] . ',';
            if (is_numeric($value['dato'])) {
                if (isset($value['tipo']) && strtoupper($value['tipo']) == 'STRING') {
                    $valores .= "'" . $value['dato'] . "',";
                } else {
                    $valores .= str_replace(',', '', $value['dato']) . ',';
                }
            } else {
                $valores .= "'" . str_replace(',', '', $value['dato']) . "',";
            }
        }

        $campos = rtrim($campos, ',');
        $valores = rtrim($valores, ',');

        $sql .= '(' . $campos . ') VALUES (' . $valores . ');';
        return $sql;
    }
}


//Para detectar errores en el query generado 
/*$grupos = array_chunk($datos, 500);

// Insertar el primer grupo en la base de datos
$contador = 0;

// Obtener el primer grupo
$primer_grupo = $grupos[1];

// Inicializar un array para las consultas SQL
$sql = array();

// Generar las consultas SQL para el primer grupo
foreach ($primer_grupo as $dato) {
    $sql[] = $this->inserts('representantes', $dato);
}

// Construir la sentencia SQL para el primer grupo y imprimirla
$sentenciaSql = '';
foreach ($sql as $consulta) {
    echo $consulta . "-- " . $contador . "<br><br>";
    $contador++;
}*/
