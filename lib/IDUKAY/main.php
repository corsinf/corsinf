<?php

require_once(dirname(__DIR__) . '/IDUKAY/funciones_carga_idukay.php');
require_once(dirname(__DIR__) . '/IDUKAY/sql_idukay.php');
require_once(dirname(__DIR__, 2) . '/db/codigos_globales.php');



class main
{
    private $SQL_idukay;
    private $funciones_carga_idukay;

    //Clase que tenga la funcion sql_string()
    private $clase_llamada;

    //Codigos globales
    private $cod_global;

    function __construct($clase_llamada)
    {
        $this->SQL_idukay = new SQL_idukay();
        $this->funciones_carga_idukay = new funciones_carga_idukay();

        //Sirve para traer los metodos de la clase
        $this->clase_llamada = $clase_llamada;

        $this->cod_global = new codigos_globales();
    }

    /*

    Estudiantes

    */

    //$estudiatesUP -> sirve para poner todos los estudiantes con estado = 1, 
    //para que se muestre en caso de que no tenga paralelo asignado
    function cargarEstudiantesIdukay($data, $selecionar_anio_lectivo, $desarrollo_idukay, $estudiatesUP = false)
    {
        // $data = $this->Idukay_API->lista_Estudiante();
        // $selecionar_anio_lectivo = $this->anio_lectivo;


        //print_r($selecionar_anio_lectivo); exit();die();

        if ($data != -11) { //Validar si llega la data
            $parametros = [];
            foreach ($data['response'] as $item) {

                $sa_est_sexo = $item['user']['gender'] ?? '';

                if ($sa_est_sexo === 'M') {
                    $sa_est_sexo = 'Masculino';
                } elseif ($sa_est_sexo === 'F') {
                    $sa_est_sexo = 'Femenino';
                }

                $sa_est_estado = '';

                /*if ($item['years']['registered'] == true) {
                    $sa_est_estado = '1';
                } else {
                    $sa_est_estado = '0';
                }*/


                /*----------------------------------------------------------------
                Para guardar los curso en otro array y crear un sql 
                /*----------------------------------------------------------------*/

                // Recorrer los datos para la seccion, grado y paralelo
                $grado_seccion = $item['relational_data']['years'][$selecionar_anio_lectivo]['grade']['show'] ?? '-1,-1';
                $grado_seccion_min = $item['relational_data']['years'][$selecionar_anio_lectivo]['grade']['order'] ?? '';

                $paralelo = $item['relational_data']['years'][$selecionar_anio_lectivo]['group']['show'] ?? '-1';
                $paralelo_min = $item['relational_data']['years'][$selecionar_anio_lectivo]['group']['order'] ?? '';

                //$curso_est = $grado_seccion . ', ' . $paralelo;

                if (!empty($grado_seccion)) {
                    $curso_array = array_map('trim', explode(',', $grado_seccion));

                    if (count($curso_array) == 2) {
                        list($grado, $seccion) = $curso_array;
                    } else {
                        // Manejo de error o asignación de valores predeterminados
                        $grado = $seccion = '-1';
                    }
                } else {
                    // Asignación de valores predeterminados si curso_texto está vacío
                    $grado = $seccion = '-1';
                }

                $estado_est = 0;
                if (isset($item['years'])) {
                    $estado_est = '1';
                }

                //Validacion para correo
                $correo = '';
                if (!empty($item['user']['email'])) {
                    $correo = $desarrollo_idukay . $item['user']['email'];
                } else {
                    $correo = $item['user']['email'] ?? '';
                }

                //Alamacenar la data para poder armar el sql
                $parametros[] = [
                    'sa_est_primer_apellido' => $item['user']['surname'] ?? '',
                    'sa_est_segundo_apellido' => $item['user']['second_surname'] ?? '',
                    'sa_est_primer_nombre' => $item['user']['name'] ?? '',
                    'sa_est_segundo_nombre' => $item['user']['second_name'] ?? '',
                    'sa_est_cedula' => $item['user']['id_card'] ?? '',
                    'sa_est_sexo' => $sa_est_sexo,
                    'sa_est_fecha_nacimiento' => $item['user']['birthday'] ?? '',
                    'sa_id_representante' => -1,
                    'sa_est_correo' => $correo,
                    'sa_est_direccion' => $item['user']['address'] ?? '',
                    'sa_id_seccion' => -1,
                    'sa_id_grado' => -1,
                    'sa_id_paralelo' => -1,

                    'sa_id_est_idukay' => $item['_id'],

                    'sa_id_rep_idukay' => $item['relatives'][0]['parent'] ?? '',
                    'sa_est_rep_parentesco' => ucwords(strtolower($item['relatives'][0]['relationship'] ?? '')),

                    'sa_id_rep_idukay_2' => $item['relatives'][1]['parent'] ?? '',
                    'sa_est_rep_parentesco_2' => ucwords(strtolower($item['relatives'][1]['relationship'] ?? '')),

                    'sa_est_estado' => $estado_est,

                    'seccion_estudiante_idukay' => $seccion,
                    'grado_estudiante_idukay' => $grado,
                    'paralelo_estudiante_idukay' => $paralelo,

                ];
            }

            /*print_r($parametros);
            exit();
            die();*/

            //Crear clave valor para generar el script para guardar a los estudiantes    
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

                    array('campo' => 'sa_est_estado', 'dato' => $parametro['sa_est_estado']),
                    array('campo' => 'seccion_estudiante_idukay', 'dato' => $parametro['seccion_estudiante_idukay']),
                    array('campo' => 'grado_estudiante_idukay', 'dato' => $parametro['grado_estudiante_idukay']),
                    array('campo' => 'paralelo_estudiante_idukay', 'dato' => $parametro['paralelo_estudiante_idukay']),

                );
                //break;

                /*print_r($datos);
                exit();
                die();*/
            }

