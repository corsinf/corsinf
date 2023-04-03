<?php include('./header.php'); ?>
<script type="text/javascript">
   $('body').addClass('sidebar-collapse');
  $( document ).ready(function() {
    lista_articulos();
    autocmpletar()
    autocmpletar_l()
  })


 function autocmpletar(){
      $('#ddl_custodio').select2({
        placeholder: 'Seleccione una custodio',
        ajax: {
          url: '../controlador/custodioC.php?lista=true',
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
          url: '../controlador/localizacionC.php?lista=true',
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
      'query':$('#txt_buscar').val(),
      'localizacion':  $('#ddl_localizacion').val(),
      'custodio': $('#ddl_custodio').val(),
      'pag':$('#txt_pag').val(),
      'exacto':$('#rbl_exacto').prop('checked'),
      'asset':$('#rbl_aset').prop('checked'),
      'asset_org':$('#rbl_aset_ori').prop('checked'),
     }
     var lineas = '';
    $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/articulosC.php?lista_patrimoniales=true',
      type:  'post',
      dataType: 'json',
      beforeSend: function () {   
           // var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#pag').html('');
      },
        success:  function (response) { 
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
          lineas+= '<tr class="'+baja+'"  onclick="redireccionar(\''+item.id+'\')"><td>'+item.id+'</td><td style="color: #1467e2;"><u>'+item.tag+'</u></td><td>'+item.nom+'</td><td>'+item.modelo+'</td><td>'+item.serie+'</td><td>'+item.localizacion+'</td><td>'+item.custodio+'</td><td>'+item.marca+'</td><td>'+item.estado+'</td><td>'+item.genero+'</td><td>'+item.color+'</td><td>'+item.fecha_in+'</td><td>'+item.OBSERVACION+'</td></tr>';
          console.log(item);
       
        });       
        $('#tbl_datos').html(lineas);        
      },
      error: function (error) {
    alert(JSON.stringify(error));
}
    });
  }
 function redireccionar(id)
 {
    var loc= 'null';var cus = 'null';
    if($('#ddl_localizacion').val() != null)
    {
      loc = $('#ddl_localizacion').select2('data')[0].text;
    }
    if($('#ddl_custodio').val() != null)
    {
      cus = $('#ddl_custodio').select2('data')[0].text;
    }
     window.location.href="patrimoniales.php?id="+id+'&fil1='+$('#ddl_localizacion').val()+'--'+loc+'&fil2='+$('#ddl_custodio').val()+'--'+cus;
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
       lista_articulos();

    }else{
    var fin =  m[1]*(pos+1);
    var ini = fin-m[1];
    $('#txt_pag').val(ini+'-'+fin);
    lista_articulos();
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
       lista_articulos();  
    }
  }
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
                <li class="breadcrumb-item active" aria-current="page">Patrimoniales</li>
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
                  <div class="col-sm-3">
                    <button class="btn btn-success btn-sm" onclick="location.href = 'patrimoniales.php?fil1=&fil2='"> <i class="bx bx-plus"></i> Agregar Nuevo</button>
                  </div>
        </div>
        <hr>
        
            <div class="row">
              <input type="hidden" id="txt_pag" name="" value="0-25">
              <input type="hidden" id="txt_pag1" name="" value="0-25">
              <input type="hidden" id="txt_numpag" name="">
              <div class="col-sm-4">
                  <label class="checkbox-inline" style="margin: 0px;"><input type="checkbox" name="" id="rbl_exacto" onclick="activar()" checked=""> Busqueda exacta</label>   
                   <input type="" name="" id="txt_buscar" onkeyup="lista_articulos()" class="form-control form-control-sm" placeholder="Buscar Descripcion o tag">   
                     <label class="checkbox-inline" style="margin: 0px;"><input type="radio" name="rbl_aset" id="rbl_aset_ori" checked="" onclick="lista_articulos()"> Orig Asset</label>
                   <label class="checkbox-inline" style="margin: 0px;"><input type="radio" name="rbl_aset" id="rbl_aset"  onclick="lista_articulos()"> Asset</label>                           
                
              </div>
             <div class="col-sm-4">
             <b> Busqueda por custodio</b>
               <div class="input-group input-group-sm">
                <select class="form-control form-control-sm" id="ddl_custodio" onchange="$('#txt_pag').val('0-25');lista_articulos()" style="width:80%"></select>
                    <button type="button" class="btn btn-secondary btn-flat" onclick="limpiar('ddl_custodio')" title="Limpiar custodio"><i class="bx bx-x"></i></button>
                 
                </div>
             </div>

             <div class="col-sm-4">
               <b> Busqueda por Localizacion</b>
               <div class="input-group input-group-sm">
                <select class="form-control form-control-sm" id="ddl_localizacion" onchange="$('#txt_pag').val('0-25');lista_articulos()" style="width:80%"></select>
                    <button type="button" class="btn btn-secondary btn-flat" onclick="limpiar('ddl_localizacion')" title="Limpiar localizacion"><i class="bx bx-x"></i></button>
                </div>
             </div>    
            </div>

            <hr>


            <div class="row">
              <div class="col-sm-6">
                
              </div>              
              <div class="col-sm-6">
                <div class="row justify-content-end">
                  <nav aria-label="Page navigation example">
                    <ul class="pagination" id="pag" style="margin:0px;"></ul>
                  </nav>           
                </div>
              </div>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>TAG SERIE</th>
                  <th>DESCRIPCION</th>
                  <th>MODELO</th>
                  <th>SERIE</th>
                  <th>LOCALIZACION</th>
                  <th>CUSTODIO</th>
                  <th>MARCA</th>
                  <th>ESTADO</th>
                  <th>GENERO</th>
                  <th>COLOR</th>
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
        <!--end row-->
      </div>
    </div>




<!--  -->

<?php include('./footer.php'); ?>
