<?php 
//include('../cabeceras/header.php');?>
<script type="text/javascript">
  $( document ).ready(function() {
    lista_tipo_usuario();
    // modulos_acceso('1');

    $('#txt_tipo_usuario_new').autocomplete({
       source: function( request, response ) {
                
                $.ajax({
                    url:  '../controlador/tipo_usuarioC.php?lista_usuarios_all=true',
                    type: 'post',
                    dataType: "json",
                    data: {
                        search: request.term
                    },
                    success: function( data ) {
                      console.log(data);
                        response( data );
                    }
                });
            },
            select: function (event, ui) {
                $('#txt_id_tipo_usu_empresa').val(ui.item.value); // display the selected text
                $('#txt_tipo_usuario_new').val(ui.item.label); // display the selected text
                return false;
            },
            focus: function(event, ui){
                 $('#txt_tipo_usuario_new').val(ui.item.label); // display the selected text
                
                return false;
            },
    });

  });

  function lista_tipo_usuario()
  {
    $.ajax({
         // data:  {parametros:parametros},
         url:   '../controlador/tipo_usuarioC.php?lista_usuarios=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {  
           if (response) 
           {
            $('#tipo_usuario').html(response);
           } 
          } 
          
       });
  }
 



  function eliminar_usuario_tipo(id)
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

    $.ajax({
         data:  {id:id},
         url:   '../controlador/tipo_usuarioC.php?eliminar_usuario_tipo=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) { 
           if(response==1)
           {
            Swal.fire('','Registro eliminado.','success');
           } else if(response == -2)
           {
             Swal.fire('','El tipo de usuario esta ligado a uno o varios usuario o paginas y no se podra eliminar.','error')
           }else
           {
            Swal.fire('','No se pudo elimnar.','info')
           }
          } 
          
       });}
      });

   }


  function accesos_asignados()
  {
    // var perfil = $('#ddl_perfil').val();
    var usuario_perfil = $('#ddl_usuario_perfil').val();
    parametros = 
    {
      // 'perfil':perfil,
      'usuario':usuario_perfil,
    }   
    $.ajax({
         data:  {parametros:parametros},
         url:   '../controlador/tipo_usuarioC.php?accesos_asignados=true',
         type:  'post',
         dataType: 'json',
           success:  function (response) {  
           if (response) 
           {
             console.log(response);
             response.forEach(function(item,i){

              $('#ver_'+item.pag).prop('checked',item.Ver);
              $('#edi_'+item.pag).prop('checked',item.editar);
              $('#eli_'+item.pag).prop('checked',item.eliminar);
             })
           } 
          } 
          
       });
  }

  function guardar_accesos_edi(id)
  {
    var perfil = $('#ddl_perfil').val();
    if(perfil=='')
    {
      $('#edi_'+id).prop('checked',false);
      Swal.fire('Seleccione un perfil','','info');
      return false;
    }
    parametros= 
    {
      'pag':id,
      'perfil':$('#ddl_usuario_perfil').val(),
      'ver':$('#ver_'+id).prop('checked'),
      'edi':$('#edi_'+id).prop('checked'),
      'eli':$('#eli_'+id).prop('checked'),
    } 
    $.ajax({
         data:  parametros,
         url:   '../controlador/tipo_usuarioC.php?accesos_guardar_edi=true',
         type:  'post',
         dataType: 'json',
         // beforeSend: function () {   
         //      var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
         //    $('#tabla_').html(spiner);
         // },
           success:  function (response) {  
           if (response==1) 
           {
             // Swal.fire('Acceso modificado','','success');            
           } 
          } 
          
       });
  }

   function cargar_usuarios(id)
  {
    $('#usuarios_con_tipo').modal('show');
    $.ajax({
         data:  {id:id},
         url:   '../controlador/tipo_usuarioC.php?cargar_usuarios=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {  
           $('#tbl_usuarios').html(response);
          } 
          
       });
  }


   function eliminar_tipo(id)
  {
     Swal.fire({
      title: 'Quiere eliminar este registro?',
      text: "Esta seguro de eliminar este registro!",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
        if (result.value) {

    $.ajax({
         data:  {id:id},
         url:   '../controlador/tipo_usuarioC.php?eliminar_tipo=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) { 
           if(response==1)
           {
            Swal.fire('','Registro eliminado.','success');
            lista_tipo_usuario();
           } else if(response == -2)
           {
             Swal.fire('','El tipo de usuario esta ligado a uno o varios usuario o paginas y no se podra eliminar.','error')
           }else
           {
            Swal.fire('','No se pudo elimnar.','info')
           }
          } 
          
       });}
      });

   }

  function add_tipo(i='')
  {
    console.log(i);
    if(i)
    {
      console.log('enn');
      var ti = $('#txt_tipo_usuario_'+i).val();
      var id = i;
      var tipo_usu_empresa  = '';
    }else
    {
      console.log('dasd');
      var ti = $('#txt_tipo_usuario_new').val();
      var id = $('#txt_tipo_usuario_update').val();
      var tipo_usu_empresa = $('#txt_id_tipo_usu_empresa').val();
    }
    if(ti=='')
    {
      Swal.fire('','Asegurese de llenar todo los campos.','info')
      return false;
    }
    var parametros = 
    {
      'tipo':ti,
      'id':id,
      'tipo_usu_empresa':tipo_usu_empresa,
    };
    $.ajax({
         data:  {parametros,parametros},
         url:   '../controlador/tipo_usuarioC.php?guardar_tipo=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {  
           if (response==1) 
           {
            $('#nuevo_tipo_usuario').modal('hide');
            lista_tipo_usuario();

            if(id!='')
            {
              Swal.fire(
                  '',
                  'Registro Editado.',
                  'success');

            }else{
            Swal.fire(
                  '',
                  'Registro agregado.',
                  'success');
          }
            $('#txt_tipo_usuario_new').val('');
            $('#tipo_usu_empresa').val('');
            $('#txt_tipo_usuario_update').val('');
            $('#btn_opcion').text('Guardar');
            $('#exampleModalLongTitle').text('Nuevo tipo de usuario');
           }else
           {

            $('#nuevo_tipo_usuario').modal('hide');
            Swal.fire(
                  '',
                  'No se pudo guardar intente mas tarde.',
                  'info');

            $('#txt_tipo_usuario_new').val('');
            $('#txt_tipo_usuario_update').val('');
            $('#btn_opcion').text('Guardar');
           } 
          } 
          
       });

  }
  function update(id,nombre)
  {
     $('#nuevo_tipo_usuario').modal('show');
     $('#txt_tipo_usuario_new').val(nombre);
     $('#txt_tipo_usuario_update').val(id);
     $('#btn_opcion').text('Editar');
     $('#exampleModalLongTitle').text('Editar tipo de usuario');
   }

   function guardar_modulos()
   {
     var modulos = [];
     $.each($("input[name='modulos']:checked"), function(){
         modulos.push($(this).val());
     });
     var tip = $('#tipo_select').val();
     var parametros = 
     {
       'modulos':modulos,
       'tipo': tip,
     }
      $.ajax({
         data:  {parametros:parametros},
         url:   '../controlador/tipo_usuarioC.php?guardar_modulos=true',
         type:  'post',
         dataType: 'json',
           success:  function (response) { 
             Swal.fire('','Modulo activado.','success');
          } 
          
       });
   }

  

   function marcar_todo_edit()
   {
     var perfil = $('#ddl_perfil').val();
     if(perfil=='')
     {
       Swal.fire('Seleccione un pefil de usuario','','info');
       return false;
     }

   }
   function marcar_todo_delet()
   {
     var perfil = $('#ddl_perfil').val();
     if(perfil=='')
     {
       Swal.fire('Seleccione un pefil de usuario','','info');
       return false;
     }

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
                <li class="breadcrumb-item active" aria-current="page">Roles de usuario</li>
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
                    <a class="nav-link active" data-bs-toggle="tab" href="#dangerhome" role="tab" aria-selected="true">
                      <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class="bx bx-user-circle font-18 me-1"></i>
                        </div>
                        <div class="tab-title">Perfiles</div>
                      </div>
                    </a>
                  </li>                  
                </ul>
                <div class="tab-content py-3">
                  <div class="tab-pane fade active show" id="dangerhome" role="tabpanel">
                    <div class="row">
                      <div class="col-sm-6">
                        <input type="text"  class="form-control form-control-sm" name="txt_tipo_usuario_new" id="txt_tipo_usuario_new" placeholder="Nombre">
                        <input type="hidden"  class="form-control form-control-sm" name="txt_id_tipo_usu_empresa" id="txt_id_tipo_usu_empresa" placeholder="Nombre">
                      </div>
                      <div class="col-sm-6">
                        <button class="btn btn-sm btn-primary btn-sm" onclick="add_tipo();"><i class="bx bx-plus font-18 me-1"></i></i> Agregar</button>                         
                      </div>
                    </div>
                     <div class="row">                        
                        <table class="table">                    
                          <thead>
                            <th scope="col">Tipo de usuario</th>
                            <th scope="col"></th>
                          </thead>
                          <tbody id="tipo_usuario">
                            <tr>
                              <td colspan="2">No se encontraron tipos de usuario</td>
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
        <!--end row-->
      </div>
    </div>

<!-- Modal nueva categoria-->
<div class="modal fade" id="nuevo_tipo_usuario" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Nuevo tipo de usuario</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12">
            Nombre de tipo de usuario
            <input type="hidden" name="txt_tipo_usuario_update" id="txt_tipo_usuario_update">
            <input type="text"  class="form-control form-control-sm" name="txt_tipo_usuario_new" id="txt_tipo_usuario_new">            
          </div>
        </div>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-primary" onclick="add_tipo();" id="btn_opcion">Guardar</button>
          <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">Cerrar</button>
        </div>
    </div>
  </div>
</div>

<div class="modal fade" id="usuarios_con_tipo" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="title_usuarios">Usuarios asignados a este tipo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row" id="tbl_usuarios">
          
        </div>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">Cerrar</button>
        </div>
    </div>
  </div>
</div>



<?php //include('../cabeceras/footer.php');  ?>
