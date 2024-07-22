<?php

if(!class_exists('PDF_MC_Table'))
{
	require('PDF_MC_Table.php');
}
if (!class_exists('FPDF')) {
    //$mi_clase = new MiClase();
   require('fpdf.php');
}


//include(dirname(__DIR__,2)."/php/db/db.php");
//echo dirname(__DIR__,1);

/**
 * 
 */


class cabecera_pdf
{
	private $pdf;
	private $conn;
	private $header_cuerpo;

	function __construct()
	{
		$this->pdf = new PDFv();
		$this->pdftable = new PDF_MC();
		$this->fechafin='';
		$this->fechaini='';
		$this->sizetable ='12';
		
	}


	function cedula_reporte($titulo,$tablaHTML,$contenido=false,$image=false,$fechaini,$fechafin,$sizetable,$mostrar=false,$sal_hea_body=30,$orientacion='P')
	{	

	    $this->pdftable->fechaini = $fechaini; 
	    $this->pdftable->fechafin = $fechafin; 
	    $this->pdftable->titulo = $titulo;
	    $this->pdftable->salto_header_cuerpo = $sal_hea_body;
	    $this->pdftable->orientacion = $orientacion;
	    $estiloRow='';
		 $this->pdftable->AddPage($orientacion);
		 if($image)
		 {
		  foreach ($image as $key => $value) {
		  	//print_r($value);		 	
		 	 	 $this->pdftable->Image($value['url'], $value['x'],$value['y'],$value['width'],$value['height']);
		 	 	 $this->pdftable->Ln(5);		 	 
		 }
		}

		if($contenido)
		{
		 foreach ($contenido as $key => $value) {
		 	 if($value['tipo'] == 'texto' && $value['posicion']=='top-tabla')
		 	 {
		 	 	//print_r($value);
		 	 	$this->pdftable->SetFont('Arial','',11);
		 	 	$this->pdftable->MultiCell(0,3,$value['valor']);
		 	 	$this->pdftable->Ln(5);

		 	 }else if($value['tipo'] == 'titulo' && $value['posicion']=='top-tabla')
		 	 {
		 	 	$this->pdftable->SetFont('Arial','',18);
		 	 	$this->pdftable->Cell(0,3,$value['valor'],0,0,'C');
		 	 	$this->pdftable->Ln(5);

		 	 }
		 }
        }
                $this->pdftable->SetFont('Arial','',$sizetable);
		    foreach ($tablaHTML as $key => $value){
		    	if(isset($value['estilo']) && $value['estilo']!='')
		    	{
		    		$this->pdftable->SetFont('Arial',$value['estilo'],$sizetable);
		    		$estiloRow = $value['estilo'];
		    	}else
		    	{
		    		$this->pdftable->SetFont('Arial','',$sizetable);
		    		$estiloRow ='';
		    	}
		    	if(isset($value['borde']) && $value['borde']!='0')
		    	{
		    		$borde=$value['borde'];
		    	}else
		    	{
		    		$borde =0;
		    	}

		    //print_r($value['medida']);
		       $this->pdftable->SetWidths($value['medidas']);
			   $this->pdftable->SetAligns($value['alineado']);
			   //print_r($value['datos']);
			   $arr= $value['datos'];
			   $this->pdftable->Row($arr,4,$borde,$estiloRow);		    	
		    }
		

		  if($contenido)
		  {
		 foreach ($contenido as $key => $value) {
		 	 if($value['tipo'] == 'texto' && $value['posicion']=='button-tabla')
		 	 {
		 	 	$this->pdftable->SetFont('Arial','',11);
		 	 	$this->pdftable->MultiCell(0,3,$value['valor']);
		 	 	$this->pdftable->Ln(5);
		 	 }else if($value['tipo'] == 'titulo' && $value['posicion']=='button-tabla')
		 	 {
		 	 	$this->pdftable->SetFont('Arial','',18);
		 	 	$this->pdftable->Cell(0,3,$value['valor'],0,0,'C');
		 	 	$this->pdftable->Ln(5);
		 	 }
		 }
		}
		//echo $titulo;
		//die();
		 if($mostrar==true)
	       {
		    $this->pdftable->Output();

	       }else
	       {
		     $this->pdftable->Output('D',$titulo.'.pdf',false);

	      }

	}


