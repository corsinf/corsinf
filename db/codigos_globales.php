<?php
@session_start();
if(!class_exists('db'))
{
 	require_once('../db/db.php');
}
if(!class_exists('codigos_globales'))
{
	require_once('../db/codigos_globales.php');
}
/**
 * 
 */
class codigos_globales
{
	private $db;
	private $CodAES;
	private $iv;
	function __construct()
	{
		$this->db = new db();

		//AES-128
		$this->CodAES = 'corsinf-encrypted-Data/*';
		$this->iv = 'Data/*cors¡nf47';
	}


	function transformar_array_encode($arr) {
		foreach($arr as $key => $value) {
			if(!is_object($value))
				{
			     
			      $arr[$key] = utf8_encode($value);
			    }else
			    {			    	
			      $arr[$key] = $value->format('Y-m-d');       
			    }
		}
		return $arr;
	}

	// regitra una pagina creada en la base de datos en la tabla paginas para que aparesca en el menu,esta funcion debe estar en el controlador 
	//	ejemplo:
	// function __construct()
	// {		
	// 	$this->pagina->registrar_pagina_creada('../vista/articulos.php','Articulos','2','estado');		
	// }
	function registrar_pagina_creada($link,$nombre,$modulo,$estado)
	{
		$sql = "SELECT * FROM paginas WHERE nombre_pagina='".$nombre."' AND link_pagina='".$link."'";
		if($modulo!='')
		{
			$sql.=" AND id_modulo = '".$modulo."'";
		}

		$resp = $this->db->existente($sql);
		if($resp==false)
		{
			$sql = "SELECT * FROM paginas WHERE  link_pagina='".$link."'";
		            if($modulo!=''){ $sql.=" AND id_modulo = '".$modulo."'";}
		    $resp = $this->db->existente($sql);
		    if($resp==false)
		    {	
			     if($modulo !='')
			     {
			     $sql2 = "INSERT INTO paginas (nombre_pagina,link_pagina,id_modulo) VALUES('".$nombre."','".$link."',".$modulo.")";				
			     }else
			     {

			     $sql2 = "INSERT INTO paginas (nombre_pagina,link_pagina) VALUES('".$nombre."','".$link."')";
			     }
			 $this->db->sql_string($sql2);
			$resp2 = $this->db->datos($sql);
			$id_pag = $resp2[0]['id_paginas'];
			$sql3 = "INSERT INTO accesos (id_paginas,id_tipo_usuario) VALUES('".$id_pag."','1')";
			$this->db->sql_string($sql3);	
		    }else
		    {
		    	 if($modulo !='')
			     {
			     $sql2 = "UPDATE paginas SET nombre_pagina='".$nombre."',id_modulo='".$modulo."' WHERE link_pagina = '".$link."' ";				
			     }else
			     {

			     $sql2 = "UPDATE paginas SET nombre_pagina='".$nombre."' WHERE link_pagina = '".$link."'";
			     }

			$this->db->sql_string($sql2);
		    }

			// print_r($sql2);die();
			// $this->db->sql_string($sql2);
			// $resp2 = $this->db->datos($sql);
			// $id_pag = $resp2[0]['id_paginas'];
			// $sql3 = "INSERT INTO accesos (id_paginas,id_tipo_usuario) VALUES('".$id_pag."','1')";
			// $this->db->sql_string($sql3);		

		}

	}



	/*
	para usar tabla generica

	--cabecera
	 $cabecera = array('#','Referencia','Detalle','Categoria','Precio','','Foto','Fecha');

	--botones lado derecho
		-- seran los que se  agregaran linea por linea en cada fila
		 $botones[0]['boton']='Editar'; // se creara un tittle y un evento onclick con este nombre no importal los espacios
		 $botones[0]['icono']='<i class="fas fa-save nav-icon"></i>';
		 $botones[0]['tipo']='primary'; //color color de boton
		 $botones[0]['id']='id'; // campo que se debe colocar de la consulta sql
		 $botones[1]['boton']='Eliminar';
		 $botones[1]['icono']='<i class="fas fa-trash nav-icon"></i>';
		 $botones[1]['tipo']='danger';	
		 $botones[1]['id']='id';
    --checkbox
        --el primer id es el nombre de la matriz el segundo es de la consulta sql
		 $chek=array('id'=>'id');

    --ocultar campos de la tabla
		 $ocultar = array('id','ref');  se debera colocar los nombres de los campos de la cnsulta sql
	--tipo foto
	    parametos foto = nombre del campo que se trae de una consulta sql 
	    25px = width
	    30px = heigth
		$foto[0] = array('foto','25px','30px');


		 $datos -> son los datos traidos de una consulta sql;
	*/

