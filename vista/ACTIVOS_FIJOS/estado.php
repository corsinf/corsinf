<?php include('../../cabeceras/header2.php'); ?>
<script type="text/javascript">
  $(document).ready(function() {
    consultar_datos();
  });

  function consultar_datos(id = '') {
    var estado = '';

    $.ajax({
      data: {
        id: id
      },
      url: '../../controlador/ACTIVOS_FIJOS/estadoC.php?lista=true',
      type: 'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
      success: function(response) {
        // console.log(response);   
        $.each(response, function(i, item) {
          console.log(item);
          estado += '<tr><td>' + item.CODIGO + '</td><td><a href="estado_detalle.php?id=' + item.ID_ESTADO + '"><u>' + item.DESCRIPCION + '</u></a></td><td>';
          //   if($('#eliminar').val()==1 || $('#dba').val()==1)
          //   {
          //   estado+='<button class="btn btn-danger" tittle="Eliminar" onclick="delete_datos(\''+item.ID_ESTADO+'\')"><i class="fa fa-trash"></i></button>';
          // }
          // if($('#editar').val()==1 || $('#dba').val()==1)
          //   {
          //   estado+='<button class="btn btn-primary" tittle="Editar" onclick="datos_col(\''+item.ID_ESTADO+'\')" data-toggle="modal" data-target="#myModal"><i class="fa fa-paint-brush"></i></button>';
          // }
          estado += '</td></tr>';
        });
        $('#tbl_datos').html(estado);
      }
    });
  }

  //   function datos_col(id)
  //   { 
  //     $('#titulo').text('Editar Estado');
  //     $('#op').text('Editar');
  //     var estado='';

  //     $.ajax({
  //       data:  {id:id},
  //       url:   '../../controlador/ACTIVOS_FIJOS/estadoC.php?lista=true',
  //       type:  'post',
  //       dataType: 'json',
  //       /*beforeSend: function () {   
  //            var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
  //          $('#tabla_').html(spiner);
  //       },*/
  //         success:  function (response) {
  //            $('#codigo').val(response[0].CODIGO); 
  //            $('#descripcion').val(response[0].DESCRIPCION);
  //            $('#id').val(response[0].ID_ESTADO); 
  //       }
  //     });
  //   }

  //   function delete_datos(id)
  //   {
  //     Swal.fire({
  //   title: 'Eliminar Registro?',
  //   text: "Esta seguro de eliminar este registro?",
  //   icon: 'warning',
  //   showCancelButton: true,
  //   confirmButtonColor: '#3085d6',
  //   cancelButtonColor: '#d33',
  //   confirmButtonText: 'Si'
  // }).then((result) => {
  //   if (result.value) {
  //     eliminar(id);    
  //   }
  // })

  //   }

  function buscar(buscar) {
    var estado = '';

    $.ajax({
      data: {
        buscar: buscar
      },
      url: '../../controlador/ACTIVOS_FIJOS/estadoC.php?buscar=true',
      type: 'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
      success: function(response) {
        // console.log(response);   
        $.each(response, function(i, item) {
          console.log(item);
          estado += '<tr><td>' + item.CODIGO + '</td><td><a href="estado_detalle.php?id=' + item.ID_ESTADO + '"><u>' + item.DESCRIPCION + '</u></a></td><td>';
          //   if($('#eliminar').val()==1 || $('#dba').val()==1)
          //   {
          //   estado+='<button class="btn btn-danger" tittle="Eliminar" onclick="delete_datos(\''+item.ID_ESTADO+'\')"><i class="fa fa-trash"></i></button>';
          // }
          // if($('#editar').val()==1 || $('#dba').val()==1)
          //   {
          //   estado+='<button class="btn btn-primary" tittle="Editar" onclick="datos_col(\''+item.ID_ESTADO+'\')" data-toggle="modal" data-target="#myModal"><i class="fa fa-paint-brush"></i></button>';
          // }
          estado += '</td></tr>';
        });
        $('#tbl_datos').html(estado);
      }
    });
  }

  // function insertar(parametros)
  // {
  //    $.ajax({
  //     data:  {parametros:parametros},
  //     url:   '../../controlador/ACTIVOS_FIJOS/estadoC.php?insertar=true',
  //     type:  'post',
  //     dataType: 'json',
  //     beforeSend: function () {   
  //          var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
  //        $('#tabla_').html(spiner);
  //     },
  //       success:  function (response) {  
  //       if(response == 1)
  //       {
  //         $('#myModal').modal('hide');
  //         Swal.fire(
  //           '',
  //           'Operaciopn realizada con exito.',
  //           'success'
  //         )
  //         consultar_datos();
  //       }  

  //     }
  //   });

  // }
  function limpiar() {
    $('#codigo').val('');
    $('#descripcion').val('');
    $('#id').val('');
    $('#titulo').text('Nuevo Estado');
    $('#op').text('Guardar');


  }
  // function eliminar(id)
  // {
  //    $.ajax({
  //     data:  {id:id},
  //     url:   '../../controlador/ACTIVOS_FIJOS/estadoC.php?eliminar=true',
  //     type:  'post',
  //     dataType: 'json',
  //     beforeSend: function () {   
  //          var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
  //        $('#tabla_').html(spiner);
  //     },
  //       success:  function (response) {  
  //       if(response == 1)
  //       {
  //        Swal.fire(
  //     'Eliminado!',
  //     'Registro Eliminado.',
  //     'success'
  //   )
  //         consultar_datos();
  //       }  

  //     }
  //   });

  // }
  // function editar_insertar()
  // {
  //    var codigo = $('#codigo').val();
  //    var descri = $('#descripcion').val();
  //    var id = $('#id').val();

  //     var parametros = {
  //       'cod':codigo,
  //       'des':descri,
  //       'id':id,
  //     }
  //     if(id=='')
  //       {
  //         if(codigo == '' || descri == '')
  //           {
  //             Swal.fire({
  //               icon: 'error',
  //               title: 'Oops...',
  //               text: 'Asegurese de llenar todo los campos',
  //              })
  //           }else
  //           {
  //            insertar(parametros)
  //         }
  //       }else
  //       {
  //          if(codigo == '' || descri == '')
  //           {
  //             Swal.fire({
  //               icon: 'error',
  //               title: 'Oops...',
  //               text: 'Asegurese de llenar todo los campos',
  //              })
  //           }else
  //           {
  //             insertar(parametros);
  //           }
  //       }
  // }
