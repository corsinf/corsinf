<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
include('../db/db.php');
include('../modelo/marcasM.php');
include('../modelo/estadoM.php');
include('../modelo/generoM.php');
include('../modelo/coloresM.php');
include('../modelo/proyectosM.php');
include('../modelo/localizacionM.php');
include('../modelo/custodioM.php');

/**
 * 
 */
$controlador = new actualizar_datos();
if(isset($_GET['plantilla']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->actualizar_datos($parametros));
	

	//echo json_encode($controlador->actualizacion());
}

class actualizar_datos
{
	private $marcas;
	private $estado;
	private $genero;
	private $color;
	private $proyecto;
	private $localizacion;
	private $custodio;
	private $db;
	
	function __construct()
	{
		$this->marcas = new marcasM();
		$this->estado = new estadoM();
		$this->genero = new generoM();
		$this->color = new coloresM();
		$this->proyecto = new proyectosM();
		$this->localizacion = new  localizacionM();
		$this->custodio = new  custodioM();
		$this->db = new db();
	}

	function actualizar_datos($parametros)
	{
		if ($parametros['parte']==1) 
		{
			$nombreArchivo = '';
			if ($parametros['id']==1) {
				$nombreArchivo = 'plantilla_masiva.xlsx';
			}elseif ($parametros['id']==2) {
				$nombreArchivo = 'colores_act.xlsx';
			}elseif ($parametros['id']==3) {
				$nombreArchivo = 'custodio_act.xlsx';
			}elseif ($parametros['id']==4) {
				$nombreArchivo = 'estado_act.xlsx';
			}elseif ($parametros['id']==5) {
				$nombreArchivo = 'genero_act.xlsx';
			}elseif ($parametros['id']==6) {
				$nombreArchivo = 'emplazamiento_act.xlsx';
			}elseif ($parametros['id']==7) {
				$nombreArchivo = 'marcas.xlsx';
			}elseif ($parametros['id']==8) {
				$nombreArchivo = 'proyecto_act.xlsx';
			}
			 	
	        // $nombreArchivo = 'ejemplo.xlsx';
			 $doc = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
			 $objPHPExcel = $doc->load($nombreArchivo);
			 $objPHPExcel->setActiveSheetIndex(0);
			 $numRows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
	         $partes = 0;
	         $ini=0;
	         $fin =0;
	         if($numRows > 20000)
	         {
	    	     $partes = ($numRows/20000); 
	         }else
	         {
	         	$partes = 1;
	         }
	         if(is_float($partes))
	          {
	     	     $partes = intval($partes)+1;
	     	     $ini = 2 ;
	     	     $fin = 20002;
	          }else
	          {
	          	$ini = 2 ;
	     	    $fin = $numRows;
	          }
	          if ($parametros['id']==1) {
	          	 $res =  $this->plantilla_masiva($parametros['parte'],$partes,$numRows,$ini,$fin);
	             return $res;
	          }elseif ($parametros['id']==2) {
	          	 $res =  $this->colores($parametros['parte'],$partes,$numRows,$ini,$fin+1);
	             return $res;
	          }elseif ($parametros['id']==3) {
	          	 $res =  $this->custodio($parametros['parte'],$partes,$numRows,$ini,$fin+1);
	             return $res;
	          }elseif ($parametros['id']==4) {
	          	 $res =  $this->estado($parametros['parte'],$partes,$numRows,$ini,$fin+1);
	             return $res;
	          }elseif ($parametros['id']==5) {
	          	 $res =  $this->genero($parametros['parte'],$partes,$numRows,$ini,$fin+1);
	             return $res;
	          }elseif ($parametros['id']==6) {
	          	 $res =  $this->emplazamiento($parametros['parte'],$partes,$numRows,$ini,$fin+1);
	             return $res;
	          }elseif ($parametros['id']==7) {
	          	 $res =  $this->marcas($parametros['parte'],$partes,$numRows,$ini,$fin+1);
	             return $res;
	          }elseif ($parametros['id']==8) {
	          	 $res =  $this->proyectos($parametros['parte'],$partes,$numRows,$ini,$fin+1);
	             return $res;
	          }
	         

		}else
		{
			if($parametros['parte']==$parametros['partes'])
			{
				$ini = $parametros['fin'];
				$fin = $parametros['regis'];
				 if ($parametros['id']==1) 
				 {
				 	$res =  $this->plantilla_masiva($parametros['parte'],$parametros['partes'],$parametros['regis'],$ini,$fin+1);
				 	return $res;
				 }elseif ($parametros['id']==2) 
				 {
				 	$res =  $this->colores($parametros['parte'],$parametros['partes'],$parametros['regis'],$ini,$fin+1);
				 	return $res;
	             }elseif ($parametros['id']==3) 
				 {
				 	$res =  $this->custodio($parametros['parte'],$parametros['partes'],$parametros['regis'],$ini,$fin+1);
				 	return $res;
	             }elseif ($parametros['id']==4) 
				 {
				 	$res =  $this->estado($parametros['parte'],$parametros['partes'],$parametros['regis'],$ini,$fin+1);
				 	return $res;
	             }elseif ($parametros['id']==5) 
				 {
				 	$res =  $this->genero($parametros['parte'],$parametros['partes'],$parametros['regis'],$ini,$fin+1);
				 	return $res;
	             }elseif ($parametros['id']==6) 
				 {
				 	$res =  $this->emplazamiento($parametros['parte'],$parametros['partes'],$parametros['regis'],$ini,$fin+1);
				 	return $res;
	             }elseif ($parametros['id']==7) 
				 {
				 	$res =  $this->marcas($parametros['parte'],$parametros['partes'],$parametros['regis'],$ini,$fin+1);
				 	return $res;
	             }elseif ($parametros['id']==8) 
				 {
				 	$res =  $this->proyectos($parametros['parte'],$parametros['partes'],$parametros['regis'],$ini,$fin+1);
				 	return $res;
	             }

				
			}else
			{
				$ini = intval($parametros['fin']);
				$fin = intval($parametros['fin'])+20000;
				 if ($parametros['id']==1) 
				 {
				 	$res =  $this->plantilla_masiva($parametros['parte'],$parametros['partes'],$parametros['regis'],$ini,$fin);
				 	return $res;
				 }elseif ($parametros['id']==2) 
				 {
				 	$res =  $this->colores($parametros['parte'],$parametros['partes'],$parametros['regis'],$ini,$fin);
				 	return $res;
	             }elseif ($parametros['id']==3) {
	          	 $res =  $this->custodio($parametros['parte'],$partes,$numRows,$ini,$fin);
	             return $res;
	          }elseif ($parametros['id']==4) {
	          	 $res =  $this->estado($parametros['parte'],$partes,$numRows,$ini,$fin);
	             return $res;
	          }elseif ($parametros['id']==5) {
	          	 $res =  $this->genero($parametros['parte'],$partes,$numRows,$ini,$fin);
	             return $res;
	          }elseif ($parametros['id']==6) {
	          	 $res =  $this->emplazamiento($parametros['parte'],$partes,$numRows,$ini,$fin);
	             return $res;
	          }elseif ($parametros['id']==7) {
	          	 $res =  $this->marcas($parametros['parte'],$partes,$numRows,$ini,$fin);
	             return $res;
	          }elseif ($parametros['id']==8) {
	          	 $res =  $this->proyectos($parametros['parte'],$partes,$numRows,$ini,$fin);
	             return $res;
	          }
				

			}

		}
	}