	function tabla_generica($datos=false,$cabecera,$opciones=false,$checks=false,$ocultar = false,$foto=false,$posicion=false,$enlace=false)
	{
		$num_campos = 0;
		$td='';
		$alineado ='text-left';
		if($datos)
		{
		foreach ($datos as $key => $value) {

			  $td.='<tr>';
			   if($checks)
			    {
			    	if(key($value)==$checks['id'])
			    	{
			    	$td.='<td width="10px" class="'.$lineado.'">
				    <input type="checkbox" name="rbl_'.$value[$checks['id']].'"/>
				    </td>';
			    	}
			    }

			    $num_reg = count($cabecera);

			    $num_reg_l = count($value);

			foreach ($value as $key2 => $value2) {
				if($num_reg_l == $num_campos)
				{
					$num_campos = 0;
				}
				
				$style = '';
				if(is_object($value2))
				{
					$value2 = $value2->format('Y-m-d');
				}
				if(is_float($value2))
				{
					$value2 = number_format($value2,2);
				}
				$num = $this->dimenciones_tabl($value2);
				if($ocultar)
				{
				foreach ($ocultar as $key7 => $value7) {
					if($value7 == $key2)
					{
						$style = 'style="display:none"';
						$alineado = '';
				        break;
					}
				}
			   }
				
			    if($foto)
			    {
			    	foreach ($foto as $key8 => $value8) {
			    		if($value8[0]==$key2)
			    		{
			    			if(file_exists($value2))
			    			{
			    				$td.='<td width="40px" '.$style.' class="text-center"><img src="'.$value2.'?'.rand(1,1000).'" style="width:'.$value8[1].' ; height: '.$value8[2].'"></td>';
			    			}else
			    			{
			    				$td.='<td width="40px" '.$style.' class="text-center"><img src="../img/de_sistema/sin_imagen.png" style="width:'.$value8[1].' ; height: '.$value8[2].'"></td>';
			    			}
			    		}else
			    		{
			    			if($enlace){
			    				$posi = $num_campos;
			    	         if(isset($enlace[0]['get']))
			    	         {
			    	             if($enlace[0]['posicion']==$posi)
			    	             {
			    	    	          $var = explode(',',$enlace[0]['get']['nombre']);
			    	    	          $val = explode(',',$enlace[0]['get']['valor']);
			    	    	          $variables = '';
			    	    	          foreach ($var as $key => $value9){
			    	    	          	$variables.=  $value9.'='.$value[$val[$key]].'&';
			    	    	          }
			    	    	          $variables = substr($variables, 0,-1);	
			    	    	          $td.='<td width="'.$num.'" '.$style.' class="'.$alineado.'"><a href="'.$enlace[0]['link'].'?'.$variables.'">'.utf8_encode($value2).'</a></td>';
			                   }else
			                   {
			                     $td.='<td width="'.$num.'" '.$style.' class="'.$alineado.'">'.utf8_encode($value2).'</td>';
			                   }
			              }else
			              {
			     	         $td.='<td width="'.$num.'" '.$style.' class="'.$alineado.'"><a href="'.$enlace[0]['link'].'">'.utf8_encode($value2).'</a></td>';
			              }
			           }else
			           {
			    	       $td.='<td width="'.$num.'" '.$style.' class="'.$alineado.'">'.utf8_encode($value2).'</td>';
			           }

			    			// $td.='<td width="'.$num.'" '.$style.' lass="'.$alineado.'">'.$value2.'</td>';
			    		}
			    		
			    	}

			    }else
			    {
			    	// print_r($posicion);die();
			    	if($style != 'style="display:none"')
			    	{
			    	   if($posicion!=false && $posicion[$num_campos-1] =='R')
					   {
						   $alineado = 'text-right';
					   }else
					   {
						   $alineado = 'text-left';
					   }
					}

					if($enlace)
					{						   
			    	$posi = $num_campos;
			    	if(isset($enlace[0]['get']))
			    	{
			    	if($enlace[0]['posicion']==$posi)
			    	{
			    	  $var = explode(',',$enlace[0]['get']['nombre']);
			    	  $val = explode(',',$enlace[0]['get']['valor']);
			    	  $variables = '';
			    	  foreach ($var as $key => $value9){
			    	  	  $variables.=  $value9.'='.$value[$val[$key]].'&';

			    	  }
			    	  $variables = substr($variables, 0,-1);	  
			    	$td.='<td width="'.$num.'" '.$style.' class="'.$alineado.'"><a href="'.$enlace[0]['link'].'?'.$variables.'">'.utf8_encode($value2).'</a></td>';
			      }else
			      {			      
			    	$td.='<td width="'.$num.'" '.$style.' class="'.$alineado.'">'.utf8_encode($value2).'</td>';
			      }
			     }else
			     {
			     	$td.='<td width="'.$num.'" '.$style.' class="'.$alineado.'"><a href="'.$enlace[0]['link'].'">'.utf8_encode($value2).'</a></td>';
			     }
			    }else
			    {
			    	$td.='<td width="'.$num.'" '.$style.' class="'.$alineado.'">'.utf8_encode($value2).'</td>';
			    }

			    	// $td.='<td width="'.$num.'" '.$style.' class="'.$alineado.'">'.utf8_encode($value2).'</td>';
			    }
				
				$num_campos = $num_campos+1;
			}
			if($opciones)
				{
					$td.='<td with="10px">';
					foreach ($opciones as $key3 => $value3) {
						$valor = '';
						$tipo = 'default';
						$icono = '<i class="far fa-circle nav-icon"></i>';
						if(isset($value3['tipo']))
						{
							$tipo = $value3['tipo'];
						}
						if(isset($value3['icono']))
						{
							$icono = $value3['icono'];
						}
						$k = explode(',', $value3['id']);
						foreach ($k as $key4 => $value4) {

							$valor.="'".$value[$value4]."',";
						}
						if($valor!='')
						{
							$valor = substr($valor,0,-1);
						}
						$funcion = str_replace(' ','_', $value3['boton']);
						$td.='<button class="btn btn-sm btn-'.$tipo.'" onclick="'.$funcion.'('.$valor.')" title="'.$value3['boton'].'">'.$icono.'</button>';
					}
					$td.='</td>';
				}

			$td.='</tr>';
		}
	    }
		$th='<tr>';
		if($checks)
			{
				$th.='<th width="10px"></th>';
			}

		foreach ($cabecera as $key => $value) {		
		    if($posicion != false && $posicion[$key]=='R')
			    {
				    $alineado = 'text-right';
			    }else
			    {
			    	$alineado = 'text-left';
			    }	
			$th.='<th class = "'.$alineado.'">'.$value.'</th>';			
		}
		if($opciones)
		{
			$th.='<th width="10px"></th>';
		}
		$th.='</tr>';

		$tabla = '<div class="table-responsive"><table class="table table-bordered table table-sm table-active">';
		$tabla.='<thead class="table btn-secondary text-white">'.$th.'</thead>';
		$tabla.='<tbody>'.$td.'</tbody></table></div>';

		return $tabla;

	}

