<?php
if(isset($_SESSION['INICIO']))
{	
  @session_start();
} 
require_once(dirname(__DIR__, 2) .'/modelo/loginM.php');
/**
 * 
 */
$controlador = new loginC();
if(isset($_GET['iniciar']))
{
echo json_encode($controlador->iniciar_sesion($_POST['parametros']));
}
if(isset($_GET['cerrar']))
{
echo json_encode($controlador->cerrar_session());
}
if(isset($_GET['restriccion']))
{
  echo json_encode($controlador->restriccion());
}
class loginC
{
	private $login;
	function __construct()
	{
		$this->login = new loginM();
	}


	function iniciar_sesion($parametros)
	{
		if($this->login->existe($parametros['email'],$parametros['pass']) == 1)
		{
			$datos = $this->login->datos_login($parametros['email'],$parametros['pass']);

				// print_r($datos[0]);die();
			// print_r($datos);die();
			if($datos[0]['tipo']!='')
			{
				// session_start();
				$_SESSION["INICIO"]['ID'] = $datos[0]['ID'];
				$_SESSION["INICIO"]['TIPO'] = $datos[0]['tipo'];
				$_SESSION["INICIO"]['USUARIO_LOG'] = $datos[0]['nombres'];
				$_SESSION["INICIO"]['EMAIL'] = $datos[0]['email'];
				$_SESSION['INICIO']['TIPO_NOMBRE'] = $datos[0]['tipo_detalle'];				
				$_SESSION['INICIO']['PUNTO_VENTA_SELECIONADO']='';
				$_SESSION['INICIO']['BODEGAS']='';
				$puntos = $this->login->puntos_venta($datos[0]['ID']);
				$p_venta = '';
				if(count($puntos)>1)
				{
				foreach ($puntos as $key => $value) {
					$p_venta.=$value['id'].',';
				}
			    }else
			    {
			    	if(count($puntos)==1)
			    	{
			    	     $_SESSION['INICIO']['PUNTO_VENTA_SELECIONADO']=$puntos[0]['id'];
			    	     $p_venta = $puntos[0]['id'].',';
			    	     $b = $this->login->bodegas($puntos[0]['id']);
			    	     $_SESSION['INICIO']['PUNTO_VENTA_SELECIONADO_NOM']=$b[0]['nombre_punto'];
			    	     if($b[0]['all_bodegas']==1)
			    	     {
			    		      $bo_all = $this->login->bodegas_all();
			    		      $bo = '';
			    		      foreach ($bo_all as $key => $value) {
			    		 	     $bo.=$value['id'].',';			    		 	
			    		      }
			    		      $bo = substr($bo, 0,-1);
			    		     $_SESSION['INICIO']['BODEGAS'] = $bo;

			    	     }else
			    	     {
				           $_SESSION['INICIO']['BODEGAS']=$b[0]['bodega'];
			    	     }
			        }else
			        {
			        	$_SESSION['INICIO']['PUNTO_VENTA_SELECIONADO']=0;
			        	$_SESSION['INICIO']['PUNTO_VENTA_SELECIONADO_NOM']='NINGUNO';
			        	$_SESSION['INICIO']['BODEGAS'] = 0;

			        }
			    }
				$p_venta = substr($p_venta, 0,-1);
				$_SESSION['INICIO']['PUNTO_VENTA'] = $p_venta;

				return 1;

			}else
			{
				return -1;
			}

		}else
		{
			return -2;
		}

	}
	function cerrar_session()
	{
		// session_start();
		session_destroy();
		return 1;

	}
	function restriccion()
	{
		@session_start();
		$datos = array('ver'=>$_SESSION["INICIO"]['VER'],'editar'=>$_SESSION["INICIO"]['EDITAR'],'eliminar'=>$_SESSION["INICIO"]['ELIMINAR'],'dba'=>$_SESSION["INICIO"]['ELIMINAR']);
		return $datos;

	}

}
?>