	function cedula_reporte_lista($titulo,$tablaHTML,$contenido=false,$image=false,$fechaini,$fechafin,$sizetable,$mostrar=false,$sal_hea_body=30,$orientacion='P')
	{	

	    $this->pdftable->fechaini = $fechaini; 
	    $this->pdftable->fechafin = $fechafin; 
	    $this->pdftable->titulo = $titulo;
	    $this->pdftable->salto_header_cuerpo = $sal_hea_body;
	    $this->pdftable->orientacion = $orientacion;
	    	$estiloRow='';
		 $this->pdftable->AddPage($orientacion);
		 if($image)
		 {
		  foreach ($image as $key => $value) {
		  	//print_r($value);		 	
		 	 	 $this->pdftable->Image($value['url'], $value['x'],$value['y'],$value['width'],$value['height']);
		 	 	 $this->pdftable->Ln(5);		 	 
		 }
		}

		if($contenido)
		{
		 foreach ($contenido as $key => $value) {
		 	 if($value['tipo'] == 'texto' && $value['posicion']=='top-tabla')
		 	 {
		 	 	//print_r($value);
		 	 	$this->pdftable->SetFont('Arial','',11);
		 	 	$this->pdftable->MultiCell(0,3,$value['valor']);
		 	 	$this->pdftable->Ln(5);

		 	 }else if($value['tipo'] == 'titulo' && $value['posicion']=='top-tabla')
		 	 {
		 	 	$this->pdftable->SetFont('Arial','',18);
		 	 	$this->pdftable->Cell(0,3,$value['valor'],0,0,'C');
		 	 	$this->pdftable->Ln(5);

		 	 }
		 }
        }
                $this->pdftable->SetFont('Arial','',$sizetable);
		    foreach ($tablaHTML as $key => $value){
		    	if(isset($value['estilo']) && $value['estilo']!='')
		    	{
		    		$this->pdftable->SetFont('Arial',$value['estilo'],$sizetable);
		    		$estiloRow = $value['estilo'];
		    	}else
		    	{
		    		$this->pdftable->SetFont('Arial','',$sizetable);
		    		$estiloRow ='';
		    	}
		    	if(isset($value['borde']) && $value['borde']!='0')
		    	{
		    		$borde=$value['borde'];
		    	}else
		    	{
		    		$borde =0;
		    	}

		    //print_r($value['medida']);
		       $this->pdftable->SetWidths($value['medidas']);
			   $this->pdftable->SetAligns($value['alineado']);
			   //print_r($value['datos']);
			   $arr= $value['datos'];
			   $this->pdftable->Row($arr,4,$borde,$estiloRow);

			   // print_r($tablaHTML[$key+1]);die();
			   if(isset($tablaHTML[$key+1]['datos'][0]) && $tablaHTML[$key+1]['datos'][0]=='PONTIFICIA UNIVERSIDAD CATOLICA DEL ECUADOR')
			   {
			   	$this->pdftable->AddPage($orientacion);
			   	if($image)
				 {
				  foreach ($image as $key => $value) {
				  	//print_r($value);		 	
				 	 	 $this->pdftable->Image($value['url'], $value['x'],$value['y'],$value['width'],$value['height']);
				 	 	 $this->pdftable->Ln(5);		 	 
				 }
				}
			   }
		    }
		

		  if($contenido)
		  {
		 foreach ($contenido as $key => $value) {
		 	 if($value['tipo'] == 'texto' && $value['posicion']=='button-tabla')
		 	 {
		 	 	$this->pdftable->SetFont('Arial','',11);
		 	 	$this->pdftable->MultiCell(0,3,$value['valor']);
		 	 	$this->pdftable->Ln(5);
		 	 }else if($value['tipo'] == 'titulo' && $value['posicion']=='button-tabla')
		 	 {
		 	 	$this->pdftable->SetFont('Arial','',18);
		 	 	$this->pdftable->Cell(0,3,$value['valor'],0,0,'C');
		 	 	$this->pdftable->Ln(5);
		 	 }
		 }
		}

		 


		 if($mostrar==true)
	       {
		    $this->pdftable->Output();

	       }else
	       {
		     $this->pdftable->Output('D',$titulo.'.pdf',false);

	      }

	}

