<?php
require_once(dirname(__DIR__,1) . '/lib/TCPDF/tcpdf.php');

// Primero crear una clase que extienda TCPDF para el header y footer personalizados
class PDFConHeader extends TCPDF 
{
    private $datosProveedor;

    function llenarDatosProveedor($datos)
    {
        $this->datosProveedor = $datos;
    }
    
    public function Header() {
       // Crear cuadro con diferentes bordes
        // $this->SetFillColor(255, 255, 255);
        // $this->SetDrawColor(255, 0, 0); // Borde rojo
        // $this->Cell(100, 20, 'Cuadro con todos los bordes', 1, 1, 'C', true);
        // $this->Ln(5);


    }
    
    function Footer() {
        // Posición a 1.5 cm del final
        // $this->SetY(-15);
        
        // // Línea decorativa
        // $this->SetDrawColor(51, 102, 153);
        // $this->SetLineWidth(0.3);
        // $this->Line(10, $this->GetY(), 200, $this->GetY());
        
        // // Texto del pie de página
        // $this->SetY(-12);
        // $this->SetFont('helvetica', 'I', 8);
        // $this->SetTextColor(128, 128, 128);
        // $this->Cell(0, 5, 'Documento generado electrónicamente - ' . date('Y') . ' Mi Empresa S.A.', 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }

    function generarCeros($numero, $tamaño=null)
    {
       //obtengop el largo del numero
       $largo_numero = strlen($numero);
       //especifico el largo maximo de la cadena
       if($tamaño==null)
       {
          $largo_maximo = 7;
       }
       else
       {
         $largo_maximo = $tamaño;
       }
       //tomo la cantidad de ceros a agregar
       $agregar = $largo_maximo - $largo_numero;
       //agrego los ceros
       for($i =0; $i<$agregar; $i++){
         $numero = "0".$numero;
       }
       //retorno el valor con ceros
       return $numero;
    }

    function cabeceraComprobante($datos,$datosFactura)
    {
       // Guardar posición original
        $data = $datos[0];
        $factura = $datosFactura[0];

        // print_r($datos);
        // print_r($factura);die();
        $x = 10;
        $y = $this->GetY();
        $this->SetXY($x, $y);

        $conta = 'NO';
        if($data['Obligado_conta']==1){$conta='SI';}
        $ambiente = 'PRUEBAS';
        if($data['Ambiente']==2){$ambiente = 'PRODUCCION';}
        $barcode = '1234567890098765432123456789098765';
        if($factura['Autorizacion']!='') {$barcode = $factura['Autorizacion'];}

        $tbl = '
        <table>
            <tr>
                <td height="45">imagen</td>
                <td rowspan="2">
                    <table border="1" width="260">
                        <tr>
                            <td>
                                <table>
                                    <tr><td colspan="2"></td></tr>
                                    <tr><td colspan="2"><b>CI/RUC:</b>'.$data['Ruc'].'</td></tr>
                                    <tr><td width="180"><b>FACTURA:</b></td><td>'.$factura['serie'].'-'.$this->generarCeros($factura['num_factura'],6).'</td></tr>
                                    <tr><td width="180"><b>FECHA Y HORA DE AUTORIZACION:</b></td><td>'.$factura['fecha'].'</td></tr>
                                    <tr><td width="180"><b>AMBIENTE:</b></td><td>'.$ambiente.'</td></tr>
                                    <tr><td width="180"><b>EMISION:</b></td><td>NORMAL</td></tr>
                                    <tr><td colspan="2"></td></tr>
                                    <tr><td colspan="2">NUMERO DE AUTORIZACION Y CLAVE DE ACCESO</td></tr>
                                    <tr><td colspan="2" height="50"></td></tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table border="1" width="240">
                        <tr>
                            <td>
                                <table>';
                                if($data['Razon_Social']!=$data['Nombre_Comercial'])
                                {
                                    $tbl.='<tr><td colspan="2"> '.$data['Razon_Social'].'</td></tr>';
                                    $tbl.='<tr><td colspan="2"> '.$data['Nombre_Comercial'].'</td></tr>';
                                }else
                                {                  
                                    $tbl.='<tr><td colspan="2"> </td></tr>';
                                    $tbl.='<tr><td colspan="2"> '.$data['Razon_Social'].'</td></tr>';
                                }
                                    

                                $tbl.='
                                <tr><td colspan="2"><b> Direccion Matriz</b></td></tr>
                                <tr><td colspan="2"> '.$data["direccion"].'</td></tr>
                                <tr><td colspan="2"><b> Telefono:</b> '.$data["telefono"].'</td></tr>
                                <tr><td colspan="2"><b> Email:</b>'.$data["Email"].'</td></tr>
                                <tr><td width="200"><b> Contribuyente especial Nro:</b></td><td>'.$data["Contribuyente_esp"].'</td></tr>
                                <tr><td width="210"><b> OBLIGADO A LLEVAR CONTABILIDAD:</b></td><td>'.$conta.'</td></tr>
                                <tr><td colspan="2"><b> ****'.utf8_encode($data["rimpe"]).'*****</b></td></tr>

                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
                <td>
                    
                </td>
            </tr>
        </table';

        // print_r($tbl);die();

        $this->SetFont('helvetica','', 8);
        $this->writeHTML($tbl, true, false, false, false, '');

        $style = array(
            'position' => '',
            'align' => 'C',
            'stretch' => false,
            'fitwidth' => true,
            'cellfitalign' => '',
            'border' => false,
            'hpadding' => 'auto',
            'vpadding' => 'auto',
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255),
            'text' => true,
            'font' => 'helvetica',
            'fontsize' => 8,
            'stretchtext' => 4
        );
        $this->write1DBarcode($barcode, 'C128', 104,$this->GETY()-18, '', 18, 0.4, $style, 'N');

    }