	function colores($part,$partes,$totalReg,$ini,$fin)
	{
	ini_set('memory_limit', '-1');
	$respuesta = '';
	$nombreArchivo = 'colores_act.xlsx';	
	$doc = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
	$objPHPExcel = 	$doc->load($nombreArchivo);
	$objPHPExcel->setActiveSheetIndex(0);
	
	$campos = " UPDATE COLORES SET ESTADO = 'I'";
	$dato = array();
	$count = 0;
	$insert ='';
	for($i = $ini; $i < $fin; $i++)
	{
		$count = $count+1;
		
		$codigo = $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getCalculatedValue();
		$desc = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('B'.$i)->getCalculatedValue());
		$dato[] = array('Codigo' => $codigo,'Descrip'=>$desc);

		
	}


	   foreach ($dato as $key => $value) {
	   	$existe = $this->color->buscar_colores_codigo($value['Codigo']);
	   	if(count($existe)>0)
	   	{
	   		$campos.= " UPDATE COLORES SET ESTADO = 'A', DESCRIPCION = '".$value['Descrip']."' WHERE CODIGO = '".$value['Codigo']."'";

	   	}else
	    {
	    	$campos.= " INSERT INTO COLORES (CODIGO,DESCRIPCION)VALUES('".$value['Codigo']."','".$value['Descrip']."')";
	    }
	   }