	function solicitud_acta($img_header=false,$img_foot=false,$titulo,$cuerpo=false,$fechaini=false,$fechafin=false,$sizetable,$mostrar=false,$sal_hea_body=30,$orientacion='P',$descargar=0)
	{	

	    $this->pdftable->fechaini = $fechaini; 
	    $this->pdftable->fechafin = $fechafin; 
	    $this->pdftable->titulo = $titulo;
	    $this->pdftable->salto_header_cuerpo = $sal_hea_body;
	    $this->pdftable->orientacion = $orientacion;
	    	$estiloRow='';
		 $this->pdftable->AddPage($orientacion);
		 $url ="../img/cabecera_puce_acta.png";$H_x = 0;$H_y = 0;$H_w = 220;$H_h = 30;
		 $url2 ="../img/footer_puce_actaH.png";$F_x = 10;$F_y = 268;$F_w = 195;$F_h = 25;

		 if($img_header)
		 {
	 	     foreach ($img_header as $key => $value) 
	 	      {	 	
	 	        $this->pdftable->Image($value['url'], $value['x'],$value['y'],$value['w'],$value['h']);
	 	   	$this->pdftable->Ln(5);		 	 
	 	      }

		 }else
		 {
		      $img_header[0] = array('url'=>$url, 'x'=>$H_x,'y'=>$H_y,'w'=>$H_w,'h'=>$H_h);
		      // print_r($img_header);die();
		      if(file_exists($url))
		      {
			   foreach ($img_header as $key => $value) 
		 	   {	 	
		 	     $this->pdftable->Image($value['url'], $value['x'],$value['y'],$value['w'],$value['h']);
		 	     $this->pdftable->Ln(5);		 	 
		 	   }
	 	      }

		 }


		$this->pdftable->Ln($sal_hea_body);

		if($titulo)
		{
		 foreach ($titulo as $key => $value) {
		     $this->pdftable->SetFont('Arial','',$value['size']);
	 	     $this->pdftable->MultiCell(0,3,$value['dato'],0,$value['alineado']);
	 	     $this->pdftable->Ln($value['salto']);
		 }
		}

		if($cuerpo)
		{
		 foreach ($cuerpo as $key => $value) {

		 	// print_r($value);die();
		 	$borde =0;$estilo = '';$size = 11;$salto = 4;
		 	if(isset($value['estilo']) && $value['estilo']!=''){$estilo = $value['estilo']; }
		 	if(isset($value['size']) && $value['size']!=''){ $size = $value['size']; }
		    	if(isset($value['borde']) && $value['borde']!='0'){$borde=$value['borde'];}
		    	if(isset($value['salto']) && $value['salto']!='0'){$salto=$value['salto'];}
		    	
		    	

		     $this->pdftable->SetFont('Arial',$estilo,$size);
    	             $this->pdftable->SetWidths($value['medidas']);
		     $this->pdftable->SetAligns($value['alineado']);
		     $this->pdftable->Row($value['datos'],4,$borde,$estilo);
	 	     // $this->pdftable->MultiCell(0,3,$value['dato'],0,$value['alineado']);
	 	     if(isset($value['salto'])){
	 		     $this->pdftable->Ln($salto);
	 	     }
		 }
		}
		
               
		 if($img_foot)
		 {
	 	     foreach ($img_foot as $key => $value) 
	 	      {	 	
	 	        $this->pdftable->Image($value['url'], $value['x'],$value['y'],$value['w'],$value['h']);
	 	   	$this->pdftable->Ln(5);		 	 
	 	      }

		 }else
		 {
		      $img_foot[0] = array('url'=>$url2, 'x'=>$F_x,'y'=>$F_y,'w'=>$F_w,'h'=>$F_h);
		      // print_r($img_header);die();
		      if(file_exists($url))
		      {
			   foreach ($img_foot as $key => $value) 
		 	   {	 	
		 	     $this->pdftable->Image($value['url'], $value['x'],$value['y'],$value['w'],$value['h']);
		 	     $this->pdftable->Ln(5);		 	 
		 	   }
	 	      }

		 }


	      if($mostrar==1)
	       {
		    $this->pdftable->Output();

	       }else{
	       	  if($descargar)
	       	  {
		     $this->pdftable->Output('D',str_replace(' ','_',$titulo[0]['dato']).'.pdf',false);
		  }else{

		     $this->pdftable->Output('F',dirname(__DIR__,2).'/TEMP/'.str_replace(' ','_',$titulo[0]['dato']).'.pdf');
		  }
	       }

	}