    function clienteComprobante($datos)
    {
      
        $data = $datos[0];
        // print_r($datos);die();
        $tbl = '
        <table>
            <tr>
                <td>
                    <table border="1" width="532">
                        <tr>
                            <td>
                                <table>
                                    <tr><td width="150"><b>Razon Social / Nombre y Apellido:</b></td><td>'.$data['nombre'].'</td></tr>
                                    <tr><td width="80"><b>Telefono:</b></td><td>'.$data['telefono'].'</td></tr>
                                    <tr><td><b>Email:</b></td><td>'.$data['mail'].' </td></tr>
                                    <tr><td width="100"><b>Identificacion (RUC/C.C):</b></td><td>'.$data['ci_ruc'].'</td></tr>
                                    <tr><td width="80"><b>Fecha Emision:</b></td><td>'.$data['fecha'].'</td></tr>
                                    <tr><td><b>Direccion:</b></td><td>'.$data["direccion"].'</td></tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table';

        // print_r($tbl);die();

        $this->SetFont('helvetica','', 8);
        $this->writeHTML($tbl, true, false, false, false, '');


    }
}

class PDFelectronicos
{
    private $pdf;
    private $conn;
    private $header_cuerpo;
    private $fechafin;
    private $fechaini;
    private $sizetable;

    function __construct()
    {
        // Usar la clase personalizada PDFConHeader en lugar de TCPDF directamente
        $this->pdf = new PDFConHeader('P', 'mm', 'A4', true, 'UTF-8', false);
        $this->fechafin = '';
        $this->fechaini = '';
        $this->sizetable = '12';
        
    }

