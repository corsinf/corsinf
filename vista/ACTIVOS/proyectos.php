<?php //include('../cabeceras/header.php'); ?>
<script type="text/javascript">
  $( document ).ready(function() {
    consultar_datos();
});
     
  function consultar_datos(id='')
  { 
    var proyectos='';

    $.ajax({
      data:  {id:id},
      url:   '../controlador/proyectosC.php?lista=true',
      type:  'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
        success:  function (response) {    
        // console.log(response);   
        $.each(response, function(i, item){
          console.log(item);
        proyectos+='<tr><td>'+item.id+'</td><td><a href="inicio.php?acc=detalle_proyectos&id='+item.id+'">'+item.pro+'</a></td><td>'+item.enti+'</td><td>'+item.deno+'</td><td>'+item.desc+'</td><td>'+item.valde.date.substr(0,10)+'</td><td>'+item.vala.date.substr(0,10)+'</td><td>'+item.exp.date.substr(0,10)+'</td><td>';
        });      
        $('#tbl_datos').html(proyectos);            
      }
    });
  }

  function datos_col(id)
  { 
    $('#titulo').text('Editar Proyecto');
    $('#op').text('Editar');
    var proyectos='';

    $.ajax({
      data:  {id:id},
      url:   '../controlador/proyectosC.php?lista=true',
      type:  'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
        success:  function (response) {
           $('#codigo').val(response[0].id); 
           $('#txt_fin').val(response[0].pro);
           $('#txt_enti').val(response[0].enti);
           $('#txt_deno').val(response[0].deno);
           $('#txt_descri').val(response[0].desc);
           $('#txt_valde').val(response[0].valde.date.substr(0,10));
           $('#txt_vala').val(response[0].vala.date.substr(0,10));
           $('#txt_expi').val(response[0].exp.date.substr(0,10));
      }
    });
  }

  function delete_datos(id)
  {
    Swal.fire({
  title: 'Eliminar Registro?',
  text: "Esta seguro de eliminar este registro?",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Si'
}).then((result) => {
  if (result.value) {
    eliminar(id);    
  }
})

  }

  function buscar(buscar)
  {
     var proyectos='';

    $.ajax({
      data:  {buscar:buscar},
      url:   '../controlador/proyectosC.php?buscar=true',
      type:  'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
        success:  function (response) {    
        // console.log(response);   
        $.each(response, function(i, item){
          console.log(item);
         proyectos+='<tr><td>'+item.id+'</td><td><a href="inicio.php?acc=detalle_proyectos.php&id='+item.id+'">'+item.pro+'</a></td><td>'+item.enti+'</td><td>'+item.deno+'</td><td>'+item.desc+'</td><td>'+item.valde.date.substr(0,10)+'</td><td>'+item.vala.date.substr(0,10)+'</td><td>'+item.exp.date.substr(0,10)+'</td><td>';
        });      
        $('#tbl_datos').html(proyectos);                    
      }
    });
  }
  
  function insertar(parametros)
  {
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/proyectosC.php?insertar=true',
      type:  'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
        success:  function (response) {  
        if(response==1)
        {
            $('#myModal').modal('hide');
          Swal.fire(
            '',
            'Operaciopn realizada con exito.',
            'success'
          )
          consultar_datos();
           
        }
               
      }
    });

  }
  function limpiar()
  {
    $('#codigo').val(''); 
    $('#txt_fin').val('');
    $('#txt_enti').val('');
    $('#txt_deno').val('');
    $('#txt_descri').val('');
    $('#txt_valde').val('');
    $('#txt_vala').val('');
    $('#txt_expi').val(''); 
       $('#titulo').text('Nuevo color');
        $('#op').text('Guardar');
           

  }
  function eliminar(id)
  {
     $.ajax({
      data:  {id:id},
      url:   '../controlador/proyectosC.php?eliminar=true',
      type:  'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
        success:  function (response) {  
        if(response == 1)
        {
         Swal.fire(
      'Eliminado!',
      'Registro Eliminado.',
      'success'
    )
          consultar_datos();
        }  
               
      }
    });

  }
  function editar_insertar()
  {
    var id = $('#codigo').val(); 
    var fin= $('#txt_fin').val();
    var ent= $('#txt_enti').val();
    var den= $('#txt_deno').val();
    var des= $('#txt_descri').val();
    var val= $('#txt_valde').val();
    var vla= $('#txt_vala').val();
    var exp= $('#txt_expi').val();
    var parametros= 
    {
      'id':id,
      'fin':fin,
      'ent':ent,
      'den':den,
      'des':des,
      'val':val,
      'vla':vla,
      'exp':exp,
    }  
      if(id=='')
        {
          if(fin == '' || ent == '' || den == '' || des == '' || val == '' ||  vla== '' || exp == '')
            {
              Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Asegurese de llenar todo los campos',
               })
            }else
            {
             insertar(parametros)
          }
        }else
        {
           if(fin == '' || ent == '' || den == '' || des == '' || val == '' ||  vla== '' || exp == '')
            {
              Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Asegurese de llenar todo los campos',
               })
            }else
            {
              insertar(parametros);
            }
        }
  }
</script>
<div class="page-wrapper">
      <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
          <div class="breadcrumb-title pe-3">Mantenimientos</div>
          <div class="ps-3">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Proyectos</li>
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
                 <div class="row mb-3">
                  <div class="col-sm-12" id="btn_nuevo">
                    <a href="#" class="btn btn-success btn-sm" onclick="location.href = 'detalle_proyectos.php'"><i class=" bx bx-plus"></i> Nuevo</a>
                    <a href="#" class="btn btn-outline-secondary btn-sm" id="excel_proyectos" title="Informe en excel del total de proyectos"><i class="bx bx-file"></i> Total Proyectos</a>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                        <input type="" name="" id="txt_buscar" onkeyup="buscar($('#txt_buscar').val())" class="form-control form-control-sm" placeholder="Buscar por descripcion">    
                    </div>  
                </div>
                <div class="table-responsive">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>Codigo</th>
                        <th>Financiacion</th>
                        <th>Entidad</th>
                        <th>Denominacion</th>
                        <th>Descripcion</th>
                        <th>Validez de</th>
                        <th> Validez a</th>
                        <th>Expiracion</th>
                      </tr>
                    </thead>
                    <tbody id="tbl_datos">
                     
                    </tbody>
                  </table>
                </div>               
              </div>
            </div>
          </div>
        </div>
        <!--end row-->
      </div>
    </div>



<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="titulo">Nuevo Proyecto</h3>
      </div>
      <div class="modal-body">
        <input type="hidden" name="" id="codigo" class="form-control" placeholder="Nombre del proyecto">
        Financiacion <br>
        <input type="text" name="descripcion" id="txt_fin" class="form-control">
         Entidad <br>
        <input type="text" name="descripcion" id="txt_enti" class="form-control" placeholder="Entidad">
        Denominacion <br>
        <input type="text" name="descripcion" id="txt_deno" class="form-control" placeholder="Denominacion">
        Descripcion <br>
        <input type="text" name="descripcion" id="txt_descri" class="form-control" placeholder="Descripcion">
        validez de <br>
        <input type="date" name="descripcion" id="txt_valde" class="form-control" placeholder="Validez de">
        Validez a <br>
        <input type="date" name="descripcion" id="txt_vala" class="form-control" placeholder="Validez a">
         Expiracion <br>
        <input type="date" name="descripcion" id="txt_expi" class="form-control" placeholder="Expiracion">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="op" onclick="editar_insertar()">Guardar</button>
      </div>
    </div>
  </div>
</div>

     
<?php //include('../cabeceras/footer.php'); ?>