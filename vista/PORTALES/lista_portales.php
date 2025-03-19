<script type="text/javascript">
 $( document ).ready(function() {
 	cargar_datos();  
 })

function cargar_datos() {
   
    $.ajax({
      // data: {parametros: parametros},
      url: '../controlador/PORTALES/portalesC.php?lista=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
      	tr = '';
      	response.forEach(function(item,i){
      		tr+=`<tr>
      				<td>`+(i+1)+`</td>
      				<td>`+item.nombre+`</td>
      				<td>`+item.serie+`</td>
      				<td>`+item.ip+`</td>
      				<td>`+item.puerto+`</td>
      				<td>
      					<button class="btn btn-sm btn-danger"><i class="bx bx-trash m-0"></i></butto>
      					<button class="btn btn-sm btn-primary" onclick="comenzar_lectura('`+item.id+`')"><i class="bx bx-play m-0"></i></butto>
      				</td>
      			</tr>`
      	})
      	$('#tbl_portales').html(tr);       
      }

    });
 }

 function comenzar_lectura(portal)
 {
 	$('#modal_respuesta').modal('show');
 	parametros = 
 	{
 		'id':portal,
 	}
 	$.ajax({
      data: {parametros: parametros},
      url: '../controlador/PORTALES/portalesC.php?comenzar_lectura=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        var li = '';
        response.forEach(function(item,i){
          const linea = JSON.parse(item);
           li+= `<li>`+linea.epc+`</li>`
        })

        $('#img_espera_logo').css('display','none');
        if(li!='')
        {
          $('#lbl_msj_espera').html(li);
        }else
        {
           $('#lbl_msj_espera').html('<li>No se a encontrado lecturas</li>');
        }

      }

    });
 }
  
</script>
<div class="page-wrapper">
      <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
          <div class="breadcrumb-title pe-3">Portales</div>
          <div class="ps-3">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Lista portales</li>
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
                	<div class="col">
                		<button class="btn btn-primary btn-sm"><i class="bx bx-search"></i>Buscar</button>                		
                	</div>
                	
                </div>               
              </div>
            </div>
          </div>
        </div>

         <div class="row">
          <div class="col-xl-12 mx-auto">
            <div class="card">
              <div class="card-body">
              	<div class="row">
              		<div class="col-lg-12">
              			<table class="table table-hover">
              				<thead>
              					<th>#</th>
              					<th>Nombre</th>
              					<th>Tipo conexion</th>
              					<th>Ip</th>
              					<th>Puerto</th>
              					<th></th>
              				</thead>
              				<tbody id="tbl_portales">
              					
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

<div class="modal fade" id="modal_respuesta" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
	  <div class="modal-dialog modal-sm modal-dialog-centered">
	    <div class="modal-content">
	      <div class="modal-header">
	      </div>
	      <div class="modal-body">
	         <div class="text-center">	         	
              <div id="img_espera_logo">
                <?php if (file_exists($_SESSION['INICIO']['LOGO'])) { ?>      
                  <img src="<?php echo $_SESSION['INICIO']['LOGO']; ?>" style="width: 35%;" alt="logo icon">
                <?php } ?>
                  ESPERE...
              </div>		
	         </div>
	         <div class="text-center">
	         	<div id="lbl_msj_espera"></div>
	         </div>
	        
	      </div>
	      <div class="modal-footer">   
	      		<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
	      </div>
	    </div>
	  </div>
	</div>