		if($part==1)
		{
		  // $sql = " DELETE FROM COLORES; DBCC CHECKIDENT (COLORES, RESEED, 0); ".$insert;
		   //print_r($sql);die();
            $ret = $this->db->sql_string($campos);
			$ret = 1;
           $res = array('parte' => $part,'partes'=>$partes,'TotalReg'=>$totalReg,'respuesta'=>$ret,'observaciones'=>$respuesta,'fin'=>$fin);
		   return $res;
        }else
        {
        	//print_r($insert);die();
        	$ret = $this->db->sql_string($insert);
        	$res = array('parte' => $part,'partes'=>$partes,'TotalReg'=>$totalReg,'respuesta'=>$ret,'observaciones'=>$respuesta,'fin'=>$fin);
		   return $res;
        }
    
	}



	function marcas($part,$partes,$totalReg,$ini,$fin)
	{
	ini_set('memory_limit', '-1');
	$respuesta = '';
	$nombreArchivo = 'marcas_act.xlsx';	
	$doc = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
	$objPHPExcel = 	$doc->load($nombreArchivo);
	$objPHPExcel->setActiveSheetIndex(0);
	
	$campos = " UPDATE MARCAS SET ESTADO = 'I'";
	$dato = array();
	$count = 0;
	$insert ='';
	for($i = $ini; $i < $fin; $i++)
	{
		$count = $count+1;
		
		$codigo = $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getCalculatedValue();
		$desc = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('B'.$i)->getCalculatedValue());
		$dato[] = array('Codigo' => $codigo,'Descrip'=>$desc);


	}
	   $count = 1;
	   foreach ($dato as $key => $value) {
	   	$existe = $this->marcas->buscar_marcas_codigo($value['Codigo']);
	   	if(count($existe)>0)
	   	{
	   		$campos.= " UPDATE MARCAS SET ESTADO = 'A', DESCRIPCION = '".$value['Descrip']."' WHERE CODIGO = '".$value['Codigo']."'";

	   	}else
	    {
	    	$campos.= " INSERT INTO MARCAS (CODIGO,DESCRIPCION)VALUES('".$value['Codigo']."','".$value['Descrip']."')";
	    }
	     if($count==300)
	    {
	    	$ret = $this->db->sql_string($campos);
	    	$campos='';
	    	$count = 0;
	    }
	    $count = $count+1;
	   }
	  

		if($part==1)
		{
		   //$sql = " DELETE FROM MARCAS; DBCC CHECKIDENT (MARCAS, RESEED, 0); ".$insert;
		   //print_r($sql);die();
           $ret = $this->db->sql_string($campos);
			$ret = 1;
           $res = array('parte' => $part,'partes'=>$partes,'TotalReg'=>$totalReg,'respuesta'=>$ret,'observaciones'=>$respuesta,'fin'=>$fin);
		   return $res;
        }else
        {
        	//print_r($insert);die();
        	$ret = $this->db->sql_string($insert);
        	$res = array('parte' => $part,'partes'=>$partes,'TotalReg'=>$totalReg,'respuesta'=>$ret,'observaciones'=>$respuesta,'fin'=>$fin);
		   return $res;
        }
    
	}

	function emplazamiento($part,$partes,$totalReg,$ini,$fin)
	{
	ini_set('memory_limit', '-1');
	$respuesta = '';
	$nombreArchivo = 'emplazamiento_act.xlsx';	
	$doc = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
	$objPHPExcel = 	$doc->load($nombreArchivo);
	$objPHPExcel->setActiveSheetIndex(0);
	
	$campos = " UPDATE LOCATION SET ESTADO = 'I';";
	$dato = array();
	$count = 0;
	$insert ='';
	for($i = $ini; $i < $fin; $i++)
	{
		$count = $count+1;

		$centro = $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getCalculatedValue();
		$emplazamiento = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('B'.$i)->getCalculatedValue());
		$denominacion = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('C'.$i)->getCalculatedValue());
		$fami = substr($emplazamiento, 0,5);
		$subfami = substr($emplazamiento, 5);
		$dato[] = array('centro' => $centro,'empla'=>$emplazamiento,'deno'=>$denominacion,'fami'=>$fami,'sub'=>$subfami);

	}
		$count = 1;
	    foreach ($dato as $key => $value) {
	   	$existe = $this->localizacion->buscar_localizacion_codigo($value['empla']);
	   	if(count($existe)>0)
	   	{
	   	$campos.= "UPDATE LOCATION SET CENTRO='".$value['centro']."',DENOMINACION='".$value['deno']."',FAMILIA='".$value['fami']."',SUBFAMILIA='".$value['sub']."',ESTADO = 'A' WHERE EMPLAZAMIENTO = '".$value['empla']."';";	   

	   	}else
	    {
	    	$campos.= " INSERT INTO (CENTRO,EMPLAZAMIENTO,DENOMINACION,FAMILIA,SUBFAMILIA) VALUES ('".$value['centro']."','".$value['empla']."','".$value['deno']."','".$value['fami']."','".$value['sub']."');";
	    }
	    if($count==300)
	    {
	    	$ret = $this->db->sql_string($campos);
	    	$campos='';
	    	$count = 0;
	    }
	    $count = $count+1;
    
     }

		if($part==1)
		{
		  
		   //print_r($campos);die();
           $ret = $this->db->sql_string($campos);
            //print_r($ret);
			$ret = 1;
           $res = array('parte' => $part,'partes'=>$partes,'TotalReg'=>$totalReg,'respuesta'=>$ret,'observaciones'=>$respuesta,'fin'=>$fin);
		   return $res;
        }else
        {
        	//print_r($insert);die();
        	$ret = $this->db->sql_string($insert);
        	$res = array('parte' => $part,'partes'=>$partes,'TotalReg'=>$totalReg,'respuesta'=>$ret,'observaciones'=>$respuesta,'fin'=>$fin);
		   return $res;
        }
    
	}

