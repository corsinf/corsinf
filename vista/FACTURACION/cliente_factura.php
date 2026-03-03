<?php 
$mod = $_GET['mod']; 
$tipo = 'FA';
if(isset($_GET['tipo']) && $_GET['tipo']!=''){$tipo = $_GET['tipo'];}
?>
<script type="text/javascript">
	var modulo = '<?php echo $mod; ?>'
</script>

<script type="text/javascript">
$(document).ready(function () {	

    $( "#txt_ci" ).autocomplete({
      source: function( request, response ) {
                
            $.ajax({
                url: '../controlador/FACTURACION/cliente_facturaC.php?buscar_cliente_x_ci=true',
                type: 'post',
                dataType: "json",
                data: {
                    q: request.term
                },
                success: function( data ) {
                  // console.log(data);
                    response( data );
                }
            });
        },
        select: function (event, ui) {
            $('#txt_ci').val(ui.item.label); // display the selected text
            $('#txt_nombre').val(ui.item.nombre); // save selected id to input
            $('#txt_email').val(ui.item.email); // save selected id to input
            $('#txt_telefono').val(ui.item.telefono); // save selected id to input
            $('#txt_razon').val(ui.item.razon); // save selected id to input
            $('#txt_direccion').val(ui.item.direccion); // save selected id to input
            $('#txt_id').val(ui.item.value); // save selected id to input

            return false;
        },
        focus: function(event, ui){
            $( "#txt_ci" ).val( ui.item.label);
            return false;
        },
    });

     $( "#txt_nombre" ).autocomplete({
      source: function( request, response ) {
                
            $.ajax({
                url: '../controlador/FACTURACION/cliente_facturaC.php?buscar_cliente_x_ci2=true',
                type: 'post',
                dataType: "json",
                data: {
                    q: request.term
                },
                success: function( data ) {
                  // console.log(data);
                    response( data );
                }
            });
        },
        select: function (event, ui) {
            $('#txt_ci').val(ui.item.ci); // display the selected text
            $('#txt_nombre').val(ui.item.label); // save selected id to input
            $('#txt_email').val(ui.item.email); // save selected id to input
            $('#txt_telefono').val(ui.item.telefono); // save selected id to input
            $('#txt_razon').val(ui.item.razon); // save selected id to input
            $('#txt_direccion').val(ui.item.direccion); // save selected id to input
            $('#txt_id').val(ui.item.value); // save selected id to input

            return false;
        },
        focus: function(event, ui){
        	console.log(ui);
            $( "#txt_nombre" ).val(ui.item.nombre);
            return false;
        },
    });

});

function guardar()
{
	if($('#txt_ci').val()=='')
	{
		Swal.fire('Seleccione un cliente','','info');
		return false;
	}

	if($('#txt_nombre').val()!='' &&  $('#txt_razon').val()=='')
	{
		$('#txt_razon').val($('#txt_nombre').val());
	}

	if($('#txt_nombre').val()=='' || $('#txt_email').val()=='' || $('#txt_razon').val()=='' || $('#txt_direccion').val()=='')
	{
		Swal.fire('Llene todo los campos','','info');
		return false;
	}
	tipo = '<?php echo $tipo; ?>'

	var datos = $('#form_cliente').serialize();
	$.ajax({
	  type: "POST",
	  url: '../controlador/FACTURACION/cliente_facturaC.php?guardar=true',
	  data: datos, 
	  dataType:'json',
	  success: function(data)
	  {
	  	console.log(data);
	  	if(tipo=='FA')
	  	{
	  	 location.href="inicio.php?mod="+modulo+"&acc=detalle_factura&id="+data[0].id+"&estado=P";
		}else{
	  	 location.href="inicio.php?mod="+modulo+"&acc=detalle_liquidacion&id="+data[0].id+"&estado=P";
	  	}
	  }
	})

}

</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Documentos</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Nueva factura
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="card shadow mb-3">
                <div class="card-body">
                   <div class="row">
                   	  <form id="form_cliente" class="col-sm-9">
	                   	 <div class="col-sm-12">
	                   	 	<div class="row">
	                   	 		<input type="hidden" name="txt_id" id="txt_id">
	                   	 		<input type="hidden" name="txt_tc" id="txt_tc" value="<?php echo $tipo; ?>">
	                   	 		 <div class="col-sm-6">
			                   		<b>CI / RUC</b>
			                   		<input type="text" name="txt_ci" id="txt_ci" class="form-control-sm form-control" onkeyup="num_caracteres('txt_ci',13)" onblur="validar_ci_ruc('txt_ci')" placeholder="Ingrese numero de cedula">
			                   	</div>
			                   	<div class="col-sm-6">
			                   		<b>Nombre</b>
		                   			<input type="" name="txt_nombre" id="txt_nombre" class="form-control-sm form-control">
		                   		</div>
		                   		<div class="col-sm-4">
		                   			<b>Email</b>
		                   			<input type="text" id="txt_email" name="txt_email" class="form-control-sm form-control" onblur ="validador_correo('txt_email')" autocomplete="off">
		                   		</div>
		                   		<div class="col-sm-4">
		                   			<b>Telefono</b>
		                   			<input type="" name="txt_telefono" id="txt_telefono" class="form-control-sm form-control"  onkeyup="num_caracteres('txt_telefono',10)">
		                   		</div>
		                   		<div class="col-sm-4">
		                   			<b>Razon Social</b>
		                   			<input type="" name="txt_razon" id="txt_razon" class="form-control-sm form-control">
		                   		</div>
		                   		<div class="col-sm-12">
		                   			<b>Direccion</b>
		                   			<textarea class="form-control-sm form-control" style="resize:none;" rows="3" name="txt_direccion" id="txt_direccion" ></textarea>
		                   		</div>
	                   	 	</div>
	                   	 </div>
	                   	</form>
	                   	 <div class="col-sm-3">	                   	 	
	                   	 	<button class="btn btn-sm btn-default" style="border: 1px solid;"> Cancelar <img src="../img/de_sistema/close.png"></button>
	                   	 	<button class="btn btn-sm btn-default" style="border: 1px solid;" onclick="guardar()"> Continuar <img src="../img/de_sistema/next.png"></button>
	                   	 	
	                   	 </div>	 	                 
                   	</div>
                  </div>
              </div>
          </div>
      </div>
  </div>
