<?php

require_once(dirname(__DIR__, 2) . '/modelo/EDUCATIVO/herramientas_gpaM.php');
require_once(dirname(__DIR__, 2) . '/db/codigos_globales.php');
// require_once(dirname(__DIR__, 2) . '/lib/pdf/cabecera_pdf.php');
require_once(dirname(__DIR__, 2) . '/lib/TCPDF/tcpdf.php');
require_once(dirname(__DIR__, 2) . '/lib/PDFPARSER/vendor/autoload.php');

use Smalot\PdfParser\Parser;

/**
 * 
 */
$controlador = new herramienta_gpa_conversionC();

// if (isset($_GET['gpa_pdf'])) {
//     $encodedArray = $_GET['data'];
//     $idioma = $_GET['idioma'];
//     $datos = json_decode(urldecode($encodedArray), true);
//     echo json_encode($controlador->gpa_pdf($datos, $idioma));
// }

if (isset($_GET['gpa_pdf_conversion'])) {
    echo ($controlador->conversion_gpa());
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_FILES, $_POST));
}


class herramienta_gpa_conversionC
{
    private $modelo;
    private $cod_global;


    function __construct()
    {
        $this->modelo = new herramientas_gpaM();
        $this->cod_global = new codigos_globales();
        // $this->pdf = new cabecera_pdf();
    }

    function insertar_editar($file, $parametros)
    {
        // print_r($file);
        // exit();
        // die();

        $datos = array(
            // array('campo' => 'th_refl_nombre_referencia', 'dato' => $parametros['txt_nombre_referencia']),
            // array('campo' => 'th_refl_telefono_referencia', 'dato' => $parametros['txt_telefono_referencia']),
            // //array('campo' => 'th_refl_carta_recomendacion', 'dato' => $parametros['txt_copia_archivo']), 
            // array('campo' => 'th_pos_id', 'dato' => $parametros['txt_postulante_id']),
            // array('campo' => 'th_refl_correo', 'dato' => $parametros['txt_referencia_correo']),
            // array('campo' => 'th_refl_nombre_empresa', 'dato' => $parametros['txt_referencia_nombre_empresa']),
        );

        // $id_referencias_laboral = $parametros['txt_referencias_laborales_id'];

        // if ($id_referencias_laboral == '') {
        return $this->guardar_archivo($file, $parametros, 1);
        // return 1;
        // } else {
        //     if ($file['txt_copia_archivo']['tmp_name'] != '' && $file['txt_copia_archivo']['tmp_name'] != null) {
        //         $datos = $this->guardar_archivo($file, $parametros, $id_referencias_laboral);
        //     }
        // }

        // return $datos;
    }