	function cabecera_reporte($titulo,$tablaHTML,$contenido=false,$image=false,$fechaini,$fechafin,$sizetable,$mostrar=false,$sal_hea_body=30,$orientacion='P')
	{	

	    $this->pdf->fechaini = $fechaini; 
	    $this->pdf->fechafin = $fechafin; 
	    $this->pdf->titulo = $titulo;
	    $this->pdf->salto_header_cuerpo = $sal_hea_body;
	    $this->pdf->orientacion = $orientacion;
		$this->pdf->AddPage();
		 if($image)
		 {
		  foreach ($image as $key => $value) {
		  	//print_r($value);		 	
		 	 	 $this->pdf->Image($value['url'], $value['x'],$value['y'],$value['width'],$value['height']);
		 	 	 $this->pdf->Ln(5);		 	 
		 }
		}

		if($contenido)
		{
		 foreach ($contenido as $key => $value) {
		 	 if($value['tipo'] == 'texto' && $value['posicion']=='top-tabla')
		 	 {
		 	 	//print_r($value);
		 	 	$this->pdf->SetFont('Arial','',11);
		 	 	$this->pdf->MultiCell(0,3,$value['valor']);
		 	 	$this->pdf->Ln(5);

		 	 }else if($value['tipo'] == 'titulo' && $value['posicion']=='top-tabla')
		 	 {
		 	 	$this->pdf->SetFont('Arial','',18);
		 	 	$this->pdf->Cell(0,3,$value['valor'],0,0,'C');
		 	 	$this->pdf->Ln(5);

		 	 }
		 }
        }
		 $this->pdf->SetFont('Arial','',$sizetable);
		 $this->pdf->WriteHTML($tablaHTML);

		  if($contenido)
		  {
		 foreach ($contenido as $key => $value) {
		 	 if($value['tipo'] == 'texto' && $value['posicion']=='button-tabla')
		 	 {
		 	 	$this->pdf->SetFont('Arial','',11);
		 	 	$this->pdf->MultiCell(0,3,$value['valor']);
		 	 	$this->pdf->Ln(5);
		 	 }else if($value['tipo'] == 'titulo' && $value['posicion']=='button-tabla')
		 	 {
		 	 	$this->pdf->SetFont('Arial','',18);
		 	 	$this->pdf->Cell(0,3,$value['valor'],0,0,'C');
		 	 	$this->pdf->Ln(5);
		 	 }
		 }
		}
		//echo $titulo;
		//die();
		 if($mostrar==true)
	       {
		    $this->pdf->Output();

	       }else
	       {
		     $this->pdf->Output('D',$titulo.'.pdf',false);

	      }

	}
 