    //calcula el ancho de una celda dependiendo la cantidad de catracteres devolciendo un valor en pixels (px)
    function dimenciones_tabl($len)
    {
      $px = 8;
      $len = strlen($len);
      if($len > 60)
      {
        $val = 60*8;
        return $val.'px';
      }elseif ($len==1) {
         $val = ($len+2)*8;
        return $val.'px';
      }elseif ($len >= 10 And $len<=13){
         $val = ($len+2)*8;
        return $val.'px';
      }elseif ($len==10){
         $val = ($len+2)*8;
        return $val.'px';
      }elseif ($len>3 And $len <6) {
         $val = ($len+2)*8;
        return $val.'px';
      }elseif ($len==3){
         $val = ($len+2)*8;
        return $val.'px';
      }elseif ($len>13 And $len<60) {
         $val = ($len+2)*8;
       return $val.'px';
      }else
      {
         $val = ($len+2)*8;
        return $val.'px';
      }
    }

    // quita espacion y coloca guion bajo (_)
    function quitar_estacios($query)
    {
    	$cambiado = str_replace(' ','_', $query);
    	return $cambiado; 
    }

    // aumenta ceros aun numero dependiendo la extencion que se requiera
    // ingresa el numero y la cantidad de catracteres que se requiera
    function agregar_ceros($num,$valor)
    {
    	$ca = strlen($valor);
    	$num_c = $num-$ca;
    	$ceros = str_repeat('0',$num_c);
    	return $ceros.$valor;
    }


    // funcion que permite navegar con botones de derecha del teclado
    function derecha($parametros)
    {

    	if($parametros['pag']=='C' || $parametros['pag']=='P')
    	{
    		$sql ="SELECT id_cliente_prove as 'id'
    		       FROM cliente_proveedor
    		       WHERE id_cliente_prove=(SELECT max(id_cliente_prove) 
    		                               FROM cliente_proveedor 
    		                               WHERE id_cliente_prove < ".$parametros['id']." AND tipo = '".$parametros['pag']."' AND estado = 'A') 
    		                               AND tipo = '".$parametros['pag']."' ORDER BY id_cliente_prove DESC ";
    		$res = $this->db->datos($sql);
    		// print_r($sql);die();
    		if(count($res)>0)
    		{
    		$url = 'detalle_cliente.php?id='.$res[0]['id'];
    		if($parametros['pag']=='P')
    		{
    			$url = 'detalle_proveedores.php?id='.$res[0]['id'];
    		}
    		return $url;
    	   }else
    	   {
    	   	return -1;
    	   }

    	}elseif($parametros['pag']=='A')
    	{
    		$sql="SELECT id_producto as id
    		      FROM productos
    		      WHERE id_producto=( SELECT max(id_producto) 
    		                          FROM productos  
    		                          WHERE id_producto  < ".$parametros['id']." 
    		                          AND trabajo = 0  
    		                          AND materia_prima = 0 AND id_categoria is not NULL ) 
    		                          ORDER BY id_producto DESC";

    		$res = $this->db->datos($sql);
    		// print_r($sql);die();
    		if(count($res)>0)
    		{
    		$url = 'detalle_articulo.php?articulo='.$res[0]['id'];
    		return $url;
    	   }else
    	   {
    	   	return -1;
    	   }

    	}else if ($parametros['pag']=='U') {

    		// print_r($parametros);die();
    		$sql="SELECT id_usuario as id
		    		      FROM usuarios
		    		      WHERE id_usuario=( SELECT min(id_usuario) 
		    		                          FROM usuarios  
		    		                          WHERE id_usuario  >  ".$parametros['id']." AND estado_usuario='A' ) 
		    		                          ORDER BY id_usuario DESC";
    		
		      $res = $this->db->datos($sql);
		    		// print_r($sql);die();
		    		if(count($res)>0)
		    		{
		    		$url = 'detalle_usuario.php?usuario='.$res[0]['id'];
		    		return $url;
		    	   }else
		    	   {
		    	   	return -1;
		    	   }
    	}elseif($parametros['pag']=='M')
    	{
    		$sql="SELECT id_producto as id
    		      FROM productos
    		      WHERE id_producto=( SELECT max(id_producto) 
    		                          FROM productos  
    		                          WHERE id_producto  < ".$parametros['id']." 
    		                          AND trabajo = 0  
    		                          AND materia_prima = 1) 
    		                          ORDER BY id_producto DESC";

    		$res = $this->db->datos($sql);
    		// print_r($sql);die();
    		if(count($res)>0)
    		{
    		$url = 'detalle_materia.php?materia='.$res[0]['id'];
    		return $url;
    	   }else
    	   {
    	   	return -1;
    	   }

    	}
    }

