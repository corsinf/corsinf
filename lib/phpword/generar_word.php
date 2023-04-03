
<?php
 @session_start();
include(dirname(__DIR__,2).'/modelo/actasM.php');
date_default_timezone_set('America/Guayaquil'); 
setlocale(LC_ALL,"es-ES");
/**
 * Trabajar con documentos de Word y PHP usando PHPOffice
 *
 * Más tutoriales en: parzibyte.me/blog
 *
 * Ejemplo 1:
 * Crear documento de word, poner propiedades,
 * guardar para versiones actuales y
 * establecer idioma
 */
require_once "vendor/autoload.php";
use PhpOffice\PhpWord\Style\Language;
use PhpOffice\PhpWord\SimpleType\Jc;


$reporte = new generar_word();
if(isset($_GET['word_acta1']))
{
    $parametros = $_GET;
    $reporte->acta_donacion($parametros);
}
if(isset($_GET['word_acta2']))
{
    if(isset($_GET['tipo']) && $_GET['tipo']==3)
    {
        $parametros = $_GET;
        $reporte->acta_recepcion_definitivo($parametros);
    }else
    {
        $parametros = $_GET;
        $reporte->acta_custodio_temp($parametros);
    }    
}
if(isset($_GET['word_acta4']))
{
    $parametros = $_GET;
    $reporte->traspaso_saliente($parametros);
}
if(isset($_GET['word_acta5']))
{
    $parametros = $_GET;
    $reporte->acta_entrega_donacion($parametros);
}

/**
 * 
 */
class generar_word
{
    private $actas;
    function __construct()
    {
        $this->actas = new actasM();
    }

