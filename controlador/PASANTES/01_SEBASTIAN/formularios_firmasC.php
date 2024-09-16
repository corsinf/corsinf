<?php
require_once(dirname(__DIR__, 3) . '/lib/pdf/fpdf.php');
require_once(dirname(__DIR__, 3) . '/modelo/PASANTES/01_SEBASTIAN/formularios_firmasM.php');

$controlador = new formularios_firmasC();

if (isset($_GET['persona_natural'])) {
    echo $controlador->persona_natural($_GET['id']);
}

if (isset($_GET['persona_natural_ruc'])) {
    echo $controlador->persona_natural_ruc($_GET['id']);
}

if (isset($_GET['listar'])) {
    echo json_encode($controlador->listar());
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}
class formularios_firmasC
{
    private $modelo;

    function __construct()
    {
        $this->modelo = new formularios_firmasM();
    }

    function persona_natural($id)
    {
        //////////////////////////////////////////////////////////////////////////////////////////////////////
        $datos = $this->modelo->where('fir_sol_id', $id)->listar();
        $fir_sol_primer_nombre = $datos[0]['fir_sol_primer_nombre'];
        $fir_sol_segundo_nombre = $datos[0]['fir_sol_segundo_nombre'];
        $fir_sol_primer_apellido = $datos[0]['fir_sol_primer_apellido'];
        $fir_sol_segundo_apellido = $datos[0]['fir_sol_segundo_apellido'];
        $fir_sol_numero_identificacion = $datos[0]['fir_sol_numero_identificacion'];
        $fir_sol_direccion_domicilio = $datos[0]['fir_sol_direccion_domicilio'];
        $fir_sol_correo = $datos[0]['fir_sol_correo'];
        $fir_sol_ciudad = $datos[0]['fir_sol_ciudad'];
        $fir_sol_provincia = $datos[0]['fir_sol_provincia'];
        $fir_sol_numero_celular = $datos[0]['fir_sol_numero_celular'];
        $fir_sol_numero_fijo = $datos[0]['fir_sol_numero_fijo'];
        $fir_sol_razon_social = $datos[0]['fir_sol_razon_social'];
        $fir_sol_ruc_juridico = $datos[0]['fir_sol_ruc_juridico'];
        $fir_sol_direccion_ruc_juridico = $datos[0]['fir_sol_direccion_ruc_juridico'];
        $fir_sol_correo_empresarial = $datos[0]['fir_sol_correo_empresarial'];
        $fir_sol_tipo_formulario = $datos[0]['fir_sol_tipo_formulario'];
        $fir_sol_nombres_completos =  $fir_sol_primer_apellido . ' ' . $fir_sol_segundo_apellido  . ' ' . $fir_sol_primer_nombre . ' ' . $fir_sol_segundo_nombre;


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
        $pdf->Cell(64, 7, utf8_decode('NOMBRES COMPLETOS'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(90, 7, utf8_decode($fir_sol_nombres_completos), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(64, 7, utf8_decode('NÚMERO DE CEDULA O PASAPORTE:'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(90, 7, utf8_decode($fir_sol_numero_identificacion), 1, 1, 'L');

        if (strlen($fir_sol_direccion_domicilio) > 50 & strlen($fir_sol_direccion_domicilio) < 101) {
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(64, 14, utf8_decode('DIRECCIÓN DOMICILIO'), 1, 0, 'L');
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCellRow(90, 7, utf8_decode($fir_sol_direccion_domicilio), 1, 1, 'L');
            $pdf->Ln(14);
        } else {
            if (strlen($fir_sol_direccion_domicilio) < 51){
                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(64, 7, utf8_decode('DIRECCIÓN DOMICILIO'), 1, 0, 'L');
                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(90, 7, utf8_decode($fir_sol_direccion_domicilio), 1, 1, 'L');
            }
            if (strlen($fir_sol_direccion_domicilio) > 100 & strlen($fir_sol_direccion_domicilio) < 151) {
                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(64, 21, utf8_decode('DIRECCIÓN DOMICILIO'), 1, 0, 'L');
                $pdf->SetFont('Arial', '', 9);
                $pdf->MultiCellRow(90, 7, utf8_decode($fir_sol_direccion_domicilio), 1, 1, 'L');
                $pdf->Ln(21);
            }
        }

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(64, 7, utf8_decode('PROVINCIA'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(90, 7, utf8_decode($fir_sol_provincia), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(64, 7, utf8_decode('CIUDAD:'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(90, 7, utf8_decode($fir_sol_ciudad), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(64, 7, utf8_decode('DIRECCION CORREO ELECTRONICO:'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(90, 7, utf8_decode($fir_sol_correo), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(64, 7, utf8_decode('NO. CELULAR PONER CÓDIGO DE PAÍS'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(90, 7, utf8_decode($fir_sol_numero_celular), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(64, 7, utf8_decode('NO. FIJO PONER CÓDIGO DE PAÍS'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(90, 7, utf8_decode($fir_sol_numero_fijo), 1, 1, 'L');

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
            'Yo ' . $fir_sol_nombres_completos . ' con número de cédula o pasaporte ' . $fir_sol_numero_identificacion . '; autorizo a ANFAC AUTORIDAD DE CERTIFICACION ECUADOR C.A. la emision de mi certificado digital de Firma Electronica. 
            
Particular que pongo en su conocimiento para los fines pertinentes. 
            
            
            
Atentamente,';

        $pdf->MultiCell(0, 6, utf8_decode($authorization_text_natural), 0, 'L');

        $pdf->Ln(30);

        $pdf->SetFont('Arial', '', 26);

        $pdf->Cell(100, 8, utf8_decode('X'), 'B', 1, 'L');



        $pdf->Output();
    }

    function persona_natural_ruc($id)
    {
        //////////////////////////////////////////////////////////////////////////////////////////////////////
        $datos = $this->modelo->where('fir_sol_id', $id)->listar();
        $fir_sol_primer_nombre = $datos[0]['fir_sol_primer_nombre'];
        $fir_sol_segundo_nombre = $datos[0]['fir_sol_segundo_nombre'];
        $fir_sol_primer_apellido = $datos[0]['fir_sol_primer_apellido'];
        $fir_sol_segundo_apellido = $datos[0]['fir_sol_segundo_apellido'];
        $fir_sol_numero_identificacion = $datos[0]['fir_sol_numero_identificacion'];
        $fir_sol_direccion_domicilio = $datos[0]['fir_sol_direccion_domicilio'];
        $fir_sol_correo = $datos[0]['fir_sol_correo'];
        $fir_sol_ciudad = $datos[0]['fir_sol_ciudad'];
        $fir_sol_provincia = $datos[0]['fir_sol_provincia'];
        $fir_sol_numero_celular = $datos[0]['fir_sol_numero_celular'];
        $fir_sol_numero_fijo = $datos[0]['fir_sol_numero_fijo'];
        $fir_sol_nombres_completos =  $fir_sol_primer_apellido . ' ' . $fir_sol_segundo_apellido  . ' ' . $fir_sol_primer_nombre . ' ' . $fir_sol_segundo_nombre;


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
        $pdf->Cell(0, 15, utf8_decode('FORMULARIO PERSONA NATURAL CON RUC'), 0, 1, 'C');

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
        $pdf->Cell(64, 7, utf8_decode('NOMBRES COMPLETOS'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(90, 7, utf8_decode($fir_sol_nombres_completos), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(64, 7, utf8_decode('NÚMERO DE RUC:'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(90, 7, utf8_decode($fir_sol_numero_identificacion), 1, 1, 'L');

        if (strlen($fir_sol_direccion_domicilio) > 50 & strlen($fir_sol_direccion_domicilio) < 101) {
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->Cell(64, 14, utf8_decode('DIRECCIÓN DOMICILIO'), 1, 0, 'L');
            $pdf->SetFont('Arial', '', 9);
            $pdf->MultiCellRow(90, 7, utf8_decode($fir_sol_direccion_domicilio), 1, 1, 'L');
            $pdf->Ln(14);
        } else {
            if (strlen($fir_sol_direccion_domicilio) < 51){
                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(64, 7, utf8_decode('DIRECCIÓN DOMICILIO'), 1, 0, 'L');
                $pdf->SetFont('Arial', '', 9);
                $pdf->Cell(90, 7, utf8_decode($fir_sol_direccion_domicilio), 1, 1, 'L');
            }
            if (strlen($fir_sol_direccion_domicilio) > 100 & strlen($fir_sol_direccion_domicilio) < 151) {
                $pdf->SetFont('Arial', 'B', 9);
                $pdf->Cell(64, 21, utf8_decode('DIRECCIÓN DOMICILIO'), 1, 0, 'L');
                $pdf->SetFont('Arial', '', 9);
                $pdf->MultiCellRow(90, 7, utf8_decode($fir_sol_direccion_domicilio), 1, 1, 'L');
                $pdf->Ln(21);
            }
        }

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(64, 7, utf8_decode('PROVINCIA'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(90, 7, utf8_decode($fir_sol_provincia), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(64, 7, utf8_decode('CIUDAD:'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(90, 7, utf8_decode($fir_sol_ciudad), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(64, 7, utf8_decode('DIRECCION CORREO ELECTRONICO:'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(90, 7, utf8_decode($fir_sol_correo), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(64, 7, utf8_decode('NO. CELULAR PONER CÓDIGO DE PAÍS'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(90, 7, utf8_decode($fir_sol_numero_celular), 1, 1, 'L');

        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(64, 7, utf8_decode('NO. FIJO PONER CÓDIGO DE PAÍS'), 1, 0, 'L');
        $pdf->SetFont('Arial', '', 9);
        $pdf->Cell(90, 7, utf8_decode($fir_sol_numero_fijo), 1, 1, 'L');

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
            'Yo ' . $fir_sol_nombres_completos . ' con número de RUC ' . $fir_sol_numero_identificacion . '; autorizo a ANFAC AUTORIDAD DE CERTIFICACION ECUADOR C.A. la emision de mi certificado digital de Firma Electronica. 
            
Particular que pongo en su conocimiento para los fines pertinentes. 
            
            
            
Atentamente,';

        $pdf->MultiCell(0, 6, utf8_decode($authorization_text_natural), 0, 'L');

        $pdf->Ln(30);

        $pdf->SetFont('Arial', '', 26);

        $pdf->Cell(100, 8, utf8_decode('X'), 'B', 1, 'L');



        $pdf->Output();
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
        if (!empty($parametros['txt_ruc_juridico'])) {
            $fir_sol_ruc_juridico = $parametros['txt_ruc_juridico'];
        }
        $fir_sol_correo_empresarial = '';
        if (!empty($parametros['txt_correo_empresarial'])) {
            $fir_sol_correo_empresarial = $parametros['txt_correo_empresarial'];
        }
        $fir_sol_direccion_domicilio = '';
        if (!empty($parametros['txt_direccion_domicilio'])) {
            $fir_sol_direccion_domicilio = $parametros['txt_direccion_domicilio'];
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
}