            /*----------------------------------------------------------------
                Para guardar en la bdd los insert de los estudiantes 
            /*----------------------------------------------------------------*/

            // Dividir los datos en grupos de 300
            $grupos = array_chunk($datos, 100);

            // Insertar cada grupo en la base de datos
            $contador = 0;
            foreach ($grupos as $grupo) {
                $sql = array();

                foreach ($grupo as $dato) {
                    //$sql[] = array($this->inserts('estudiantes', $dato));
                    $sql[] = array($this->funciones_carga_idukay->upsertSQL('estudiantes', $dato, 'sa_id_est_idukay'));
                }

                // Construir la sentencia SQL para el grupo actual
                $sentenciaSql = '';
                foreach ($sql as $dato) {
                    foreach ($dato as $consulta) {
                        $sentenciaSql .= $consulta . " "; //<br/>
                    }
                }

                /*echo ($sentenciaSql);
                exit();
                die();*/


                // Ejecutar la inserción del grupo actual
                $resultado = $this->clase_llamada->sql_string($sentenciaSql);

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

            $ponerIdCursos = $this->SQL_idukay->ponerIdCursos();
            $query = $this->clase_llamada->sql_string($ponerIdCursos);

            $ponerSegurosPredeterminados = $this->SQL_idukay->ponerSegurosPredeterminados();
            $query = $this->clase_llamada->sql_string($ponerSegurosPredeterminados);

            /**
             * Parche para que todos los estudiantes salgan y puedan sacar su ficha medica
             */

            $ponerRepresentantesEstudiantes = $this->SQL_idukay->ponerRepresentantesEstudiantes();
            $query = $this->clase_llamada->sql_string($ponerRepresentantesEstudiantes);


            if ($estudiatesUP == false) {
                $resultado = $this->clase_llamada->sql_string("UPDATE estudiantes set sa_est_estado = '1';");
            }

            return $query;
        } else {
            return -11;
        }
    }

    /*

    Representantes

    */

    function cargarRepresentantesIdukay($data, $desarrollo_idukay)
    {
        //$data = $this->Idukay_API->lista_Padres();

        if ($data != -11) {
            $parametros = [];
            foreach ($data['response'] as $item) {

                $sa_est_sexo = $item['user']['gender'] ?? '';

                if ($sa_est_sexo === 'M') {
                    $sa_est_sexo = 'Masculino';
                } elseif ($sa_est_sexo === 'F') {
                    $sa_est_sexo = 'Femenino';
                }

                //Validacion para correo
                $correo = '';
                if (!empty($item['user']['email'])) {
                    $correo = $desarrollo_idukay . $item['user']['email'];
                } else {
                    $correo = $item['user']['email'] ?? '';
                }

                $parametros[] = [
                    'sa_rep_primer_apellido' => $item['user']['surname'] ?? '',
                    'sa_rep_segundo_apellido' => $item['user']['second_surname'] ?? '',
                    'sa_rep_primer_nombre' => $item['user']['name'] ?? '',
                    'sa_rep_segundo_nombre' => $item['user']['second_name'] ?? '',
                    'sa_rep_cedula' => $item['user']['id_card'] ?? '',
                    'sa_rep_sexo' => $sa_est_sexo,
                    'sa_rep_fecha_nacimiento' => $item['user']['birthday'] ?? '',
                    'sa_rep_correo' =>  $correo,
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

                    array('campo' => 'PASS', 'dato' => $this->cod_global->enciptar_clave($parametro['sa_rep_cedula'])),

                    array('campo' => 'sa_id_rep_idukay', 'dato' => $parametro['sa_id_rep_idukay']),
                );

                //break;
            }

            //print_r($datos);

            // Dividir los datos en grupos de 300
            $grupos = array_chunk($datos, 100);
            $sql_global = '';
            // Insertar cada grupo en la base de datos
            $contador = 0;
            $contador_sql_tab = 0;
            foreach ($grupos as $grupo) {
                $sql = array();

                foreach ($grupo as $dato) {
                    //$sql[] = array($this->inserts('representantes', $dato));
                    $sql[] = array($this->funciones_carga_idukay->upsertSQL('representantes', $dato, 'sa_id_rep_idukay'));
                }

                // Construir la sentencia SQL para el grupo actual
                $sentenciaSql = '';
                foreach ($sql as $dato) {
                    foreach ($dato as $consulta) {
                        $sentenciaSql .= $consulta; // . "--" . $contador_sql_tab . "  <br/>";
                        //$sql_global .= $consulta . "--" . $contador . "  <br/>";

                        // echo $consulta . "-- " . $contador . "<br/> ";
                        //$contador_sql_tab++;
                    }
                }

                // print_r($sentenciaSql);
                // die();


                // Ejecutar la inserción del grupo actual
                $resultado = $this->clase_llamada->sql_string($sentenciaSql);

                // Verificar el resultado
                if ($resultado !== 1) {
                    // Manejar el error o detener el proceso si falla la inserción
                    //echo "Error al insertar grupo de datos en la base de datos.";
                    return $resultado;
                    break;
                } else {
                    $contador++;
                }
            }

            // print_r($sql_global);
            //  die();

            $ponerRepresentantesEstudiantes = $this->SQL_idukay->ponerRepresentantesEstudiantes();
            $query = $this->clase_llamada->sql_string($ponerRepresentantesEstudiantes);

            return $query;
        } else {
            return -11;
        }
    }

    /*
    
    Docentes
    
    */

    function cargarDocentesIdukay($data, $desarrollo_idukay)
    {
        //$data = $this->Idukay_API->lista_Docentes();

        if ($data != -11) {
            $parametros = [];
            foreach ($data['response'] as $item) {

                $sa_est_sexo = $item['user']['gender'] ?? '';

                if ($sa_est_sexo === 'M') {
                    $sa_est_sexo = 'Masculino';
                } elseif ($sa_est_sexo === 'F') {
                    $sa_est_sexo = 'Femenino';
                }

                //Validacion para correo
                $correo = '';
                if (!empty($item['user']['email'])) {
                    $correo = $desarrollo_idukay . $item['user']['email'];
                } else {
                    $correo = $item['user']['email'] ?? '';
                }

                $parametros[] = [
                    'sa_doc_primer_apellido' => $item['user']['surname'] ?? '',
                    'sa_doc_segundo_apellido' => $item['user']['second_surname'] ?? '',
                    'sa_doc_primer_nombre' => $item['user']['name'] ?? '',
                    'sa_doc_segundo_nombre' => $item['user']['second_name'] ?? '',
                    'sa_doc_cedula' => $item['user']['id_card'] ?? '',
                    'sa_doc_sexo' => $sa_est_sexo,
                    'sa_doc_fecha_nacimiento' => $item['user']['birthday'] ?? '',
                    'sa_doc_correo' => $correo,
                    'sa_doc_telefono_1' => '.',
                    'sa_doc_telefono_2' => '.',
                    'sa_doc_direccion' => '.',

                    'sa_doc_id_idukay' => $item['_id'],
                ];
            }

            //print_r($parametros); die();

            $datos = array();
            foreach ($parametros as $parametro) {

                $fecha_nacimiento = '1900-01-12';
                if (isset($parametro['sa_doc_fecha_nacimiento']) && $parametro['sa_doc_fecha_nacimiento'] != '') {
                    $timestamp = $parametro['sa_doc_fecha_nacimiento'];
                    $fecha_convertida = date('Y-m-d', $timestamp);

                    // Verificar si la fecha convertida es válida
                    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_convertida) && strtotime($fecha_convertida) !== false) {
                        $fecha_nacimiento = $fecha_convertida;
                    }
                }

                $datos[] = array(
                    array('campo' => 'sa_doc_primer_apellido', 'dato' => $parametro['sa_doc_primer_apellido']),
                    array('campo' => 'sa_doc_segundo_apellido', 'dato' => $parametro['sa_doc_segundo_apellido']),
                    array('campo' => 'sa_doc_primer_nombre', 'dato' => $parametro['sa_doc_primer_nombre']),
                    array('campo' => 'sa_doc_segundo_nombre', 'dato' => $parametro['sa_doc_segundo_nombre']),
                    array('campo' => 'sa_doc_cedula', 'dato' => $parametro['sa_doc_cedula']),
                    array('campo' => 'sa_doc_sexo', 'dato' => $parametro['sa_doc_sexo']),
                    array('campo' => 'sa_doc_fecha_nacimiento', 'dato' => $fecha_nacimiento),
                    array('campo' => 'sa_doc_correo', 'dato' => $parametro['sa_doc_correo']),
                    array('campo' => 'sa_doc_telefono_1', 'dato' => $parametro['sa_doc_telefono_1']),
                    array('campo' => 'sa_doc_telefono_2', 'dato' => $parametro['sa_doc_telefono_2']),
                    array('campo' => 'sa_doc_direccion', 'dato' => $parametro['sa_doc_direccion']),

                    array('campo' => 'PASS', 'dato' => $this->cod_global->enciptar_clave($parametro['sa_doc_cedula'])),

                    array('campo' => 'sa_doc_id_idukay', 'dato' => $parametro['sa_doc_id_idukay']),
                );

                //break;
            }

            //print_r($datos); die();

            // Dividir los datos en grupos de 300
            $grupos = array_chunk($datos, 100);

            //print_r($grupos[1]); die();

            // Insertar cada grupo en la base de datos
            $contador = 0;
            //$sentenciaSql_global = '';
            $contador_s = 0;

            foreach ($grupos as $grupo) {
                $sql = array();

                foreach ($grupo as $dato) {
                    //$sql[] = array($this->inserts('docentes', $dato));
                    $sql[] = array($this->funciones_carga_idukay->upsertSQL('docentes', $dato, 'sa_doc_id_idukay'));
                }

                // Construir la sentencia SQL para el grupo actual
                $sentenciaSql = '';
                foreach ($sql as $dato) {
                    foreach ($dato as $consulta) {
                        $sentenciaSql .= $consulta . " ";
                        //$sentenciaSql_global .= $consulta . "-- " . $contador_s . "<br/> ";

                        //echo $consulta . "-- " . $contador_s . "<br/> ";
                        $contador_s++;
                    }
                }

                // Ejecutar la inserción del grupo actual 1; //
                $resultado = $this->clase_llamada->sql_string($sentenciaSql);

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

            //$query = $this->estudiantes->ponerRepresentantesEstudiantes();
            //return $query;

            //print_r($sentenciaSql_global); //die();


            return 1;
        } else {
            return -11;
        }
    }

    function cargarHorariosDocentesIdukay($data)
    {
        //$data = $this->Idukay_API->lista_HorariosDocentes();
        // print_r($data);
        // die();
        $resultado = $this->clase_llamada->sql_string('TRUNCATE TABLE horario_clases;');
        $resultado = $this->clase_llamada->sql_string('TRUNCATE TABLE docente_paralelo;');


        if ($data != -11) {
            $parametros = [];
            foreach ($data['response'] as $item) {

                $string = $item['reference_name'];

                // Separar el string por la coma
                $partes = explode(',', $string);

                // Eliminar espacios en blanco de los elementos del array
                $partes = array_map('trim', $partes);

                // Tomar las palabras del primer fragmento
                $primer_fragmento = $partes[0];
                $primer_fragmento_partes = explode(' ', $primer_fragmento);

                // Tomar el último dígito del primer fragmento
                $ultimo_digito_primer_fragmento = array_pop($primer_fragmento_partes);

                // Reunir el primer fragmento sin el último dígito
                $primer_fragmento_sin_ultimo_digito = implode(' ', $primer_fragmento_partes);

                if (is_array($partes) && count($partes) > 1) {
                    $seccion = $partes[1];
                } else {
                    $seccion = ''; // Default value if the index 1 does not exist
                }

                $grado = $primer_fragmento_sin_ultimo_digito;
                $paralelo = $ultimo_digito_primer_fragmento;

                foreach ($item['schedule'] as $clases) {

                    $dia_clases = '';
                    if ($clases['day'] == 0) {
                        $dia_clases = 'lunes';
                    } else if ($clases['day'] == 1) {
                        $dia_clases = 'martes';
                    } else if ($clases['day'] == 2) {
                        $dia_clases = 'miercoles';
                    } else if ($clases['day'] == 3) {
                        $dia_clases = 'jueves';
                    } else if ($clases['day'] == 4) {
                        $dia_clases = 'viernes';
                    }

                    $parametros[] = [
                        'ac_horarioC_inicio' => $clases['start'] ?? '',
                        'ac_horarioC_fin' => $clases['end'] ?? '',
                        'ac_horarioC_dia' => $dia_clases ?? '',

                        'ac_horarioC_materia' => $item['name'] ?? '',

                        'id_docente_idukay' => $item['teacher'] ?? '',
                        'seccion_idukay' => $seccion ?? '',
                        'grado_idukay' => $grado ?? '',
                        'paralelo_idukay' => $paralelo ?? '',
                    ];
                }
            }

            // print_r($parametros);
            // die();

            $datos = array();
            foreach ($parametros as $parametro) {

                $datos[] = array(
                    array('campo' => 'ac_horarioC_inicio', 'dato' => $parametro['ac_horarioC_inicio']),
                    array('campo' => 'ac_horarioC_fin', 'dato' => $parametro['ac_horarioC_fin']),
                    array('campo' => 'ac_horarioC_dia', 'dato' => $parametro['ac_horarioC_dia']),
                    array('campo' => 'ac_horarioC_materia', 'dato' => $parametro['ac_horarioC_materia']),
                    array('campo' => 'id_docente_idukay', 'dato' => $parametro['id_docente_idukay']),
                    array('campo' => 'seccion_idukay', 'dato' => $parametro['seccion_idukay']),
                    array('campo' => 'grado_idukay', 'dato' => $parametro['grado_idukay']),
                    array('campo' => 'paralelo_idukay', 'dato' => $parametro['paralelo_idukay']),
                );

                //break;
            }

            //print_r($datos); die();

            // Dividir los datos en grupos de 300
            $grupos = array_chunk($datos, 100);

            //print_r($grupos[1]); die();

            // Insertar cada grupo en la base de datos
            $contador = 0;
            //$sentenciaSql_global = '';
            //$contador_s = 0;

            foreach ($grupos as $grupo) {
                $sql = array();

                foreach ($grupo as $dato) {
                    $sql[] = array($this->funciones_carga_idukay->inserts('horario_clases', $dato));
                }

                // Construir la sentencia SQL para el grupo actual
                $sentenciaSql = '';
                foreach ($sql as $dato) {
                    foreach ($dato as $consulta) {
                        $sentenciaSql .= $consulta . " ";
                        //$sentenciaSql_global .= $consulta . "-- " . $contador_s . "<br/> ";

                        //echo $consulta . "-- " . $contador_s . "<br/> ";
                        //$contador_s++;
                    }
                }

                // Ejecutar la inserción del grupo actual 1; //
                $resultado = $this->clase_llamada->sql_string($sentenciaSql);

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

            //Tabla horario_clases
            $ponerIdDocentes = $this->SQL_idukay->ponerIdDocentes();
            $query = $this->clase_llamada->sql_string($ponerIdDocentes);

            $ponerIdCursosDocentes = $this->SQL_idukay->ponerIdCursosDocentes();
            $query = $this->clase_llamada->sql_string($ponerIdCursosDocentes);

            //Tabla docente_paralelo
            $guardaDocenteParalelo = $this->SQL_idukay->guardaDocenteParalelo();
            $query = $this->clase_llamada->sql_string($guardaDocenteParalelo);

            return $query;

            //print_r($sentenciaSql_global); //die(); 

            //return 1;
        } else {
            return -11;
        }
    }
}