</script>

<div class="content">
  <!-- Content Header (Page header) -->
  <br>
  <section class="content">
    <div class="container-fluid">

      <div class="row">
        <div class="col-sm-12" id="btn_nuevo">
          <a href="estado_detalle.php?" class="btn btn-success btn-sm"><i class="bx bx-plus"></i> Nuevo</a>
          <a href="#" class="btn btn-default btn-sm" id="excel_estados" title="Informe en excel del total de estados"><i class="bx bx-file"></i> Total Estados</a>
        </div>
      </div>
      <br>
      <div class="row">
        <div class="col-sm-6">
          <input type="" name="" id="txt_buscar" onkeyup="buscar($('#txt_buscar').val())" class="form-control form-control-sm" placeholder="Buscar estado">
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Codigo</th>
              <th>Descripcion</th>
              <th></th>
            </tr>
          </thead>
          <tbody id="tbl_datos">

          </tbody>
        </table>
      </div>
    </div>

    <!-- Button trigger modal -->


    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title" id="titulo">Nuevo Estado</h3>
          </div>
          <div class="modal-body">
            <input type="hidden" name="id" id="id" class="form-control">
            Codigo <br>
            <input type="input" name="codigo" id="codigo" class="form-control">
            Descripcion <br>
            <input type="input" name="descripcion" id="descripcion" class="form-control">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="op" onclick="editar_insertar()">Guardar</button>
          </div>
        </div>
      </div>

    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
</div>