    // funcion que permite navegar con botones de izquierda del teclado
    function izquierda($parametros)
    {

    	if($parametros['pag']=='C' || $parametros['pag']=='P')
    	{
    		$sql ="SELECT id_cliente_prove as 'id'
    		       FROM cliente_proveedor
    		       WHERE id_cliente_prove=(SELECT min(id_cliente_prove) 
    		                               FROM cliente_proveedor 
    		                               WHERE id_cliente_prove > ".$parametros['id']." AND tipo = '".$parametros['pag']."' AND estado = 'A') 
    		                               AND tipo = '".$parametros['pag']."' ORDER BY id_cliente_prove DESC ";
    		$res = $this->db->datos($sql);
    		// print_r($sql);die();
    		if(count($res)>0)
    		{
    		$url = 'detalle_cliente.php?id='.$res[0]['id'];
    		if($parametros['pag']=='P')
    		{
    			$url = 'detalle_proveedores.php?id='.$res[0]['id'];
    		}
    		return $url;
    	   }else
    	   {
    	   	return -1;
    	   }

    	}elseif($parametros['pag']=='A')
    	{
    		$sql="SELECT id_producto as id
    		      FROM productos
    		      WHERE id_producto=( SELECT min(id_producto) 
    		                          FROM productos  
    		                          WHERE id_producto  > ".$parametros['id']." 
    		                          AND trabajo = 0  
    		                          AND materia_prima = 0 AND id_categoria is not NULL ) 
    		                          ORDER BY id_producto DESC";

    		$res = $this->db->datos($sql);
    		// print_r($sql);die();
    		if(count($res)>0)
    		{
    		$url = 'detalle_articulo.php?articulo='.$res[0]['id'];
    		return $url;
    	   }else
    	   {
    	   	return -1;
    	   }

    	}elseif($parametros['pag']=='U') {
		    	
		    	$sql="SELECT id_usuario as id
    		      FROM usuarios
    		      WHERE id_usuario=( SELECT max(id_usuario) 
    		                          FROM usuarios  
    		                          WHERE id_usuario  <  ".$parametros['id']." AND estado_usuario='A' ) 
    		                          ORDER BY id_usuario DESC";
		      $res = $this->db->datos($sql);
		    		// print_r($sql);die();
		    		if(count($res)>0)
		    		{
		    		$url = 'detalle_usuario.php?usuario='.$res[0]['id'];
		    		return $url;
		    	   }else
		    	   {
		    	   	return -1;
		    	   }
    }elseif($parametros['pag']=='M')
    	{
    		$sql="SELECT id_producto as id
    		      FROM productos
    		      WHERE id_producto=( SELECT min(id_producto) 
    		                          FROM productos  
    		                          WHERE id_producto  > ".$parametros['id']." 
    		                          AND trabajo = 0  
    		                          AND materia_prima = 1 ) 
    		                          ORDER BY id_producto DESC";

    		$res = $this->db->datos($sql);
    		// print_r($sql);die();
    		if(count($res)>0)
    		{
    		$url = 'detalle_materia.php?materia='.$res[0]['id'];
    		return $url;
    	   }else
    	   {
    	   	return -1;
    	   }

    	}
 }

 function optimizar_imagen($origen, $destino, $calidad) 
 {
 	// print_r($origen);
 
      $info = getimagesize($origen);
      // print_r($info);die();

      if ($info['mime'] == 'image/jpeg'){
     $imagen = @imagecreatefromjpeg($origen);
      }
 
   else if ($info['mime'] == 'image/gif'){
     $imagen = @imagecreatefromgif($origen);
   }
 
   else if ($info['mime'] == 'image/png'){
     $imagen = @imagecreatefrompng($origen);
   }
 
 // print_r($imagen);die();
   imagejpeg($origen.'1', $destino, $calidad);
   
   return $destino;
   
}


//reduce tamaño de imagen
function reducir_img($rutaOrigen,$rutaDestino,$nombre,$calidad=20)
{
	$rutaImagenOriginal = $rutaOrigen;

	$info = getimagesize($rutaOrigen);
	// print_r($info);die();
	$rutaImagenComprimida = $rutaDestino.$nombre;
	$calidad = 20; // Valor entre 0 y 100. Mayor calidad, mayor peso

	switch ($info['mime']) {
		case 'image/jpeg':
			 $imagenOriginal = imagecreatefromjpeg($rutaImagenOriginal);
			 imagejpeg($imagenOriginal, $rutaImagenComprimida, $calidad);
			break;		
		case 'image/png':
		     // escarla de 0 a 9
			  $imagenOriginal = imagecreatefrompng($rutaImagenOriginal);
			 imagepng($imagenOriginal, $rutaImagenComprimida, $calidad=9);
			break;
		case 'image/gif':
			   $imagenOriginal = imagecreatefromgif($rutaImagenOriginal);
			 imagegif($imagenOriginal, $rutaImagenComprimida, $calidad);
			break;
		case 'image/jpg':
			 $imagenOriginal = imagecreatefromjpeg($rutaImagenOriginal);	
			 imagejpeg($imagenOriginal, $rutaImagenComprimida, $calidad);
			break;
	}
	
}

function para_ftp($nombre,$texto)
{
	if(!is_dir('../descargas/para_sap'))
		{
			mkdir('../descargas/para_sap',7777);
			$fh = fopen("../descargas/para_sap/".$nombre.".txt", 'w'); 
		}else
		{
			if(is_file('../descargas/para_sap/'.$nombre.'.txt'))
			{
				$fh = fopen("../descargas/para_sap/".$nombre.".txt", 'a'); 
			}else
			{
				$fh = fopen("../descargas/para_sap/".$nombre.".txt", 'w'); 
			}
		}
	fwrite($fh, $texto . PHP_EOL);
	fclose($fh);
}