function custodio($part,$partes,$totalReg,$ini,$fin)
	{
	ini_set('memory_limit', '-1');
	$respuesta = '';
	$nombreArchivo = 'custodio_act.xlsx';	
	$doc = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
	$objPHPExcel = 	$doc->load($nombreArchivo);
	$objPHPExcel->setActiveSheetIndex(0);
	
	$campos = " UPDATE PERSON_NO SET ESTADO = 'I';";
	$dato = array();
	$count = 0;
	$insert ='';
	for($i = $ini; $i < $fin; $i++)
	{
		$NO = $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getCalculatedValue();
		$CI = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('B'.$i)->getCalculatedValue());
		$NOM = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('C'.$i)->getCalculatedValue());
		$PUE = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('D'.$i)->getCalculatedValue());
		$UNI =$this->validar_datos($objPHPExcel->getActiveSheet()->getCell('E'.$i)->getCalculatedValue());
		$COR = $objPHPExcel->getActiveSheet()->getCell('F'.$i)->getCalculatedValue();
		$dato[] = array('no' => $NO,'cedula'=>$CI,'nombre'=>$NOM,'puesto'=>$PUE,'unidad'=>$UNI,'correo'=>$COR);

	}
		$count = 1;
	    foreach ($dato as $key => $value) {
	   	$existe = $this->custodio->buscar_custodio_($value['no']);
	   	if(count($existe)>0)
	   	{
	   	$campos.= "UPDATE PERSON_NO SET PERSON_NOM='".$value['nombre']."',PERSON_CI='".$value['cedula']."',PERSON_CORREO='".$value['correo']."',PUESTO='".$value['puesto']."',UNIDAD_ORG='".$value['unidad']."',ESTADO='A' WHERE PERSON_NO ='".$value['no']."';";	   

	   	}else
	    {
	    	$campos.= "INSERT INTO PERSON_NO (PERSON_NO,PERSON_NOM,PERSON_CI,PERSON_CORREO,PUESTO,UNIDAD_ORG) VALUES ('".$value['no']."','".$value['nombre']."','".$value['cedula']."','".$value['correo']."','".$value['puesto']."','".$value['correo']."');";
	    }
	    if($count==300)
	    {
	    	$ret = $this->db->sql_string($campos);
	    	$campos='';
	    	$count = 0;
	    }
	    $count = $count+1;
    
     }

		if($part==1)
		{
		  
		   //print_r($campos);die();
           $ret = $this->db->sql_string($campos);
            //print_r($ret);
			$ret = 1;
           $res = array('parte' => $part,'partes'=>$partes,'TotalReg'=>$totalReg,'respuesta'=>$ret,'observaciones'=>$respuesta,'fin'=>$fin);
		   return $res;
        }else
        {
        	//print_r($insert);die();
        	$ret = $this->db->sql_string($insert);
        	$res = array('parte' => $part,'partes'=>$partes,'TotalReg'=>$totalReg,'respuesta'=>$ret,'observaciones'=>$respuesta,'fin'=>$fin);
		   return $res;
        }
    
	}

