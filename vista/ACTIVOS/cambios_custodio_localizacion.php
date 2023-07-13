<?php  include('../../cabeceras/header.php'); ?>
<script type="text/javascript">
  // $('body').addClass('sidebar-collapse');
  $( document ).ready(function() {
  	autocmpletar();
  	autocmpletar_l();
    autocmpletar_cus();
    autocmpletar_loc();
});

 function autocmpletar(){
      $('#ddl_custodio').select2({
        placeholder: 'Seleccione una custodio',
        ajax: {
          url: '../../controlador/custodioC.php?lista=true',
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

   function autocmpletar_cus(){
      $('#ddl_custodio2').select2({
        placeholder: 'Seleccione una custodio',
        ajax: {
          url: '../../controlador/custodioC.php?lista=true',
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

  function autocmpletar_l(){
      $('#ddl_localizacion').select2({
        placeholder: 'Seleccione una localizacion',
        ajax: {
          url: '../../controlador/localizacionC.php?lista=true',
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

function autocmpletar_loc(){
      $('#ddl_localizacion2').select2({
        placeholder: 'Seleccione una localizacion',
        ajax: {
          url: '../../controlador/localizacionC.php?lista=true',
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


  function lista_articulos()
  {
     var query = $('#txt_buscar').val();
     var parametros = 
     {
      'query': '', //$('#txt_buscar').val(),
      'localizacion':  $('#ddl_localizacion').val(),
      'custodio': $('#ddl_custodio').val(),
      'pag':$('#txt_pag').val(),
      // 'exacto':$('#rbl_exacto').prop('checked'),
      // 'asset':$('#rbl_aset').prop('checked'),
      // 'asset_org':$('#rbl_aset_ori').prop('checked'),
     }
     var lineas = '';
    $.ajax({
      data:  {parametros:parametros},
      url:   '../../controlador/cambios_custodio_localizacionC.php?lista=true',
      type:  'post',
      dataType: 'json',
      beforeSend: function () {   
           // var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#pag').html('');
      },
        success:  function (response) { 

          op = $('input[name=rbl_opcion]:checked').val();
          if(op=='C')
          {
            $('#total_art').text(response.cant);
            $('#alerta_custodio').css('display','block');
          }else{
            $('#total_art_loc').text(response.cant);
            $('#alerta_localizacion').css('display','block');
          }
        console.log(response);
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
          baja = '';
          if(item.BAJAS==1){baja = 'bg-danger'}
          if(item.PATRIMONIALES==1){baja = 'bg-warning';}
          if(item.TERCEROS==1){baja ='bg-blue'}
          lineas+= '<tr class="'+baja+'"  onclick="redireccionar(\''+item.id+'\')"><td>'+item.id+'</td><td style="color: #1467e2;"><u>'+item.tag+'</u></td><td>'+item.nom+'</td><td>'+item.localizacion+'</td><td>'+item.custodio+'</td><td>'+item.estado+'</td><td>'+item.fecha_in+'</td><td>'+item.OBSERVACION+'</td></tr>';
          console.log(item);
       
        });       
        $('#tbl_datos').html(lineas);        
      },
      error: function (error) {
    alert(JSON.stringify(error));
}
    });
  }
 

 function mostrar(item)
 {
  // console.log($('#rbl_'+item).prop('checked'));
  if($('#rbl_'+item).prop('checked'))
  {
   $('#div_articulos').css('display','block');
    console.log('tt');
  }else
  {
    console.log('dd');
   $('#div_articulos').css('display','none');

  }
 }

 function alerta_cambiar()
 {

    cus  = $('#ddl_custodio').val();
    cus2 = $('#ddl_custodio2').val();
    loc  = $('#ddl_localizacion').val();
    loc2 = $('#ddl_localizacion2').val();
  op = $('input[name=rbl_opcion]:checked').val();
  if(op=='C')
  {
    if(cus!='' && cus2!='')
    {
      if(cus!=cus2)
      {

      }else
      {
        Swal.fire('Custodio anterior no puede ser el custodio nuevo','','info')
        return false;
      }

    }else
    {
      Swal.fire('Seleccione custodio Anterior y nuevo','','info')
      return false;
    }

  }else
  {
    if(loc!='' && loc2!='')
    {
      if(loc!=loc2)
      {

      }else
      {
        Swal.fire('Localizacion anterior no puede ser la localizacion nuevo','','info')
        return false;
      }

    }else
    {
      Swal.fire('Seleccione localizacion Anterior y nuevo','','info')
      return false;
    }

  }


   Swal.fire({
      title: 'Esta seguro que quiere trasnferir estos articulos?',
      text: "Una vez que confrime no se podra revertir el cambio!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
        if (result.value) {
          cambiar();
        }
      })
 }

 function opciones()
 {
  op = $('input[name=rbl_opcion]:checked').val();
  if(op=='C')
  {
    $('#custodio').css('display','-webkit-box');
    $('#localizacion').css('display','none');
    $("#ddl_localizacion2").empty();
    $("#ddl_localizacion").empty();
    $('#alerta_localizacion').css('display','none')
    $('#rbl_cus').prop('checked',false);
    $('#div_articulos').css('display','none')

  }else
  {
    $('#custodio').css('display','none');
    $('#localizacion').css('display','-webkit-box');
    $('#ddl_custodio').empty();
    $('#ddl_custodio2').empty();
    $('#alerta_custodio').css('display','none');
    $('#rbl_loc').prop('checked',false)
    $('#div_articulos').css('display','none')

  }

 }

 function cambiar()
 {

  op = $('input[name=rbl_opcion]:checked').val();
  if(op=='C')
  {
    var antes =  $('#ddl_custodio').val();
    var despues = $('#ddl_custodio2').val();
  }else
  {
    var antes = $('#ddl_localizacion').val();
    var despues = $('#ddl_localizacion2').val();
  }
  var parametros = 
  {
    'antes':antes,
    'despues':despues,
    'opcion':op,
  }
  $.ajax({
         data:  {parametros,parametros},
         url:   '../../controlador/cambios_custodio_localizacionC.php?cambiar=true',
         type:  'post',
         dataType: 'json',
           success:  function (response) { 
            // console.log(response);
            if(response==1)
            {
              Swal.fire('Se transfirieron los articulos','','success').then(function(){location.reload();});
            }
          } 
          
       });
   

 }

</script>

<div class="page-wrapper">
      <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
          <div class="breadcrumb-title pe-3">Articulos</div>
          <div class="ps-3">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Cambiar custodio o localizacion masivamente</li>
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
                <div class="row">
                  <div class="col-sm-6">
                    <label> cambiar por custodio <input type="radio" id="rbl_custodio" name="rbl_opcion" checked value="C" onclick="opciones()"></label>
                  </div>
                  <div class="col-sm-6">          
                    <label> cambiar por Localizacion <input type="radio" id="rbl_localizacion" name="rbl_opcion" value="L" onclick="opciones()"></label>
                  </div>
                </div>
                <div class="row" id="custodio">
                  <hr>
                    <div class="col-sm-4">
                       <b> Antiguo custodio</b>
                        <div class="input-group input-group-sm">
                          <select class="form-control form-control-sm" id="ddl_custodio" onchange="$('#txt_pag').val('0-25');lista_articulos()" style="width:100%"><option value="">Seleccione un Custodio</option>
                          </select>
                        </div>
                        <p><input type="checkbox" name="rbl_cus" id="rbl_cus" onclick="mostrar('cus')"> Mostrar Articulos de custodio</p>
                        <div class="alert alert-warning alert-dismissible" style="display:none" id="alerta_custodio">
                            <h5><i class="icon fas fa-exclamation-triangle"></i> Total articulos</h5>
                            Este custodio tiene <b id="total_art">0</b> a su cargo.
                        </div>               
                    </div>
                    <div class="col-sm-4">
                      <b> Nuevo custodio</b>
                      <div class="input-group input-group-sm">
                        <select class="form-control form-control-sm" id="ddl_custodio2" onchange="$('#txt_pag').val('0-25');lista_articulos()" style="width:100%"><option value="">Seleccione un Custodio</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <br>
                      <button class="btn btn-primary btn-sm" onclick="alerta_cambiar()"><i class="bx bx-git-compare"></i> Cambiar</button>
                    </div>
                </div>
      <div class="row" id="localizacion" style="display: none;">        
      <hr>
         <div class="col-sm-4">
             <b> localizacion  anterior</b>
               <div class="input-group input-group-sm">
                <select class="form-control form-control-sm" id="ddl_localizacion" onchange="$('#txt_pag').val('0-25');lista_articulos()" style="width:100%"><option value="">Seleccione Una localizacion</option></select>
                </div>                
                <p><input type="checkbox"  name="rbl_loc" id="rbl_loc" onclick="mostrar('loc')"> Mostrar Articulos de localizacion</p>
                <div class="alert alert-warning alert-dismissible" style="display:none" id="alerta_localizacion">
                  <h5><i class="icon fas fa-exclamation-triangle"></i> Total articulos</h5>
                  Esta localizacion tiene <b id="total_art_loc">0</b> articulos.
                </div>
             </div>

             <div class="col-sm-4">
               <b> Localizacion nueva</b>
               <div class="input-group input-group-sm">
                 <select class="form-control form-control-sm" id="ddl_localizacion2" onchange="$('#txt_pag').val('0-25');lista_articulos()" style="width:100%"><option value="">Seleccione Una localizacion</option></select>               
                  
                </div>
             </div>
             <div class="col-sm-4">
              <br>
              <button class="btn btn-primary btn-sm" onclick="alerta_cambiar()"><i class="bx bx-git-compare"></i> Cambiar</button>
              </div>

      </div>      
      <hr>
      <div class="row" id="div_articulos" style=" display:none">
         <div class="col-sm-6">
                <div class="row justify-content-end">
                  <nav aria-label="Page navigation example">
                    <ul class="pagination" id="pag" style="margin:0px;"></ul>
                  </nav>           
                </div>
              </div>
         <div class="table-responsive">
          <input type="hidden" id="txt_pag" name="" value="0-25">
              <input type="hidden" id="txt_pag1" name="" value="0-25">
              <input type="hidden" id="txt_numpag" name="">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>TAG SERIE</th>
                  <th>DESCRIPCION</th>
                  <th>LOCALIZACION</th>
                  <th>CUSTODIO</th>
                  <th>ESTADO</th>
                  <th>FECHA INV.</th>
                  <th>OBSERVACION</th>
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
        </div>
        <!--end row-->
      </div>
    </div>

<?php include('../../cabeceras/footer.php');?>

     