<?php //include('../cabeceras/header.php');  ?>
<script type="text/javascript">
  $( document ).ready(function() {
    cargar_modulos_sis();
    cargar_modulos_sis_ddl();
    cargar_modulos_sis_ddl_pag();
    cargar_modulos_sis_ddl_pag_ing();
    cargar_modulos();
    lista_paginas();
    cargar_modulos_ddl();
    cargar_modulos_pag();
  });

   function lista_paginas()
  {
    var parametros = 
    {
      'modulo_sis':$('#ddl_modulos_sis').val(),
      'modulo':$('#ddl_modulos').val(),
      'query':$('#txt_pagina').val(),
    }

    $.ajax({
         data:  {parametros:parametros},
         url:   '../controlador/modulos_paginasC.php?lista_paginas=true',
         type:  'post',
         dataType: 'json',
         async:false,
           success:  function (response) {  
            console.log(response);
           
            $('#tbl_paginas').html(response);
            // accesos_asignados();
           
          } 
          
       });
  }

function cargar_modulos_ddl()
{   
  parametros =
  {
    'modulo_sis':$('#ddl_modulos_sis_pag').val(),
  }
  $.ajax({
       data:  {parametros:parametros},
       url:   '../controlador/tipo_usuarioC.php?modulos=true',
       type:  'post',
       dataType: 'json',
         success:  function (response) {  
          // console.log(response);
          $('#ddl_modulos').html(response);
         
        } 
        
     });
}

function cargar_modulos_sis_ddl()
{   
  $.ajax({
       // data:  {parametros:parametros},
       url:   '../controlador/modulos_paginasC.php?modulos_sis_ddl=true',
       type:  'post',
       dataType: 'json',
         success:  function (response) {  
          // console.log(response);
         if (response) 
         {
          $('#ddl_modulos_sis').html(response);
         } 
        } 
        
     });
}

function cargar_modulos_sis_ddl_pag()
{   
  $.ajax({
       // data:  {parametros:parametros},
       url:   '../controlador/modulos_paginasC.php?modulos_sis_ddl=true',
       type:  'post',
       dataType: 'json',
         success:  function (response) {  
          // console.log(response);
         if (response) 
         {
          $('#ddl_modulos_sis_pag').html(response);
         }
         cargar_modulos_ddl(); 
        } 
        
     });
}

function cargar_modulos_sis_ddl_pag_ing()
{   
  $.ajax({
       // data:  {parametros:parametros},
       url:   '../controlador/modulos_paginasC.php?modulos_sis_ddl=true',
       type:  'post',
       dataType: 'json',
         success:  function (response) {  
          // console.log(response);
         if (response) 
         {
          $('#ddl_modulos_sis_pag_ing').html(response);
         }
          cargar_modulos_ddl_ing()
        } 
        
     });
}

function cargar_modulos_ddl_ing(id='')
{   
  parametros =
  {
    'modulo_sis':$('#ddl_modulos_sis_pag_ing'+id).val(),
    'tipo':2,
  }
  $.ajax({
       data:  {parametros:parametros},
       url:   '../controlador/tipo_usuarioC.php?modulos=true',
       type:  'post',
       dataType: 'json',
         success:  function (response) {  
          // console.log(response);
          $('#ddl_modulos_pag_ing'+id).html(response);
         
        } 
        
     });
}

function cargar_modulos_sis()
  {
  var parametros = {'query':''};   
    $.ajax({
         data:  {parametros:parametros},
         url:   '../controlador/modulos_paginasC.php?modulos_sis_tabla=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {  
            // console.log(response);
           if (response) 
           {
            $('#tbl_modulos_sis').html(response);
           } 
          } 
          
       });
  }


