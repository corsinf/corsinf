<?php include('./header.php'); ?>

<script type="text/javascript">
 $( document ).ready(function() {

  lista_articulos();
  autocmpletar();
  autocmpletar_l();
  lista_actas();
  autocmpletar_acta2();
  autocmpletar_lo_acta2();

  autocmpletar_acta4()
  autocmpletar_acta_4()
  autocmpletar_lo_acta4()
 autocmpletar_lo_acta_4()


    autocmpletar_clase_mov();
 	
 	 $('#acta1').click(function(){
     $('#modal_actas1').modal('show');	   
	 });

   $('#acta2').click(function(){
      $('#title_modal').text('Acta de custodio temporal');
      $('#txt_acta_num').val('2');
      $('#btn_acta2').css('display','block');
      $('#btn_acta3').css('display','none');
      $('#modal_actas2').modal('show');    
   });

   $('#acta3').click(function(){
      $('#title_modal').text('Acta de custodio definitivo');
      $('#txt_acta_num').val('3');
      $('#btn_acta2').css('display','none');
      $('#btn_acta3').css('display','block');
      $('#modal_actas2').modal('show');    
   });

   $('#acta4').click(function(){
      $('#modal_actas4').modal('show');    
   });

  $('#acta5').click(function(){
      $('#modal_actas5').modal('show');    
   });

 })

  function autocmpletar_clase_mov(){
    $('#ddl_clase_mov').select2({
      placeholder: 'Seleccione una familia',      
      dropdownParent: $('#modal_tipo_baja'),
      ajax: {
        url: '../controlador/clase_movimientoC.php?buscar_auto=true',
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


 function acta1()
 {
   var person = $('#txt_person').val();
   var sub = $('#txt_subtitulo1').val();
   var url = '../lib/phpword/generar_word.php?word_acta1=true&to='+person+'&sub='+sub;                 
   window.open(url, '_blank');
 }

 function acta2()
 {
    var cus_no = $('#ddl_custodio_modal2').val();
    var cus = $('#ddl_custodio_modal2 option:selected').text();
    var empla = $('#ddl_emplazamiento_modal2 option:selected').text();
    var sub = $('#txt_subtitulo2').val();
    var url = '../lib/phpword/generar_word.php?word_acta2=true&tipo=2&cus='+cus+'&cus_no='+cus_no+'&empla='+empla+'&sub='+sub;                 
    window.open(url, '_blank');  
 }

 function acta3()
 {
   var cus_no = $('#ddl_custodio_modal2').val();
   var cus = $('#ddl_custodio_modal2 option:selected').text();
   var empla = $('#ddl_emplazamiento_modal2 option:selected').text();
   var sub = $('#txt_subtitulo2').val();
    var url = '../lib/phpword/generar_word.php?word_acta2=true&tipo=3&cus='+cus+'&cus_no='+cus_no+'&empla='+empla+'&sub='+sub;       
    window.open(url, '_blank');  
 }

 function acta4()
 {
    var cusS_no = $('#ddl_custodio_modal4').val();
    var cusE_no = $('#ddl_custodio_modal_4').val();
    var cusS = $('#ddl_custodio_modal4 option:selected').text();
    var cusE = $('#ddl_custodio_modal_4 option:selected').text();
    var emplaS = $('#ddl_emplazamiento_modal4 option:selected').text();
    var emplaE = $('#ddl_emplazamiento_modal_4 option:selected').text();
    var sub = $('#txt_subtitulo4').val();
    var url = '../lib/phpword/generar_word.php?word_acta4=true&cusS='+cusS+'&cusE='+cusE+'&emplaS='+emplaS+'&emplaE='+emplaE+'&cS='+cusS_no+'&cE='+cusE_no+'&sub='+sub;                 
    window.open(url, '_blank');  
 }

 function acta5()
 {
   var donante = $('#txt_donante').val();
   var ci = $('#txt_ci_donante').val();
   var director = $('#txt_director').val();
   var unidad = $('#txt_nom_unidad').val();
   var sub = $('#txt_subtitulo5').val();

   var url = '../lib/phpword/generar_word.php?word_acta5=true&donante='+donante+'&ci='+ci+'&director='+director+'&unidad='+unidad+'&sub='+sub;    
   window.open(url, '_blank');
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
      'rfid':$('#rbl_rfid').prop('checked'),
      'bajas':$('#cbx_bajas').prop('checked'),
      'patri':$('#cbx_patri').prop('checked'),
      'terce':$('#cbx_terce').prop('checked'),
     }
     var lineas = '';
    $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/actasC.php?lista=true',
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
          if(item.BAJAS==1){baja = 'background-color: coral;/*bg-danger*/'}
          if(item.PATRIMONIALES==1){baja = 'background-color: #ffc108a6; /*bg-warning*/';}
          if(item.TERCEROS==1){baja ='background-color: #007bffa8;; /*bg-blue*/'}
          lineas+= '<tr style="'+baja+'"><td><input type="checkbox" id="rbl_'+item.id+'" name="rbl_'+item.id+'" value="'+item.id+'"><!--<button type="button" class="btn btn-sm btn-primary" onclick="pasar_lista(\''+item.id+'\')" tittle="Pasar lista"><i class="bx bx-arrow-to-right"></i></button>--></td><td style="color: #1467e2;"><u>'+item.tag+'</u></td><td>'+item.nom+'</td><td>'+item.RFID+'</td><td>'+item.localizacion+'</td><td>'+item.custodio+'</td><td>'+item.valor+'</td></tr>';
          console.log(item);
       
        });       
        $('#tbl_datos').html(lineas);        
      },
      error: function (error) {
    alert(JSON.stringify(error));
}
    });
  }


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

  function autocmpletar_acta2(){
      $('#ddl_custodio_modal2').select2({
        placeholder: 'Seleccione una custodio',
        dropdownParent: $('#modal_actas2'),
        width:'100%',
        ajax: {
          url: '../controlador/custodioC.php?lista_acta=true',
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

   function autocmpletar_acta4(){
      $('#ddl_custodio_modal4').select2({
        placeholder: 'Seleccione una custodio',
        dropdownParent: $('#modal_actas4'),
        width:'100%',
        ajax: {
          url: '../controlador/custodioC.php?lista_acta=true',
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

   function autocmpletar_acta_4(){
      $('#ddl_custodio_modal_4').select2({
        placeholder: 'Seleccione una custodio',
        dropdownParent: $('#modal_actas4'),
        width:'100%',
        ajax: {
          url: '../controlador/custodioC.php?lista_acta=true',
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

   function autocmpletar_lo_acta2(){
      $('#ddl_emplazamiento_modal2').select2({
        placeholder: 'Seleccione una localizacion',
         dropdownParent: $('#modal_actas2'),
        width:'100%',
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

    function autocmpletar_lo_acta4(){
      $('#ddl_emplazamiento_modal4').select2({
        placeholder: 'Seleccione una localizacion',
         dropdownParent: $('#modal_actas4'),
        width:'100%',
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

    function autocmpletar_lo_acta_4(){
      $('#ddl_emplazamiento_modal_4').select2({
        placeholder: 'Seleccione una localizacion',
         dropdownParent: $('#modal_actas4'),
        width:'100%',
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


  function pasar_lista(id)
  {
     var query = $('#txt_buscar').val();
     var parametros = 
     {
        'id':id,     
     }
     var lineas = '';
    $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/actasC.php?addacta=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
        console.log(response);
        if(response==-2)
        {
          Swal.fire('Este Activo ya esta en la lista','','error');
        }else if(response==1)
        {
          Swal.fire('Agregado a lista de acta','','success').then(function(){
            $('#myModal_espera').modal('hide');
          });
          lista_actas();
        }
      }
    });
    // alert('dd');
  }


  function eliminar_lista(id)
  {
     var parametros = 
     {
        'id':id,     
     }
    $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/actasC.php?eliminar_lista=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
        if(response==1)
        {
          // Swal.fire('Agregado a lista de acta','','success');
          lista_actas();
        }
      }
    });
    // alert('dd');
  }



  function lista_actas()
  {
     
     // $('#myModal_espera').modal('show');
     var lineas = '';
    $.ajax({
      // data:  {parametros:parametros},
      url:   '../controlador/actasC.php?lista_actas=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
        console.log(response);
        $.each(response,function(i,item){
          lineas+="<tr><td><button class='btn-danger btn-sm btn' onclick='eliminar_lista(\""+item.id+"\")'><i class='bx bx-trash'></i></button></td><td>"+item.asset+"</td><td>"+item.articulo+"</td><td>"+item.valor+"</td></tr>"
        });

        $('#lista_actas').html(lineas);

     $('#myModal_espera').modal('hide');
      }
    });
    // alert('dd');
  }
  function limpiar(ddl)
  {
    $('#'+ddl).val('').trigger('change');
  }

  function pasar_selected()
  {
     datos = $('#form_selected').serialize();
     if(datos=='')
     {
       Swal.fire('Selecione una activo','','info');
       return false;
     }

      $.ajax({
        data:  datos,
        url:   '../controlador/actasC.php?add_selected=true',
        type:  'post',
        dataType: 'json',
          success:  function (response) { 
            lista_articulos();
          lista_actas();
         if(response==1)
          {
            Swal.fire('Agregado a lista de acta','','success').then(function(){
              $('#myModal_espera').modal('hide');
            });
          }else
          {            
            Swal.fire('Algunos activos no se añadiero','Activos ya en lista','info').then(function(){
                   $('#myModal_espera').modal('hide');
            });
          }
          $('#myModal_espera').modal('hide');
          
        }
      });

     console.log(datos);
  }

  function pasar_todo()
  {
    var query = $('#txt_buscar').val();
    var custodio =  $('#ddl_custodio').val();
    var location = $('#ddl_localizacion').val();

    console.log(query)
    
    if(query=='' && custodio=='' && location == '')
    {
       Swal.fire('Seleccione algun filtro','No se puede agregar todos los activos','error');
       return  false;
    }

     $('#myModal_espera').modal('show');
     var parametros = 
     {
      'query':$('#txt_buscar').val(),
      'localizacion':  $('#ddl_localizacion').val(),
      'custodio': $('#ddl_custodio').val(),
      'pag':$('#txt_pag').val(),
      'exacto':$('#rbl_exacto').prop('checked'),
      'asset':$('#rbl_aset').prop('checked'),
      'asset_org':$('#rbl_aset_ori').prop('checked'),
      'rfid':$('#rbl_rfid').prop('checked'),
     }
      $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/actasC.php?add_masivo=true',
        type:  'post',
        dataType: 'json',
          success:  function (response) { 
          lista_actas();
         if(response==1)
          {
            Swal.fire('Agregado a lista de acta','','success').then(function(){
              $('#myModal_espera').modal('hide');
            });
          }else
          {            
            Swal.fire('Algunos activos no se añadiero','Activos ya en lista','info').then(function(){
                   $('#myModal_espera').modal('hide');
            });
          }
          $('#myModal_espera').modal('hide');
          
        }
      });

  }

  function eliminar_todo()
  {
    
      $.ajax({
        // data:  {parametros:parametros},
        url:   '../controlador/actasC.php?delete_masivo=true',
        type:  'post',
        dataType: 'json',
          success:  function (response) { 
          lista_actas();         
          $('#myModal_espera').modal('hide');
          
        }
      });

  }


   function dar_baja_acta1()
  {
    var parametros = 
    {
      'movimiento':$('#ddl_clase_mov').val(),
      'descripcion_mov':$('#ddl_clase_mov option:selected').text(),
    }
     $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/actasC.php?dar_baja=true',
        type:  'post',
        dataType: 'json',
          success:  function (response) { 
          if(response==1)
          {
            Swal.fire('Activos dados de baja','','success').then(function(){
                  $('#modal_actas1').modal('hide');
                  $('#modal_tipo_baja').modal('hide');
            })
          }          
        }
      });
  }

  function cambiar_custodio()
  {
    var parametros = 
    {
      'acta':$('#txt_acta_num').val(),
      'idC':$('#ddl_custodio_modal2').val(),
      'custodio':$('#ddl_custodio_modal2 option:selected').text(),
      'idL':$('#ddl_emplazamiento_modal2').val(),
      'location':$('#ddl_emplazamiento_modal2 option:selected').text(),
    }
     $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/actasC.php?cambiar_custodio=true',
        type:  'post',
        dataType: 'json',
          success:  function (response) { 
          if(response==1)
          {
            Swal.fire('Custodio y Emplazamiento Actualizados','','success').then(function(){
                  $('#modal_actas1').modal('hide');
                  $('#modal_tipo_baja').modal('hide');
            })
          }          
        }
      });
  }

  function cambiar_entrada_salida()
  {
    var parametros = 
    {
      'idCE':$('#ddl_custodio_modal_4').val(),
      'custodioE':$('#ddl_custodio_modal_4 option:selected').text(),
      'idLE':$('#ddl_emplazamiento_modal_4').val(),
      'locationE':$('#ddl_emplazamiento_modal_4 option:selected').text(),
      'idCS':$('#ddl_custodio_modal4').val(),
      'custodioS':$('#ddl_custodio_modal4 option:selected').text(),
      'idLS':$('#ddl_emplazamiento_modal4').val(),
      'locationS':$('#ddl_emplazamiento_modal4 option:selected').text(),
    }
     $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/actasC.php?cambiar_E_S=true',
        type:  'post',
        dataType: 'json',
          success:  function (response) { 
          if(response==1)
          {
            Swal.fire('Custodio y Emplazamiento Actualizados','','success').then(function(){
                  $('#modal_actas1').modal('hide');
                  $('#modal_tipo_baja').modal('hide');
            })
          }          
        }
      });
  }


</script>

<div class="page-wrapper">
      <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-0">
          <div class="breadcrumb-title pe-3">Actas</div>
          <div class="ps-3">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"></li>
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
                  <div class="col">
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="acta1"><i class="bx bx-file"></i>Donacion</button>      
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="acta2"><i class="bx bx-file"></i>Custodio Temporal</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="acta3"><i class="bx bx-file"></i>Custodio Definitivo</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="acta4"><i class="bx bx-file"></i>Traspaso saliente-entrante</button>
                     <button type="button" class="btn btn-outline-secondary btn-sm" id="acta5"><i class="bx bx-file"></i>Entrega de donacion</button>          
                  </div>     
                </div>
                <hr>
                 <div class="row">
                    <div class="col-sm-6">
                        <div class="row">
                          <div class="col-sm-6">
                            <label class="checkbox-inline" style="margin: 0px;"><input type="checkbox" name="" id="rbl_exacto" onclick="activar()" checked=""> Busqueda exacta</label>                  
                          </div>
                          <div class="col-sm-6">
                            <label class="checkbox-inline" style="margin: 0px;"><input type="radio" name="rbl_aset" id="rbl_aset"  onclick="lista_articulos()" checked=""> Asset</label>
                             <label class="checkbox-inline" style="margin: 0px;"><input type="radio" name="rbl_aset" id="rbl_aset_ori"  onclick="lista_articulos()"> Orig Asset</label>
                             <label class="checkbox-inline" style="margin: 0px;"><input type="radio" name="rbl_aset" id="rbl_rfid"  onclick="lista_articulos()"> RFID</label>                             
                          </div>
                        </div>
                        <input type="type" name="" id="txt_buscar" onkeyup="lista_articulos()" class="form-control form-control-sm" placeholder="Buscar Descripcion o tag">               
                    </div>
                    
                    <div class="col-sm-3">
                      <b> Busqueda por custodio</b>
                      <div class="input-group input-group-sm">
                        <select class="form-control form-control-sm" id="ddl_custodio" onchange="$('#txt_pag').val('0-25');lista_articulos()" style="width:80%">                  
                        </select>
                          <button type="button" class="btn btn-outline-secondary" onclick="limpiar('ddl_custodio')" title="Limpiar custodio"><i class="bx bx-x"></i></button>                  
                      </div>
                   </div>

                   <div class="col-sm-3">
                     <b> Busqueda por Localizacion</b>
                     <div class="input-group input-group-sm">
                      <select class="form-control form-control-sm" id="ddl_localizacion" onchange="$('#txt_pag').val('0-25');lista_articulos()" style="width:80%"></select>
                          <button type="button" class="btn btn-outline-secondary" onclick="limpiar('ddl_localizacion')" title="Limpiar localizacion"><i class="bx bx-x"></i></button>                
                      </div>
                   </div>            
             <div class="col-sm-6">
                <label><input type="checkbox" id="cbx_bajas" name="" onclick="lista_articulos()">Bajas</label>
                <label><input type="checkbox" id="cbx_patri" name="" onclick="lista_articulos()">Patrimoniales</label>
                <label><input type="checkbox" id="cbx_terce" name="" onclick="lista_articulos()">Terceros</label>
             </div>
        </div>
        <hr>
        <div class="row">
          <div class="col-sm-6" style="overflow-x: scroll;height: 400px; overflow-y: scroll;">           
            <b>Listado de bienes</b>
            <form id="form_selected">
            <table class="table table-striped table-bordered dataTable" style="white-space:nowrap;">
              <thead class="table table-hover table-sm">
                <th></th>
                <th>Asset</th>
                <th>Descripcion</th>
                <th>RFID</th>
                <th>Emplazamiento</th>                
                <th>Custodio</th>                
                <th>Valor</th>
              </thead>
              <tbody id="tbl_datos">
                
              </tbody>
            </table>
            </form>
          </div>
          <div class="col-sm-1">
            <br><br><br>
            <div class="col">
              <div class="btn-group-vertical" role="group" aria-label="Vertical button group">
                <button type="button" class="btn btn-outline-primary btn-xs" onclick="pasar_selected()">
                  <i class="bx bx-checkbox-checked"></i>
                  <i class="bx bx-right-arrow-alt"></i></button>
                <button type="button" class="btn btn-outline-primary" onclick="pasar_todo()">Todos<br><i class="bx bx-right-arrow-alt"></i></button>
              </div>
            </div>
          </div>          
          <div class="col-sm-5">
          <div class="row">
            <div class="col-sm-8">
              <b>Listado de bienes para acta</b>               
            </div>
            <div class="col-sm-4">
               <button class="btn btn-danger btn-sm" onclick="eliminar_todo()"><i class="bx bx-trash"></i> Eliminar</button>            
            </div>
          </div>                     
            <table class="table table-striped table-bordered dataTable">
              <thead>
                <th></th>
                <th>Asset</th>
                <th>Descripcion</th>
                <th>Valor</th>
              </thead>
              <tbody id="lista_actas">
                
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


 <div class="modal fade" id="modal_actas1" tabindex="-1" data-bs-backdrop="static" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Acta de donacion</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-6 text-end">
          </div>
          <div class="col-sm-6 text-end">
            <input type="" class="form-control form-control-sm" id="txt_subtitulo1" name="txt_subtitulo1" placeholder="Descripcion">
          </div>
          <div class="col-sm-12">
            <p>Nombre del beneficiario</p>
            <input type="" name="txt_person" id="txt_person" class="form-control form-control-sm">            
          </div>
          
        </div> 
      </div>
      <div class="modal-footer">
         <button type="button" class="btn btn-primary btn-sm" id="" onclick="$('#modal_tipo_baja').modal('show');">Dar de baja</button>  
         <button type="button" class="btn btn-primary btn-sm" id="btn_vin_cus" onclick="acta1()">Generar acta</button>  
         <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button> 
      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="modal_actas5" tabindex="-1" data-bs-backdrop="static" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Acta de entrega donacion activo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-6 text-end">
          </div>
          <div class="col-sm-6 text-end">
            <input type="" class="form-control form-control-sm" id="txt_subtitulo5" name="txt_subtitulo5" placeholder="Descripcion">
          </div>
          <div class="col-sm-8">
            <p>Nombre del donante</p>
            <input type="" name="txt_donante" id="txt_donante" class="form-control form-control-sm">            
          </div>
          <div class="col-sm-4">
            <p>CI / RUC Donante</p>
            <input type="" name="txt_ci_donante" id="txt_ci_donante" class="form-control form-control-sm">            
          </div>
          <div class="col-sm-12">
            <p>Director</p>
            <input type="" name="txt_director" id="txt_director" class="form-control form-control-sm">            
          </div>
          <div class="col-sm-12">
            <p>Nombre unidad</p>
            <input type="" name="txt_nom_unidad" id="txt_nom_unidad" class="form-control form-control-sm">            
          </div>
          
        </div> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-sm" id="btn_vin_cus" onclick="acta5()">Generar acta</button>   
         <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="modal_actas2" tabindex="-1" data-bs-backdrop="static" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="title_modal">Acta custodio temporal</h5>
        <input type="hidden" name="" id="txt_acta_num" value="2">
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-6 text-end">
          </div>
          <div class="col-sm-6 text-end">
            <input type="" class="form-control form-control-sm" id="txt_subtitulo2" name="txt_subtitulo2" placeholder="Descripcion">
          </div>
          <div class="col-sm-12">
            <b>Custodio</b>
            <br>
            <select class="form-select form-select-sm" id="ddl_custodio_modal2">
              <option value="">Seleccione custodio</option>
            </select>
            <br>
            <b>Emplazamiento</b>
            <br>
            <select class="form-select form-select-sm" id="ddl_emplazamiento_modal2">
              <option value="">Seleccione emplazamiento</option>
            </select>
          </div>
        </div>         
      </div>
      <div class="modal-footer">        
        <button type="button" class="btn btn-primary btn-sm" id="btn_cambiar2" onclick="cambiar_custodio()">Aplicar cambio</button>         
        <button type="button" class="btn btn-primary btn-sm" style="display:none;" id="btn_acta2" onclick="acta2()">Generar informe</button>  
        <button type="button" class="btn btn-primary btn-sm" style="display:none;" id="btn_acta3" onclick="acta3()">Generar informe</button>           
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>

</div><!-- /.container-fluid -->


<div class="modal fade" id="modal_actas4" tabindex="-1" data-bs-backdrop="static" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Acta Traspaso saliente-entrante</h5>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-6 text-end">
          </div>
          <div class="col-sm-6 text-end">
            <input type="" class="form-control form-control-sm" id="txt_subtitulo4" name="txt_subtitulo4" placeholder="Descripcion">
          </div>
          <div class="col-sm-12">
            <b>Custodio saliente</b>
            <br>
            <select class="form-control" id="ddl_custodio_modal4">
              <option value="">Seleccione custodio</option>
            </select>
            <br>
            <b>Emplazamiento saliente</b>
            <br>
            <select class="form-control" id="ddl_emplazamiento_modal4">
              <option value="">Seleccione emplazamiento</option>
            </select>
            <hr>
            <b>Custodio entrante</b>
            <select class="form-control" id="ddl_custodio_modal_4">
              <option value="">Seleccione custodio</option>
            </select>
            <br>
            <b>Emplazamiento entrante</b>
            <br>
            <select class="form-control" id="ddl_emplazamiento_modal_4">
              <option value="">Seleccione emplazamiento</option>
            </select>
              
          </div>          
        </div>             
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-sm" id="btn_cambiar4" onclick=" cambiar_entrada_salida()">Aplicar cambio</button> 
        <button type="button" class="btn btn-primary btn-sm" id="btn_vin_cus" onclick="acta4()">Generar informe</button>   
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div><!-- /.container-fluid -->




<div class="modal fade" id="modal_tipo_baja" tabindex="-1" data-bs-backdrop="static" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"></h5>
      </div>
      <div class="modal-body">
        <div class="row">     
          <div class="col-sm-12">
            <b>Clase de movimiento</b><br>
               <div class="input-group">
                <select class="form-select" id="ddl_clase_mov" style="width:100%">
                   <option value="">Selecciones</option>
                 </select>                                              
               </div>                   
          </div>
          
        </div> 
      </div>
      <div class="modal-footer">
         <button type="button" class="btn btn-primary btn-sm" id="btn_vin_cus" onclick="dar_baja_acta1()">Dar de baja</button> 
         <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button> 
      </div>
    </div>
  </div>
</div>


<?php include('./footer.php'); ?>
