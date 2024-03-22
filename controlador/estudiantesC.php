<?php
include('../modelo/estudiantesM.php');
include('../modelo/contratosM.php');

$controlador = new estudiantesC();

if (isset($_GET['listar_todo'])) {
    echo json_encode($controlador->lista_estudiantes_todo());
}

if (isset($_GET['listar'])) {
    echo json_encode($controlador->lista_estudiantes($_POST['id']));
}

if (isset($_GET['buscar'])) {
    echo json_encode($controlador->buscar_estudiantes($_POST['buscar']));
}

if (isset($_GET['insertar'])) {
    echo json_encode($controlador->insertar_editar($_POST['parametros']));
}

if (isset($_GET['eliminar'])) {
    echo json_encode($controlador->eliminar($_POST['id']));
}

if (isset($_GET['listar_estudiante_representante'])) {
    echo json_encode($controlador->lista_estudiante_representante($_POST['id_representante']));
}

if (isset($_GET['listar_estudiante_representante_get'])) {
    echo json_encode($controlador->lista_estudiante_representante($_GET['id_representante']));
}

if (isset($_GET['SaveSeguros'])) {
    echo json_encode($controlador->SaveSeguros($_POST['parametros']));
}

if (isset($_GET['ListaSeguros'])) {
    echo json_encode($controlador->ListaSeguros($_POST['parametros']));
}

if (isset($_GET['EliminarSeguros'])) {
    echo json_encode($controlador->EliminarSeguros($_POST['id']));
}
if(isset($_GET['cargar_imagen_estudiantes']))
{
   echo json_encode($controlador->cargar_imagen_estudiantes($_FILES,$_POST));
}
//echo json_encode($controlador->buscar_estudiantes_ficha_medica(5));

class estudiantesC
{
    private $modelo;
    private $seguros;

    function __construct()
    {
        $this->modelo = new estudiantesM();
        $this->seguros = new contratosM();
    }

    function lista_estudiantes_todo()
    {
        $datos = $this->modelo->lista_estudiantes_todo();
        return $datos;
    }

    function lista_estudiantes($id)
    {
        $datos = $this->modelo->lista_estudiantes($id);
        return $datos;
    }

    function buscar_estudiantes($buscar)
    {
        $datos = $this->modelo->buscar_estudiantes($buscar);
        return $datos;
    }

    function insertar_editar($parametros)
    {
        $datos1[0]['campo'] = 'sa_est_cedula';
        $datos1[0]['dato'] = strval($parametros['sa_est_cedula']);

        $datos = array(
            array('campo' => 'sa_est_primer_apellido', 'dato' => $parametros['sa_est_primer_apellido']),
            array('campo' => 'sa_est_segundo_apellido', 'dato' => $parametros['sa_est_segundo_apellido']),
            array('campo' => 'sa_est_primer_nombre', 'dato' => $parametros['sa_est_primer_nombre']),
            array('campo' => 'sa_est_segundo_nombre', 'dato' => $parametros['sa_est_segundo_nombre']),
            array('campo' => 'sa_est_cedula', 'dato' => $parametros['sa_est_cedula']),
            array('campo' => 'sa_est_sexo', 'dato' => $parametros['sa_est_sexo']),
            array('campo' => 'sa_est_fecha_nacimiento', 'dato' => $parametros['sa_est_fecha_nacimiento']),
            array('campo' => 'sa_id_seccion', 'dato' => $parametros['sa_id_seccion']),
            array('campo' => 'sa_id_grado', 'dato' => $parametros['sa_id_grado']),
            array('campo' => 'sa_id_paralelo', 'dato' => $parametros['sa_id_paralelo']),
            array('campo' => 'sa_id_representante', 'dato' => $parametros['sa_id_representante']),
            array('campo' => 'sa_est_rep_parentesco', 'dato' => $parametros['sa_est_rep_parentesco']),
            array('campo' => 'sa_est_correo', 'dato' => $parametros['sa_est_correo']),
        );

        if ($parametros['sa_est_id'] == '') {
            if (count($this->modelo->buscar_estudiantes_CEDULA($datos1[0]['dato'])) == 0) {
                $datos = $this->modelo->insertar($datos);
            } else {
                return -2;
            }
        } else {
            $where[0]['campo'] = 'sa_est_id';
            $where[0]['dato'] = $parametros['sa_est_id'];
            $datos = $this->modelo->editar($datos, $where);
        }

        return $datos;
    }

