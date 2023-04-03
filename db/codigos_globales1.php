<?php 
/**
 * 
 */
class codigos_globales
{
	
	function __construct()
	{
		
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
		$tabla.='<thead class="thead-dark">'.$th.'</thead>';
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

}
?>