<?php 
//include('../cabeceras/header.php');?>
<script type="text/javascript">
  $( document ).ready(function() {
    usuarios();
    lista_paginas();
    lista_tipo_usuario_drop_pagina();
    lista_modulos();  
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


    // $( "#txt_tipo_usuario_new" ).autocomplete({
    //   source: [
    //       "ActionScript",
    //       "AppleScript",
    //       "Asp",
    //       "BASIC",
    //       "C",
    //       "C++",
    //       "Clojure",
    //       "COBOL",
    //       "ColdFusion",
    //       "Erlang",
    //       "Fortran",
    //       "Groovy",
    //       "Haskell",
    //       "Java",
    //       "JavaScript",
    //       "Lisp",
    //       "Perl",
    //       "PHP",
    //       "Python",
    //       "Ruby",
    //       "Scala",
    //       "Scheme"
    //     ],
    // });


  });



   function lista_tipo_usuario_drop_pagina()
  {
    $.ajax({
         // data:  {parametros:parametros},
         url:   '../controlador/tipo_usuarioC.php?lista_usuarios_drop=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {  
           if (response) 
           {

            response = '<option value="">Seleccione perfil</option>'+response;
            $('#ddl_perfil').html(response);
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
            lista_usuarios_asignados();
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

  function usuarios(){
    $('#ddl_usuario').select2({
      width:'100%',
      placeholder: 'Seleccione una usuario',
      ajax: {
        url: '../controlador/usuariosC.php?lista_usuarios_ddl2=true',
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
          return {
            results: data
          };
        },
        cache: true
      }
    });
  }

  function lista_modulos()
  {
    //  var parametros = 
    // {
    //   'perfil':$('#ddl_perfil').val(),
    //   'query':$('#txt_pagina').val(),
    // }

    $.ajax({
         // data:  {parametros:parametros},
         url:   '../controlador/tipo_usuarioC.php?modulo_sistema=true',
         type:  'post',
         dataType: 'json',
           success:  function (response) {  
            // console.log(response);
           
            $('#ddl_modulos').html(response);
            // accesos_asignados();
           
          } 
          
       });
  }

  function lista_paginas()
  {
    var parametros = 
    {
      'perfil':$('#ddl_perfil').val(),
      'modulo_sis':$('#ddl_modulos').val(),
      'modulo':$('#ddl_menu').val(),
      'query':$('#txt_pagina').val(),
    }

    $.ajax({
         data:  {parametros:parametros},
         url:   '../controlador/tipo_usuarioC.php?lista_paginas=true',
         type:  'post',
         dataType: 'json',
           success:  function (response) {  
            // console.log(response);
           
            $('#tbl_paginas').html(response);
            accesos_asignados();
           
          } 
          
       });
  }

  function validar_licencia()
  {
    var parametros = 
    {
      'modulo_sis':$('#ddl_modulos').val(),
    }
    $.ajax({
         data:  {parametros:parametros},
         url:   '../controlador/tipo_usuarioC.php?valida_licencia=true',
         type:  'post',
         dataType: 'json',
           success:  function (response) {  

            if(response==0)
            {
              Swal.fire({
                title: "Advertencia",
                text: "Usted no tiene una licencia para este modulo, aun que Asigne accesos no se podra ver el modulo ",
                icon: 'info',
                confirmButtonText: 'OK',
                allowOutsideClick: false, // Permite cerrar haciendo clic fuera del cuadro de diálogo
              })

            }
           
          } 
          
       });
  }

  function  buscar_usuario_perfil()
  {    
   var tipo = $('#ddl_perfil').val();

    $.ajax({
         data:  {tipo:tipo},
         url:   '../controlador/tipo_usuarioC.php?lista_usuarios_perfil_accesos=true',
         type:  'post',
         dataType: 'json',
           success:  function (response) {  
            console.log(response);
            var  op = "<option value = ''>Asignar a todos</option>";
            response.forEach(function(item,i){
              op+="<option value = '"+item.ID+"'>"+item.nombre+"</option>";
           });           
            $('#ddl_usuario_perfil').html(op);
            // accesos_asignados();
           
          } 
          
       });

  }

  function cargar_menu()
  {   
    parametros = 
    {
      'modulo_sis':$('#ddl_modulos').val(),
    }
    $.ajax({
         data:  {parametros:parametros},
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
            $('#ddl_menu').html(response);
           } 
          } 
          
       });
  }

  function accesos_asignados()
  {
    var perfil = $('#ddl_perfil').val();
    var usuario_perfil = $('#ddl_usuario_perfil').val();
    parametros = 
    {
      'perfil':perfil,
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
      'perfil':$('#ddl_perfil').val(),
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

  function guardar_en_perfil()
   {     
     var tipo = $('#ddl_tipo_usuario').val();
     var tipo_nom = $('#ddl_tipo_usuario option:selected').text();
      var usuario = $('#ddl_usuario').val();
     var parametros = 
     {
       'usuario':usuario,
       'tipo': tipo,
     }
      $.ajax({
         data:  {parametros:parametros},
         url:   '../controlador/tipo_usuarioC.php?guardar_en_perfil=true',
         type:  'post',
         dataType: 'json',
           success:  function (response) { 
            if(response==1)
            {
             Swal.fire('','Usuario Asignado a perfil','success');
             lista_usuarios_asignados();
            }else if(response==2)
            {
             Swal.fire('','Este usuario ya esta registrado en '+tipo_nom,'error');              
            }
          } 
          
       });
   }

   function marcar_todo_edit()
   {
     var perfil = $('#ddl_perfil').val();
     if(perfil=='')
     {
       Swal.fire('Seleccione un Usuario','','info');
       $('#rbl_todo_edit').prop('checked',false);
       return false;
     }
       
     $('.rbl_pag_edi').each(function() {
        const checkbox = $(this);
        const isChecked = checkbox.prop('checked'); 
        if (!isChecked) {
          $(this).click();
          // console.log(this.id);
        }
        // console.log(checkbox);
    });

     Swal.fire('Todos seleccionados','','info').then(function(){
       $('#rbl_todo_edit').prop('checked',false);
     })
  
   }

  function marcar_todo_ver()
   {
     var perfil = $('#ddl_perfil').val();
     if(perfil=='')
     {
       Swal.fire('Seleccione un Usuario','','info');
       $('#rbl_todo_ver').prop('checked',false);
       return false;
     }
       
     $('.rbl_pag_ver').each(function() {
        const checkbox = $(this);
        const isChecked = checkbox.prop('checked'); 
        if (!isChecked) {
          $(this).click();
          // console.log(this.id);
        }
        // console.log(checkbox);
    });

     Swal.fire('Todos seleccionados','','info').then(function(){
       $('#rbl_todo_ver').prop('checked',false);
     })
  
   }
   function marcar_todo_delet()
   {
     var perfil = $('#ddl_perfil').val();
     if(perfil=='')
     {
       Swal.fire('Seleccione un pefil de usuario','','info');
       $('#rbl_todo_eli').prop('checked',false);
       return false;
     }

      $('.rbl_pag_eli').each(function() {
        const checkbox = $(this);
        const isChecked = checkbox.prop('checked'); 
        if (!isChecked) {
          $(this).click();
          // console.log(this.id);
        }
        // console.log(checkbox);
    });
       Swal.fire('Todos seleccionados','','info').then(function(){
       $('#rbl_todo_eli').prop('checked',false);
     })

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
                <li class="breadcrumb-item active" aria-current="page">Perfiles y accesos</li>
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
                <div class="row"><br>                                     
                    <div class="col-sm-3">                    
                      <b>Perfil usuario</b>
                      <select class="form-select form-select-sm" id="ddl_perfil" name="ddl_perfil" onchange="accesos_asignados()">
                        <option value="">Seleccione perfil de usuario</option>
                      </select>                    
                    </div>
                    <div class="col-sm-5" style="display:none;">
                      <b>Usuarios</b>
                      <select class="form-select form-select-sm" id="ddl_usuario_perfil" name="ddl_usuario_perfil" onchange="lista_paginas()">
                        <option value="T">Aplicar a todos</option>
                      </select>                      
                    </div>
                    <div class="col-sm-2">
                      <b>Modulo </b>
                      <select class="form-select form-select-sm" id="ddl_modulos" name="ddl_modulos" onchange="cargar_menu();lista_paginas();validar_licencia()">
                        <option value="">Modulos</option>
                      </select>                    
                    </div>

                    <div class="col-sm-2">
                      <b>Menu</b>
                      <select class="form-select form-select-sm" id="ddl_menu" name="ddl_menu" onchange="lista_paginas()">
                        <option value="">Modulos</option>
                      </select>                    
                    </div>
                    <div class="col-sm-4">
                      <b>Buscar pagina</b>
                        <input type="text" name="txt_pagina" id="txt_pagina" placeholder="Buscar pagina" class="form-control form-control-sm" onkeyup="lista_paginas()">             
                    </div>  
                </div>
                <hr>
                <table class="table">
                  <thead>
                      <th colspan="5" class="text-end">Marcar todos<i class="bx bx-down-arrow-alt"></i></th>                            
                      <th class="text-center"><input type="checkbox" name="rbl_todo_ver" id="rbl_todo_ver" onclick="marcar_todo_ver()"></th>
                      <th class="text-center"><input type="checkbox" name="rbl_todo_edit" id="rbl_todo_edit" onclick="marcar_todo_edit()"></th>
                      <th class="text-center"><input type="checkbox" name="rbl_todo_eli" id="rbl_todo_eli" onclick="marcar_todo_delet()"></th>
                    </thead>
                    <thead>
                      <th>Pagina</th>
                      <th>Detalle</th>
                      <th>Estado</th>
                      <th>Menu</th>
                      <th>Default</th>
                      <th>Leer</th>
                      <th class="text-center">Editar</th>
                      <th class="text-center">Eliminar</th>
                    </thead>
                    <tbody id="tbl_paginas">
                      <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td width="15px" class="text-center"><input type="checkbox" name="" id="" checked disabled></td>
                        <td width="15px" class="text-center"><input type="checkbox" name="" id=""></td>
                        <td width="15px" class="text-center"><input type="checkbox" name="" id=""></td>
                      </tr>
                    </tbody>
                </table>
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
