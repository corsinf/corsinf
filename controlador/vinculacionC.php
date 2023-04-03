<?php 
include('../db/codigos_globales.php');
include('../modelo/vinculacionM.php');
include('../modelo/articulosM.php');
include('../modelo/custodioM.php');
include('../modelo/localizacionM.php');

if(isset($_SESSION['INICIO']))
{   
  @session_start();
}else
{
     session_start();
}


$controlador = new vinculacionC();
if(isset($_GET['lista_art']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lista_articulos_vinculados($parametros));
}
if(isset($_GET['desvincular_art']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->desvincular_art($parametros));
}
if(isset($_GET['lista_desvinculados']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lista_desvinculados($parametros));
}

if(isset($_GET['vincular_custodio']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->vincular_custodio($parametros));
}

if(isset($_GET['vincular_custodio_todo']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->vincular_custodio_todo($parametros));
}

if(isset($_GET['vincular_localizacion']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->vincular_localizacion($parametros));
}
if(isset($_GET['vincular_localizacion_todo']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->vincular_localizacion_todo($parametros));
}

if(isset($_GET['numero_custodios']))
{
	// $parametros = $_POST['parametros'];
	echo json_encode($controlador->cantidad_custodios());
}
if(isset($_GET['numero_localizaciones']))
{
	// $parametros = $_POST['parametros'];
	echo json_encode($controlador->cantidad_localizacion());
}





class vinculacionC
{
	private $modelo;	
	private $articulos;
	private $cod_global;
	private $custodio;
	private $localizacion;
	function __construct()
	{
		$this->modelo = new vinculacionM();
		$this->articulos = new ArticulosM();
		$this->cod_global = new codigos_globales();
		$this->custodio = new custodioM();
		$this->localizacion = new localizacionM();


	}

	function lista_articulos_vinculados($parametros)
	{
		// print_r($parametros);die();

		if($parametros['exacto'] == 'true'){$parametros['exacto'] = true;	}else{$parametros['exacto'] = false;}
		if($parametros['asset'] == 'true'){$parametros['asset'] = true;	}else{$parametros['asset'] = false;	}

		$datos = $this->articulos->lista_articulos($parametros['query'],$parametros['localizacion'],$parametros['custodio'],$parametros['pag'],$whereid=false,$parametros['exacto'],$parametros['asset'],$bajas=false,$terceros=false,$patrimoniales=false,$desde=false,$hasta=false);
		$tbl = '';

		foreach ($datos as $key => $value) {
			if($value['IMAGEN']=='' || $value['IMAGEN']==null){$value['IMAGEN']='../img/sin_imagen.jpg';}
			if(!file_exists($value['IMAGEN'])){$value['IMAGEN']='../img/no_disponible.png';}
		 	$tbl.='<tr>		 	
                  <td><img src="'.$value['IMAGEN'].'" style="width: 65px; height: 65px;"></td>
                  <td>'.$value['tag'].'</td>
                  <td>'.$value['nom'].'</td>
                  <td>'.$value['localizacion'].'</td>  
                  <td>
                  <div class="btn-group">
                  	<div class="dropdown"><br>
	                      <button class="btn btn-danger dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">Desvincular</button>
	                      <ul class="dropdown-menu" style="">
	                        <li><a class="dropdown-item" href="#" onclick="desvincular('.$value['id'].',\'C\')">Por Custodio</a></li>
	                        <li><a class="dropdown-item" href="#" onclick="desvincular('.$value['id'].',\'L\')">Por Localizacion</a></li>
	                        </li>
	                      </ul>                                               
	                    </div>
                  </div>
                  </td>                          
                </tr>';	
		}
		return $tbl;
	}