	function ingresar_movimientos($id=false,$movimiento,$seccion='ARTICULOS',$dato_ant=false,$dato_act=false,$cod_ant=false,$cod_nue=false)
	{
		$fecha = date('Y-m-d');
			$sql = "INSERT INTO MOVIMIENTO (obs_movimiento,fecha_movimiento,responsable,seccion)VALUES('".$movimiento."','".$fecha."','".$_SESSION['INICIO']['USUARIO']."','".$seccion."')";
		if($id)
		{
			$sql = "INSERT INTO MOVIMIENTO (id_plantilla,obs_movimiento,fecha_movimiento,responsable,seccion,dato_anterior,dato_nuevo,codigo_ant,codigo_nue)VALUES(".$id.",'".$movimiento."','".$fecha."','".$_SESSION['INICIO']['USUARIO']."','".$seccion."','".$dato_ant."','".$dato_act."','".$cod_ant."','".$cod_nue."')";
		}
		// print_r($sql);die();
		$this->db->sql_string($sql);

	}
	function tablas_asociadas($texto)
    {
    	$tablas = array(
    		'PLANTILLA_MASIVA'=>'P',
    		'ASSET'=>'A',
    		'LOCATION'=>'L',
    		'PERSON_NO'=>'PE',
    		'MARCA'=>'M',
    		'GENERO'=>'G',
    		'COLORES'=>'C',
    		'ESTADO'=>'E',
    		'PROYECTOS'=>'PR',
    		'CLASE_MOVIMIENTO'=>'CL'
    	);

    	// print_r($texto);
    	// print_r($tablas);die();

    	foreach ($tablas as $key => $value) {
    		if (strpos($key,$texto)!==false) {
    			// print_r($key);
    			return $value;
    		}

    	}
    }

   //--------------------------------funcion para crear por primera vez los accesos a una empresa nueva en el mismo server ----------------------
   function generar_primera_vez($db_destino,$id_empresa)
	{		
		// print_r($db_destino);
		// print_r($id_empresa);
		// die();
		$usu = array();
		$sql = 'SELECT id_usuarios as id,perfil FROM USUARIOS WHERE all_default_db = 1';
		$usuarios_defualt = $this->db->datos($sql);

		foreach ($usuarios_defualt as $key => $value)
		{
			//valida si los usuarios por default estan creados si no estan los crea
				$sql = "SELECT * FROM ACCESOS_EMPRESA WHERE Id_Empresa = '".$id_empresa."' AND Id_Usuario = ".$value['id'];
				$usu = $this->db->datos($sql,1);	

				if(count($usu)==0)
				{
					 $datos[0]['campo'] = 'Id_usuario';
					 $datos[0]['dato']  = $value['id'];
					 $datos[1]['campo'] = 'Id_Empresa';
					 $datos[1]['dato']  = $id_empresa;
					 $datos[2]['campo'] = 'Id_Tipo_usuario';
					 $datos[2]['dato']  = $value['perfil'];
					 $this->db->inserts('ACCESOS_EMPRESA',$datos,1);		
				}

			//validar los tipos de usuario con la empresa
				$sql = "SELECT * FROM TIPO_USUARIO_EMPRESA WHERE id_tipo_usuario = '".$value['id']."' AND id_empresa = '".$id_empresa."'"; 
				$tipo = $this->db->datos($sql,1);	

				if(count($tipo)==0)
				{
					 $datos[0]['campo'] = 'id_empresa';
					 $datos[0]['dato']  = $id_empresa;
					 $datos[1]['campo'] = 'id_tipo_usuario';
					 $datos[1]['dato']  = $value['id'];
					 $this->db->inserts('TIPO_USUARIO_EMPRESA',$datos,1);		
				} 	

				//insertar en acceso un registro de dba
				$sql = "SELECT * FROM ACCESOS WHERE id_tipo_usu = '1' and id_paginas='93' "; 
				$tipo = $this->db->datos($sql,1);	
				if(count($tipo)==0)
				{
					 $datos[0]['campo'] = 'Ver';
					 $datos[0]['dato']  = 1;
					 $datos[1]['campo'] = 'editar';
					 $datos[1]['dato']  = 1;
					 $datos[2]['campo'] = 'eliminar';
					 $datos[2]['dato']  = 1;
					 $datos[3]['campo'] = 'dba';
					 $datos[3]['dato']  = 1;
					 $datos[4]['campo'] = 'id_paginas';
					 $datos[4]['dato']  = 93;
					 $datos[5]['campo'] = 'id_tipo_usu';
					 $datos[5]['dato']  = 1;
					 $this->db->inserts('ACCESOS',$datos,1);		
				} 		
				
			
		 }

		 // print_r('expression');die();


		 //genera tablas que comprate los diferentes modulos
		 $parametros1 = array($id_empresa
		 							 ,$db_destino);
		  $sql = "EXEC GenerarTablasCompartidas  @id_empresa = ?,@db_destino = ?";
		  $this->db->ejecutar_procesos_almacenados($sql,$parametros1,false,1);


		 $db_origen = EMPRESA_MASTER;
		 $parametros = array($db_origen,
		    						$db_destino,
		    						$id_empresa);

		 // print_r($parametros);die();

			
			
		  $sql = "EXEC CopiarEstructuraAccesos @origen_bd = ?,@destino_bd = ?,@id_empresa = ?";
		  $res = $this->db->ejecutar_procesos_almacenados($sql,$parametros,false,1);

			// print_r("holii");die();
		  $sql = "SELECT * FROM ACCESOS_EMPRESA WHERE Id_Empresa = '".$id_empresa."'";

		  $empresa = $this->lista_empresa($id_empresa);
		  $usuarios = $this->db->datos($sql,1);
		  foreach ($usuarios as $key => $value) {
		  // print_r($value);die();

		  	$sql = "UPDATE USUARIOS SET perfil = '".$value['Id_Tipo_usuario']."' WHERE id_usuarios = '".$value['Id_usuario']."'";
		  	// $datos[0]['campo'] = 'perfil';
		  	// $datos[0]['dato'] = $value['Id_Tipo_usuario'];

		  	// $where[0]['campo'] = 'id_usuarios';
		  	// $where[0]['dato'] = $value['Id_usuario'];

			$database = $empresa[0]['Base_datos'];
			$usuario = $empresa[0]['Usuario_db'];
			$password = $empresa[0]['Password_db'];
			$servidor = $empresa[0]['Ip_host'];
			$puerto = $empresa[0]['Puerto_db'];

			// print_r($sql);die();
			// print_r($database);
			$datos = $this->db->sql_string_db_terceros($database, $usuario, $password, $servidor, $puerto,$sql);

			// print_r('expression');die();


		  	// $this->db->update('USUARIOS',$datos, $where);
		  }

		  return $res;
	}


