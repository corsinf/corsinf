<?php 

require_once(dirname(__DIR__,2).'/modelo/EDUCATIVO/herramientas_gpaM.php');
require_once(dirname(__DIR__,2).'/db/codigos_globales.php');
require_once(dirname(__DIR__,2).'/lib/pdf/cabecera_pdf.php');

/**
 * 
 */
$controlador = new herramientas_gpaC();
if(isset($_GET['gpa_pdf']))
{
	$encodedArray = $_GET['data'];
	$idioma = $_GET['idioma'];
    $datos = json_decode(urldecode($encodedArray), true);
	echo json_encode($controlador->gpa_pdf($datos,$idioma));
}
if(isset($_GET['ejecutar_sp']))
{
	$parametros = $_POST['parametros'];
	 echo json_encode($controlador->ejecutar_sp($parametros));
}


class herramientas_gpaC
{
	private $modelo;
	private $cod_global;
	
	function __construct()
	{
		$this->modelo = new herramientas_gpaM();
		$this->cod_global = new codigos_globales();
		$this->pdf = new cabecera_pdf();
		
	}

	function gpa_pdf($datos,$idioma)
	{
		$titulo="Reporte bajas";
		$ingles = $idioma;
		$sizetable = 9;
		$pos=1;


		$image[0] = array('url'=>dirname(__DIR__,2).'/img/de_sistema/escudo_ec.png' ,'x'=>10,'y'=>10,'width'=>65 ,'height'=>25);
		$image[1] = array('url'=>dirname(__DIR__,2).'/img/de_sistema/LOGOMINEDUC.png' ,'x'=>140,'y'=>5,'width'=>60 ,'height'=>30);

		$text = 'SUBSECRETARIA DE EDUCACIÓN METROPOLITANA DE QUITO';
		if($ingles)
		{
			$text = 'METROPOLITAN UNDERSECRETARY OF EDUCATION OF QUITO';
		}		
		$tablaHTML = array();
		$tablaHTML[0]['medidas']=array(192);
		$tablaHTML[0]['alineado']=array('C');
		$tablaHTML[0]['datos']=array($text);
		// $tablaHTML[0]['estilo']='B';
		// $tablaHTML[0]['borde'] = '1';

		$text = 'CODIGO AMIE: 17H01716';
		$text2 = 'AÑO LECTIVO: 2021-2022';
		$text3 = 'RÉGIMEN: SIERRA';
		if($ingles)
		{
			$text = 'AMIE CODE: 17H01716';
			$text2 = 'ACADEMIC YEAR: 2021-2022 ';
			$text3 = 'REGIME: HIGHLANDS';
		}
		$tablaHTML[1]['medidas']=array(70,70,70);
		$tablaHTML[1]['alineado']=array('L','L','L');
		$tablaHTML[1]['datos']=array($text,$text2,$text3);
		// $tablaHTML[1]['estilo']='';
		// $tablaHTML[0]['borde'] = '1';

		$text = 'CERTIFICADO DE  PROMOCIÓN';
		if($ingles)
		{
			$text = 'PROMOTION CERTIFICATE';
		}	
		$tablaHTML[2]['medidas']=array(192);
		$tablaHTML[2]['alineado']=array('C');
		$tablaHTML[2]['datos']=array($text);
		$tablaHTML[2]['estilo']='B';
		// $tablaHTML[2]['borde'] = '1';

		$text = 'El Rector (a)/ Director (a) de la institución Educativa';
		if($ingles)
		{
			$text = 'The Rector of the School:';
		}
		$tablaHTML[4]['medidas']=array(192);
		$tablaHTML[4]['alineado']=array('L');
		$tablaHTML[4]['datos']=array($text);
		$tablaHTML[4]['estilo']='';
		// $tablaHTML[4]['borde'] = '1';

		$text = 'Unidad Educativa Particular "Saint Dominic School"';
		if($ingles)
		{
			$text = '"SAINT DOMINIC SCHOOL" PARTICULAR SCHOOL';
		}
		$tablaHTML[5]['medidas']=array(192);
		$tablaHTML[5]['alineado']=array('C');
		$tablaHTML[5]['datos']=array($text);
		$tablaHTML[5]['estilo']='B';
		$tablaHTML[5]['size'] = 13;


		$text = 'De conformidad con lo prescrito en el Art. 197 de Reglamento General a la ley Órganica de Educación Intercultural y demás normativas vigentes, certifica que el/la estudiante:';
		if($ingles)
		{
			$text = 'According to Art. 197 of the General Rulebook of the Organic Law of Intercultural Education and other currentrules, certifies that student:';
		}
		$tablaHTML[6]['medidas']=array(192);
		$tablaHTML[6]['alineado']=array('L');
		$tablaHTML[6]['datos']=array($text);
		$tablaHTML[6]['estilo']='';

		$tablaHTML[7]['medidas']=array(192);
		$tablaHTML[7]['alineado']=array('C');
		$tablaHTML[7]['datos']=array('RUBIO CHILLAGANA ANNY MAITE');
		$tablaHTML[7]['estilo']='B';
		$tablaHTML[7]['size'] = 12;


		$text = 'del PRIMER CURSO DE BACHILLERATO GENERAL UNIFICADO E,CÓDIGO DEL ESTUDIANTE N° 1753987757 obtuvo las siguientes calificaciones durante el presente año lectivo';
		if($ingles)
		{
			$text = 'student of the First Year of General Unified Baccalaureate "E", with student code N° 1753987757, obtained the following grades throughout the academic year:';
		}
		$tablaHTML[8]['medidas']=array(192);
		$tablaHTML[8]['alineado']=array('L');
		$tablaHTML[8]['datos']=array($text);

		$text = 'ÁREAS';
		$text2 = 'ASIGNATURAS';
		$text3 = 'PROMEDIO ANUAL';
		if($ingles)
		{
			$text = 'Areas';
			$text2 = 'Subject';
			$text3 = 'Annual Average';
		}


		$tablaHTML[9]['medidas']=array(48,48,96);
		$tablaHTML[9]['alineado']=array('L','L','C');
		$tablaHTML[9]['datos']=array($text,$text2,$text3);
		$tablaHTML[9]['borde'] = 'LRT';

		$text = 'del PRIMER CURSO DE BACHILLERATO GENERAL UNIFICADO E,CÓDIGO DEL ESTUDIANTE N° 1753987757 obtuvo las siguientes calificaciones durante el presente año lectivo';
		if($ingles)
		{
			$text = 'student of the First Year of General Unified Baccalaureate "E", with student code N° 1753987757, obtained the following grades throughout the academic year:';
		}
		$tablaHTML[10]['medidas']=array(48,48,20,76);
		$tablaHTML[10]['alineado']=array('L','L','L','L');
		$tablaHTML[10]['datos']=array('','','Quantitative score','Qualitative score');
		$tablaHTML[10]['borde'] = '1';

		$text = 'CALIFICACIÓN CUANTITATIVA';
		$text2 = 'CALIFICACIÓN CUALITATIVA';
		if($ingles)
		{
			$text = 'Quantitative score';
			$text2 = 'Qualitative score';
		}
		$tablaHTML[10]['medidas']=array(48,48,20,76);
		$tablaHTML[10]['alineado']=array('L','L','L','L');
		$tablaHTML[10]['datos']=array('','',$text,$text2);
		$tablaHTML[10]['borde'] = '1';

		$pos = 11;
		$area = '';
		$promedio = 0;
		$n_nota =0;
		foreach ($datos as $key => $value) {
			$tablaHTML[$pos]['medidas']=array(48,48,20,76);
			$tablaHTML[$pos]['alineado']=array('L','L','L','L');
			$tablaHTML[$pos]['datos']=array($this->ingles($value['area'],$ingles),$this->ingles($value['text'],$ingles),$value['nota'],$this->valoracion($value['nota'],$ingles));
			$tablaHTML[$pos]['borde'] = '1';
			$pos++;
			if(is_numeric($value['nota']))
			{
				$promedio= $promedio + floatval($value['nota']);
				$n_nota+=1;
			}
		}

		$promedio = $promedio/$n_nota;
		$letra = $this->promedio_valoraciongpa($promedio);
		$tablaHTML[$pos]['medidas']=array(96,20,76);
		$tablaHTML[$pos]['alineado']=array('L','L','L');
		$tablaHTML[$pos]['datos']=array($this->ingles("<b>Promedio General",$ingles),$promedio,$this->valoracion($promedio,$ingles));
		$tablaHTML[$pos]['borde'] = '1';
		$tablaHTML[$pos]['estilo']='B';
		$pos++;
		$tablaHTML[$pos]['medidas']=array(96,20,76);
		$tablaHTML[$pos]['alineado']=array('L','L','L');
		$tablaHTML[$pos]['datos']=array($this->ingles("<b>Evaluacion del comportamiento",$ingles),'B',$this->valoracion('B',$ingles));
		$tablaHTML[$pos]['borde'] = '1';
		$tablaHTML[$pos]['estilo']='B';

		$pos++;

		$text = 'Por lo tanto, es promovido/a al Segundo Curso de Bachillerato General unificado, para constancia suscriben en unidad de acta el/la RECTORA con el/la SECRETARIO GENERAL del plantel que certifica.
		Dado y firmado en Quito, ';
		if($ingles)
		{
			$text = "Therefore, she is promoted to the Second Year of General unified Baccalaureate. In witness whereof, the Rector and the General Secretary of the School affix their signatures. Given in QUITO, PICHINCHA, on ";
		}

		$tablaHTML[$pos]['medidas']=array(192);
		$tablaHTML[$pos]['alineado']=array('L');
		$tablaHTML[$pos]['datos']=array("");
		$pos++;
		$tablaHTML[$pos]['medidas']=array(192);
		$tablaHTML[$pos]['alineado']=array('L');
		$tablaHTML[$pos]['datos']=array($text.date('r'));
		$pos++;

		$tablaHTML[$pos]['medidas']=array(192);
		$tablaHTML[$pos]['alineado']=array('L');
		$tablaHTML[$pos]['datos']=array("");
		$pos++;

		$tablaHTML[$pos]['medidas']=array(192);
		$tablaHTML[$pos]['alineado']=array('L');
		$tablaHTML[$pos]['datos']=array("");
		$pos++;

		$tablaHTML[$pos]['medidas']=array(192);
		$tablaHTML[$pos]['alineado']=array('L');
		$tablaHTML[$pos]['datos']=array("");
		$pos++;



		$tablaHTML[$pos]['medidas']=array(15,65,30,65,15);
		$tablaHTML[$pos]['alineado']=array('L','C','L','C','L',);
		$tablaHTML[$pos]['datos']=array("","_________________________________","","_________________________________","");
		// $tablaHTML[$pos]['borde'] = '1';
		$pos++;

		$tablaHTML[$pos]['medidas']=array(15,65,30,65,15);
		$tablaHTML[$pos]['alineado']=array('L','L','L','L','L',);
		$tablaHTML[$pos]['datos']=array("","SR./SRA..........................","","SR./SRA..........................","");
		// $tablaHTML[$pos]['borde'] = '1';
		$pos++;

		$tablaHTML[$pos]['medidas']=array(15,65,30,65,15);
		$tablaHTML[$pos]['alineado']=array('L','C','L','C','L',);
		$tablaHTML[$pos]['datos']=array("","RECTOR","","GENERAL SECRETARY","");
		// $tablaHTML[$pos]['borde'] = '1';
		$pos++;

		return $this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image,'fecha','fecha',$sizetable,true,$sal_hea_body=20);
	}


	function promedio_valoraciongpa($nota)
	{
		$valor = '';
		if($nota>=9 && $nota <=10)
		{
			$valor = 'A';
		}
		if($nota>=8 && $nota <=8.9)
		{
			$valor = 'B';
		}
		if($nota>=7 && $nota <=7.9)
		{
			$valor = 'C';
		}
		if($nota>=6 && $nota <=6.9)
		{
			$valor = 'D';
		}
		if($nota>=0 && $nota <=5.9)
		{
			$valor = 'F';
		}

		return $valor;
	}

	function valoracion($nota,$ingles=false)
	{
		$text = '';
		if($nota >=7 && $nota <8.93)
		{
			if($ingles)
			{
				$text = "Achieves the Required Learnings";
			}else
			{
				$text = "ALCANZA LOS APRENDIZAJES REQUERIDOS";
			}
		}
		if($nota >8.93 && $nota <=10)
		{
			if($ingles)
			{
				$text = "Dominates the Required Learnings";
			}else
			{
				$text = "DOMINA LOS APRENDIZAJES REQUERIDOS";
			}
		}
		if($nota=='EX')
		{
			if($ingles)
			{
				$text = "Excellent";
			}else
			{
				$text = "Excelente";
			}

		}
		if($nota=='B')
		{
			if($ingles)
			{
				$text = "Satisfactory";
			}else
			{
				$text = "Satisfactorio";
			}

		}
		return $text;
	}

	function ingles($dato,$ingles=false)
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
				 "Evaluacion del comportamiento");
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
			"Behavior Evaluation");
		if($ingles)
		{
			return str_replace($buscar, $remplazar, $dato); 
		}else
		{
			return $dato;
		}
	}





}
?>