    function eliminar($id)
    {
        $datos[0]['campo'] = 'sa_est_id';
        $datos[0]['dato'] = $id;
        $datos = $this->modelo->eliminar($datos);
        return $datos;
    }

    function lista_estudiante_representante($id)
    {
        $datos = $this->modelo->buscar_estudiantes_representante($id);
        return $datos;
    }
    //Validacion para determinar si tiene una ficha medica

    function SaveSeguros($parametros)
    {
        // print_r($parametros);die();
        //buscamos si el proveedor existe        
        $datos = $this->seguros->lista_proveedores($parametros['Proveedor'],$id=false);
        if(count($datos)==0)
        {
            // guardamos el proveedor
            $datosP[0]['campo'] = 'nombre';        
            $datosP[0]['dato'] = $parametros['Proveedor'];
            $this->modelo->add('PROVEEDOR',$datosP);
            $datos = $this->seguros->lista_proveedores($parametros['Proveedor'],$id=false);
        }
        if(count($datos)>0)
        {
            // buscamos is el seguro que va a ingresar ya existe
            $datosSR = $this->seguros->buscar_seguro(false,$datos[0]['id'],false,false,false,false,$parametros['seguro'],1);

            if(count($datosSR)==0)
            {
                $datosS[0]['campo'] = 'proveedor';        
                $datosS[0]['dato'] = $datos[0]['id'];
                $datosS[1]['campo'] = 'plan_seguro';        
                $datosS[1]['dato'] = trim($parametros['seguro']);
                $datosS[2]['campo'] = 'terceros';        
                $datosS[2]['dato'] = 1;
                $this->modelo->add('SEGUROS',$datosS);
                $datosSR = $this->seguros->buscar_seguro(false,$datos[0]['id'],false,false,false,false,$parametros['seguro'],1);
            }

            if(count($datosSR)>0)
            {
                if($parametros['todos']=='true')
                {
                    $id = substr($parametros['ids'],0,-1);
                    $id = explode(',',$id);
                    foreach ($id as $key => $value) {
                        $tabla = 'estudiantes';
                        if(isset($parametros['tabla'])){$tabla = $parametros['tabla'];}
                        $datosEstAse = $this->seguros->lista_articulos_seguro2($tabla,$value,$_SESSION['INICIO']['MODULO_SISTEMA'],$datosSR[0]['id_contratos']);
                        if(count($datosEstAse)==0)
                        {
                            $datosSE[0]['campo'] = 'id_seguro';        
                            $datosSE[0]['dato'] = $datosSR[0]['id_contratos'];
                            $datosSE[1]['campo'] = 'id_articulos';        
                            $datosSE[1]['dato'] = $value;
                            $datosSE[2]['campo'] = 'tabla';        
                            $datosSE[2]['dato'] = $tabla;
                            $datosSE[3]['campo'] = 'modulo';        
                            $datosSE[3]['dato'] = $_SESSION['INICIO']['MODULO_SISTEMA'];
                            $this->modelo->add('ARTICULOS_ASEGURADOS',$datosSE);
                        }
                    }

                    return 1;

                }else
                {
                    $tabla = 'estudiantes';
                    if(isset($parametros['tabla'])){$tabla = $parametros['tabla'];}
                       
                    $datosEstAse = $this->seguros->lista_articulos_seguro2($tabla,$parametros['estudiantes'],$_SESSION['INICIO']['MODULO_SISTEMA'],$datosSR[0]['id_contratos']);
                    if(count($datosEstAse)==0)
                    {
                        $datosSE[0]['campo'] = 'id_seguro';        
                        $datosSE[0]['dato'] = $datosSR[0]['id_contratos'];
                        $datosSE[1]['campo'] = 'id_articulos';        
                        $datosSE[1]['dato'] = $parametros['estudiantes'];
                        $datosSE[2]['campo'] = 'tabla';        
                        $datosSE[2]['dato'] = $tabla;
                        $datosSE[3]['campo'] = 'modulo';        
                        $datosSE[3]['dato'] = $_SESSION['INICIO']['MODULO_SISTEMA'];
                        return $this->modelo->add('ARTICULOS_ASEGURADOS',$datosSE);
                    }else
                    {
                        return -2;
                    }
                }
            }
        }

    }