    private function guardar_archivo($file, $post, $id_insertar_editar)
    {
        $id_empresa = $_SESSION['INICIO']['ID_EMPRESA'];
        $ruta = dirname(__DIR__, 2) . '/REPOSITORIO/SALUD_EDUCATIVO/' . $id_empresa . '/'; //ruta carpeta donde queremos copiar los archivos
        $ruta .= $post['txt_estudiante_cedula'] . '/' . 'GPA/';

        if (!file_exists($ruta)) {
            mkdir($ruta, 0777, true);
        }

        // print_r($ruta);
        // exit();
        // die();

        if ($this->validar_formato_archivo($file) === 1) {
            $uploadfile_temporal = $file['txt_copia_archivo']['tmp_name'];
            $extension = pathinfo($file['txt_copia_archivo']['name'], PATHINFO_EXTENSION);
            //Para referencias laborales
            $nombre = 'GPA_' . $id_insertar_editar . '.' . $extension;
            $nuevo_nom = $ruta . $nombre;

            $nombre_ruta = '../REPOSITORIO/SALUD_EDUCATIVO/' . $id_empresa . '/' . $post['txt_estudiante_cedula'] . '/' . 'GPA/';
            $nombre_ruta .= $nombre;
            //print_r($post); exit(); die();

            if (is_uploaded_file($uploadfile_temporal)) {
                if (move_uploaded_file($uploadfile_temporal, $nuevo_nom)) {



                    return 1;
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
        switch ($file['txt_copia_archivo']['type']) {
            case 'application/pdf':
                return 1;
                break;
            default:
                return -1;
                break;
        }
    }

    function conversion_gpa_1()
    {
        // Parsear el texto del PDF
        $parser = new Parser();

        $rutaPdf = dirname(__DIR__, 2) . '/REPOSITORIO/SALUD_EDUCATIVO/1042/123456/GPA/GPA_1.pdf';
        $pdf = $parser->parseFile($rutaPdf);
        // Extraer texto del PDF
        $texto = $pdf->getText();

        // Buscar la sección CALIFICACIÓN CUALITATIVA hasta Promedio General
        $pattern = '/CALIFICACIÓN CUALITATIVA(.*?)Promedio General/s';
        preg_match($pattern, $texto, $matches);

        if (isset($matches[1])) {
            $calificacionesTexto = $matches[1];
            $datos = [];
            $lines = explode("\n", $calificacionesTexto);
            $cursos = $this->array_cursos();

            foreach ($lines as $line) {
                // Validar si la línea contiene un curso válido
                foreach ($cursos as $curso) {
                    if (str_contains($line, $curso)) {
                        // Ajustar la expresión regular para incluir los valores esperados
                        if (preg_match('/^(.+?)\s(.+?)\s([\d\.]+)\s(.+)$/', $line, $matches)) {
                            $datos[] = [
                                'area' => trim($matches[1]),
                                'text' => trim($matches[2]),
                                'nota' => floatval($matches[3]),
                            ];
                        }
                        break; // Salir del foreach interno
                    }
                }
            }

            // Mostrar el array de calificaciones
            //print_r($datos);
            $this->gpa_pdf_conversion($datos, true);
        } else {
            echo "No se encontró la sección 'CALIFICACIÓN CUALITATIVA'.";
        }
    }

    function conversion_gpa()
    {
        // Ruta al archivo PDF
        $rutaPdf = dirname(__DIR__, 2) . '/REPOSITORIO/SALUD_EDUCATIVO/1042/123456/GPA/GPA_1.pdf';

        // Parsear el texto del PDF
        $parser = new Parser();
        $pdf = $parser->parseFile($rutaPdf);

        // Extraer texto del PDF
        $texto = $pdf->getText();

        // Mostrar el texto extraído (para verificar que contiene "CUALITATIVA")
        // echo nl2br(htmlspecialchars($texto));
        // exit;

        // Buscar la sección que empieza en "CUALITATIVA" y termina en "Promedio General"
        //$pattern = '/CUALITATIVA(.*?)Promedio General/s';

        $pattern = '/CUALITATIVA(.*?)Promedio/si';

        // Realizar la búsqueda
        if (preg_match($pattern, $texto, $matches)) {

            $calificacionesTexto = $matches[1];

            // Procesar cada línea para crear el array
            $datos = [];
            $lineas = explode("\n", $calificacionesTexto);

            //echo json_encode($lineas);
            //exit;

            // $lineas = [
            //     "",
            //     "Matematica Matematica 7.81 ALCANZA LOS APRENDIZAJES REQUERIDOS",
            //     "Ciencias Naturales Fisica 8.12 ALCANZA LOS APRENDIZAJES REQUERIDOS",
            //     "Ciencias Naturales Quimica 9.38 DOMINA LOS APRENDIZAJES REQUERIDOS",
            //     "Ciencias Naturales Biologia 9.15 DOMINA LOS APRENDIZAJES REQUERIDOS",
            //     "Ciencia Sociales Historia 9.43 DOMINA LOS APRENDIZAJES REQUERIDOS",
            //     "Ciencia Sociales Filosofia 9.37 DOMINA LOS APRENDIZAJES REQUERIDOS",
            //     "Lenguaje Extrangero Gramatica 9.03 DOMINA LOS APRENDIZAJES REQUERIDOS",
            //     "Lenguaje Extrangero Escrita 8.93",
            //     "Lenguaje Extrangero Lectora 9.25 DOMINA LOS APRENDIZAJES REQUERIDOS",
            //     "Educacion Física Educación Fisica (gym) 9.01 DOMINA LOS APRENDIZAJES REQUERIDOS",
            //     "Modulo interdisiplinario Emprendimiento 8.66 ALCANZA LOS APRENDIZAJES REQUERIDOS",
            //     ""
            // ];

            //temer en cuenta los puntos aumentados
            $datos = $this->procesar_lineas($lineas);

            $this->gpa_pdf_conversion($datos, true);

            // Mostrar el array como JSON
            //echo json_encode($datos);
            //print_r($datos);
        } else {
            echo "No se encontró la sección 'CUALITATIVA'.";
        }
    }


    function conversion_gpa_buena()
    {
        // Ruta al archivo PDF

        $rutaPdf = dirname(__DIR__, 2) . '/REPOSITORIO/SALUD_EDUCATIVO/1042/123456/GPA/GPA_1.pdf';

        // print_r($rutaPdf);
        // exit();
        // die();


        // Parsear el texto del PDF
        $parser = new Parser();
        $pdf = $parser->parseFile($rutaPdf);

        // Extraer texto del PDF
        $texto = $pdf->getText();

        // Buscar la sección CALIFICACIÓN CUALITATIVA hasta Promedio General
        $pattern = '/CUALITATIVA(.*?)Promedio General/s';

        // Realizar la búsqueda
        preg_match($pattern, $texto, $matches);

        // echo json_encode(htmlspecialchars($texto));
        // exit;

        // Si se encuentra el texto, extraer las calificaciones
        if (isset($matches[1])) {
            $calificacionesTexto = $matches[1];

            // Procesar cada línea para crear el array
            $datos = [];
            $lines = explode("\n", $calificacionesTexto);

            foreach ($lines as $line) {
                // Si la línea tiene la estructura correcta, extraer los valores
                if (preg_match('/^([a-zA-Z\s]+)\s([a-zA-Z\s]+)\s([\d\.]+)\s(.+)$/', $line, $matches)) {
                    $area = trim($matches[1]);
                    $text = trim($matches[2]);
                    $nota = floatval($matches[3]);

                    // Añadir al array
                    $datos[] = [
                        'area' => $area,
                        'text' => $text,
                        'nota' => $nota
                    ];
                }
            }

            // Mostrar el array de calificaciones
            //print_r($datos);

            $this->gpa_pdf_conversion($datos, $idioma = true);
        } else {
            echo "No se encontró la sección 'CALIFICACIÓN CUALITATIVA'.";
        }
    }

    ///////////////////////////////////////////////////////////
    function gpa_pdf_conversion($datos = [], $idioma)
    {
        // Crear objeto TCPDF
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // Configuración básica
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Saint Dominic School');
        $pdf->SetTitle('GPA');
        $pdf->SetSubject('Certificado de Promoción');
        $pdf->SetKeywords('TCPDF, PDF, report, promotion, certificate');

        // Establecer márgenes
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);

        // Añadir página
        $pdf->AddPage();

        // Establecer fuente
        $pdf->SetFont('helvetica', '', 12);

        // Agregar imagenes
        $imagePath1 = dirname(__DIR__, 2) . '/img/de_sistema/escudo_ec.png';
        $imagePath2 = dirname(__DIR__, 2) . '/img/de_sistema/LOGOMINEDUC.png';
        $pdf->Image($imagePath1, 10, 10, 65, 25);
        $pdf->Image($imagePath2, 140, 5, 60, 30);

        // Contenido texto
        $ingles = $idioma;

        // En esta seccion se va a armar todo el texto del pdf

        //Primera linea
        $text_1 = 'SUBSECRETARIA DE EDUCACIÓN METROPOLITANA DE QUITO';
        if ($ingles) {
            $text_1 = 'METROPOLITAN UNDERSECRETARY OF EDUCATION OF QUITO';
        }

        //Segunda linea
        // Datos adicionales
        $text_2 = 'CODIGO AMIE: 17H01716';
        $text_3 = 'AÑO LECTIVO: 2021-2022';
        $text_4 = 'RÉGIMEN: SIERRA';

        if ($ingles) {
            $text_2 = 'AMIE CODE: 17H01716';
            $text_3 = 'ACADEMIC YEAR: 2021-2022';
            $text_4 = 'REGIME: HIGHLANDS';
        }

        //Tercera linea
        // Título del Certificado
        $text_5 = 'CERTIFICADO DE  PROMOCIÓN';
        if ($ingles) {
            $text_5 = 'PROMOTION CERTIFICATE';
        }

        //Cuarta linea
        // El rector
        $text_6 = 'El Rector (a)/ Director (a) de la institución Educativa';
        if ($ingles) {
            $text_6 = 'The Rector of the School:';
        }

        //Quinta linea
        // Nombre de la institución
        $text_7 = 'Unidad Educativa Particular "Saint Dominic School"';
        if ($ingles) {
            $text_7 = '"SAINT DOMINIC SCHOOL" PARTICULAR SCHOOL';
        }

        //Sexta linea
        // Introducción
        $text_8 = 'De conformidad con lo prescrito en el Art. 197 de Reglamento General a la ley Órganica de Educación Intercultural y demás normativas vigentes, certifica que el/la estudiante:';
        if ($ingles) {
            $text_8 = 'According to Art. 197 of the General Rulebook of the Organic Law of Intercultural Education and other current rules, certifies that student:';
        }

        //Septima linea
        // Información de estudiante
        $text_9 = 'RUBIO CHILLAGANA ANNY MAITE';

        //Septima linea
        // Detalle de calificaciones
        $text_10 = 'del PRIMER CURSO DE BACHILLERATO GENERAL UNIFICADO E, CÓDIGO DEL ESTUDIANTE N° 1753987757 obtuvo las siguientes calificaciones durante el presente año lectivo';
        if ($ingles) {
            $text_10 = 'student of the First Year of General Unified Baccalaureate "E", with student code N° 1753987757, obtained the following grades throughout the academic year:';
        }

        //Octava linea
        // Encabezado de calificaciones
        $text_11 = 'ÁREAS';
        $text_12 = 'ASIGNATURAS';
        $text_13 = 'PROMEDIO ANUAL';
        if ($ingles) {
            $text_11 = 'Areas';
            $text_12 = 'Subject';
            $text_13 = 'Annual Average';
        }

        $text_14 = 'Por lo tanto, es promovido/a al Segundo Curso de Bachillerato General unificado, para constancia suscriben en unidad de acta el/la RECTORA con el/la SECRETARIO GENERAL del plantel que certifica.';
        if ($ingles) {
            $text_14 = "Therefore, she is promoted to the Second Year of General Unified Baccalaureate...";
        }

        //----------------------------------------------------------------------------------------------------------
        $pdf->Ln(29);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 10, $text_1, 0, 1, 'C');
        //----------------------------------------------------------------------------------------------------------
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(64, 5, $text_2, 0, 0, 'L');
        $pdf->Cell(64, 5, $text_3, 0, 0, 'L');
        $pdf->Cell(64, 5, $text_4, 0, 1, 'L');
        //----------------------------------------------------------------------------------------------------------
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(0, 10, $text_5, 0, 1, 'C');
        //----------------------------------------------------------------------------------------------------------
        $pdf->SetFont('helvetica', '', 10);
        $pdf->MultiCell(0, 10, $text_6, 0, 'L');
        //----------------------------------------------------------------------------------------------------------        
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->MultiCell(0, 10, $text_7, 0, 'C');
        //----------------------------------------------------------------------------------------------------------        
        $pdf->SetFont('helvetica', '', 10);
        $pdf->MultiCell(0, 10, $text_8, 0, 'L');
        //----------------------------------------------------------------------------------------------------------        
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->MultiCell(0, 10, $text_9, 0, 'C');
        //----------------------------------------------------------------------------------------------------------        
        $pdf->SetFont('helvetica', '', 10);
        $pdf->MultiCell(0, 10, $text_10, 0, 'L');
        //----------------------------------------------------------------------------------------------------------        
        $pdf->Ln(2);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(43, 10, $text_11, 1, 0, 'L');
        $pdf->Cell(43, 10, $text_12, 1, 0, 'L');
        $pdf->Cell(106, 10, $text_13, 1, 1, 'C');

        // Calificaciones (llenado con los datos)
        $promedio = 0;
        $n_nota = 0;

        //Datos de ejemplo
        // $datos = [
        //     ['area' => 'Matemáticas', 'text' => 'Álgebra', 'nota' => 9.5],
        //     ['area' => 'Matemáticas', 'text' => 'Geometría', 'nota' => 8.7],
        //     ['area' => 'Ciencias', 'text' => 'Biología', 'nota' => 9.2],
        //     ['area' => 'Ciencias', 'text' => 'Física', 'nota' => 7.8],
        //     ['area' => 'Lengua y Literatura', 'text' => 'Literatura', 'nota' => 8.9],
        //     ['area' => 'Lengua y Literatura', 'text' => 'Redacción', 'nota' => 9.0],
        //     ['area' => 'Historia', 'text' => 'Historia Universal', 'nota' => 8.5],
        //     ['area' => 'Historia', 'text' => 'Historia de Ecuador', 'nota' => 9.3],
        //     ['area' => 'Inglés', 'text' => 'Inglés básico', 'nota' => 9.0],
        //     ['area' => 'Inglés', 'text' => 'Inglés avanzado', 'nota' => 8.8],
        // ];

        $pdf->SetFont('helvetica', '', 10);
        foreach ($datos as $key => $value) {
            $pdf->Cell(43, 5, $this->ingles($value['area'], $ingles), 1, 0, 'L');
            $pdf->Cell(43, 5, $this->ingles($value['asignatura'], $ingles), 1, 0, 'L');
            $pdf->Cell(28, 5, $value['nota'] . ' (' . $this->promedio_valoraciongpa($value['nota']) . ')', 1, 0, 'C');
            $pdf->Cell(78, 5, $this->valoracion($value['nota'], $ingles), 1, 1, 'L');
            if (is_numeric($value['nota'])) {
                $promedio += floatval($value['nota']);
                $n_nota++;
            }
        }

        // Promedio final
        $promedio = $promedio / $n_nota;
        $letra = $this->promedio_valoraciongpa($promedio);
        $pdf->Cell(86, 5, $this->ingles("Promedio General", $ingles), 1, 0, 'L');
        $pdf->Cell(28, 5, $letra, 1, 0, 'C');
        $pdf->Cell(78, 5, $this->valoracion($promedio, $ingles), 1, 1, 'C');

        // Evaluación del comportamiento
        $pdf->Cell(86, 5, $this->ingles("Evaluacion del comportamiento", $ingles), 1, 0, 'L');
        $pdf->Cell(28, 5, $this->promedio_valoraciongpa($promedio), 1, 0, 'C');
        $pdf->Cell(78, 5, $this->valoracion($this->promedio_valoraciongpa($value['nota']), $ingles), 1, 1, 'C');

        // Firma y fecha
        $pdf->Ln(5);
        $pdf->MultiCell(0, 10, $text_14 . ' ' . date('r'), 0, 'L');

        // Firma final
        $pdf->MultiCell(0, 10, '', 0, 'L');
        $pdf->MultiCell(0, 10, '', 0, 'L');

        // Firmas
        $pdf->Cell(15, 10, '', 0, 0, 'L');
        $pdf->Cell(65, 10, '_________________________________', 0, 0, 'C');
        $pdf->Cell(30, 10, '', 0, 0, 'L');
        $pdf->Cell(65, 10, '_________________________________', 0, 1, 'C');

        // Guardar el PDF
        $pdf->Output('gpa.pdf', 'I');
    }


    function gpa_pdf($datos, $idioma)
    {
        $titulo = "Reporte bajas";
        $ingles = $idioma;
        $sizetable = 9;
        $pos = 1;


        $image[0] = array('url' => dirname(__DIR__, 2) . '/img/de_sistema/escudo_ec.png', 'x' => 10, 'y' => 10, 'width' => 65, 'height' => 25);
        $image[1] = array('url' => dirname(__DIR__, 2) . '/img/de_sistema/LOGOMINEDUC.png', 'x' => 140, 'y' => 5, 'width' => 60, 'height' => 30);

        $text = 'SUBSECRETARIA DE EDUCACIÓN METROPOLITANA DE QUITO';
        if ($ingles) {
            $text = 'METROPOLITAN UNDERSECRETARY OF EDUCATION OF QUITO';
        }
        $tablaHTML = array();
        $tablaHTML[0]['medidas'] = array(192);
        $tablaHTML[0]['alineado'] = array('C');
        $tablaHTML[0]['datos'] = array($text);
        // $tablaHTML[0]['estilo']='B';
        // $tablaHTML[0]['borde'] = '1';

        $text = 'CODIGO AMIE: 17H01716';
        $text2 = 'AÑO LECTIVO: 2021-2022';
        $text3 = 'RÉGIMEN: SIERRA';
        if ($ingles) {
            $text = 'AMIE CODE: 17H01716';
            $text2 = 'ACADEMIC YEAR: 2021-2022 ';
            $text3 = 'REGIME: HIGHLANDS';
        }
        $tablaHTML[1]['medidas'] = array(70, 70, 70);
        $tablaHTML[1]['alineado'] = array('L', 'L', 'L');
        $tablaHTML[1]['datos'] = array($text, $text2, $text3);
        // $tablaHTML[1]['estilo']='';
        // $tablaHTML[0]['borde'] = '1';

        $text = 'CERTIFICADO DE  PROMOCIÓN';
        if ($ingles) {
            $text = 'PROMOTION CERTIFICATE';
        }
        $tablaHTML[2]['medidas'] = array(192);
        $tablaHTML[2]['alineado'] = array('C');
        $tablaHTML[2]['datos'] = array($text);
        $tablaHTML[2]['estilo'] = 'B';
        // $tablaHTML[2]['borde'] = '1';

        $text = 'El Rector (a)/ Director (a) de la institución Educativa';
        if ($ingles) {
            $text = 'The Rector of the School:';
        }
        $tablaHTML[4]['medidas'] = array(192);
        $tablaHTML[4]['alineado'] = array('L');
        $tablaHTML[4]['datos'] = array($text);
        $tablaHTML[4]['estilo'] = '';
        // $tablaHTML[4]['borde'] = '1';

        $text = 'Unidad Educativa Particular "Saint Dominic School"';
        if ($ingles) {
            $text = '"SAINT DOMINIC SCHOOL" PARTICULAR SCHOOL';
        }
        $tablaHTML[5]['medidas'] = array(192);
        $tablaHTML[5]['alineado'] = array('C');
        $tablaHTML[5]['datos'] = array($text);
        $tablaHTML[5]['estilo'] = 'B';
        $tablaHTML[5]['size'] = 13;


        $text = 'De conformidad con lo prescrito en el Art. 197 de Reglamento General a la ley Órganica de Educación Intercultural y demás normativas vigentes, certifica que el/la estudiante:';
        if ($ingles) {
            $text = 'According to Art. 197 of the General Rulebook of the Organic Law of Intercultural Education and other currentrules, certifies that student:';
        }
        $tablaHTML[6]['medidas'] = array(192);
        $tablaHTML[6]['alineado'] = array('L');
        $tablaHTML[6]['datos'] = array($text);
        $tablaHTML[6]['estilo'] = '';

        $tablaHTML[7]['medidas'] = array(192);
        $tablaHTML[7]['alineado'] = array('C');
        $tablaHTML[7]['datos'] = array('RUBIO CHILLAGANA ANNY MAITE');
        $tablaHTML[7]['estilo'] = 'B';
        $tablaHTML[7]['size'] = 12;


        $text = 'del PRIMER CURSO DE BACHILLERATO GENERAL UNIFICADO E,CÓDIGO DEL ESTUDIANTE N° 1753987757 obtuvo las siguientes calificaciones durante el presente año lectivo';
        if ($ingles) {
            $text = 'student of the First Year of General Unified Baccalaureate "E", with student code N° 1753987757, obtained the following grades throughout the academic year:';
        }
        $tablaHTML[8]['medidas'] = array(192);
        $tablaHTML[8]['alineado'] = array('L');
        $tablaHTML[8]['datos'] = array($text);

        $text = 'ÁREAS';
        $text2 = 'ASIGNATURAS';
        $text3 = 'PROMEDIO ANUAL';
        if ($ingles) {
            $text = 'Areas';
            $text2 = 'Subject';
            $text3 = 'Annual Average';
        }


        $tablaHTML[9]['medidas'] = array(48, 48, 96);
        $tablaHTML[9]['alineado'] = array('L', 'L', 'C');
        $tablaHTML[9]['datos'] = array($text, $text2, $text3);
        $tablaHTML[9]['borde'] = 'LRT';

        $text = 'del PRIMER CURSO DE BACHILLERATO GENERAL UNIFICADO E,CÓDIGO DEL ESTUDIANTE N° 1753987757 obtuvo las siguientes calificaciones durante el presente año lectivo';
        if ($ingles) {
            $text = 'student of the First Year of General Unified Baccalaureate "E", with student code N° 1753987757, obtained the following grades throughout the academic year:';
        }
        $tablaHTML[10]['medidas'] = array(48, 48, 20, 76);
        $tablaHTML[10]['alineado'] = array('L', 'L', 'L', 'L');
        $tablaHTML[10]['datos'] = array('', '', 'Quantitative score', 'Qualitative score');
        $tablaHTML[10]['borde'] = '1';

        $text = 'CALIFICACIÓN CUANTITATIVA';
        $text2 = 'CALIFICACIÓN CUALITATIVA';
        if ($ingles) {
            $text = 'Quantitative score';
            $text2 = 'Qualitative score';
        }
        $tablaHTML[10]['medidas'] = array(48, 48, 20, 76);
        $tablaHTML[10]['alineado'] = array('L', 'L', 'L', 'L');
        $tablaHTML[10]['datos'] = array('', '', $text, $text2);
        $tablaHTML[10]['borde'] = '1';

        $pos = 11;
        $area = '';
        $promedio = 0;
        $n_nota = 0;
        foreach ($datos as $key => $value) {
            $tablaHTML[$pos]['medidas'] = array(48, 48, 20, 76);
            $tablaHTML[$pos]['alineado'] = array('L', 'L', 'L', 'L');
            $tablaHTML[$pos]['datos'] = array($this->ingles($value['area'], $ingles), $this->ingles($value['text'], $ingles), $value['nota'], $this->valoracion($value['nota'], $ingles));
            $tablaHTML[$pos]['borde'] = '1';
            $pos++;
            if (is_numeric($value['nota'])) {
                $promedio = $promedio + floatval($value['nota']);
                $n_nota += 1;
            }
        }

        $promedio = $promedio / $n_nota;
        $letra = $this->promedio_valoraciongpa($promedio);
        $tablaHTML[$pos]['medidas'] = array(96, 20, 76);
        $tablaHTML[$pos]['alineado'] = array('L', 'L', 'L');
        $tablaHTML[$pos]['datos'] = array($this->ingles("<b>Promedio General", $ingles), $promedio, $this->valoracion($promedio, $ingles));
        $tablaHTML[$pos]['borde'] = '1';
        $tablaHTML[$pos]['estilo'] = 'B';
        $pos++;
        $tablaHTML[$pos]['medidas'] = array(96, 20, 76);
        $tablaHTML[$pos]['alineado'] = array('L', 'L', 'L');
        $tablaHTML[$pos]['datos'] = array($this->ingles("<b>Evaluacion del comportamiento", $ingles), 'B', $this->valoracion('B', $ingles));
        $tablaHTML[$pos]['borde'] = '1';
        $tablaHTML[$pos]['estilo'] = 'B';

        $pos++;

        $text = 'Por lo tanto, es promovido/a al Segundo Curso de Bachillerato General unificado, para constancia suscriben en unidad de acta el/la RECTORA con el/la SECRETARIO GENERAL del plantel que certifica.
		Dado y firmado en Quito, ';
        if ($ingles) {
            $text = "Therefore, she is promoted to the Second Year of General unified Baccalaureate. In witness whereof, the Rector and the General Secretary of the School affix their signatures. Given in QUITO, PICHINCHA, on ";
        }

        $tablaHTML[$pos]['medidas'] = array(192);
        $tablaHTML[$pos]['alineado'] = array('L');
        $tablaHTML[$pos]['datos'] = array("");
        $pos++;
        $tablaHTML[$pos]['medidas'] = array(192);
        $tablaHTML[$pos]['alineado'] = array('L');
        $tablaHTML[$pos]['datos'] = array($text . date('r'));
        $pos++;

        $tablaHTML[$pos]['medidas'] = array(192);
        $tablaHTML[$pos]['alineado'] = array('L');
        $tablaHTML[$pos]['datos'] = array("");
        $pos++;

        $tablaHTML[$pos]['medidas'] = array(192);
        $tablaHTML[$pos]['alineado'] = array('L');
        $tablaHTML[$pos]['datos'] = array("");
        $pos++;

        $tablaHTML[$pos]['medidas'] = array(192);
        $tablaHTML[$pos]['alineado'] = array('L');
        $tablaHTML[$pos]['datos'] = array("");
        $pos++;



        $tablaHTML[$pos]['medidas'] = array(15, 65, 30, 65, 15);
        $tablaHTML[$pos]['alineado'] = array('L', 'C', 'L', 'C', 'L',);
        $tablaHTML[$pos]['datos'] = array("", "_________________________________", "", "_________________________________", "");
        // $tablaHTML[$pos]['borde'] = '1';
        $pos++;

        $tablaHTML[$pos]['medidas'] = array(15, 65, 30, 65, 15);
        $tablaHTML[$pos]['alineado'] = array('L', 'L', 'L', 'L', 'L',);
        $tablaHTML[$pos]['datos'] = array("", "SR./SRA..........................", "", "SR./SRA..........................", "");
        // $tablaHTML[$pos]['borde'] = '1';
        $pos++;

        $tablaHTML[$pos]['medidas'] = array(15, 65, 30, 65, 15);
        $tablaHTML[$pos]['alineado'] = array('L', 'C', 'L', 'C', 'L',);
        $tablaHTML[$pos]['datos'] = array("", "RECTOR", "", "GENERAL SECRETARY", "");
        // $tablaHTML[$pos]['borde'] = '1';
        $pos++;

        // return $this->pdf->cabecera_reporte_MC($titulo, $tablaHTML, $contenido = false, $image, 'fecha', 'fecha', $sizetable, true, $sal_hea_body = 20);
    }


    function promedio_valoraciongpa($nota)
    {
        $valor = '';
        if ($nota >= 9 && $nota <= 10) {
            $valor = 'A';
        }
        if ($nota >= 8 && $nota <= 8.9) {
            $valor = 'B';
        }
        if ($nota >= 7 && $nota <= 7.9) {
            $valor = 'C';
        }
        if ($nota >= 6 && $nota <= 6.9) {
            $valor = 'D';
        }
        if ($nota >= 0 && $nota <= 5.9) {
            $valor = 'F';
        }

        return $valor;
    }

    function valoracion($nota, $ingles = false)
    {
        $text = '';
        if ($nota >= 7 && $nota < 8.93) {
            if ($ingles) {
                $text = "Achieves the Required Learnings";
            } else {
                $text = "ALCANZA LOS APRENDIZAJES REQUERIDOS";
            }
        }
        if ($nota > 8.93 && $nota <= 10) {
            if ($ingles) {
                $text = "Dominates the Required Learnings";
            } else {
                $text = "DOMINA LOS APRENDIZAJES REQUERIDOS";
            }
        }
        if ($nota == 'EX') {
            if ($ingles) {
                $text = "Excellent";
            } else {
                $text = "Excelente";
            }
        }
        if ($nota == 'B') {
            if ($ingles) {
                $text = "Satisfactory";
            } else {
                $text = "Satisfactorio";
            }
        }
        return $text;
    }

    function ingles($dato, $ingles = false)
    {
        $buscar = array(
            "Matematica",
            "Fisica",
            "Quimica",
            "Ciencias Naturales",
            "Biologia",
            "Historia",
            "Educacion para la Ciudadania",
            "Ciencia Sociales",
            "Filosofia",
            "Lenguaje y Literatura",
            "Gramatica",
            "Escrita",
            "Lectora",
            "Lenguaje Extrangero",
            "Hablado y escuchado",
            "Educacion Cultural y artistica",
            "Educacion Fisica",
            "Modulo interdisiplinario",
            "Emprendimiento",
            "Educacion Religiosa",
            "Investigacion",
            "Estudios Multidiciplinarios",
            "Desarrollo integral y humano",
            "Promedio General",
            "Evaluacion del comportamiento",
            "Educacion Fisica (gym)",
            "Espanol A: Literatura Nivel Superior",
            "Language B: English B High Level ",
            "Gestion Empresarial Nivel Superior",
            "Analisis y Enfoques Nivel Medio",
            "Quimica Nivel Medio",
            "Fisica Nivel Medio",
            "Teoria del Conocimiento",
            "Monografia.",
            "Educacion Fisica.",
        );
        $remplazar = array(
            "Mathematics",
            "Physics",
            "Chemistry",
            "Natural Sciences",
            "Biology",
            "History",
            "Citizenship Education",
            "Social Science",
            "Philosophy",
            "Lenguage and Literature",
            "Grammar",
            "writing",
            "Reading",
            "Foreing Language",
            "Speaking and Listening",
            "Cultural and artistic education",
            "Physical education",
            "interdissiplinary module",
            "Entrepreneuraship",
            "Religious Education",
            "Research",
            "Multidiciplinary studies",
            "Integral human Development",
            "General Average",
            "Behavior Evaluation",
            "Physical education (gym)",
            //"Espanol A",
            //"Language B",
            "Gestion Empresarial",
            "Analisis y Enfoques",
            "Quimica",
            "Fisica",
            "Teoria",
            "Monog",
            "ed Fisica.",
        );

        if ($ingles) {
            return str_replace($buscar, $remplazar, $dato);
        } else {
            return 'No se encontró el Curso';
        }
    }

    function procesar_lineas($lineas)
    {
        $areas_asignaturas = $this->array_cursos();
        //$resultado = ['datos' => [], 'errores' => []];
        $resultado = [];
        $area_encontrada = [];
        $asignatura_encontrada = [];
        $nota_encontrada = [];

        foreach ($lineas as $linea) {
            // Limpieza inicial: quitar tabulaciones y espacios extra
            $linea = trim(preg_replace('/\s+/', ' ', $linea));
            $linea = $this->quitar_acentos($linea);

            // echo json_encode($linea);
            // echo '<br>';

            // Ordenar el array de áreas por longitud en orden descendente (primero las más largas)
            usort($areas_asignaturas, function ($a, $b) {
                return strlen($b) - strlen($a); // Compara la longitud de las cadenas
            });

            // Variable para manejar la línea modificada
            $linea_modificada = $linea;

            $area = null;
            //Buscar y eliminar la primera coincidencia de área
            foreach ($areas_asignaturas as $curso) {
                if (stripos($linea_modificada, $curso) === 0) { // Compara desde la posición 0
                    $area_encontrada[] = $curso; // Guardamos el área encontrada
                    $area = $curso;

                    $linea_modificada = preg_replace('/' . preg_quote($curso, '/') . '/', '', $linea_modificada, 1); // Elimina solo la primera aparición
                    $linea_modificada = trim(preg_replace('/\s+/', ' ', $linea_modificada));

                    // echo json_encode($linea_modificada);
                    // echo '<br>' . $curso . '<br>------------------<br>';
                    // echo '<br>';

                    break; // Rompe el ciclo para no seguir buscando otras áreas
                }
            }

            $asignatura = null;
            //Buscar la asignatura después de haber eliminado el área
            foreach ($areas_asignaturas as $curso) {
                if (stripos($linea_modificada, $curso) === 0) { // Compara desde la posición 0
                    $asignatura_encontrada[] = $curso; // Guardamos la asignatura encontrada
                    $asignatura = $curso ?? '.';

                    // echo json_encode($linea_modificada);
                    // echo '<br>' . $curso . '<br>------------------<br>';
                    // echo '<br>';

                    break; // Detener el loop después de la primera coincidencia
                }
            }

            $nota = null;
            if (preg_match('/([\d\,]+)\s*(?:[^\d]*)?$/', $linea, $matches)) {
                // Reemplazar la coma por un punto antes de convertir a float
                $nota = floatval(str_replace(',', '.', $matches[1]));
                $nota_encontrada[] = floatval($matches[1]);
                //Para mostrar las notas
                // echo 'Nota detectada: ' . $nota . '<br><br>';
                // echo 'Detalles del match: ' . json_encode($matches) . '<br>------------------------<br><br>';
            }


            // Validar que se hayan identificado área y asignatura
            if (!empty($area)) {
                $asignatura = $asignatura ?? null;

                $resultado[] = [
                    'area' => $area,
                    'asignatura' => $asignatura,
                    'nota' => $nota,
                ];

            } else {
                //$resultado['errores'][] = "Error en la línea: '$linea'. No se pudo identificar área o asignatura.";
            }
        }

        return $resultado;
    }

    function procesar_lineas_1($lineas)
    {
        $areas_asignaturas = $this->array_cursos();
        $resultado = [];
        $area_encontrada = [];
        $asignatura_encontrada = [];
        $nota_encontrada = [];

        foreach ($lineas as $linea) {
            // Limpieza inicial: quitar tabulaciones y espacios extra
            $linea = trim(preg_replace('/\s+/', ' ', $linea));
            $linea = $this->quitar_acentos($linea);

            // Ordenar el array de áreas por longitud en orden descendente (primero las más largas)
            usort($areas_asignaturas, function ($a, $b) {
                return strlen($b) - strlen($a);
            });

            // Variable para manejar la línea modificada
            $linea_modificada = $linea;

            // Buscar y eliminar la primera coincidencia de área
            $area = null;
            foreach ($areas_asignaturas as $curso) {
                if (stripos($linea_modificada, $curso) === 0) {
                    $area_encontrada[] = $curso;
                    $area = $curso;

                    $linea_modificada = preg_replace('/' . preg_quote($curso, '/') . '/', '', $linea_modificada, 1);
                    $linea_modificada = trim(preg_replace('/\s+/', ' ', $linea_modificada));
                    break;
                }
            }

            // Buscar la asignatura después de haber eliminado el área
            $asignatura = null;
            foreach ($areas_asignaturas as $curso) {
                if (stripos($linea_modificada, $curso) === 0) {
                    $asignatura_encontrada[] = $curso;
                    $asignatura = $curso;
                    break;
                }
            }

            // Buscar la nota en la línea
            $nota = null;
            if (preg_match('/([\d\,]+)\s*(?:[^\d]*)?$/', $linea, $matches)) {
                $nota = floatval(str_replace(',', '.', $matches[1]));
                $nota_encontrada[] = $nota;
            }

            // Validar que se haya identificado el área y exista una nota
            if (!empty($area)) {
                // Asignar null a la asignatura si no se encontró
                $asignatura = $asignatura ?? null;

                // Solo agregar el resultado si hay una nota
                if (isset($nota)) {
                    $resultado[] = [
                        'area' => $area,
                        'asignatura' => $asignatura,
                        'nota' => $nota,
                    ];
                } else {
                    // Manejar error opcional: línea sin nota válida
                    //$resultado['errores'][] = "Error en la línea: '$linea'. No se detectó una nota válida.";
                }
            } else {
                // Manejar error opcional: línea sin área válida
                //$resultado['errores'][] = "Error en la línea: '$linea'. No se detectó un área.";
            }
        }

        return $resultado;
    }


    function quitar_acentos($texto)
    {
        // Mapa de caracteres con acentos a sus equivalentes sin acento
        $acentos = array(
            'á' => 'a',
            'é' => 'e',
            'í' => 'i',
            'ó' => 'o',
            'ú' => 'u',
            'Á' => 'A',
            'É' => 'E',
            'Í' => 'I',
            'Ó' => 'O',
            'Ú' => 'U',
            'ñ' => 'n',
            'Ñ' => 'N'
        );

        // Sustituir los caracteres con acento por su equivalente sin acento
        return strtr($texto, $acentos);
    }

    function array_cursos()
    {
        return array(
            "Matematica",
            "Fisica",
            "Quimica",
            "Ciencias Naturales",
            "Biologia",
            "Historia",
            "Educacion para la Ciudadania",
            "Ciencia Sociales",
            "Filosofia",
            "Lenguaje y Literatura",
            "Gramatica",
            "Escrita",
            "Lectora",
            "Lenguaje Extrangero",
            "Hablado y escuchado",
            "Educacion Cultural y artistica",
            "Educacion Fisica",
            "Modulo interdisiplinario",
            "Emprendimiento",
            "Educacion Religiosa",
            "Investigacion",
            "Estudios Multidiciplinarios",
            "Desarrollo integral y humano",
            "Promedio General",
            "Evaluacion del comportamiento",
            "Educacion Fisica (gym)",
            "Espanol A: Literatura Nivel Superior",
            "Language B: English B High Level ",
            "Gestion Empresarial Nivel Superior",
            "Analisis y Enfoques Nivel Medio",
            "Quimica Nivel Medio",
            "Fisica Nivel Medio",
            "Teoria del Conocimiento",
            "Monografia.",
            "Educacion Fisica.",
        );
    }
}
