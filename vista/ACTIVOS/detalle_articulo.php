<?php include('../../cabeceras/header.php'); ?>
<script type="text/javascript">
    $( document ).ready(function() {      
  // navegacion();

   $('#imprimir_cedula').click(function(){
        var url = '../../lib/Reporte_pdf.php?reporte_cedula=true&id='+$('#txt_id').val();
        window.open(url, '_blank');
    });

     $("#subir_imagen").on('click', function() {
        var formData = new FormData(document.getElementById("form_img"));
        var files = $('#file_img')[0].files[0];
        formData.append('file',files);
       // formData.append('curso',curso);
        $.ajax({
            url: '../../controlador/detalle_articuloC.php?cargar_imagen=true',
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            dataType:'json',
         // beforeSend: function () {
         //        $("#foto_alumno").attr('src',"../../img/gif/proce.gif");
         //     },
            success: function(response) {
               if(response==-1)
               {
                 Swal.fire(
                  '',
                  'Algo extraño a pasado intente mas tarde.',
                  'error')

               }else if(response ==-2)
               {
                  Swal.fire(
                  '',
                  'Asegurese que el archivo subido sea una imagen.',
                  'error')
               }  else
               {
                cargar_datos($('#txt_id').val()); 
               } 
            }
        });
    });

  //--------------------------------
   $('#ddl_marca').on('select2:select', function (e) {
      var data = e.params.data.data;

      $('#lbl_sap_mar').text('SAP:'+data.CODIGO)     
      // console.log(data);
    });
  //---------------------------------
   $('#ddl_genero').on('select2:select', function (e) {
      var data = e.params.data.data;
      $('#lbl_sap_gen').text('SAP:'+data.CODIGO)        
      // console.log(data);
    });
  //---------------------------------
  $('#ddl_color').on('select2:select', function (e) {
      var data = e.params.data.data;      
      $('#lbl_sap_col').text('SAP:'+data.CODIGO)  
      console.log(data);
    });
  //---------------------------------
  $('#ddl_estado').on('select2:select', function (e) {
      var data = e.params.data.data;      
      $('#lbl_sap_est').text('SAP:'+data.CODIGO)  
      console.log(data);
    });
  //---------------------------------
  $('#ddl_proyecto').on('select2:select', function (e) {
      var data = e.params.data.data;      
      $('#lbl_sap_pro').text('SAP:'+data.pro)  
      console.log(data);
    });
  //---------------------------------
  $('#ddl_localizacion').on('select2:select', function (e) {
      var data = e.params.data.data;      
      $('#lbl_sap_loc').text('SAP:'+data.EMPLAZAMIENTO)  
      console.log(data);
    });
  //---------------------------------
  });
  
</script>
<script type="text/javascript">	
  $( document ).ready(function() {
    var id = '<?php if(isset($_GET['id'])){ echo $_GET['id'];} ?>';
    $('#txt_id').val(id);
    lista_kit();
  	autocmpletar_l();
  	autocmpletar();
  	autocmpletar_color();
  	autocmpletar_marca();
  	autocmpletar_genero();
  	// estado();
    autocmpletar_proyecto();
    validar_datos();
    autocmpletar_fam();
    autocmpletar_subfam();
    autocmpletar_clase_mov();
    autocmpletar_estado()
  });

  var pagi = 50;
     
  function consultar_datos(id='')
  { 
    var marcas='';
    var id = id;

    $.ajax({
      data:  {id:id},
      url:   '../../controlador/marcasC.php?lista=true',
      type:  'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
        success:  function (response) {    
         // console.log(response);   
        $.each(response, function(i, item){
         // console.log('sss');
         marcas+='<tr><td>'+item.CODIGO+'</td><td>'+item.DESCRIPCION+'</td><td><button class="btn btn-danger" tittle="Eliminar" onclick="delete_datos(\''+item.ID_MARCA+'\')"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button><button class="btn btn-primary" tittle="Editar" onclick="datos_col(\''+item.ID_MARCA+'\')" data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button></td></tr>';
        });       
         $('#tbl_datos').html(marcas);        
      }
    });
  }

  function lista_kit()
  { 
    var parametros = {
      'activo':$('#txt_id').val(),
    }
    $.ajax({
      data:  {parametros:parametros},
      url:   '../../controlador/articulosC.php?lista_kit=true',
      type:  'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
        success:  function (response) {    
         // console.log(response);
        kit = '';   
        $.each(response, function(i, item){
          kit+='<tr>'+
          '<td>'+item.DESCRIPT+'</td>'+
          '<td>'+item.CARACTERISTICA+'</td>'+
          '<td>'+item.OBSERVACION+'</td>'+
          '<td><button class="btn btn-danger btn-sm" onclick="eliminar_kit('+item.id_plantilla+')"><i class="bx bx-trash"></i></button></td>'+
          '</tr>';

        });       
         $('#tbl_kit').html(kit);        
      }
    });
}

function eliminar_kit(id)
{
      Swal.fire({
      title: 'Eliminar de kit?',
      text: "Esta seguro de eliminar este registro?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
      if (result.value) {
        kit_eliminar(id);    
      }
    })

}

function kit_eliminar(id)
{
   var parametros = {
      'id':id,      
    }
    $.ajax({
      data:  {parametros:parametros},
      url:   '../../controlador/articulosC.php?delete_kit=true',
      type:  'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
        success:  function (response) {    
        if(response==1)
        {
           lista_kit();
        }       
      }
    });
}