function proyectos($part,$partes,$totalReg,$ini,$fin)
	{
	ini_set('memory_limit', '-1');
	$respuesta = '';
	$nombreArchivo = 'proyecto_act.xlsx';	
	$doc = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
	$objPHPExcel = 	$doc->load($nombreArchivo);
	$objPHPExcel->setActiveSheetIndex(0);
	
	$campos = " UPDATE PROYECTO SET ESTADO = 'I';";
	$dato = array();
	$count = 0;
	$insert ='';
	for($i = $ini; $i < $fin; $i++)
	{
		$codigo = $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getCalculatedValue();
		$entidad = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('B'.$i)->getCalculatedValue());
		$denominacion = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('C'.$i)->getCalculatedValue());
		$descripcion = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('D'.$i)->getCalculatedValue());
		$f1 =  $objPHPExcel->getActiveSheet()->getCell('E'.$i)->getCalculatedValue();
		if($f1 == "")
		{
			$f1= 'NULL';
		}else
		{
			$f1= \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($f1);
			$f1 = "'".$f1->format('Y-m-d')."'";			
		}
		
		$f2 =  $objPHPExcel->getActiveSheet()->getCell('F'.$i)->getCalculatedValue();
		if($f2 == "")
		{
			$f2= 'NULL';
		}else
		{
			$f2= \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($f2);
			$f2 = "'".$f2->format('Y-m-d')."'";			
		}

		$f3 =  $objPHPExcel->getActiveSheet()->getCell('G'.$i)->getCalculatedValue();
		if($f3 == "")
		{
			$f3= 'NULL';
		}else
		{
			$f3= \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($f3);
			$f3 = "'".$f3->format('Y-m-d')."'";			
		}


		$dato[] = array('codigo' => $codigo,'entidad'=>$entidad,'deno'=>$denominacion,'desc'=>$descripcion,'f1'=>$f1,'f2'=>$f2,'f3'=>$f3);

	}
		$count = 1;
	    foreach ($dato as $key => $value) {
	   	$existe = $this->proyecto->buscar_proyecto_programa($value['codigo']);
	   	if(count($existe)>0)
	   	{
	   	$campos.= "UPDATE PROYECTO SET entidad_cp='".$value['entidad']."',denominacion='".$value['deno']."',descripcion='".$value['desc']."',validez_de=".$value['f1'].",validez_a=".$value['f2'].",expiracion=".$value['f3'].",ESTADO='A' WHERE programa_financiacion ='".$value['codigo']."';";	   

	   	}else
	    {
	    	$campos.= "INSERT INTO PROYECTO (programa_financiacion,entidad_cp,denominacion,descripcion,validez_de,validez_a,expiracion) VALUES ('".$value['codigo']."','".$value['entidad']."','".$value['deno']."','".$value['desc']."',".$value['f1'].",".$value['f2'].",".$value['f3'].");";
	    }
	    if($count==300)
	    {
	    	$ret = $this->db->sql_string($campos);
	    	$campos='';
	    	$count = 0;
	    }
	    $count = $count+1;
    
     }

		if($part==1)
		{
		  
		   //print_r($campos);die();
           $ret = $this->db->sql_string($campos);
            //print_r($ret);
			$ret = 1;
           $res = array('parte' => $part,'partes'=>$partes,'TotalReg'=>$totalReg,'respuesta'=>$ret,'observaciones'=>$respuesta,'fin'=>$fin);
		   return $res;
        }else
        {
        	//print_r($insert);die();
        	$ret = $this->db->sql_string($insert);
        	$res = array('parte' => $part,'partes'=>$partes,'TotalReg'=>$totalReg,'respuesta'=>$ret,'observaciones'=>$respuesta,'fin'=>$fin);
		   return $res;
        }
    
	}