 //--------------------------------funcion para crear por primera vez los accesos a una empresa nueva en server de terceros ---------------------
   function generar_primera_vez_terceros($empresa,$id_empresa)
	{		
		// print_r($empresa);
		// print_r($id_empresa);
		// die();
		$usu = array();

		$sql = 'SELECT id_usuarios as id,perfil FROM USUARIOS WHERE all_default_db = 1';
		$usuarios_defualt = $this->db->datos($sql);
		foreach ($usuarios_defualt as $key => $value)
		{

			//valida si los usuarios por default estan creados si no estan los crea
				$sql = "SELECT * FROM ACCESOS_EMPRESA WHERE Id_Empresa = '".$id_empresa."' AND Id_Usuario = ".$value['id'];
				$usu = $this->db->datos($sql,1);	

				if(count($usu)==0)
				{
					 $datos[0]['campo'] = 'Id_usuario';
					 $datos[0]['dato']  = $value['id'];
					 $datos[1]['campo'] = 'Id_Empresa';
					 $datos[1]['dato']  = $id_empresa;
					 $datos[2]['campo'] = 'Id_Tipo_usuario';
					 $datos[2]['dato']  = $value['perfil'];
					 $this->db->inserts('ACCESOS_EMPRESA',$datos,1);		
				}

			//validar los tipos de usuario con la empresa
				$sql = "SELECT * FROM TIPO_USUARIO_EMPRESA WHERE id_tipo_usuario = '".$value['id']."' AND id_empresa = '".$id_empresa."'"; 
				$tipo = $this->db->datos($sql,1);	

				if(count($tipo)==0)
				{
					 $datos[0]['campo'] = 'id_empresa';
					 $datos[0]['dato']  = $id_empresa;
					 $datos[1]['campo'] = 'id_tipo_usuario';
					 $datos[1]['dato']  = $value['id'];
					 $this->db->inserts('TIPO_USUARIO_EMPRESA',$datos,1);		
				} 	

				//insertar en acceso un registro de dba
				$sql = "SELECT * FROM ACCESOS WHERE id_tipo_usu = '1' and id_paginas='93' "; 
				$tipo = $this->db->datos($sql,1);	
				if(count($tipo)==0)
				{
					 $datos[0]['campo'] = 'Ver';
					 $datos[0]['dato']  = 1;
					 $datos[1]['campo'] = 'editar';
					 $datos[1]['dato']  = 1;
					 $datos[2]['campo'] = 'eliminar';
					 $datos[2]['dato']  = 1;
					 $datos[3]['campo'] = 'dba';
					 $datos[3]['dato']  = 1;
					 $datos[4]['campo'] = 'id_paginas';
					 $datos[4]['dato']  = 93;
					 $datos[5]['campo'] = 'id_tipo_usu';
					 $datos[5]['dato']  = 1;
					 $this->db->inserts('ACCESOS',$datos,1);		
				} 		
				
			
		 }

		 
		$this->crear_sp_en_terceros($id_empresa,'Tablas_compartidas');	
		$this->crear_sp_en_terceros($id_empresa,'GenerarTablasCompartidas');	
		$this->crear_sp_en_terceros($id_empresa,'GenerarSPBase');		
		 //COPIAMOS EL PROCESO ALMACENADO QUE ES PARA COPIAR LAS TABLAS DE ACCESO
		$this->crear_sp_en_terceros($id_empresa,'CopiarEstructuraAccesosTerc');

		 $empresaAC = $this->lista_empresa($id_empresa);
		 
		// print_r($empresaAC);die();
		$database = $empresaAC[0]['Base_datos'];
		$usuario = $empresaAC[0]['Usuario_db'];
		$password = $empresaAC[0]['Password_db'];
		$servidor = $empresaAC[0]['Ip_host'];
		$puerto = $empresaAC[0]['Puerto_db'];


		$db_origen = EMPRESA_MASTER;
		$db_destino = $empresa[0]["Base_datos"];


		 // print_r('expression');die();
		

		//ejecutamos el proceso almacenado y copiamos estructura de accesos
		// print_r($res);die();


		$parametros = array($db_origen,
		    						$db_destino,
		    						$id_empresa);
		$sql = "EXEC CopiarEstructuraAccesosTerc @origen_bd = ?,@destino_bd = ?,@id_empresa = ?";
		$res = $this->db->ejecutar_sp_db_terceros($database, $usuario, $password, $servidor, $puerto,$sql, $parametros);

		 // print_r($res);die();

	
		 $parametros2 = array($id_empresa
		 							 ,$db_destino
		 							 ,'1');
		$sql = "EXEC GenerarTablasCompartidas  @id_empresa = ?,@db_destino = ?,@db_tercero=?";
		$res = $this->db->ejecutar_sp_db_terceros($database, $usuario, $password, $servidor, $puerto,$sql, $parametros2);


// print_r($res);die();
		  $sql = "SELECT * FROM ACCESOS_EMPRESA WHERE Id_Empresa = '".$id_empresa."'";
		  $usuarios = $this->db->datos($sql,1);


		  foreach ($usuarios as $key => $value) {
		  // print_r($value);die();

		  	$sql = "UPDATE USUARIOS SET perfil = '".$value['Id_Tipo_usuario']."' WHERE id_usuarios = '".$value['Id_usuario']."'";
		  	// $datos[0]['campo'] = 'perfil';
		  	// $datos[0]['dato'] = $value['Id_Tipo_usuario'];

		  	// $where[0]['campo'] = 'id_usuarios';
		  	// $where[0]['dato'] = $value['Id_usuario'];

			// $database = $empresaAC[0]['Base_datos'];
			// $usuario = $empresa[0]['Usuario_db'];
			// $password = $empresa[0]['Password_db'];
			// $servidor = $empresa[0]['Ip_host'];
			// $puerto = $empresa[0]['Puerto_db'];

			// print_r($sql);
			// print_r($database);
			$datos = $this->db->sql_string_db_terceros($database, $usuario, $password, $servidor, $puerto,$sql);


		  	// $this->db->update('USUARIOS',$datos, $where);
		  }

		  return $res;
	}


