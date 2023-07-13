<?php 
/**
 * 
 */
class funciones
{
	
	function __construct()
	{

	}

	function equivalente($texto)
	{
		$valor ='';
		switch ($texto) {
			case 'PLANTILLA_MASIVA':
				$valor = 'ACTIVO';
				break;
			case 'PERSON_NO':
				$valor = 'CUSTODIO';
				break;
			case 'LOCATION':
				$valor = 'EMPLAZAMIENTOS';
				break;
			case 'FECHA_INV_DATE':
				$valor = 'FECHA DE INVENTARIO';
				break;
			case 'FECHA_BAJA':
				$valor = 'FECHA BAJA';
				break;
			
			default:
			$valor = $texto;
				break;
		}

		return $valor;
	}

	function join_tabla($tbl_principal,$iden,$tbl_ligado,$iden1)
	{
		$join = $tbl_principal.'='.$tbl_ligado;
		$valor ='';
		switch ($join) {
			case 'PLANTILLA_MASIVA=MARCAS':
				$valor = ' LEFT JOIN MARCAS '.$iden1.' ON '.$iden.'.EVALGROUP1 = '.$iden1.'.ID_MARCA';
				break;
			case 'PLANTILLA_MASIVA=GENERO':
				$valor = ' LEFT JOIN GENERO '.$iden1.' ON '.$iden.'.EVALGROUP3 = '.$iden1.'.ID_GENERO';
				break;
			case 'PLANTILLA_MASIVA=ESTADO':
				$valor = ' LEFT JOIN ESTADO '.$iden1.' ON '.$iden.'.EVALGROUP2 = '.$iden1.'.ID_ESTADO';
				break;
			case 'PLANTILLA_MASIVA=COLORES':
				$valor = ' LEFT JOIN COLORES '.$iden1.' ON '.$iden.'.EVALGROUP4 = '.$iden1.'.ID_COLORES ';
				break;
			case 'PLANTILLA_MASIVA=PROYECTO':
				$valor = ' LEFT JOIN PROYECTO '.$iden1.' ON '.$iden.'.EVALGROUP5 = '.$iden1.'.ID_PROYECTO ';
				break;
			case 'PLANTILLA_MASIVA=PERSON_NO':
				$valor = ' LEFT JOIN PERSON_NO '.$iden1.' ON '.$iden.'.PERSON_NO = '.$iden1.'.ID_PERSON ';
				break;
			case 'PLANTILLA_MASIVA=LOCATION':
				$valor = ' LEFT JOIN LOCATION '.$iden1.' ON '.$iden.'.LOCATION = '.$iden1.'.ID_LOCATION ';
				break;
			case 'PLANTILLA_MASIVA=ASSET':
				$valor = ' LEFT JOIN ASSET '.$iden1.' ON '.$iden.'.ID_ASSET = '.$iden1.'.ID_ASSET';
				break;
			case 'PLANTILLA_MASIVA=CLASE_MOVIMIENTO':
				$valor = ' LEFT JOIN CLASE_MOVIMIENTO '.$iden1.' ON '.$iden.'.CLASE_MOVIMIENTO = '.$iden1.'.CODIGO';
				break;
			
			// default:
			// $valor = $texto;
			// 	break;
		}

		return $valor;

	}

	function generar_sql($parametros,$sql,$para_vista=false)
	{
		$filtro_param = false;
		if (count($parametros)==2) {
				if($para_vista)
				{				
					$pag = explode('-',$parametros['pag']);
					$sql.= " OFFSET ".$pag[0]." ROWS FETCH NEXT ".$pag[1]." ROWS ONLY;";
				}
			}else
			{
				// print_r($parametros);die();
				$where = '';

				$arraysIndividuales = array_map(function($elemento) {
					return array($elemento);
				}, $parametros);

				// print_r($arraysIndividuales);die();
				
					foreach ($arraysIndividuales as $key => $value) {

						if($value[0]!='' && $key!='id' && $key!='pag'  && $key!='pag2')
						{
							// print_r($value);
							$campo = explode('-',str_replace('txt_','',$key));
							if(!is_array($value[0]))
							{
								// print_r(str_replace('txt_','',$key));die();
								if(isset($campo[1]) && $campo[1]!='')
								{
									if($value[0]=='on')
									{
										$filtro_param = true;
										$tb = substr($campo[1], 0,4);
										$where.= $tb.".".$campo[0]." = '".$value[0]."' AND ";
									}else
									{
										$filtro_param = true;
										$tb = substr($campo[1], 0,4);
										$where.= $tb.".".$campo[0]." = '1' AND ";
									}
								}else
								{	
									if($value[0]!='on')
									{
										$filtro_param = true;							
										$where.= $campo[0]." = '".$value[0]."' AND ";		
									}else
									{
										$filtro_param = true;							
										$where.= $campo[0]." = '1' AND ";		
									}		
								}
							}else
							{
								if( $value[0][0]!='' && $value[0][1]!='')
								{
									if(isset($campo[1]) && $campo[1]!='')
									{
										$filtro_param = true;
										$tb = substr($campo[1], 0,4);
										$where.= $tb.".".$campo[0]." BETWEEN '".str_replace('-','',$value[0][0])."' AND '".str_replace('-','',$value[0][1])."' AND ";
									}else
									{	
										$filtro_param = true;							
										$where.= $campo[0]." BETWEEN '".str_replace('-','',$value[0][0])."' AND '".str_replace('-','',$value[0][1])."' AND ";
									}
								}

							}
						}
					}

					if($filtro_param)
					{
						if($where!='')
						{
							$where = substr($where,0 ,strlen($where)-4);
							$where = ' WHERE '.$where;
						}
						$sql = explode('ORDER', $sql);
						$sql = $sql[0].' '.$where.' ORDER '.$sql[1];
						$pag = explode('-',$parametros['pag']);
						$sql.= " OFFSET ".$pag[0]." ROWS FETCH NEXT ".$pag[1]." ROWS ONLY;";
					}else
					{
						if($para_vista)
						{
							$pag = explode('-',$parametros['pag']);
							$sql.= " OFFSET ".$pag[0]." ROWS FETCH NEXT ".$pag[1]." ROWS ONLY;";
						}
					}
				

				// print_r($sql);
				// print_r($sql2);die();
			}

			$sql2 = explode('FROM', $sql);
			$sql2 = "SELECT COUNT(*) as 'total' FROM ".$sql2[1];
			$sql2 = explode('ORDER', $sql2);
			$sql2 =$sql2[0];

			// print_r($sql);
			// print_r($sql2);die();
		

				// print_r($sql);die();
			
			return array('sql_total'=>$sql2,'sql_normal'=>$sql);
	}

	function datos_repetidos_array($campos_todos)
	{
		//analisa cual de los datos esta repetido en nombre
		$campos2 = array_unique($campos_todos);
		$v_comunes1 = array_diff_assoc($campos_todos, $campos2); 
		$v_comunes2 = array_unique($v_comunes1); 
		$repetidos = implode(',',$v_comunes2);  
		$repetidos = explode(',',$repetidos);  
			// fin de analizar datos repetidos
	}
}
?>