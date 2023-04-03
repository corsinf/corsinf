<?php 
/**
 * 
 */
$controlador = new subir_datosC();
if(isset($_GET['cargar_excel']))
{
	echo json_encode($controlador->cargar_excel($_FILES,$_POST['txt_opcion']));
}

//actualizacion y carga de datos de activo por xlxs
if(isset($_GET['actualizar_excel']))
{
  echo json_encode($controlador->actualizar_excel($_FILES,$_POST['txt_opcion']));
}

//carga de activos normal desde un excel xlsx
if(isset($_GET['cargar_activos']))
{
  echo json_encode($controlador->cargar_activos($_FILES,$_POST['txt_opcion']));
}

if(isset($_GET['cargar_csv']))
{
  echo json_encode($controlador->cargar_csv());
}
class subir_datosC
{
	
	function __construct()
	{
		# code...
	}
  function cargar_excel($file,$op)
  {
  	if($file['file']['type'] == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
  	{
  		 $uploadfile_temporal=$file['file']['tmp_name'];
  		 $ruta = '../lib/';
   	     //$tipo = explode('/', $file['file']['type']);
       $nombre = '';
        if ($op==1) {
          $nombre = 'plantilla_masiva.cvs';
        }elseif ($op==2) {
          $nombre = 'colores.xlsx';
        }elseif ($op==3) {
          $nombre = 'custodio.xlsx';
        }elseif ($op==4) {
          $nombre = 'estado.xlsx';
        }elseif ($op==5) {
          $nombre = 'genero.xlsx';
        }elseif ($op==6) {
          $nombre = 'emplazamiento.xlsx';
        }elseif ($op==7) {
          $nombre = 'marcas.xlsx';
        }elseif ($op==8) {
          $nombre = 'proyecto.xlsx';
        }
        
   	     $nuevo_nom=$ruta.$nombre;
   	     if (is_uploaded_file($uploadfile_temporal))
   	     {
   		     move_uploaded_file($uploadfile_temporal,$nuevo_nom); 
   		     return 1;  		     
   	     }
   	     else
   	     {
   		    return -1;
   	     } 
  	}else
    {
      return -2;
    }

  } 


  function cargar_activos($file,$op)
  {
     if($file['file']['type'] == 'text/csv')
    {
       $uploadfile_temporal=$file['file']['tmp_name'];
       $ruta = '../TEMP/';
         //$tipo = explode('/', $file['file']['type']);
       $nombre = '';
        if ($op==1) {
          $nombre = 'plantilla_masiva.csv';
        }        
         $nuevo_nom=$ruta.$nombre;
         if (is_uploaded_file($uploadfile_temporal))
         {
           move_uploaded_file($uploadfile_temporal,$nuevo_nom); 
           return 1;           
         }
         else
         {
          return -1;
         } 
    }else
    {

      // print_r($file['file']['type']);die();
      return -2;
    }

  }


  function actualizar_excel($file,$op)
  {

    print_r('dolas');die();

    // print_r($file);die();
    if($file['file']['type'] == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
    {
       $uploadfile_temporal=$file['file']['tmp_name'];
       $ruta = '../TEMP/';
         //$tipo = explode('/', $file['file']['type']);
       $nombre = '';
        if ($op==1) {
          $nombre = 'plantilla_masiva.csv';
        }elseif ($op==2) {
          $nombre = 'colores_act.xlsx';
        }elseif ($op==3) {
          $nombre = 'custodio_act.xlsx';
        }elseif ($op==4) {
          $nombre = 'estado_act.xlsx';
        }elseif ($op==5) {
          $nombre = 'genero_act.xlsx';
        }elseif ($op==6) {
          $nombre = 'emplazamiento_act.xlsx';
        }elseif ($op==7) {
          $nombre = 'marcas_act.xlsx';
        }elseif ($op==8) {
          $nombre = 'proyecto_act.xlsx';
        }
        
         $nuevo_nom=$ruta.$nombre;
         if (is_uploaded_file($uploadfile_temporal))
         {
           move_uploaded_file($uploadfile_temporal,$nuevo_nom); 
           return 1;           
         }
         else
         {
          return -1;
         } 
    }else
    {

      // print_r($file['file']['type']);die();
      return -2;
    }

  } 
}

?>