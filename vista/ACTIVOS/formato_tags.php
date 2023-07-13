<?php include("header.php"); ?>
<script type="text/javascript">
  $(document).ready( function() { 
    var id = "<?php if(isset($_GET['forma'])){ echo $_GET['forma'];} ?>";
    if(id!='')
    {
      $('#txt_id').val(id);
      cargar_formatos();
    }

    //Esta parte del código se ejecutará automáticamente cuando la página esté lista.
    $("#btn_guardar").click( function() {     // Con esto establecemos la acción por defecto de nuestro botón de enviar.
      if($('#txt_nom_eti').val() !="")
      {
            $.post("../controlador/formato_tagsC.php?guardar=true",$("#form_detalles").serialize(),function(res){
                // $("#form_detalles").fadeOut("slow");   // Hacemos desaparecer el div "formulario" con un efecto fadeOut lento.
                if(res == 1){
                     Swal.fire(
                  '',
                  'Formato guardado con exito.',
                  'success')
                } else {
                    Swal.fire(
                  '',
                  'Algo extraño a pasado intente mas tarde.',
                  'error')
                }
            });
        }else
        {
           Swal.fire(
                  '',
                  'Coleque un nombre a la etiqueta.',
                  'error');
        }        
    });    
});

function generar_code()
 {
 	var parametros = {
 		'tamano':40,
 		'contenido_qr': $('#txt_codeqr').val(),
 		'tipo':$('#ddl_tipo').val(),
 		'orientacion':$('#ddl_orientacion').val(),
 		'contenido_br':$('#txt_barcode').val(),
 		'tamano_br':40,
    'qr':$('#rbl_qr').prop('checked'),
    'barras':$('#rbl_barras').prop('checked'),
 	}
 	codigos ='';		
	$.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/formato_tagsC.php?generar=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
        	$.each(response, function(i,item){
        		console.log(item);
        		codigos+=item.imagen;
        	})
        	$('#codes').html(codigos);
        	
        		modificar_canvas();
      }
    });
  }
  function modificar_canvas()
  {
    if ( $("#img_qr").length) {
  	     $('#img_qr').width($('#txt_qr_w').val());
  	     $('#img_qr').height($('#txt_qr_h').val());
  	     $('#img_qr').css('top',$('#txt_qr_y').val()+'px');
  	     $('#img_qr').css('left',$('#txt_qr_x').val()+'px');
     }
    if ( $("#img_br").length ) {
  	    $('#img_br').width($('#txt_br_w').val());
  	    $('#img_br').height($('#txt_br_h').val());
  	    $('#img_br').css('top',$('#txt_br_y').val()+'px');
  	    $('#img_br').css('left',$('#txt_br_x').val()+'px');
      }

  	if($('#txt_alternativo')!='')
  	{
  		$("#lbl_alternativo").remove();
  		var label = "<label id='lbl_alternativo' style='font-size:"+$('#txt_alt_ta').val()+"px; position:relative; left:"+$('#txt_alt_x').val()+"px; top:"+$('#txt_alt_y').val()+"px'>"+$('#txt_alternativo').val()+"</laber>";
  		$('#codes').append(label);
  	}
  	
  }

   function cargar_formatos()
  {   
    var id = $('#txt_id').val();
     $.ajax({
          data:  {id:id},
         url:   '../controlador/formato_tagsC.php?formato=true',
         type:  'post',
         dataType: 'json',
         success:  function (response) {
       console.log(response[0]);
          $('#txt_br_x').val(response[0].br_x);
          $('#txt_br_y').val(response[0].br_y);
          $('#txt_br_h').val(response[0].br_h);
          $('#txt_br_w').val(response[0].br_w);
          $('#txt_barcode').val(response[0].texto_barras);
          $('#txt_qr_x').val(response[0].qr_x);
          $('#txt_qr_y').val(response[0].qr_y);
          $('#txt_qr_w').val(response[0].qr_w);
          $('#txt_qr_h').val(response[0].qr_h);
          $('#txt_codeqr').val(response[0].texto_qr);
          $('#txt_alternativo').val(response[0].texto);
          $('#txt_alt_y').val(response[0].texto_y);
          $('#txt_alt_x').val(response[0].texto_x);
          $('#txt_alt_ta').val(response[0].tamano_texto);
          $('#txt_nom_eti').val(response[0].nombre_etiqueta);
          if(response[0].barras==1)
          {
            $('#rbl_barras').prop('checked',true);
          }else
          {
            $('#rbl_barras').prop('checked',false);
          }
          if(response[0].qr==1)
          {
            $('#rbl_qr').prop('checked',true);
          }else
          {
            $('#rbl_qr').prop('checked',false);
          }
          // console.log(response.barras ==1);
          generar_code()
         }
   
         });
    }

	</script>
	  <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">Formato de tag</h1>
              <div class="row">
              	   <div class="col-sm-6">
                    <div id="codes" style="border:1px solid #d3d3d3; height: 198px ; width: 397px"></div>
                   <button class="btn btn-primary" id="btn_guardar">Guardar formato de etiqueta</button>
              	   </div>
              	   <div class="col-sm-6">
                    <form id="form_detalles">
                      <input type="hidden" name="txt_id" id="txt_id">
                        <b>Nombre del Formato de la etiqueta</b> 
                        <input type="text" name="txt_nom_eti" class="form-control" id="txt_nom_eti"> 
              	   	    <label><input type="checkbox" name="rbl_barras" id="rbl_barras" checked=""> Codigo de barras</label>
              	   	    <div class="row">
              	   	    	<div class="col-sm-12">
              	   	    		Valor a encriptar <br>
              	   	    		<input type="text" class="form-control" name="txt_barcode" id="txt_barcode" placeholder="Valor del codigo">
              	   	    	</div>
              	   	    	<div class="col-sm-6">
              	   	    		Tipo de codigo
              	   	    		 <select name="barcodeType" id="ddl_tipo" class="form-control">
              	   	    		 	<option value="codabar">Codabar</option>
              	   	    	        <option value="code128">Code128</option>
              	   	    	        <option value="code39">Code39</option>
              	   	            </select>              	   	    		
              	   	    	</div>   
              	   	        <div class="col-sm-6">
              	   	    	    Horientacion
              	   	    	    <select class="form-control" id="ddl_orientacion">
              	   	    	        <option value="horizontal">Horizontal</option>
              	   	    	        <option value="vertical">Vertical</option>
              	   	            </select>
              	   	        </div>
              	   	    </div>        	   	    
              	   	    <div class="row">
              	   	         <div class="col-sm-3">
              	   	    	     Posicion en X
              	   	    	     <input type="" name="txt_br_x" class="form-control" id="txt_br_x" placeholder="posicion x" value="1">
              	   	         </div>
              	   	         <div class="col-sm-3">
              	   	    	     Posicion en Y
              	   	    	     <input type="" name="txt_br_y" class="form-control" id="txt_br_y" placeholder="posicion Y" value="1">
              	   	         </div>
              	   	         <div class="col-sm-3">
              	   	    	     Ancho
              	   	    	     <input type="" name="txt_br_w" class="form-control" id="txt_br_w" placeholder="width" value="150">
              	   	         </div>
              	   	         <div class="col-sm-3">
              	   	    	     Alto
              	   	    	     <input type="" name="txt_br_h" class="form-control" id="txt_br_h" placeholder="height" value="100">
              	   	         </div>
              	   	    </div>              	   	   
              	   	    <label><input type="checkbox" name="rbl_qr" id="rbl_qr"> Codigo QR</label>
              	   	    <div class="row">
              	   	    	<div class="col-sm-12">
              	   	    		Valor a encriptar
              	   	    		 <input type="text" name="txt_codeqr" class="form-control" id="txt_codeqr" placeholder="Valor del codigo qr">              	   	    	
              	   	    	</div>               	   	    	 	   	    	
              	   	    </div>
              	   	    <div class="row">
              	   	         <div class="col-sm-3">
              	   	    	     Posicion en X
              	   	    	     <input type="" name="txt_qr_x" class="form-control" id="txt_qr_x" placeholder="posicion x" value="1">
              	   	         </div>
              	   	         <div class="col-sm-3">
              	   	    	     Posicion en Y
              	   	    	     <input type="" name="txt_qr_y" class="form-control" id="txt_qr_y" placeholder="posicion Y" value="1">
              	   	         </div>
              	   	         <div class="col-sm-3">
              	   	    	     Ancho
              	   	    	     <input type="" name="txt_qr_w" class="form-control" id="txt_qr_w" placeholder="width" value="100">
              	   	         </div>
              	   	         <div class="col-sm-3">
              	   	    	     Alto
              	   	    	     <input type="" name="txt_qr_h" class="form-control" id="txt_qr_h" placeholder="height" value="100">
              	   	         </div>
              	   	    </div> 
              	   	    texto alternativo
              	   	    <input type="" name="txt_alternativo" class="form-control" id="txt_alternativo">  
                        <div class="row">
                             <div class="col-sm-3">
                               Posicion en X
                               <input type="" name="txt_alt_x" class="form-control" id="txt_alt_x" placeholder="posicion x" value="1">
                             </div>
                             <div class="col-sm-3">
                               Posicion en Y
                               <input type="" name="txt_alt_y" class="form-control" id="txt_alt_y" placeholder="posicion Y" value="1">
                             </div>
                             <div class="col-sm-3">
                               Tamaño de letra
                               <input type="" name="txt_alt_ta" class="form-control" id="txt_alt_ta" value="12">
                             </div>                             
                        </div> 
                      </form>

              	   	    <button class="btn btn-success" onclick="generar_code()">Generar codigo</button>
              	   	    <button class="btn btn-success" onclick="modificar_canvas()">Modificar</button>
              	   </div>
              </div>	  		  	
	  </div>

<?php include('footer.php') ?>