	function desvincular_art($parametros)
	{

		// print_r($parametros);die();
		if($parametros['id']=='')
		{
			
			$articulos = $this->articulos->lista_articulos($parametros['query'],$parametros['localizacion'],$parametros['custodio'],$pag=false,$whereid=false,$exacto=false,$asset=false,$bajas=false,$terceros=false,$patrimoniales=false,$desde=false,$hasta=false);

			// print_r($articulos);die();

			$seguro = 0;$mensaje='Los Articulos <br>';
			foreach ($articulos as $key => $value) {

			$validar_seguro = $this->modelo->validar_asegurado($value['id']);
					
			
				$datos[0]['campo'] = 'articulo_id';
				$datos[0]['dato'] = $value['id'];
				$datos[1]['campo'] = 'tipo';
				$datos[1]['dato'] = $parametros['tipo'];
				$datos[2]['campo'] = 'custodio_id';
				$datos[2]['dato'] = 0;
				$datos[3]['campo'] = 'localizacion_id';
				$datos[3]['dato'] = 0;

				if($parametros['tipo']=='C')
				{
					$datos[2]['campo'] = 'custodio_id';
					$datos[2]['dato'] = $value['IDC'];
				}
				if($parametros['tipo']=='L')
				{
					$datos[3]['campo'] = 'localizacion_id';
					$datos[3]['dato'] = $value['IDL'];
				}

				if(count($validar_seguro)>0)
				{
					$seguro = 1;
					$mensaje.= $value['tag'].'-'.$value['nom'].'<br>';
				}else{				
					if($parametros['tipo']=='C')
						{
							$this->cod_global->ingresar_movimientos($value['id'],'Articulo Desvinculado de custodio:'.$value['custodio'],'VINCULAR / DESVINCULAR ARTICULOS');
						}else
						{
							$this->cod_global->ingresar_movimientos($value['id'],'Articulo Desvinculado de Localizacion:'.$value['localizacion'],'VINCULAR / DESVINCULAR ARTICULOS');
						}
						$this->modelo->insertar('VINCULACION',$datos);
				}
			}
			if($seguro==0)
			{
				return 1;
			}else
			{
				return array('response'=>-2,'mensaje'=>$mensaje);			
			}
				


		}else
		{
			

			// print_r($validar_seguro);die();

			$articulos = $this->articulos->lista_articulos(false,false,false,$pag=false,$parametros['id'],$exacto=false,$asset=false,$bajas=false,$terceros=false,$patrimoniales=false,$desde=false,$hasta=false);

			$validar_seguro = $this->modelo->validar_asegurado($parametros['id']);
			if(count($validar_seguro)>0)
			{
				return array('response'=>-2,'mensaje'=>'El articulo <br> '.$articulos[0]['tag'].'-'.$articulos[0]['nom']);
			}

			// print_r($articulos);
			// die();

		// print_r($parametros);die();
			$datos[0]['campo'] = 'articulo_id';
			$datos[0]['dato'] = $parametros['id'];
			$datos[1]['campo'] = 'custodio_id';
			$datos[1]['dato'] = $parametros['custodio'];
			$datos[2]['campo'] = 'localizacion_id';
			$datos[2]['dato'] = $articulos[0]['IDL'];
			$datos[3]['campo'] = 'tipo';
			$datos[3]['dato'] = $parametros['tipo'];

		if($parametros['tipo']=='C')
		{
			$this->cod_global->ingresar_movimientos($parametros['id'],'Articulo Desvinculado de custodio:'.$articulos[0]['custodio'],'VINCULAR / DESVINCULAR ARTICULOS');
		}else
		{
			$this->cod_global->ingresar_movimientos($parametros['id'],'Articulo Desvinculado de Localizacion:'.$articulos[0]['localizacion'],'VINCULAR / DESVINCULAR ARTICULOS');
		}

			return $this->modelo->insertar('VINCULACION',$datos);
		}

		
	}

	function lista_desvinculados($parametros)
	{
		// print_r($parametros);die();
		if($parametros['tipo']=='C')
		{
			$datos = $this->modelo->lista_desvinculados($parametros['tipo'],$parametros['query'],$parametros['cus'],$location=false);
		}else
		{
			$datos = $this->modelo->lista_desvinculados($parametros['tipo'],$parametros['query'],false,$parametros['loc']);
		}
		$tbl='';
		foreach ($datos as $key => $value) {
				if($value['IMAGEN']=='' || $value['IMAGEN']==null){$value['IMAGEN']='../img/sin_imagen.jpg';}
				if(!file_exists($value['IMAGEN'])){$value['IMAGEN']='../img/no_disponible.png';}
			 	$tbl.='<tr>		 	
	                  <td><img src="'.$value['IMAGEN'].'" style="width: 65px; height: 65px;"></td>
	                  <td>'.$value['tag'].'</td>
	                  <td>'.$value['nom'].'</td>';
	                  if($parametros['tipo']=='C')
	                  {
	                  	$tbl.='<td>'.$value['cus'].'</td>';
	                  }else
	                  {
	                  		$tbl.='<td>'.$value['loc'].'</td>';
	                  }  
	                  $tbl.='<td>
	                  <div class="btn-group">
	                    <button type="button" class="btn btn-primary btn-sm" onclick="vincular('.$value['id'].',\''.$value['tipo'].'\')" title="Vincular"><i class="fa fa-address-book"></i> <!-- Vincular --> </button>
	                  </div>
	                  </td>                          
	                </tr>';	
			}
			return $tbl;
	}