    function FacturaElectronica($datos,$datosFactura=false,$lineas=false)
    {
        // Configurar información del documento
        $this->pdf->SetTitle('Documento con Header Personalizado');
        $this->pdf->SetSubject('Ejemplo de PDF con TCPDF');
        $this->pdf->SetKeywords('TCPDF, PDF, header, footer, ejemplo');

        // Agregar páginas
        $this->pdf->AddPage();

        $this->pdf->cabeceraComprobante($datos,$datosFactura);
        $this->pdf->Ln(2);
        $this->pdf->clienteComprobante($datosFactura);
        $this->pdf->Ln(1);

        // print_r($lineas);die();

        $total_Factura = 0;

        $sub=0;
        $total=0;
        $iva = 0;
        $des = 0;       
        $con_iva = 0;
        $sin_iva = 0;

         $tbl = '<table border="1" width="530">
                    <tr>
                        <td>
                            Cod.Principal  
                        </td>
                        <td  width="50">
                            Cant
                        </td>
                        <td width="245">
                            Descripcion 
                        </td>
                        <td  width="50">
                            Precio Uni. 
                        </td>
                        <td  width="50">
                            Descuento
                        </td>
                        <td  width="50">
                            Total
                        </td>
                    </tr>';
                    // print_r($lineas);die();
                    foreach ($lineas as $key => $value) {
                        // print_r($value);
                        $tbl.='<tr> 
                                <td> '.$value['referencia'].' 
                                </td>
                                <td> '.$value['cantidad'].'
                                </td>
                                <td> '.$value['producto'].'
                                </td>
                                <td align="R">'.number_format($value['precio_uni'],2,',').'
                                </td>
                                <td align="R">'.number_format($value['descuento'],2,',').'
                                </td>
                                <td align="R">'.number_format($value['total'],2,',').'
                                </td>
                            </tr>';
                        $total_Factura= $total_Factura+$value['total'];

                        $sub = $sub+$value['subtotal'];
                        $iva+= $value['iva'];
                        $des+= $value['descuento'];
                        if($value['porc_iva']==0)
                        {
                            $sin_iva+= $value['subtotal'];
                        }else if($value['porc_iva']=='0.15')
                        {
                            $con_iva+= $value['subtotal'];
                        }
                    }
                $tbl.='</table>';

        // print_r($tbl);die();
        $this->pdf->SetX($this->pdf->GETX()+1);
        $this->pdf->SetFont('helvetica','', 8);
        $this->pdf->writeHTML($tbl, true, false, false, false, '');

        $this->pdf->Ln(1);

        // print_r($datosFactura);die();

        $tbl = '
        <table>
            <tr>
                <td width="330">
                    <table border="1">
                        <tr align="C"><td width="260">FORMA DE PAGO </td><td align="C" width="60">Valor</td></tr>
                        <tr><td> '.$datosFactura[0]['tipo_pago_des'].'</td><td align="R">'.number_format($total_Factura,2,'.','').'</td></tr>
                    </table>
                    <table>
                        <tr><td></td></tr>
                    </table>
                    <table border="1">
                        <tr><td width="320"><b> Datos adicionales</b></td></tr>
                        <tr><td></td></tr>
                    </table>
                </td>
                <td width="210">
                    <table border="1">
                        <tr><td width="130"><b> SUBTOTAL '.$datos[0]['Valor_iva'].'%</b></td><td width="70" align="R">'.number_format($con_iva,2,'.','').' </td></tr>
                        <tr><td><b> SUB TOTAL 0%</b></td><td align="R">'.number_format($sin_iva,2,'.','').' </td></tr>
                        <tr><td><b> SUBTOTAL SIN IMPUESTOS</b></td><td align="R">'.number_format($con_iva+$sin_iva,2,'.','').' </td></tr>
                        <tr><td><b> DESCUENTOS </b></td><td align="R">'.number_format($des,2,'.','').' </td></tr>
                        <tr><td><b> IVA '.$datos[0]['Valor_iva'].'% </b></td><td align="R">'.number_format($iva,2,'.','').' </td></tr>
                        <tr><td><b> VALOR TOTAL</b></td><td align="R">'.number_format($total_Factura,2,'.','').' </td></tr>
                    </table>
                </td>
            </tr>
        </table';

        // print_r($tbl);die();

        $this->pdf->SetFont('helvetica','', 8);
        $this->pdf->writeHTML($tbl, true, false, false, false, '');

       


        // Salida del PDF
        $this->pdf->Output('documento_con_header.pdf', 'I');
    }
  
