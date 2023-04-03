<?php include('./header.php'); 
  $tipo_lista = 1;
  if(isset($_SESSION['INICIO']["LISTA_ART"])){$tipo_lista = $_SESSION['INICIO']["LISTA_ART"]; } 


?>
<script type="text/javascript">
  $('body').addClass('sidebar-collapse');
  $( document ).ready(function() {
     ddl_meses();
  	autocmpletar();
  	autocmpletar_l();
    var fil1 = '<?php if(isset($_GET["fil1"])){echo $_GET["fil1"];} ?>';
    var fil2 = '<?php if(isset($_GET["fil2"])){echo $_GET["fil2"];} ?>';
    // console.log(fil1);
    // console.log(fil2);
    if(fil1 !='null--null')
    {    
        var loc = fil1.split('--');
        $('#ddl_localizacion').append($('<option>',{value: loc[0], text: loc[1],selected: true }));
    }
    if(fil2 !='null--null')
    {
       var cus = fil2.split('--');
        $('#ddl_custodio').append($('<option>',{value: cus[0], text:cus[1],selected: true }));
    }

    var tipo_lista = '<?php echo $tipo_lista; ?>';
    console.log(tipo_lista);
    if(tipo_lista==1)
    {
      lista();
    }else
    {
      grilla();
    }


      // $('#imprimir_excel').click(function(){
      //  var url = '../lib/Reporte_excel.php?reporte_normal&query='+$('#txt_buscar').val()+'&loc='+$('#ddl_localizacion').val()+'&cus='+$('#ddl_custodio').val();                 
      //      window.open(url, '_blank');
      //  });
      $('#imprimir_excel_sap').click(function(){
        if($('#txt_desde').val()=='' || $('#txt_hasta').val()=='')
      {
        Swal.fire('Rango de fechas no validos','Asegurese de que los rangos de fecha esten bien seleccionados','info');
        return false;
      }
       var url = '../lib/Reporte_excel.php?reporte_sap&query='+$('#txt_buscar').val()+'&loc='+$('#ddl_localizacion').val()+'&cus='+$('#ddl_custodio').val()+'&desde='+$('#txt_desde').val()+'&hasta='+$('#txt_hasta').val();                 
           window.open(url, '_blank');
       });

      $('#imprimir_excel_bajas_sap').click(function(){
        if($('#txt_desde').val()=='' || $('#txt_hasta').val()=='')
      {
        Swal.fire('Rango de fechas no validos','Asegurese de que los rangos de fecha esten bien seleccionados','info');
        return false;
      }
       var url = '../lib/Reporte_excel.php?reporte_sap_bajas_rangos&query='+$('#txt_buscar').val()+'&loc='+$('#ddl_localizacion').val()+'&cus='+$('#ddl_custodio').val()+'&desde='+$('#txt_desde').val()+'&hasta='+$('#txt_hasta').val();                 
           window.open(url, '_blank');
       });

       $('#imprimir_excel_tot').click(function(){
       var url = '../lib/Reporte_excel.php?reporte_total';                 
           window.open(url, '_blank');
       });

       $('#imprimir_excel_actual').click(function(){
        var parametros = '&query='+$('#txt_buscar').val()+'&localizacion='+$('#ddl_localizacion').val()+'&custodio='+ $('#ddl_custodio').val()+'&pag='+$('#txt_pag').val()+'&exacto='+$('#rbl_exacto').prop('checked')+'&asset='+$('#rbl_aset').prop('checked')+
      '&asset_org='+$('#rbl_aset_ori').prop('checked')+'&rfid='+$('#rbl_rfid').prop('checked')+'&multiple='+$('#rbl_multiple').prop('checked');
       var url = '../lib/Reporte_excel.php?reporte_actual=true'+parametros;                 
           window.open(url, '_blank');
       });


        $('#imprimir_excel_bajas').click(function(){
       var url = '../lib/Reporte_excel.php?reporte_sap_bajas';                 
           window.open(url, '_blank');
       });
         $('#imprimir_excel_terceros').click(function(){
       var url = '../lib/Reporte_excel.php?reporte_sap_terceros';                 
           window.open(url, '_blank');
       });
          $('#imprimir_excel_patrimoniales').click(function(){
       var url = '../lib/Reporte_excel.php?reporte_sap_patrimoniales';                 
           window.open(url, '_blank');
       });

       $('#imprimir_excel_cambios').click(function(){
       var url = '../lib/Reporte_excel.php?reporte_cambios';                 
           window.open(url, '_blank');
       });

        $('#imprimir_excel_cambios_rango').click(function(){
       var url = '../lib/Reporte_excel.php?reporte_cambios=true&desde='+$('#txt_desde').val()+'&hasta='+$('#txt_hasta').val();                 
           window.open(url, '_blank');
       });




     $('#imprimir_pdf').click(function(){
      if($('#txt_desde').val()=='' || $('#txt_hasta').val()=='')
      {
        Swal.fire('Coleque fechas validas','','info');
        return false;
      }
      var url='../lib/Reporte_pdf.php?reporte_pdf&query='+$('#txt_buscar').val()+'&loc='+$('#ddl_localizacion').val()+'&cus='+$('#ddl_custodio').val()+'&desde='+$('#txt_desde').val()+'&hasta='+$('#txt_hasta').val();
      window.open(url, '_blank');
       });
      // $('#imprimir_pdf_sap').click(function(){
      // var url='../lib/Reporte_pdf.php?reporte_pdf_sap&query='+$('#txt_buscar').val()+'&loc='+$('#ddl_localizacion').val()+'&cus='+$('#ddl_custodio').val();
      // window.open(url, '_blank');
      //  });

      $('#reporte_pdf_total').click(function(){
      var url='../lib/Reporte_pdf.php?reporte_pdf_total';
      window.open(url, '_blank');
       });
      $('#reporte_pdf_bajas').click(function(){
      var url='../lib/Reporte_pdf.php?reporte_pdf_bajas';
      window.open(url, '_blank');
       });
       $('#reporte_pdf_terceros').click(function(){
      var url='../lib/Reporte_pdf.php?reporte_pdf_terceros';
      window.open(url, '_blank');
       });
        $('#reporte_pdf_patrimoniales').click(function(){
      var url='../lib/Reporte_pdf.php?reporte_pdf_patrimoniales';
      window.open(url, '_blank');
       });


});

 function autocmpletar(){
      $('#ddl_custodio').select2({
        placeholder: 'Seleccione una custodio',
        width:'90%',
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
        width:'90%',
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
      'rfid':$('#rbl_rfid').prop('checked'),
      'multiple':$('#rbl_multiple').prop('checked'),
      'lista':'1',
     }
     var lineas = '';
    $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/articulosC.php?lista=true',
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

        var pagi = '<li class="paginate_button page-item" onclick="guias_pag(\'-\')"><a class="page-link" href="#"> << </a></li>';
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
               pagi+='<li class="paginate_button page-item active" onclick="paginacion(\''+pa+'\')"><a class="page-link" href="#">'+i+'</a></li>';
              }else
              { 
                pagi+='<li class="paginate_button page-item" onclick="paginacion(\''+pa+'\')"><a class="page-link" href="#">'+i+'</a></li>';
              }
            }
           }else
           {

               pagi+='<li class="paginate_button page-item" onclick="paginacion(\'0-25\')"><a class="page-link" href="#">1</a></li>';
            for (var i = pag2[1]/25; i < (pag2[1]/25)+10 ; i++) {
              var pos =pag[1]*i;
              var ini =pos-pag[1];  
              var pa = ini+'-'+pos;
              if($('#txt_pag').val()==pa){
               pagi+='<li class="paginate_button page-item active" onclick="paginacion(\''+pa+'\')"><a class="page-link" href="#">'+i+'</a></li>';
              }else
              { 
                pagi+='<li class="paginate_button page-item" onclick="paginacion(\''+pa+'\')"><a class="page-link" href="#">'+i+'</a></li>';
              }
            }
           }
            pagi+='<li class="paginate_button page-item" onclick="guias_pag(\'+\')"><a class="page-link" href="#"> >> </a></li>'
           }else
           { 
             
            for (var i = 1; i < num+1 ; i++) {
              var pos =pag[1]*i;
              var ini =pag[1]-pos;  
              var pa = ini+'-'+pos;
              if($('#txt_pag').val() == pa)
              {
               pagi+='<li class="paginate_button page-item active"  onclick="paginacion(\''+pa+'\')"><a class="page-link" href="#">'+i+'</a></li>';
              }else
              {  
                pagi+='<li class="paginate_button page-item"  onclick="paginacion(\''+pa+'\')"><a class="page-link" href="#">'+i+'</a></li>';
              }
            }
           }

           // <li class="paginate_button page-item "><a href="#" aria-controls="example2" data-dt-idx="5" tabindex="0" class="page-link">5</a>
                          // </li>


        $('#pag').html(pagi);  

        }   
        $.each(response.datos, function(i, item){
          baja = '';
          if(item.BAJAS==1){baja = 'background-color: coral;/*bg-danger*/'}
          if(item.PATRIMONIALES==1){baja = 'background-color: #ffc108a6; /*bg-warning*/';}
          if(item.TERCEROS==1){baja ='background-color: #007bffa8;; /*bg-blue*/'}
          if(item.RFID==null){item.RFID='';}
          lineas+= '<tr style="'+baja+'"  onclick="redireccionar(\''+item.id+'\')"><td>'+item.id+'</td><td style="color: #1467e2;"><u>'+item.tag+'</u></td><td>'+item.nom+'</td><td>'+item.modelo+'</td><td>'+item.serie+'</td><td>'+item.RFID+'</td><td>'+item.localizacion+'</td><td>'+item.custodio+'</td><td>'+item.marca+'</td><td>'+item.estado+'</td><td>'+item.genero+'</td><td>'+item.color+'</td><td>'+item.fecha_in+'</td><td>'+item.OBSERVACION+'</td></tr>';
          // console.log(item);
       
        });       
        $('#tbl_datos').html(lineas);        
      },
      error: function (error) {
    alert(JSON.stringify(error));
}
    });
  }


  function lista_articulos_grid()
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
      'multiple':$('#rbl_multiple').prop('checked'),      
      'lista':'0',
  	 }
  	 var lineas = '';
    $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/articulosC.php?lista=true',
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

        var pagi = '<li class="paginate_button page-item" onclick="guias_pag(\'-\')"><a class="page-link" href="#"> << </a></li>';
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
               pagi+='<li class="paginate_button page-item active" onclick="paginacion(\''+pa+'\')"><a class="page-link" href="#">'+i+'</a></li>';
              }else
              { 
                pagi+='<li class="paginate_button page-item" onclick="paginacion(\''+pa+'\')"><a class="page-link" href="#">'+i+'</a></li>';
              }
            }
           }else
           {

               pagi+='<li class="paginate_button page-item" onclick="paginacion(\'0-25\')"><a class="page-link" href="#">1</a></li>';
            for (var i = pag2[1]/25; i < (pag2[1]/25)+10 ; i++) {
              var pos =pag[1]*i;
              var ini =pos-pag[1];  
              var pa = ini+'-'+pos;
              if($('#txt_pag').val()==pa){
               pagi+='<li class="paginate_button page-item active" onclick="paginacion(\''+pa+'\')"><a class="page-link" href="#">'+i+'</a></li>';
              }else
              { 
                pagi+='<li class="paginate_button page-item" onclick="paginacion(\''+pa+'\')"><a class="page-link" href="#">'+i+'</a></li>';
              }
            }
           }
            pagi+='<li class="paginate_button page-item" onclick="guias_pag(\'+\')"><a class="page-link" href="#"> >> </a></li>'
           }else
           { 
             
            for (var i = 1; i < num+1 ; i++) {
              var pos =pag[1]*i;
              var ini =pag[1]-pos;  
              var pa = ini+'-'+pos;
              if($('#txt_pag').val() == pa)
              {
               pagi+='<li class="paginate_button page-item active"  onclick="paginacion(\''+pa+'\')"><a class="page-link" href="#">'+i+'</a></li>';
              }else
              {  
                pagi+='<li class="paginate_button page-item"  onclick="paginacion(\''+pa+'\')"><a class="page-link" href="#">'+i+'</a></li>';
              }
            }
           }

           // <li class="paginate_button page-item "><a href="#" aria-controls="example2" data-dt-idx="5" tabindex="0" class="page-link">5</a>
                          // </li>


        $('#pag').html(pagi);  

        }   
        $.each(response.datos, function(i, item){
          baja = '';
          tex = '';
          if(item.BAJAS==1){baja = 'text-danger'; tex = 'BAJA';}
          if(item.PATRIMONIALES==1){baja = 'text-warning bg-light-warning'; tex = 'PATRIMONIAL';}
          if(item.TERCEROS==1){baja ='text-primary bg-light-primary'; tex = 'TERCEROS';}

          if(item.estado=='BUENO' || item.estado=='bueno')
          {
            estado ='<i class="bx bxs-star text-warning"></i>'+
                  '<i class="bx bxs-star text-warning"></i>'+
                  '<i class="bx bxs-star text-warning"></i>'+
                  '<i class="bx bxs-star text-warning"></i>'+
                  '<i class="bx bxs-star text-warning"></i>';

          }else if(item.estado=='malo' || item.estado=='MALO')
          {
            estado ='<i class="bx bxs-star text-secondary"></i>'+
                  '<i class="bx bxs-star text-secondary"></i>'+
                  '<i class="bx bxs-star text-secondary"></i>'+
                  '<i class="bx bxs-star text-secondary"></i>'+
                  '<i class="bx bxs-star text-secondary"></i>';
          }else
          {
             estado ='<i class="bx bxs-star text-warning"></i>'+
                  '<i class="bx bxs-star text-warning"></i>'+
                  '<i class="bx bxs-star text-warning"></i>'+
                  '<i class="bx bxs-star text-secondary"></i>'+
                  '<i class="bx bxs-star text-secondary"></i>';
          }

          imagen = '../img/sin_imagen.jpg';
          if(item.IMAGEN!='' && item.IMAGEN!=null)
          {
            imagen = item.IMAGEN;
          }

          lineas+='<div class="col">'+
            '<div class="card" onclick="redireccionar(\''+item.id+'\')">'+
              '<img src="../img/'+imagen+'" class="card-img-top" alt="..." style="width: 100%;height: 200px;">'+
              '<div class="">'
               if(baja!='')
                {
                lineas+='<div class="position-absolute top-0 end-0 m-3"><span class="">'+
                '<div class="badge rounded-pill '+baja+' p-2 text-uppercase px-3">'+tex+'</div>'+
                '</span></div>';
                }
              lineas+='</div>'+
              '<div class="card-body">'+
                '<h6 class="card-title cursor-pointer">'+item.nom+'</h6>'+
                '<div class="clearfix">'+
                  '<p class="mb-0 float-start"><strong>Asset</strong> '+item.tag+'</p><br>'+                              
                  '<p class="mb-0 float-start" style="font-size: 80%;"><strong>RFID:</strong> '+item.RFID+'</p>'+
                '</div>'+
                '<div class="d-flex align-items-center mt-1 mb-1 fs-6 font-13">'+
                  '<div class="cursor-pointer">Estado<br>'+
                  estado+
                  '</div>'+  
                  '<p class="mb-0 ms-auto font-13"><b>Fecha Inv.</b><br>'+item.fecha_in+'</p>'+
                '</div>'+
              '</div>'+
            '</div>'+
          '</div>';

        	// lineas+= '<tr style="'+baja+'"  onclick="redireccionar(\''+item.id+'\')"><td>'+item.id+'</td><td style="color: #1467e2;"><u>'+item.tag+'</u></td><td>'+item.nom+'</td><td>'+item.modelo+'</td><td>'+item.serie+'</td><td>'+item.RFID+'</td><td>'+item.localizacion+'</td><td>'+item.custodio+'</td><td>'+item.marca+'</td><td>'+item.estado+'</td><td>'+item.genero+'</td><td>'+item.color+'</td><td>'+item.fecha_in+'</td><td>'+item.OBSERVACION+'</td></tr>';
          // console.log(item);


       
        });       
        $('#grilla_art').html(lineas);        
      },
      error: function (error) {
    alert(JSON.stringify(error));
}
    });
  }


  function buscar_art()
  {
    var tipo_lista = '<?php echo $tipo_lista; ?>';
    if(tipo_lista==1)
    {
      lista_articulos();
    }else
    {
      lista_articulos_grid();
    }
  }


  function limpiar(ddl)
  {
  	$('#'+ddl).val('').trigger('change');
  }
  function redireccionar(id){
    var loc= 'null';var cus = 'null';
    if($('#ddl_localizacion').val() != null)
    {
      loc = $('#ddl_localizacion').select2('data')[0].text;
    }
    if($('#ddl_custodio').val() != null)
    {
      cus = $('#ddl_custodio').select2('data')[0].text;
    }
  	 window.location.href="detalle_articulo.php?id="+id+'&fil1='+$('#ddl_localizacion').val()+'--'+loc+'&fil2='+$('#ddl_custodio').val()+'--'+cus;
    }
