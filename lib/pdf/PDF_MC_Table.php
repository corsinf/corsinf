<?php
if (!class_exists('FPDF')) {
    //$mi_clase = new MiClase();
   require('fpdf.php');
}
//print_r(get_declared_classes());
class PDF_MC_Table extends FPDF
{
	var $widths;
	var $aligns;
	protected $col = 0; // Columna actual
	protected $y0;      // Ordenada de comienzo de la columna
	protected $B = 0;
	protected $I = 0;
	protected $U = 0;
	protected $HREF = '';
	
	//BARCODE
	protected $T128;                                         // Tableau des codes 128
	protected $ABCset = "";                                  // jeu des caractères éligibles au C128
	protected $Aset = "";                                    // Set A du jeu des caractères éligibles
	protected $Bset = "";                                    // Set B du jeu des caractères éligibles
	protected $Cset = "";                                    // Set C du jeu des caractères éligibles
	protected $SetFrom;                                      // Convertisseur source des jeux vers le tableau
	protected $SetTo;                                        // Convertisseur destination des jeux vers le tableau
	protected $JStart = array("A"=>103, "B"=>104, "C"=>105); // Caractères de sélection de jeu au début du C128
	protected $JSwap = array("A"=>101, "B"=>100, "C"=>99);   // Caractères de changement de jeu
	
	function SetWidths($w)
	{
		//Set the array of column widths
		$this->widths=$w;
	}
	function SetAligns($a)
	{
		//Set the array of column alignments
		$this->aligns=$a;
	}
	//data = array con datos, h1 altura, b 0,1 para borde, ali = para tipo de alineacion
	function Row($data,$h1=null,$b=null,$estiloRow=null,$ali=null,$mostrar_cero=false)
	{
		//para el alto
		if ($h1==null)
		{
			$h1=5;
		}
		//Calculate the height of the row
		$nb=0;
		for($i=0;$i<count($data);$i++)
			$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
		$h=$h1*$nb;
		//Issue a page break first if needed
		$this->CheckPageBreak($h);		
		//Draw the cells of the row
		for($i=0;$i<count($data);$i++)
		{
			$w=$this->widths[$i];
			$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
			//Save the current position
			$x=$this->GetX();
			$y=$this->GetY();
			//Draw the border
			if(is_array($b))
			{
				if(array_key_exists($i, $b))
				{
				    $b1 =$b[$i]; 				
				//$this->Rect($x,$y,$w,$h);	
				    if($b1 == 'R' || $b1 == 'RT' || $b1 == 'RB')
					{
						$this->Line($x+$w,$y,$w+$x,$h+$y);
					}
				    if( $b1 == 'L' || $b1 == 'LT' ||$b1 == 'LB')
				    {
				      $this->Line($x,$y,$x,$h+$y);
			        }
			        if($b1 == 'LR')
			        {
			    	  $this->Line($x+$w,$y,$w+$x,$h+$y);
			    	  $this->Line($x,$y,$x,$h+$y);
			        }
			        if($b1 == '1')
			        {
			        	$b1='0';
			        	$this->Rect($x,$y,$w,$h);
			        }

			    }else
			    {
			    	$b1='0';
			    }

			}else if($b=='1')
			{				
				$b1 = '0';
				$this->Rect($x,$y,$w,$h);
			}else if($b != '1' && $b != '0')
			{
				if($b=='LR')
				{
				 $b1 = $b;
				 $this->Line($x+$w,$y,$w+$x,$h+$y);
			     $this->Line($x,$y,$x,$h+$y);
			   }else if($b=='LRT')
			   {
			     $b1 = $b;
				 $this->Line($x+$w,$y,$w+$x,$h+$y);
			     $this->Line($x,$y,$x,$h+$y);

			   }
			   else
			   {
			   	 $b1= $b;
			   }

			}

			else
			{

				$b1='0';
			}
			// if($b=='1')
			// {
			// 	$this->Rect($x,$y,$w,$h);
			// 	$b1 = '1'
			// }
			//Print the text
			//$this->SetTextColor(0,0,0);
			if( is_numeric($data[$i]))
			{
				if($data[$i]<0)
				{
					$this->SetTextColor(255,51,51);
					$data[$i] = round($data[$i], 2);
					if(($data[$i] == 0 ) )
					{
						$data[$i] = '';
					}
				}else
				{
			      $this->SetTextColor(0,0,0);
					$data[$i] = round($data[$i], 2);
					if($data[$i] == 0 and $mostrar_cero==false)
					{
						$data[$i] = '';
					}else
					{
						$data[$i] = $data[$i];
					}
				}
			}else
			{
			  $this->SetTextColor(0,0,0);
			}
			if(strpos($data[$i],'<') === false)
			{
			  if($estiloRow != '')
			  {
			  	$this->SetFont('Arial',$estiloRow);
			    $this->MultiCell($w,$h1,utf8_decode($data[$i]),$b1,$a);
			  }else
			  {
			  	$this->SetFont('Arial','');
				//str_replace('<b>','', $data[$i]);
			    $this->MultiCell($w,$h1,utf8_decode($data[$i]),$b1,$a);
			  }		
				
			}else
			{
				$estilo = explode('<',$data[$i]);
				$estilo1 = explode('>',$estilo[1]);
				//print_r($estilo1);
				$this->SetFont('',$estilo1[0]);		
			    $this->MultiCell($w,$h1,str_replace('<'.$estilo1[0].'>','', utf8_decode($data[$i])),$b1,$a);
			}
			//$this->MultiCell($w,$h1,$data[$i],$b1,$a);
			//Put the position to the right of the cell
			$this->SetXY($x+$w,$y);
		}
		//Go to the next line
		$this->Ln($h);
	}
	function CheckPageBreak($h)
	{
		//If the height h would cause an overflow, add a new page immediately
		if($this->GetY()+$h>$this->PageBreakTrigger)
			$this->AddPage($this->CurOrientation);
	}
	function NbLines($w,$txt)
	{
		//Computes the number of lines a MultiCell of width w will take
		$cw=&$this->CurrentFont['cw'];
		if($w==0)
			$w=$this->w-$this->rMargin-$this->x;
		$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
		$s=str_replace("\r",'',$txt);
		$nb=strlen($s);
		if($nb>0 and $s[$nb-1]=="\n")
			$nb--;
		$sep=-1;
		$i=0;
		$j=0;
		$l=0;
		$nl=1;
		while($i<$nb)
		{
			$c=$s[$i];
			if($c=="\n")
			{
				$i++;
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
				continue;
			}
			if($c==' ')
				$sep=$i;
			$l+=$cw[$c];
			if($l>$wmax)
			{
				if($sep==-1)
				{
					if($i==$j)
						$i++;
				}
				else
					$i=$sep+1;
				$sep=-1;
				$j=$i;
				$l=0;
				$nl++;
			}
			else
				$i++;
		}
		return $nl;
	}
	