function genero($part,$partes,$totalReg,$ini,$fin)
	{
	ini_set('memory_limit', '-1');
	$respuesta = '';
	$nombreArchivo = 'genero_act.xlsx';	
	$doc = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
	$objPHPExcel = 	$doc->load($nombreArchivo);
	$objPHPExcel->setActiveSheetIndex(0);
	
	$campos = " UPDATE GENERO SET ESTADO = 'I';";
	$dato = array();
	$count = 0;
	$insert ='';
	for($i = $ini; $i < $fin; $i++)
	{
		$codigo = $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getCalculatedValue();
		$desc = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('B'.$i)->getCalculatedValue());
		$dato[] = array('Codigo' => $codigo,'Descrip'=>$desc);


	}
	   $count = 1;
	   foreach ($dato as $key => $value) {
	   	$existe = $this->genero->buscar_genero_CODIGO($value['Codigo']);
	   	if(count($existe)>0)
	   	{
	   		$campos.= " UPDATE GENERO SET ESTADO = 'A', DESCRIPCION = '".$value['Descrip']."' WHERE CODIGO = '".$value['Codigo']."'";

	   	}else
	    {
	    	$campos.= " INSERT INTO GENERO (CODIGO,DESCRIPCION)VALUES('".$value['Codigo']."','".$value['Descrip']."')";
	    }
	     if($count==300)
	    {
	    	$ret = $this->db->sql_string($campos);
	    	$campos='';
	    	$count = 0;
	    }
	    $count = $count+1;
	   }
	  

		if($part==1)
		{
		   //$sql = " DELETE FROM MARCAS; DBCC CHECKIDENT (MARCAS, RESEED, 0); ".$insert;
		   //print_r($sql);die();
           $ret = $this->db->sql_string($campos);
			$ret = 1;
           $res = array('parte' => $part,'partes'=>$partes,'TotalReg'=>$totalReg,'respuesta'=>$ret,'observaciones'=>$respuesta,'fin'=>$fin);
		   return $res;
        }else
        {
        	//print_r($insert);die();
        	$ret = $this->db->sql_string($insert);
        	$res = array('parte' => $part,'partes'=>$partes,'TotalReg'=>$totalReg,'respuesta'=>$ret,'observaciones'=>$respuesta,'fin'=>$fin);
		   return $res;
        }
    
	}


	function estado($part,$partes,$totalReg,$ini,$fin)
	{
		
	ini_set('memory_limit', '-1');
	$respuesta = '';
	$nombreArchivo = 'estado_act.xlsx';	
	$doc = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
	$objPHPExcel = 	$doc->load($nombreArchivo);
	$objPHPExcel->setActiveSheetIndex(0);
	
	$campos = " UPDATE ESTADO SET ESTADO = 'I';";
	$dato = array();
	$count = 0;
	$insert ='';
	for($i = $ini; $i < $fin; $i++)
	{
		$codigo = $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getCalculatedValue();
		$desc = $this->validar_datos($objPHPExcel->getActiveSheet()->getCell('B'.$i)->getCalculatedValue());
		$dato[] = array('Codigo' => $codigo,'Descrip'=>$desc);


	}
	   $count = 1;
	   foreach ($dato as $key => $value) {
	   	$existe = $this->estado->buscar_estado_CODIGO($value['Codigo']);
	   	if(count($existe)>0)
	   	{
	   		$campos.= " UPDATE ESTADO SET ESTADO = 'A', DESCRIPCION = '".$value['Descrip']."' WHERE CODIGO = '".$value['Codigo']."'";

	   	}else
	    {
	    	$campos.= " INSERT INTO ESTADO (CODIGO,DESCRIPCION)VALUES('".$value['Codigo']."','".$value['Descrip']."')";
	    }
	     if($count==300)
	    {
	    	$ret = $this->db->sql_string($campos);
	    	$campos='';
	    	$count = 0;
	    }
	    $count = $count+1;
	   }
	  

		if($part==1)
		{
		   //$sql = " DELETE FROM MARCAS; DBCC CHECKIDENT (MARCAS, RESEED, 0); ".$insert;
		   //print_r($sql);die();
           $ret = $this->db->sql_string($campos);
			$ret = 1;
           $res = array('parte' => $part,'partes'=>$partes,'TotalReg'=>$totalReg,'respuesta'=>$ret,'observaciones'=>$respuesta,'fin'=>$fin);
		   return $res;
        }else
        {
        	//print_r($insert);die();
        	$ret = $this->db->sql_string($insert);
        	$res = array('parte' => $part,'partes'=>$partes,'TotalReg'=>$totalReg,'respuesta'=>$ret,'observaciones'=>$respuesta,'fin'=>$fin);
		   return $res;
        }
    
	}







	function validar_datos($texto)
	{
		$buscar = array('á','é','í','ó','ú','ñ','Ñ','Á','É','Í','Ó','Ú',',',':',';',"'",'"' );
		$remplazar = array('a','e','i','o','u','n','N','A','E','I','O','U','','','','','' );
		$texto_new = str_replace($buscar,$remplazar,$texto);
		return $texto_new;
	}




}
?> 