 function cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,$fechaini,$fechafin,$sizetable,$mostrar=false,$sal_hea_body=30,$orientacion='P')
	{	

	    $this->pdftable->fechaini = $fechaini; 
	    $this->pdftable->fechafin = $fechafin; 
	    $this->pdftable->titulo = $titulo;
	    $this->pdftable->salto_header_cuerpo = $sal_hea_body;
	    $this->pdftable->orientacion = $orientacion;
	    $estiloRow='';
		 $this->pdftable->AddPage($orientacion);
		 if($image)
		 {
		  foreach ($image as $key => $value) {
		  	//print_r($value);		 	
		 	 	 $this->pdftable->Image($value['url'], $value['x'],$value['y'],$value['width'],$value['height']);
		 	 	 $this->pdftable->Ln(5);		 	 
		 }
		}

		// print_r($this->pdftable->GetY());die();

		if($contenido)
		{
		 foreach ($contenido as $key => $value) {
		 	 if($value['tipo'] == 'texto' && $value['posicion']=='top-tabla')
		 	 {
		 	 	//print_r($value);
		 	 	$this->pdftable->SetFont('Arial','',11);
		 	 	$this->pdftable->MultiCell(0,3,$value['valor']);
		 	 	$this->pdftable->Ln(5);

		 	 }else if($value['tipo'] == 'titulo' && $value['posicion']=='top-tabla')
		 	 {
		 	 	$this->pdftable->SetFont('Arial','',18);
		 	 	$this->pdftable->Cell(0,3,$value['valor'],0,0,'C');
		 	 	$this->pdftable->Ln(5);

		 	 }
		 }
        }
                $this->pdftable->SetFont('Arial','',$sizetable);
		    foreach ($tablaHTML as $key => $value){
		    	$tama = 9;
		    	$esti = '';

		    	// if(isset($value['estilo']) && $value['estilo']!='')
		    	// {
		    	// 	$this->pdftable->SetFont('Arial',$value['estilo'],$sizetable);
		    	// 	$estiloRow = $value['estilo'];
		    	// }else
		    	// {
		    	// 	$this->pdftable->SetFont('Arial','',$sizetable);
		    	// 	$estiloRow ='';
		    	// }

		    	if(isset($value['estilo']) && $value['estilo']!='')
		    	{
		    		$esti = $value['estilo'];
		    	}
		    	if(isset($value['size']) && $value['size']!='')
		    	{
		    		$tama = $value['size'];
		    	}

		    	$this->pdftable->SetFont('Arial',$esti,$tama);
		    	$estiloRow = $esti;



		    	if(isset($value['borde']) && $value['borde']!='0')
		    	{
		    		$borde=$value['borde'];
		    	}else
		    	{
		    		$borde =0;
		    	}

		    //print_r($value['medida']);
		       $this->pdftable->SetWidths($value['medidas']);
			   $this->pdftable->SetAligns($value['alineado']);
			   //print_r($value['datos']);
			   $arr= $value['datos'];
			   $this->pdftable->Row($arr,4,$borde,$estiloRow);		    	
		    }
		

		  if($contenido)
		  {
		 foreach ($contenido as $key => $value) {
		 	 if($value['tipo'] == 'texto' && $value['posicion']=='button-tabla')
		 	 {
		 	 	$this->pdftable->SetFont('Arial','',11);
		 	 	$this->pdftable->MultiCell(0,3,$value['valor']);
		 	 	$this->pdftable->Ln(5);
		 	 }else if($value['tipo'] == 'titulo' && $value['posicion']=='button-tabla')
		 	 {
		 	 	$this->pdftable->SetFont('Arial','',18);
		 	 	$this->pdftable->Cell(0,3,$value['valor'],0,0,'C');
		 	 	$this->pdftable->Ln(5);
		 	 }
		 }
		}
		//echo $titulo;
		//die();
		 if($mostrar==true)
	       {
		    $this->pdftable->Output();

	       }else
	       {
		     $this->pdftable->Output('D',$titulo.'.pdf',false);

	      }

	}
  }


class PDFv extends FPDF
{

