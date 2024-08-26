<?php

use Box\Spout\Common\Entity\Cell;

require_once(dirname(__DIR__, 3) . '/lib/pdf/fpdf.php');
require_once(dirname(__DIR__, 3) . '/modelo/PASANTES/01_SEBASTIAN/formularios_firmasM.php');

$controlador = new formularios_firmasC();

if (isset($_GET['persona_natural'])) {
    echo $controlador->persona_natural();
}

if (isset($_GET['hola'])) {
    echo $controlador->persona();
}

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar());
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['persona_juridica'])) {
    echo ($controlador->persona_juridica($_GET['id']));
}

class formularios_firmasC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new formularios_firmasM();
    }

    function persona_natural()
    {
        //////////////////////////////////////////////////////////////////////////////////////////////////////
        $fir_segundo_apellido = 'Pilca';
        $fir_primer_apellido = 'Ortiz';
        $fir_primer_nombre = 'Ruben';
        $fir_segundo_nombre = 'Andres';

        $nombres_completos =  $fir_primer_apellido . ' ' . $fir_segundo_apellido  . ' ' . $fir_primer_nombre . ' ' . $fir_segundo_nombre;


        //////////////////////////////////////////////////////////////////////////////////////////////////////

        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->SetMargins(28, 15, 28);
        $pdf->SetAutoPageBreak(true, 15);
        $pdf->AddPage();

        $pdf->SetY(10);
        $pdf->SetFont('Helvetica', '', 11);
        $pdf->SetTextColor(169, 169, 169);
        $pdf->Cell(0, 10, 'Pag ' . $pdf->PageNo(), 0, 1, 'C');




        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Times', 'BU', 12);
        $pdf->Cell(0, 15, utf8_decode('FORMULARIO PERSONA NATURAL'), 0, 1, 'C');

        $pdf->Ln(10);

        $pdf->SetFont('Times', 'B', 12);
        $pdf->SetFillColor(255, 255, 255); // Color de relleno
        $pdf->SetTextColor(0, 102, 204);   // Cambia el color del texto (Azul oscuro)
        $pdf->Cell(0, 3, utf8_decode('CIUDAD, FECHA:'), 0, 1, 'C');


        $pdf->Ln(8);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->SetTextColor(0, 0, 0);   // Cambia el color del texto (Azul oscuro)
        $pdf->Cell(0, 7, utf8_decode('DATOS REPRESENTANTE LEGAL'), 1, 1, 'C');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(60, 7, utf8_decode('NOMBRES COMPLETOS'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(94, 7, utf8_decode($nombres_completos), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(60, 7, utf8_decode('NÚMERO DE RUC:'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(94, 7, utf8_decode('Ruben Pilca'), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(60, 7, utf8_decode('NOMBRES COMPLETOS'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(94, 7, utf8_decode('Ruben Pilca'), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(60, 7, utf8_decode('NOMBRES COMPLETOS'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(94, 7, utf8_decode('Ruben Pilca'), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(60, 7, utf8_decode('DIRECCION CORREO ELECTRONICO:'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(94, 7, utf8_decode('Ruben Pilca'), 1, 1, 'L');

        $pdf->AddPage();

        $pdf->SetY(10);
        $pdf->SetFont('Helvetica', '', 11);
        $pdf->SetTextColor(169, 169, 169);
        $pdf->Cell(0, 10, 'Pag ' . $pdf->PageNo(), 0, 1, 'C');


        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 15, utf8_decode('Autorización de Certificados Digitales para funcionarios.'), 0, 1, 'C');

        $pdf->Ln(10);

        $pdf->SetFont('Times', 'B', 12);
        $pdf->SetFillColor(255, 255, 255); // Color de relleno
        $pdf->SetTextColor(0, 102, 204);   // Cambia el color del texto (Azul oscuro)
        $pdf->Cell(0, 3, utf8_decode('CIUDAD, FECHA:'), 0, 1, 'C');

        $pdf->Ln(30);

        $pdf->SetFont('Arial', '', 9.5);
        $pdf->SetTextColor(0, 0, 0);   // Cambia el color del texto (Azul oscuro)
        $authorization_text_natural =
            'Yo ' . $nombres_completos . ' con número de cédula o pasaporte 100456789654; autorizo a ANFAC AUTORIDAD DE CERTIFICACION ECUADOR C.A. la emision de mi certificado digital de Firma Electronica. 
            
Particular que pongo en su conocimiento para los fines pertinentes. 
            
            
            
Atentamente,';

        $pdf->MultiCell(0, 6, utf8_decode($authorization_text_natural), 0, 'L');

        $pdf->Ln(30);

        $pdf->SetFont('Arial', '', 26);

        $pdf->Cell(100, 8, utf8_decode('X'), 'B', 1, 'L');



        $pdf->Output();
    }

    function persona()
    {
        echo 'hola';
    }

    function listar()
    {
        $lista = $this->modelo->listar();
        return $lista;
    }

    function insertar_editar($parametros)
    {
        $fir_sol_razon_social = '';
        if (!empty($parametros['txt_razon_social'])) {
            $fir_sol_razon_social = $parametros['txt_razon_social'];
        }
        $fir_sol_direccion_ruc_juridico = '';
        if (!empty($parametros['txt_direccion_ruc'])) {
            $fir_sol_direccion_ruc_juridico = $parametros['txt_direccion_ruc'];
        }
        $fir_sol_ruc_juridico = '';
        if (!empty($parametros['txt_ruc'])) {
            $fir_sol_ruc_juridico = $parametros['txt_ruc'];
        }
        $fir_sol_correo_empresarial = '';
        if (!empty($parametros['txt_correo_empresarial'])) {
            $fir_sol_correo_empresarial = $parametros['txt_correo_empresarial'];
        }
        $fir_sol_direccion_domicilio = '';
        if (!empty($parametros['txt_direccion_domicilio'])) {
            $fir_direccion_domicilio = $parametros['txt_direccion_domicilio'];
        }
        $fir_sol_correo = '';
        if (!empty($parametros['txt_correo'])) {
            $fir_sol_correo = $parametros['txt_correo'];
        }
        $datos = array(
            array('campo' => 'fir_sol_primer_nombre', 'dato' => $parametros['txt_primer_nombre']),
            array('campo' => 'fir_sol_segundo_nombre', 'dato' => $parametros['txt_segundo_nombre']),
            array('campo' => 'fir_sol_primer_apellido', 'dato' => $parametros['txt_primer_apellido']),
            array('campo' => 'fir_sol_segundo_apellido', 'dato' => $parametros['txt_segundo_apellido']),
            array('campo' => 'fir_sol_numero_identificacion', 'dato' => $parametros['txt_numero_identificacion']),
            array('campo' => 'fir_sol_direccion_domicilio', 'dato' => $fir_sol_direccion_domicilio),
            array('campo' => 'fir_sol_correo', 'dato' => $fir_sol_correo),
            array('campo' => 'fir_sol_ciudad', 'dato' => $parametros['txt_ciudad']),
            array('campo' => 'fir_sol_provincia', 'dato' => $parametros['txt_provincia']),
            array('campo' => 'fir_sol_numero_celular', 'dato' => $parametros['txt_celular']),
            array('campo' => 'fir_sol_numero_fijo', 'dato' => $parametros['txt_fijo']),
            array('campo' => 'fir_sol_razon_social', 'dato' => $fir_sol_razon_social),
            array('campo' => 'fir_sol_ruc_juridico', 'dato' => $fir_sol_ruc_juridico),
            array('campo' => 'fir_sol_direccion_ruc_juridico', 'dato' => $fir_sol_direccion_ruc_juridico),
            array('campo' => 'fir_sol_correo_empresarial', 'dato' => $fir_sol_correo_empresarial),
            array('campo' => 'fir_sol_tipo_formulario', 'dato' => $parametros['txt_tipo']),
        );

        $datos = $this->modelo->insertar($datos);

        return $datos;
    }
    function persona_juridica($id)
    {

        $datos = $this->modelo->where('fir_sol_id', $id)->listar();

        $fir_sol_primer_nombre = $datos[0]['fir_sol_primer_nombre'];
        $fir_sol_segundo_nombre = $datos[0]['fir_sol_segundo_nombre'];
        $fir_sol_primer_apellido = $datos[0]['fir_sol_primer_apellido'];
        $fir_sol_segundo_apellido = $datos[0]['fir_sol_segundo_apellido'];
        $fir_sol_numero_identificacion = $datos[0]['fir_sol_numero_identificacion'];
        $fir_sol_ciudad = $datos[0]['fir_sol_ciudad'];
        $fir_sol_provincia = $datos[0]['fir_sol_provincia'];
        $fir_sol_numero_celular = $datos[0]['fir_sol_numero_celular'];
        $fir_sol_numero_fijo = $datos[0]['fir_sol_numero_fijo'];
        $fir_sol_razon_social = $datos[0]['fir_sol_razon_social'];
        $fir_sol_ruc_juridico = $datos[0]['fir_sol_ruc_juridico'];
        $fir_sol_direccion_ruc_juridico = $datos[0]['fir_sol_direccion_ruc_juridico'];
        $fir_sol_correo_empresarial = $datos[0]['fir_sol_correo_empresarial'];

        $fir_sol_nombre_completo = $fir_sol_primer_nombre . ' ' . $fir_sol_segundo_nombre . ' ' . $fir_sol_primer_apellido . ' ' . $fir_sol_segundo_apellido;
        $fir_sol_autorizacion = 'Yo, ' . $fir_sol_nombre_completo . ' con número de cédula o pasaporte ' . $fir_sol_numero_identificacion . ' en mi calidad de representante legal de la empresa ' . 'Yo que sé' . ', con número de RUC ' . $fir_sol_ruc_juridico . ', autorizo a ANFAC AUTORIDAD DE CERTIFICACION ECUADOR C.A. la emisión de los siguientes certificados digitales para los siguientes empleados de esta empresa:';

        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(true, 15);
        $pdf->setFillColor(255, 255, 255);
        $pdf->AliasNbPages();
        $pdf->AddPage();
        
        $pdf->SetY(10);
        $pdf->SetFont('Helvetica', '', 11);
        $pdf->SetTextColor(169, 169, 169);
        $pdf->Cell(0, 10, 'Pag ' . $pdf->PageNo(), 0, 1, 'C');

        $pdf->SetTextColor(0, 0, 0);

        $pdf->SetFont('Times', 'BU', 13);
        $pdf->Cell(0, 10, utf8_decode('FORMULARIO PERSONA JURÍDICA'), 0, 1, 'C');

        $pdf->Ln(7);

        $pdf->SetTextColor(0, 102, 204);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(85, 7, utf8_decode('Ciudad, Fecha: '), 0, 0, 'R');
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(50, 7, utf8_decode('Quito, 2023-02-10'), 0, 1, 'L');

        $pdf->Ln(7);

        $pdf->SetTextColor(0, 0, 0);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(0, 7, utf8_decode('DATOS REPRESENTANTE LEGAL'), 1, 1, 'C');

        $pdf->Cell(85, 7, utf8_decode('RAZÓN SOCIAL'), 1, 0, 'R');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 7, utf8_decode($fir_sol_razon_social), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(85, 7, utf8_decode('RUC'), 1, 0, 'R');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 7, utf8_decode($fir_sol_ruc_juridico), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->MultiCellRow(85, 7, utf8_decode('NOMBRES COMPLETOS DEL' . "\n" . 'REPRESENTANTE LEGAL'), 1, 'R', 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 14, utf8_decode($fir_sol_nombre_completo), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(85, 7, utf8_decode('NÚMERO DE CÉDULA O PASAPORTE'), 1, 0, 'R');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 7, utf8_decode($fir_sol_numero_identificacion), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(85, 7, utf8_decode('DIRECCIÓN (COMO ESTÁ EN EL RUC)'), 1, 0, 'R');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 7, utf8_decode($fir_sol_direccion_ruc_juridico), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(85, 7, utf8_decode('PROVINCIA'), 1, 0, 'R');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 7, utf8_decode($fir_sol_provincia), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(85, 7, utf8_decode('CIUDAD'), 1, 0, 'R');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 7, utf8_decode($fir_sol_ciudad), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->MultiCellRow(85, 7, utf8_decode('DIRECCION CORREO ELECTRÓNICO' . "\n" . 'EMPRESARIAL VÁLIDO'), 1, 'R', 0);
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 14, utf8_decode($fir_sol_correo_empresarial), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(85, 7, utf8_decode('N° CELULAR (PONER CÓDIGO DE PAÍS)'), 1, 0, 'R');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 7, utf8_decode($fir_sol_numero_celular), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(85, 7, utf8_decode('N° FIJO (PONER CÓDIGO DE PAÍS)'), 1, 0, 'R');
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 7, utf8_decode($fir_sol_numero_fijo), 1, 1, 'L');

        
        
        $pdf->AddPage();

        $pdf->SetY(10);
        $pdf->SetFont('Helvetica', '', 11);
        $pdf->SetTextColor(169, 169, 169);
        $pdf->Cell(0, 10, 'Pag ' . $pdf->PageNo(), 0, 1, 'C');

        $pdf->SetTextColor(0, 0, 0);

        $pdf->SetFont('Times', 'BU', 13);
        $pdf->Cell(0, 10, utf8_decode('AUTORIZACIÓN DE CERTIFICADOS DIGITALES'), 0, 1, 'C');

        $pdf->Ln(10);

        $pdf->SetTextColor(0, 102, 204);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(85, 7, utf8_decode('Ciudad, Fecha: '), 0, 0, 'R');
        $pdf->SetFont('Arial', '', 11);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(50, 7, utf8_decode('Quito, 2023-02-10'), 0, 1, 'L');

        $pdf->Ln(8);


        $pdf->MultiCell(0, 6, utf8_decode($fir_sol_autorizacion));

        $pdf->Ln(7);

        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(8, 12, utf8_decode('#'), 1, 0, 'C');
        $pdf->MultiCellRow(92, 6, utf8_decode('Nombres y apellidos de las personas que van a hacer uso de un certificado digital'), 1, 'C', 0);
        $pdf->Cell(40, 12, utf8_decode('Número de Cédula'), 1, 0, 'C');
        $pdf->Cell(40, 12, utf8_decode('Cargo'), 1, 1, 'C');

        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(8, 6, utf8_decode('1'), 1, 0, 'C');
        $pdf->Cell(92, 6, utf8_decode(''), 1, 0, 'C');
        $pdf->Cell(40, 6, utf8_decode(''), 1, 0, 'C');
        $pdf->Cell(40, 6, utf8_decode(''), 1, 1, 'C');

        $pdf->Cell(8, 6, utf8_decode('2'), 1, 0, 'C');
        $pdf->Cell(92, 6, utf8_decode(''), 1, 0, 'C');
        $pdf->Cell(40, 6, utf8_decode(''), 1, 0, 'C');
        $pdf->Cell(40, 6, utf8_decode(''), 1, 1, 'C');

        $pdf->Ln(7);

        $pdf->MultiCell(0, 5, utf8_decode('Nota: Todas las personas, incluido el representante legal, que requieran de un certificado digital de firma electrónica deben constar en la tabla anterior.'));

        $pdf->Ln(7);

        $pdf->MultiCell(0, 5, utf8_decode('Particular que pongo en su conocimiento para los fines pertinentes.'));

        $pdf->Ln(10);

        $pdf->Cell(0, 6, utf8_decode('Atentamente,'), 0, 1, 'J');

        $pdf->MultiCell(0, 6, utf8_decode(''), 0, 'L');

        $pdf->Ln(30);

        $pdf->SetFont('Arial', '', 26);

        $pdf->Cell(100, 8, utf8_decode('X'), 'B', 1, 'L');

        $pdf->Ln(15);

        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(0, 6, utf8_decode('Nombre: '.$fir_sol_nombre_completo), 0, 1, 'J');
        $pdf->Cell(0, 6, utf8_decode('Representante legal: '.'Alguien ha de ser'), 0, 1, 'J');


        $pdf->Output();
    }
}