	//Cabecera de página
	function Header()
	{
		/*// Logo
		$logo="DEFAULT";
		$this->Image(__DIR__ . '/../../img/logotipos/'.$logo.'.png',10,8,33);
		// Arial bold 15
		$this->SetFont('Arial','B',15);
		// Movernos a la derecha
		$this->Cell(80);
		// Título
		$this->Cell(40,15,'Title',1,0,'C');
		// Salto de línea
		$this->Ln(20);
		global $title;

		// Arial bold 15
		$this->SetFont('Arial','B',15);
		// Calculamos ancho y posición del título.
		$w = $this->GetStringWidth($title)+6;
		$this->SetX(($w)/2);
		// Colores de los bordes, fondo y texto
		$this->SetDrawColor(0,90,180);
		$this->SetFillColor(230,230,0);
		$this->SetTextColor(220,50,50);
		// Ancho del borde (1 mm)
		$this->SetLineWidth(1);
		// Título
		$this->Cell($w,16,$title,1,1,'C',true);
		// Salto de línea
		$this->Ln(10);
		// Guardar ordenada
		//$this->y0 = $this->GetY();*/
	}

	// Pie de página
	function Footer()
	{
		/*// Posición: a 1,5 cm del final
		$this->SetY(-15);
		// Arial italic 8
		$this->SetFont('Arial','I',8);
		// Número de página
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');*/
		// Posición a 1,5 cm del final
		$this->SetY(-15);
		// Arial itálica 8
		$this->SetFont('Arial','I',8);
		// Color del texto en gris
		$this->SetTextColor(128);
		// Número de página
		$this->Cell(0,10,utf8_decode('Página ').$this->PageNo(),0,0,'C');
	}
	function ChapterTitle($num, $label)
	{
		// Arial 12
		$this->SetFont('Arial','',12);
		// Color de fondo
		$this->SetFillColor(200,220,255);
		// Título
		$this->Cell(0,12,"Capítulo $num : $label",0,1,'L',true);
		// Salto de línea
		$this->Ln(4);
		// Guardar ordenada
		//$this->y0 = $this->GetY();
	}

	function ChapterBody($file)
	{
		// Leemos el fichero
		$txt = file_get_contents($file);
		// Times 12
		$this->SetFont('Times','',12);
		// Imprimimos el texto justificado
		$this->MultiCell(0,12,$txt);
		// Salto de línea
		$this->Ln(4);
		// Cita en itálica
		$this->SetFont('','I');
		$this->Cell(0,5,'(fin del extracto)');
		// Volver a la primera columna
		//$this->SetCol(0);
	}

