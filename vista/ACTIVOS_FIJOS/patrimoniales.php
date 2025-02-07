<?php /*include('../../cabeceras/header.php');*/ include dirname(__DIR__,2)."/lib/phpqrcode/qrlib.php";    $id = ''; if(isset($_GET['id'])){$id = $_GET['id'];} ?>
<script type="text/javascript">
    $( document ).ready(function() {      
  // navegacion();
     $("#subir_imagen").on('click', function() {
      var id = '<?php echo $id; ?>';
      if(id==''){ Swal.fire('No se pudo Subir la imagen','Asegurese de llenar primero el detalle','error')}
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


   $('#imprimir_qr').click(function(){      
     var id = $('#txt_id').val();
      var url='../lib/Reporte_pdf.php?codigo_qr=true&id='+id;
      window.open(url, '_blank');
  }); 





  });


 
</script>
<script type="text/javascript">	
  $( document ).ready(function() {
  	autocmpletar_l();
  	autocmpletar();
  	autocmpletar_color();
  	autocmpletar_marca();
  	autocmpletar_genero();
  	estado();
    autocmpletar_proyecto();
    autocmpletar_clase_mov();
    validar_datos();
    var art = '<?php echo $id;?>';
    if(art!='')
    {
    	cargar_tarjeta(art);
      cargar_tarjeta_view(art);
    }
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

 function estado()
  { 
    var id='';
    var estado = '<option value="">Seleccione Estado</option>';

    $.ajax({
      data:  {id:id},
      url:   '../../controlador/estadoC.php?lista=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {    
        // console.log(response);   
        $.each(response, function(i, item){
        	estado+="<option value='"+item.ID_ESTADO+"''>"+item.DESCRIPCION+"</option>";

          // console.log(item);
        });       
        $('#ddl_estado').html(estado);        
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
  		// alert('no a seleccionado ningun articulo');
  	}else
  	{

  		movimientos(id);
  		cargar_datos_view(id);
      cargar_datos(id);

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
        $('#ddl_clase_mov').append($('<option>',{value: response[0].CLASE_MOVIMIENTO, text: response[0].MOVIMIENTO,selected: true }));
        $('#ddl_estado').val(response[0].est);
        $('#txt_asset').val(response[0].tag_s);        
        $('#txt_subno').val(response[0].SUBNUMBER);
        $('#txt_assetsupno').val(response[0].ASSETSUPNO);
        $('#txt_rfid').val(response[0].rfid);
        $('#txt_tag_anti').val(response[0].ant);
        $('#txt_serie').val(response[0].SERIE);
        $('#txt_fecha').val(response[0].fecha);
        $('#txt_modelo').val(response[0].MODELO);
        $('#txt_id').val(response[0].id_A);        
        $('#txt_idA_img').val(response[0].id_A);
        $('#txt_id_A').val(response[0].id_AS);
        $('#txt_observacion').val(response[0].OBSERVACION);
      	$("#img_articulo").attr("src","../img/"+response[0].IMAGEN);
        $('#txt_nom_img').val(response[0].tag_s);
        $('#txt_cant').val(response[0].QUANTY);
        $('#txt_unidad').val(response[0].BASE_UOM);
        $('#txt_compra').val(response[0].ORIG_ACQ_YR);
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

        // $('#ddl_localizacion').val('55'); // Select the option with a value of '1'
        // console.log(response);   
      //    if($('#editar').val()==0 || $('#dba').val()==0)
      // {
      //   $('#btn_editar').hide();
      // }

        datos_col(response[0].id_cus);
              
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
          // console.log(response);
        $('#txt_company').val(response[0].COMPANYCODE);
        $('#lbl_descripcion_act').text(response[0].nom);
        $('#lbl_description').text(response[0].nom);
        $('#lbl_descricion2').text(response[0].des);
        $('#lbl_localizacion1').html('<b>Emplazamiento / Localizacion</b> | <label style="font-size:65%"> SAP:'+response[0].Cloc+'</label>');
        $('#lbl_localizacion').text(response[0].DENOMINACION);

        $('#lbl_custodio1').html('<b>Custodio:</b> | <label style="font-size:65%"> SAP:'+response[0].Ccus+'</label>');
        $('#lbl_custodio').text(response[0].PERSON_NOM);

        $('#lbl_marca').html(response[0].marca+' | <label style="font-size:65%"> SAP:'+response[0].Cmar+'</label>');     
        $('#lbl_color').html(response[0].color+' | <label style="font-size:65%"> SAP:'+response[0].Ccol+'</label>');
        $('#lbl_genero').html(response[0].genero+' | <label style="font-size:65%"> SAP:'+response[0].Cgen+'</label>');
        $('#lbl_proyecto').html(response[0].proyecto+' | <label style="font-size:65%"> SAP:'+response[0].Cpro+'</label>');
        $('#lbl_estado_act').html(response[0].estado+' | <label style="font-size:65%"> SAP:'+response[0].Cest+'</label>');


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
         $("#img_articulo").attr("src","../img/"+response[0].IMAGEN);
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
        datos_col(response[0].id_cus);
              
      }
    });
  }


function cargar_tarjeta(id)
{
	$.ajax({
      data:  {id:id},
      url:   '../../controlador/detalle_articuloC.php?cargar_tarjeta=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
        if(response!='')
        {

           $('#txt_id_info').val(response[0].ID_PATRIMONIAL);
        	 $('.textarea').html(response[0].HTML_INFO);
        	 $('#txt_id_tarjeta').val(response[0].ID_PATRIMONIAL);

           $('#txt_codigonacional').val(response[0].CODNACIONAL);

           var uni = response[0].UNIDADDOCUMENTAL;
           if(uni=='H')
           {
            $('#rbl_H').prop('checked',true);
           }else if(uni=='A')
           {
            $('#rbl_A').prop('checked',true);
           }else if(uni=='G')
           {
            $('#rbl_G').prop('checked',true);
           }        
             

           $('#txt_autor').val(response[0].AUTOR); 
           $('#txt_pais').val(response[0].PAIS); 
           $('#txt_siglo').val(response[0].SIGLO); 
           $('#txt_fecha').val(response[0].FECHA.date); 
           $('#txt_propietario').val(response[0].PROPIETARIO); 
           $('#txt_dni').val(response[0].NDI); 
           $('#txt_telefono').val(response[0].TELEFONO); 
           $('#txt_correo').val(response[0].EMAIL);  // => casa@cultura.com
           $('#txt_municipio').val(response[0].MUNICIPIO);  // => pichincha
           $('#txt_distrito').val(response[0].DISTRITO);  // => quito
           $('#txt_departamento').val(response[0].DEPARTAMENTO);  // => QUITO
           $('#txt_direccion').val(response[0].DIRECCION);  
           $('#txt_descripcion').val(response[0].DESCRIPCION); 
           $('#ddl_unidad_conservacion').val(response[0].CONSERVACION); 
           $('#txt_unidades').val(response[0].UNIDADES);  // => 1
           $('#txt_largo').val(response[0].LARGO);  // => 3CM
           $('#txt_ancho').val(response[0].ANCHO);  // => 25
           $('#txt_grosor').val(response[0].GROSOR);  // => 1CM
           $('#txt_metro_lineal').val(response[0].METROSLINEALES);  // => 
           $('#txt_escala').val(response[0].ESCALA);  // => 
           var inte = response[0].INTEGRIDAD
           if(inte=='C')
           {
            $('#rbl_completo').prop('checked',true);
           }else if(inte=='I')
           {
            $('#rbl_incompleto').prop('checked',true);
           }else if(inte=='F')
           {
            $('#rbl_fragmentado').prop('checked',true);
           }else if(inte=='U')
           {
            $('#rbl_unido').prop('checked',true);
           }else if(inte=='A')
           {
            $('#rbl_agregado').prop('checked',true);
           }else if(inte=='D')
           {
            $('#rbl_descosido').prop('checked',true);
           }else 
           {
            // $('#rbl_regular').prop('checked',true);
           }

           let estado = response[0].ESTADO  // => R
           let start = '';
           if(estado=='B')
           {
            start='<i class="bx bxs-star text-warning"></i>'+
                '<i class="bx bxs-star text-warning"></i>'+
                '<i class="bx bxs-star text-warning"></i>'+
                '<i class="bx bxs-star text-warning"></i>'+
                '<i class="bx bxs-star text-warning"></i>'+
                '<i class=""> (Bueno)</i>';
           }else if(estado=='R')
           {
            start='<i class="bx bxs-star text-warning"></i>'+
                '<i class="bx bxs-star text-warning"></i>'+
                '<i class="bx bxs-star text-secondary"></i>'+
                '<i class="bx bxs-star text-secondary"></i>'+
                '<i class="bx bxs-star text-secondary"></i>'+
                '<i class=""> (Regular)</i>';
           }else
           {
             start='<i class="bx bxs-star text-warning"></i>'+
                '<i class="bx bxs-star text-secondary"></i>'+
                '<i class="bx bxs-star text-secondary"></i>'+
                '<i class="bx bxs-star text-secondary"></i>'+
                '<i class="bx bxs-star text-secondary"></i>'+
                '<i class=""> (Malo)</i>';
           }

          $('#lbl_estado').html(start)

           $('#txt_observacion_info').val(response[0].OBSERVACION);  // 
           $('#txt_valoracion').val(response[0].VALORACION);  // => ASDASDAS
        	 console.log(response);

        }   

        }
    })
	
}


function cargar_tarjeta_view(id)
{
  $.ajax({
      data:  {id:id},
      url:   '../../controlador/detalle_articuloC.php?cargar_tarjeta=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
        if(response!='')
        {

           $('#txt_id_info').val(response[0].ID_PATRIMONIAL);
           $('.textarea').html(response[0].HTML_INFO);
           $('#txt_id_tarjeta').val(response[0].ID_PATRIMONIAL);

           $('#lbl_codigonacional').text(response[0].CODNACIONAL);

           var uni = response[0].UNIDADDOCUMENTAL;
            if(uni=='H')
           {
            $('#lbl_H').text('Documento histórico');
           }else if(uni=='A')
           {
            $('#lbl_H').text('Archivo administrativo');
           }else if(uni=='G')
           {
            $('#lbl_H').text('Gráfico o Cartográfico');
           }
           $('#txt_autor').val(response[0].AUTOR); 
           $('#lbl_pais').text(response[0].PAIS); 
           $('#lbl_siglo').text(response[0].SIGLO); 
           $('#lbl_fecha').text(response[0].FECHA.date); 
           $('#lbl_propietario').text(response[0].PROPIETARIO); 
           $('#lbl_dni').text(response[0].NDI); 
           $('#lbl_telefono').text(response[0].TELEFONO); 
           $('#lbl_correo').text(response[0].EMAIL);  // => casa@cultura.com
           $('#lbl_municipio').text(response[0].MUNICIPIO);  // => pichincha
           $('#lbl_distrito').text(response[0].DISTRITO);  // => quito
           $('#lbl_departamento').text(response[0].DEPARTAMENTO);  // => QUITO
           $('#lbl_direccion').text(response[0].DIRECCION);  
           $('#txt_descripcion').val(response[0].DESCRIPCION); 
           $('#ddl_unidad_conservacion').val(response[0].CONSERVACION); 
           detalle_cons = $('#ddl_unidad_conservacion option:selected').val(); 

           $('#lbl_conservacion').text(detalle_cons);

           $('#lbl_unidades').text(response[0].UNIDADES);  // => 1
           $('#lbl_dimensiones').text(response[0].LARGO+'x'+response[0].ANCHO+'x'+response[0].GROSOR);
           $('#lbl_metro_lineal').text(response[0].METROSLINEALES);  // => 
           $('#lbl_escala').text(response[0].ESCALA);  // => 
           var inte = response[0].INTEGRIDAD

           if(inte=='C')
           {
              integridad = 'Completo';
           }else if(inte=='I')
           {
              integridad = 'Incompleto';
           }else if(inte=='F')
           {            
              integridad = 'Fragmentado';
           }else if(inte=='U')
           {
              integridad = 'Unido';
           }else if(inte=='A')
           {
              integridad = 'Agregado';
           }else if(inte=='D')
           {
              integridad = 'Descosido';
           }

           $('#lbl_integridad').text(integridad);

           let estado = response[0].ESTADO  // => R
           if(estado=='B')
           {
            $('#rbl_bueno').prop('checked',true);
           }else if(estado=='R')
           {
            $('#rbl_regular').prop('checked',true);
           }else
           {
            $('#rbl_malo').prop('checked',true);
           }
           $('#lbl_observacion_info').html('<b>Observaciones:</b> '+response[0].OBSERVACION);  // 
           $('#lbl_valoracion').html('<b>Valoración y Significado Cultural del Bien:</b> '+response[0].VALORACION);  // => ASDASDAS
           console.log(response);

        }   

        }
    })
  
}


  function movimientos(id)
  {
  	var table = '';
    $.ajax({
      data:  {id:id},
      url:   '../../controlador/detalle_articuloC.php?movimientos=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {   
        $.each(response,function(i,item){
        	// console.log(item);
        	table+="<tr><td>"+item.ob+"</td><td>"+item.fe+"</td><td>"+item.responsable+"</td></tr>"
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
        'bajas':'false',
        'terceros':'false',
        'patrimoniales':'true', 
        'clase_mov':$('#ddl_clase_mov').val(),
        'movimiento':$('#ddl_clase_mov option:selected').text(),
    };
    console.log(parametros);
    var id =$('#txt_id').val();
    $.ajax({
      data:  {parametros:parametros},
      url:   '../../controlador/detalle_articuloC.php?guardarArticulo_patrimonial=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
        if(response>0)
        {
          Swal.fire(
            '',
            'Operacion realizada con exito.',
            'success'
          ).then(function(){
            location.href = 'patrimoniales.php?id='+response+'&fil1=&fil2=';
          })
        }else if(response==-2)
        {
          Swal.fire(
            '',
            'Asset ya registrado.',
            'error'
          )

        }
        else if(response==-3)
        {
          Swal.fire(
            '',
            'Tag antiguo ya registrado.',
            'error'
          )

        }
        else
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

          botones='<a class="btn btn-default" href="../vista/detalle_articulo.php?id='+response.atras+'&fil1=<?php echo $_GET['fil1']?>&fil2=<?php echo $_GET['fil2']?>"><i class="fa fa-caret-left"></i> Atras</a><a class="btn btn-default" href="../vista/detalle_articulo.php?id='+response.siguiente+'&fil1=<?php echo $_GET['fil1']?>&fil2=<?php echo $_GET['fil2']?>">Siguiente <i class="fa fa-caret-right"></i></a>';
          }else
          {
            botones='<a class="btn btn-default" href="../vista/detalle_articulo.php?id='+response.siguiente+'&fil1=<?php echo $_GET['fil1']?>&fil2=<?php echo $_GET['fil2']?>">Siguiente <i class="fa fa-caret-right"></i></a>';
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

  function guardar_info()
  {
    var parametros = $('#form_informativo').serialize(); 
    $.ajax({
        data:  parametros,
        url:   '../../controlador/detalle_articuloC.php?add_info=true',
        type:  'post',
        dataType: 'json',
          success:  function (response) {
          if(response==1)
          {
            Swal.fire('Informacion guardada','','success');
            var art = '<?php echo $id;?>';
            if(art!='')
            {
              cargar_tarjeta(art);              
              cargar_tarjeta_view(art);
            }

          }   

          }
      })
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

  function cambiar_editar1()
  {
    $('#panel_editar_1').css('display','contents');
    $('#panel_vista_1').css('display','none');
  }

  function cambio_vista1()
  {
    $('#panel_editar_1').css('display','none');
    $('#panel_vista_1').css('display','contents');
  }

  function cambiar_editar2()
  {
    $('#panel_editar_2').css('display','contents');
    $('#panel_vista_2').css('display','none');
  }

  function cambio_vista2()
  {
    $('#panel_editar_2').css('display','none');
    $('#panel_vista_2').css('display','contents');
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
                <li class="breadcrumb-item active" aria-current="page">Patrimoniales detalle</li>
              </ol>
            </nav>
          </div>          
        </div>
        <!--end breadcrumb-->

         <div class="card">
         <div class="card-body">
          <div class="row row-cols-auto g-1">                  
            <div class="col">
              <?php if($_GET['fil1']=='null--null' && $_GET['fil1']=='null--null'){   ?>
              <a class="btn btn-outline-secondary btn-sm"  href="inicio.php?mod=<?php echo $_SESSION['INICIO']['MODULO_SISTEMA']; ?>&acc=lista_patrimoniales"><i class="bx bx-arrow-back"></i>  Regresar</a>
            <?php }else{ ?>
              <a class="btn btn-outline-secondary btn-sm" href="inicio.php?mod=<?php echo $_SESSION['INICIO']['MODULO_SISTEMA']; ?>&acc=lista_patrimoniales&fil1=<?php echo $_GET['fil1']?>&fil2=<?php echo $_GET['fil2']?>"> Regresar</a><div id="na">                    
              </div>
            <?php } ?>
            </div>
          </div> 
          <hr>
         </div>         

          <div class="row g-0">
            <div class="col-sm-3 text-center border-end">
              <img src="../img/sin_imagen.jpg" class="img-fluid rounded" id="img_articulo2"style="width: 75%"> 
              <form enctype="multipart/form-data" id="form_img" method="post">
                <div class="custom-file">
                  <input type="file" class="form-control form-control-sm form-control form-control-sm-sm" id="file_img" name="file_img">
                  <input type="hidden" name="txt_nom_img" id="txt_nom_img">
                  <input type="hidden" name="txt_idA_img" id="txt_idA_img">
                </div>
                <button type="button" class="btn btn-primary btn-sm" style="width: 100%" id="subir_imagen"> Subir Imagen</button>
              </form> 
              <hr>   

              <?php
                if(isset($_GET['id']))
                {
                $PNG_TEMP_DIR = '../TEMP/';
                if (!file_exists($PNG_TEMP_DIR))
                {
                    mkdir($PNG_TEMP_DIR);
                }
                $matrixPointSize = 5;
                $errorCorrectionLevel = 'M';

                $url = str_replace('patrimoniales.php','detalle_patrimonial.php', $_SERVER['REQUEST_URI']);

                $filename = $PNG_TEMP_DIR.'QRCODE_'.$_GET['id'].'.png';
                QRcode::png($_SERVER['HTTP_HOST'].$url, $filename, $errorCorrectionLevel, $matrixPointSize, 2); 

                  echo '<img  id="qr_'.$_GET['id'].'" name="qr_'.$_GET['id'].'" src="'.$PNG_TEMP_DIR.basename($filename).'" />
                  <button type="button" class="btn btn-primary btn-sm" style="width: 100%" id="imprimir_qr"> Imprimir QR</button><hr>';
                }  
              ?>
              
            </div>          
            <div class="col-md-9">
            <div class="card-body">
              <h4 class="card-title" id="lbl_description"></h4>

              <div class="card">
              <div class="card-body" style="">
                <ul class="nav nav-tabs nav-danger" role="tablist">
                  <li class="nav-item" role="presentation">
                    <a class="nav-link active" data-bs-toggle="tab" href="#dangerhome" role="tab" aria-selected="true">
                      <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class="bx bx-arch font-18 me-1"></i>
                        </div>
                        <div class="tab-title">Detalle de patrimonial</div>
                      </div>
                    </a>
                  </li>
                  <li class="nav-item" role="presentation">
                    <a class="nav-link" data-bs-toggle="tab" href="#dangerprofile" role="tab" aria-selected="false" tabindex="-1">
                      <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class="bx bx-box font-18 me-1"></i>
                        </div>
                        <div class="tab-title">Detalle de activo</div>
                      </div>
                    </a>
                  </li>
                  <li class="nav-item" role="presentation">
                    <a class="nav-link" data-bs-toggle="tab" href="#dangercontact" role="tab" aria-selected="false" tabindex="-1">
                      <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class="bx bx-pen font-18 me-1"></i>
                        </div>
                        <div class="tab-title">Resumen</div>
                      </div>
                    </a>
                  </li>
                </ul>
                <div class="tab-content py-1">
                  <div class="tab-pane fade show active" id="dangerhome" role="tabpanel">
                    <div id="panel_vista_1">
                      <div class="text-end">
                        <button class="btn btn-sm btn-primary" id="btn_editar1" onclick="cambiar_editar1()"><i class="bx bx-pencil"></i></button> 
                      </div>
                        <div class="d-flex gap-3 py-0">
                          <b>Estado:</b>
                          <div class="cursor-pointer" id="lbl_estado">
                            <i class='bx bxs-star text-secondary'></i>
                            <i class='bx bxs-star text-secondary'></i>
                            <i class='bx bxs-star text-secondary'></i>
                            <i class='bx bxs-star text-secondary'></i>
                            <i class='bx bxs-star text-secondary'></i>
                          </div>  
                          <div><b>Codigo Nacional:</b> <i class="bx bx-package" id="lbl_codigonacional"></i></div>
                        </div>
                         <div class="d-flex gap-3 py-2">                      
                          <div><b>Grupo:</b><i class="bx bx-package" id="lbl_H"></i></div>
                          <div><b>Unidad de conservacion: </b><i class="bx bx-git-pull-request" id="lbl_conservacion"></i></div>
                          <div class="text-success"><b>integridad:</b><i class='bx bxs-tone align-middle' id="lbl_integridad"></i></div>
                        </div>                 
                 
                        <dl class="row">
                          <dd class="col-sm-4">
                            <span class="price h4" id="lbl_unidades">1</span> 
                            <span class="text-muted">/Unidades</span> 
                          </dd>
                          <dd class="col-sm-4">
                            <span class="price h4" id="lbl_dimensiones">1x3x3x3</span> 
                            <span class="text-muted">/Dimensiones <br><p> <small>(largo x ancho x grosor)</small></p></span> 
                          </dd>
                          <dd class="col-sm-4">
                            <span class="price h4" id="lbl_metro_lineal">1</span> 
                            <span class="text-muted">/metros lineales</span> 
                          </dd>
                          <dd class="col-sm-4">
                            <span class="price h4" id="lbl_escala">1</span> 
                            <span class="text-muted">/Escala</span> 
                          </dd>
                        </dl>


                      <p class="card-text fs-6" id="lbl_valoracion"></p>
                      <dl class="row">
                        <dt class="col-sm-3">Pais de origen</dt>
                        <dd class="col-sm-3" id="lbl_pais"></dd>

                        <dt class="col-sm-3">Municipio</dt>
                        <dd class="col-sm-3" id="lbl_municipio"></dd>

                        <dt class="col-sm-3">Distrito</dt>
                        <dd class="col-sm-3" id="lbl_distrito"></dd>

                        <dt class="col-sm-3">Departamento</dt>
                        <dd class="col-sm-3" id="lbl_departamento"></dd>
                        
                        <dt class="col-sm-3">siglos</dt>
                        <dd class="col-sm-3" id="lbl_siglo"></dd>
                        
                        <dt class="col-sm-3">Fecha</dt>
                        <dd class="col-sm-3" id="lbl_fecha"></dd>
                      </dl>              
                      <p class="card-text fs-6" id="lbl_observacion_info"></p>
                      <hr>
                      <div class="row row-cols-auto align-items-center mt-3">
                        <div class="col-sm-8">
                          <label class="form-label"><b>Propietario</b></label>
                          <p id="lbl_propietario"></p>                
                        </div>
                        <div class="col-sm-4">
                          <label class="form-label"><b>N.documento de iden.</b></label>  
                          <p id="lbl_dni"></p>                              
                        </div>
                        <div class="col-sm-6">
                          <label class="form-label"><b>Teléfono</b></label> 
                          <p id="lbl_telefono"></p>                               
                        </div>
                         <div class="col-sm-6">
                          <label class="form-label"><b>Correo electrónico</b></label> 
                          <p id="lbl_correo"></p>                               
                        </div>
                        <div class="col-sm-12">
                          <label class="form-label"><b>Direccion</b></label> 
                          <p id="lbl_direccion"></p>                               
                        </div>
                      </div>
                    </div>
                    <div id="panel_editar_1"  style="display:none;">
                      <div class="row">
                        <div class="col-sm-12 text-end">
                          <button type="button" class="btn btn-primary btn-sm" onclick="guardar_info()"><i class="bx bx-save"></i>Guardar</button>
                           <button class="btn btn-sm btn-primary" id="btn_vista1" onclick="cambio_vista1()"><i class="bx bx-window-alt"></i></button>
                        </div>
                      </div>
                      <form id="form_informativo">
                      <div class="row">
                        <div class="col-sm-3">
                          <i class="bx bx-info-circle" title="Es el código de inventario asignado por cualquier ente regulador como banco central, casa de la cultura o ministerio de cultura y patrimonio" data-toggle="tooltip"></i>
                          <b> Codigo Nacional</b>                  
                          <input type="hidden" name="txt_id_info" id="txt_id_info" class="form-control form-control-sm">
                          <input type="hidden" name="txt_id" id="txt_id" class="form-control form-control-sm" value="<?php echo $id;?>">
                          <input type="" name="txt_codigonacional" id="txt_codigonacional" class="form-control form-control-sm">
                        </div>
                         <div class="col-sm-9">
                          <b>Grupo</b><br>
                          <label><input type="radio" class="form-check-input" name="rbl_grupo" id="rbl_H" value="H" checked>Documento histórico</label>
                          <label><input type="radio" class="form-check-input" name="rbl_grupo" id="rbl_A" value="A">Archivo administrativo</label>
                          <label><input type="radio" class="form-check-input" name="rbl_grupo" id="rbl_G" value="G">Gráfico o Cartográfico</label>
                        </div>   
                       <!--  <div class="col-sm-3">
                          <b>Sub Grupo</b>
                          <select class="form-control form-control-sm" id="txt_grupo" name="txt_grupo">
                            <option value="">Seleccione</option>
                            <option value="">como acta</option>
                            <option value="">oficio</option>
                            <option value="">acuerdo</option>
                            <option value="">carta</option>
                            <option value="">expediente</option>
                            <option value="">proceso</option>
                            <option value="">proyecto</option>
                          </select>
                        </div>          -->                                
                           
                      </div>
                      <div class="row">     
                        <div class="col-sm-4">
                          <b>Autor</b>
                          <input type="" name="txt_autor" id="txt_autor" class="form-control form-control-sm">                  
                        </div>      
                         <div class="col-sm-3">
                          <b>Pais de origen</b> 
                          <input type="" name="txt_pais" id="txt_pais" class="form-control form-control-sm">                  
                        </div> 
                        <div class="col-sm-2">
                          <b>siglos </b>
                          <input type="" name="txt_siglo" id="txt_siglo" class="form-control form-control-sm" placeholder="XX">    
                        </div>
                        <div class="col-sm-3">
                          <b>Fecha</b> 
                          <input type="date" name="txt_fecha" id="txt_fecha" class="form-control form-control-sm">                  
                        </div> 
                      </div>
                      <div class="row">                        
                        <div class="col-sm-4">
                          <b>Municipio</b>
                          <input type="" name="txt_municipio" id="txt_municipio" class="form-control form-control-sm">                 
                        </div>  
                        <div class="col-sm-4">
                          <b>Distrito</b>
                          <input type="" name="txt_distrito" id="txt_distrito" class="form-control form-control-sm">                  
                        </div>  
                        <div class="col-sm-4">
                          <b>Departamento</b>
                          <input type="" name="txt_departamento" id="txt_departamento" class="form-control form-control-sm">
                        </div> 
                      </div>
                      <div class="row">                         
                        <div class="col-sm-7">
                         <b> Descripcion </b>                
                          <input type="" name="txt_descripcion" id="txt_descripcion" class="form-control form-control-sm">    
                        </div>
                        <div class="col-sm-5">
                          <b>Unidad de conservacion</b>
                          <div class="input-group">
                           <select class="form-control form-control-sm" id="ddl_unidad_conservacion" name="ddl_unidad_conservacion">
                            <option value="">Seleccione</option>
                            <option value="caja">CAJA</option>
                            <option value="tomo">TOMO</option>
                            <option value="carp">CARPETA</option>
                            <option value="lega">LEGADO</option>
                          </select>
                            <button class="btn btn-primary btn-sm"><i class="bx bx-plus"></i></button>                    
                          </div>         
                        </div>
                        <div class="col-sm-12">
                          <!-- trabaja con tipo de documento  -->
                           <b><i class="fa fa-info-circle" title="Si se trata de piezas individuales se discriminan las medidas (alto, ancho, grosor) y la unidad. Si son unidades de conservación se contabilizan los metros lineales que ocupan en el estante. Si se trata de documentos individuales manuscritos consignar las medidas (alto, ancho) y la unidad. Cuando se trate de material cartográfi co se debe registrar la escala"></i> Dimensiones</b>
                           <div class="row">
                            <div class="col-sm-2">
                              Unidades
                              <input type="" name="txt_unidades" id="txt_unidades" class="form-control form-control-sm">  
                             </div>
                             <div class="col-sm-2">
                              largo
                              <input type="" name="txt_largo" id="txt_largo" class="form-control form-control-sm">  
                             </div>
                             <div class="col-sm-2">
                              Ancho
                              <input type="" name="txt_ancho" id="txt_ancho" class="form-control form-control-sm">                       
                             </div>
                             <div class="col-sm-2">
                              groso
                              <input type="" name="txt_grosor" id="txt_grosor" class="form-control form-control-sm">                       
                             </div>
                             <div class="col-sm-2">
                              metro lineal
                              <input type="" name="txt_metro_lineal" id="txt_metro_lineal" class="form-control form-control-sm">                       
                             </div>
                             <div class="col-sm-2">
                              Escala
                              <input type="" name="txt_escala" id="txt_escala" class="form-control form-control-sm">
                             </div>
                           </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-5">                  
                        <b>Estado de integridad</b> <br>
                          <div class="row">
                            <div class="col-sm-6 col-md-6">
                              <label title="El bien tiene todas sus partes originales."><input type="radio" name="rbl_integridad" id="rbl_completo" value="C" checked>Completo</label><br>
                              <label title="Alguna de las partes y/o elementos originales no existe."><input type="radio" name="rbl_integridad" id="rbl_incompleto" value="I">Incompleto</label><br>
                              <label title="El bien se encuentra roto en dos o más pedazos."><input type="radio" name="rbl_integridad" id="rbl_fragmentado" value="F">Fragmentado</label>                      
                            </div>
                            <div class="col-sm-6 col-md-6">
                              <label class="form-check-label" title="El bien ha sido reconstruido con sus partes originales."><input type="radio" name="rbl_integridad" id="rbl_unido" value="U">Unido</label> <br>
                              <label class="form-check-label" title="Al bien le han sido colocados elementos y/o materiales no originales."><input type="radio" name="rbl_integridad" id="rbl_agregado" value="A" class="">Agregado</label><br>
                              <label class="form-check-label" title="Los hilos que unen las hojas o folios se han reventado o han desaparecido"><input type="radio" name="rbl_integridad" id="rbl_descosido" value="D">Descosido</label>
                            </div>                    
                          </div>                 
                        </div>
                        <div class="col-sm-2">
                          <b>Conservacion</b> <br>
                          <label class="form-check-label" title="Los materiales y los elementos que conforman o hacen parte del objeto, se encuentran en buen estado."><input type="radio" name="rbl_estado" id="rbl_bueno" value="B" checked>Bueno</label><br>
                          <label class="form-check-label" title="Se observan indicios de deterioro."><input type="radio" name="rbl_estado" id="rbl_regular" value="R">Regular</label><br>
                          <label class="form-check-label" title="Los materiales y/o elementos están bastante deteriorados"><input type="radio" name="rbl_estado" id="rbl_malo" value="M">Malo</label>
                          
                        </div>
                         <div class="col-sm-5">
                          <b>Observaciones</b> 
                          <textarea class="form-control-sm form-control" style="resize:none;" rows="3" name="txt_observacion_info" id="txt_observacion_info" ></textarea>
                        </div>
                      </div>               
                      <div class="row">    
                        <div class="col-sm-12">
                          <b>Valoración y Significado Cultural del Bien</b> 
                              <textarea class="form-control-sm form-control" style="resize:none;" rows="3" name="txt_valoracion" id="txt_valoracion" ></textarea>
                        </div>
                      </div>
                        <hr>
                      <div class="row">
                        <div class="col-sm-4">
                          <b>Propietario</b> 
                          <input type="" name="txt_propietario" id="txt_propietario" class="form-control form-control-sm">
                        </div>
                        <div class="col-sm-3">
                          <b>N.documento de iden.</b>
                          <input type="" name="txt_dni" id="txt_dni" class="form-control form-control-sm">                  
                        </div>  
                        <div class="col-sm-2">
                          <b>Teléfono</b>
                          <input type="" name="txt_telefono" id="txt_telefono" class="form-control form-control-sm">                  
                        </div>                                    
                        <div class="col-sm-3">
                          <b>Correo electrónico</b>
                          <input type="" name="txt_correo" id="txt_correo" class="form-control form-control-sm">                  
                        </div> 
                        <div class="col-sm-6">
                          <b>Dirección </b>
                          <input type="" name="txt_direccion" id="txt_direccion" class="form-control form-control-sm">                  
                        </div>  
                        
                      </div>
                      </form>

                    </div>
                </div>
                  <div class="tab-pane fade" id="dangerprofile" role="tabpanel">
                    <div class="row" id="panel_vista_2">
                      <div class="text-end">
                          <button class="btn btn-sm btn-primary" onclick="cambiar_editar2()"><i class="bx bx-pencil"></i></button> 
                      </div>
                      <h4 class="card-title" id="lbl_descripcion_act"></h4>
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
                      <dd class="col-sm-4" id="lbl_estado_act"></dd>

                      <dt class="col-sm-2">Model</dt>
                      <dd class="col-sm-4" id="lbl_modelo"></dd>

                      <dt class="col-sm-2">Serie</dt>
                      <dd class="col-sm-4" id="lbl_serie"></dd>

                      <dt class="col-sm-2">Proyecto</dt>
                      <dd class="col-sm-9" id="lbl_proyecto"></dd>

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
                    </div>                   
                    </div>
                    <div id="panel_editar_2" style="display:none;">
                      <div class="text-end">
                        <button type="button" class="btn btn-primary btn-sm" onclick="guardar_articulo()" id="btn_editar"><i class="bx bx-save"></i> Guardar</button>
                         <button class="btn btn-sm btn-primary" onclick="cambio_vista2()"><i class="bx bx-window-alt"></i></button>
                      </div>
                      <div class="row">                  
                          <div class="col-sm-9">              
                             <input type="hidden" name="" id="txt_id">
                             <input type="hidden" name="" id="txt_id_A">
                             <div class="row" style="display:none;">
                               <div class="col-sm-3">
                                <label><input type="radio" id="txt_bajas" name="rbl_op"> Bajas</label>
                               </div>
                                <div class="col-sm-3">
                                <label><input type="radio" id="txt_patrimonial" name="rbl_op" checked> Patrimonial</label>
                               </div>
                                <div class="col-sm-3">
                                <label><input type="radio" id="txt_tercero" name="rbl_op"> Tercero</label>
                               </div>
                               <div class="col-sm-3">
                                <label><input type="radio" id="txt_ninguno" name="rbl_op" > Ninguno</label>
                               </div>
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
                           <div class="col-sm-6">
                               <!--  <b>Custodio</b><br>
                                <select class="form-control form-control-sm" id="ddl_custodio">
                                  <option>Seleccione Custodio</option>
                                </select> -->
                                
                                 <b>Descripcion 2</b><br>
                                 <input type="text" class="form-control form-control-sm" name="" id="txt_description2">
                              </div>
                              <div class="col-sm-6">
                                   <b>Clase de movimiento</b><br>
                                   <div class="input-group">
                                    <select class="form-select" id="ddl_clase_mov">
                                       <option value="">Selecciones</option>
                                     </select>
                                     <!-- <span class="input-group-append">
                                      <button type="button" class="btn btn-primary btn-sm btn-flat" onclick="add_familia()" title="Nueva familia"><i class="fa fa-plus"></i></button>
                                    </span> -->                               
                                   </div>                             
                               </div>          
                          </div>              
                          <div class="row">                          
                                <div class="col-sm-6">
                                  <div class="row">
                                    <div class="col-sm-7"><i class="text-danger">*</i><b>Emplazamiento / Localiz.</b></div>
                                    <div class="col-sm-5 text-end"><u><p class="mb-0"><small id="lbl_sap_loc"> SAP:</small></p></u></div>
                                 </div>  
                                  <select class="form-control form-control-sm" id="ddl_localizacion">
                                    <option>Seleccione Custodio</option>
                                  </select>
                                </div>
                                <div class="col-sm-6">
                                  <b><i class="text-danger">*</i>Custodio</b><br>
                                  <select class="form-control form-control-sm" id="ddl_custodio">
                                    <option>Seleccione Custodio</option>
                                  </select>
                                </div>                             
                           </div>
                           <div class="row">
                             <div class="col-sm-4" style="display: none;">
                               <b>assetsupno </b><br>
                               <input type="text" class="form-control form-control-sm" name="" id="txt_assetsupno">
                             </div>
                             <div class="col-sm-5">
                               <b><i class="text-danger">*</i>Asset </b><br>
                               <input type="text" class="form-control form-control-sm" name="" id="txt_asset">
                               <div>
                                <p>
                                 <label><input type="radio" name="rbl_asset" onclick="validar_campo()" value="8" checked><small>Activo(8)</small></label>
                                 <label><input type="radio" name="rbl_asset" onclick="validar_campo()" value="9"><small>Patrimonial(9)</small></label>
                                 <label><input type="radio" name="rbl_asset" onclick="validar_campo()" value="0"><small>Ninguno</small></label>
                                 </p>
                               </div>
                             </div>
                             <div class="col-sm-4">
                               <b>Tag RFID </b><br>
                               <input type="text" class="form-control form-control-sm" name="" id="txt_rfid">
                             </div>
                             <div class="col-sm-3">
                               <b><i class="text-danger">*</i>Tag antiguo </b><br>
                               <input type="text" class="form-control form-control-sm" name="" id="txt_tag_anti">
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
                               <input type="text" class="form-control form-control-sm" name="" id="txt_carac">
                               </div>                                                     
                           </div>
                           <div class="row">
                            <div class="col-sm-3">
                                 <b>Cantidad </b><br>
                                 <input type="text" class="form-control form-control-sm" name="" id="txt_cant">
                               </div>  
                               <div class="col-sm-3">
                               <b>Unidad medida  </b><br>
                               <input type="text" class="form-control form-control-sm" name="" id="txt_unidad">
                               </div>
                                <div class="col-sm-3">
                               <b>Act. fijo original </b><br>
                               <input type="text" class="form-control form-control-sm" name="" id="txt_acti">
                               </div>
                               <div class="col-sm-3">
                                 <b>Fecha de inventario </b><br>
                                 <input type="date" class="form-control form-control-sm" name="" id="txt_fecha">
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
                      </div>
                    </div>
                  </div>
                  <div class="tab-pane fade" id="dangercontact" role="tabpanel">
                   <div class="row">
                    <div class="col-sm-6">
                       <h3>Tarjeta Informativa</h3>                     
                    </div>
                    <div class="col-sm-6 text-end">                
                      <button id="edit" class="btn btn-primary btn-sm" onclick="Editar()" type="button">Editar</button>
                      <button id="save" class="btn btn-primary btn-sm" onclick="Guardar()" type="button">Guardar</button>
                    </div>           
                   </div>
                    <div class="row">                     
                    <div class="card-body pad">
                      <input type="hidden" name="txt_id_tarjeta" id="txt_id_tarjeta">                     

                      <div class="mb-3 textarea">
                        <p>Edite tarjeta informativa<p>
                      </div>
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
          </div>

          </div>        
      </div>
    </div>

  <script>
  // $(function () {
  //   // Summernote
  //   $('.textarea').summernote({
  //   	height: 500,
  //   })
  // })

function Editar () {
  $('.textarea').summernote({focus: true,height:500});
};

function Guardar() {
  var markup = $('.textarea').summernote('code');
  var id_t = $('#txt_id_tarjeta').val();
  var id = '<?php echo $id; ?>';
   var parametros = 
    {
      'articulo':id,
      'tarjeta':markup,
      'id_tarjeta':id_t,
    }
    $.ajax({
      data:  {parametros:parametros},
      url:   '../../controlador/detalle_articuloC.php?tarjeta_guardar=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
        	$('.textarea').summernote('destroy');
        	cargar_tarjeta(id);
      }
    });

};


</script>

<?php /*include('../../cabeceras/footer.php');*/ ?>
     