function paginacion(num)
{
  $('#txt_pag').val(num);
  var pag = $('#txt_pag').val().split('-');
  var pos = pag[1]/25;

    var tipo_lista = '<?php echo $tipo_lista; ?>';
    if(tipo_lista==1)
    {
      lista_articulos();
    }else
    {
      lista_articulos_grid();
    }


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

function activar()
{
  if(!$('#rbl_exacto').prop('checked'))
  {
    $('#rbl_aset').prop('checked',false);
    $('#rbl_aset_ori').prop('checked',false);
    $('#rbl_aset').prop('disabled',true);
    $('#rbl_aset_ori').prop('disabled',true);
    $('#rbl_rfid').prop('checked',false);
    $('#rbl_rfid').prop('disabled',true);
  }else
  {

    $('#rbl_aset').prop('disabled',false);
    $('#rbl_aset_ori').prop('disabled',false);
    $('#rbl_rfid').prop('disabled',false);
    $('#rbl_aset').prop('checked',true);
  }
  lista_articulos();
}

  function ddl_meses()
  { 
    var opcion = '<option value="">seleccione un mes</option>';
    $.ajax({
      // data:  {id:id},
      url:  '../controlador/articulosC.php?meses=true',
      type:  'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
        success:  function (response) {
          // console.log(response);
          $.each(response,function(i,item){
            opcion+="<option value='"+item.num+"'>"+item.mes+"</option>";
          })
           $('#ddl_meses').html(opcion); 
      }
    });
  }


  function busqued_multiple()
  {
     check = $('#rbl_multiple').prop('checked');
     if(check)
     {
       $('#rbl_exacto').prop('checked',true);
       $('#rbl_exacto').attr('disabled',true);
       // alert('actyivo');
     }else
     {

       $('#rbl_exacto').prop('checked',true);
       $('#rbl_exacto').attr('disabled',false);
       // alert('no act');
     }
     lista_articulos();
  }

  function grilla()
  {
    $('#lista_art').css('display','none');
    $('#grilla_art').css('display','contents');

    $('#btn_grid').css('display','none');
    $('#btn_lista').css('display','block');
    lista_articulos_grid();
  }
  function lista()
  {
    $('#grilla_art').css('display','none');
    $('#lista_art').css('display','block');

    $('#btn_lista').css('display','none');
    $('#btn_grid').css('display','block');
    lista_articulos();
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
                <li class="breadcrumb-item active" aria-current="page">Articulos</li>
              </ol>
            </nav>
          </div>
           <div class="ms-auto">
            <div class="btn-group">
              <button type="button" class="btn btn-primary">Opciones</button>
              <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"> <span class="visually-hidden">Toggle Dropdown</span>
              </button>
              <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                <button type="button" class="dropdown-item" id="btn_grid" onclick="grilla()"><i class="bx bx-grid-alt"></i> Grilla</button>
                <button type="button" class="dropdown-item" id="btn_lista" onclick="lista()" style="display: none;"><i class="bx bx-list-ul"></i> Lista</button>
              </div>
            </div>
          </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
          <div class="col-xl-12 mx-auto">
            <hr>
            <div class="card">
              <div class="card-body">
                <div class="row row-cols-auto g-1">
                  <div class="col">
                    <div class="dropdown">
                      <button class="btn btn-outline-secondary dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-file"></i> Busq. Actual</button>
                      <ul class="dropdown-menu" style="">
                        <li><a class="dropdown-item" href="#" id="imprimir_excel_actual">Informe en EXCEL</a></li>
                      </ul>                                               
                    </div>
                  </div>
                  <div class="col">
                    <div class="dropdown">
                      <button class="btn btn-outline-secondary dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-file"></i>Activos</button>
                      <ul class="dropdown-menu" style="">
                        <li class=""><a class="dropdown-item" href="#" id="reporte_pdf_total"><i class="bx bx-file"></i>  Informe total en PDF</a></li>
                        <li class=""><a class="dropdown-item" href="#" id="imprimir_excel_tot"><i class="bx bx-file"></i>  Informe total en EXCEL</a></li>

                      </ul>                                               
                    </div>
                  </div>
                  <div class="col">
                    <div class="dropdown">
                      <button class="btn btn-outline-secondary dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-file"></i> Bajas</button>
                      <ul class="dropdown-menu" style="">
                          <li><a  class="dropdown-item" href="#" id="reporte_pdf_bajas"><i class="bx bx-file"></i> Informe total en PDF</a></li>
                          <li><a  class="dropdown-item" href="#" id="imprimir_excel_bajas"><i class="bx bx-file"></i> Informe total en EXCEL</a></li>
                      </ul>                                               
                    </div>
                  </div>
                  <div class="col">
                    <div class="dropdown">
                      <button class="btn btn-outline-secondary dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-file"></i> Patrimoniales.</button>
                      <ul class="dropdown-menu" style="">
                          <li><a  class="dropdown-item" href="#" id="reporte_pdf_patrimoniales"><i class="bx bx-file"></i> Informe total en PDF</a></li>
                          <li><a  class="dropdown-item" href="#" id="imprimir_excel_patrimoniales"><i class="bx bx-file"></i> Informe total en EXCEL</a></li>
                      </ul>                                               
                    </div>
                  </div>
                  <div class="col">
                    <div class="dropdown">
                      <button class="btn btn-outline-secondary dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-file"></i> Terceros</button>
                      <ul class="dropdown-menu" style="">
                         <li><a class="dropdown-item" href="#" id="reporte_pdf_terceros"><i class="bx bx-file"></i> Informe total en PDF</a></li>
                         <li><a class="dropdown-item" href="#" id="imprimir_excel_terceros"><i class="bx bx-file"></i> Informe total en EXCEL</a></li>
                      </ul>                                               
                    </div>
                  </div>
                  <div class="col">
                    <div class="dropdown">
                      <button class="btn btn-outline-secondary dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-file"></i> Informe Cambios</button>
                      <ul class="dropdown-menu" style="">
                        <li><a class="dropdown-item" href="#" id="imprimir_excel_cambios"><i class="bx bx-file"></i> Informe en EXCEL</a></li>
                        <li><a class="dropdown-item" href="#" id="" onclick="$('#myModal1').modal('show')"><i class="bx bx-calendar"></i> Por Fecha</a></li>
                      </ul>                                               
                    </div>
                  </div>
                  
                  <!-- <div class="col">
                    <button class="btn btn-primary btn-sm" onclick="$('#myModal1').modal('show')"><i class="bx bx-calendar"></i>Por fechas</button> 
                  </div> -->
                    
                </div>
                <hr>
                <div class="row">
                   <div class="col-sm-4">
                      <div class="row row-cols-auto g-1">
                        <div class="col">
                          <label class="checkbox-inline" style="margin: 0px;"><input type="checkbox" name="" id="rbl_exacto" onclick="activar()" checked=""> Busqueda exacta</label> 
                        </div>
                        <div class="col">
                          <label class="checkbox-inline" style="margin: 0px;"><input type="checkbox" name="" id="rbl_multiple" onclick="busqued_multiple()"> Busqueda Multiple</label>
                        </div>
                      </div>
                      <input type="" name="" id="txt_buscar" onkeyup="buscar_art();/*lista_articulos()*/" class="form-control form-control-sm" placeholder="Buscar Descripcion o tag">
                      <div class="row row-cols-auto g-1">
                        <div class="col">
                          <label class="checkbox-inline" style="margin: 0px;"><input type="radio" name="rbl_aset" id="rbl_aset"  onclick="buscar_art(); /*lista_articulos()*/" checked=""> Asset</label>
                          <label class="checkbox-inline" style="margin: 0px;"><input type="radio" name="rbl_aset" id="rbl_aset_ori"  onclick="buscar_art(); /*lista_articulos()*/"> Orig Asset</label>
                          <label class="checkbox-inline" style="margin: 0px;"><input type="radio" name="rbl_aset" id="rbl_rfid"  onclick="buscar_art(); /*lista_articulos()*/"> RFID</label>                                           
                        </div>
                      </div>
                   </div>
                   <div class="col-sm-4">
                    <label class="form-label"><b>Busqueda por custodio</b></label>
                    <div class="input-group">                 
                      <select class="form-select" id="ddl_custodio"  onchange="$('#txt_pag').val('0-25');buscar_art();/*lista_articulos()*/">
                        <option value="">Seleccione custodio</option>                            
                      </select>
                      <button type="button" style="padding:0px" class="btn btn-outline-secondary btn-sm" onclick="limpiar('ddl_custodio')"><i class="bx bx-x"></i></button>                
                    </div>
                   </div>
                   <div class="col-sm-4">
                      <label class="form-label"><b> Busqueda por Localizacion</b></label>
                      <div class="input-group">
                          <select class="form-select" id="ddl_localizacion" onchange="$('#txt_pag').val('0-25');buscar_art();/*lista_articulos()*/">
                            <option value="">Seleccione custodio</option>                            
                          </select>
                          <button type="button" style="padding:0px" class="btn btn-outline-secondary btn-sm" onclick="limpiar('ddl_localizacion')" title="Limpiar localizacion"><i class="bx bx-x"></i></button>                                    
                      </div>                     
                    </div>                  
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-6 col-md-8">
                    <label class="bg-danger"> Bajas </label>
                    <label class="bg-warning"> Patrimonial</label>
                    <label class="bg-primary"> Terceros </label>       
                  </div>
                  <div class="col-sm-6 col-md-4">
                      <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                        <ul class="pagination pagination-sm" id="pag">
                        </ul>
                      </div>
                    </div>
                </div>
                
                <div class="row">
                  <input type="hidden" id="txt_pag" name="" value="0-25">
                  <input type="hidden" id="txt_pag1" name="" value="0-25">
                  <input type="hidden" id="txt_numpag" name="">
                  <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4 row-cols-xxl-5 product-grid" id="grilla_art" style="display: none;">
                    
                  </div>
                  
                  <!-- <div class="col-sm-6">

                    <br>
                    <div class="row justify-content-end">
                      <nav aria-label="Page navigation example">
                        <ul class="pagination pagination-sm" id="pag" style="margin:0px;"></ul>
                      </nav>           
                    </div>
                  </div>
               -->
                  <div class="table-responsive" id="lista_art">
                      <div id="example_wrapper" class="dataTables_wrapper dt-bootstrap5">
                            <div class="col-sm-12">
                              <table id="example" class="table table-striped table-bordered dataTable" role="grid" style="cursor: pointer;">
                                <thead>
                                  <tr role="row">
                                    <th>Id</th>
                                    <th>Tag Serie</th>
                                    <th>Descripcion</th>
                                    <th>Modelo</th>
                                    <th>Serie</th>
                                    <th>RFID</th>
                                    <th>Localizacion</th>
                                    <th>Custodio</th>
                                    <th>Marca</th>
                                    <th>Estado</th>
                                    <th>Genero</th>
                                    <th>Color</th>
                                    <th>Fecha Inv.</th>
                                    <th>Observacion</th>
                                </thead>
                                <tbody id="tbl_datos">                  
                                  <tr role="row" class="odd">
                                    <td colspan="14">sin registros</td>
                                  </tr>
                                  
                                </tbody>
                                <tfoot>
                                  <tr><th>Id</th>
                                    <th>Tag Serie</th>
                                    <th>Descripcion</th>
                                    <th>Modelo</th>
                                    <th>Serie</th>
                                    <th>RFID</th>
                                    <th>Localizacion</th>
                                    <th>Custodio</th>
                                    <th>Marca</th>
                                    <th>Estado</th>
                                    <th>Genero</th>
                                    <th>Color</th>
                                    <th>Fecha Inv.</th>
                                    <th>Observacion</th>
                                  </tr>
                                </tfoot>
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

<!-- Modal -->

<div class="modal fade" id="myModal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Articulos modificados por fecha</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-sm-4">  
              <b style="font-size: 9px;">Desde:</b> <br>
              <input type="date" name="" id="txt_desde" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>">
            </div>
            <div class="col-sm-4">                
              <b style="font-size: 9px;">Hasta:</b> <br>
              <input type="date" name="" id="txt_hasta" class="form-control form-control-sm" value="<?php echo date('Y-m-d'); ?>">
            </div>
            <div class="col-sm-4"><br>
              <div class="dropdown">
                  <button class="btn btn-outline-secondary dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-file"></i> Informes</button>
                  <ul class="dropdown-menu" style="">
                    <li><a class="dropdown-item" href="#" id="imprimir_excel_sap">Informe excel para sap</a></li>
                    <li><a class="dropdown-item" href="#" id="imprimir_excel_bajas_sap">Informe excel bajas sap</a></li>
                    <li><a class="dropdown-item" href="#" id="imprimir_excel_cambios_rango">Informe excel cambios</a></li>
                  </ul>                                               
              </div>
            </div>                 
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


        <?php include('./footer.php'); ?>
     