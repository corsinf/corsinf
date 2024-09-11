<?php 
$controlador = new validar_firmaC();
if(isset($_GET['validar_firma']))
{

	$parametros = $_POST;
	$p12 = $_FILES;
	echo json_encode($controlador->validar_firma($p12,$parametros));
	// print_r($_FILES);
	// print_r($_POST);die();
}
if(isset($_GET['validar_documento']))
{

	$parametros = $_POST;
	$p12 = $_FILES;
	echo json_encode($controlador->validar_documento($p12,$parametros));
	// print_r($_FILES);
	// print_r($_POST);die();
}
if(isset($_GET['firmar_documento']))
{
	$parametros = $_POST;
	$doc = $_FILES;
	echo json_encode($controlador->firmar_documento($doc,$parametros));
}

/**
 * 
 */
class validar_firmaC
{
	private $rutaTemp;	
	function __construct()
	{
		$this->rutaTemp = dirname(__DIR__,2).'/TEMP/';
		// code...
	}

	function validar_firma($file,$parametros)
	{
		
		  $ruta_p12 = '';
        $uploadfile_temporal=$file['txt_cargar_imagen']['tmp_name'];
        $nombre = $file['txt_cargar_imagen']['name'];     
        $ruta_p12=$this->rutaTemp.str_replace(' ','_',$nombre);
        // print_r($ruta_p12);die();
        if(is_uploaded_file($uploadfile_temporal))
        {
           move_uploaded_file($uploadfile_temporal,$ruta_p12);
        }

       	$rutaJar = dirname(__DIR__,2).'/lib/firmarPdf/FirmarPDF.jar';
       	// $param = array('2',$ruta_p12,$parametros['txt_ingresarClave']);

       	// $param = json_encode($param);

       	$comando = "java -jar $rutaJar 2 $ruta_p12 ".$parametros['txt_ingresarClave'];
       	$respuesta = shell_exec($comando);
       	if(!isset($parametros['mantener']) || $parametros['mantener']==0)
       	{
       		unlink($ruta_p12);
       	}

       	$resp = json_decode($respuesta);
       	if(count($resp)>0)
       	{
       		return array('resp'=>$resp[0],'msj'=>$resp[1]);
       	}
	}

	function validar_documento($file,$parametros)
	{
		$ruta_doc = '';

      $uploadfile_temporal=$file['txt_cargar_imagen']['tmp_name'];
      $nombre = $file['txt_cargar_imagen']['name'];     
      $ruta_doc=$this->rutaTemp.str_replace(' ','_',$nombre);
      // print_r($ruta_p12);die();
      if (is_uploaded_file($uploadfile_temporal))
      {
         move_uploaded_file($uploadfile_temporal,$ruta_doc);
      }

      $rutaJar = dirname(__DIR__,2).'/lib/firmarPdf/FirmarPDF.jar';
    	// $param = array('2',$ruta_p12,$parametros['txt_ingresarClave']);

    	// $param = json_encode($param);

    	$comando = "java -jar $rutaJar 3 $ruta_doc ";
    	$respuesta = shell_exec($comando);

    	$resp = json_decode($respuesta);
    	if(count($resp)>0)
    	{
    		if($resp[0]==1)
    		{
    			$firmas = json_decode($resp[1],true);
    			// print_r($firmas);die();
    			$tr='';
    			foreach ($firmas as $key => $value) {
    				$ci='';
    				if(isset($value['SERIALNUMBER']))
    				{
    					$ci = $value['SERIALNUMBER'];
    					$serie = explode('-',$value['SERIALNUMBER']); 
    					if(count($serie)>1){$ci = $serie[0];}
    				}
    				$tr.='<tr>
    							<td>'.$ci.'</td>
    							<td>'.str_replace($ci,"",$value['CN']).'</td>
    							<td>'.$value['EMC_O'].'</td>
    							<td>'.$value['FechaFirma'].'</td>
    						</tr>';
    			}
    			return array('resp'=>$resp[0],'msj'=>'','tr'=>$tr);
    		}else
    		{
    			return array('resp'=>$resp[0],'msj'=>$resp[1],'tr'=>'');
    		}
    	}

		print_r($ruta_p12);
		print_r($parametros);die();

	}


	function firmar_documento($file,$parametros)
	{


		// valida que la firma sea correta
		$parametros['txt_ingresarClave'] = $parametros['txt_passFirma'];
		$parametros['mantener'] = 1;
		$file2['txt_cargar_imagen'] = $file['uploadFirma'];
		$firma_valida = $this->validar_firma($file2,$parametros);

		if($firma_valida['resp']==-1)
		{
			$resp = array('resp'=>-2,'ruta'=>'');
			return $resp;
		}


		// carga datos para firmar el documento
		$datos_firmas = json_decode($parametros['insertedImages'],true);
		$ruta_doc = '';
		$ruta_p12 = $this->rutaTemp.str_replace(" ","_", $file['uploadFirma']['name']);
		$pass_p12 = $parametros['txt_passFirma'];;		
    	


// print_r($parametros);die();
      $uploadfile_temporal=$file['uploadPDF']['tmp_name'];
      $nombre = $file['uploadPDF']['name'];     
      $ruta_doc=$this->rutaTemp.str_replace(' ','_',$nombre);
      // print_r($ruta_p12);die();
      if (is_uploaded_file($uploadfile_temporal))
      {
         move_uploaded_file($uploadfile_temporal,$ruta_doc);
      }


      $rutaJar = dirname(__DIR__,2).'/lib/firmarPdf/FirmarPDF.jar';

      // print_r($datos_firmas);die();

    	foreach ($datos_firmas as $key => $value) {

    		$ruta_final = $this->rutaTemp.'Firmado_'.$key.'_'.str_replace(' ','_',$nombre);
    		$pag = $value['page'];

    		$camvas_x = $value['canvasX'];
    		$camvas_y = $value['canvasY'];

    		$x = intval($value['x']+45);
   	 	$y = $camvas_y - intval($value['y']+45);
   	 	


   	 	//ejecuta con los parametros 
    		$comando = "java -jar $rutaJar 1 $ruta_p12 $pass_p12 $ruta_doc $ruta_final $x $y $pag";
    		// print_r($comando);
    		$respuesta = shell_exec($comando);	    		
    		$this->delete_update_pdf($ruta_doc);
    		$ruta_doc = $ruta_final;
    	} 

    	$resp = json_decode($respuesta,true);

    	// print_r($resp);die();   


    	$resp = array('resp'=>1,'ruta'=>str_replace(dirname(__DIR__,2),"..",$ruta_doc));
// print_r($resp);die();
    	return $resp;

	}


	function delete_update_pdf($archivo_a_eliminar)
	{
		// Eliminar el archivo PDF
		if (file_exists($archivo_a_eliminar)) {
		    if (unlink($archivo_a_eliminar)) {
		       return 1;
		    } else {
		       return -1;
		    }
		}

		
	}
}
?>