function cargar_modulos()
  {   
    $.ajax({
         // data:  {parametros:parametros},
         url:   '../controlador/tipo_usuarioC.php?modulos_tabla=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {  
            // console.log(response);
           if (response) 
           {
            $('#tbl_modulos').html(response);
           } 
          } 
          
       });
  }

  function cargar_modulos_pag()
  {   
    $.ajax({
         // data:  {parametros:parametros},
         url:   '../controlador/tipo_usuarioC.php?modulos=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {  
            // console.log(response);
           if (response) 
           {
            html = response.replace('Todos','Modulos');
            $('#ddl_modulos_pag').html(html);
           } 
          } 
          
       });
  }

  function guardar_modulos(id='')
  {
    var query = $('#txt_modulo'+id).val();
    if(query=='')
    {
      Swal.fire('El campo no puede estar vacio','Asegurese de llenar el campo','info');
      return false;
    }
    var parametros = 
    {
      'modulo':query,
      'id':id,
      'icono':$('#ddl_icono'+id).val(),
      'detalle':$('#txt_detalle'+id).val(),
      'modulo_sis':$('#ddl_modulos_sis'+id).val(),
    }
     $.ajax({
         data:  {parametros:parametros},
         url:   '../controlador/modulos_paginasC.php?guardar_modulos=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {  
            console.log(response);
            if(response==1)
            {
              Swal.fire('Modulo guardado','','success');
            }cargar_modulos();
           
          } 
          
       });
  }
  function eliminar_modulos(id)
  {
    Swal.fire({
      title: 'Quiere eliminar este registro?',
      text: "Esta seguro de eliminar este registro!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
        if (result.value) { 
          eliminar(id);
        }
      });
  }

  function eliminar(id)
  {    
     $.ajax({
         data:  {id:id},
         url:   '../controlador/modulos_paginasC.php?eliminar_modulos=true',
         type:  'post',
         dataType: 'json',
           success:  function (response) {  
            console.log(response);
            if(response==1)
            {
              Swal.fire('Registro Eliminado','','success');
            }cargar_modulos();
           
          } 
          
       });
  }

   function eliminar_pagina(id)
  {
    Swal.fire({
      title: 'Quiere eliminar este registro?',
      text: "Esta seguro de eliminar este registro!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
        if (result.value) { 
          eliminar_pag(id);
        }
      });
  }

  function eliminar_pag(id)
  {    
     $.ajax({
         data:  {id:id},
         url:   '../controlador/modulos_paginasC.php?eliminar_pagina=true',
         type:  'post',
         dataType: 'json',
           success:  function (response) {  
            console.log(response);
            if(response==1)
            {
              Swal.fire('Registro Eliminado','','success');
            } lista_paginas();
           
          } 
          
       });
  }

function guardar_pagina(id='')
  {
    var pagina = $('#txt_pagina_new'+id).val();
    var detalle = $('#txt_detalle_pag'+id).val();
    var url = $('#txt_url'+id).val();
    var modulo = $('#ddl_modulos_pag_ing'+id).val();
    var modulo_sis = $('#ddl_modulos_sis_pag_ing'+id).val();
    var icono = $('#ddl_icono_pag'+id).val();

    if(pagina=='' || detalle=='' || url=='' || modulo=='')
    {
      Swal.fire('Asegurese de llenar todos los datos','Uno de los campos esta vacio','info');
      return false;
    }
    if(id!='')
    {
      d = $('#rbl_defaul'+id).prop('checked')
      a = $('#rbl_activo'+id).prop('checked')
      s = $('#rbl_subpag'+id).prop('checked')
    }else
    {
      d = $('#rbl_defaul').prop('checked')
      a = $('#rbl_estado').prop('checked')
      s = $('#rbl_subpag').prop('checked')
    }
    var parametros = 
    {
      'modulo_sis':modulo_sis,
      'modulo':modulo,
      'id':id,
      'pagina':pagina,
      'detalle':detalle,
      'url':url,
      'icono':icono,
      'defaul':d,
      'activo':a,
      'subpag':s,
    }
     $.ajax({
         data:  {parametros:parametros},
         url:   '../controlador/modulos_paginasC.php?guardar_paginas=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {  
            console.log(response);
            if(response==1)
            {
              Swal.fire('Pagina guardada','','success');
            }
    lista_paginas();
    limpiar_pag();
           
          } 
          
       });
  }

  function default_pag(id)
  {
    var op = $('#rbl_defaul'+id).prop('checked');
    var parametros = 
    {
      'op':op,
      'id':id,
    }
     $.ajax({
         data:  {parametros:parametros},
         url:   '../controlador/modulos_paginasC.php?defaul_paginas=true',
         type:  'post',
         dataType: 'json',        
           success:  function (response) {  
            console.log(response);                       
          }           
       });
  }

   function subpag(id)
  {
    var op = $('#rbl_subpag'+id).prop('checked');

    // console.log(id);console.log(op);
    var parametros = 
    {
      'op':op,
      'id':id,
    }
     $.ajax({
         data:  {parametros:parametros},
         url:   '../controlador/modulos_paginasC.php?sub_pagina=true',
         type:  'post',
         dataType: 'json',        
           success:  function (response) {  
            console.log(response);                       
          }           
       });
  }

  function activo_pag(id)
  {
    var op = $('#rbl_activo'+id).prop('checked');
    var parametros = 
    {
      'op':op,
      'id':id,
    }
     $.ajax({
         data:  {parametros:parametros},
         url:   '../controlador/modulos_paginasC.php?activo_paginas=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {  
            console.log(response);
                       
          } 
          
       });
  }

  function limpiar_pag()
  {
     $('#txt_pagina_new').val('');
     $('#txt_detalle_pag').val('');
     $('#txt_url').val('');
     $('#ddl_modulos_pag').val('');
     $('#ddl_icono_pag').val("<i class='far fa-circle nav-icon'></i>");

  }

  function editar_modulo(id='')
  {
    var nombre = $('#txt_modulo_sis'+id).val();
    var link = $('#txt_link_sis'+id).val();
    var icono = $('#txt_icono_sis'+id).val();
    var estado = $('#rbl_estado'+id).prop('checked');
    if(nombre=='' || link=='' || icono =='')
    {
      Swal.fire('','Llene todos los campos','info');
      return false;
    }
    var parametros = 
    {
      'nombre':nombre,
      'link':link,
      'icono':icono,
      'estado':estado,
      'id':id,
    }
    $.ajax({
       data:  {parametros:parametros},
       url:   '../controlador/modulos_paginasC.php?editar_modulo_sis=true',
       type:  'post',
       dataType: 'json',
       /*beforeSend: function () {   
            var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
          $('#tabla_').html(spiner);
       },*/
         success:  function (response) {  
          if(response==1)
          {
            if(id=='')
            {
             Swal.fire('','Registro guardado','success');
            }else
            {
             Swal.fire('','Registro editado','success');              
            }

            cargar_modulos_sis();
            cargar_modulos_sis_ddl()
            cargar_modulos_sis_ddl_pag()
          }
        } 
        
     });

  }