	function vincular_custodio($parametros)
	{
		// print_r($parametros);die();

		$custo = $this->custodio-> buscar_custodio($parametros['nuevo']); 	
		$datos[0]['campo'] = 'PERSON_NO';
		$datos[0]['dato'] = $parametros['nuevo'];

		$where[0]['campo'] = 'id_plantilla';
		$where[0]['dato'] = $parametros['id'];
		$this->cod_global->ingresar_movimientos($parametros['id'],'Articulo Vinculado a Custodio:'.$custo[0]['PERSON_NOM'],'VINCULAR / DESVINCULAR ARTICULOS');
		$this->modelo->editar('PLANTILLA_MASIVA',$datos,$where);
		return $this->modelo->delete_vin($parametros['id'],'C');

	}

	function vincular_custodio_todo($parametros)
	{
		$datos5 = $this->modelo->lista_desvinculados('C',$parametros['query'],$parametros['antiguo'],$location=false);
		$resp = 1;
		foreach ($datos5 as $key => $value) {

			$custo = $this->custodio->buscar_custodio($parametros['nuevo']); 	

			$datos[0]['campo'] = 'PERSON_NO';
			$datos[0]['dato'] = $parametros['nuevo'];

			$where[0]['campo'] = 'id_plantilla';
			$where[0]['dato'] = $value['id'];
			$this->cod_global->ingresar_movimientos($value['id'],'Articulo Vinculado a Custodio:'.$custo[0]['PERSON_NOM'],'VINCULAR / DESVINCULAR ARTICULOS');
			$r = $this->modelo->editar('PLANTILLA_MASIVA',$datos,$where);
			// print_r($datos);print_r($where);
			// print_r($parametros);
			// print_r($r);die();
			$this->modelo->delete_vin($value['id'],'C');

			
		}
		return $resp;
		// print_r($datos);die();	

	}

	function vincular_localizacion($parametros)
	{

		// print_r($parametros);die();
		$location = $this->localizacion->buscar_localizacion($parametros['nuevo']);
		$datos[0]['campo'] = 'LOCATION';
		$datos[0]['dato'] = $parametros['nuevo'];

		$where[0]['campo'] = 'id_plantilla';
		$where[0]['dato'] = $parametros['id'];
		$this->cod_global->ingresar_movimientos($parametros['id'],'Articulo Vinculado a Localizacion:'.$location[0]['DENOMINACION'],'VINCULAR / DESVINCULAR ARTICULOS');
		$this->modelo->editar('PLANTILLA_MASIVA',$datos,$where);
		return $this->modelo->delete_vin($parametros['id'],'L');

	}

	function vincular_localizacion_todo($parametros)
	{
		// print_r($parametros);die();
		$datos5 = $this->modelo->lista_desvinculados('L',$parametros['query'],false,$parametros['antiguo']);
		$resp = 1;
		// print_r($datos5);die();
		foreach ($datos5 as $key => $value) {

			$location = $this->localizacion->buscar_localizacion($parametros['nuevo']);
			$datos[0]['campo'] = 'LOCATION';
			$datos[0]['dato'] = $parametros['nuevo'];

			$where[0]['campo'] = 'id_plantilla';
			$where[0]['dato'] = $value['id'];
			$this->cod_global->ingresar_movimientos($value['id'],'Articulo Vinculado a Localizacion:'.$location[0]['EMPLAZAMIENTO'],'VINCULAR / DESVINCULAR ARTICULOS');
			$this->modelo->editar('PLANTILLA_MASIVA',$datos,$where);
			$this->modelo->delete_vin($value['id'],'L');

			
		}
		return $resp;
		// print_r($datos);die();	
	}

	function cantidad_custodios()
	{
		$datos = $this->modelo->lista_desvinculados('C',$query=false,$custodio=false,$location=false);
		return array('cant'=>count($datos));

	}
	function cantidad_localizacion()
	{

		$datos = $this->modelo->lista_desvinculados('L',$query=false,$custodio=false,$location=false);
		return array('cant'=>count($datos));

	}


}

?>