    function ListaSeguros($parametros)
    {
        $tr = '';
        $id = substr($parametros['estudiantes'], 0,-1);
        $ids = explode(',', $id);
        foreach ($ids as $key => $value) {
            $datos = $this->seguros->lista_articulos_seguro_detalle('estudiantes',$value,$_SESSION['INICIO']['MODULO_SISTEMA'],'sa_est_id');
            foreach ($datos as $key2 => $value2) {
                $tr.='<tr>';
                 if($value2['terceros']==1)
                {
                    $tr.='<td><button class="btn btn-sm btn-danger" onclick="eliminar_seguro('.$value2['id_arti_asegurados'].')"><i class="bx bx-trash me-0"></i></button></td>';
                }else
                {
                    $tr.='<td><span class="badge bg-warning text-dark">Por Defecto</span></td>';
                }
                $tr.='<td>'.$value2['nombre'].'</td>
                <td>'.$value2['plan_seguro'].'</td>
                <td>'.$value2['sa_est_primer_apellido'].' '.$value2['sa_est_segundo_apellido'].' '.$value2['sa_est_primer_nombre'].' '.$value2['sa_est_segundo_nombre'].'</td>
                </tr>';
            }
        }


        return $tr;
        // print_r($parametros);die();
    }

    function EliminarSeguros($id)
    {
        return $this->seguros->Articulo_contrato_delete($id);
    }

    function cargar_imagen_estudiantes($file,$post)
    {       
        // print_r($file);print_r($post);die();
        $id = $post['txt_idEst'];
        $ruta='../img/estudiantes/';//ruta carpeta donde queremos copiar las imágenes
        if (!file_exists($ruta)) {
           mkdir($ruta, 0777, true);
        }
        if($this->validar_formato_img($file['file_estudiante_img_'.$id])==1)
        {
             $uploadfile_temporal=$file['file_estudiante_img_'.$id]['tmp_name'];
             $tipo = explode('/', $file['file_estudiante_img_'.$id]['type']);
             $nombre = $post['name_img'].'.'.$tipo[1];          
             $nuevo_nom=$ruta.$nombre;
             if (is_uploaded_file($uploadfile_temporal))
             {
               move_uploaded_file($uploadfile_temporal,$nuevo_nom);

                  $datosI[0]['campo']='sa_est_foto_url';
                  $datosI[0]['dato'] = $nuevo_nom;
                  $where[0]['campo'] = 'sa_est_id';
                  $where[0]['dato'] =  $id;

                  $base = $this->modelo->editar($datosI,$where);

                 //$resp = $this->modelo->generar_primera_vez($_SESSION['INICIO']['BASEDATO'],$_SESSION['INICIO']['ID_EMPRESA']);


               if($base==1)
               {
                return 1;
               }else
               {
                return -1;
               }

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
    function validar_formato_img($file)
      {
        switch ($file['type']) {
          case 'image/jpeg':
          case 'image/pjpeg':
          case 'image/gif':
          case 'image/png':
             return 1;
            break;      
          default:
            return -1;
            break;
        }

      }
}