	function PrintChapter($num, $title, $file)
	{
		$this->AddPage();
		$this->ChapterTitle($num,$title);
		$this->ChapterBody($file);
	}
	/*function SetCol($col)
	{
		// Establecer la posición de una columna dada
		$this->col = $col;
		$x = 10+$col*65;
		$this->SetLeftMargin($x);
		$this->SetX($x);
	}

	function AcceptPageBreak()
	{
		// Método que acepta o no el salto automático de página
		if($this->col<2)
		{
			// Ir a la siguiente columna
			$this->SetCol($this->col+1);
			// Establecer la ordenada al principio
			$this->SetY($this->y0);
			// Seguir en esta página
			return false;
		}
		else
		{
			// Volver a la primera columna
			$this->SetCol(0);
			// Salto de página
			return true;
		}
	}*/
	// Cargar los datos
	function LoadData($file)
	{
		// Leer las líneas del fichero
		$lines = file($file);
		$data = array();
		foreach($lines as $line)
			$data[] = explode(';',trim($line));
		return $data;
	}

	// Tabla simple
	function BasicTable($header, $data)
	{
		//mas ancho 
		$tam=0;
		foreach($header as $col)
		{
			if($tam<strlen($col))
			{
				$tam=strlen($col);
			}
		}
		$tam=$tam*7;
		// Cabecera
		foreach($header as $col)
			$this->Cell($tam,13,$col,1);
		$this->Ln();
		// Datos
		foreach($data as $row)
		{
			foreach($row as $col)
				$this->Cell($tam,13,$col,1);
			$this->Ln();
		}
	}

	// Una tabla más completa
	function ImprovedTable($header, $data)
	{
		// Anchuras de las columnas
		//mas ancho 
		$tam=0;
		foreach($header as $col)
		{
			if($tam<strlen($col))
			{
				$tam=strlen($col);
			}
		}
		$tam=$tam*7;
		$w = array($tam, $tam, $tam, $tam);
		// Cabeceras
		for($i=0;$i<count($header);$i++)
			$this->Cell($w[$i],13,$header[$i],1,0,'C');
		$this->Ln();
		// Datos
		foreach($data as $row)
		{
			$this->Cell($w[0],13,$row[0],'LR');
			$this->Cell($w[1],13,$row[1],'LR');
			$this->Cell($w[2],13,number_format($row[2]),'LR',0,'R');
			$this->Cell($w[3],13,number_format($row[3]),'LR',0,'R');
			$this->Ln();
		}
		// Línea de cierre
		$this->Cell(array_sum($w),0,'','T');
	}

	// Tabla coloreada
	function FancyTable($header, $data)
	{
		// Colores, ancho de línea y fuente en negrita
		$this->SetFillColor(255,0,0);
		$this->SetTextColor(255);
		$this->SetDrawColor(128,0,0);
		$this->SetLineWidth(.3);
		$this->SetFont('','B');
		//mas ancho 
		$tam=0;
		foreach($header as $col)
		{
			if($tam<strlen($col))
			{
				$tam=strlen($col);
			}
		}
		$tam=$tam*7;
		// Cabecera
		$w = array($tam, $tam, $tam, $tam);
		for($i=0;$i<count($header);$i++)
			$this->Cell($w[$i],13,$header[$i],1,0,'C',true);
		$this->Ln();
		// Restauración de colores y fuentes
		$this->SetFillColor(224,235,255);
		$this->SetTextColor(0);
		$this->SetFont('');
		// Datos
		$fill = false;
		foreach($data as $row)
		{
			$this->Cell($w[0],13,$row[0],'LR',0,'L',$fill);
			$this->Cell($w[1],13,$row[1],'LR',0,'L',$fill);
			$this->Cell($w[2],13,number_format($row[2]),'LR',0,'R',$fill);
			$this->Cell($w[3],13,number_format($row[3]),'LR',0,'R',$fill);
			$this->Ln();
			$fill = !$fill;
		}
		// Línea de cierre
		$this->Cell(array_sum($w),0,'','T');
	}


	function WriteHTML($html)
	{
		// Intérprete de HTML
		$html = str_replace("\n",' ',$html);
		$a = preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
		foreach($a as $i=>$e)
		{
			if($i%2==0)
			{
				// Text
				if($this->HREF)
					$this->PutLink($this->HREF,$e);
				else
					$this->Write(5,$e);
			}
			else
			{
				// Etiqueta
				if($e[0]=='/')
					$this->CloseTag(strtoupper(substr($e,1)));
				else
				{
					// Extraer atributos
					$a2 = explode(' ',$e);
					$tag = strtoupper(array_shift($a2));
					$attr = array();
					foreach($a2 as $v)
					{
						if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
							$attr[strtoupper($a3[1])] = $a3[2];
					}
					$this->OpenTag($tag,$attr);
				}
			}
		}
	}

	function OpenTag($tag, $attr)
	{
		// Etiqueta de apertura
		if($tag=='B' || $tag=='I' || $tag=='U')
			$this->SetStyle($tag,true);
		if($tag=='A')
			$this->HREF = $attr['HREF'];
		if($tag=='BR')
			$this->Ln(5);
	}

	function CloseTag($tag)
	{
		// Etiqueta de cierre
		if($tag=='B' || $tag=='I' || $tag=='U')
			$this->SetStyle($tag,false);
		if($tag=='A')
			$this->HREF = '';
	}

