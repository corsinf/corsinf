<?php 
if(isset($_SESSION['INICIO']))
{   
  @session_start();
}else
{
     session_start();
}
require_once(dirname(__DIR__, 2) .'/modelo/headerM.php');
require_once(dirname(__DIR__, 2) .'/modelo/tipo_usuarioM.php');

/**
 * 
 */$controlador = new headerC();
if (isset($_GET['menu'])) {
 echo json_encode($controlador->menu_lateral());
}
if (isset($_GET['ci_existente'])) {
  $parametros = $_POST['parametros'];
 echo json_encode($controlador->ci_existente($parametros));
}
class headerC
{
	private $modelo;
	
	function __construct()
	{
		
	$this->modelo = new headerM(); 
  $this->tipo = new tipo_usuarioM(); 
	}

	function menu_lateral()
	{      

		$me = '';
    $me1 = '';
    $modulos = $this->modelo->menu_lateral();
		$empresa = $this->modelo->datos_empresa('1'); ///cambiar conforme se vaya agregando
    $_SESSION['INICIO']['Logo_Tipo'] = $empresa[0]['icono'];
    $_SESSION['INICIO']['nombre_comercial'] = $empresa[0]['comercial'];
    $_SESSION['INICIO']['direccion'] = $empresa[0]['direccion'];
    $_SESSION['INICIO']['telefono'] = $empresa[0]['telefono'];
    $paginas_sin_mod = $this->modelo->paginas('');

    // print_r($paginas_sin_mod);die();

    foreach ($paginas_sin_mod as $key => $value) {
      // print_r($value);die();
       $resp = $this->modelo->accesos($value['id']);
        if($resp==1)
        {
          $me1.='<li class="nav-item">
                  <a href="'.$value['link'].'" class="nav-link">
                      '.$value['icono'].'
                      <p> '.$value['nombre'].'</p>
                  </a>
                </li>';
        }
    }

    $coincide = false;
		foreach ($modulos as $key => $value) {
      $coincide = false;
      $acc = $this->tipo->modulos_habilitados($_SESSION['INICIO']['TIPO']);
      foreach ($acc as $key1 => $value1) {
        if($value['id']==$value1['id_modulos'])
          {
              $paginas = $this->modelo->paginas($value['id']);
              $sub = count($paginas);
              $me.='<li class="nav-item">
                    <a href="" class="nav-link">
                      '.$value['icono'].'
                      <p>
                         '.$value['nombre'].'
                        ';
                        if($sub>0)
                        {
                         $me.='<i class="fas fa-angle-left right"></i>';
                        }
                        $me.='</p></a>';
                        if($sub>0)
                        {
                          $me.='<ul class="nav nav-treeview">';
                          foreach ($paginas as $key => $valuee) {
                            $resp = $this->modelo->accesos($valuee['id']);
                            if($resp==1)
                            {
                            $me.='<li class="nav-item">
                                    <a href="'.$valuee['link'].'" class="nav-link">
                                        '.$valuee['icono'].'
                                       <p> '.$valuee['nombre'].'</p>
                                    </a>
                                 </li>';
                            }                   
                          }
                          $me.=' </ul>';
                        }
                        $me.='</li>';
            break;
          }
      }			
		}
        $datos = array('me'=>$me1.$me,'empresa'=>$empresa);
		// print_r($me);die();
		return $datos;

	}
  function ci_existente($parametros)
  {
      if($parametros['ci']!='9999999999')
      {
        $resp = $this->modelo->ci_existente($parametros['ci'],$parametros['tipo']);
        return $resp;
      }else
      {
        return false;
      }
  }
}
?>