function guardar_kit()
  { 

    if($('#txt_nombre_kit').val()=='' || $('#txt_identificador_kit').val()=='' ||  $('#txt_observacion_kit').val()=='')
    {
      Swal.fire('Llene todo los campos','','info');
      return false;
    }
    var parametros = {
      'activo':$('#txt_id_A').val(),
      'nombre':$('#txt_nombre_kit').val(),
      'identificador':$('#txt_identificador_kit').val(),
      'observacion':$('#txt_observacion_kit').val(),
    }
    $.ajax({
      data:  {parametros:parametros},
      url:   '../../controlador/articulosC.php?guardar_kit=true',
      type:  'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
        success:  function (response) {    
        if(response==1)
        {
           Swal.fire('Elemento añadido a kit','','success');
            $('#txt_nombre_kit').val('');
            $('#txt_identificador_kit').val('');
            $('#txt_observacion_kit').val('');
           lista_kit();
        }       
      }
    });
}

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

   function autocmpletar_fam(){
      $('#ddl_familia').select2({
        placeholder: 'Seleccione una familia',
        ajax: {
          url: '../../controlador/familiasC.php?lista_drop=true',
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

     function autocmpletar_clase_mov(){
      $('#ddl_clase_mov').select2({
        placeholder: 'Seleccione una familia',
        ajax: {
          url: '../../controlador/clase_movimientoC.php?buscar_auto=true',
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


  function autocmpletar_subfam(){
       var fa = $('#ddl_familia').val();
       if(fa==''){return false;}
      $('#ddl_subfamilia').select2({
        placeholder: 'Seleccione una Subfamilia',
        ajax: {
          url: '../../controlador/familiasC.php?lista_subfamilia=true&fam='+fa,
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
  function autocmpletar_color(){
      $('#ddl_color').select2({
        placeholder: 'Seleccione un color',
        ajax: {
          url:  '../../controlador/detalle_articuloC.php?colores=true',
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
  function autocmpletar_marca(){
      $('#ddl_marca').select2({
        placeholder: 'Seleccione una marca',
        ajax: {
          url: '../../controlador/detalle_articuloC.php?marca=true',
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
  function autocmpletar_genero(){
      $('#ddl_genero').select2({
        placeholder: 'Seleccione una custodio',
        ajax: {
          url: '../../controlador/detalle_articuloC.php?genero=true',
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
 // function estado()
 //  { 
 //    var id='';
 //    var estado = '<option value="">Seleccione Estado</option>';

 //    $.ajax({
 //      data:  {id:id},
 //      url:   '../../controlador/estadoC.php?lista=true',
 //      type:  'post',
 //      dataType: 'json',
 //        success:  function (response) {    
 //        // console.log(response);   
 //        $.each(response, function(i, item){
 //        	estado+="<option value='"+item.ID_ESTADO+"''>"+item.DESCRIPCION+"</option>";

 //          // console.log(item);
 //        });       
 //        $('#ddl_estado').html(estado);        
 //      }
 //    });
 //  }
  function autocmpletar_estado(){
      $('#ddl_estado').select2({
        placeholder: 'Seleccione Estado',
        ajax: {
          url: '../../controlador/estadoC.php?lista_drop=true',
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
  function autocmpletar_proyecto(){
      $('#ddl_proyecto').select2({
        placeholder: 'Seleccione una Proyecto',
        ajax: {
          url: '../../controlador/detalle_articuloC.php?proyecto=true',
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


  function validar_datos()
  {
  	var id = '<?php if(isset($_GET["id"])){echo $_GET["id"];}else{echo "-1";} ?>';
  	// console.log(id);
  	if(id==-1)
  	{
  		alert('no a seleccionado ningun articulo');
  	}else
  	{

  		movimientos(id);
      cargar_datos_view(id);

  	}
  }

  function cargar_datos(id)
  {
    $.ajax({
      data:  {id:id},
      url:   '../../controlador/detalle_articuloC.php?cargar_datos=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {   
          console.log(response);
        $('#txt_company').val(response[0].COMPANYCODE);
        $('#txt_description').val(response[0].nom);
        $('#txt_description2').val(response[0].des);
        $('#ddl_localizacion').append($('<option>',{value: response[0].id_loc, text: response[0].DENOMINACION,selected: true }));
        $('#ddl_custodio').append($('<option>',{value: response[0].id_cus, text: response[0].PERSON_NOM,selected: true }));
        $('#ddl_marca').append($('<option>',{value: response[0].mar, text: response[0].marca,selected: true }));
        $('#ddl_color').append($('<option>',{value: response[0].col, text: response[0].color,selected: true }));
        $('#ddl_genero').append($('<option>',{value: response[0].gen, text: response[0].genero,selected: true }));
        $('#ddl_proyecto').append($('<option>',{value: response[0].idpro, text: response[0].proyecto,selected: true }));
        $('#ddl_estado').append($('<option>',{value: response[0].est, text: response[0].estado,selected: true }));


        $('#ddl_familia').append($('<option>',{value: response[0].IDF, text: response[0].FAMILIA,selected: true }));
        $('#ddl_subfamilia').append($('<option>',{value: response[0].IDSUBF, text: response[0].SUBFAMILIA,selected: true }));
        $('#ddl_clase_mov').append($('<option>',{value: response[0].CLASE_MOVIMIENTO, text: response[0].MOVIMIENTO,selected: true }));
        $('#ddl_estado').val(response[0].est);
        $('#txt_asset').val(response[0].tag_s);
        $('#txt_subno').val(response[0].SUBNUMBER);
        $('#txt_assetsupno').val(response[0].ASSETSUPNO);
        $('#txt_rfid').val(response[0].rfid);
        $('#txt_tag_anti').val(response[0].ant);
        $('#txt_serie').val(response[0].SERIE);
        $('#txt_fecha').val( formatoDate(response[0].fecha.date));
        $('#txt_modelo').val(response[0].MODELO);
        $('#txt_id').val(response[0].id_A);        
        $('#txt_idA_img').val(response[0].id_A);
        $('#txt_id_A').val(response[0].id_AS);
        $('#txt_observacion').val(response[0].OBSERVACION);
        if(response[0].IMAGEN!='' && response[0].IMAGEN!=null)
        {
      	 $("#img_articulo").attr("src","../../img/"+response[0].IMAGEN);
        }
        $('#txt_nom_img').val(response[0].tag_s);
        $('#txt_cant').val(response[0].QUANTY);
        $('#txt_unidad').val(response[0].BASE_UOM);
        $('#txt_compra').val(formatoDate(response[0].ORIG_ACQ_YR.date));
        $('#txt_carac').val(response[0].CARACTERISTICA);
        $('#txt_valor').val(response[0].ORIG_VALUE);
        $('#txt_acti').val(response[0].ORIG_ASSET);
        $('#txt_cant').val(response[0].QUANTITY);
        $('#txt_compa').val(response[0].COMPANYCODE);


        $('#lbl_sap_col').text('SAP:'+response[0].Ccol);        
        $('#lbl_sap_est').text('SAP:'+response[0].Cest);
        $('#lbl_sap_mar').text('SAP:'+response[0].Cmar);
        $('#lbl_sap_pro').text('SAP:'+response[0].Cpro);
        $('#lbl_sap_gen').text('SAP:'+response[0].Cgen);
        $('#lbl_sap_loc').text('SAP:'+response[0].Cloc);


        bajas = false;terceros = false; patri = false;
        if(response[0].PATRIMONIALES=='1'){patri = true;}
        if(response[0].TERCEROS=='1'){terceros = true;}
        if(response[0].BAJAS=='1'){bajas = true;}

        if(patri==false && terceros==false && bajas==false)
        {
          $('#txt_ninguno').prop('checked',true );
        }

        $('#txt_bajas').prop('checked', bajas);
        $('#txt_tercero').prop('checked', terceros);
        $('#txt_patrimonial').prop('checked',patri );


      $('#txt_sistema_op').val(response[0].SISTEMA_OP);
      $('#txt_arquitectura').val(response[0].ARQUITECTURA);
      $('#txt_kernel').val(response[0].KERNEL);
      $('#txt_producto_id').val(response[0].PRODUCTO_ID);
      $('#txt_version').val(response[0].VERSION);
      $('#txt_service_pack').val(response[0].SERVICE_PACK);
      $('#txt_edicion').val(response[0].EDICION);


        // $('#ddl_localizacion').val('55'); // Select the option with a value of '1'
        // console.log(response);   
      //    if($('#editar').val()==0 || $('#dba').val()==0)
      // {
      //   $('#btn_editar').hide();
      // }

        datos_col(response[0].id_cus);
        autocmpletar_fam();
        autocmpletar_subfam();
              
      }
    });
  }

   function cargar_datos_view(id)
  {
    $.ajax({
      data:  {id:id},
      url:   '../../controlador/detalle_articuloC.php?cargar_datos=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {   
          console.log(response);
        $('#txt_company').val(response[0].COMPANYCODE);
        $('#lbl_descripcion').text(response[0].nom);
        $('#lbl_descricion2').text(response[0].des);
        $('#lbl_localizacion1').html('<b>Emplazamiento / Localizacion</b> | <label style="font-size:65%"> SAP:'+response[0].Cloc+'</label>');
        $('#lbl_localizacion').text(response[0].DENOMINACION);

        $('#lbl_custodio1').html('<b>Custodio:</b> | <label style="font-size:65%"> SAP:'+response[0].Ccus+'</label>');
        $('#lbl_custodio').text(response[0].PERSON_NOM);

        $('#lbl_marca').html(response[0].marca+' | <label style="font-size:65%"> SAP:'+response[0].Cmar+'</label>');     
        $('#lbl_color').html(response[0].color+' | <label style="font-size:65%"> SAP:'+response[0].Ccol+'</label>');
        $('#lbl_genero').html(response[0].genero+' | <label style="font-size:65%"> SAP:'+response[0].Cgen+'</label>');
        $('#lbl_proyecto').html(response[0].proyecto+' | <label style="font-size:65%"> SAP:'+response[0].Cpro+'</label>');
        $('#lbl_estado').html(response[0].estado+' | <label style="font-size:65%"> SAP:'+response[0].Cest+'</label>');


        // $('#ddl_familia').append($('<option>',{value: response[0].IDF, text: response[0].FAMILIA,selected: true }));
        // $('#ddl_subfamilia').append($('<option>',{value: response[0].IDSUBF, text: response[0].SUBFAMILIA,selected: true }));
        // $('#ddl_clase_mov').append($('<option>',{value: response[0].CLASE_MOVIMIENTO, text: response[0].MOVIMIENTO,selected: true }));
        // $('#ddl_estado').val(response[0].est);
        $('#lbl_asset').html('<b>Asset:</b>'+response[0].tag_s);
        $('#lbl_sub_num').html('<b>SubNum:</b>'+response[0].SUBNUMBER);
        // $('#txt_assetsupno').val(response[0].ASSETSUPNO);
        $('#lbl_rfid').html(response[0].rfid);
        $('#lbl_tag_ant').html('<b>Tag Antiguo:</b>'+response[0].ant);
        $('#lbl_serie').text(response[0].SERIE);
        $('#lbl_fecha_inve').text(response[0].fecha.date);
        $('#lbl_modelo').text(response[0].MODELO);
        // $('#txt_id').val(response[0].id_A);        
        // $('#txt_idA_img').val(response[0].id_A);
        // $('#txt_id_A').val(response[0].id_AS);
        if(response[0].OBSERVACION!='')
        {
          $('#lbl_observaciones').css('display','block');
          $('#lbl_observaciones').html('<b>Obsercaciones:</b>'+response[0].OBSERVACION);
        }
        if(response[0].IMAGEN!='' && response[0].IMAGEN!=null)
        {
         $("#img_articulo").attr("src","../../img/"+response[0].IMAGEN);
        }
        // $('#txt_nom_img').val(response[0].tag_s);
        $('#lbl_unidad').text('/'+response[0].BASE_UOM);
        $('#lbl_fecha_compra').text(response[0].ORIG_ACQ_YR.date);

        if(response[0].CARACTERISTICA!='')
        {
          $('#lbl_caracteristicas').css('display','block');
          $('#lbl_caracteristicas').html(response[0].CARACTERISTICA);
        }
        $('#lbl_precio').text('$'+response[0].ORIG_VALUE);
        // $('#txt_acti').val(response[0].ORIG_ASSET);
        $('#lbl_canti').text(response[0].QUANTITY);
        // $('#txt_compa').val(response[0].COMPANYCODE);



        bajas = false;terceros = false; patri = false;
        if(response[0].PATRIMONIALES=='1')
        {
          $('#lbl_tipo').html('<div class="text-warning">ACTIVO PATRIONIAL</div>');
        }
        if(response[0].TERCEROS=='1')
        {          
            $('#lbl_tipo').html('<div class="text-primary">ACTIVO DE TERCERO</div>');
        }
        if(response[0].BAJAS=='1')
        {          
            $('#lbl_tipo').html('<div class="text-danger">ACTIVO DE BAJA</div>');
        }

        if(response[0].SISTEMA_OP!='' ||  response[0].ARQUITECTURA!='' || response[0].KERNEL!='' || response[0].PRODUCTO_ID!='' ||     response[0].VERSION!='' ||  response[0].SERVICE_PACK!='' ||  response[0].EDICION!='')
        {
          $('#detalle_it').css('display','flex');
          $('#lbl_sistema_op').text(response[0].SISTEMA_OP);
          $('#lbl_arquitectura').text(response[0].ARQUITECTURA);
          $('#lbl_kernel').text(response[0].KERNEL);
          $('#lbl_producto_id').text(response[0].PRODUCTO_ID);
          $('#lbl_version').text(response[0].VERSION);
          $('#lbl_service_pack').text(response[0].SERVICE_PACK);
          $('#lbl_edicion').text(response[0].EDICION);
        }

        // $('#ddl_localizacion').val('55'); // Select the option with a value of '1'
        // console.log(response);   
      //    if($('#editar').val()==0 || $('#dba').val()==0)
      // {
      //   $('#btn_editar').hide();
      // }

        datos_col(response[0].id_cus);
        autocmpletar_subfam();
              
      }
    });
  }
 

  function movimientos()
  {
  	var table = '';
    var id =$('#txt_id').val();
    var desde = $('#txt_desde').val();    
    var hasta = $('#txt_hasta').val();
    if(desde!='' && hasta=='' || desde=='' && hasta!='')
    {
      Swal.fire('Rango de fecha no valido','Seleccione fechas correctas','info');
    }
    var parametros = 
    {
      'id':id,
      'desde':desde,
      'hasta':hasta,
    }
    $.ajax({
      data:  {parametros:parametros},
      url:   '../../controlador/detalle_articuloC.php?movimientos=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {   
        $.each(response,function(i,item){
        	console.log(item);
        	table+="<tr><td>"+item.ob+"</td><td style='white-space: nowrap;'>"+formatoDate(item.fe.date)+"</td><td>"+item.codigo_ant+"</td><td>"+item.dante+"</td><td>"+item.codigo_nue+"</td><td>"+item.dnuevo+"</td><td>"+item.responsable+"</td></tr>"
        });
        $('#table_contenido').html(table); 

              
      }
    });
  }

  function guardar_articulo()
  {
    var parametros = 
    {

        'company': $('#txt_company').val(),
        'desc':$('#txt_description').val(),
        'des2':$('#txt_description2').val(),
        'loca':$('#ddl_localizacion').val(),
        'cust':$('#ddl_custodio').val(),
        'marc':$('#ddl_marca').val(),
        'colo':$('#ddl_color').val(),
        'gene':$('#ddl_genero').val(),
        'asse':$('#txt_asset').val(),
        'assetno':$('#txt_assetsupno').val(),
        'esta':$('#ddl_estado').val(),
        'rfid':$('#txt_rfid').val(),
        'tagA':$('#txt_tag_anti').val(),
        'seri':$('#txt_serie').val(),
        'fech':$('#txt_fecha').val(),
        'mode':$('#txt_modelo').val(),
        'idAr':$('#txt_id').val(),
        'idAs':$('#txt_id_A').val(),
        'obse':$('#txt_observacion').val(),
        'cant':$('#txt_cant').val(),
        'uni':$('#txt_unidad').val(),
        'compra':$('#txt_compra').val(),
        'cara':$('#txt_carac').val(),
        'valor':$('#txt_valor').val(),
        'act':$('#txt_acti').val(),
        'crit':$('#ddl_proyecto').val(),
        'bajas':$('#txt_bajas').prop('checked'),
        'terceros':$('#txt_tercero').prop('checked'),
        'patrimoniales':$('#txt_patrimonial').prop('checked'),
        'familia':$('#ddl_familia').val(),
        'subfamilia':$('#ddl_subfamilia').val(),
        'clase_mov':$('#ddl_clase_mov').val(),
        'movimiento':$('#ddl_clase_mov option:selected').text(),
    };
    // console.log(parametros);
    var id =$('#txt_id').val();
    $.ajax({
      data:  {parametros:parametros},
      url:   '../../controlador/detalle_articuloC.php?guardarArticulo=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
        if(response ==1)
        {
          Swal.fire(
            '',
            'Operacion realizada con exito.',
            'success'
          )
         cargar_datos(id);   
          movimientos();
        }else
        {
          Swal.fire(
            '',
            'Algo extraño a pasado.',
            'error'
          )

        }           
      }
    });

  }

  function navegacion()
   { 
     var fil1 = '<?php if(isset($_GET["fil1"])){echo $_GET["fil1"];} ?>';
    var fil2 = '<?php if(isset($_GET["fil2"])){echo $_GET["fil2"];} ?>';
    var id = '<?php if(isset($_GET["id"])){echo $_GET["id"];}else{echo "-1";} ?>';
    var botones = '';
    var parametros = 
    {
      'loc':fil1,
      'cus':fil2,
      'id':id,
    }

    $.ajax({
      data:  {parametros:parametros},
      url:   '../../controlador/detalle_articuloC.php?navegacion=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
          if(response.atras != 0)
          {

          botones='<a class="btn btn-default" href="../vista/detalle_articulo.php?id='+response.atras+'&fil1=<?php  if(isset($_GET["fil1"])){echo $_GET["fil1"];} ?>&fil2=<?php if(isset($_GET["fil2"])){echo $_GET["fil2"];} ?>"><i class="fa fa-caret-left"></i> Atras</a><a class="btn btn-default" href="../vista/detalle_articulo.php?id='+response.siguiente+'&fil1=<?php if(isset($_GET["fil1"])){echo $_GET["fil1"];} ?>&fil2=<?php if(isset($_GET["fil2"])){echo $_GET["fil2"];} ?>">Siguiente <i class="fa fa-caret-right"></i></a>';
          }else
          {
            botones='<a class="btn btn-default" href="../vista/detalle_articulo.php?id='+response.siguiente+'&fil1=<?php if(isset($_GET["fil1"])){echo $_GET["fil1"];} ?>&fil2=<?php if(isset($_GET["fil2"])){echo $_GET["fil2"];} ?>">Siguiente <i class="fa fa-caret-right"></i></a>';
          }

          $('#na').html(botones);

      }
    });

  }

  function datos_col(id)
  { 
    $('#titulo').text('Editar custodio');
    $('#op').text('Editar');
    var custodio='';

    $.ajax({
      data:  {id:id},
      url:   '../../controlador/custodioC.php?listar_todo=true',
      type:  'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
        success:  function (response) {
          // console.log(response);
           $('#txt_nombre').val(response[0].PERSON_NOM); 
           $('#txt_ci').val(response[0].PERSON_CI); 
           $('#txt_email').val(response[0].PERSON_CORREO);
           $('#txt_puesto').val(response[0].PUESTO); 
           $('#txt_unidad_p').val(response[0].UNIDAD_ORG); 
           $('#id').val(response[0].ID_PERSON); 
      }
    });
  }

  function add_familia()
  {
    $('#modal_familia').modal('show');
  }
  function add_subfamilia()
  {
    var fam = $('#ddl_familia').val();
    if(fam=='')
    {
      Swal.fire('Seleccione una familia','','info');
      return false;
    }

    $('#modal_subfamilia').modal('show');
  }

  function guardar_familia()
  {    
    if($('#txt_new_familia').val()=='')
    {
      Swal.fire('Llene el campo','','info');
      return false;
    }
    var parametros = 
    {
      'id':'',
      'familia':$('#txt_new_familia').val(),
    }

    $.ajax({
      data:  {parametros:parametros},
      url:   '../../controlador/familiasC.php?insertar=true',
      type:  'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
        success:  function (response) {
          // console.log(response);
          if(response==1)
          {
            Swal.fire('Familia ingresada','','success');
            $('#modal_familia').modal('hide');
          }
      }
    });
  }

  function guardar_subfamilia()
  { 
   if($('#txt_new_subfamilia').val()=='')
    {
      Swal.fire('Llene el campo','','info');
      return false;
    }
    var parametros = 
    {
      'id':'',
      'familia':$('#ddl_familia').val(),
      'subfamilia':$('#txt_new_subfamilia').val(),
    }

    $.ajax({
      data:  {parametros:parametros},
      url:   '../../controlador/familiasC.php?insertar_sub=true',
      type:  'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
        success:  function (response) {
          // console.log(response);
          if(response==1)
          {
            Swal.fire('SubFamilia ingresada','','success');
            $('#modal_subfamilia').modal('hide');
          }
      }
    });
  }

  function editar_custodio()
  {
     idc = $('#id').val();
     location.href = '../vista/custodio_detalle.php?id='+idc;
  }

  function validar_campo()
  {
     var asset = $('#txt_asset').val();
     var cant = $('input[type=radio][name="rbl_asset"]:checked').val();
     if(cant!=0)
     {
        num_caracteres('txt_asset',cant);
     }

     console.log(asset);
     console.log(cant);
  }

  function editar()
  {
    // alert('entra');
    var id = '<?php if(isset($_GET["id"])){echo $_GET["id"];}else{echo "-1";} ?>';
    // console.log(id);
    if(id==-1)
    {
      alert('no a seleccionado ningun articulo');
    }else
    {
      cargar_datos(id);
      $('#form_img').css('display','block');
      $('#panel_editar').css('display','block');
      $('#panel_vista').css('display','none');
      $('#btn_editar').css('display','none');    
      $('#btn_vista').css('display','block');
    }

  }
  function vista()
  {
    var id = '<?php if(isset($_GET["id"])){echo $_GET["id"];}else{echo "-1";} ?>';
    // console.log(id);
    if(id==-1)
    {
      alert('no a seleccionado ningun articulo');
    }else
    {
      cargar_datos_view(id);
      $('#form_img').css('display','none');
      $('#panel_editar').css('display','none');
      $('#panel_vista').css('display','block');
      $('#btn_editar').css('display','block');    
      $('#btn_vista').css('display','none');
    }
  }

   function guarda_detalles_it()
    {
      datos = $('#form_detalle_it').serialize();
      datos = datos+'&id='+$('#txt_id').val();
       $.ajax({
        data:  datos,
        url:   '../../controlador/articulosC.php?guardar_it=true',
        type:  'post',
        dataType: 'json',
        success:  function (response) { 
          if(response==1)
          {
            Swal.fire('Detalles de IT registrados','','success');
          }  
          // console.log(response);                      
          }
        }); 
    }

  function imprimir_tags_masivo()
  {
     var query = $('#txt_buscar').val();
     var parametros = 
     {
      'query':$('#lbl_asset').text(),
      'localizacion':'',
      'custodio': '',
      'pag':'',
     }
     var lineas = '';
    $.ajax({
      data:  {parametros:parametros},
      url:   '../../controlador/articulosC.php?lista_imprimir=true',
      type:  'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
        success:  function (response) {    
        console.log(response);  
        if(response==1)
        {
          Swal.fire( '',
                  'Etiquetas generadas Dirijase a Zebra designer.',
                  'info');
        } else if(response==2)
        {
         Swal.fire({
            title: 'Existen etiquetas generadas para impresion!',
            text: "desea generar etiquetas!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Eliminar y continuar!'
          }).then((result) => {
            if (result.value) {
              vaciar_tags();
            }
          })

        }
      }
       
    });
  }

  function vaciar_tags()
  {
     $.ajax({
      // data:  {parametros:parametros},
      url:   '../../controlador/articulosC.php?vaciar=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) {
         imprimir_tags_masivo();
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
                <li class="breadcrumb-item active" aria-current="page">Detalle de articulos</li>
              </ol>
            </nav>
          </div>
           <div class="ms-auto">
            <div class="btn-group">
              <button type="button" class="btn btn-primary">Opciones</button>
              <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"> <span class="visually-hidden">Toggle Dropdown</span>
              </button>
              <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                <button type="button" class="dropdown-item" id="btn_editar" onclick="editar()"><i class="bx bx-pencil"></i> Editar</button>
                <button type="button" class="dropdown-item" id="btn_vista" onclick="vista()" style="display: none;"><i class="bx bx-eye"></i> Vista</button>
              </div>
            </div>
          </div>
        
        </div>
        <!--end breadcrumb-->         
         <div class="card">          
          <div class="card-body">
            <div class="row row-cols-auto g-1">
               <div class="col">
                <a class="btn btn-outline-secondary btn-sm" href="articulos.php"><i class="bx bx-left-arrow-alt"></i> Regresar</a>         
              </div> 
              <div class="col">
                <button class="btn btn-outline-secondary btn-sm" type="button" id="imprimir_cedula"><i class="bx bx-file"></i> Cedula activo</button> 
                <button class="btn btn-outline-secondary btn-sm" type="button" onclick="imprimir_tags_masivo()"><i class="bx bx-purchase-tag"></i> Reimprimir Tag RFID</button>         
              </div>  
            </div>           
          </div>
          <div class="row g-0">
            <div class="col-md-3 border-end">
            <div class="image-zoom-section">
              <div class="product-gallery owl-carousel owl-theme border mb-3 p-3" data-slider-id="1">
                <div class="item">
                  <img src="../../img/sin_imagen.jpg" class="img-fluid" id="img_articulo" alt=""> 
                </div>
              </div>
              <form enctype="multipart/form-data" id="form_img" method="post" style="display:none;">
                <div class="custom-file">
                  <input type="file" class="form-control form-control-sm" id="file_img" name="file_img">
                  <input type="hidden" name="txt_nom_img" id="txt_nom_img">
                  <input type="hidden" name="txt_idA_img" id="txt_idA_img">
                </div>
                <button type="button" class="btn btn-primary btn-sm" style="width: 100%" id="subir_imagen"> Subir Imagen</button>
              </form>               
            </div>
            </div>
            <div class="col-md-9">
              <input type="hidden" name="" id="txt_id">
              <input type="hidden" name="" id="txt_id_A">
              <div class="card-body" id="panel_vista">
              <h4 class="card-title" id="lbl_descripcion"></h4>
               <div class="d-flex gap-3 py-1">                
                <div id="lbl_descripcion2"></div>
              </div>
              <div class="d-flex gap-3 py-1">      
                <div id="lbl_sub_num"></div>     
                <div id="lbl_asset"></div>
                <div id="lbl_tag_ant"></div>                
              </div>
               <dl class="row mb-1">
                <dt class="col-sm-1">RFID</dt>                
                <dd class="col-sm-5" id="lbl_rfid"></dd>

                <dd class="col-sm-6">
                  <div id="lbl_tipo">
                    <div class="text-default">ACTIVO PROPIO</div>
                  </div>
                </dd>

              </dl>

               <dl class="row mb-1">
                <dd class="col-sm-6">
                  <b>Valor Actual:</b>
                  <span class="price h4" id="lbl_precio">0</span> 
                </dd>                
                <dd class="col-sm-6">
                   <b>cantidad:</b>  
                  <span class="price h4" id="lbl_canti">0</span> 
                  <span class="text-muted" id="lbl_unidad">/</span> 
                </dd>
              </dl>                 
              
              
              <div class="card-text fs-6" id="lbl_custodio1">.</div>             
              <p class="card-text fs-6" id="lbl_custodio">.</p>
              <div class="card-text fs-6" id="lbl_localizacion1">.</div>
              <p class="card-text fs-6" id="lbl_localizacion">.</p>

              <p class="card-text fs-6" id="lbl_caracteristicas" style="display:none;">.</p>
              <p class="card-text fs-6" id="lbl_observaciones" style="display:none;">.</p>
              <dl class="row">

              <dt class="col-sm-2">Marca</dt>
              <dd class="col-sm-4" id="lbl_marca"></dd>

              <dt class="col-sm-2">Genero</dt>
              <dd class="col-sm-4" id="lbl_genero"></dd>
              
              <dt class="col-sm-2">Color</dt>
              <dd class="col-sm-4" id="lbl_color"></dd>

              <dt class="col-sm-2">Estado</dt>
              <dd class="col-sm-4" id="lbl_estado"></dd>

              <dt class="col-sm-2">Model</dt>
              <dd class="col-sm-4" id="lbl_modelo"></dd>

              <dt class="col-sm-2">Serie</dt>
              <dd class="col-sm-4" id="lbl_serie"></dd>

              <dt class="col-sm-2">Proyecto</dt>
              <dd class="col-sm-9" id="lbl_proyecto"></dd>

              </dl>
              <dl class="row" id="detalle_it" style="display:none;">
                  <hr>
                  <p class="card-text fs-6">Detalles IT</p>

                  <dt class="col-sm-3">Sistema Operativo</dt>
                  <dd class="col-sm-3" id="lbl_sistema_op"></dd>

                  <dt class="col-sm-2">Arquitectura</dt>
                  <dd class="col-sm-4" id="lbl_arquitectura"></dd>
                  
                  <dt class="col-sm-2">Kernel</dt>
                  <dd class="col-sm-4" id="lbl_kernel"></dd>

                  <dt class="col-sm-2">Producto id</dt>
                  <dd class="col-sm-4" id="lbl_producto_id"></dd>

                  <dt class="col-sm-2">Version</dt>
                  <dd class="col-sm-4" id="lbl_version"></dd>

                  <dt class="col-sm-2">Service pack</dt>
                  <dd class="col-sm-4" id="lbl_service_pack"></dd>

                  <dt class="col-sm-2">Edicion</dt>
                  <dd class="col-sm-9" id="lbl_edicion"></dd>

              </dl>
              <hr>
              <div class="row row-cols-auto align-items-center mt-3">
              <div class="col">
                <label class="form-label"><b>Fecha de compra</b></label>
                <div id="lbl_fecha_compra"></div>
              </div>
              <div class="col">
                <label class="form-label"><b>Fecha de Inventario</b></label>
               <div id="lbl_fecha_inve"></div>
              </div>
              <!-- <div class="col">
                <label class="form-label">Colors</label>
                <div class="color-indigators d-flex align-items-center gap-2">
                  <div class="color-indigator-item bg-primary"></div>
                  <div class="color-indigator-item bg-danger"></div>
                  <div class="color-indigator-item bg-success"></div>
                  <div class="color-indigator-item bg-warning"></div>
                </div>
              </div> -->
            </div>
            <!--end row-->
            <!-- <div class="d-flex gap-2 mt-3">
              <a href="javascript:;" class="btn btn-primary"><i class="bx bxs-cart-add"></i>Add to Cart</a>
              <a href="javascript:;" class="btn btn-light"><i class="bx bx-heart"></i>Add to Wishlist</a>
            </div> -->
            </div>

            <div class="card-body" id="panel_editar" style="display:none">

              <div class="row">
                <ul class="nav nav-tabs nav-danger" role="tablist">
                  <li class="nav-item" role="presentation">
                    <a class="nav-link active" data-bs-toggle="tab" href="#dangerhome" role="tab" aria-selected="true">
                      <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class="bx bx-package font-18 me-1"></i>
                        </div>
                        <div class="tab-title">Detalle Activo</div>
                      </div>
                    </a>
                  </li>
                  <li class="nav-item" role="presentation">
                    <a class="nav-link" data-bs-toggle="tab" href="#dangerprofile" role="tab" aria-selected="false" tabindex="-1">
                      <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class="bx bx-list-ul font-18 me-1"></i>
                        </div>
                        <div class="tab-title">Kit interno</div>
                      </div>
                    </a>
                  </li>
                  <li class="nav-item" role="presentation">
                    <a class="nav-link" data-bs-toggle="tab" href="#dangercontact" role="tab" aria-selected="false" tabindex="-1">
                      <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class="bx bx-cog font-18 me-1"></i>
                        </div>
                        <div class="tab-title">Detalle IT</div>
                      </div>
                    </a>
                  </li>                  
                </ul>
                <div class="tab-content py-3">
                  <div class="tab-pane fade show active" id="dangerhome" role="tabpanel">
                    <div class="row">
                       <div class="col-sm-3">
                        <label><input type="radio" id="txt_bajas" name="rbl_op"> Bajas</label>
                       </div>
                        <div class="col-sm-3">
                        <label><input type="radio" id="txt_patrimonial" name="rbl_op"> Patrimonial</label>
                       </div>
                        <div class="col-sm-3">
                        <label><input type="radio" id="txt_tercero" name="rbl_op"> Tercero</label>
                       </div>
                       <div class="col-sm-3">
                        <label><input type="radio" id="txt_ninguno" name="rbl_op" checked> Ninguno</label>
                       </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12" style="display:none;">
                            <b>Companycode</b><br>
                            <input type="text" class="form-control form-control-sm" name="" id="txt_company">
                        </div>  
                        <div class="col-sm-10">
                            <b><i class="text-danger">*</i>Descripcion</b><br>
                            <input type="text" class="form-control form-control-sm" name="" id="txt_description">
                        </div>
                        <div class="col-sm-2">
                           <b>SubNum </b><br>
                           <input type="text" class="form-control form-control-sm" name="" id="txt_subno">
                       </div>                       
                    </div>
                    <div class="row" style="display:none;">
                        <div class="col-sm-6">
                          <!-- <b>Custodio</b><br> -->
                          <select class="form-control form-control-sm" id="ddl_custodio">
                            <option>Seleccione Custodio</option>
                          </select>
                        </div>                                           
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                          <!-- <b>Custodio</b><br> -->
                          <select class="form-control form-control-sm" id="ddl_custodio" style="display:none;">
                            <option>Seleccione Custodio</option>
                          </select>                      
                           <b>Descripcion 2</b><br>
                           <input type="text" class="form-control form-control-sm" name="" id="txt_description2">
                        </div>
                        <div class="col-sm-6">
                          <div class="row">
                              <div class="col-sm-7"><i class="text-danger">*</i><b>Emplazamiento / Localiz.</b></div>
                              <div class="col-sm-5 text-end"><u><p class="mb-0"><small id="lbl_sap_loc"> SAP:</small></p></u></div>
                           </div>                            
                          <select class="form-control form-control-sm" id="ddl_localizacion">
                            <option>Seleccione Custodio</option>
                          </select>
                        </div>                       
                    </div>
                    <div class="row">
                      <div class="col-sm-4" style="display: none;">
                         <b>Assetsupno </b><br>
                         <input type="text" class="form-control form-control-sm" name="" id="txt_assetsupno">
                      </div>
                      <div class="col-sm-4">
                         <b><i class="text-danger">*</i>Asset </b><br>
                         <input type="text" class="form-control form-control-sm" name="" id="txt_asset" onkeyup="validar_campo()">
                        <div class="text-end">
                          <p>
                           <label><input type="radio" name="rbl_asset" onclick="validar_campo()" value="8" checked><small>Activo(8)</small></label>
                           <label><input type="radio" name="rbl_asset" onclick="validar_campo()" value="9"><small>Patrimonial(9)</small></label>
                           <label><input type="radio" name="rbl_asset" onclick="validar_campo()" value="0"><small>Ninguno</small></label>
                           </p>
                        </div>
                      </div>                          
                      <div class="col-sm-5">
                         <b>Tag RFID </b><br>
                         <input type="text" class="form-control form-control-sm" name="" id="txt_rfid" onkeyup="num_caracteres('txt_rfid',24)" onblur="num_caracteres('txt_rfid',24)">
                      </div>
                      <div class="col-sm-3">
                         <b><i class="text-danger">*</i>Tag antiguo </b><br>
                         <input type="text" class="form-control form-control-sm" name="" id="txt_tag_anti">
                      </div>                       
                    </div>
                    <div class="row">
                       <div class="col-sm-4">
                             <b>Clase de movimiento</b><br>
                             <div class="input-group">
                              <select class="form-select" id="ddl_clase_mov" onchange="autocmpletar_subfam()">
                                 <option value="">Selecciones</option>
                               </select>
                               <!-- <span class="input-group-append">
                                <button type="button" class="btn btn-primary btn-sm btn-flat" onclick="add_familia()" title="Nueva familia"><i class="fa fa-plus"></i></button>
                              </span> -->                               
                             </div>                             
                         </div>
                       <div class="col-sm-4">
                             <b>Familia</b><br>
                             <div class="input-group">
                              <select class="form-select" id="ddl_familia" onchange="autocmpletar_subfam()">
                                 <option value="">Selecciones</option>
                               </select>
                               <button type="button" class="btn btn-primary btn-sm btn-flat" onclick="add_familia()" title="Nueva familia"><i class="bx bx-plus"></i></button>                                                     
                             </div>                             
                         </div>
                          <div class="col-sm-4">
                             <b>Sub Familia</b><br>
                             <div class="input-group">
                                 <select class="form-select" id="ddl_subfamilia">
                                   <option value="">Selecciones</option>
                                 </select>                                
                                <button type="button" class="btn btn-primary btn-sm btn-flat" onclick="add_subfamilia()" title="Nueva sub familia"><i class="bx bx-plus"></i></button>                                                        
                             </div>                             
                         </div>                       
                     </div>
                     <div class="row">
                        <div class="col-sm-3">
                           <b>CompanyCode </b><br>
                           <input type="text" class="form-control form-control-sm" name="" id="txt_compa">
                         </div>  
                         <div class="col-sm-3">
                         <b>Modelo </b><br>
                         <input type="text" class="form-control form-control-sm" name="" id="txt_modelo">
                         </div>
                          <div class="col-sm-3">
                         <b>Serie </b><br>
                         <input type="text" class="form-control form-control-sm" name="" id="txt_serie">
                         </div>
                         <div class="col-sm-3">
                           <b>Fecha de Compra </b><br>
                           <input type="date" class="form-control form-control-sm" name="" id="txt_compra">
                         </div>                       
                     </div>
                     <div class="row">
                         <div class="col-sm-3">
                             <div class="row">
                                <div class="col-sm-6"><b><i class="text-danger">*</i>Marca</b></div>
                                <div class="col-sm-6 text-end"><u><p class="mb-0"><small id="lbl_sap_mar"> SAP:</small></p></u></div>
                             </div>
                             <select class="form-control form-control-sm" id="ddl_marca">
                               <option>Selecciones</option>
                             </select>
                         </div>
                         <div class="col-sm-3">
                            <div class="row">
                                <div class="col-sm-6"><b><i class="text-danger">*</i>Estado</b></div>
                                <div class="col-sm-6 text-end"><u><p class="mb-0"><small id="lbl_sap_est"> SAP:</small></p></u></div>
                             </div>
                            <select class="form-control form-control-sm input-sm" id="ddl_estado">
                              <option>Selecciones</option>
                            </select>
                         </div>
                         <div class="col-sm-3">
                            <div class="row">
                                <div class="col-sm-6"><b><i class="text-danger">*</i>Genero</b></div>
                                <div class="col-sm-6 text-end"><u><p class="mb-0"><small id="lbl_sap_gen"> SAP:</small></p></u></div>
                             </div>
                           <select class="form-control form-control-sm" id="ddl_genero">
                             <option>Selecciones</option>
                           </select>
                         </div>  
                         <div class="col-sm-3">
                            <div class="row">
                                <div class="col-sm-6"><b><i class="text-danger">*</i>Color</b></div>
                                <div class="col-sm-6 text-end"><u><p class="mb-0"><small id="lbl_sap_col"> SAP:</small></p></u></div>
                             </div>
                            <select class="form-control form-control-sm" id="ddl_color">
                              <option>seleccione</option>
                            </select>
                         </div>  
                     </div>
                     <div class="row">
                        <div class="col-sm-12">
                          <b>Caracteristica </b><br>
                          <input type="text" class="form-control form-control-sm" name="txt_carac" id="txt_carac">
                        </div>                                                     
                     </div>
                     <div class="row">
                        <div class="col-sm-3">
                           <b>Cantidad </b><br>
                           <input type="text" class="form-control form-control-sm" name="txt_cant" id="txt_cant">
                        </div>  
                        <div class="col-sm-3">
                          <b>Unidad medida  </b><br>
                          <input type="text" class="form-control form-control-sm" name="txt_unidad" id="txt_unidad">
                        </div>
                          <div class="col-sm-3">
                            <b>Act. fijo original </b><br>
                            <input type="text" class="form-control form-control-sm" name="txt_acti" id="txt_acti">
                          </div>
                          <div class="col-sm-3">
                            <b>Fecha de inventario </b><br>
                            <input type="date" class="form-control form-control-sm" name="" id="txt_fecha" readonly>
                          </div>                       
                     </div>
                     <div class="row">
                        <div class="col-sm-3">
                           <b>Valor actual </b><br>
                           <input type="text" class="form-control form-control-sm" name="" id="txt_valor">
                        </div>
                        <div class="col-sm-9">
                          <div class="row">
                              <div class="col-sm-6"><b><i class="text-danger">*</i>Proyecto</b></div>
                              <div class="col-sm-6 text-end"><u><p class="mb-0"><small id="lbl_sap_pro"> SAP:</small></p></u></div>
                          </div>
                            <select class="form-control form-control-sm" id="ddl_proyecto">
                              <option>seleccione</option>
                            </select>
                        </div>                                 
                     </div>
                     <div class="row">
                       <div class="col-sm-12">
                          <b>Observacion</b>
                          <textarea placeholder="observacion" style="width: 100%;height: 100px" id="txt_observacion"></textarea>
                        </div>              
                     </div>
                     <hr>
                     <div class="text-end">
                       <button class="btn btn-primary" onclick="guardar_articulo()" id="btn_editar"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Guardar</button>
                     </div>         
                    
                  </div>
                  <div class="tab-pane fade" id="dangerprofile" role="tabpanel">

                    <table class="table table-striped table-bordered dataTable">
                      <thead>
                        <th>Nombre</th>
                        <th>Identificador</th>
                        <th>Observacion</th>
                        <th></th>
                      </thead>
                      <tr>
                        <td><input type="text" class="form-control form-control-sm" name="txt_nombre_kit" id="txt_nombre_kit"></td>
                        <td><input type="text" class="form-control form-control-sm" name="txt_identificador_kit" id="txt_identificador_kit"></td>
                        <td><input type="text" class="form-control form-control-sm" name="txt_observacion_kit" id="txt_observacion_kit"></td>
                        <td><button class="btn btn-sm btn-primary" onclick="guardar_kit()">Añadir</button></td>                        
                      </tr>
                      <tbody id="tbl_kit">
                        
                      </tbody>                      
                    </table>
                  </div>
                  <div class="tab-pane fade" id="dangercontact" role="tabpanel">

                    <form id="form_detalle_it">
                    <div class="row">                 
                      <div class="col-sm-6">
                        <div class="row mb-3">
                          <label for="inputEnterYourName" class="col-sm-4 col-form-label">Sistema Op </label>
                          <div class="col-sm-8">
                            <input type="text" class="form-control form-control-sm" id="txt_sistema_op" name="txt_sistema_op" placeholder="Server 2016">
                          </div>
                        </div>
                        <div class="row mb-3">
                          <label for="inputEnterYourName" class="col-sm-4 col-form-label">Arquitectura</label>
                          <div class="col-sm-8">
                            <input type="text" class="form-control form-control-sm" id="txt_arquitectura"  name="txt_arquitectura" placeholder="64 bit / 32 bit">
                          </div>
                        </div>   
                        <div class="row mb-3">
                          <label for="inputEnterYourName" class="col-sm-3 col-form-label">Kernel</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control form-control-sm" id="txt_kernel"  name="txt_kernel" placeholder="10.0">
                          </div>
                        </div>   
                        <div class="row mb-3">
                          <label for="inputEnterYourName" class="col-sm-4 col-form-label">Producto ID</label>
                          <div class="col-sm-8">
                            <input type="text" class="form-control form-control-sm" id="txt_producto_id"  name="txt_producto_id" placeholder="0000-000-0000">
                          </div>
                        </div>                
                      </div>
                      <div class="col-sm-6">
                        <div class="row mb-3">
                          <label for="inputEnterYourName" class="col-sm-3 col-form-label">Version</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control form-control-sm" id="txt_version"  name="txt_version" placeholder="1.0">
                          </div>
                        </div>
                        <div class="row mb-3">
                          <label for="inputEnterYourName" class="col-sm-4 col-form-label">Service pack</label>
                          <div class="col-sm-8">
                            <input type="text" class="form-control form-control-sm" id="txt_service_pack"  name="txt_service_pack" placeholder="service pack1">
                          </div>
                        </div>   
                        <div class="row mb-3">
                          <label for="inputEnterYourName" class="col-sm-3 col-form-label">Edicion</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control form-control-sm" id="txt_edicion"  name="txt_edicion" placeholder="">
                          </div>
                        </div>
                        <div class="row mb-3">
                          <label for="inputEnterYourName" class="col-sm-4 col-form-label">Serie numero</label>
                          <div class="col-sm-8">
                            <input type="text" class="form-control form-control-sm" id="txt_serie_numbre"  name="txt_serie_numbre" placeholder="000-00000">
                          </div>
                        </div>                                
                      </div>
                       <div class="col-sm-12 text-end">
                        <button class="btn btn-primary btn-sm" type="button" onclick="guarda_detalles_it()">Guardar</button>
                      </div>
                    </div>
                    </form>  
                  </div>                 
                </div>
              </div>
  
              
              
                     
                                 
                     
                     
                     
                    
                    
                   
              </div>      
            <!--end row-->
            </div>
            </div>
          </div>
                    <hr/>
          <div class="card-body">
            <ul class="nav nav-tabs nav-primary mb-0" role="tablist">
              <li class="nav-item" role="presentation">
                <a class="nav-link active" data-bs-toggle="tab" href="#primaryhome" role="tab" aria-selected="true">
                  <div class="d-flex align-items-center">
                    <div class="tab-icon"><i class='bx bx-user font-18 me-1'></i>
                    </div>
                    <div class="tab-title"> Custodio </div>
                  </div>
                </a>
              </li>
              <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#primaryprofile" role="tab" aria-selected="false">
                  <div class="d-flex align-items-center">
                    <div class="tab-icon"><i class='bx bx-bookmark-alt font-18 me-1'></i>
                    </div>
                    <div class="tab-title">Movimientos</div>
                  </div>
                </a>
              </li>              
            </ul>
            <div class="tab-content pt-3">
              <div class="tab-pane fade show active" id="primaryhome" role="tabpanel">
                <div class="row">
                <div class="col-sm-6">
                  <input type="hidden" name="id" id="id" class="form-control form-control-sm">
                  Nombre <br>
                  <input type="input" name="txt_nombre" id="txt_nombre" class="form-control form-control-sm" style="border: 0px;" readonly>                  
                </div>
                <div class="col-sm-6">
                  CI <br>
                  <input type="input" name="txt_ci" id="txt_ci" class="form-control form-control-sm" style="border: 0px;" readonly>                  
                </div>
                <div class="col-sm-6">
                   Correo <br>
                  <input type="input" name="txt_email" id="txt_email" class="form-control form-control-sm" style="border: 0px;" readonly>                   
                </div>
                <div class="col-sm-6">
                    Puesto <br>
                  <input type="input" name="txt_puesto" id="txt_puesto" class="form-control form-control-sm" style="border: 0px;" readonly>
                </div> 
                <div class="col-sm-12">
                   Unidad ORG <br>
                  <input type="input" name="txt_unidad_p" id="txt_unidad_p" class="form-control form-control-sm" style="border: 0px;" readonly>              
                </div>                                   
               <div class="col-sm-12">
                <br>
                 <button class="btn btn-sm btn-primary" onclick="editar_custodio()">Editar custodio</button>
               </div>
              </div>
              </div>
              <div class="tab-pane fade" id="primaryprofile" role="tabpanel">
                  <h3>Movimiento por articulo</h3>
                  <div class="row">
                    <br>
                     <div class="col-sm-2">
                      <b>Desde</b>
                       <input type="date" name="txt_desde" id="txt_desde" class="form-control form-control-sm">
                    </div>
                     <div class="col-sm-2">
                      <b>Hasta</b>
                       <input type="date" name="txt_hasta" id="txt_hasta" class="form-control form-control-sm">
                     </div>
                     <div class="col-sm-8"><br>
                       <button class="btn btn-primary btn-sm" onclick="movimientos()"><i class="bx bx-search"></i> Buscar</button>                     
                       <button class="btn btn-default btn-sm" id="excel_movimientos_art"><i class="bx bx-file"></i> Informe</button>
                     </div>
                     <div class="table-responsive">
                      <table class="table table-striped table-sm">
                        <thead>
                          <th>Proceso realizado</th>
                          <th style="white-space: nowrap;">Fecha Mov</th>
                          <th style="white-space: nowrap;">Cod ante.</th>
                          <th style="white-space: nowrap;">Dato anter.</th>
                          <th style="white-space: nowrap;">Cod nuevo</th>
                          <th style="white-space: nowrap;">Dato nuevo</th>
                          <th>Responsable</th>  
                        </thead>
                        <tbody id="table_contenido">
                          <tr><td colspan="3">NO se a encontado movimientos de este articulo</td></tr>  
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

<div class="modal fade"  id="modal_familia" tabindex="-1" data-bs-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Nueva familia</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
       <div class="modal-body">
        <div class="row">
          <input type="" name="txt_new_familia" id="txt_new_familia" class="form-control form-control-sm">          
        </div>         
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btn_vin_cus" onclick="guardar_familia()">Guardar</button>       
      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="modal_familia" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Nueva familia</h3>
      </div>
      <div class="modal-body">
        <div class="row">
          <input type="" name="txt_new_familia" id="txt_new_familia" class="form-control form-control-sm">          
        </div>
         
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btn_vin_cus" onclick="guardar_familia()">Guardar</button>
       
      </div>
    </div>
  </div>
</div><!-- /.container-fluid -->


<div class="modal fade" id="modal_subfamilia" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Nueva Sub familia</h3>
      </div>
      <div class="modal-body">
        <div class="row">
          <input type="" name="txt_new_subfamilia" id="txt_new_subfamilia" class="form-control form-control-sm">                   
        </div>         
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btn_vin_cus" onclick="guardar_subfamilia()">Guardar</button>       
      </div>
    </div>
  </div>
</div><!-- /.container-fluid -->

 


<?php include('../../cabeceras/footer.php'); ?>
     