	function SetStyle($tag, $enable)
	{
		// Modificar estilo y escoger la fuente correspondiente
		$this->$tag += ($enable ? 1 : -1);
		$style = '';
		foreach(array('B', 'I', 'U') as $s)
		{
			if($this->$s>0)
				$style .= $s;
		}
		$this->SetFont('',$style);
	}

	function PutLink($URL, $txt)
	{
		// Escribir un hiper-enlace
		$this->SetTextColor(0,0,255);
		$this->SetStyle('U',true);
		$this->Write(5,$txt,$URL);
		$this->SetStyle('U',false);
		$this->SetTextColor(0);
	}
	//texto,posx,poxy,ancho celda,alto celda,espacio superior, curva,color 
	function cabeceraHorizontal($cabecera,$sx=null,$sy=null,$anc=null,$alt=null,$altl=null,$cur=null,$col1=null
	,$col2=null,$col3=null,$col4=null,$col5=null,$col6=null)
    {
		//coordenada de letras y celdas
		if($sx==null)
		{
			$sx=20;
		}
		if($sy==null)
		{
			$sy=50;
		}
		//ancho celda
		if($anc==null)
		{
			$anc=120;
		}
		//alto celda
		if($alt==null)
		{
			$alt=50;
		}
		//espacio superior letra
		if($altl==null)
		{
			$altl=20;
		}
		//curva celda
		if($cur==null)
		{
			$cur=5;
		}
        $this->SetXY($sx, $sy);
        $this->SetFont('Arial','B',10);
		$letra = 'D';
		if($col1!=null)
		{
			$this->SetFillColor($col1,$col2,$col3);//Fondo verde de celda
			$letra = 'FD';
		}
		if($col4!=null)
		{
			$this->SetTextColor($col4, $col5, $col6); //Letra color blanco
		}
        $ejeX = $sx;
        foreach($cabecera as $fila)
        {
            $this->RoundedRect($ejeX, $sy, $anc, $alt, $cur, $letra);
            $this->CellFitSpace($anc,$altl, utf8_decode($fila),0, 0 , 'C');
            $ejeX = $ejeX + $anc;
        }
    }
 
    function datosHorizontal($datos,$sx=null,$sy=null,$anc=null,$alt=null,$altl=null,$cur=null,$col1=null
	,$col2=null,$col3=null,$col4=null,$col5=null,$col6=null)
    {
		//coordenada de letras y celdas
		if($sx==null)
		{
			$sx=20;
		}
		if($sy==null)
		{
			$sy=100;
		}
		//ancho celda
		if($anc==null)
		{
			$anc=120;
		}
		//alto celda
		if($alt==null)
		{
			$alt=50;
		}
		//espacio superior letra
		if($altl==null)
		{
			$altl=50;
		}
		//curva celda
		if($cur==null)
		{
			$cur=5;
		}
        $this->SetXY($sx,$sy);
        $this->SetFont('Arial','',10);
		$letra = 'D';
		if($col1!=null)
		{
			$this->SetFillColor($col1,$col2,$col3);//Fondo verde de celda
			$letra = 'FD';
		}
		if($col4!=null)
		{
			$this->SetTextColor($col4, $col5, $col6); //Letra color blanco
		}
        //$this->SetFillColor(229, 229, 229); //Gris tenue de cada fila
        //$this->SetTextColor(3, 3, 3); //Color del texto: Negro
        $bandera = false; //Para alternar el relleno
        $ejeY = $sy; //Aquí se encuentra la primer CellFitSpace e irá incrementando
        //$letra = 'D'; //'D' Dibuja borde de cada CellFitSpace -- 'FD' Dibuja borde y rellena
		$i=0;
        foreach($datos as $fila)
        {
            //Por cada 3 CellFitSpace se crea un RoundedRect encimado
            //El parámetro $letra de RoundedRect cambiará en cada iteración
            //para colocar FD y D, la primera iteración es D
            //Solo la celda de enmedio llevará bordes, izquierda y derecha
            //Las celdas laterales colocarlas sin borde
            $this->RoundedRect($sx, $ejeY, $anc, $alt, $cur, $letra);
			if($i==0)
			{
				$this->CellFitSpace($anc,$altl, utf8_decode($fila['nombre']),0, 0 , 'L' );
			}
			else
			{
				$this->CellFitSpace($anc+5,$altl, utf8_decode($fila['nombre']),0, 0 , 'L' );
			}
            
            $this->CellFitSpace($anc,$altl, utf8_decode($fila['apellido']),'LR', 0 , 'L' );
            $this->CellFitSpace($anc,$altl, utf8_decode($fila['matricula']),0, 0 , 'L' );
 
            $this->Ln();
            //Condición ternaria que cambia el valor de $letra
            //($letra == 'D') ? $letra = 'FD' : $letra = 'D';
            //Aumenta la siguiente posición de Y (recordar que X es fijo)
            //Se suma 7 porque cada celda tiene esa altura
            $ejeY = $ejeY + $alt;
			$i++;
        }
    }
 