	function Copiar_estructura($modulo,$base,$tercero=false,$empresa=false)
	{				
		$db_destino = $base;
		$db_origen = '';
		$sp = array();
		switch ($modulo) {
			case '7':
				$db_origen = BASE_SALUD;
				break;
			case '2':
				$db_origen = BASE_ACTIVOS;

				break;
		}
		 $parametros = array(
		    $db_origen,
		    $db_destino,
		  );
		 if($db_origen!='')
		 {
		 	if($tercero)
		 	{		 		
		 		$id_empresa = $empresa[0]['Id_empresa'];
		 		$this->crear_sp_en_terceros($id_empresa,'EstructuraBaseTerc');
		  		$sql = "EXEC EstructuraBaseTerc @origen_bd = ?, @destino_bd = ?";
		  		$database = $empresa[0]['Base_datos'];
				$usuario = $empresa[0]['Usuario_db'];
				$password = $empresa[0]['Password_db'];
				$servidor = $empresa[0]['Ip_host'];
				$puerto = $empresa[0]['Puerto_db'];

				$parametrosSp = array($db_origen,
		    								$db_destino,
		    								'1');
				$sql2 = "EXEC GenerarSPBase @origen_bd = ?,@destino_bd = ?,@db_tercero = ?";
				$res = $this->db->ejecutar_sp_db_terceros($database, $usuario, $password, $servidor, $puerto,$sql2, $parametrosSp);
		  		return $this->db->ejecutar_sp_db_terceros($database, $usuario, $password, $servidor, $puerto,$sql, $parametros);
		 	}else{
		  		$sql = "EXEC EstructuraBase @origen_bd = ?, @destino_bd = ?";
		  		return $this->db->ejecutar_procesos_almacenados($sql,$parametros,false,1);
		  	}
		 }else{ return -2;}
	}