	public $fechaini;
	public $fechafin;
	public $titulo;
	public $salto_header_cuerpo;
	public $orientacion;

    function Header()
    {
   
  // print($_SESSION['INGRESO']['Logo_Tipo']);
    	$src='';
		if(isset($_SESSION['INGRESO']['Logo_Tipo']))
		   {
		   	$logo=$_SESSION['INGRESO']['Logo_Tipo'];
		   	//si es jpg
		   	$src = dirname(__DIR__,2).'/img/logotipos/'.$logo.'.jpg'; 
		   	if(!file_exists($src))
		   	{
		   		$src = dirname(__DIR__,2).'/img/logotipos/'.$logo.'.gif'; 
		   		if(!file_exists($src))
		   		{
		   			$src = dirname(__DIR__,2).'/img/logotipos/'.$logo.'.png'; 
		   			if(!file_exists($src))
		   			{
		   				$logo="diskcover";
		                $src= dirname(__DIR__,2).'/img/logotipos/'.$logo.'.gif';
		   			}

		   		}

		   	}
		  }

         $this->Image($src,10,3,35,20); 
         $this->SetFont('Times','b',12);
         $this->SetXY(10,10);

		$this->Cell(0,3,$_SESSION['INGRESO']['Nombre_Comercial'],0,0,'C');
		$this->SetFont('Times','I',13);
		$this->Ln(5);
		$this->Cell(0,3,strtoupper($_SESSION['INGRESO']['noempr']),0,0,'C');				
		$this->Ln(5);


		$this->SetFont('Times','I',11);
		$this->Cell(0,3,ucfirst(strtolower($_SESSION['INGRESO']['Direccion'].' Telefono: '.$_SESSION['INGRESO']['Telefono1'])),0,0,'C');

		$this->Ln(5);		
		$this->SetFont('Arial','b',12);

		$this->Cell(0,3,$this->titulo,0,0,'C');
		
		if($this->fechaini !='' && $this->fechaini != null  && $this->fechafin !='' && $this->fechafin != null){
		   $this->SetFont('Arial','b',10);
		   $this->Ln(5);		
		   $this->Cell(0,3,'DESDE: '.$this->fechaini.' HASTA:'.$this->fechafin,0,0,'C');
		   $this->Ln(10);	
		}

		if($this->orientacion == 'P')
		{
		  //inicio--------logo superior derecho//		
        $this->Image(dirname(__DIR__,2).'/img/logotipos/diskcov2.gif',182,3,20,8); 
		$this->Ln(2);		

		 $this->SetFont('Arial','b',8);
        // $this->pdf->SetXY(10,10);
		$this->SetXY(155,5);
        $this->Cell(9,2,'Hora: ',0,0,'L');
		$this->SetFont('Arial','',8);
        $this->Cell(0,2,date('h:i:s A'),0,0,'L');
		$this->Ln(2);		
		$this->SetFont('Arial','b',8);
		$this->SetXY(155,8);
        $this->Cell(17,2,'Pagina No.  ',0,0,'L');
		$this->SetFont('Arial','',8);
        $this->Cell(0,2,$this->PageNo(),0,0,'L');
		$this->Ln(2);
		$this->SetXY(155,11);
		$this->SetFont('Arial','b',8);		
        $this->Cell(10,2,'Fecha: ',0,0,'L');
		$this->SetFont('Arial','',8);
        $this->Cell(0,2,date("Y-m-d") ,0,0,'L');
		$this->Ln(2);
		$this->SetXY(155,14);
		$this->SetFont('Arial','b',8);	
        $this->Cell(12,2,'Usuario: ',0,0,'L');
		$this->SetFont('Arial','',8);	
        $this->Cell(0,2,$_SESSION['INGRESO']['Nombre_Completo'],0,0,'L');
		$this->Line(20, 35, 210-20, 35); 
        $this->Line(20, 36, 210-20, 36);
		$this->Ln($this->salto_header_cuerpo);
	}else
	{

		  //inicio--------logo superior derecho//		
        $this->Image(dirname(__DIR__,2).'/img/logotipos/diskcov2.gif',482,3,20,8); 
		$this->Ln(2);		

		 $this->SetFont('Arial','b',8);
        // $this->pdf->SetXY(10,10);
		$this->SetXY(255,5);
        $this->Cell(9,2,'Horas: ',0,0,'L');
		$this->SetFont('Arial','',8);
        $this->Cell(0,2,date('h:i:s A'),0,0,'L');
		$this->Ln(2);		
		$this->SetFont('Arial','b',8);
		$this->SetXY(255,8);
        $this->Cell(17,2,'Pagina No.  ',0,0,'L');
		$this->SetFont('Arial','',8);
        $this->Cell(0,2,$this->PageNo(),0,0,'L');
		$this->Ln(2);
		$this->SetXY(255,11);
		$this->SetFont('Arial','b',8);		
        $this->Cell(10,2,'Fecha: ',0,0,'L');
		$this->SetFont('Arial','',8);
        $this->Cell(0,2,date("Y-m-d") ,0,0,'L');
		$this->Ln(2);
		$this->SetXY(255,14);
		$this->SetFont('Arial','b',8);	
        $this->Cell(12,2,'Usuario: ',0,0,'L');
		$this->SetFont('Arial','',8);	
        $this->Cell(0,2,$_SESSION['INGRESO']['Nombre_Completo'],0,0,'L');
		$this->Line(20, 35, 300-20, 35); 
        $this->Line(20, 36, 300-20, 36);
		$this->Ln($this->salto_header_cuerpo);

	}

 }

}