    function tablaHorizontal($cabeceraHorizontal=null, $datosHorizontal=null)
    {
        $this->cabeceraHorizontal($cabeceraHorizontal);
        $this->datosHorizontal($datosHorizontal);
    }
 
    //**************************************************************************************************************
    function CellFit($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='', $scale=false, $force=true)
    {
        //Get string width
        $str_width=$this->GetStringWidth($txt);
 
        //Calculate ratio to fit cell
        if($w==0)
            $w = $this->w-$this->rMargin-$this->x;
        $ratio = ($w-$this->cMargin*2)/$str_width;
 
        $fit = ($ratio < 1 || ($ratio > 1 && $force));
        if ($fit)
        {
            if ($scale)
            {
                //Calculate horizontal scaling
                $horiz_scale=$ratio*100.0;
                //Set horizontal scaling
                $this->_out(sprintf('BT %.2F Tz ET',$horiz_scale));
            }
            else
            {
                //Calculate character spacing in points
                $char_space=($w-$this->cMargin*2-$str_width)/max($this->MBGetStringLength($txt)-1,1)*$this->k;
                //Set character spacing
                $this->_out(sprintf('BT %.2F Tc ET',$char_space));
            }
            //Override user alignment (since text will fill up cell)
            $align='';
        }
 
        //Pass on to Cell method
        $this->Cell($w,$h,$txt,$border,$ln,$align,$fill,$link);
 
        //Reset character spacing/horizontal scaling
        if ($fit)
            $this->_out('BT '.($scale ? '100 Tz' : '0 Tc').' ET');
    }
 
    function CellFitSpace($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,false,false);
    }
 
    //Patch to also work with CJK double-byte text
    function MBGetStringLength($s)
    {
        if($this->CurrentFont['type']=='Type0')
        {
            $len = 0;
            $nbbytes = strlen($s);
            for ($i = 0; $i < $nbbytes; $i++)
            {
                if (ord($s[$i])<128)
                    $len++;
                else
                {
                    $len++;
                    $i++;
                }
            }
            return $len;
        }
        else
            return strlen($s);
    }