	function tablas_por_licencias($licencias,$empresa,$terceros=false)
	{
		$totalTablas = 0;
		$db_origen  = '';
		foreach ($licencias as $key => $value) {

				switch ($value['Id_Modulo']) {
						case '7':
							$db_origen = BASE_SALUD;
							break;
						case '2':
							$db_origen = BASE_ACTIVOS;

							break;
				}

				$sql = "SELECT COUNT(*) AS CantidadDeTablas 
							FROM ".$db_origen.".INFORMATION_SCHEMA.TABLES 
							WHERE TABLE_TYPE = 'BASE TABLE' 
							AND TABLE_CATALOG = '".$db_origen."';";

			if($terceros)
			{
				$sql2 = "SELECT * FROM EMPRESAS WHERE Base_datos = '".$db_origen."' AND Estado = 'A'";
				// print_r($sql2);
				$empresaBuscada = $this->db->datos($sql2,1);
				if(count($empresaBuscada)>0)
				{
					$database = $empresaBuscada[0]['Base_datos'];
					$usuario = $empresaBuscada[0]['Usuario_db'];
					$password = $empresaBuscada[0]['Password_db'];
					$servidor = $empresaBuscada[0]['Ip_host'];
					$puerto = $empresaBuscada[0]['Puerto_db'];

					$cant =   $this->db->datos_db_terceros($database, $usuario, $password, $servidor, $puerto, $sql);
					$totalTablas = $totalTablas+$cant[0]['CantidadDeTablas'];
				}

			}else
			{
				// print_r($sql);die();
				$cant =  $this->db->datos($sql);
				$totalTablas = $totalTablas+$cant[0]['CantidadDeTablas'];
			}

		}
// print_r('expression');die();

		if(count($licencias)>1)
		{
			$totalTablas = $totalTablas - (NUM_TABLA_ACCESOS*count($licencias));
		}

		// print_r($totalTablas);die();


		$db = $empresa[0]['Base_datos'];

		$sql = $sql = "SELECT COUNT(*) AS CantidadDeTablas 
					FROM ".$db.".INFORMATION_SCHEMA.TABLES 
					WHERE TABLE_TYPE = 'BASE TABLE' 
					AND TABLE_CATALOG = '".$db."';";
		if($terceros)
		{
			// print_r($empresa);die();
			$database = $empresa[0]['Base_datos'];
			$usuario = $empresa[0]['Usuario_db'];
			$password = $empresa[0]['Password_db'];
			$servidor = $empresa[0]['Ip_host'];
			$puerto = $empresa[0]['Puerto_db'];
			$cant =  $this->db->datos_db_terceros($database, $usuario, $password, $servidor, $puerto, $sql);
			$totalActual = $cant[0]['CantidadDeTablas'];

			// print_r($totalActual);die();

		}else
		{
			// print_r('E');die();
			$cant =  $this->db->datos($sql,1);
			$totalActual = $cant[0]['CantidadDeTablas'];
		}

		// print_r($totalTablas.' = '.$totalActual);die();

		if($totalTablas!=$totalActual &&  $totalActual < $totalTablas)
		{
			return -1;
		}else
		{
			return 1;
		}
	}


	function id_tabla($tabla)
	{
		$sql2="SELECT COLUMN_NAME as 'ID'
				FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
				WHERE OBJECTPROPERTY(OBJECT_ID(CONSTRAINT_NAME), 'IsPrimaryKey') = 1
				AND TABLE_NAME = '".$tabla."'";
				// print_r($sql2);die();
		$datos2 = $this->db->datos($sql2);
		return $datos2;
	}

	function sql_string_db_terceros($database, $usuario, $password, $servidor, $puerto, $sql)
	{
		return $this->db->sql_string_db_terceros($database, $usuario, $password, $servidor, $puerto, $sql);
	}

	function datos_db_terceros($database, $usuario, $password, $servidor, $puerto, $sql)
	{
		return $this->db->datos_db_terceros($database, $usuario, $password, $servidor, $puerto, $sql);
	}

	function enciptar_clave($data)
	{
		// Clave de encriptación (debe tener 16, 24 o 32 bytes para AES-128, AES-192 o AES-256 respectivamente)
		$key = $this->CodAES; 

		// Vector de inicialización (IV) para AES-256-CBC, debe ser de 16 bytes
		$iv = $this->iv;

		// Encriptar los datos usando AES-256-CBC
		$encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);

		return $encrypted;
	}

	function desenciptar_clave($data)
	{
		$key = $this->CodAES;
		// Vector de inicialización (IV) para AES-256-CBC, debe ser de 16 bytes
		$iv = $this->iv; //openssl_random_pseudo_bytes(16);
		
		$decrypted = openssl_decrypt($data, 'aes-256-cbc', $key, 0, $iv);

		// Mostrar el texto desencriptado
		return $decrypted;
	}
	function lista_empresa($id,$master=false)
	{
		$sql = "SELECT E.*,Id_empresa as 'Id_Empresa' FROM EMPRESAS E WHERE Id_empresa = '".$id."'";
		if($master)
		{
			return $this->db->datos($sql);
		}else
		{
			return $this->db->datos($sql,1);
		}
	}

	function crear_sp_en_terceros($id_empresa,$proceso_alm)
	{
		 $sql = "SELECT definition
		 			FROM sys.sql_modules
		 			WHERE object_id = OBJECT_ID('dbo.".$proceso_alm."')";
		 $datos = $this->db->datos($sql,1);

		$empresaAC = $this->lista_empresa($id_empresa);
		 
		// print_r($empresaAC);die();
		$database = $empresaAC[0]['Base_datos'];
		$usuario = $empresaAC[0]['Usuario_db'];
		$password = $empresaAC[0]['Password_db'];
		$servidor = $empresaAC[0]['Ip_host'];
		$puerto = $empresaAC[0]['Puerto_db'];


		$sql = "DROP PROCEDURE IF EXISTS dbo.".$proceso_alm;
		$res = $this->db->Generar_sp_db_terceros($database, $usuario, $password, $servidor, $puerto, $sql);

		// print_r($datos[0]['definition']);die();
		$sql = $datos[0]['definition'];
		$res = $this->db->Generar_sp_db_terceros($database, $usuario, $password, $servidor, $puerto, $sql);

		return $res;
		
	}

}
?>