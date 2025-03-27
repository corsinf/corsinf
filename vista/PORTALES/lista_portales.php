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
        console.log(response);
      	tr = '';
      	response.forEach(function(item,i){
      		tr+=`<tr>
      				<td>`+(i+1)+`</td>
      				<td>`+item.nombre+`</td>
      				<td>`+item.comunicacion+`</td>
      				<td>`+item.ip+`</td>
      				<td>`+item.puerto+`</td>
              <td>`+item.com+`</td>
              <td>`+item.com2+`</td>
              <td>`+item.adr485+`</td>
      				<td>
      					<button class="btn btn-sm btn-danger"  onclick="eliminar_portal('`+item.id+`')"><i class="bx bx-trash m-0"></i></butto>
      					<button class="btn btn-sm btn-primary" onclick="comenzar_lectura('`+item.id+`')"><i class="bx bx-play m-0"></i></butto>
      				</td>
      			</tr>`
      	})
      	$('#tbl_portales').html(tr);       
      }

    });
 }

 function eliminar_portal(id)
 {
   Swal.fire({
      title: 'Quiere eliminar este registro?',
      text: "Esta seguro de eliminar este registro!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
        if (result.value) {
          eliminar_portal_antena(id);
        }
    })
 }

function eliminar_portal_antena(id)
 {
  parametros = 
  {
    'id':id,
  }
  $.ajax({
      data: {parametros: parametros},
      url: '../controlador/PORTALES/portalesC.php?eliminar_portal_antena=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        cargar_datos();
      }

    });
 }


 function comenzar_lectura(portal)
 {
 	$('#modal_respuesta').modal('show');
  $('#lbl_msj_espera').text('');  
  $('#img_espera_logo').css('display','block');
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
        if(response.resp=='-1')
        {
          Swal.fire("No se pudo conectar",response.msj,'error').then(function(){
            $('#modal_respuesta').modal('hide');    
          })

        }else{
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

      }

    });
 }


  function habilitar_campos()
  {
    var tipo = $('#ddl_tipo_antena').val();
    switch(tipo)
    {
      case 'RS232USB':
        $('#pnl_rs_usb').removeClass('d-none');
        $('#pnl_rs_485').addClass('d-none');
        $('#pnl_tcp_ip').addClass('d-none');
        $('#txt_ip').val('');
        $('#txt_puerto').val('');

        break;
      case 'RS485':
        $('#pnl_rs_usb').removeClass('d-none');
        $('#pnl_rs_485').removeClass('d-none');
        $('#pnl_tcp_ip').addClass('d-none');
         $('#txt_ip').val('');
         $('#txt_puerto').val('');

        break;
      case 'TCPIP':        
        $('#pnl_rs_usb').addClass('d-none');
        $('#pnl_rs_485').addClass('d-none');
        $('#pnl_tcp_ip').removeClass('d-none');
        $('#txt_adr').val(0)
        break;
      default:
        break;
    }
  }

  function guardar_antena()
  {
    if($('#txt_nombre_antena').val()=='')
    {
      Swal.fire("Agregue un nombre","","error")
      return false;
    }
    if($('#ddl_tipo_antena').val()=='TCPIP' && ($('#txt_ip').val()=='' || $('#txt_puerto').val()==''))
    {
      Swal.fire("Ingrese todos los datos","","error")
      return false;
    }
    var parametros = 
    {

      'tipo':$('#ddl_tipo_antena').val(),
      'nombre':$('#txt_nombre_antena').val(),
      'comm':$('#ddl_com').val(),
      'comm2':$('#ddl_com_2').val(),
      'adr':$('#txt_adr').val(),
      'ip':$('#txt_ip').val(),
      'puerto':$('#txt_puerto').val(),
    }
     $.ajax({
      data: {parametros: parametros},
      url: '../controlador/PORTALES/portalesC.php?guardar_antena=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        Swal.fire("Antena Guardada","","success").then(function(){
          $('#modal_nueva_antena').modal('hide')
          limpiar();
        })
       cargar_datos(); 

      }

    });
  }

  function limpiar()
  {
    $('#ddl_tipo_antena option:first').prop('selected', true);
    $('#ddl_com option:first').prop('selected', true);
    $('#ddl_com_2 option:first').prop('selected', true);    
    $('#txt_nombre_antena').val('');
    $('#txt_adr').val(0);
    $('#txt_ip').val('');
    $('#txt_puerto').val('');
    habilitar_campos()

  }

  function validar_conexion()
  {

    $('#modal_respuesta').modal('show');
    $('#lbl_msj_espera').text('');  
    $('#img_espera_logo').css('display','block');

    var parametros = $('#form_data').serialize();
  
    $.ajax({
      data: {parametros: parametros},
      url: '../controlador/PORTALES/portalesC.php?iniciarControladora=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        if(response.resp=='-1')
        {
          $('#img_espera_logo').css('display','none');
          $('#lbl_msj_espera').text('No se pudo Conectar:'+response.msj);  
        }else if(response.resp=='1')
        {
           $('#img_espera_logo').css('display','none');
           $('#lbl_msj_espera').text('Conectado a :'+response.msj);  
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
                    <button class="btn btn-success btn-sm" onclick="$('#modal_nueva_antena').modal('show');"><i class="bx bx-plus"></i>Nuevo</button> 
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
                        <th>COM</th>                      
                        <th>COM Velocidad</th>                      
                        <th>Adr485</th>
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

<div class="modal fade" id="modal_nueva_antena" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header"><h2>Nueva Antena<h2></div>
        <div class="modal-body">
          <form id="form_data"> 
            <div class="row">  
              <div class="col-12">
                  <b>Nombre Antena</b>
                  <input type="" class="form-control" name="txt_nombre_antena" id="txt_nombre_antena">
               </div>    
               <div class="col-12">
                  <b>Tipo de copnexion</b>
                 <select class="form-select" onchange="habilitar_campos()" id="ddl_tipo_antena"  name="ddl_tipo_antena">
                   <option value="RS232USB">RS232 / USB</option>
                   <option value="RS485">RS485</option>
                   <option value="TCPIP">TCP/IP</option>
                 </select>
               </div>           
             </div>
             <div class="row mt-2" id="pnl_rs_usb">
                <div class="col-6">
                   <select class="form-select" id="ddl_com" name="ddl_com">
                     <option value="COM1">COM1</option>
                     <option value="COM2">COM2</option>
                     <option value="COM3">COM3</option>
                     <option value="COM4">COM4</option>
                     <option value="COM5">COM5</option>
                     <option value="COM6">COM6</option>
                     <option value="COM7">COM7</option>
                     <option value="COM8">COM8</option>
                     <option value="COM9">COM9</option>
                     <option value="COM10">COM10</option>
                     <option value="COM18">COM18</option>
                     <option value="COM34">COM34</option>
                   </select>                
                </div>   
                <div class="col-6">
                   <select class="form-select" id="ddl_com_2" name="ddl_com_2">
                     <option value="9600">9600</option>
                     <option value="19200">19200</option>
                     <option value="38400">38400</option>
                     <option value="57600">57600</option>
                     <option value="115200">115200</option>
                   </select>                
                </div>
                <div class="row d-none" id="pnl_rs_485">
                  <div class="col-3">
                    <b>Adr485</b>
                    <input type="number" name="txt_adr" id="txt_adr" class="form-control" value="0">
                  </div>
                </div>             
             </div>
             <div class="row mt-2 d-none" id="pnl_tcp_ip">
              <div class="col-9">
                <b>IP</b>
                <input type="" name="txt_ip" id="txt_ip" class="form-control" placeholder="192.168.1.50">
              </div>
               <div class="col-3">
                <b>Puerto</b>
                <input type="" name="txt_puerto" id="txt_puerto" class="form-control" placeholder="2101">
              </div>             
             </div>
             <div class="row mt-2">
              <div class="col-12 text-end">
                 <button type="button" class="btn btn-success btn-sm" onclick="validar_conexion()"><i class="bx bx-play"></i>Validar</button>
              </div>
             </div>   
          </form>       
        </div>
        <div class="modal-footer">   
            <button type="button" class="btn btn-primary" onclick="guardar_antena()">Guardar</button>
            <button type="button" class="btn btn-danger" onclick="$('#modal_nueva_antena').modal('hide')">Cerrar</button>
        </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_respuesta" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
	  <div class="modal-dialog modal-sm modal-dialog-centered">
	    <div class="modal-content">
	      <div class="modal-header">
	      </div>
	      <div class="modal-body">
          <div class="row">
            <div class="col-12 text-center">            
              <div id="img_espera_logo">
                  <?php if (file_exists($_SESSION['INICIO']['LOGO'])) { ?>      
                    <img src="<?php echo $_SESSION['INICIO']['LOGO']; ?>" style="width: 35%;" alt="logo icon">
                  <?php } ?>
                    ESPERE...
              </div>    
            </div>
            <div class="col-12 text-center">
               <label id="lbl_msj_espera"></label>               
            </div>          
          </div>
	      </div>
	      <div class="modal-footer">   
	      		<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
	      </div>
	    </div>
	  </div>
	</div>