class PDF_MC extends PDF_MC_Table
{

	public $fechaini;
	public $fechafin;
	public $titulo;
	public $salto_header_cuerpo;
	public $orientacion;

    function Header()
    {

    	$this->Ln($this->salto_header_cuerpo);
   
   // print($_SESSION['INGRESO']['Logo_Tipo']);
    	
			      // $this->SetTextColor(0,0,0);
		// if(isset($_SESSION['INGRESO']['Logo_Tipo']))
		  //  {
		  //  	$logo=$_SESSION['INGRESO']['Logo_Tipo'];
		  //  	//si es jpg
		  //  	$src = dirname(__DIR__,2).'/img/logotipos/'.$logo.'.jpg'; 
		  //  	if(!file_exists($src))
		  //  	{
		  //  		$src = dirname(__DIR__,2).'/img/logotipos/'.$logo.'.gif'; 
		  //  		if(!file_exists($src))
		  //  		{
		  //  			$src = dirname(__DIR__,2).'/img/logotipos/'.$logo.'.png'; 
		  //  			if(!file_exists($src))
		  //  			{
		  //  				$logo="diskcover_web";
		  //               $src= dirname(__DIR__,2).'/img/logotipos/'.$logo.'.gif';

		  //  			}

		  //  		}

		  //  	}
		  // }

  //       $this->Image($src,10,3,35,20); 
  //        $this->SetFont('Times','b',12);
  //        $this->SetXY(10,10);

		// $this->Cell(0,3,$_SESSION['INGRESO']['Nombre_Comercial'],0,0,'C');
		// $this->SetFont('Times','I',13);
		// $this->Ln(5);
		// $this->Cell(0,3,strtoupper($_SESSION['INGRESO']['noempr']),0,0,'C');				
		// $this->Ln(5);


		// $this->SetFont('Times','I',11);
		// $this->Cell(0,3,ucfirst(strtolower($_SESSION['INGRESO']['Direccion'].' Telefono: '.$_SESSION['INGRESO']['Telefono1'])),0,0,'C');

		// $this->Ln(5);		
		// $this->SetFont('Arial','b',12);

		// $this->Cell(0,3,$this->titulo,0,0,'C');
		
		// if($this->fechaini !='' && $this->fechaini != null  && $this->fechafin !='' && $this->fechafin != null){
		//    $this->SetFont('Arial','b',10);
		//    $this->Ln(5);		
		//    $this->Cell(0,3,'DESDE: '.$this->fechaini.' HASTA:'.$this->fechafin,0,0,'C');
		//    $this->Ln(10);	
		// }

	// 	if($this->orientacion == 'P')
	// 	{
	// 	  //inicio--------logo superior derecho//		
 //        $this->Image(dirname(__DIR__,2).'/img/logotipos/diskcov2.gif',182,3,20,8); 
	// 	$this->Ln(2);		

	// 	 $this->SetFont('Arial','b',8);
 //        // $this->pdf->SetXY(10,10);
	// 	$this->SetXY(155,5);
 //        $this->Cell(9,2,'Hora: ',0,0,'L');
	// 	$this->SetFont('Arial','',8);
 //        $this->Cell(0,2,date('h:i:s A'),0,0,'L');
	// 	$this->Ln(2);		
	// 	$this->SetFont('Arial','b',8);
	// 	$this->SetXY(155,8);
 //        $this->Cell(17,2,'Pagina No.  ',0,0,'L');
	// 	$this->SetFont('Arial','',8);
 //        $this->Cell(0,2,$this->PageNo(),0,0,'L');
	// 	$this->Ln(2);
	// 	$this->SetXY(155,11);
	// 	$this->SetFont('Arial','b',8);		
 //        $this->Cell(10,2,'Fecha: ',0,0,'L');
	// 	$this->SetFont('Arial','',8);
 //        $this->Cell(0,2,date("Y-m-d") ,0,0,'L');
	// 	$this->Ln(2);
	// 	$this->SetXY(155,14);
	// 	$this->SetFont('Arial','b',8);	
 //        $this->Cell(12,2,'Usuario: ',0,0,'L');
	// 	$this->SetFont('Arial','',8);	
 //        $this->Cell(0,2,$_SESSION['INGRESO']['Nombre_Completo'],0,0,'L');
	// 	$this->Line(20, 35, 210-20, 35); 
 //        $this->Line(20, 36, 210-20, 36);
	// 	$this->Ln($this->salto_header_cuerpo);
	// }else
	// {

	// 	  //inicio--------logo superior derecho//		
 //        $this->Image(dirname(__DIR__,2).'/img/logotipos/diskcov2.gif',270,3,20,8); 
	// 	$this->Ln(2);		

	// 	 $this->SetFont('Arial','b',8);
 //        // $this->pdf->SetXY(10,10);
	// 	$this->SetXY(240,5);
 //        $this->Cell(9,2,'Hora: ',0,0,'L');
	// 	$this->SetFont('Arial','',8);
 //        $this->Cell(0,2,date('h:i:s A'),0,0,'L');
	// 	$this->Ln(2);		
	// 	$this->SetFont('Arial','b',8);
	// 	$this->SetXY(240,8);
 //        $this->Cell(17,2,'Pagina No.  ',0,0,'L');
	// 	$this->SetFont('Arial','',8);
 //        $this->Cell(0,2,$this->PageNo(),0,0,'L');
	// 	$this->Ln(2);
	// 	$this->SetXY(240,11);
	// 	$this->SetFont('Arial','b',8);		
 //        $this->Cell(10,2,'Fecha: ',0,0,'L');
	// 	$this->SetFont('Arial','',8);
 //        $this->Cell(0,2,date("Y-m-d") ,0,0,'L');
	// 	$this->Ln(2);
	// 	$this->SetXY(240,14);
	// 	$this->SetFont('Arial','b',8);	
 //        $this->Cell(12,2,'Usuario: ',0,0,'L');
	// 	$this->SetFont('Arial','',8);	
 //        $this->Cell(0,2,$_SESSION['INGRESO']['Nombre_Completo'],0,0,'L');
	// 	$this->Line(20, 35, 300-20, 35); 
 //        $this->Line(20, 36, 300-20, 36);
	// 	$this->Ln($this->salto_header_cuerpo);

	// }

 }
}
?>