</script>

<div class="page-wrapper">
      <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
          <div class="breadcrumb-title pe-3">Administracion</div>
          <div class="ps-3">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Modulos y paginas</li>
              </ol>
            </nav>
          </div>         
        </div>
        <!--end breadcrumb-->
        <div class="row">
          <div class="col-xl-12 mx-auto">
            <hr>
            <div class="card">
              <div class="card-body">
                <ul class="nav nav-tabs nav-danger" role="tablist">
                  <li class="nav-item" role="presentation">
                    <a class="nav-link active" data-bs-toggle="tab" href="#dangermodulos" role="tab" aria-selected="true">
                      <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class="bx bx-home font-18 me-1"></i>
                        </div>
                        <div class="tab-title">Modulos</div>
                      </div>
                    </a>
                  </li>
                  <li class="nav-item" role="presentation">
                    <a class="nav-link" data-bs-toggle="tab" href="#dangerhome" role="tab" aria-selected="true">
                      <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class="bx bx-home font-18 me-1"></i>
                        </div>
                        <div class="tab-title">Menus</div>
                      </div>
                    </a>
                  </li>
                  <li class="nav-item" role="presentation">
                    <a class="nav-link" data-bs-toggle="tab" href="#dangerprofile" role="tab" aria-selected="false" tabindex="-1">
                      <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class="bx bx-user-pin font-18 me-1"></i>
                        </div>
                        <div class="tab-title">Paginas</div>
                      </div>
                    </a>
                  </li>                  
                </ul>
                <div class="tab-content py-3">
                  <div class="tab-pane fade show active" id="dangermodulos" role="tabpanel">
                    <div class="row">
                      <table class="table">
                        <tr>
                          <td>
                            <input type="" name="txt_modulo_sis" id="txt_modulo_sis" class="form-control form-control-sm" placeholder="Nombre de modulo">
                          </td>
                          <td>
                            <input type="" name="txt_link_sis" id="txt_link_sis" class="form-control form-control-sm" placeholder="Link inicio">
                          </td>
                          <td>
                            <input type="" name="txt_icono_sis" id="txt_icono_sis" class="form-control form-control-sm" placeholder="icono">
                          </td>
                          <td class="text-center">
                            Activo <br>
                            <input type="checkbox" name="rbl_estado" id="rbl_estado" class="" placeholder="Link inicio" checked>
                          </td>
                          
                          <td>
                            <button class="btn btn-primary btn-sm" onclick="editar_modulo()">Agregar</button>
                          </td>
                        </tr>
                        <tbody id="tbl_modulos_sis">
                          
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="tab-pane fade" id="dangerhome" role="tabpanel">
                    <div class="row">
                      <table class="table">
                          <tr>
                            <td>
                              <select class="form-select form-select-sm" id="ddl_modulos_sis">
                                <option value="">Seleccione modulo</option>
                              </select>
                            </td>
                          <td> <input type="text"  class="form-control form-control-sm" name="txt_modulo" id="txt_modulo" placeholder="Nombre de menu">
                          </td>
                          <td>
                            <input type="text"  class="form-control form-control-sm" name="txt_detalle" id="txt_detalle" placeholder="Descripcion de menu">                       
                          </td>
                          <td>
                            <select class="bx" id="ddl_icono" name="ddl_icono"> 
                                  <option class="bx" value="e9be" > ICONO</option>
                                  <option class="bx" value="ea75" > &#xea75;</option>
                                  <option class="bx" value="e95f" > &#xe95f;</option>
                                  <option class="bx" value="e9be" > &#xe9be;</option>
                                  <option class="bx" value="eb2b" > &#xeb2b;</option>
                                  <option class="bx" value="ea5c" > &#xea5c; </option>
                                  <option class="bx" value="eaab" > &#xeaab;</option>
                                  <option class="bx" value="e9e6" > &#xe9e6;</option>
                                  <option class="bx" value="ea1a" > &#xea1a;</option>
                                  <option class="bx" value="ea37" > &#xea37;</option>
                                  <option class="bx" value="ebbf" > &#xebbf;</option>
                                  <option class="bx" value="ea6f" > &#xea6f;</option>
                                  <option class="bx" value="ea21" > &#xea21;</option>
                                  <option class="bx" value="e9d0" > &#xe9d0;</option>
                                  <option class="bx" value="e9ba" > &#xe9ba;</option>
                                  <option class="bx" value="e91a" > &#xe91a;</option>
                                  <option class="bx" value="e919" > &#xe919;</option>
                                  <option class="bx" value="e982" > &#xe982;</option>
                                  <option class="bx" value="eb43" > &#xeb43;</option>
                                  <option class="bx" value="e9f7" > &#xe9f7;</option>
                              </select> 
                          </td>

                          <td>
                            <button class="btn btn-sm btn-primary"  onclick="guardar_modulos()"><i class="bx bx-plus"></i></i> Agregar</button> 
                          </td>
                        </tr>
                      </table>
                      <table class="table table-hover">                    
                        <thead>
                          <th>Modulo</th>
                          <th>Menu</th>
                          <th>Detalle</th>
                          <th>Icono</th>
                          <th></th>
                        </thead>
                        <tbody id="tbl_modulos">
                          <tr>
                            <td colspan="2">No se encontraron tipos de usuario</td>
                          </tr>
                        </tbody>
                      </table>                  
                    </div>
                  </div>
                  <div class="tab-pane fade" id="dangerprofile" role="tabpanel">

                <div class="row">
                  <div class="col-sm-3">
                    Modulo de sistema
                    <select class="form-select form-select-sm" id="ddl_modulos_sis_pag_ing" onchange="cargar_modulos_ddl_ing()">
                        <option value="">Seleccione modulo</option>
                    </select>                    
                  </div>
                  <div class="col-sm-2">
                    <b>Menu</b>
                    <select class="form-select form-select-sm" id="ddl_modulos_pag_ing" name="ddl_modulos_pag_ing"> 
                      <option>Menu</option>
                    </select>
                  </div>
                  <div class="col-sm-3">
                    <b>Nombre en menu</b>
                    <input type="" name="txt_pagina_new" id="txt_pagina_new" class="form-control form-control-sm">
                  </div>
                  <div class="col-sm-3">
                    <b>link</b>
                    <input type="" name="txt_url" id="txt_url" class="form-control form-control-sm">
                  </div>
                  <div class="col-sm-1">
                    <b>Icono</b>
                    <select class="bx form-select form-select-sm" id="ddl_icono_pag" name="ddl_icono_pag"> 
                         <option class="bx" value="e9be" > ICONO</option>
                            <option class="bx" value="ea75" > &#xea75;</option>
                            <option class="bx" value="e95f" > &#xe95f;</option>
                            <option class="bx" value="e9be" > &#xe9be;</option>
                            <option class="bx" value="eb2b" > &#xeb2b;</option>
                            <option class="bx" value="ea5c" > &#xea5c; </option>
                            <option class="bx" value="eaab" > &#xeaab;</option>
                            <option class="bx" value="e9e6" > &#xe9e6;</option>
                            <option class="bx" value="ea1a" > &#xea1a;</option>
                            <option class="bx" value="ea37" > &#xea37;</option>
                            <option class="bx" value="ebbf" > &#xebbf;</option>
                            <option class="bx" value="ea6f" > &#xea6f;</option>
                            <option class="bx" value="ea21" > &#xea21;</option>
                            <option class="bx" value="e9d0" > &#xe9d0;</option>
                            <option class="bx" value="e9ba" > &#xe9ba;</option>
                            <option class="bx" value="e91a" > &#xe91a;</option>
                            <option class="bx" value="e919" > &#xe919;</option>
                            <option class="bx" value="e982" > &#xe982;</option>
                            <option class="bx" value="eb43" > &#xeb43;</option>
                            <option class="bx" value="e9f7" > &#xe9f7;</option>
                    </select> 

                  </div>
                  <div class="col-sm-4">
                    <b>Detalle</b>
                      <textarea class="form-control form-control-sm" rows="1" id="txt_detalle_pag" name="txt_detalle_pag" ></textarea> 
                  </div>
                  
                  <div class="col-sm-2"><br>
                    <label> Por default
                    <input type="checkbox" name="rbl_defaul" id="rbl_defaul"></label>
                  </div>
                  <div class="col-sm-2"><br>
                    <label> Sub pagina
                    <input type="checkbox" name="rbl_subpag" id="rbl_subpag"></label>
                  </div>
                  <div class="col-sm-2"><br>
                    <label> Estado Activo
                    <input type="checkbox" name="rbl_estado" id="rbl_estado" checked></label>
                  </div>                        
                  
                  <div class="col-sm-2"><br>
                    <button class="btn btn-primary btn-sm" onclick="guardar_pagina();"><i class="bx bx-save"></i> Guardar</button>
                  </div>                      
                </div>
                <hr>
                   <div class="row"><br> 
                    <div class="col-sm-4">
                      <b>Modulo</b>
                      <select class="form-select form-select-sm" id="ddl_modulos_sis_pag" onchange="cargar_modulos_ddl();">
                            <option value="">Seleccione modulo</option>
                          </select>                      
                    </div>
                    <div class="col-sm-4">
                    <b>Menu</b>
                    <select class="form-select form-select-sm" id="ddl_modulos" name="ddl_modulos" onchange="lista_paginas()">
                      <option value="">Modulos</option>
                    </select>                    
                  </div>
                 <div class="col-sm-4">
                  <b>Buscar pagina</b>
                    <input type="text" name="txt_pagina" id="txt_pagina" placeholder="Buscar pagina" class="form-control form-control-sm" onkeyup="lista_paginas()">             
                  </div>      
                </div>
                <div class="row" style="overflow-x: scroll; overflow-y: scroll; height: 500px;">                
                <table class="table" id="table_paginas">
                    <thead>
                      <th>Modulo</th>
                      <th>Nombre en menu</th>
                      <th>Detalle</th>
                      <th>link</th>
                      <th>Menu</th>
                      <th>Default</th>
                      <th>sub pagina</th>
                      <th>Activo</th>
                      <th width="10%">Icono</th>
                       <th></th>
                    </thead>
                    <tbody id="tbl_paginas">
                      <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td width="15px" class="text-center"><input type="checkbox" name="" id="" checked></td>
                        <td width="15px" class="text-center"><input type="checkbox" name="" id="" checked></td>
                        <td><i class="fa fa-plus"></i></td>
                        <td><button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button></td>
                      </tr>
                    </tbody>
                </table>
              </div>
            </div>
              
                  </div>                  
                </div>
              </div>
            </div>
          </div>
        </div>
        <!--end row-->
      </div>
    </div>


<?php //include('../cabeceras/footer.php');?>
