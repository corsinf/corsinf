<?php

use function Complex\ln;

include('../modelo/consultasM.php');
include('../modelo/ficha_MedicaM.php');
include('../modelo/pacientesM.php');
include('../lib/phpmailer/enviar_emails.php');
include('../lib/pdf/fpdf.php');
include('../modelo/det_consultaM.php');

include('ingreso_stockC.php');
include('../modelo/notificacionesM.php');

//HIKVISION
include('../lib/HIKVISION/Notificaciones.php');
include('../lib/HIKVISION/HIK_TCP.php');



$controlador = new consultasC();

if (isset($_GET['listar_consulta_ficha'])) {

    $id_ficha = '';

    if (isset($_POST['id_ficha'])) {
        $id_ficha = $_POST['id_ficha'];
    }

    echo json_encode($controlador->lista_consultas_ficha($id_ficha));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['observacion'])) {
    echo json_encode($controlador->observaciones_consulta($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['listar_solo_consulta'])) {
    echo json_encode($controlador->lista_solo_consultas($_POST['id']));
}

if (isset($_GET['enviar_correo'])) {
    echo json_encode($controlador->enviar_correo($_POST['parametros']));
}

if (isset($_GET['datos_consulta'])) {

    $id_consulta = '';

    if (isset($_POST['id_consulta'])) {
        $id_consulta = $_POST['id_consulta'];
    }

    echo json_encode($controlador->carga_datos_consultas($id_consulta));
}

if (isset($_GET['pdf_consulta'])) {

    //print_r($_POST);die();
    $id_consulta = '';
    if (isset($_GET['id_consulta'])) {
        $id_consulta = $_GET['id_consulta'];
    }

    if (isset($_POST['id_consulta'])) {
        $id_consulta = $_POST['id_consulta'];
    }

    echo ($controlador->pdf_consulta_paciente($id_consulta));
}

if (isset($_GET['pdf_recetario'])) {

    //print_r($_POST);die();
    $id_consulta = '';
    if (isset($_GET['id_consulta'])) {
        $id_consulta = $_GET['id_consulta'];
    }

    if (isset($_POST['id_consulta'])) {
        $id_consulta = $_POST['id_consulta'];
    }

    echo ($controlador->pdf_recetario_consulta_paciente($id_consulta));
}

if (isset($_GET['pdf_notificacion'])) {

    $id_consulta = '';
    if (isset($_GET['id_consulta'])) {
        $id_consulta = $_GET['id_consulta'];
    }

    if (isset($_POST['id_consulta'])) {
        $id_consulta = $_POST['id_consulta'];
    }

    echo json_encode($controlador->pdf_notificacion($id_consulta));
}

if (isset($_GET['listar_todo'])) {
    echo json_encode($controlador->listar_todo($_GET['tabla'], $_GET['fecha_inicio'], $_GET['fecha_fin']));
}

if (isset($_GET['lista_con_est'])) {
    echo json_encode($controlador->lista_consultas_estudiantes($_GET['id_paralelo']));
}

if (isset($_GET['lista_con_est_doc'])) {
    echo json_encode($controlador->lista_consultas_estudiantes_docente($_GET['id_docente'], $_GET['fecha_actual_estado']));
}

if (isset($_GET['contar_consultas_docente'])) {
    echo json_encode($controlador->contar_consultas_estudiantes_docente($_POST['id_docente']));
}


//print_r($controlador->ret(''));

/*$parametros = array(
    'sa_sec_id' => 1,
    'sa_sec_nombre' => 'hola'
);

print_r($controlador->insertar_editar($parametros));*/

/*$modelo = new consultasM();

print_r($modelo->buscar_consultas_CODIGO(1));*/

class consultasC
{
    private $modelo;
    private $ficha_medicaM;
    private $pacientesM;
    private $email;
    private $det_consultaM;
    private $ingreso_stock;
    private $notificaciones;
    private $notificaciones_HV;
    private $TCP_HV;
    function __construct()
    {
        $this->modelo = new consultasM();
        $this->ficha_medicaM = new ficha_MedicaM();
        $this->pacientesM = new pacientesM();
        $this->email = new enviar_emails();
        $this->det_consultaM = new det_consultaM();
        $this->ingreso_stock = new ingreso_stockC();
        $this->notificaciones = new notificacionesM();
        $this->notificaciones_HV = new NotificaionesHV('28519009', 'kTnwcJUu7OQEGHCVGSJQ');
        $this->TCP_HV = new HIK_TCP();
    }

    function listar_todo($tabla, $fecha_inicio, $fecha_fin)
    {
        $datos = $this->modelo->lista_consultas_todo($tabla, $fecha_inicio, $fecha_fin);
        return $datos;
    }

    function lista_consultas_ficha($id_ficha)
    {
        $datos = $this->modelo->lista_consultas_ficha($id_ficha);
        return $datos;
    }

    function lista_solo_consultas($id)
    {
        $datos = $this->modelo->lista_solo_consultas($id);
        return $datos;
    }

    function lista_consultas_estudiantes($id_paralelo)
    {
        $datos = $this->modelo->lista_consultas_estudiantes($id_paralelo);
        return $datos;
    }

    function lista_consultas_estudiantes_docente($id_docente, $fecha_actual_estado)
    {
        $datos = $this->modelo->lista_consultas_estudiantes_docente($id_docente, $fecha_actual_estado);
        // print_r($datos);die();
        return $datos;
    }

    function contar_consultas_estudiantes_docente($id_docente)
    {
        $datos = $this->modelo->contar_consultas_estudiantes_docente($id_docente);
        return $datos;
    }

    //Retorna los valores (id) para cargar la ficha medica y el paciente
    function carga_datos_consultas($id_consulta)
    {
        $datos = $this->modelo->carga_datos_consultas($id_consulta);


        return $datos;
    }


    function insertar_editar($parametros)
    {
        $datos1[0]['campo'] = 'sa_conp_id';
        $datos1[0]['dato'] = strval($parametros['sa_conp_id']);

        $datos = array(
            array('campo' => 'sa_fice_id', 'dato' => $parametros['sa_fice_id']),
            array('campo' => 'sa_conp_nivel', 'dato' => $parametros['sa_conp_nivel']),
            array('campo' => 'sa_conp_paralelo', 'dato' => $parametros['sa_conp_paralelo']),
            array('campo' => 'sa_conp_edad', 'dato' => $parametros['sa_conp_edad']),
            array('campo' => 'sa_conp_peso', 'dato' => empty($parametros['sa_conp_peso']) ? 0 : $parametros['sa_conp_peso']),
            array('campo' => 'sa_conp_altura', 'dato' => empty($parametros['sa_conp_altura']) ? 0 : $parametros['sa_conp_altura']),
            array('campo' => 'sa_conp_temperatura', 'dato' => empty($parametros['sa_conp_temperatura']) ? 0 : $parametros['sa_conp_temperatura']),
            array('campo' => 'sa_conp_presion_ar', 'dato' => empty($parametros['sa_conp_presion_ar']) ? 0 : $parametros['sa_conp_presion_ar']),
            array('campo' => 'sa_conp_frec_cardiaca', 'dato' => empty($parametros['sa_conp_frec_cardiaca']) ? 0 : $parametros['sa_conp_frec_cardiaca']),
            array('campo' => 'sa_conp_frec_respiratoria', 'dato' => empty($parametros['sa_conp_frec_respiratoria']) ? 0 : $parametros['sa_conp_frec_respiratoria']),
            array('campo' => 'sa_conp_saturacion', 'dato' => empty($parametros['sa_conp_saturacion']) ? 0 : $parametros['sa_conp_saturacion']),

            array('campo' => 'sa_conp_fecha_ingreso', 'dato' => $parametros['sa_conp_fecha_ingreso']),
            array('campo' => 'sa_conp_desde_hora', 'dato' => $parametros['sa_conp_desde_hora']),
            array('campo' => 'sa_conp_hasta_hora', 'dato' => $parametros['sa_conp_hasta_hora']),
            array('campo' => 'sa_conp_tiempo_aten', 'dato' => $parametros['sa_conp_tiempo_aten']),
            array('campo' => 'sa_conp_CIE_10_1', 'dato' => $parametros['sa_conp_CIE_10_1']),
            array('campo' => 'sa_conp_diagnostico_1', 'dato' => $parametros['sa_conp_diagnostico_1']),
            array('campo' => 'sa_conp_CIE_10_2', 'dato' => $parametros['sa_conp_CIE_10_2']),
            array('campo' => 'sa_conp_diagnostico_2', 'dato' => $parametros['sa_conp_diagnostico_2']),

            array('campo' => 'sa_conp_salud_certificado', 'dato' => $parametros['sa_conp_salud_certificado']),
            array('campo' => 'sa_conp_motivo_certificado', 'dato' => $parametros['sa_conp_motivo_certificado']),
            array('campo' => 'sa_conp_CIE_10_certificado', 'dato' => $parametros['sa_conp_CIE_10_certificado']),
            array('campo' => 'sa_conp_diagnostico_certificado', 'dato' => $parametros['sa_conp_diagnostico_certificado']),
            //array('campo' => 'sa_conp_fecha_entrega_certificado', 'dato' => $parametros['sa_conp_fecha_entrega_certificado']),
            //array('campo' => 'sa_conp_fecha_inicio_falta_certificado', 'dato' => $parametros['sa_conp_fecha_inicio_falta_certificado']),
            //array('campo' => 'sa_conp_fecha_fin_alta_certificado', 'dato' => $parametros['sa_conp_fecha_fin_alta_certificado']),
            array('campo' => 'sa_conp_dias_permiso_certificado', 'dato' => $parametros['sa_conp_dias_permiso_certificado']),

            array('campo' => 'sa_conp_permiso_salida', 'dato' => $parametros['sa_conp_permiso_salida']),
            //array('campo' => 'sa_conp_fecha_permiso_salud_salida', 'dato' => $parametros['sa_conp_fecha_permiso_salud_salida']),
            array('campo' => 'sa_conp_hora_permiso_salida', 'dato' => $parametros['sa_conp_hora_permiso_salida']),
            array('campo' => 'sa_conp_permiso_tipo', 'dato' => $parametros['sa_conp_permiso_tipo']),
            array('campo' => 'sa_conp_permiso_seguro_traslado', 'dato' => $parametros['sa_conp_permiso_seguro_traslado']),
            array('campo' => 'sa_conp_permiso_telefono_padre', 'dato' => $parametros['sa_conp_permiso_telefono_padre']),
            array('campo' => 'sa_conp_permiso_telefono_seguro', 'dato' => $parametros['sa_conp_permiso_telefono_seguro']),

            array('campo' => 'sa_conp_notificacion_envio_representante', 'dato' => $parametros['sa_conp_notificacion_envio_representante']),
            array('campo' => 'sa_id_representante', 'dato' => $parametros['sa_id_representante']),
            array('campo' => 'sa_conp_notificacion_envio_docente', 'dato' => $parametros['sa_conp_notificacion_envio_docente']),
            array('campo' => 'sa_id_docente', 'dato' => $parametros['sa_id_docente']),
            array('campo' => 'sa_conp_notificacion_envio_inspector', 'dato' => $parametros['sa_conp_notificacion_envio_inspector']),
            array('campo' => 'sa_id_inspector', 'dato' => $parametros['sa_id_inspector']),
            array('campo' => 'sa_conp_notificacion_envio_guardia', 'dato' => $parametros['sa_conp_notificacion_envio_guardia']),
            array('campo' => 'sa_id_guardia', 'dato' => $parametros['sa_id_guardia']),
            array('campo' => 'sa_conp_tipo_consulta', 'dato' => $parametros['sa_conp_tipo_consulta']),
            array('campo' => 'sa_conp_enfermedad_actual', 'dato' => $parametros['sa_conp_enfermedad_actual']),

            array('campo' => 'sa_conp_observaciones', 'dato' => $parametros['sa_conp_observaciones']),
            array('campo' => 'sa_conp_motivo_consulta', 'dato' => $parametros['sa_conp_motivo_consulta']),
            array('campo' => 'sa_conp_tratamiento', 'dato' => $parametros['sa_conp_tratamiento']),
            array('campo' => 'sa_conp_estado_revision', 'dato' => $parametros['sa_conp_estado_revision']),

            array('campo' => 'sa_examen_fisico_regional', 'dato' => $parametros['sa_examen_fisico_regional']),

            array('campo' => 'sa_conp_usu_id', 'dato' => $_SESSION['INICIO']['NO_CONCURENTE']),
        );


        $fechas_certificado = null;
        if ($parametros['sa_conp_tipo_consulta'] === 'certificado') {
            $fechas_certificado = array(
                array('campo' => 'sa_conp_fecha_entrega_certificado', 'dato' => $parametros['sa_conp_fecha_entrega_certificado']),
                array('campo' => 'sa_conp_fecha_inicio_falta_certificado', 'dato' => $parametros['sa_conp_fecha_inicio_falta_certificado']),
                array('campo' => 'sa_conp_fecha_fin_alta_certificado', 'dato' => $parametros['sa_conp_fecha_fin_alta_certificado']),
            );
            $datos = array_merge($datos, $fechas_certificado);
        }

        $fechas_salida = null;
        if ($parametros['sa_conp_permiso_salida'] === 'SI') {
            $fechas_salida = array(
                array('campo' => 'sa_conp_fecha_permiso_salud_salida', 'dato' => $parametros['sa_conp_fecha_permiso_salud_salida']),
            );
            $datos = array_merge($datos, $fechas_salida);
        }

        //print_r($parametros);die();

        if ($parametros['sa_conp_id'] == '') {
            if (count($this->modelo->buscar_consultas_CODIGO($datos1[0]['dato'])) == 0) {

                //Se inserta los datos de la consulta
                $id_insert = $this->modelo->insertar_id($datos);

                /* ----------------------*/
                //    Notificaciones
                /* ----------------------*/

                $icono = "bx bxs-file-plus";

                if ($parametros['txt_paciente_tabla'] == 'estudiantes') {
                    //Notificacion para el docente
                    $datos_notificaciones = array(
                        array('campo' => 'GLO_modulo', 'dato' => '7'),
                        array('campo' => 'GLO_titulo', 'dato' => $parametros['sa_conp_tipo_consulta']),
                        array('campo' => 'GLO_cuerpo', 'dato' => $parametros['nombre_paciente']),
                        array('campo' => 'GLO_icono', 'dato' => $icono),
                        array('campo' => 'GLO_tabla', 'dato' => 'docentes'),
                        array('campo' => 'GLO_id_tabla', 'dato' => ''),
                        array('campo' => 'GLO_busqueda_especifica', 'dato' => $parametros['sa_id_paralelo']),
                        array('campo' => 'GLO_desc_busqueda', 'dato' => 'Para listar los estudiantes con el docente respectivo'),
                        array('campo' => 'GLO_link_redirigir', 'dato' => '../vista/inicio.php?acc=historial_salud_estudiantil'),
                        array('campo' => 'GLO_rol', 'dato' => 'docentes'),
                        array('campo' => 'GLO_observacion', 'dato' => ''),
                    );

                    $this->notificaciones->insertar($datos_notificaciones);

                    //Notificacion para el inspector
                    $datos_notificaciones = array(
                        array('campo' => 'GLO_modulo', 'dato' => '7'),
                        array('campo' => 'GLO_titulo', 'dato' => $parametros['sa_conp_tipo_consulta']),
                        array('campo' => 'GLO_cuerpo', 'dato' => $parametros['nombre_paciente']),
                        array('campo' => 'GLO_icono', 'dato' => $icono),
                        array('campo' => 'GLO_tabla', 'dato' => ''),
                        array('campo' => 'GLO_id_tabla', 'dato' => ''),
                        array('campo' => 'GLO_busqueda_especifica', 'dato' => ''),
                        array('campo' => 'GLO_desc_busqueda', 'dato' => 'Para listar todas las consultas de estudiantes'),
                        array('campo' => 'GLO_link_redirigir', 'dato' => '../vista/inicio.php?acc=consultas'),
                        array('campo' => 'GLO_rol', 'dato' => 'INSPECTOR'),
                        array('campo' => 'GLO_observacion', 'dato' => ''),
                    );

                    $this->notificaciones->insertar($datos_notificaciones);

                    /*HIKVISION*/

                    if ($parametros['sa_conp_permiso_tipo'] == 'normal') {
                        $mensaje_TCP = 'consulta_' . $id_insert;
                        $this->notificaciones_HV->crear_Evento_usuario('SALUD ' . $parametros['nombre_apellido_paciente'] . $id_insert, $mensaje_TCP, 3);
                        sleep(4);
                        $this->TCP_HV->TCP_enviar($mensaje_TCP);
                    } else if ($parametros['sa_conp_permiso_tipo'] == 'emergencia') {
                        $mensaje_TCP = 'conulta_' . $id_insert;
                        $this->notificaciones_HV->crear_Evento_usuario('SALUD ' . $parametros['nombre_apellido_paciente'] . $id_insert, $mensaje_TCP, 2);
                        sleep(4);
                        $this->TCP_HV->TCP_enviar($mensaje_TCP);
                    }
                }


                /////////////////////////////////////////////////////////////////////////////////

                //echo($idConsultaPrincipal);die();
                if (!empty($parametros['filas_tabla_farmacologia'])) {

                    foreach ($parametros['filas_tabla_farmacologia'] as $fila) {
                        $datos_farmacologia = array();

                        $estado_entrega = -1;
                        if ($fila['sa_det_conp_estado_entrega'] == 'true') {
                            $estado_entrega = 1;
                        } elseif ($fila['sa_det_conp_estado_entrega'] == 'false') {
                            $estado_entrega = 0;
                        }

                        $datos_farmacologia = array(
                            array('campo' => 'sa_id_conp', 'dato' => $id_insert),
                            array('campo' => 'sa_det_conp_id_cmed_cins', 'dato' => $fila['sa_det_conp_id_cmed_cins']),
                            array('campo' => 'sa_det_conp_tipo', 'dato' => $fila['sa_det_conp_tipo']),
                            array('campo' => 'sa_det_conp_nombre', 'dato' => $fila['sa_det_conp_nombre']),
                            array('campo' => 'sa_det_conp_cantidad', 'dato' => $fila['sa_det_conp_cantidad']),
                            array('campo' => 'sa_det_conp_dosificacion', 'dato' => $fila['sa_det_conp_dosificacion']),
                            array('campo' => 'sa_det_conp_estado_entrega', 'dato' =>  $estado_entrega),
                        );

                        $datos_farmacologia = $this->det_consultaM->insertar($datos_farmacologia);

                        //Insertar 
                        ////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        //Para reducir el stock
                        if ($estado_entrega == 1) {
                            $tipo_tabla = '';
                            if ($fila['sa_det_conp_tipo'] == 'medicamentos') {
                                $tipo_tabla = ucfirst($fila['sa_det_conp_tipo']);
                                $tipo_tabla = substr($tipo_tabla, 0, -1);
                            } else if ($fila['sa_det_conp_tipo'] == 'insumos') {
                                $tipo_tabla = ucfirst($fila['sa_det_conp_tipo']);
                            }

                            $datos_stock = array(
                                'orden' => 'Consulta - ' . $id_insert,
                                'ddl_tipo' => $tipo_tabla,
                                'ddl_lista_productos' => $fila['sa_det_conp_id_cmed_cins'],
                                'txt_canti' => $fila['sa_det_conp_cantidad'],
                                'txt_subtotal' => 0,
                                'txt_total' => 0,
                            );

                            //print_r($tipo_tabla);die();
                            $this->ingreso_stock->producto_nuevo_salida($datos_stock);
                        }
                        ////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    }
                }


                $datos = 1;
            } else {
                return -2 . ' . ' . $datos1[0]['dato'];
            }
        } else {
            $where[0]['campo'] = 'sa_conp_id';
            $where[0]['dato'] = $parametros['sa_conp_id'];
            //$datos[] = array('campo' => 'sa_conp_estado', 'dato' => 1);
            $datos = $this->modelo->editar($datos, $where);

            /* ----------------------*/
            //    Notificaciones
            /* ----------------------*/




            /////////////////////////////////////////////////////////////////////////////////

            if (!empty($parametros['filas_tabla_farmacologia'])) {
                foreach ($parametros['filas_tabla_farmacologia'] as $fila) {
                    $datos_farmacologia = array();

                    if ($fila['sa_det_conp_id'] == '') {

                        $estado_entrega = -1;
                        if ($fila['sa_det_conp_estado_entrega'] == 'true') {
                            $estado_entrega = 1;
                        } elseif ($fila['sa_det_conp_estado_entrega'] == 'false') {
                            $estado_entrega = 0;
                        }

                        $datos_farmacologia = array(
                            array('campo' => 'sa_id_conp', 'dato' => $parametros['sa_conp_id']),
                            array('campo' => 'sa_det_conp_id_cmed_cins', 'dato' => $fila['sa_det_conp_id_cmed_cins']),
                            array('campo' => 'sa_det_conp_tipo', 'dato' => $fila['sa_det_conp_tipo']),
                            array('campo' => 'sa_det_conp_nombre', 'dato' => $fila['sa_det_conp_nombre']),
                            array('campo' => 'sa_det_conp_cantidad', 'dato' => $fila['sa_det_conp_cantidad']),
                            array('campo' => 'sa_det_conp_dosificacion', 'dato' => $fila['sa_det_conp_dosificacion']),
                            array('campo' => 'sa_det_conp_estado_entrega', 'dato' =>  $estado_entrega),
                        );

                        $datos_farmacologia = $this->det_consultaM->insertar($datos_farmacologia);

                        //Modificar
                        ////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        //Para reducir el stock
                        if ($estado_entrega == 1) {
                            $tipo_tabla = '';
                            if ($fila['sa_det_conp_tipo'] == 'medicamentos') {
                                $tipo_tabla = ucfirst($fila['sa_det_conp_tipo']);
                                $tipo_tabla = substr($tipo_tabla, 0, -1);
                            } else if ($fila['sa_det_conp_tipo'] == 'insumos') {
                                $tipo_tabla = ucfirst($fila['sa_det_conp_tipo']);
                            }

                            $datos_stock = array(
                                'orden' => 'Consulta - ' . $parametros['sa_conp_id'],
                                'ddl_tipo' => $tipo_tabla,
                                'ddl_lista_productos' => $fila['sa_det_conp_id_cmed_cins'],
                                'txt_canti' => $fila['sa_det_conp_cantidad'],
                                'txt_subtotal' => 0,
                                'txt_total' => 0,
                            );

                            //print_r($tipo_tabla);die();
                            $this->ingreso_stock->producto_nuevo_salida($datos_stock);
                        }
                        ////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    } else {

                        $estado_entrega = -1;
                        if ($fila['sa_det_conp_estado_entrega'] == 'true') {
                            $estado_entrega = 1;
                        } elseif ($fila['sa_det_conp_estado_entrega'] == 'false') {
                            $estado_entrega = 0;
                        }

                        $datos_farmacologia = array(
                            array('campo' => 'sa_det_conp_id_cmed_cins', 'dato' => $fila['sa_det_conp_id_cmed_cins']),
                            array('campo' => 'sa_det_conp_tipo', 'dato' => $fila['sa_det_conp_tipo']),
                            array('campo' => 'sa_det_conp_nombre', 'dato' => $fila['sa_det_conp_nombre']),
                            array('campo' => 'sa_det_conp_cantidad', 'dato' => $fila['sa_det_conp_cantidad']),
                            array('campo' => 'sa_det_conp_dosificacion', 'dato' => $fila['sa_det_conp_dosificacion']),
                            array('campo' => 'sa_det_conp_estado_entrega', 'dato' => $estado_entrega),
                        );

                        $where[0]['campo'] = 'sa_det_conp_id';
                        $where[0]['dato'] = $fila['sa_det_conp_id'];

                        $datos_farmacologia = $this->det_consultaM->editar($datos_farmacologia, $where);

                        //Modificar
                        ////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        //Para reducir el stock
                        if ($estado_entrega == 1) {
                            $tipo_tabla = '';
                            if ($fila['sa_det_conp_tipo'] == 'medicamentos') {
                                $tipo_tabla = ucfirst($fila['sa_det_conp_tipo']);
                                $tipo_tabla = substr($tipo_tabla, 0, -1);
                            } else if ($fila['sa_det_conp_tipo'] == 'insumos') {
                                $tipo_tabla = ucfirst($fila['sa_det_conp_tipo']);
                            }

                            $datos_stock = array(
                                'orden' => 'Consulta - ' . $parametros['sa_conp_id'],
                                'ddl_tipo' => $tipo_tabla,
                                'ddl_lista_productos' => $fila['sa_det_conp_id_cmed_cins'],
                                'txt_canti' => $fila['sa_det_conp_cantidad'],
                                'txt_subtotal' => 0,
                                'txt_total' => 0,
                            );

                            //print_r($tipo_tabla);die();
                            $this->ingreso_stock->producto_nuevo_salida($datos_stock);
                        }
                        ////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    }
                }
            }
        }

        /*$where[0]['campo'] = 'sa_conp_id';
        $where[0]['dato'] = $parametros['sa_conp_id'];
        $datos = $this->modelo->editar($datos, $where);*/

        //$datos = $this->modelo->insertar($datos);

        return $datos;
    }

    function observaciones_consulta($parametros)
    {

        $datos = array(
            array('campo' => 'sa_conp_observaciones', 'dato' => $parametros['sa_conp_observaciones']),
        );

        $where[0]['campo'] = 'sa_conp_id';
        $where[0]['dato'] = $parametros['sa_conp_id'];
        //$datos[] = array('campo' => 'sa_conp_estado', 'dato' => 1);
        $datos = $this->modelo->editar($datos, $where);


        return $datos;
    }

    function eliminar($id)
    {
        $datos[0]['campo'] = 'sa_conp_id';
        $datos[0]['dato'] = $id;
        $datos = $this->modelo->eliminar($datos);
        return $datos;
    }

    function enviar_correo($parametros)
    {
        // print_r($parametros);die();
        $to_correo = $parametros['to'];
        $titulo_correo = $parametros['sub'];
        $cuerpo_correo = $parametros['men'];

        //return $this->email->enviar_email($to_correo, $cuerpo_correo, $titulo_correo, $correo_respaldo = 'soporte@corsinf.com', $archivos = false, $titulo_correo, true);

        return true;
    }

    function pdf_consulta_paciente($id_consulta)
    {
        $datos = $this->modelo->lista_solo_consultas($id_consulta);
        $ficha_medica = $this->ficha_medicaM->lista_ficha_medica_id($datos[0]['sa_fice_id']);
        $paciente = $this->pacientesM->obtener_informacion_pacienteM($ficha_medica[0]['sa_fice_pac_id']);

        //Consulta 
        $sa_fice_id = $datos[0]['sa_fice_id'];
        $sa_conp_nivel = $datos[0]['sa_conp_nivel'];
        $sa_conp_paralelo = $datos[0]['sa_conp_paralelo'];
        $sa_conp_edad = $datos[0]['sa_conp_edad'];
        $sa_conp_peso = $datos[0]['sa_conp_peso'];
        $sa_conp_altura = $datos[0]['sa_conp_altura'];
        $sa_conp_temperatura = $datos[0]['sa_conp_temperatura'];
        $sa_conp_presion_ar = $datos[0]['sa_conp_presion_ar'];
        $sa_conp_frec_cardiaca = $datos[0]['sa_conp_frec_cardiaca'];
        $sa_conp_frec_respiratoria = $datos[0]['sa_conp_frec_respiratoria'];
        $sa_conp_saturacion = $datos[0]['sa_conp_saturacion'];


        $sa_conp_fecha_ingreso = $datos[0]['sa_conp_fecha_ingreso'];
        $sa_conp_fecha_ingreso = $sa_conp_fecha_ingreso->format('Y-m-d');

        $sa_conp_desde_hora = $datos[0]['sa_conp_desde_hora'];
        $sa_conp_desde_hora = $sa_conp_desde_hora->format('H:i:s');

        $sa_conp_hasta_hora = $datos[0]['sa_conp_hasta_hora'];
        $sa_conp_hasta_hora = $sa_conp_hasta_hora->format('H:i:s');

        $sa_conp_tiempo_aten = $datos[0]['sa_conp_tiempo_aten'];

        $sa_conp_CIE_10_1 = $datos[0]['sa_conp_CIE_10_1'];
        $sa_conp_diagnostico_1 = $datos[0]['sa_conp_diagnostico_1'];
        $sa_conp_CIE_10_2 = $datos[0]['sa_conp_CIE_10_2'];
        $sa_conp_diagnostico_2 = $datos[0]['sa_conp_diagnostico_2'];

        $sa_conp_salud_certificado = $datos[0]['sa_conp_salud_certificado'];
        $sa_conp_motivo_certificado = $datos[0]['sa_conp_motivo_certificado'];
        $sa_conp_CIE_10_certificado = $datos[0]['sa_conp_CIE_10_certificado'];
        $sa_conp_diagnostico_certificado = $datos[0]['sa_conp_diagnostico_certificado'];

        $sa_conp_fecha_entrega_certificado = $datos[0]['sa_conp_fecha_entrega_certificado'];
        if ($sa_conp_fecha_entrega_certificado !== null) {
            $sa_conp_fecha_entrega_certificado = $sa_conp_fecha_entrega_certificado->format('Y-m-d');
        }

        $sa_conp_fecha_inicio_falta_certificado = $datos[0]['sa_conp_fecha_inicio_falta_certificado'];
        if ($sa_conp_fecha_inicio_falta_certificado !== null) {
            $sa_conp_fecha_inicio_falta_certificado = $sa_conp_fecha_inicio_falta_certificado->format('Y-m-d');
        }

        $sa_conp_fecha_fin_alta_certificado = $datos[0]['sa_conp_fecha_fin_alta_certificado'];
        if ($sa_conp_fecha_fin_alta_certificado !== null) {
            $sa_conp_fecha_fin_alta_certificado = $sa_conp_fecha_fin_alta_certificado->format('Y-m-d');
        }

        $sa_conp_dias_permiso_certificado = $datos[0]['sa_conp_dias_permiso_certificado'];

        $sa_conp_permiso_salida = $datos[0]['sa_conp_permiso_salida'];

        $sa_conp_fecha_permiso_salud_salida = $datos[0]['sa_conp_fecha_permiso_salud_salida'];
        if ($sa_conp_fecha_permiso_salud_salida !== null) {
            $sa_conp_fecha_permiso_salud_salida = $sa_conp_fecha_permiso_salud_salida->format('Y-m-d');
        }

        $sa_conp_hora_permiso_salida = $datos[0]['sa_conp_hora_permiso_salida'];
        if ($sa_conp_hora_permiso_salida !== null) {
            $sa_conp_hora_permiso_salida = $sa_conp_hora_permiso_salida->format('H:i:s');
        }

        $sa_conp_permiso_tipo = $datos[0]['sa_conp_permiso_tipo'];
        $sa_conp_permiso_seguro_traslado = $datos[0]['sa_conp_permiso_seguro_traslado'];
        $sa_conp_permiso_telefono_padre = $datos[0]['sa_conp_permiso_telefono_padre'];
        $sa_conp_permiso_telefono_seguro = $datos[0]['sa_conp_permiso_telefono_seguro'];

        $sa_conp_motivo_consulta = $datos[0]['sa_conp_motivo_consulta'];
        $sa_conp_observaciones = $datos[0]['sa_conp_observaciones'];
        $sa_conp_tratamiento = $datos[0]['sa_conp_tratamiento'];
        $sa_conp_tipo_consulta = $datos[0]['sa_conp_tipo_consulta'];

        $sa_examen_fisico_regional = $datos[0]['sa_examen_fisico_regional'];


        //Ficha medica
        $sa_fice_pac_grupo_sangre = $ficha_medica[0]['sa_fice_pac_grupo_sangre'];
        $sa_fice_pac_direccion_domicilio = $ficha_medica[0]['sa_fice_pac_direccion_domicilio'];

        $sa_fice_rep_1_primer_apellido = $ficha_medica[0]['sa_fice_rep_1_primer_apellido'];
        $sa_fice_rep_1_segundo_apellido = $ficha_medica[0]['sa_fice_rep_1_segundo_apellido'];
        $sa_fice_rep_1_primer_nombre = $ficha_medica[0]['sa_fice_rep_1_primer_nombre'];
        $sa_fice_rep_1_segundo_nombre = $ficha_medica[0]['sa_fice_rep_1_segundo_nombre'];
        $sa_fice_rep_1_completo = $sa_fice_rep_1_primer_apellido . ' ' . $sa_fice_rep_1_segundo_apellido . ' ' . $sa_fice_rep_1_primer_nombre . ' ' . $sa_fice_rep_1_segundo_nombre;
        $sa_fice_rep_1_parentesco = $ficha_medica[0]['sa_fice_rep_1_parentesco'];
        $sa_fice_rep_1_telefono_1 = $ficha_medica[0]['sa_fice_rep_1_telefono_1'];
        $sa_fice_rep_1_telefono_2 = $ficha_medica[0]['sa_fice_rep_1_telefono_2'];

        $sa_fice_rep_2_primer_apellido = $ficha_medica[0]['sa_fice_rep_2_primer_apellido'];
        $sa_fice_rep_2_segundo_apellido = $ficha_medica[0]['sa_fice_rep_2_segundo_apellido'];
        $sa_fice_rep_2_primer_nombre = $ficha_medica[0]['sa_fice_rep_2_primer_nombre'];
        $sa_fice_rep_2_segundo_nombre = $ficha_medica[0]['sa_fice_rep_2_segundo_nombre'];
        $sa_fice_rep_2_completo = $sa_fice_rep_2_primer_apellido . ' ' . $sa_fice_rep_2_segundo_apellido . ' ' . $sa_fice_rep_2_primer_nombre . ' ' . $sa_fice_rep_2_segundo_nombre;

        $sa_fice_rep_2_parentesco = $ficha_medica[0]['sa_fice_rep_2_parentesco'];
        $sa_fice_rep_2_telefono_1 = $ficha_medica[0]['sa_fice_rep_2_telefono_1'];
        $sa_fice_rep_2_telefono_2 = $ficha_medica[0]['sa_fice_rep_2_telefono_2'];

        $sa_fice_pregunta_1_obs = $ficha_medica[0]['sa_fice_pregunta_1_obs'];
        $sa_fice_pregunta_2_obs = $ficha_medica[0]['sa_fice_pregunta_2_obs'];
        $sa_fice_pregunta_3_obs = $ficha_medica[0]['sa_fice_pregunta_3_obs'];
        $sa_fice_pregunta_4_obs = $ficha_medica[0]['sa_fice_pregunta_4_obs'];
        $sa_fice_pregunta_5_obs = $ficha_medica[0]['sa_fice_pregunta_5_obs'];

        //Pacientes
        $sa_pac_temp_cedula = $paciente[0]['sa_pac_temp_cedula'];
        $sa_pac_temp_primer_nombre = $paciente[0]['sa_pac_temp_primer_nombre'];
        $sa_pac_temp_segundo_nombre = $paciente[0]['sa_pac_temp_segundo_nombre'];
        $sa_pac_temp_primer_apellido = $paciente[0]['sa_pac_temp_primer_apellido'];
        $sa_pac_temp_segundo_apellido = $paciente[0]['sa_pac_temp_segundo_apellido'];
        $sa_pac_tabla = $paciente[0]['sa_pac_tabla'];

        //$sa_pac_temp_fecha_nacimiento = $paciente[0]['sa_pac_temp_fecha_nacimiento'];

        $sa_pac_temp_fecha_nacimiento = $paciente[0]['sa_pac_temp_fecha_nacimiento'];
        if ($sa_pac_temp_fecha_nacimiento !== null) {
            $sa_pac_temp_fecha_nacimiento = $sa_pac_temp_fecha_nacimiento->format('Y-m-d');
        }



        //print_r($datos);

        //exit();

        $sa_pac_temp_correo = $paciente[0]['sa_pac_temp_correo'];



        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 12);

        $pdf->Cell(40, 10, utf8_decode(''), 'L T', 0);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(90, 10, utf8_decode('UNIDAD EDUCATIVA SAINT DOMINIC'), 1, 0, 'C');
        $pdf->Cell(20, 10, utf8_decode('Código:'), 1, 0, 'R');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(40, 10, utf8_decode('GA-MD-RG-001'), 1, 1, 'C');


        $pdf->Cell(40, 10, utf8_decode(''), 'L', 0);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(90, 10, utf8_decode('DEPARTAMENTO MÉDICO'), 1, 0, 'C');
        $pdf->Cell(20, 10, utf8_decode('Versión:'), 1, 0, 'R');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(40, 10, utf8_decode('1.0'), 1, 1, 'C');

        $pdf->Cell(40, 10, utf8_decode(''), 'L B', 0, 'C');
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(90, 10, utf8_decode('FORMULARIO - ' . strtoupper($sa_conp_tipo_consulta)), 1, 0, 'C');
        $pdf->Cell(20, 10, utf8_decode('Página:'), 1, 0, 'R');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(40, 10, utf8_decode('1 de 1'), 1, 1, 'C');

        $pdf->ln('8');

        /////////////////////////////////////////////////////////////////////////////////////////////
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(190, 10, utf8_decode('  DATOS PERSONALES DEL USUARIO / PACIENTE'), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(22, 7, utf8_decode('CÉDULA'), 1, 0, 'C');
        $pdf->Cell(42, 7, utf8_decode('PRIMER APELLIDO'), 1, 0, 'C');
        $pdf->Cell(42, 7, utf8_decode('SEGUNDO APELLIDO'), 1, 0, 'C');
        $pdf->Cell(42, 7, utf8_decode('PRIMER NOMBRE'), 1, 0, 'C');
        $pdf->Cell(42, 7, utf8_decode('SEGUNDO NOMBRE'), 1, 1, 'C');

        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(22, 8, utf8_decode($sa_pac_temp_cedula), 1, 0, 'C');
        $pdf->Cell(42, 8, utf8_decode($sa_pac_temp_primer_apellido), 1, 0, 'C');
        $pdf->Cell(42, 8, utf8_decode($sa_pac_temp_segundo_apellido), 1, 0, 'C');
        $pdf->Cell(42, 8, utf8_decode($sa_pac_temp_primer_nombre), 1, 0, 'C');
        $pdf->Cell(42, 8, utf8_decode($sa_pac_temp_segundo_nombre), 1, 1, 'C');

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(70, 7, utf8_decode('FECHA DE NACIMIENTO'), 1, 0, 'C');
        $pdf->Cell(20, 7, utf8_decode('EDAD'), 1, 0, 'C');
        $pdf->Cell(100, 7, utf8_decode('CORREO'), 1, 1, 'C');

        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(70, 8, ($sa_pac_temp_fecha_nacimiento), 1, 0, 'C');
        $pdf->Cell(20, 8, utf8_decode($sa_conp_edad . ' años'), 1, 0, 'C');
        $pdf->Cell(100, 8, utf8_decode($sa_pac_temp_correo), 1, 1, 'C');

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(50, 7, utf8_decode('GRUPO SANGUÍNEO'), 1, 0, 'C');
        $pdf->Cell(140, 7, utf8_decode('DIRECCIÓN'), 1, 1, 'C');

        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(50, 8, utf8_decode($sa_fice_pac_grupo_sangre), 1, 0, 'C');
        $pdf->Cell(140, 8, utf8_decode($sa_fice_pac_direccion_domicilio), 1, 1, 'C');

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(47.5, 7, utf8_decode('FECHA DE INGRESO'), 1, 0, 'C');
        $pdf->Cell(47.5, 7, utf8_decode('HORA DE ATENCIÓN'), 1, 0, 'C');
        $pdf->Cell(47.5, 7, utf8_decode('HORA FIN DE ATENCIÓN'), 1, 0, 'C');
        $pdf->Cell(47.5, 7, utf8_decode('TIEMPO DE ATENCIÓN'), 1, 1, 'C');

        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(47.5, 8, ($sa_conp_fecha_ingreso), 1, 0, 'C');
        $pdf->Cell(47.5, 8, ($sa_conp_desde_hora), 1, 0, 'C');
        $pdf->Cell(47.5, 8, ($sa_conp_hasta_hora), 1, 0, 'C');
        $pdf->Cell(47.5, 8, ($sa_conp_tiempo_aten), 1, 1, 'C');


        /////////////////////////////////////////////////////////////////////////////////////////////
        $pdf->ln('4');

        /////////////////////////////////////////////////////////////////////////////////////////////
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(190, 10, utf8_decode('  DATOS DE CONTACTO'), 1, 1, 'L');

        if ($sa_pac_tabla == 'estudiantes') {
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(70, 7, utf8_decode('EN CASO NECESARIO LLAMAR A:'), 1, 0, 'C');
            $pdf->Cell(40, 7, utf8_decode('PARENTESCO'), 1, 0, 'C');
            $pdf->Cell(40, 7, utf8_decode('TELÉFONO 1'), 1, 0, 'C');
            $pdf->Cell(40, 7, utf8_decode('TELÉFONO 2'), 1, 1, 'C');

            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(70, 8, utf8_decode($sa_fice_rep_1_completo), 1, 0, 'C');
            $pdf->Cell(40, 8, utf8_decode($sa_fice_rep_1_parentesco), 1, 0, 'C');
            $pdf->Cell(40, 8, utf8_decode($sa_fice_rep_1_telefono_1), 1, 0, 'C');
            $pdf->Cell(40, 8, utf8_decode($sa_fice_rep_1_telefono_2), 1, 1, 'C');
        }

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(70, 7, utf8_decode('EN CASO NECESARIO LLAMAR A:'), 1, 0, 'C');
        $pdf->Cell(40, 7, utf8_decode('PARENTESCO'), 1, 0, 'C');
        $pdf->Cell(40, 7, utf8_decode('TELÉFONO 1'), 1, 0, 'C');
        $pdf->Cell(40, 7, utf8_decode('TELÉFONO 2'), 1, 1, 'C');



        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(70, 8, utf8_decode($sa_fice_rep_2_completo), 1, 0, 'C');
        $pdf->Cell(40, 8, utf8_decode($sa_fice_rep_2_parentesco), 1, 0, 'C');
        $pdf->Cell(40, 8, utf8_decode($sa_fice_rep_2_telefono_1), 1, 0, 'C');
        $pdf->Cell(40, 8, utf8_decode($sa_fice_rep_2_telefono_2), 1, 1, 'C');



        /////////////////////////////////////////////////////////////////////////////////////////////
        $pdf->ln('4');

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(190, 10, utf8_decode('  DIAGNÓSTICOS'), 1, 1, 'L');

        if ($sa_conp_tipo_consulta == 'consulta') {
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(32, 7, utf8_decode('DIAGNÓSTICO 1: '), 1, 0, 'C');
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(158, 7, utf8_decode($sa_conp_diagnostico_1), 1, 1, 'C');

            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(32, 7, utf8_decode('DIAGNÓSTICO 2: '), 1, 0, 'C');
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(158, 7, utf8_decode($sa_conp_diagnostico_2), 1, 1, 'C');
        } else {
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(32, 7, utf8_decode('DIAGNÓSTICO: '), 1, 0, 'C');
            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(158, 7, utf8_decode($sa_conp_diagnostico_certificado), 1, 1, 'C');

            //DATOS DEL CERTIFICADO

            //$sa_conp_salud_certificado
            //$sa_conp_motivo_certificado

            $pdf->ln('4');

            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(47.5, 7, utf8_decode('FECHA DE ENTREGA'), 1, 0, 'C');
            $pdf->Cell(47.5, 7, utf8_decode('FECHA INICIO'), 1, 0, 'C');
            $pdf->Cell(47.5, 7, utf8_decode('FECHA FIN'), 1, 0, 'C');
            $pdf->Cell(47.5, 7, utf8_decode('DÍAS DE PERMISO'), 1, 1, 'C');

            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(47.5, 8, ($sa_conp_fecha_entrega_certificado), 1, 0, 'C');
            $pdf->Cell(47.5, 8, ($sa_conp_fecha_inicio_falta_certificado), 1, 0, 'C');
            $pdf->Cell(47.5, 8, ($sa_conp_fecha_fin_alta_certificado), 1, 0, 'C');
            $pdf->Cell(47.5, 8, ($sa_conp_dias_permiso_certificado), 1, 1, 'C');

            $pdf->ln('4');

            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(0, 7, utf8_decode('SALUD CERTIFICADO'), 1, 1, 'L');
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(0, 5, utf8_decode($sa_conp_salud_certificado), 1, 'L');

            $pdf->ln('4');
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(0, 7, utf8_decode('MOTIVO DEL CERTIFICADO'), 1, 1, 'l');
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(0, 5, utf8_decode($sa_conp_motivo_certificado), 1, 'L');
        }



        /////////////////////////////////////////////////////////////////////////////////////////////
        $pdf->ln('4');

        if ($sa_conp_tipo_consulta == 'consulta') {
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(190, 10, utf8_decode('  CONSTANTES VITALES'), 1, 1, 'L');

            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(28.2, 7, utf8_decode('TEMPERATURA'), 1, 0, 'C');
            $pdf->Cell(31, 7, utf8_decode('PRESIÓN ARTERIAL'), 1, 0, 'C');
            $pdf->Cell(20, 7, utf8_decode('PULOS / min'), 1, 0, 'C');
            $pdf->Cell(41.2, 7, utf8_decode('FRECIENCIA RESPITARORIA'), 1, 0, 'C');
            $pdf->Cell(20, 7, utf8_decode('PESO (kg)'), 1, 0, 'C');
            $pdf->Cell(20, 7, utf8_decode('TALLA (m)'), 1, 0, 'C');
            $pdf->Cell(29.6, 7, utf8_decode('SARURACIÓN'), 1, 1, 'C');

            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(28.2, 8, utf8_decode($sa_conp_temperatura), 1, 0, 'C');
            $pdf->Cell(31, 8, utf8_decode($sa_conp_presion_ar), 1, 0, 'C');
            $pdf->Cell(20, 8, utf8_decode($sa_conp_frec_cardiaca), 1, 0, 'C');
            $pdf->Cell(41.2, 8, utf8_decode($sa_conp_frec_respiratoria), 1, 0, 'C');
            $pdf->Cell(20, 8, utf8_decode($sa_conp_peso), 1, 0, 'C');
            $pdf->Cell(20, 8, utf8_decode($sa_conp_altura), 1, 0, 'C');
            $pdf->Cell(29.6, 8, utf8_decode($sa_conp_saturacion), 1, 1, 'C');
        }
        /////////////////////////////////////////////////////////////////////////////////////////////
        $pdf->ln('4');

        if ($sa_conp_permiso_salida == 'SI' && $sa_conp_permiso_tipo == 'emergencia') {
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(190, 10, utf8_decode('  REFERENCIA'), 1, 1, 'L');

            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(160, 7, utf8_decode('PACIENTE REFERIDO A:'), 1, 0, 'C');
            $pdf->Cell(30, 7, utf8_decode('TELÉFONO'), 1, 1, 'C');

            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(160, 8, utf8_decode($sa_conp_permiso_seguro_traslado), 1, 0, 'C');
            $pdf->Cell(30, 8, utf8_decode($sa_conp_permiso_telefono_seguro), 1, 1, 'C');

            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(47.5, 7, utf8_decode('TELÉFONO RESPONSABLE:'), 1, 0, 'C');
            $pdf->Cell(47.5, 7, utf8_decode('FECHA DE SALIDA:'), 1, 0, 'C');
            $pdf->Cell(47.5, 7, utf8_decode('HORA DE SALIDA'), 1, 0, 'C');
            $pdf->Cell(47.5, 7, utf8_decode('TIPO DE SALIDA'), 1, 1, 'C');

            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(47.5, 8, utf8_decode($sa_conp_permiso_telefono_padre), 1, 0, 'C');
            $pdf->Cell(47.5, 8, ($sa_conp_hora_permiso_salida), 1, 0, 'C');
            $pdf->Cell(47.5, 8, ($sa_conp_fecha_permiso_salud_salida), 1, 0, 'C');
            $pdf->Cell(47.5, 8, utf8_decode(strtoupper($sa_conp_permiso_tipo)), 1, 1, 'C');
        } else if ($sa_conp_permiso_salida == 'SI' && $sa_conp_permiso_tipo == 'normal') {
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(190, 10, utf8_decode('  DATOS DE SALIDA'), 1, 1, 'L');

            $pdf->SetFont('Arial', 'B', 8);
            $pdf->Cell(63.33, 7, utf8_decode('FECHA DE SALIDA:'), 1, 0, 'C');
            $pdf->Cell(63.33, 7, utf8_decode('HORA DE SALIDA'), 1, 0, 'C');
            $pdf->Cell(63.33, 7, utf8_decode('TIPO DE SALIDA'), 1, 1, 'C');

            $pdf->SetFont('Arial', '', 9);
            $pdf->Cell(63.33, 8, ($sa_conp_hora_permiso_salida), 1, 0, 'C');
            $pdf->Cell(63.33, 8, ($sa_conp_fecha_permiso_salud_salida), 1, 0, 'C');
            $pdf->Cell(63.33, 8, utf8_decode(strtoupper($sa_conp_permiso_tipo)), 1, 1, 'C');
        }



        /////////////////////////////////////////////////////////////////////////////////////////////
        //Ficha medica Ecamen fisico regional
        /////////////////////////////////////////////////////////////////////////////////////////////
        $pdf->AddPage();

        if ($sa_conp_tipo_consulta == 'consulta') {

            $jsonArray = json_decode($sa_examen_fisico_regional, true);

            $numValoresPiel = count($jsonArray['Piel']);
            $numValoresOjos = count($jsonArray['Ojos']);
            $numValoresOido = count($jsonArray['Oído']);
            $numValoresOroFaringe = count($jsonArray['Oro_faringe']);
            $numValoresNariz = count($jsonArray['Nariz']);
            $numValoresCuello = count($jsonArray['Cuello']);
            $numValoresTorax1 = count($jsonArray['Torax_1']);
            $numValoresTorax2 = count($jsonArray['Torax_2']);
            $numValoresAbdomen = count($jsonArray['Abdomen']);
            $numValoresColumna = count($jsonArray['Columna']);
            $numValoresPelvis = count($jsonArray['Pelvis']);
            $numValoresExtremidades = count($jsonArray['Extremidades']);
            $numValoresNeurologico = count($jsonArray['Neurológico']);

            if (
                $numValoresPiel > 0 ||
                $numValoresOjos > 0 ||
                $numValoresOido > 0 ||
                $numValoresOroFaringe > 0 ||
                $numValoresNariz > 0 ||
                $numValoresCuello > 0 ||
                $numValoresTorax1 > 0 ||
                $numValoresTorax2 > 0 ||
                $numValoresAbdomen > 0 ||
                $numValoresColumna > 0 ||
                $numValoresPelvis > 0 ||
                $numValoresExtremidades > 0 ||
                $numValoresNeurologico > 0
            ) {
                $pdf->ln('10');

                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(190, 10, utf8_decode('  EXAMEN FÍSICO REGIONAL'), 1, 1, 'L');

                $pdf->SetFont('Arial', '', 10);


                //190 63.33
                //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



                $pdf->SetFont('Arial', 'B', 12);



                //1 Piel
                $Piel_a = isset($jsonArray['Piel']['a']) ? 'X' : '';
                $Piel_b = isset($jsonArray['Piel']['b']) ? 'X' : '';
                $Piel_c = isset($jsonArray['Piel']['c']) ? 'X' : '';

                //2 Ojos
                $Ojos_a = isset($jsonArray['Ojos']['a']) ? 'X' : '';
                $Ojos_b = isset($jsonArray['Ojos']['b']) ? 'X' : '';
                $Ojos_c = isset($jsonArray['Ojos']['c']) ? 'X' : '';
                $Ojos_d = isset($jsonArray['Ojos']['d']) ? 'X' : '';
                $Ojos_e = isset($jsonArray['Ojos']['e']) ? 'X' : '';

                //3 Oído
                $Oido_a = isset($jsonArray['Oído']['a']) ? 'X' : '';
                $Oido_b = isset($jsonArray['Oído']['b']) ? 'X' : '';
                $Oido_c = isset($jsonArray['Oído']['c']) ? 'X' : '';

                //4 Oro_faringe
                $Oro_faringe_a = isset($jsonArray['Oro_faringe']['a']) ? 'X' : '';
                $Oro_faringe_b = isset($jsonArray['Oro_faringe']['b']) ? 'X' : '';
                $Oro_faringe_c = isset($jsonArray['Oro_faringe']['c']) ? 'X' : '';
                $Oro_faringe_d = isset($jsonArray['Oro_faringe']['d']) ? 'X' : '';
                $Oro_faringe_e = isset($jsonArray['Oro_faringe']['e']) ? 'X' : '';

                //5 Nariz
                $Nariz_a = isset($jsonArray['Nariz']['a']) ? 'X' : '';
                $Nariz_b = isset($jsonArray['Nariz']['b']) ? 'X' : '';
                $Nariz_c = isset($jsonArray['Nariz']['c']) ? 'X' : '';
                $Nariz_d = isset($jsonArray['Nariz']['d']) ? 'X' : '';

                //6 Cuello
                $Cuello_a = isset($jsonArray['Cuello']['a']) ? 'X' : '';
                $Cuello_b = isset($jsonArray['Cuello']['b']) ? 'X' : '';

                //7 Tórax
                $Torax_1_a = isset($jsonArray['Torax_1']['a']) ? 'X' : '';
                $Torax_1_b = isset($jsonArray['Torax_1']['b']) ? 'X' : '';

                //8 Tórax
                $Torax_2_a = isset($jsonArray['Torax_2']['a']) ? 'X' : '';
                $Torax_2_b = isset($jsonArray['Torax_2']['b']) ? 'X' : '';

                //9 Abdomen
                $Abdomen_a = isset($jsonArray['Abdomen']['a']) ? 'X' : '';
                $Abdomen_b = isset($jsonArray['Abdomen']['b']) ? 'X' : '';

                //10 Columna
                $Columna_a = isset($jsonArray['Columna']['a']) ? 'X' : '';
                $Columna_b = isset($jsonArray['Columna']['b']) ? 'X' : '';
                $Columna_c = isset($jsonArray['Columna']['c']) ? 'X' : '';

                //11 Pelvis
                $Pelvis_a = isset($jsonArray['Pelvis']['a']) ? 'X' : '';
                $Pelvis_b = isset($jsonArray['Pelvis']['b']) ? 'X' : '';

                //12 Extremidades
                $Extremidades_a = isset($jsonArray['Extremidades']['a']) ? 'X' : '';
                $Extremidades_b = isset($jsonArray['Extremidades']['b']) ? 'X' : '';
                $Extremidades_c = isset($jsonArray['Extremidades']['c']) ? 'X' : '';

                //13 Neurológico
                $Neurologico_a = isset($jsonArray['Neurológico']['a']) ? 'X' : '';
                $Neurologico_b = isset($jsonArray['Neurológico']['b']) ? 'X' : '';
                $Neurologico_c = isset($jsonArray['Neurológico']['c']) ? 'X' : '';
                $Neurologico_d = isset($jsonArray['Neurológico']['d']) ? 'X' : '';


                $pdf->SetFont('Arial', 'B', 8);
                // Primera fila de celdas




                $pdf->SetFillColor(211, 228, 251);
                $pdf->Cell(6, 7, utf8_decode(''), 'LT', 0, 'L', true);
                $pdf->SetFillColor(211, 228, 251);
                $pdf->Cell(35.5, 7, utf8_decode('a. Cicatrices'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Piel_a), 1, 0, 'L');

                $pdf->SetFillColor(211, 228, 251);
                $pdf->Cell(6, 7, utf8_decode(''), 'LT', 0, 'L', true);
                $pdf->SetFillColor(211, 228, 251);
                $pdf->Cell(35.5, 7, utf8_decode('a. Labios'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Oro_faringe_a), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'LT', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('a. Mamas'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Torax_1_a), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'LT', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('a. Vascular'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Extremidades_a), 1, 1, 'L');


                // Segunda fila de celdas
                $pdf->SetFillColor(211, 228, 251);
                $pdf->Cell(6, 7, utf8_decode(''), 'L', 0, 'L', true);
                $pdf->SetFillColor(211, 228, 251);
                $pdf->Cell(35.5, 7, utf8_decode('b. Tatuajes'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Piel_b), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'L', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('b. Lengua'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Oro_faringe_b), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'L', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('b. Corazón'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Torax_1_b), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'L', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('b. Miembros superiores'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Extremidades_b), 1, 1, 'L');


                // Tercera fila de celdas
                $pdf->SetFillColor(211, 228, 251);
                $pdf->Cell(6, 7, utf8_decode(''), 'L', 0, 'L', true);
                $pdf->SetFillColor(211, 228, 251);
                $pdf->Cell(35.5, 7, utf8_decode('c. Piel y Faneras'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Piel_c), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'L', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('c. Faringe'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Oro_faringe_c), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'LT', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('a. Pulmones'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Torax_2_a), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'L', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('c. Miembros inferiores'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Extremidades_c), 1, 1, 'L');


                // Cuarta fila de celdas
                $pdf->Cell(6, 7, utf8_decode(''), 'LT', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('a. Párpados'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Ojos_a), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'L', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('d. Amígdalas'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Oro_faringe_d), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'L', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('b. Parrilla Costal'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Torax_2_b), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'LT', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('a. Fuerza'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Neurologico_a), 1, 1, 'L');


                // Quinta fila de celdas
                $pdf->Cell(6, 7, utf8_decode(''), 'L', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('b. Conjuntivas'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Ojos_b), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'L', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('e. Dentadura'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Oro_faringe_e), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'LT', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('a. Vísceras'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Abdomen_a), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'L', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('b. Sencibilidad'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Neurologico_b), 1, 1, 'L');


                // Sexta fila de celdas
                $pdf->Cell(6, 7, utf8_decode(''), 'L', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('c. Pupilas'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Ojos_c), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'LT', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('a. Tabique'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Nariz_a), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'L', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('b. Pared Abdominal'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Abdomen_b), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'L', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('c. Marcha'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Neurologico_c), 1, 1, 'L');


                // Septima fila de celdas
                $pdf->Cell(6, 7, utf8_decode(''), 'L', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('d. Córnea'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Ojos_d), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'L', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('b. Cornetes'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Nariz_b), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'LT', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('a. Flexibilidad'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Columna_a), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'LB', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('d. Reflejos'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Neurologico_d), 1, 1, 'L');


                // Octava fila de celdas
                $pdf->Cell(6, 7, utf8_decode(''), 'L', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('e. Motilidad'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Ojos_e), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'L', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('c. Mucosas'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Nariz_c), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'L', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('b. Desviación'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Columna_b), 1, 1, 'L');

                // Novena fila de celdas
                $pdf->Cell(6, 7, utf8_decode(''), 'LT', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('a. C. auditivo externo'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Oido_a), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'L', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('d. Senos paranasales'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Nariz_d), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'L', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('c. Dolor'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Columna_c), 1, 1, 'L');


                // Decima fila de celdas
                $pdf->Cell(6, 7, utf8_decode(''), 'L', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('b. Pabellón'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Oido_b), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'LT', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('a. Tiroides/masas'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Cuello_a), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'LT', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('a. Pelvis'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Pelvis_a), 1, 1, 'L');


                // Decimo primero fila de celdas
                $pdf->Cell(6, 7, utf8_decode(''), 'LB', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('c. Timpanos'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Oido_c), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'LB', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('b. Movilidad'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Cuello_b), 1, 0, 'L');

                $pdf->Cell(6, 7, utf8_decode(''), 'LB', 0, 'L', true);
                $pdf->Cell(35.5, 7, utf8_decode('b. Genitales'), 1, 0, 'L', true);
                $pdf->Cell(6, 7, utf8_decode($Pelvis_b), 1, 1, 'L');

                //Para poner los textos en la tabla
                $pdf->RotatedText(14, 44, utf8_decode('1. Piel'), 90);
                $pdf->RotatedText(14, 72, utf8_decode('2. Ojos'), 90);
                $pdf->RotatedText(14, 101, utf8_decode('3. Oído'), 90);

                $pdf->RotatedText(61.5, 56, utf8_decode('4. Oro faringe'), 90);
                $pdf->RotatedText(61.5, 84, utf8_decode('5. Naríz'), 90);
                $pdf->RotatedText(61.5, 105.5, utf8_decode('6.Cuello'), 90);

                $pdf->RotatedText(109, 42, utf8_decode('7. Tórax'), 90);
                $pdf->RotatedText(109, 56.5, utf8_decode('8. Tórax'), 90);
                $pdf->SetFont('Arial', 'B', 7);

                $pdf->RotatedText(109, 71.7, utf8_decode('9.Abdomen'), 90);
                $pdf->SetFont('Arial', 'B', 8);

                $pdf->RotatedText(109, 91, utf8_decode('10. Columna'), 90);
                $pdf->RotatedText(109, 106.5, utf8_decode('11. Pelvis'), 90);

                $pdf->SetFont('Arial', 'B', 7);
                $pdf->RotatedText(156.5, 50.5, utf8_decode('12. Extremidades'), 90);

                $pdf->SetFont('Arial', 'B', 8);
                $pdf->RotatedText(156.5, 76, utf8_decode('13. Neurológico'), 90);


                //$pdf->ln('1');
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(190, 10, utf8_decode('  OBSERVACIONES'), 1, 1, 'L');

                $pdf->SetFont('Arial', '', 10);
                $observaciones = isset($jsonArray['Observaciones']['sa_examen_fisico_regional_obs']) ? $jsonArray['Observaciones']['sa_examen_fisico_regional_obs'] : '';

                $pdf->MultiCell(190, 6, utf8_decode($observaciones), 1);
            }
        }



        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //Obervaciones
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        $pdf->ln('10');

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(190, 10, utf8_decode('  INFORMACIÓN ADICIONAL'), 1, 1, 'L');


        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(0, 7, utf8_decode('OBSERVACIONES'), 1, 1, 'l');
        $pdf->SetFont('Arial', '', 9);
        $pdf->MultiCell(0, 6, utf8_decode($sa_conp_observaciones), 1, 'L');

        if ($sa_conp_tipo_consulta == 'consulta') {
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(0, 7, utf8_decode('MOTIVO DE LA CONSULTA'), 1, 1, 'l');
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(0, 6, utf8_decode($sa_conp_motivo_consulta), 1, 'L');

            $pdf->SetFont('Arial', 'B', 10);
            $pdf->Cell(0, 7, utf8_decode('TRATAMIENTO'), 1, 1, 'l');
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCell(0, 6, utf8_decode($sa_conp_tratamiento), 1, 'L');
        }

        $pdf->ln('10');


        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(190, 10, utf8_decode('  FICHA MÉDICA'), 1, 1, 'L');
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(190, 7, utf8_decode('   1.- ¿Ha sido diagnosticado con alguna enfermedad?:'), 1, 1, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->MultiCell(190, 6, utf8_decode($sa_fice_pregunta_1_obs), 1);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(190, 7, utf8_decode('   2.- ¿Tiene algún antecedente familiar de importancia?:'), 1, 1, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->MultiCell(190, 6, utf8_decode($sa_fice_pregunta_2_obs), 1);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(190, 7, utf8_decode('   3.- ¿Ha sido sometido a cirugías previas?:'), 1, 1, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->MultiCell(190, 6, utf8_decode($sa_fice_pregunta_3_obs), 1);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(190, 7, utf8_decode('   4.- ¿Tiene alergias?:'), 1, 1, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->MultiCell(190, 6, utf8_decode($sa_fice_pregunta_4_obs), 1);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(190, 7, utf8_decode('   5.- ¿Qué medicamentos usa?:'), 1, 1, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->MultiCell(190, 6, utf8_decode($sa_fice_pregunta_5_obs), 1);

        /////////////////////////////////////////////////////////////////////////////////////////////



        $pdf->ln('8');

        $pdf->SetFont('Arial', '', 9);




        /*$nombre_medico = ' Md. Camila López';

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(60, 10, utf8_decode($nombre_medico), '0', 1, 'C');
        $pdf->SetFont('Arial', '', 12);

        $pdf->Cell(60, 10, utf8_decode('Médico Institucional'), '0', 0, 'C');*/

        $pdf->Output();
    }

    function pdf_recetario_consulta_paciente($id_consulta)
    {
        $datos = $this->modelo->lista_solo_consultas($id_consulta);
        $ficha_medica = $this->ficha_medicaM->lista_ficha_medica_id($datos[0]['sa_fice_id']);
        $paciente = $this->pacientesM->obtener_informacion_pacienteM($ficha_medica[0]['sa_fice_pac_id']);
        $detalle_consulta = $this->det_consultaM->lista_det_consulta_consulta($id_consulta);

        //Pacientes
        $sa_pac_temp_cedula = $paciente[0]['sa_pac_temp_cedula'];
        $sa_pac_temp_primer_nombre = $paciente[0]['sa_pac_temp_primer_nombre'];
        $sa_pac_temp_segundo_nombre = $paciente[0]['sa_pac_temp_segundo_nombre'];
        $sa_pac_temp_primer_apellido = $paciente[0]['sa_pac_temp_primer_apellido'];
        $sa_pac_temp_segundo_apellido = $paciente[0]['sa_pac_temp_segundo_apellido'];

        $nombre_completo = $sa_pac_temp_primer_apellido . ' ' .  $sa_pac_temp_segundo_apellido . ' ' .  $sa_pac_temp_primer_nombre . ' ' . $sa_pac_temp_segundo_nombre;


        $pdf = new FPDF('L', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetLeftMargin(10);
        $pdf->SetRightMargin(10);
        $pdf->SetTopMargin(10);
        $pdf->SetAutoPageBreak(true, 1);

        //Footer
        $pdf->setY(201);
        $pdf->setX(1);
        $pdf->SetFont('Arial', 'I', 8);
        $pdf->Cell(148.5, 5, 'Desarrollado por Corsinf', 0, 0, 'C');

        $pdf->SetFont('Arial', 'B', 14);

        $pdf->setY(201);
        $pdf->setX(148.5);
        $pdf->SetFont('Arial', 'I', 8);
        $pdf->Cell(148.5, 5, 'Desarrollado por Corsinf', 0, 0, 'C');

        $pdf->SetFont('Arial', 'B', 14);



        //210 x 297


        //x1 y1 x2 y2
        //105 148.5

        $imagePath = '../img/empresa/9999999999999.jpeg';

        // Coordenadas y dimensiones de la imagen

        $pdf->Image($imagePath, 10, 10, 20, 20);
        $pdf->Image($imagePath, 158.5, 10, 20, 20);

        $pdf->Line(148.5, 0, 148.5, 210);

        $pdf->SetTextColor(57, 80, 122);
        $pdf->SetFont('Arial', 'B', 12);

        $pdf->setY(10);
        $pdf->setX(40);
        $pdf->Cell(90, 5, utf8_decode('UNIDAD EDUCATIVA SAINT DOMINIC'), 0, 1, 'C');

        $pdf->setY(15);
        $pdf->setX(40);
        $pdf->Cell(90, 5, utf8_decode('DEPARTAMENTO MÉDICO'), 0, 1, 'C');

        $pdf->setY(20);
        $pdf->setX(40);
        $pdf->Cell(90, 5, utf8_decode('RECETARIO'), 0, 1, 'C');

        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->setY(40);
        $pdf->setX(10);
        $pdf->Cell(18, 7, utf8_decode('Nombre: '), 0, 0, '');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(90, 7, utf8_decode($nombre_completo), 0, 1, 'L');
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetTextColor(57, 80, 122);
        $pdf->Cell(90, 5, utf8_decode('Rp. '), 0, 1, 'L');

        $pdf->Ln(2);

        $medicamentos = array(
            array("1", "Medicamento 1", "1", "1 mes", "Tomar tres veces en el día"),
            array("2", "Medicamento 2", "2", "2 meses", "Tomar tres veces en el día"),
            array("3", "Medicamento 3", "2", "1 mes", "Tomar tres veces en el día"),
            array("4", "Medicamento 4", "2", "1 mes", "Tomar tres veces en el día"),
            array("5", "Medicamento 5", "3", "1 mes", "Tomar tres veces en el día"),
            array("6", "Medicamento 6", "1", "12 meses", "Tomar tres veces en el día"),
            array("7", "Medicamento 6", "1", "12 meses", "Tomar tres veces en el día"),
            array("8", "Medicamento 6", "1", "12 meses", "Tomar tres veces en el día"),
            array("9", "Medicamento 6", "1", "12 meses", "Tomar tres veces en el día"),
            array("10", "Medicamento 6", "1", "12 meses", "Tomar tres veces en el díaomar tres veces en el díaomar tres veces en el díaomar tres veces en el díaomar tres veces en el día"),

        );

        $pdf->SetTextColor(0, 0, 0);

        $pdf->Cell('8.5', 5, '#', 0, 0, 'C');
        $pdf->Cell('100', 5, utf8_decode('Famacología'), 0, 0, 'L');
        $pdf->Cell('20', 5, 'Cantidad', 0, 1, 'C');

        $pdf->SetFont('Arial', '', 9);
        $contador = 1;
        foreach ($detalle_consulta as $row) {
            $pdf->Cell('8.5', 5, utf8_decode($contador), 0, 0, 'C');
            $pdf->Cell('100', 5, utf8_decode($row['sa_det_conp_nombre']), 0, 0, 'L');
            $pdf->Cell('20', 5, utf8_decode($row['sa_det_conp_cantidad']), 0, 1, 'C');
            $contador++;
        }


        /*$pdf->Ln(25);

        //$pdf->setX(10);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(128.5, 7, "FIRMA Y SELLO", 0, 1, 'C');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Ln(25);
        $pdf->Cell(128.5, 7, "_________________________________________", 0, 1, 'C');
        $pdf->Cell(128.5, 7, "Nombre del Medico/Doctor", 0, 1, 'C');

        $pdf->Cell(128.5, 7, "Powered by Evilnapsis", 0, 1, 'C');*/


        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //Inficaciones
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        $pdf->SetTextColor(57, 80, 122);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->setY(10);
        $pdf->setX(190);
        $pdf->Cell(90, 5, utf8_decode('UNIDAD EDUCATIVA SAINT DOMINIC'), 0, 1, 'C');

        $pdf->setY(15);
        $pdf->setX(190);
        $pdf->Cell(90, 5, utf8_decode('DEPARTAMENTO MÉDICO'), 0, 1, 'C');

        $pdf->setY(20);
        $pdf->setX(190);
        $pdf->Cell(90, 5, utf8_decode('RECETARIO'), 0, 1, 'C');

        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->setY(40);
        $pdf->setX(158.5);
        $pdf->Cell(18, 7, utf8_decode('Nombre: '), 0, 0, '');
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(158.5, 7, utf8_decode($nombre_completo), 0, 1, 'L');
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetTextColor(57, 80, 122);
        $pdf->setY(47);
        $pdf->setX(158.5);
        $pdf->Cell(90, 5, utf8_decode('Indicaciones. '), 0, 1, 'L');

        $pdf->Ln(2);



        $pdf->SetTextColor(0, 0, 0);
        $pdf->setX(158.5);
        $pdf->Cell('8.5', 5, '#', 0, 0, 'C');
        $pdf->setX(167);
        $pdf->Cell('100', 5, utf8_decode('Famacología'), 0, 0, 'L');
        $pdf->setX(267);
        $pdf->Cell('20', 5, 'Cantidad', 0, 1, 'C');

        $contador_rec = 1;
        foreach ($detalle_consulta as $row) {
            $pdf->SetFont('Arial', '', 9);
            $pdf->SetTextColor(0, 0, 0);

            $pdf->setX(158.5);
            $pdf->Cell('8.5', 3, utf8_decode($contador_rec), 0, 0, 'C');

            $pdf->setX(167);
            $pdf->Cell('100', 3, utf8_decode($row['sa_det_conp_nombre']), 0, 0, 'L');

            $pdf->setX(267);
            $pdf->Cell('20', 3, utf8_decode($row['sa_det_conp_cantidad']), 0, 1, 'C');
            $pdf->setX(167);

            $pdf->SetFont('Arial', '', 8);
            $pdf->SetTextColor(57, 80, 122);
            $pdf->MultiCell(120, 5, "Indicaciones: " . utf8_decode($row['sa_det_conp_dosificacion']), 0, 'L');

            $contador_rec++;
        }

        $pdf->output();
    }

    function pdf_notificacion($id_consulta)
    {
        $datos = $this->modelo->lista_solo_consultas($id_consulta);
        $ficha_medica = $this->ficha_medicaM->lista_ficha_medica_id($datos[0]['sa_fice_id']);
        $paciente = $this->pacientesM->obtener_informacion_pacienteM($ficha_medica[0]['sa_fice_pac_id']);

        //Consulta 
        $sa_conp_nivel = $datos[0]['sa_conp_nivel'];
        $sa_conp_paralelo = $datos[0]['sa_conp_paralelo'];

        $sa_conp_fecha_ingreso = $datos[0]['sa_conp_fecha_ingreso'];
        $sa_conp_fecha_ingreso = $sa_conp_fecha_ingreso;

        $sa_conp_desde_hora = $datos[0]['sa_conp_desde_hora'];
        $sa_conp_desde_hora = $sa_conp_desde_hora;

        $sa_conp_hasta_hora = $datos[0]['sa_conp_hasta_hora'];
        $sa_conp_hasta_hora = $sa_conp_hasta_hora;

        $sa_conp_diagnostico_1 = $datos[0]['sa_conp_diagnostico_1'];

        $sa_conp_diagnostico_certificado = $datos[0]['sa_conp_diagnostico_certificado'];

        $sa_conp_permiso_salida = $datos[0]['sa_conp_permiso_salida'];

        $sa_conp_tipo_consulta = $datos[0]['sa_conp_tipo_consulta'];

        //Pacientes
        $sa_pac_temp_cedula = $paciente[0]['sa_pac_temp_cedula'];
        $sa_pac_temp_primer_nombre = $paciente[0]['sa_pac_temp_primer_nombre'];
        $sa_pac_temp_segundo_nombre = $paciente[0]['sa_pac_temp_segundo_nombre'];
        $sa_pac_temp_primer_apellido = $paciente[0]['sa_pac_temp_primer_apellido'];
        $sa_pac_temp_segundo_apellido = $paciente[0]['sa_pac_temp_segundo_apellido'];
        $sa_pac_tabla = $paciente[0]['sa_pac_tabla'];

        $nombre_completo = $sa_pac_temp_primer_apellido . ' ' .  $sa_pac_temp_segundo_apellido . ' ' .  $sa_pac_temp_primer_nombre . ' ' . $sa_pac_temp_segundo_nombre;


        //Valores para notificacion ///////////////////////////////////////////////////////////////////////////////////
        $fecha_creado = $sa_conp_fecha_ingreso;

        $grado = $sa_conp_nivel;
        $paralelo = $sa_conp_paralelo;
        //CONSULTA
        $hora_desde = $sa_conp_desde_hora;
        $hora_hasta = $sa_conp_hasta_hora;
        $diagnostico_consulta = $sa_conp_diagnostico_1;
        //cERTIFICADO
        $diagnostico_certificado = $sa_conp_diagnostico_certificado;

        $nombre_medico = ' Md. Camila López';

        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 12);

        $pdf->Cell(40, 10, utf8_decode(''), 'L T', 0);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(90, 10, utf8_decode('UNIDAD EDUCATIVA SAINT DOMINIC'), 1, 0, 'C');
        $pdf->Cell(20, 10, utf8_decode('Código:'), 1, 0, 'R');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(40, 10, utf8_decode('GA-MD-RG-001'), 1, 1, 'C');


        $pdf->Cell(40, 10, utf8_decode(''), 'L', 0);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(90, 10, utf8_decode('DEPARTAMENTO MÉDICO'), 1, 0, 'C');
        $pdf->Cell(20, 10, utf8_decode('Versión:'), 1, 0, 'R');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(40, 10, utf8_decode('1.0'), 1, 1, 'C');


        $pdf->Cell(40, 10, utf8_decode(''), 'L B', 0, 'C');
        $pdf->SetFont('Arial', 'B', 12);

        if ($sa_conp_permiso_salida == 'SI') {
            $pdf->Cell(90, 10, utf8_decode('PERMISO DE SALIDA'), 1, 0, 'C');
        } else {
            $pdf->Cell(90, 10, utf8_decode(strtoupper($sa_conp_tipo_consulta)), 1, 0, 'C');
        }

        $pdf->Cell(20, 10, utf8_decode('Página:'), 1, 0, 'R');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(40, 10, utf8_decode('1 de 1'), 1, 1, 'C');

        $pdf->ln('8');

        $pdf->Cell(0, 10, utf8_decode('Fecha: ' . $fecha_creado), 0, 1, 'L');
        $pdf->ln('3');

        if ($sa_pac_tabla == 'estudiantes') {
            if ($sa_conp_permiso_salida == 'SI') {
                $mensaje_salida = 'Certifico que él/la estudiante  ' . $nombre_completo . ' del grado ' . $grado . ' paralelo ' . $paralelo . ' requiere salir del plantel para recibir atención médica externa';
                $pdf->MultiCell(0, 6, utf8_decode($mensaje_salida), 0, 'J');
            } else if ($sa_conp_tipo_consulta == 'consulta') {
                $mensaje_consulta = 'Certifico que él/la estudiante ' . $nombre_completo . ' del grado ' . $grado . ' paralelo ' . $paralelo . ' se encontró en el departamento médico desde ' . $hora_desde . ' hasta ' . $hora_hasta . '.';
                $pdf->MultiCell(0, 6, utf8_decode($mensaje_consulta), 0, 'J');
            } else if ($sa_conp_tipo_consulta == 'certificado') {
                $mensaje_certificado = 'Certifico que él/la representante de ' . $nombre_completo . ' del grado ' . $grado . ' paralelo ' . $paralelo . ' entrega certificado médico de representado con diagnóstico ' . $diagnostico_certificado;
                $pdf->MultiCell(0, 6, utf8_decode($mensaje_certificado), 0, 'J');
            }
        } else {
            $mensaje = 'Certifico que  ' . $nombre_completo . ' estaba en el cosultoria con diagnóstico ' . $diagnostico_consulta . ' desde ' . $hora_desde . ' hasta ' . $hora_hasta . '.';
            $pdf->MultiCell(0, 6, utf8_decode($mensaje), 0, 'J');
        }

        $pdf->ln('25');

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(60, 10, utf8_decode($nombre_medico), '0', 0, 'C');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(60, 10, utf8_decode('Representante'), '0', 1, 'R');

        $pdf->Cell(60, 10, utf8_decode('Médico Institucional'), '0', 0, 'C');

        $pdf->Output();
    }
}
