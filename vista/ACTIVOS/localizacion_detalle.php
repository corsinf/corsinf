<?php /*include('../cabeceras/header.php'); */ $id = ''; if(isset($_GET['id'])){ $id = $_GET['id'];} ?>
<script type="text/javascript">
  $( document ).ready(function() {
    var id = '<?php  echo $id; ?>';
    if(id!='')
    {
       datos_col(id);
    }
});
     
  function consultar_datos(id='')
  { 
    var localizacion='';
    var parametros = 
    {
      'id':id,
      'pag':$('#txt_pag').val(),
      'query':$('#txt_buscar').val(),
    }

    $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/localizacionC.php?buscar=true',
      type:  'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
        success:  function (response) {    
        // console.log(response);
         var pag = $('#txt_pag1').val().split('-');        
        var pag2 = $('#txt_pag').val().split('-');

        var pagi = '<li class="page-item" onclick="guias_pag(\'-\')"><a class="page-link" href="#"> << </a></li>';
        if($('#txt_numpag').val() =='')
        {
          $('#txt_numpag').val(response.cant / pag[1]);
        }
        if(response.cant > pag[1])
        {
           var num = response.cant / pag[1];
           if(num >10)
           {
            if(pag2[1]/pag[1] <= 10)
            {
            for (var i = 1; i < 11 ; i++) {
              var pos =pag[1]*i;
              var ini =pos-pag[1];  
              var pa = ini+'-'+pos;
              if($('#txt_pag').val()==pa){
               pagi+='<li class="page-item active" onclick="paginacion(\''+pa+'\')"><a class="page-link" href="#">'+i+'</a></li>';
              }else
              { 
                pagi+='<li class="page-item" onclick="paginacion(\''+pa+'\')"><a class="page-link" href="#">'+i+'</a></li>';
              }
            }
           }else
           {

               pagi+='<li class="page-item" onclick="paginacion(\'0-25\')"><a class="page-link" href="#">1</a></li>';
            for (var i = pag2[1]/25; i < (pag2[1]/25)+10 ; i++) {
              var pos =pag[1]*i;
              var ini =pos-pag[1];  
              var pa = ini+'-'+pos;
              if($('#txt_pag').val()==pa){
               pagi+='<li class="page-item active" onclick="paginacion(\''+pa+'\')"><a class="page-link" href="#">'+i+'</a></li>';
              }else
              { 
                pagi+='<li class="page-item" onclick="paginacion(\''+pa+'\')"><a class="page-link" href="#">'+i+'</a></li>';
              }
            }
           }
            pagi+='<li class="page-item" onclick="guias_pag(\'+\')"><a class="page-link" href="#"> >> </a></li>'
           }else
           { 
             
            for (var i = 1; i < num+1 ; i++) {
              var pos =pag[1]*i;
              var ini =pag[1]-pos;  
              var pa = ini+'-'+pos;
              if($('#txt_pag').val() == pa)
              {
               pagi+='<li class="page-item active"  onclick="paginacion(\''+pa+'\')"><a class="page-link" href="#">'+i+'</a></li>';
              }else
              {  
                pagi+='<li class="page-item"  onclick="paginacion(\''+pa+'\')"><a class="page-link" href="#">'+i+'</a></li>';
              }
            }
           }

        $('#pag').html(pagi);  

        }   


        $.each(response.datos, function(i, item){
          // console.log(item);
        localizacion+='<tr><td>'+item.ID_LOCATION+'</td><td>'+item.CENTRO+'</td><td>'+item.EMPLAZAMIENTO+'</td><td>'+item.DENOMINACION+'</td><td>';
         if($('#elimina').val()==1 || $('#dba').val()==1)
        {
        localizacion+='<button class="btn btn-danger" tittle="Eliminar" onclick="delete_datos(\''+item.ID_LOCATION+'\')"><i class="fa fa-trash"></i></button>';
      }if($('#editar').val()==1 || $('#dba').val()==1)
        {
          localizacion+='<button class="btn btn-primary" tittle="Editar" onclick="datos_col(\''+item.ID_LOCATION+'\')" data-toggle="modal" data-target="#myModal"><i class="fa fa-paint-brush"></i></button>';
        }
        localizacion+='</td></tr>';
        });       
        $('#tbl_datos').html(localizacion);        
      }
    });
  }

  function datos_col(id)
  { 
    $('#titulo').text('Editar localizacion');
    $('#op').text('Editar');
    var localizacion='';

    $.ajax({
      data:  {id:id},
      url:   '../controlador/localizacionC.php?listar=true',
      type:  'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
        success:  function (response) {
          console.log(response);
           $('#txt_centro').val(response[0].CENTRO); 
           $('#txt_empla').val(response[0].EMPLAZAMIENTO);
           $('#txt_deno').val(response[0].DENOMINACION);           
           $('#id').val(response[0].ID_LOCATION); 
      }
    });
  }

  function delete_datos()
  {
    var id = '<?php echo $id; ?>';
    if(id!='')
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
    }else
    {
      Swal.fire('','Seleccione un dato a eliminar','info');
    }

  }

  function buscar()
  {
     var localizacion='';
     var busca = $('#txt_buscar').val();
     if(busca != '')
     {
    $.ajax({
      data:  {busca:busca},
      url:   '../controlador/localizacionC.php?buscar=true',
      type:  'post',
      dataType: 'json',
      beforeSend: function () {   
           // var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#pag').html('');
      },
        success:  function (response) {    
        // console.log(response);   
         $.each(response.datos, function(i, item){
           localizacion+='<tr><td>'+item.ID_LOCATION+'</td><td>'+item.CENTRO+'</td><td>'+item.EMPLAZAMIENTO+'</td><td>'+item.DENOMINACION+'</td><td>';
         if($('#elimina').val()==1 || $('#dba').val()==1)
        {
        localizacion+='<button class="btn btn-danger" tittle="Eliminar" onclick="delete_datos(\''+item.ID_LOCATION+'\')"><i class="fa fa-trash"></i></button>';
      }if($('#editar').val()==1 || $('#dba').val()==1)
        {
          localizacion+='<button class="btn btn-primary" tittle="Editar" onclick="datos_col(\''+item.ID_LOCATION+'\')" data-toggle="modal" data-target="#myModal"><i class="fa fa-paint-brush"></i></button>';
        }
        localizacion+='</td></tr>';
        });       
        $('#tbl_datos').html(localizacion);  
      }
    });
  }else
  {
     consultar_datos();
  }
  }
  
  function insertar(parametros)
  {
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/localizacionC.php?insertar=true',
      type:  'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
        success:  function (response) {  
        if(response == 1)
        {
          $('#myModal').modal('hide');
          Swal.fire(
            '',
            'Operacion realizada con exito.',
            'success'
          )
          consultar_datos();
        }  
               
      }
    });

  }
  function limpiar()
  {
       $('#txt_centro').val('');
       $('#txt_empla').val('');
       $('#txt_deno').val('');
       $('#id').val('');
       $('#titulo').text('Nuevo localizacion');
        $('#op').text('Guardar');
           

  }
  function eliminar(id)
  {
     $.ajax({
      data:  {id:id},
      url:   '../controlador/localizacionC.php?eliminar=true',
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
    ).then(function(){location.href = 'localizacion.php'});

        }  
               
      }
    });

  }
  function editar_insertar()
  {
     var cen = $('#txt_centro').val();
     var emp = $('#txt_empla').val();
     var den = $('#txt_deno').val();
     var id = $('#id').val();
    
      var parametros = {
        'centro':cen,
        'empla':emp,
        'deno':den,
        'id':id,
      }
      if(id=='')
        {
          if(cen == '' || emp == '' || den == '')
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
           if(cen == '' || emp == '' || den == '')
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

  function paginacion(num)
{
  $('#txt_pag').val(num);
  var pag = $('#txt_pag').val().split('-');
  var pos = pag[1]/25;
  consultar_datos();
  // alert(pos);
}
function guias_pag(tipo)
{

  var m1 =  $('#txt_pag').val().split('-');
  var m =  $('#txt_pag1').val().split('-');
  var pos = m1[1]/25;
  if (tipo=='+')
  {
    if(pos >= 10)
    {
       var fin =  m[1]*(pos+1);
       var ini = fin-m[1];
       $('#txt_pag').val(ini+'-'+fin);
       consultar_datos();

    }else{
    var fin =  m[1]*(pos+1);
    var ini = fin-m[1];
    $('#txt_pag').val(ini+'-'+fin);
    consultar_datos();
   }

  }else
  {
    if(pos == 1)
    {
      alert('esta en el inicio');
    }else
    {
       var fin =  m[1]*(pos-1);
       var ini = fin-m[1];
       $('#txt_pag').val(ini+'-'+fin); 
       consultar_datos();  
    }
  }
}
</script>

<div class="page-wrapper">
      <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
          <div class="breadcrumb-title pe-3">Forms</div>
          <div class="ps-3">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Wizard</li>
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
                  <input type="hidden" id="txt_pag" name="" value="0-25">
                  <input type="hidden" id="txt_pag1" name="" value="0-25">
                  <input type="hidden" id="txt_numpag" name="">
                  <div class="row">
                    <div class="col-sm-12" id="btn_nuevo">
                      <a href="inicio.php?mod=<?php echo $_SESSION['INICIO']['MODULO_SISTEMA']; ?>&acc=localizacion" class="btn btn-outline-secondary btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                        <a href="#" class="btn btn-success btn-sm" onclick="location.href = 'localizacion_detalle.php'"><i class="bx bx-plus"></i>Nuevo</a>              
                    </div>
                  </div>
                  <div class="row">           
                     <input type="hidden" name="id" id="id" class="form-control form-control-sm">
                     <div class="col-sm-6">
                       Centro <br>
                       <input type="input" name="txt_centro" id="txt_centro" class="form-control form-control-sm">              
                    </div>
                     <div class="col-sm-6">
                       Emplazamiento <br>
                    <input type="input" name="txt_empla" id="txt_empla" class="form-control form-control-sm">   
                    </div>
                     <div class="col-sm-6">
                         Denominacion<br>
                      <input type="input" name="txt_deno" id="txt_deno" class="form-control form-control-sm">
                    </div>
                  </div>  
                  <div class="modal-footer">
                      <button type="button" class="btn btn-primary btn-sm" id="btn_editar" onclick="editar_insertar()">Guardar</button>
                      <button type="button" class="btn btn-danger btn-sm" id="btn_eliminar" onclick="delete_datos()">Eliminar</button>
                  </div>                 
              </div>
            </div>
          </div>
        </div>
        <!--end row-->
      </div>
    </div>

<?php //include('../cabeceras/footer.php'); ?>
     