    function FacturaElectronica2($tablaHTML,$tablaHTML2,$tablaHTML3,$tablaHTML4,$tablaHTML5,$tablaHTML6,$tablaHTML7,$logo,$barcode=false,$mostrar = true,$descargar=false,$numfactura)
    {
        if($barcode==false)
        {
            $barcode = '123456789123456789123456789'.$numfactura;
        }

        $this->pdf->AddPage();

        $src = $logo; 
        if(!file_exists($src))
        {           
            $src = dirname(__DIR__,2).'/img/empresa/logo.png'; 
        } else {
            $this->pdf->Image($src,10,10,45,20);
        }

        $pos_x = 100;
        $this->pdf->SetXY($pos_x,20);
        $sizetable = 8;

        $this->pdf->SetFont('Arial','',$sizetable);
        foreach ($tablaHTML as $key => $value){
            $altoRow = 10;
            if(isset($value['Size']))
            {
                $sizetable = $value['Size'];
            }
            if(isset($value['estilo']) && $value['estilo']!='')
            {
                $this->pdf->SetFont('Arial',$value['estilo'],$sizetable);
                $estiloRow = $value['estilo'];
            } else {
                $this->pdf->SetFont('Arial','',$sizetable);
                $estiloRow ='';
            }
            if(isset($value['borde']) && $value['borde']!='0')
            {
                $borde=$value['borde'];
            } else {
                $borde =0;
            }
            if(isset($value['altoRow']))
            {
                $altoRow = $value['altoRow'];
            }

            $this->pdf->SetWidths($value['medidas']);
            $this->pdf->SetAligns($value['alineado']);
            $arr= $value['datos'];
            $this->pdf->Row($arr,$altoRow,$borde,$estiloRow);   
            $this->pdf->SetXY($this->pdf->GETX()+$pos_x-10,$this->pdf->GETY());
        }
        $this->pdf->i25($this->pdf->GETX()+3,$this->pdf->GETY(),$barcode,0.60,10);
        $y_fin = $this->pdf->GETY();           
        $this->pdf->RoundedRect($pos_x, 20,100,$y_fin,2, '1234');

        // Continuar con el resto del código de FacturaElectronica2...
        // (Aquí va todo el código que ya tenías, solo asegúrate de no volver a crear el PDF)

        $pos2_x = 10;
        $pos2_y = 36; 
        $cell_y = 0;
        $this->pdf->SetXY($pos2_x,$pos2_y);
        $this->pdf->SetFont('Arial','',$sizetable);
        foreach ($tablaHTML2 as $key => $value){
            $altoRow = 10;
            if(isset($value['Size']))
            {
                $sizetable = $value['Size'];
            }
            if(isset($value['estilo']) && $value['estilo']!='')
            {
                $this->pdf->SetFont('Arial',$value['estilo'],$sizetable);
                $estiloRow = $value['estilo'];
            } else {
                $this->pdf->SetFont('Arial','',$sizetable);
                $estiloRow ='';
            }
            if(isset($value['borde']) && $value['borde']!='0')
            {
                $borde=$value['borde'];
            } else {
                $borde =0;
            }
            if(isset($value['altoRow']))
            {
                $altoRow = $value['altoRow'];
            }

            $this->pdf->SetWidths($value['medidas']);
            $this->pdf->SetAligns($value['alineado']);
            $arr= $value['datos'];
            $this->pdf->Row($arr,$altoRow,$borde,$estiloRow);   
            $this->pdf->SetXY($this->pdf->GETX()+$pos2_x-10,$this->pdf->GETY());
            $cell_y = $this->pdf->GETY();
        }
        $this->pdf->RoundedRect($pos2_x, $pos2_y,86,$cell_y-$pos2_y,2, '1234');

        // Continuar con el resto de tablas...
        // Aquí debes continuar con el código de tablaHTML3, tablaHTML4, etc.
        // Ya que es muy extenso, mantén el resto de tu código igual
        // solo asegúrate de que todas las llamadas usen $this->pdf

        if($mostrar==true)
        {
            $this->pdf->Output();
        } else {
            $this->pdf->Output('F',dirname(__DIR__,2).'/TEMP/'.$barcode.'.pdf');
        }

        if($descargar)
        {
            return $barcode.'.pdf';
        }
    }
}