//**********************************************************************************************
 
 function RoundedRect($x, $y, $w, $h, $r, $style = '', $angle = '1234')
    {
        $k = $this->k;
        $hp = $this->h;
        if($style=='F')
            $op='f';
        elseif($style=='FD' or $style=='DF')
            $op='B';
        else
            $op='S';
        $MyArc = 4/3 * (sqrt(2) - 1);
        $this->_out(sprintf('%.2f %.2f m', ($x+$r)*$k, ($hp-$y)*$k ));
 
        $xc = $x+$w-$r;
        $yc = $y+$r;
        $this->_out(sprintf('%.2f %.2f l', $xc*$k, ($hp-$y)*$k ));
        if (strpos($angle, '2')===false)
            $this->_out(sprintf('%.2f %.2f l', ($x+$w)*$k, ($hp-$y)*$k ));
        else
            $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
 
        $xc = $x+$w-$r;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2f %.2f l', ($x+$w)*$k, ($hp-$yc)*$k));
        if (strpos($angle, '3')===false)
            $this->_out(sprintf('%.2f %.2f l', ($x+$w)*$k, ($hp-($y+$h))*$k));
        else
            $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
 
        $xc = $x+$r;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2f %.2f l', $xc*$k, ($hp-($y+$h))*$k));
        if (strpos($angle, '4')===false)
            $this->_out(sprintf('%.2f %.2f l', ($x)*$k, ($hp-($y+$h))*$k));
        else
            $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
 
        $xc = $x+$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2f %.2f l', ($x)*$k, ($hp-$yc)*$k ));
        if (strpos($angle, '1')===false)
        {
            $this->_out(sprintf('%.2f %.2f l', ($x)*$k, ($hp-$y)*$k ));
            $this->_out(sprintf('%.2f %.2f l', ($x+$r)*$k, ($hp-$y)*$k ));
        }
        else
            $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
        $this->_out($op);
    }
 
    function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
    {
        $h = $this->h;
        $this->_out(sprintf('%.2f %.2f %.2f %.2f %.2f %.2f c ', $x1*$this->k, ($h-$y1)*$this->k,
            $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
    }
	/*******************************************************************************************
	********************************************************************************************
	***********************************BARCODE 128**********************************************
	********************************************************************************************
	********************************************************************************************/
	
	

	//____________________________ Extension du constructeur _______________________
	function __construct($orientation='P', $unit='mm', $format='A4') {

		parent::__construct($orientation,$unit,$format);

		$this->T128[] = array(2, 1, 2, 2, 2, 2);           //0 : [ ]               // composition des caractères
		$this->T128[] = array(2, 2, 2, 1, 2, 2);           //1 : [!]
		$this->T128[] = array(2, 2, 2, 2, 2, 1);           //2 : ["]
		$this->T128[] = array(1, 2, 1, 2, 2, 3);           //3 : [#]
		$this->T128[] = array(1, 2, 1, 3, 2, 2);           //4 : [$]
		$this->T128[] = array(1, 3, 1, 2, 2, 2);           //5 : [%]
		$this->T128[] = array(1, 2, 2, 2, 1, 3);           //6 : [&]
		$this->T128[] = array(1, 2, 2, 3, 1, 2);           //7 : [']
		$this->T128[] = array(1, 3, 2, 2, 1, 2);           //8 : [(]
		$this->T128[] = array(2, 2, 1, 2, 1, 3);           //9 : [)]
		$this->T128[] = array(2, 2, 1, 3, 1, 2);           //10 : [*]
		$this->T128[] = array(2, 3, 1, 2, 1, 2);           //11 : [+]
		$this->T128[] = array(1, 1, 2, 2, 3, 2);           //12 : [,]
		$this->T128[] = array(1, 2, 2, 1, 3, 2);           //13 : [-]
		$this->T128[] = array(1, 2, 2, 2, 3, 1);           //14 : [.]
		$this->T128[] = array(1, 1, 3, 2, 2, 2);           //15 : [/]
		$this->T128[] = array(1, 2, 3, 1, 2, 2);           //16 : [0]
		$this->T128[] = array(1, 2, 3, 2, 2, 1);           //17 : [1]
		$this->T128[] = array(2, 2, 3, 2, 1, 1);           //18 : [2]
		$this->T128[] = array(2, 2, 1, 1, 3, 2);           //19 : [3]
		$this->T128[] = array(2, 2, 1, 2, 3, 1);           //20 : [4]
		$this->T128[] = array(2, 1, 3, 2, 1, 2);           //21 : [5]
		$this->T128[] = array(2, 2, 3, 1, 1, 2);           //22 : [6]
		$this->T128[] = array(3, 1, 2, 1, 3, 1);           //23 : [7]
		$this->T128[] = array(3, 1, 1, 2, 2, 2);           //24 : [8]
		$this->T128[] = array(3, 2, 1, 1, 2, 2);           //25 : [9]
		$this->T128[] = array(3, 2, 1, 2, 2, 1);           //26 : [:]
		$this->T128[] = array(3, 1, 2, 2, 1, 2);           //27 : [;]
		$this->T128[] = array(3, 2, 2, 1, 1, 2);           //28 : [<]
		$this->T128[] = array(3, 2, 2, 2, 1, 1);           //29 : [=]
		$this->T128[] = array(2, 1, 2, 1, 2, 3);           //30 : [>]
		$this->T128[] = array(2, 1, 2, 3, 2, 1);           //31 : [?]
		$this->T128[] = array(2, 3, 2, 1, 2, 1);           //32 : [@]
		$this->T128[] = array(1, 1, 1, 3, 2, 3);           //33 : [A]
		$this->T128[] = array(1, 3, 1, 1, 2, 3);           //34 : [B]
		$this->T128[] = array(1, 3, 1, 3, 2, 1);           //35 : [C]
		$this->T128[] = array(1, 1, 2, 3, 1, 3);           //36 : [D]
		$this->T128[] = array(1, 3, 2, 1, 1, 3);           //37 : [E]
		$this->T128[] = array(1, 3, 2, 3, 1, 1);           //38 : [F]
		$this->T128[] = array(2, 1, 1, 3, 1, 3);           //39 : [G]
		$this->T128[] = array(2, 3, 1, 1, 1, 3);           //40 : [H]
		$this->T128[] = array(2, 3, 1, 3, 1, 1);           //41 : [I]
		$this->T128[] = array(1, 1, 2, 1, 3, 3);           //42 : [J]
		$this->T128[] = array(1, 1, 2, 3, 3, 1);           //43 : [K]
		$this->T128[] = array(1, 3, 2, 1, 3, 1);           //44 : [L]
		$this->T128[] = array(1, 1, 3, 1, 2, 3);           //45 : [M]
		$this->T128[] = array(1, 1, 3, 3, 2, 1);           //46 : [N]
		$this->T128[] = array(1, 3, 3, 1, 2, 1);           //47 : [O]
		$this->T128[] = array(3, 1, 3, 1, 2, 1);           //48 : [P]
		$this->T128[] = array(2, 1, 1, 3, 3, 1);           //49 : [Q]
		$this->T128[] = array(2, 3, 1, 1, 3, 1);           //50 : [R]
		$this->T128[] = array(2, 1, 3, 1, 1, 3);           //51 : [S]
		$this->T128[] = array(2, 1, 3, 3, 1, 1);           //52 : [T]
		$this->T128[] = array(2, 1, 3, 1, 3, 1);           //53 : [U]
		$this->T128[] = array(3, 1, 1, 1, 2, 3);           //54 : [V]
		$this->T128[] = array(3, 1, 1, 3, 2, 1);           //55 : [W]
		$this->T128[] = array(3, 3, 1, 1, 2, 1);           //56 : [X]
		$this->T128[] = array(3, 1, 2, 1, 1, 3);           //57 : [Y]
		$this->T128[] = array(3, 1, 2, 3, 1, 1);           //58 : [Z]
		$this->T128[] = array(3, 3, 2, 1, 1, 1);           //59 : [[]
		$this->T128[] = array(3, 1, 4, 1, 1, 1);           //60 : [\]
		$this->T128[] = array(2, 2, 1, 4, 1, 1);           //61 : []]
		$this->T128[] = array(4, 3, 1, 1, 1, 1);           //62 : [^]
		$this->T128[] = array(1, 1, 1, 2, 2, 4);           //63 : [_]
		$this->T128[] = array(1, 1, 1, 4, 2, 2);           //64 : [`]
		$this->T128[] = array(1, 2, 1, 1, 2, 4);           //65 : [a]
		$this->T128[] = array(1, 2, 1, 4, 2, 1);           //66 : [b]
		$this->T128[] = array(1, 4, 1, 1, 2, 2);           //67 : [c]
		$this->T128[] = array(1, 4, 1, 2, 2, 1);           //68 : [d]
		$this->T128[] = array(1, 1, 2, 2, 1, 4);           //69 : [e]
		$this->T128[] = array(1, 1, 2, 4, 1, 2);           //70 : [f]
		$this->T128[] = array(1, 2, 2, 1, 1, 4);           //71 : [g]
		$this->T128[] = array(1, 2, 2, 4, 1, 1);           //72 : [h]
		$this->T128[] = array(1, 4, 2, 1, 1, 2);           //73 : [i]
		$this->T128[] = array(1, 4, 2, 2, 1, 1);           //74 : [j]
		$this->T128[] = array(2, 4, 1, 2, 1, 1);           //75 : [k]
		$this->T128[] = array(2, 2, 1, 1, 1, 4);           //76 : [l]
		$this->T128[] = array(4, 1, 3, 1, 1, 1);           //77 : [m]
		$this->T128[] = array(2, 4, 1, 1, 1, 2);           //78 : [n]
		$this->T128[] = array(1, 3, 4, 1, 1, 1);           //79 : [o]
		$this->T128[] = array(1, 1, 1, 2, 4, 2);           //80 : [p]
		$this->T128[] = array(1, 2, 1, 1, 4, 2);           //81 : [q]
		$this->T128[] = array(1, 2, 1, 2, 4, 1);           //82 : [r]
		$this->T128[] = array(1, 1, 4, 2, 1, 2);           //83 : [s]
		$this->T128[] = array(1, 2, 4, 1, 1, 2);           //84 : [t]
		$this->T128[] = array(1, 2, 4, 2, 1, 1);           //85 : [u]
		$this->T128[] = array(4, 1, 1, 2, 1, 2);           //86 : [v]
		$this->T128[] = array(4, 2, 1, 1, 1, 2);           //87 : [w]
		$this->T128[] = array(4, 2, 1, 2, 1, 1);           //88 : [x]
		$this->T128[] = array(2, 1, 2, 1, 4, 1);           //89 : [y]
		$this->T128[] = array(2, 1, 4, 1, 2, 1);           //90 : [z]
		$this->T128[] = array(4, 1, 2, 1, 2, 1);           //91 : [{]
		$this->T128[] = array(1, 1, 1, 1, 4, 3);           //92 : [|]
		$this->T128[] = array(1, 1, 1, 3, 4, 1);           //93 : [}]
		$this->T128[] = array(1, 3, 1, 1, 4, 1);           //94 : [~]
		$this->T128[] = array(1, 1, 4, 1, 1, 3);           //95 : [DEL]
		$this->T128[] = array(1, 1, 4, 3, 1, 1);           //96 : [FNC3]
		$this->T128[] = array(4, 1, 1, 1, 1, 3);           //97 : [FNC2]
		$this->T128[] = array(4, 1, 1, 3, 1, 1);           //98 : [SHIFT]
		$this->T128[] = array(1, 1, 3, 1, 4, 1);           //99 : [Cswap]
		$this->T128[] = array(1, 1, 4, 1, 3, 1);           //100 : [Bswap]                
		$this->T128[] = array(3, 1, 1, 1, 4, 1);           //101 : [Aswap]
		$this->T128[] = array(4, 1, 1, 1, 3, 1);           //102 : [FNC1]
		$this->T128[] = array(2, 1, 1, 4, 1, 2);           //103 : [Astart]
		$this->T128[] = array(2, 1, 1, 2, 1, 4);           //104 : [Bstart]
		$this->T128[] = array(2, 1, 1, 2, 3, 2);           //105 : [Cstart]
		$this->T128[] = array(2, 3, 3, 1, 1, 1);           //106 : [STOP]
		$this->T128[] = array(2, 1);                       //107 : [END BAR]

		for ($i = 32; $i <= 95; $i++) {                                            // jeux de caractères
			$this->ABCset .= chr($i);
		}
		$this->Aset = $this->ABCset;
		$this->Bset = $this->ABCset;
		
		for ($i = 0; $i <= 31; $i++) {
			$this->ABCset .= chr($i);
			$this->Aset .= chr($i);
		}
		for ($i = 96; $i <= 127; $i++) {
			$this->ABCset .= chr($i);
			$this->Bset .= chr($i);
		}
		for ($i = 200; $i <= 210; $i++) {                                           // controle 128
			$this->ABCset .= chr($i);
			$this->Aset .= chr($i);
			$this->Bset .= chr($i);
		}
		$this->Cset="0123456789".chr(206);

		for ($i=0; $i<96; $i++) {                                                   // convertisseurs des jeux A & B
			@$this->SetFrom["A"] .= chr($i);
			@$this->SetFrom["B"] .= chr($i + 32);
			@$this->SetTo["A"] .= chr(($i < 32) ? $i+64 : $i-32);
			@$this->SetTo["B"] .= chr($i);
		}
		for ($i=96; $i<107; $i++) {                                                 // contrôle des jeux A & B
			@$this->SetFrom["A"] .= chr($i + 104);
			@$this->SetFrom["B"] .= chr($i + 104);
			@$this->SetTo["A"] .= chr($i);
			@$this->SetTo["B"] .= chr($i);
		}
	}

	//________________ Fonction encodage et dessin du code 128 _____________________
	function Code128($x, $y, $code, $w, $h) {
		$Aguid = "";                                                                      // Création des guides de choix ABC
		$Bguid = "";
		$Cguid = "";
		for ($i=0; $i < strlen($code); $i++) {
			$needle = substr($code,$i,1);
			$Aguid .= ((strpos($this->Aset,$needle)===false) ? "N" : "O"); 
			$Bguid .= ((strpos($this->Bset,$needle)===false) ? "N" : "O"); 
			$Cguid .= ((strpos($this->Cset,$needle)===false) ? "N" : "O");
		}

		$SminiC = "OOOO";
		$IminiC = 4;

		$crypt = "";
		while ($code > "") {
																						// BOUCLE PRINCIPALE DE CODAGE
			$i = strpos($Cguid,$SminiC);                                                // forçage du jeu C, si possible
			if ($i!==false) {
				$Aguid [$i] = "N";
				$Bguid [$i] = "N";
			}

			if (substr($Cguid,0,$IminiC) == $SminiC) {                                  // jeu C
				$crypt .= chr(($crypt > "") ? $this->JSwap["C"] : $this->JStart["C"]);  // début Cstart, sinon Cswap
				$made = strpos($Cguid,"N");                                             // étendu du set C
				if ($made === false) {
					$made = strlen($Cguid);
				}
				if (fmod($made,2)==1) {
					$made--;                                                            // seulement un nombre pair
				}
				for ($i=0; $i < $made; $i += 2) {
					$crypt .= chr(strval(substr($code,$i,2)));                          // conversion 2 par 2
				}
				$jeu = "C";
			} else {
				$madeA = strpos($Aguid,"N");                                            // étendu du set A
				if ($madeA === false) {
					$madeA = strlen($Aguid);
				}
				$madeB = strpos($Bguid,"N");                                            // étendu du set B
				if ($madeB === false) {
					$madeB = strlen($Bguid);
				}
				$made = (($madeA < $madeB) ? $madeB : $madeA );                         // étendu traitée
				$jeu = (($madeA < $madeB) ? "B" : "A" );                                // Jeu en cours

				$crypt .= chr(($crypt > "") ? $this->JSwap[$jeu] : $this->JStart[$jeu]); // début start, sinon swap

				$crypt .= strtr(substr($code, 0,$made), $this->SetFrom[$jeu], $this->SetTo[$jeu]); // conversion selon jeu

			}
			$code = substr($code,$made);                                           // raccourcir légende et guides de la zone traitée
			$Aguid = substr($Aguid,$made);
			$Bguid = substr($Bguid,$made);
			$Cguid = substr($Cguid,$made);
		}                                                                          // FIN BOUCLE PRINCIPALE

		$check = ord($crypt[0]);                                                   // calcul de la somme de contrôle
		for ($i=0; $i<strlen($crypt); $i++) {
			$check += (ord($crypt[$i]) * $i);
		}
		$check %= 103;

		$crypt .= chr($check) . chr(106) . chr(107);                               // Chaine cryptée complète

		$i = (strlen($crypt) * 11) - 8;                                            // calcul de la largeur du module
		$modul = $w/$i;

		for ($i=0; $i<strlen($crypt); $i++) {                                      // BOUCLE D'IMPRESSION
			$c = $this->T128[ord($crypt[$i])];
			for ($j=0; $j<count($c); $j++) {
				$this->Rect($x,$y,$c[$j]*$modul,$h,"F");
				$x += ($c[$j++]+$c[$j])*$modul;
			}
		}
	}
}
?>