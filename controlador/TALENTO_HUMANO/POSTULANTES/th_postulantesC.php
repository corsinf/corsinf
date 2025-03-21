<?php
require_once(dirname(__DIR__, 3) . '/lib/pdf/fpdf.php');
require_once(dirname(__DIR__, 3) . '/modelo/TALENTO_HUMANO/POSTULANTES/th_postulantesM.php');

$controlador = new th_postulantesC();

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar($_POST['id']));
}

if (isset($_GET['listar_todo'])) {
    echo json_encode($controlador->listar_todo());
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['insertar_imagen'])) {
    echo json_encode($controlador->insertar_imagen($_FILES, $_POST));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['hoja_de_vida'])) {
    echo $controlador->hoja_de_vida($_GET['id']);
}

if (isset($_GET['agregar_postulante_persona'])) {
    echo json_encode($controlador->agregar_postulante_persona($_POST['pos_cedula']));
}


class th_postulantesC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new th_postulantesM();
    }

    function listar($id)
    {
        if ($id == '') {
            $datos = $this->modelo->where('th_pos_estado', 1)->listar();
        } else {
            $datos = $this->modelo->where('th_pos_id', $id)->listar();
        }

        $datos = $this->modelo->where('th_pos_id', $id)->where('th_pos_estado', 1)->listar();

        $texto = '';
        foreach ($datos as $key => $value) {
            $texto .=
                <<<HTML
                            <div class="row mb-3">
                                <div class="col-10">
                                    <a href="#" onclick="definir_ruta_iframe_cambiar_foto('{$value['th_pos_foto_url']}');">Ver foto</a>
                                </div>
                                <div class="col-2 d-flex justify-content-end align-items-center">
                                    <button class="btn icon-hover" style="color: white;" onclick="abrir_modal_cambiar_foto('{$value['_id']}')">
                                        <i class="text-dark bx bx-pencil bx-sm"></i>
                                    </button>
                                </div>
                            </div>
                        HTML;
        }


        return $datos;
        // $datos = $this->modelo->where('th_pos_id', $id)->listar($id);
        // return $datos;
    }

    function listar_todo()
    {
        $lista = $this->modelo->where('th_pos_estado', 1)->listar();
        return $lista;
    }

    function insertar_editar($parametros)
    {
        //print_r($parametros); exit(); die();
        $datos = array(
            array('campo' => 'th_pos_primer_nombre', 'dato' => $parametros['txt_primer_nombre']),
            array('campo' => 'th_pos_segundo_nombre', 'dato' => $parametros['txt_segundo_nombre']),
            array('campo' => 'th_pos_primer_apellido', 'dato' => $parametros['txt_primer_apellido']),
            array('campo' => 'th_pos_segundo_apellido', 'dato' => $parametros['txt_segundo_apellido']),
            array('campo' => 'th_pos_cedula', 'dato' => $parametros['txt_cedula']),
            array('campo' => 'th_pos_sexo', 'dato' => $parametros['ddl_sexo']),
            array('campo' => 'th_prov_id', 'dato' => $parametros['ddl_provincias']),
            array('campo' => 'th_ciu_id', 'dato' => $parametros['ddl_ciudad']),
            array('campo' => 'th_parr_id', 'dato' => $parametros['ddl_parroquia']),
            array('campo' => 'th_pos_direccion', 'dato' => $parametros['txt_direccion']),
            array('campo' => 'th_pos_postal', 'dato' => $parametros['txt_codigo_postal']),
            array('campo' => 'th_pos_fecha_nacimiento', 'dato' => $parametros['txt_fecha_nacimiento']),
            array('campo' => 'th_pos_nacionalidad', 'dato' => $parametros['ddl_nacionalidad']),
            array('campo' => 'th_pos_estado_civil', 'dato' => $parametros['ddl_estado_civil']),
            array('campo' => 'th_pos_telefono_1', 'dato' => $parametros['txt_telefono_1']),
            array('campo' => 'th_pos_telefono_2', 'dato' => $parametros['txt_telefono_2']),
            array('campo' => 'th_pos_correo', 'dato' => $parametros['txt_correo']),
        );

        if ($parametros['_id'] == '') {
            if (count($this->modelo->where('th_pos_cedula', $parametros['txt_cedula'])->listar()) == 0) {
                $datos = $this->modelo->insertar($datos);
            } else {
                return -2;
            }
        } else {
            if (count($this->modelo->where('th_pos_cedula', $parametros['txt_cedula'])->where('th_pos_id !', $parametros['_id'])->listar()) == 0) {
                $where[0]['campo'] = 'th_pos_id';
                $where[0]['dato'] = $parametros['_id'];
                $datos = $this->modelo->editar($datos, $where);
            } else {
                return -2;
            }
        }

        return $datos;
    }

    function eliminar($id)
    {
        $datos_archivo = $this->modelo->where('th_pos_id', $id)->where('th_pos_estado', 1)->listar();

        if ($datos_archivo && isset($datos_archivo[0]['th_pos_foto_url'])) {
            $ruta_relativa = ltrim($datos_archivo[0]['th_pos_foto_url'], './');
            $ruta_archivo = dirname(__DIR__, 3) . '/' . $ruta_relativa;

            if (file_exists($ruta_archivo)) {
                unlink($ruta_archivo);
            }
        }

        $datos = array(
            array('campo' => 'th_pos_estado', 'dato' => 0),
        );

        $where = array(
            array('campo' => 'th_pos_id', 'dato' => strval($id)),
        );


        $datos = $this->modelo->editar($datos, $where);
        return $datos;
    }

    function agregar_postulante_persona($cedula)
    {
        try {

            $this->modelo->agregar_postulante_personaM($cedula);

            $datos = array(
                array('campo' => 'th_pos_contratado', 'dato' => 1),
            );
            $where = array(
                array('campo' => 'th_pos_cedula', 'dato' => $cedula),
            );

            $datos = $this->modelo->editar($datos, $where);

            if ($datos == 1) {
                return [
                    'success' => 'Postulante agregado correctamente.',
                    'redirect' => '../vista/inicio.php?mod=modulo_sistema&acc=th_postulantes'
                ];
            } else {
                return [
                    'error' => 'Postulante no se agrego.',
                ];
            }
        } catch (Exception $e) {
            return array('error' => 'Error al agregar el postulante: ' . $e->getMessage());
        }
    }

    function hoja_de_vida($id)
    {
        //////////////////////////////////////////////////////////////////////////////////////////////////////
        //* Datos del Postulante
        //$datos = $this->modelo->where('th_pos_id', $id)->listar();
        //$datos = $this->modelo2->where('th_pos_id', $id)->listar();
        //$datos = $this->modelo3->where('th_pos_id', $id)->listar();
        $th_pos_primer_nombre = 'Ruben'; //$datos[0]['th_pos_primer_nombre'];
        $th_pos_segundo_nombre = 'Ruben'; //$datos[0]['th_pos_segundo_nombre'];
        $th_pos_primer_apellido = 'Ruben'; //$datos[0]['th_pos_primer_apellido'];
        $th_pos_segundo_apellido = 'Ruben'; //$datos[0]['th_pos_segundo_apellido'];
        $th_pos_nombres_completos = $th_pos_primer_nombre . ' ' . $th_pos_segundo_nombre . ' ' . $th_pos_primer_apellido . ' ' . $th_pos_segundo_apellido;
        $th_pos_correo = 'milton@corsinf.com'; //$datos[0]['th_pos_correo'];
        $th_pos_telefono_1 = '(023) 310-2928'; //$datos[0]['th_pos_telefono_1'];
        $point = ' ° ';
        $guion = ' - ';

        //? Educación
        $th_fora_institucion = 'Universidad de Harvard, Escuela Corsinf'; //$datos[0]['th_fora_institución'];
        $th_fora_titulo_obtenido = 'Licenciatura en Ciencias de la Computación'; //$datos[0]['th_fora_titulo_obtenido'];
        $th_fora_fecha_inicio_formacion = 'Septiembre 2012'; //$datos[0]['th_fora_fecha_inicio_formacion'];
        //! No pongo becas o premios porque en la tabla no hay campos para eso, ni tampoco para una segunda formación academica.

        //* Habilidades Tecnicas
        $th_hab_nombre = 'Machine Learning'; //$datos[0]['th_hab_nombre'];
        $th_tiph_nombre = 'Python/Scrikit-learn'; //$datos[0]['th_tiph_nombre'];
        //! Sucede lo mismo que en el anterior comentario, no hay campos para habilidades tecnicas, por lo que no se puede hacer un bucle para mostrar más de una habilidad.

        //? Experiencia Laboral
        $th_expl_nombre_empresa = 'Corsinf'; //$datos[0]['th_expl_nombre_empresa'];
        $th_expl_cargos_ocupados = 'Desarrollador de Software'; //$datos[0]['th_expl_cargos_ocupados'];
        $th_expl_fecha_inicio_experiencia = 'Septiembre 2012'; //$datos[0]['th_expl_fecha_inicio_experiencia'];
        $th_expl_fecha_fin_experiencia = 'Presente'; //$datos[0]['th_expl_fecha_fin_experiencia'];
        $th_expl_responsabilidades_logros = 'Desarrollo de software para la empresa'; //$datos[0]['th_expl_responsabilidades_logros'];
        //! No pongo más de una experiencia laboral porque no hay campos para eso en la tabla, por lo tanto no se puede hacer un bucle para mostrar más de una experiencia laboral.

        //////////////////////////////////////////////////////////////////////////////////////////////////////

        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->SetMargins(28, 15, 28);
        $pdf->SetAutoPageBreak(true, 15);
        $pdf->AddPage();
        $bullet = chr(149);

        $pdf->SetFont('Times', 'B', 22);
        $pdf->Cell(10, 10, utf8_decode('Hoja de Vida'), 0, 1, 0);
        $pdf->Ln(10);

        //* Nombre, Apellido, Correo y Teléfono
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(60, 7, utf8_decode(''), 0, 0, 'C');
        $pdf->Cell(40, 7, utf8_decode($th_pos_nombres_completos), 0, 1, 'C');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(60, 7, utf8_decode(''), 0, 0, 'C');
        $pdf->Cell(40, 7, utf8_decode($th_pos_correo . $point . $th_pos_telefono_1), 0, 0, 'C');
        $pdf->Ln(10);

        //* Educación
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(60, 7, utf8_decode(''), 0, 0, 'C');
        $pdf->Cell(40, 14, utf8_decode('Educación'), 0, 1, 'C');
        $pdf->Cell(0, 2, utf8_decode(''), 'T', 1, 'C');
        $pdf->Cell(60, 7, utf8_decode($th_fora_institucion), 0, 1, 'L');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(60, 7, utf8_decode($th_fora_titulo_obtenido), 0, 0, 'L');
        $pdf->Cell(40, 7, utf8_decode(''), 0, 0, 'R');
        $pdf->Cell(55, 7, utf8_decode($th_fora_fecha_inicio_formacion), 0, 1, 'R');
        // $pdf->Cell(10, 5.5, $bullet, 0, 0,'L');
        // $pdf->Cell(0, 5.5, utf8_decode('Premio Marshall'), 0, 1,'L');
        // $pdf->Cell(10, 5.5, $bullet, 0, 0,'L');
        // $pdf->Cell(0, 5.5, utf8_decode('Beca de la Fundación Gates'), 0, 1,'L');
        // $pdf->Cell(10, 5.5, $bullet, 0, 0,'L');
        // $pdf->Cell(0, 5.5, utf8_decode('Beca de la Fundación Rubén'), 0, 1,'L');
        // $pdf->Cell(10, 5.5, $bullet, 0, 0,'L');
        // $pdf->Cell(0, 5.5, utf8_decode('Beca de la Fundación Milton'), 0, 1,'L');
        $pdf->Ln(5);

        //! En th_formacion_academica no existe forma de poner una segunda educación, por lo que no se puede hacer un bucle para mostrar más de una educación.
        // $pdf->SetFont('Arial', 'B', 11);
        // $pdf->Cell(60, 7, utf8_decode('Universidad de Malacia'), 0, 1,'L');
        // $pdf->SetFont('Arial', '', 11);
        // $pdf->Cell(60, 7, utf8_decode('Bachiller en Ciencias de la Computación'), 0, 0,'L');
        // $pdf->Cell(40, 7, utf8_decode(''), 0, 0,'R');
        // $pdf->Cell(55, 7, utf8_decode('Agosto 2008'), 0, 1,'R');
        $pdf->Ln(6);

        //? Habilidades Tecnicas
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(60, 7, utf8_decode(''), 0, 0, 'C');
        $pdf->Cell(40, 14, utf8_decode('Habilidades Tecnicas'), 0, 1, 'C');
        $pdf->Cell(0, 2, utf8_decode(''), 'T', 1, 'C');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(2.5, 5.5, $bullet, 0, 0, 'L');
        $pdf->Cell(35, 5.5, utf8_decode($th_hab_nombre), 0, 0, 'L');
        $pdf->Cell(2.5, 5.5, $bullet, 0, 0, 'L');
        $pdf->Cell(40, 5.5, utf8_decode($th_tiph_nombre), 0, 0, 'L');
        $pdf->Cell(2.5, 5.5, $bullet, 0, 0, 'L');
        $pdf->Cell(35, 5.5, utf8_decode('Spark'), 0, 0, 'L');
        $pdf->Cell(2.5, 5.5, $bullet, 0, 0, 'L');
        $pdf->Cell(20, 5.5, utf8_decode('Data Visualization'), 0, 1, 'L');

        $pdf->Cell(2.5, 5.5, $bullet, 0, 0, 'L');
        $pdf->Cell(35, 5.5, utf8_decode('Quantitative'), 0, 0, 'L');
        $pdf->Cell(2.5, 5.5, $bullet, 0, 0, 'L');
        $pdf->Cell(40, 5.5, utf8_decode('Cloud Scripting'), 0, 0, 'L');
        $pdf->Cell(2.5, 5.5, $bullet, 0, 0, 'L');
        $pdf->Cell(35, 5.5, utf8_decode('Hadoop'), 0, 0, 'L');
        $pdf->Cell(2.5, 5.5, $bullet, 0, 0, 'L');
        $pdf->Cell(20, 5.5, utf8_decode('Java C#'), 0, 1, 'L');

        $pdf->Cell(2.5, 5.5, $bullet, 0, 0, 'L');
        $pdf->Cell(35, 5.5, utf8_decode('Unix Scripting'), 0, 0, 'L');
        $pdf->Cell(2.5, 5.5, $bullet, 0, 0, 'L');
        $pdf->Cell(40, 5.5, utf8_decode('Oracle/SQL Server'), 0, 0, 'L');
        $pdf->Cell(2.5, 5.5, $bullet, 0, 0, 'L');
        $pdf->Cell(35, 5.5, utf8_decode('PLSQL/T-SQL'), 0, 0, 'L');
        $pdf->Cell(2.5, 5.5, $bullet, 0, 0, 'L');
        $pdf->Cell(20, 5.5, utf8_decode('Data Warehouse/ETL'), 0, 1, 'L');

        $pdf->Cell(2.5, 5.5, $bullet, 0, 0, 'L');
        $pdf->Cell(35, 5.5, utf8_decode('RDBMS Tuning'), 0, 0, 'L');
        $pdf->Cell(2.5, 5.5, $bullet, 0, 0, 'L');
        $pdf->Cell(40, 5.5, utf8_decode('Network Protocals'), 0, 0, 'L');
        $pdf->Cell(2.5, 5.5, $bullet, 0, 0, 'L');
        $pdf->Cell(35, 5.5, utf8_decode('Agile & DevOps'), 0, 0, 'L');
        $pdf->Cell(2.5, 5.5, $bullet, 0, 0, 'L');
        $pdf->Cell(20, 5.5, utf8_decode('Web Development'), 0, 1, 'L');
        $pdf->Ln(6);

        //* Experiencia Laboral
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(60, 7, utf8_decode(''), 0, 0, 'C');
        $pdf->Cell(40, 14, utf8_decode('Experiencia Profesional'), 0, 1, 'C');
        $pdf->Cell(0, 2, utf8_decode(''), 'T', 1, 'C');

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(60, 7, utf8_decode($th_expl_nombre_empresa), 0, 0, 'L');
        $pdf->Cell(40, 7, utf8_decode($th_expl_cargos_ocupados), 0, 0, 'R');
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(60, 7, utf8_decode($th_expl_responsabilidades_logros), 0, 0, 'L');
        $pdf->Cell(40, 7, utf8_decode(''), 0, 0, 'R');
        $pdf->Cell(55, 7, utf8_decode($th_expl_fecha_inicio_experiencia . $guion . $th_expl_fecha_fin_experiencia), 0, 1, 'R');
        $pdf->SetFillColor(255, 255, 255);
        $pdf->Cell(10, 5.5, $bullet, 0, 0, 'L');
        $pdf->MultiCell(0, 5.5, utf8_decode('Built Command & Command System for Singapore Civil Defence Force using C# .NET WCF Services'), 0, 1, 'L');
        $pdf->Cell(10, 5.5, $bullet, 0, 0, 'L');
        $pdf->MultiCell(0, 5.5, utf8_decode('Integrated propietary software components with commercial off the shell software product'), 0, 1, 'L');
        $pdf->Ln(5);

        //? Certificaciones
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(60, 7, utf8_decode(''), 0, 0, 'C');
        $pdf->Cell(40, 14, utf8_decode('Certificaciones'), 0, 1, 'C');
        $pdf->Cell(0, 2, utf8_decode(''), 'T', 1, 'C');

        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(10, 7, $bullet, 0, 0, 'L');
        $pdf->Cell(60, 7, utf8_decode('4-Course graduado en ciencias de datos'), 0, 0, 'L');
        $pdf->Cell(30, 7, utf8_decode(''), 0, 0, 'R');
        $pdf->Cell(55, 7, utf8_decode('Septiembre 2013'), 0, 1, 'R');
        $pdf->Cell(10, 7, $bullet, 0, 0, 'L');
        $pdf->Cell(60, 7, utf8_decode('ITIL fundation V3'), 0, 0, 'L');
        $pdf->Cell(30, 7, utf8_decode(''), 0, 0, 'R');
        $pdf->Cell(55, 7, utf8_decode('Enero 2015'), 0, 1, 'R');
        $pdf->Cell(10, 7, $bullet, 0, 0, 'L');
        $pdf->Cell(60, 7, utf8_decode('Project Management Professional PMP'), 0, 0, 'L');
        $pdf->Cell(30, 7, utf8_decode(''), 0, 0, 'R');
        $pdf->Cell(55, 7, utf8_decode('Octubre 2012'), 0, 1, 'R');

        //! Salida del PDF
        $pdf->Output();
    }

    //Para colocar una imagen a un postulante existente
    function insertar_imagen($file, $parametros)
    {
        // print_r($file);
        // exit();
        // die();

        // $datos = array(
        //     array('campo' => 'th_refl_nombre_empresa', 'dato' => $parametros['txt_referencia_nombre_empresa']),
        // );

        $id_postulante = $parametros['txt_postulante_id'];

        if ($id_postulante != '') {
            // $where = array(
            //     array('campo' => 'th_refl_id', 'dato' => $id_postulante),
            // );

            // $datos = $this->modelo->editar($datos, $where);

            if ($file['txt_copia_cambiar_foto']['tmp_name'] != '' && $file['txt_copia_cambiar_foto']['tmp_name'] != null) {
                $datos = $this->guardar_archivo($file, $parametros, $id_postulante);
            }
        }

        return $datos;
    }

    private function guardar_archivo($file, $post, $id_insertar_editar)
    {
        // Obtener el ID de la empresa desde la sesión
        $id_empresa = $_SESSION['INICIO']['ID_EMPRESA'];

        // Definir la ruta donde se guardarán las imágenes
        $ruta = dirname(__DIR__, 3) . '/REPOSITORIO/TALENTO_HUMANO/' . $id_empresa . '/'; // Ruta base
        $ruta .= $post['txt_postulante_cedula'] . '/' . 'FOTO_PERFIL/'; // Ruta completa del postulante

        // Verificar si la carpeta existe, si no, crearla
        if (!file_exists($ruta)) {
            mkdir($ruta, 0777, true);
        }

        // Validar si el archivo tiene el formato correcto (usando la función que debes tener implementada)

        if ($this->validar_formato($file) === 1) {

            // Obtener la ubicación temporal del archivo cargado
            $uploadfile_temporal = $file['txt_copia_cambiar_foto']['tmp_name'];
            // Obtener la extensión del archivo de la imagen
            $extension = pathinfo($file['txt_copia_cambiar_foto']['name'], PATHINFO_EXTENSION);

            // Crear un nuevo nombre para la imagen
            $nombre = 'foto_perfil_' . $id_insertar_editar . '.' . $extension;
            $nuevo_nom = $ruta . $nombre; // Nombre completo con la ruta donde se guardará la imagen

            // Ruta final que se almacenará en la base de datos
            $nombre_ruta = '../REPOSITORIO/TALENTO_HUMANO/' . $id_empresa . '/' . $post['txt_postulante_cedula'] . '/' . 'FOTO_PERFIL/';
            $nombre_ruta .= $nombre; // Ruta completa en el repositorio

            // Verificar si el archivo ha sido cargado correctamente
            if (is_uploaded_file($uploadfile_temporal)) {
                // Mover el archivo de su ubicación temporal al destino final
                if (move_uploaded_file($uploadfile_temporal, $nuevo_nom)) {

                    // Datos para actualizar la URL de la foto en la base de datos
                    $datos = array(
                        array('campo' => 'th_pos_foto_url', 'dato' => $nombre_ruta),
                    );

                    // Condición para identificar el postulante que se debe actualizar
                    $where = array(
                        array('campo' => 'th_pos_id', 'dato' => $id_insertar_editar),
                    );

                    // Ejecutar la actualización en la base de datos
                    $base = $this->modelo->editar($datos, $where);

                    // Si la actualización fue exitosa, retornar 1
                    return $base == 1 ? 1 : -1;
                } else {
                    // Si ocurre un error al mover el archivo, retornar -1
                    return -1;
                }
            } else {
                // Si el archivo no ha sido cargado correctamente, retornar -1
                return -1;
            }
        } else {
            // Si el formato de la imagen es inválido, retornar -2
            return -2;
        }
    }

    //Sirve para validar imagenes 
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