    function acta_donacion($parametros)
    {

        // print_r($parametros);die();
    $documento = new \PhpOffice\PhpWord\PhpWord();
    $propiedades = $documento->getDocInfo();
    $propiedades->setCreator("Luis Cabrera Benito");
    $propiedades->setTitle("Texto");

    # Agregar texto...
    /*
    Todos los textos deben estar dentro de una sección
     */

    $seccion = $documento->addSection();

    //imagen cabecera
    $header = $seccion->addHeader();
    $header->addImage(
        '../../img/cabecera_puce_acta.png',
        array(
        'width' => 595,
        'height' => 100,
        'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
        'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_HORIZONTAL_RIGHT,
        'posHorizontalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_PAGE,
        'posVerticalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_PAGE,
        'marginLeft' => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(15.5),
        'marginTop' => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(1.55),
        )
    );

    $seccion->addTextBreak(4);


    # Títulos.
    $fuenteTitulo = [
        "name" => "Montserrat ExtraBold",
        "size" => 11,
        "color" => "000000",
        "bold" => true,
    ];

    $alineacion = [
        "alignment" => Jc::CENTER,
        // "lineHeight" => 0.7,
    ];
    $alineacionEND = [
        "alignment" => Jc::END,
        // "lineHeight" => 0.7,
    ];

    // $documento->addParagraphStyle('myStyle', array('align'=>'center', 'spaceAfter'=>100));
    $documento->addTitleStyle(1, $fuenteTitulo,$alineacion);
    $seccion->addTitle("ACTA DE BIENES DE DONACION", 1);

    $query = 'ACTA_DONACIONES';
    $datos = $this->actas->secuencial_acta($query);
    $documento->addTitleStyle(2, $fuenteTitulo,$alineacionEND);
    $seccion->addTitle($parametros['sub'].'-'.($datos[0]['NUMERO']+1).'-'.date('Y'), 2);
    $this->actas->secuencial_acta_update($query);


    //cuerpo del documento
    $fuente = [
        "name" => "Montserrat",
        "size" => 10,
        "color" => "000000",
        "italic" => false,
        "bold" => false,
    ];
    $alineacionC = [
        "alignment" => Jc::BOTH,
        "lineHeight" => 1.5,
    ];

    $alineacionCenter = [
        "alignment" => Jc::CENTER,
        // "lineHeight" => 1.5,
    ];
    $alineacionRIGHT = [
        "alignment" => Jc::RIGHT,
        // "lineHeight" => 1.5,
    ];


    $fecha = strftime("%A %d de %B del %Y");


    $seccion->addText("En la ciudad de Quito, ".$fecha.", comparece por una parte la Dirección de Control de Activos Fijos y seguros de la Pontificia Universidad Católica del Ecuador, y por otra parte ".strtoupper($parametros['to'])."; como benefactor, celebran y suscriben la presente acta de ENTREGA – DONACION del bien o los bienes detallado en el listado adjunto.", $fuente,$alineacionC);

    $seccion->addTextBreak(1);

    $seccion->addText("La Dirección de Control de Activos Fijos y seguros de la Pontificia Universidad Católica del Ecuador en calidad de DONANTE, declara entregar en su totalidad del bien o los bienes antes descritos, la misma que cuenta con la autorización correspondiente.",$fuente,$alineacionC);

    $seccion->addTextBreak(1);

    $seccion->addText("Para constancia de la Entrega/donación firman las partes en Quito, ".$fecha,$fuente,$alineacionC);

    $seccion->addTextBreak(3);


    $fancyTableStyle = ['borderSize' => 2, 'borderColor' => 'FFFFFF', 'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER, 'cellSpacing' => 50];
    $fancyTableFirstRowStyle = ['borderBottomSize' => 2, 'borderBottomColor' => '0000FF', 'bgColor' => 'FFFFFF'];


    $documento->addTableStyle('ESTILO1', $fancyTableStyle, $fancyTableFirstRowStyle);
    $table = $seccion->addTable();
    $table->addRow();
    $table->addCell(3000, $alineacionCenter)->addText('------------------------------------',$fuente,$alineacionCenter);
    $table->addCell(3000, $alineacionCenter)->addText('', $fuente);
    $table->addCell(3000, $alineacionCenter)->addText('------------------------------------', $fuente,$alineacionCenter);
    $table->addRow();
    $table->addCell(3000, $alineacionCenter)->addText('Dir. De Control de Activo', $fuente,$alineacionCenter);
    $table->addCell(3000, $alineacionCenter)->addText('', $fuente);
    $table->addCell(3000, $alineacionCenter)->addText(strtoupper($parametros['to']), $fuente,$alineacionCenter);
    $table->addRow();
    $table->addCell(3000, $alineacionCenter)->addText('Diana Espín Aguirre', $fuente,$alineacionCenter);




         $fuenteFooter = [
            "name" => "Calibri",
            "size" => 10,
            "color" => "000000",
            "italic" => false,
            "bold" => false,
        ];
        $alineacionM = [
            // "alignment" => Jc::CENTER,
            "lineHeight" =>0.75,
            'marginLeft'=>-50
        ];


     $footer = $seccion->addFooter();       
     $footer->addImage(
            '../../img/forma1.png',
             array(
                'width'=>595,
                'height'=>5,
                'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'marginLeft' => -50,
                'marginTop'=>2,
                'wrappingStyle'=> 'inline'
            )        
        );
     
          $footer->addImage(
            '../../img/footer_puce_acta.png',
            array(
                'width'=>550,
                'height'=>50,
                'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'marginLeft' => -50,
                'marginTop'=>-10,
                'wrappingStyle'=> 'behind'
            )        
        );


    $seccion = $documento->addSection(
        array(
        'orientation'=>'landscape')
    );
    $seccion->addTextBreak(2);
    $usuario = $_SESSION['INICIO']['ID_USUARIO'];
    $lista = $this->actas->lista_actas($usuario);
        # Otra tabla
        $estiloTabla2 = [
            'border'=>1,
            "borderColor" => "080808",
            "alignment" => Jc::LEFT,
            "borderSize" => 2,
            "cellMargin" => 10,
        ];        
       # Encabezados
        $fuente = [
            "name" => "Arial",
            "size" => 8,
            "color" => "000000",
            'Bold'=>true,
        ];

        $fuente2 = [
            "name" => "Arial",
            "size" => 8,
            "color" => "000000",
            'Bold'=>false,
        ];


       
        $documento->addTableStyle('estilo2',$estiloTabla2);
        $table = $seccion->addTable('estilo2');
        $table->addRow();
        $table->addCell(3000, $alineacionCenter)->addText('Asset',$fuente,$alineacionCenter);
        $table->addCell(7000, $alineacionCenter)->addText('Activo',$fuente,$alineacionCenter);
        $table->addCell(2000, $alineacionCenter)->addText('RFID', $fuente,$alineacionCenter);
        $table->addCell(2000, $alineacionCenter)->addText('Serie', $fuente,$alineacionCenter);
        $table->addCell(2000, $alineacionCenter)->addText('Modelo', $fuente,$alineacionCenter);
        $table->addCell(7000, $alineacionCenter)->addText('Custodio', $fuente,$alineacionCenter);
        $table->addCell(7000, $alineacionCenter)->addText('Emplazamiento', $fuente,$alineacionCenter);
        $table->addCell(2000, $alineacionCenter)->addText('Valor', $fuente,$alineacionCenter);

        $total = 0;
        foreach ($lista as $key => $value) {
            // print_r($value);die();
            $table->addRow();
            $table->addCell(3000)->addText($value["asset"],$fuente2);
            $table->addCell(7000)->addText($value["articulo"],$fuente2);
            $table->addCell(7000)->addText($value["TAG_UNIQUE"],$fuente2);
            $table->addCell(7000)->addText($value["SERIE"],$fuente2);
            $table->addCell(7000)->addText($value["MODELO"],$fuente2);
            $table->addCell(7000)->addText($value["PERSON_NOM"],$fuente2);
            $table->addCell(7000)->addText($value["DENOMINACION"],$fuente2);
            $table->addCell(2000)->addText(floatval(str_replace(',','', $value["valor"])),$fuente2,$alineacionRIGHT);
            $total = $total+number_format(floatval(str_replace(',','', $value["valor"])),2,'.','');
        }

        $cellColSpan = ['gridSpan' => 6, 'valign' => 'center'];

        $table->addRow();
        $table->addCell(null, $cellColSpan);
        $table->addCell(7000, $alineacionCenter)->addText('Total', $fuente,$alineacionRIGHT);
        $table->addCell(2000, $alineacionCenter)->addText($total, $fuente,$alineacionRIGHT);



        $header = $seccion->addHeader();
        $header->addImage(
            '../../img/cabecera_puce_actaH.png',
            array(
            'width' => 825,
            'height' => 70,
            'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
            'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_HORIZONTAL_RIGHT,
            'posHorizontalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_PAGE,
            'posVerticalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_PAGE,
            'marginLeft' => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(15.5),
            'marginTop' => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(1.55),
            )
        );



         $footer = $seccion->addFooter();       
         $footer->addImage(
                '../../img/footer_puce_actaH.png',
                 array(
                    'width'=>825,
                    'height'=>70,
                    'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                    'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                    'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                    'marginLeft' => -50,
                    'marginTop'=>-25,
                    'wrappingStyle'=> 'inline',
                )        
            );



        

        # Para que no diga que se abre en modo de compatibilidad
        $documento->getCompatibility()->setOoxmlVersion(15);
        # Idioma español de México
        $documento->getSettings()->setThemeFontLang(new Language("ES-MX"));

        # Guardarlo
        // $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($documento, "Word2007");

        // $objWriter->save("ACTA DONACION.docx");

        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="ACTA DONACION.docx"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($documento, 'Word2007');
        $xmlWriter->save("php://output");



    }

    function acta_custodio_temp($parametros)
    {
        $documento = new \PhpOffice\PhpWord\PhpWord();
         $seccion = $documento->addSection();

        //imagen cabecera
        $header = $seccion->addHeader();
        $header->addImage(
            '../../img/cabecera_puce_acta.png',
            array(
                'width' => 595,
                'height' => 100,
                'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_HORIZONTAL_RIGHT,
                'posHorizontalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_PAGE,
                'posVerticalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_PAGE,
                'marginLeft' => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(15.5),
                'marginTop' => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(1.55),
                )
        );
        $seccion->addTextBreak(4);
         # Títulos.
        $fuenteTitulo = [
            "name" => "Montserrat ExtraBold",
            "size" => 11,
            "color" => "000000",
            "bold" => true,
        ];

        $alineacion = [
            "alignment" => Jc::CENTER,
            // "lineHeight" => 0.7,
        ];
        $alineacionEND = [
            "alignment" => Jc::END,
            // "lineHeight" => 0.7,
        ];

        $fuente = [
            "name" => "Montserrat",
            "size" => 10,
            "color" => "000000",
            "italic" => false,
            "bold" => false,
        ];
        $alineacionC = [
            "alignment" => Jc::BOTH,
            "lineHeight" => 1.5,
        ];

        $alineacionCenter = [
            "alignment" => Jc::CENTER,
            // "lineHeight" => 1.5,
        ];

         $lista = $this->actas->lista_actas();
         $total = 0;
         foreach ($lista as $key => $value) {
            $total = $total+number_format(floatval(str_replace(',','', $value["valor"])),2,'.','');
      
          }


         $fecha = strftime("%A %d de %B del %Y");

        // $documento->addParagraphStyle('myStyle', array('align'=>'center', 'spaceAfter'=>100));
        $documento->addTitleStyle(1, $fuenteTitulo,$alineacion);
        $seccion->addTitle("ACTA ENTREGA RECEPCION", 1);
        $seccion->addTitle("CUSTODIO TEMPORAL", 1);

        $documento->addTitleStyle(2, $fuenteTitulo,$alineacionEND);
        $seccion->addTitle($parametros['sub'].'-'.date('Y'), 2);
        
        $subrayado =  array('align' => 'both', 'spaceAfter' => 100,'underline' => 'single', "name" => "Times New Roman",
            "size" => 11);
        $negrita =  array('align' => 'both', 'spaceAfter' => 100,'bold' =>true, "name" => "Times New Roman",
            "size" => 11);

        $textrun = $seccion->addTextRun($alineacionC);
        $textrun->addText(htmlspecialchars('En la ciudad de Quito, '.$fecha.', comparece por una parte la Dirección de Control de Activos Fijos y seguros de la Pontificia Universidad Católica del Ecuador, y por otra parte '),$fuente);
        $textrun->addText(htmlspecialchars(strtoupper($parametros['cus']).', '),$subrayado);
        $textrun->addText(htmlspecialchars('como custodio temporal, celebran y suscriben la presente acta de ENTREGA - RECEPCIÓN del bien o bienes  '),$fuente);
         $textrun->addText(htmlspecialchars('detallado en el listado adjunto. '),$subrayado);
         $textrun->addText(htmlspecialchars('Valorados en $'.$total.' de conformidad a lo establecido en los lineamientos internos de con-trol de Activos de la Universidad Católica del Ecuador debidamente etiquetados.'),$fuente);
         $textrun->addTextBreak(2); 
         $textrun->addText(htmlspecialchars(strtoupper($parametros['cus']).', '),$subrayado); 
         $textrun->addText(htmlspecialchars(', en calidad de custodio temporal, declara recibir en su totalidad del bien o los bienes antes descritos en perfectas condiciones los mismos que están ubicados en la '),$fuente);
         $textrun->addText(htmlspecialchars(strtoupper($parametros['empla']).', '),$subrayado); 
         $textrun->addText(htmlspecialchars('y acepta por medio de este instrumento, someterse a todas y cada una de las disposiciones.'),$fuente);
         
        $seccion->addListItem(htmlspecialchars('Responsable de dar buen uso de los bienes asignados'));
        $seccion->addListItem(htmlspecialchars('Comunicar y solicitar autorización para el movimiento dentro y fuera de la PUCE de los bienes.'));
        $seccion->addListItem(htmlspecialchars('Verificar el estado y ubicación de los bienes por constataciones aleatorias que se realizarán.'));
        $seccion->addListItem(htmlspecialchars('Notificar en caso de tener algún siniestro con los bienes.'));
        $seccion->addListItem(htmlspecialchars('Informar si la etiqueta de control de bienes PUCE se ha deteriorado o perdido.'));
        $seccion->addListItem(htmlspecialchars('Informar donaciones que reciba la PUCE.'));


        $textrun = $seccion->addTextRun($alineacionC);
        $textrun->addText(htmlspecialchars('Es importante la notificación oportuna cuando se realice la entrega del bien o bienes al custodio definitivo responsable.'),$negrita);

        


        $seccion->addTextBreak(3);

        ///secion firmas 

        $estiloTabla = [
            "borderColor" => "ffffff",
            "alignment" => Jc::CENTER,
            "borderSize" => 0,
            // "cellMargin" => 500,
        ];

        // Guardarlo para usarlo más tarde
        $fancyTableStyle = ['borderSize' => 2, 'borderColor' => 'FFFFFF', 'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER, 'cellSpacing' => 50];
        $fancyTableFirstRowStyle = ['borderBottomSize' => 2, 'borderBottomColor' => '0000FF', 'bgColor' => 'FFFFFF'];

        $documento->addTableStyle('ESTILO1', $fancyTableStyle, $fancyTableFirstRowStyle);
        $table = $seccion->addTable();
        $table->addRow();
        $table->addCell(3000, $alineacionCenter)->addText('------------------------------------',$fuente,$alineacionCenter);
        $table->addCell(3000, $alineacionCenter)->addText('', $fuente);
        $table->addCell(3000, $alineacionCenter)->addText('------------------------------------', $fuente,$alineacionCenter);
        $table->addRow();
        $table->addCell(3000, $alineacionCenter)->addText('Dir. De Control de Activo', $fuente,$alineacionCenter);
        $table->addCell(3000, $alineacionCenter)->addText('', $fuente);
        $table->addCell(3000, $alineacionCenter)->addText(strtoupper($parametros['cus']), $fuente,$alineacionCenter);
        $table->addRow();
        $table->addCell(3000, $alineacionCenter)->addText('Diana Espín Aguirre', $fuente,$alineacionCenter);


         $fuenteFooter = [
            "name" => "Calibri",
            "size" => 10,
            "color" => "000000",
            "italic" => false,
            "bold" => false,
        ];
        $alineacionM = [
            // "alignment" => Jc::CENTER,
            "lineHeight" =>0.75,
            'marginLeft'=>-50
        ];


     $footer = $seccion->addFooter();       
     $footer->addImage(
            '../../img/forma1.png',
             array(
                'width'=>595,
                'height'=>5,
                'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'marginLeft' => -50,
                'marginTop'=>2,
                'wrappingStyle'=> 'inline'
            )        
        );
     
          $footer->addImage(
            '../../img/footer_puce_acta.png',
            array(
                'width'=>550,
                'height'=>50,
                'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'marginLeft' => -50,
                'marginTop'=>-10,
                'wrappingStyle'=> 'behind'
            )        
        );





    $seccion = $documento->addSection(
        array(
        'orientation'=>'landscape')
    );
    $seccion->addTextBreak(2);
    $usuario = $_SESSION['INICIO']['ID_USUARIO'];
    $lista = $this->actas->lista_actas($usuario);
        # Otra tabla
        $estiloTabla2 = [
            'border'=>1,
            "borderColor" => "080808",
            "alignment" => Jc::LEFT,
            "borderSize" => 2,
            "cellMargin" => 10,
        ];        
       # Encabezados
        $fuente = [
            "name" => "Arial",
            "size" => 8,
            "color" => "000000",
            'Bold'=>true,
        ];

        $fuente2 = [
            "name" => "Arial",
            "size" => 8,
            "color" => "000000",
            'Bold'=>false,
        ];


       
        $documento->addTableStyle('estilo2',$estiloTabla2);
        $table = $seccion->addTable('estilo2');
        $table->addRow();
        $table->addCell(3000, $alineacionCenter)->addText('Asset',$fuente,$alineacionCenter);
        $table->addCell(7000, $alineacionCenter)->addText('Activo',$fuente,$alineacionCenter);
        $table->addCell(2000, $alineacionCenter)->addText('RFID', $fuente,$alineacionCenter);
        $table->addCell(2000, $alineacionCenter)->addText('Serie', $fuente,$alineacionCenter);
        $table->addCell(2000, $alineacionCenter)->addText('Modelo', $fuente,$alineacionCenter);
        $table->addCell(7000, $alineacionCenter)->addText('Custodio', $fuente,$alineacionCenter);
        $table->addCell(7000, $alineacionCenter)->addText('Emplazamiento', $fuente,$alineacionCenter);
        $table->addCell(2000, $alineacionCenter)->addText('Valor', $fuente,$alineacionCenter);

        $total = 0;
        foreach ($lista as $key => $value) {
            // print_r($value);die();
            $table->addRow();
            $table->addCell(3000)->addText($value["asset"],$fuente2);
            $table->addCell(7000)->addText($value["articulo"],$fuente2);
            $table->addCell(7000)->addText($value["TAG_UNIQUE"],$fuente2);
            $table->addCell(7000)->addText($value["SERIE"],$fuente2);
            $table->addCell(7000)->addText($value["MODELO"],$fuente2);
            $table->addCell(7000)->addText($value["PERSON_NOM"],$fuente2);
            $table->addCell(7000)->addText($value["DENOMINACION"],$fuente2);
            $table->addCell(2000)->addText(floatval(str_replace(',','', $value["valor"])),$fuente2,$alineacionRIGHT);
            $total = $total+number_format(floatval(str_replace(',','', $value["valor"])),2,'.','');
       }

        $cellColSpan = ['gridSpan' => 6, 'valign' => 'center'];

        $table->addRow();
        $table->addCell(null, $cellColSpan);
        $table->addCell(7000, $alineacionCenter)->addText('Total', $fuente,$alineacionRIGHT);
        $table->addCell(2000, $alineacionCenter)->addText($total, $fuente,$alineacionRIGHT);

        $header = $seccion->addHeader();
        $header->addImage(
            '../../img/cabecera_puce_actaH.png',
            array(
            'width' => 825,
            'height' => 70,
            'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
            'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_HORIZONTAL_RIGHT,
            'posHorizontalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_PAGE,
            'posVerticalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_PAGE,
            'marginLeft' => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(15.5),
            'marginTop' => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(1.55),
            )
        );



         $footer = $seccion->addFooter();       
         $footer->addImage(
                '../../img/footer_puce_actaH.png',
                 array(
                    'width'=>825,
                    'height'=>70,
                    'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                    'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                    'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                    'marginLeft' => -50,
                    'marginTop'=>-25,
                    'wrappingStyle'=> 'inline',
                )        
            );



        


     
     
      
        $documento->getCompatibility()->setOoxmlVersion(15);
        # Idioma español de México
        $documento->getSettings()->setThemeFontLang(new Language("ES-MX"));

        # Guardarlo
        // $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($documento, "Word2007");
        // $objWriter->save("ACTA CUSTODIO TEMPORAL.docx");

        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="ACTA CUSTODIO TEMPORAL.docx"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($documento, 'Word2007');
        $xmlWriter->save("php://output");

        

        


    }


    function acta_recepcion_definitivo($parametros)
    {
        $documento = new \PhpOffice\PhpWord\PhpWord();
         $seccion = $documento->addSection();

         $header = $seccion->addHeader();
        $header->addImage(
            '../../img/cabecera_puce_acta.png',
            array(
                'width' => 595,
                'height' => 100,
                'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_HORIZONTAL_RIGHT,
                'posHorizontalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_PAGE,
                'posVerticalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_PAGE,
                'marginLeft' => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(15.5),
                'marginTop' => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(1.55),
                )
        );
        $seccion->addTextBreak(4);
         # Títulos.
        $fuenteTitulo = [
            "name" => "Montserrat ExtraBold",
            "size" => 11,
            "color" => "000000",
            "bold" => true,
        ];

        $alineacion = [
            "alignment" => Jc::CENTER,
            // "lineHeight" => 0.7,
        ];
        $alineacionEND = [
            "alignment" => Jc::END,
            // "lineHeight" => 0.7,
        ];

        $fuente = [
            "name" => "Montserrat",
            "size" => 10,
            "color" => "000000",
            "italic" => false,
            "bold" => false,
        ];
        $alineacionC = [
            "alignment" => Jc::BOTH,
            "lineHeight" => 1.5,
        ];

        $alineacionCenter = [
            "alignment" => Jc::CENTER,
            // "lineHeight" => 1.5,
        ];




         $lista = $this->actas->lista_actas($usuario);
         $total = 0;
         foreach ($lista as $key => $value) {
            $total = $total+number_format(floatval(str_replace(',','', $value["valor"])),2,'.','');
      
          }

         $fecha = strftime("%A %d de %B del %Y");


        // $documento->addParagraphStyle('myStyle', array('align'=>'center', 'spaceAfter'=>100));
        $documento->addTitleStyle(1, $fuenteTitulo,$alineacion);
        $seccion->addTitle("ACTA DE ENTREGA RECEPCION", 1);

        $documento->addTitleStyle(1, $fuenteTitulo,$alineacion);
        $seccion->addTitle("CUSTODIO DEFINITIVO", 1);

        $documento->addTitleStyle(2, $fuenteTitulo,$alineacionEND);
        $seccion->addTitle($parametros['sub'].'-'.date('Y'), 2);
        
        $subrayado =  array('align' => 'both', 'spaceAfter' => 100,'underline' => 'single', "name" => "Times New Roman",
            "size" => 11);
        $negrita =  array('align' => 'both', 'spaceAfter' => 100,'bold' =>true, "name" => "Times New Roman",
            "size" => 11);

        $textrun = $seccion->addTextRun($alineacionC);
        $textrun->addText(htmlspecialchars('En la ciudad de Quito, '.$fecha.', comparece por una parte la Dirección de Control de Activos Fijos y seguros de la Pontificia Universidad Católica del Ecuador, y por otra parte '),$fuente);
        $textrun->addText(htmlspecialchars(strtoupper($parametros['cus']).' ,'),$subrayado);

        $textrun->addText(htmlspecialchars('como custodio responsable del buen uso, cuidado y notificación, del bien  o bienes detallados en el listado adjunto valorados en $'.$total.' celebran y suscriben la presente acta de ENTREGA - RECEPCIÓN de la de conformidad a lo establecido en los lineamientos de Activos de la Universidad Católica del Ecuador, debidamente etiquetados.'),$fuente);

        $textrun = $seccion->addTextRun($alineacionC);
        $textrun->addText(htmlspecialchars(strtoupper($parametros['cus']).' ,'),$subrayado);
        $textrun->addText(htmlspecialchars('en calidad de custodio responsable, declara recibir en su totalidad del bien o los bienes antes descritos en condiciones de uso que están ubicados en '),$fuente);
        $textrun->addText(htmlspecialchars(strtoupper($parametros['empla']).' ,'),$subrayado); 
        $textrun->addText(htmlspecialchars('y acepta por medio de este instrumento, someterse a todas y cada una de las disposiciones.'),$fuente);

        $seccion->addListItem(htmlspecialchars('Responsable de dar buen uso de los bienes asignados'));
        $seccion->addListItem(htmlspecialchars('Comunicar y solicitar autorización para el movimiento dentro y fuera de la PUCE de los bienes.'));
        $seccion->addListItem(htmlspecialchars('Verificar el estado y ubicación de los bienes por constataciones aleatorias que se realizarán.'));
        $seccion->addListItem(htmlspecialchars('Notificar en caso de tener algún siniestro con los bienes.'));
        $seccion->addListItem(htmlspecialchars('Informar si la etiqueta de control de bienes PUCE se ha deteriorado o perdido.'));
        $seccion->addListItem(htmlspecialchars('Informar donaciones que reciba la PUCE.'));

        $seccion->addTextBreak(3);

        ///secion firmas 

        $estiloTabla = [
            "borderColor" => "ffffff",
            "alignment" => Jc::CENTER,
            "borderSize" => 0,
            // "cellMargin" => 500,
        ];

         // Guardarlo para usarlo más tarde
        $fancyTableStyle = ['borderSize' => 2, 'borderColor' => 'FFFFFF', 'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER, 'cellSpacing' => 50];
        $fancyTableFirstRowStyle = ['borderBottomSize' => 2, 'borderBottomColor' => '0000FF', 'bgColor' => 'FFFFFF'];

        $documento->addTableStyle('ESTILO1', $fancyTableStyle, $fancyTableFirstRowStyle);
        $table = $seccion->addTable();
        $table->addRow();
        $table->addCell(3000, $alineacionCenter)->addText('------------------------------------',$fuente,$alineacionCenter);
        $table->addCell(3000, $alineacionCenter)->addText('', $fuente);
        $table->addCell(3000, $alineacionCenter)->addText('------------------------------------', $fuente,$alineacionCenter);
        $table->addRow();
        $table->addCell(3000, $alineacionCenter)->addText('Dir. De Control de Activo', $fuente,$alineacionCenter);
        $table->addCell(3000, $alineacionCenter)->addText('', $fuente);
        $table->addCell(3000, $alineacionCenter)->addText(strtoupper($parametros['cus']), $fuente,$alineacionCenter);
        $table->addRow();
        $table->addCell(3000, $alineacionCenter)->addText('Diana Espín Aguirre', $fuente,$alineacionCenter);


         $fuenteFooter = [
            "name" => "Calibri",
            "size" => 10,
            "color" => "000000",
            "italic" => false,
            "bold" => false,
        ];
        $alineacionM = [
            // "alignment" => Jc::CENTER,
            "lineHeight" =>0.75,
            'marginLeft'=>-50
        ];


     $footer = $seccion->addFooter();       
     $footer->addImage(
            '../../img/forma1.png',
             array(
                'width'=>595,
                'height'=>5,
                'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'marginLeft' => -50,
                'marginTop'=>2,
                'wrappingStyle'=> 'inline'
            )        
        );
     
          $footer->addImage(
            '../../img/footer_puce_acta.png',
            array(
                'width'=>550,
                'height'=>50,
                'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'marginLeft' => -50,
                'marginTop'=>-10,
                'wrappingStyle'=> 'behind'
            )        
        );





    $seccion = $documento->addSection(
        array(
        'orientation'=>'landscape')
    );
    $seccion->addTextBreak(2);
    $usuario = $_SESSION['INICIO']['ID_USUARIO'];
    $lista = $this->actas->lista_actas($usuario);
        # Otra tabla
        $estiloTabla2 = [
            'border'=>1,
            "borderColor" => "080808",
            "alignment" => Jc::LEFT,
            "borderSize" => 2,
            "cellMargin" => 10,
        ];        
       # Encabezados
        $fuente = [
            "name" => "Arial",
            "size" => 8,
            "color" => "000000",
            'Bold'=>true,
        ];

        $fuente2 = [
            "name" => "Arial",
            "size" => 8,
            "color" => "000000",
            'Bold'=>false,
        ];


       
        $documento->addTableStyle('estilo2',$estiloTabla2);
        $table = $seccion->addTable('estilo2');
        $table->addRow();
        $table->addCell(3000, $alineacionCenter)->addText('Asset',$fuente,$alineacionCenter);
        $table->addCell(7000, $alineacionCenter)->addText('Activo',$fuente,$alineacionCenter);
        $table->addCell(2000, $alineacionCenter)->addText('RFID', $fuente,$alineacionCenter);
        $table->addCell(2000, $alineacionCenter)->addText('Serie', $fuente,$alineacionCenter);
        $table->addCell(2000, $alineacionCenter)->addText('Modelo', $fuente,$alineacionCenter);
        $table->addCell(7000, $alineacionCenter)->addText('Custodio', $fuente,$alineacionCenter);
        $table->addCell(7000, $alineacionCenter)->addText('Emplazamiento', $fuente,$alineacionCenter);
        $table->addCell(2000, $alineacionCenter)->addText('Valor', $fuente,$alineacionCenter);

        $total = 0;
        foreach ($lista as $key => $value) {
            // print_r($value);die();
            $table->addRow();
            $table->addCell(3000)->addText($value["asset"],$fuente2);
            $table->addCell(7000)->addText($value["articulo"],$fuente2);
            $table->addCell(7000)->addText($value["TAG_UNIQUE"],$fuente2);
            $table->addCell(7000)->addText($value["SERIE"],$fuente2);
            $table->addCell(7000)->addText($value["MODELO"],$fuente2);
            $table->addCell(7000)->addText($value["PERSON_NOM"],$fuente2);
            $table->addCell(7000)->addText($value["DENOMINACION"],$fuente2);
            $table->addCell(2000)->addText(floatval(str_replace(',','', $value["valor"])),$fuente2,$alineacionRIGHT);
            $total = $total+number_format(floatval(str_replace(',','', $value["valor"])),2,'.','');
       }

        $cellColSpan = ['gridSpan' => 6, 'valign' => 'center'];

        $table->addRow();
        $table->addCell(null, $cellColSpan);
        $table->addCell(7000, $alineacionCenter)->addText('Total', $fuente,$alineacionRIGHT);
        $table->addCell(2000, $alineacionCenter)->addText($total, $fuente,$alineacionRIGHT);



        $header = $seccion->addHeader();
        $header->addImage(
            '../../img/cabecera_puce_actaH.png',
            array(
            'width' => 825,
            'height' => 70,
            'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
            'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_HORIZONTAL_RIGHT,
            'posHorizontalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_PAGE,
            'posVerticalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_PAGE,
            'marginLeft' => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(15.5),
            'marginTop' => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(1.55),
            )
        );



         $footer = $seccion->addFooter();       
         $footer->addImage(
                '../../img/footer_puce_actaH.png',
                 array(
                    'width'=>825,
                    'height'=>70,
                    'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                    'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                    'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                    'marginLeft' => -50,
                    'marginTop'=>-25,
                    'wrappingStyle'=> 'inline',
                )        
            );



        

     
      
        $documento->getCompatibility()->setOoxmlVersion(15);
        # Idioma español de México
        $documento->getSettings()->setThemeFontLang(new Language("ES-MX"));

        # Guardarlo
        // $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($documento, "Word2007");
        // $objWriter->save("ACTA RECEPCION DEFINITIVO.docx");


        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="ACTA CUSTODIO DEFINITIVO.docx"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($documento, 'Word2007');
        $xmlWriter->save("php://output");

    }

    function traspaso_saliente($parametros)
    {
        $documento = new \PhpOffice\PhpWord\PhpWord();
         $seccion = $documento->addSection();

       
         $header = $seccion->addHeader();
        $header->addImage(
            '../../img/cabecera_puce_acta.png',
            array(
                'width' => 595,
                'height' => 100,
                'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_HORIZONTAL_RIGHT,
                'posHorizontalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_PAGE,
                'posVerticalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_PAGE,
                'marginLeft' => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(15.5),
                'marginTop' => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(1.55),
                )
        );
        $seccion->addTextBreak(4);
         # Títulos.
        $fuenteTitulo = [
            "name" => "Montserrat ExtraBold",
            "size" => 11,
            "color" => "000000",
            "bold" => true,
        ];

        $alineacion = [
            "alignment" => Jc::CENTER,
            // "lineHeight" => 0.7,
        ];
        $alineacionEND = [
            "alignment" => Jc::END,
            // "lineHeight" => 0.7,
        ];

        $fuente = [
            "name" => "Montserrat",
            "size" => 10,
            "color" => "000000",
            "italic" => false,
            "bold" => false,
        ];
        $alineacionC = [
            "alignment" => Jc::BOTH,
            "lineHeight" => 1.5,
        ];

        $alineacionCenter = [
            "alignment" => Jc::CENTER,
            // "lineHeight" => 1.5,
        ];




         $total = 0;
         $usuario = $_SESSION['INICIO']['ID_USUARIO'];
         $lista = $this->actas->lista_actas($usuario);
         foreach ($lista as $key => $value) {
            $total = $total+number_format(floatval(str_replace(',','', $value["valor"])),2,'.','');
      
          }

         $fecha = strftime("%A %d de %B del %Y");

        // $documento->addParagraphStyle('myStyle', array('align'=>'center', 'spaceAfter'=>100));
        $documento->addTitleStyle(1, $fuenteTitulo,$alineacion);
        $seccion->addTitle("ACTA DE ENTREGA RECEPCION", 1);
        $seccion->addTitle("TRASPASO DE CUSTODIO SALIENTE A CUSTODIO ENTRANTE ", 1);

        $documento->addTitleStyle(2, $fuenteTitulo,$alineacionEND);
        $seccion->addTitle($parametros['sub'].'-'.date('Y'), 2);

        $subrayado =  array('align' => 'both', 'spaceAfter' => 100,'underline' => 'single', "name" => "Times New Roman",
            "size" => 11);
        $negrita =  array('align' => 'both', 'spaceAfter' => 100,'bold' =>true, "name" => "Times New Roman",
            "size" => 11);

        $textrun = $seccion->addTextRun($alineacionC);
        $textrun->addText(htmlspecialchars('En la ciudad de Quito, '.$fecha.' comparece por una parte la Dirección de Control de Activos Fijos y seguros de la Pontificia Universidad Católica del Ecuador, y por otra parte '),$fuente);
        $textrun->addText(htmlspecialchars( strtoupper($parametros['cusS']).' , '),$subrayado);
        $textrun->addText(htmlspecialchars(' Como Custodio Saliente del buen uso, cuidado y notificación, del bien o bienes detallados en el listado adjunto valorados en $ '.$total.'celebran y suscriben la presente acta de ENTREGA - RECEPCIÓN de la de conformidad a lo establecido en los lineamientos de Activos de la Universidad Católica del Ecuador, debidamente etiquetados.'),$fuente);
         // $textrun->addText(htmlspecialchars( strtoupper($parametros['emplaS']).', '),$subrayado);
         // $textrun->addText(htmlspecialchars('conjuntamente con personal del área y el delegado de la dirección de control de activos se ha ejecutado la revisión de los bienes, no se presentaron novedades debidamente etiquetados.'),$fuente);
         $textrun->addTextBreak(2); 
         $textrun->addText(htmlspecialchars(strtoupper($parametros['cusE']).', '),$subrayado); 
         $textrun->addText(htmlspecialchars(' en calidad de  '),$fuente);
         $textrun->addText(htmlspecialchars('custodio responsable, '),$subrayado); 
         $textrun->addText(htmlspecialchars('declara recibir en su totalidad del bien o los bienes antes descritos en condiciones de uso que están ubicados en '),$fuente);
         $textrun->addText(htmlspecialchars(strtoupper($parametros['emplaE']).', '),$subrayado);
         $textrun->addText(htmlspecialchars(' y acepta por medio de este instrumento, someterse a todas y cada una de las disposiciones'),$fuente); 

          $seccion->addListItem(htmlspecialchars('Responsable de dar buen uso de los bienes asignados'));
        $seccion->addListItem(htmlspecialchars('Comunicar y solicitar autorización para el movimiento dentro y fuera de la PUCE de los bienes.'));
        $seccion->addListItem(htmlspecialchars('Verificar el estado y ubicación de los bienes por constataciones aleatorias que se realizarán.'));
        $seccion->addListItem(htmlspecialchars('Notificar en caso de tener algún siniestro con los bienes.'));
        $seccion->addListItem(htmlspecialchars('Informar si la etiqueta de control de bienes PUCE se ha deteriorado o perdido.'));
        $seccion->addListItem(htmlspecialchars('Informar donaciones que reciba la PUCE.'));

        $seccion->addTextBreak(1);

        ///secion firmas 
        $estiloTabla = [
            "borderColor" => "FFFFFF",
            "alignment" => Jc::CENTER,
            // "borderSize" => 0,
            // "cellMargin" => 500,
        ];
      
        $documento->addTableStyle('estilo1',$estiloTabla);
        $table = $seccion->addTable('estilo1');
        $table->addRow();
        $table->addCell(3000, $alineacionCenter)->addText('------------------------------------',$fuente,$alineacionCenter);
        $table->addCell(3000, $alineacionCenter)->addText('', $fuente);
        $table->addCell(3000, $alineacionCenter)->addText('------------------------------------', $fuente,$alineacionCenter);
        $table->addRow();
        $table->addCell(3000, $alineacionCenter)->addText(strtoupper($parametros['cusS']), $fuente,$alineacionCenter);
        $table->addCell(3000, $alineacionCenter)->addText('', $fuente);
        $table->addCell(3000, $alineacionCenter)->addText(strtoupper($parametros['cusE']), $fuente,$alineacionCenter);
        $table->addRow();
        $table->addCell(3000, $alineacionCenter)->addText('', $fuente,$alineacionCenter);
        $table->addCell(3000, $alineacionCenter)->addText('-----------------------------------', $fuente,$alineacionCenter);
        $table->addCell(3000, $alineacionCenter)->addText('', $fuente,$alineacionCenter);
        $table->addRow();
        $table->addCell(3000, $alineacionCenter)->addText('', $fuente,$alineacionCenter);
        $table->addCell(3000, $alineacionCenter)->addText('Dir. De Control De Activo', $fuente,$alineacionCenter);
        $table->addCell(3000, $alineacionCenter)->addText('', $fuente,$alineacionCenter);
        $table->addRow();
        $table->addCell(3000, $alineacionCenter)->addText('', $fuente,$alineacionCenter);
        $table->addCell(3000, $alineacionCenter)->addText('Diana Espín Aguirre', $fuente,$alineacionCenter);
        $table->addCell(3000, $alineacionCenter)->addText('', $fuente,$alineacionCenter);



         $fuenteFooter = [
            "name" => "Calibri",
            "size" => 10,
            "color" => "000000",
            "italic" => false,
            "bold" => false,
        ];
        $alineacionM = [
            // "alignment" => Jc::CENTER,
            "lineHeight" =>0.75,
            'marginLeft'=>-50
        ];


     $footer = $seccion->addFooter();       
     $footer->addImage(
            '../../img/forma1.png',
             array(
                'width'=>595,
                'height'=>5,
                'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'marginLeft' => -50,
                'marginTop'=>2,
                'wrappingStyle'=> 'inline'
            )        
        );
     
          $footer->addImage(
            '../../img/footer_puce_acta.png',
            array(
                'width'=>550,
                'height'=>50,
                'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'marginLeft' => -50,
                'marginTop'=>-10,
                'wrappingStyle'=> 'behind'
            )        
        );




    $seccion = $documento->addSection(
        array(
        'orientation'=>'landscape')
    );
    $seccion->addTextBreak(2);
    $usuario = $_SESSION['INICIO']['ID_USUARIO'];
    $lista = $this->actas->lista_actas($usuario);
        # Otra tabla
        $estiloTabla2 = [
            'border'=>1,
            "borderColor" => "080808",
            "alignment" => Jc::LEFT,
            "borderSize" => 2,
            "cellMargin" => 10,
        ];        
       # Encabezados
        $fuente = [
            "name" => "Arial",
            "size" => 8,
            "color" => "000000",
            'Bold'=>true,
        ];

        $fuente2 = [
            "name" => "Arial",
            "size" => 8,
            "color" => "000000",
            'Bold'=>false,
        ];


       
        $documento->addTableStyle('estilo2',$estiloTabla2);
        $table = $seccion->addTable('estilo2');
        $table->addRow();
        $table->addCell(3000, $alineacionCenter)->addText('Asset',$fuente,$alineacionCenter);
        $table->addCell(7000, $alineacionCenter)->addText('Activo',$fuente,$alineacionCenter);
        $table->addCell(2000, $alineacionCenter)->addText('RFID', $fuente,$alineacionCenter);
        $table->addCell(2000, $alineacionCenter)->addText('Serie', $fuente,$alineacionCenter);
        $table->addCell(2000, $alineacionCenter)->addText('Modelo', $fuente,$alineacionCenter);
        $table->addCell(7000, $alineacionCenter)->addText('Custodio', $fuente,$alineacionCenter);
        $table->addCell(7000, $alineacionCenter)->addText('Emplazamiento', $fuente,$alineacionCenter);
        $table->addCell(2000, $alineacionCenter)->addText('Valor', $fuente,$alineacionCenter);

        $total = 0;
        foreach ($lista as $key => $value) {
            // print_r($value);die();
            $table->addRow();
            $table->addCell(3000)->addText($value["asset"],$fuente2);
            $table->addCell(7000)->addText($value["articulo"],$fuente2);
            $table->addCell(7000)->addText($value["TAG_UNIQUE"],$fuente2);
            $table->addCell(7000)->addText($value["SERIE"],$fuente2);
            $table->addCell(7000)->addText($value["MODELO"],$fuente2);
            $table->addCell(7000)->addText($value["PERSON_NOM"],$fuente2);
            $table->addCell(7000)->addText($value["DENOMINACION"],$fuente2);
            $table->addCell(2000)->addText(floatval(str_replace(',','', $value["valor"])),$fuente2,$alineacionRIGHT);
            $total = $total+number_format(floatval(str_replace(',','', $value["valor"])),2,'.','');
       }

        $cellColSpan = ['gridSpan' => 6, 'valign' => 'center'];

        $table->addRow();
        $table->addCell(null, $cellColSpan);
        $table->addCell(7000, $alineacionCenter)->addText('Total', $fuente,$alineacionRIGHT);
        $table->addCell(2000, $alineacionCenter)->addText($total, $fuente,$alineacionRIGHT);


        $header = $seccion->addHeader();
        $header->addImage(
            '../../img/cabecera_puce_actaH.png',
            array(
            'width' => 825,
            'height' => 70,
            'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
            'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_HORIZONTAL_RIGHT,
            'posHorizontalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_PAGE,
            'posVerticalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_PAGE,
            'marginLeft' => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(15.5),
            'marginTop' => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(1.55),
            )
        );



         $footer = $seccion->addFooter();       
         $footer->addImage(
                '../../img/footer_puce_actaH.png',
                 array(
                    'width'=>825,
                    'height'=>70,
                    'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                    'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                    'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                    'marginLeft' => -50,
                    'marginTop'=>-25,
                    'wrappingStyle'=> 'inline',
                )        
            );




     
     
      
        $documento->getCompatibility()->setOoxmlVersion(15);
        # Idioma español de México
        $documento->getSettings()->setThemeFontLang(new Language("ES-MX"));

        # Guardarlo
        // $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($documento, "Word2007");
        // $objWriter->save("TRASPASO SALIENTE-ENTRANTE.docx");

        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="TRASPASO SALIENTE-ENTRANTE.docx"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($documento, 'Word2007');
        $xmlWriter->save("php://output");

    }

    function acta_entrega_donacion($parametros)
    {

        // print_r($parametros);die();
    $documento = new \PhpOffice\PhpWord\PhpWord();
    $propiedades = $documento->getDocInfo();
    $propiedades->setCreator("Luis Cabrera Benito");
    $propiedades->setTitle("Texto");

    # Agregar texto...
    /*
    Todos los textos deben estar dentro de una sección
     */

    $seccion = $documento->addSection();

    //imagen cabecera
    $header = $seccion->addHeader();
    $header->addImage(
        '../../img/cabecera_puce_acta.png',
        array(
        'width' => 595,
        'height' => 100,
        'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
        'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_HORIZONTAL_RIGHT,
        'posHorizontalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_PAGE,
        'posVerticalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_PAGE,
        'marginLeft' => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(15.5),
        'marginTop' => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(1.55),
        )
    );

    $seccion->addTextBreak(4);


    # Títulos.
    $fuenteTitulo = [
        "name" => "Montserrat ExtraBold",
        "size" => 11,
        "color" => "000000",
        "bold" => true,
    ];

    $alineacion = [
        "alignment" => Jc::CENTER,
        // "lineHeight" => 0.7,
    ];
    $alineacionEND = [
        "alignment" => Jc::END,
        // "lineHeight" => 0.7,
    ];

    // $documento->addParagraphStyle('myStyle', array('align'=>'center', 'spaceAfter'=>100));
    $documento->addTitleStyle(1, $fuenteTitulo,$alineacion);
    $seccion->addTitle("ACTA DE ENTREGA DONACION ACTIVO", 1);

    $documento->addTitleStyle(2, $fuenteTitulo,$alineacionEND);    
    $seccion->addTitle($parametros['sub'].'-'.date('Y'), 2);


    //cuerpo del documento
    $fuente = [
        "name" => "Montserrat",
        "size" => 10,
        "color" => "000000",
        "italic" => false,
        "bold" => false,
    ];
    $fuenteN = [
        "name" => "Montserrat",
        "size" => 10,
        "color" => "000000",
        "italic" => false,
        "bold" => true,
    ];
    $alineacionC = [
        "alignment" => Jc::BOTH,
        "lineHeight" => 1.5,
    ];

    $alineacionCenter = [
        "alignment" => Jc::CENTER,
        // "lineHeight" => 1.5,
    ];
    $alineacionRIGHT = [
        "alignment" => Jc::RIGHT,
        // "lineHeight" => 1.5,
    ];


    $fecha = strftime("%A %d de %B del %Y");

    $textrun = $seccion->addTextRun($alineacionC);
    $textrun->addText("En la ciudad de Quito, ".$fecha.", comparece por una parte ".strtoupper($parametros['donante'])." con RUC ".$parametros['ci']." y por otra parte la ",$fuente);
 
    $textrun->addText("Pontificia Universidad Católica del Ecuador, ",$fuenteN);
    $textrun->addText("como responsable la unidad ".strtoupper($parametros['unidad'])." su director ".strtoupper($parametros['director']).", quién recibe en donación ",$fuente);

    $textrun->addTextBreak(3);

    $textrun->addText("El valor total de la donación se estima en $0.00 y son recibidos en la PONTIFICIA UNIVERSIDAD CATÓLICA DEL ECUADOR.",$fuente);

    $textrun->addTextBreak(3);


    $fancyTableStyle = ['borderSize' =>2, 'borderColor' => 'FFFFFF'];  

    $documento->addTableStyle('estilo1', $fancyTableStyle);
    $table = $seccion->addTable('estilo1');
    $table->addRow();
    $table->addCell(3000, $alineacionCenter)->addText('------------------------------------',$fuente,$alineacionCenter);
    $table->addCell(3000, $alineacionCenter)->addText('', $fuente);
    $table->addCell(3000, $alineacionCenter)->addText('------------------------------------', $fuente,$alineacionCenter);
    $table->addRow();
    $table->addCell(3000, $alineacionCenter)->addText('Dir. De Control de Activo', $fuente,$alineacionCenter);
    $table->addCell(3000, $alineacionCenter)->addText('', $fuente);
    $table->addCell(3000, $alineacionCenter)->addText(strtoupper($parametros['donante']), $fuente,$alineacionCenter);
    $table->addRow();
    $table->addCell(3000, $alineacionCenter)->addText('Diana Espín Aguirre', $fuente,$alineacionCenter);
    $table->addCell(3000, $alineacionCenter)->addText('', $fuente,$alineacionCenter);
    $table->addCell(3000, $alineacionCenter)->addText('Entidad donante', $fuente,$alineacionCenter);




         $fuenteFooter = [
            "name" => "Calibri",
            "size" => 10,
            "color" => "000000",
            "italic" => false,
            "bold" => false,
        ];
        $alineacionM = [
            // "alignment" => Jc::CENTER,
            "lineHeight" =>0.75,
            'marginLeft'=>-50
        ];


     $footer = $seccion->addFooter();       
     $footer->addImage(
            '../../img/forma1.png',
             array(
                'width'=>595,
                'height'=>5,
                'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'marginLeft' => -50,
                'marginTop'=>2,
                'wrappingStyle'=> 'inline'
            )        
        );
     
          $footer->addImage(
            '../../img/footer_puce_acta.png',
            array(
                'width'=>550,
                'height'=>50,
                'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                'marginLeft' => -50,
                'marginTop'=>-10,
                'wrappingStyle'=> 'behind'
            )        
        );





    $seccion = $documento->addSection(
        array(
        'orientation'=>'landscape')
    );

    $seccion->addTextBreak(2);
    $usuario = $_SESSION['INICIO']['ID_USUARIO'];
    $lista = $this->actas->lista_actas($usuario);
        # Otra tabla
        $estiloTabla2 = [
            'border'=>1,
            "borderColor" => "080808",
            "alignment" => Jc::LEFT,
            "borderSize" => 2,
            "cellMargin" => 10,
        ];        
       # Encabezados
        $fuente = [
            "name" => "Arial",
            "size" => 8,
            "color" => "000000",
            'Bold'=>true,
        ];

        $fuente2 = [
            "name" => "Arial",
            "size" => 8,
            "color" => "000000",
            'Bold'=>false,
        ];


       
        $documento->addTableStyle('estilo2',$estiloTabla2);
        $table = $seccion->addTable('estilo2');
        $table->addRow();
        $table->addCell(4000, $alineacionCenter)->addText('Asset',$fuente,$alineacionCenter);
        $table->addCell(7000, $alineacionCenter)->addText('Activo',$fuente,$alineacionCenter);
        $table->addCell(2000, $alineacionCenter)->addText('RFID', $fuente,$alineacionCenter);
        $table->addCell(2000, $alineacionCenter)->addText('Serie', $fuente,$alineacionCenter);
        $table->addCell(2000, $alineacionCenter)->addText('Modelo', $fuente,$alineacionCenter);
        $table->addCell(7000, $alineacionCenter)->addText('Custodio', $fuente,$alineacionCenter);
        $table->addCell(7000, $alineacionCenter)->addText('Emplazamiento', $fuente,$alineacionCenter);
        $table->addCell(2000, $alineacionCenter)->addText('Valor', $fuente,$alineacionCenter);

        $total = 0;
        foreach ($lista as $key => $value) {
            // print_r($value);die();
            $table->addRow();
            $table->addCell(4000)->addText($value["asset"],$fuente2);
            $table->addCell(7000)->addText($value["articulo"],$fuente2);
            $table->addCell(7000)->addText($value["TAG_UNIQUE"],$fuente2);
            $table->addCell(7000)->addText($value["SERIE"],$fuente2);
            $table->addCell(7000)->addText($value["MODELO"],$fuente2);
            $table->addCell(7000)->addText($value["PERSON_NOM"],$fuente2);
            $table->addCell(7000)->addText($value["DENOMINACION"],$fuente2);
             $table->addCell(2000)->addText(floatval(str_replace(',','', $value["valor"])),$fuente2,$alineacionRIGHT);
            $total = $total+number_format(floatval(str_replace(',','', $value["valor"])),2,'.','');
       }

        $cellColSpan = ['gridSpan' => 6, 'valign' => 'center'];

        $table->addRow();
        $table->addCell(null, $cellColSpan);
        $table->addCell(7000, $alineacionCenter)->addText('Total', $fuente,$alineacionRIGHT);
        $table->addCell(2000, $alineacionCenter)->addText($total, $fuente,$alineacionRIGHT);


        $header = $seccion->addHeader();
        $header->addImage(
            '../../img/cabecera_puce_actaH.png',
            array(
            'width' => 825,
            'height' => 70,
            'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
            'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_HORIZONTAL_RIGHT,
            'posHorizontalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_PAGE,
            'posVerticalRel' => \PhpOffice\PhpWord\Style\Image::POSITION_RELATIVE_TO_PAGE,
            'marginLeft' => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(15.5),
            'marginTop' => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(1.55),
            )
        );



         $footer = $seccion->addFooter();       
         $footer->addImage(
                '../../img/footer_puce_actaH.png',
                 array(
                    'width'=>825,
                    'height'=>70,
                    'positioning' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                    'posHorizontal' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                    'posVertical' => \PhpOffice\PhpWord\Style\Image::POSITION_ABSOLUTE,
                    'marginLeft' => -50,
                    'marginTop'=>-25,
                    'wrappingStyle'=> 'inline',
                )        
            );
         
        

        # Para que no diga que se abre en modo de compatibilidad
        $documento->getCompatibility()->setOoxmlVersion(15);
        # Idioma español de México
        $documento->getSettings()->setThemeFontLang(new Language("ES-MX"));

        # Guardarlo
        // $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($documento, "Word2007");

        // $objWriter->save("ACTA DONACION.docx");

        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="ACTA DONACION.docx"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($documento, 'Word2007');
        $xmlWriter->save("php://output");



    }
}
?>