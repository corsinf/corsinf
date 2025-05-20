<script type="text/javascript">
 $( document ).ready(function() {
   Empresascliente()   
 })

  function Empresascliente() 
  {

      $.ajax({
          // data:  {parametros,parametros},
          url:   '../controlador/nueva_empresaC.php?listaClienteEmpresas=true',
          type: 'POST',         
            dataType:'json',
          success: function(response) {

            $('#tbl_lista_cliente').html(response);          
          },
          error: function(error) {
              console.error('Error al enviar datos:', error);
              // Puedes manejar los errores aquí
          }
      });
  }

  function detalle_licencias(id)
  {
    var parametros = {
      'id':id
    }
    $.ajax({
          data:  {parametros,parametros},
          url:   '../controlador/nueva_empresaC.php?detalle_licencias=true',
          type: 'POST',         
            dataType:'json',
          success: function(response) {
            console.log(response)
            var tbl = '<thead><th>Licencia</th><th>Fecha Inicio</th><th>Fecha Fin</th><th>Modulo</th><th>Num Usuarios</th><th>Estado</th></thead>';
            response.forEach(function(item,i){
                tbl+=`<tr><td>`+item.Codigo_licencia+`</td>
                          <td>`+item.Fecha_ini+`</td>
                          <td>`+item.Fecha_exp+`</td>
                          <td>`+item.nombre_modulo+`</td>
                          <td>`+item.Numero_maquinas+`</td>
                          <td>`
                          if(item.registrado=='0')
                          {
                              tbl+=`<div class="d-flex align-items-center text-danger"> <i class="bx bx-radio-circle-marked bx-burst bx-rotate-90 align-middle font-18 me-1"></i>
                                    <span>Pendiente de activacion</span>`;
                          }else
                          {
                               tbl+=`<div class="d-flex align-items-center text-success"> <i class="bx bx-radio-circle-marked bx-burst bx-rotate-90 align-middle font-18 me-1"></i>
                                    <span>Activo</span>`;
                          }

                        tbl+=`</td>
                      </div>
                </tr>`
            })

            $('#tbl_detalles_canal_lic').html(tbl);  
            $('#myModal_detalles_canales_lic').modal('show');        
          },
          error: function(error) {
              console.error('Error al enviar datos:', error);
              // Puedes manejar los errores aquí
          }
      });
  }
  function detalle_empresa(id)
  {
    var parametros = {
      'id':id
    }
    $.ajax({
          data:  {parametros,parametros},
          url:   '../controlador/nueva_empresaC.php?detalle_empresa=true',
          type: 'POST',         
            dataType:'json',
          success: function(response) {
            console.log(response)

            var tbl =`<tr><td colspan="2" class=""><h3>Datos Empresa</h3></td></tr>  
                      <tr><td><u>Nombre Empresa</u></td><td>`+response[0]['Nombre_Comercial']+`</td></tr>  
                      <tr><td><u>Razon social</u></td><td>`+response[0]['Razon_Social']+`</td></tr>  
                      <tr><td><u>CI / RUC</u></td><td>`+response[0]['Ruc']+`</td></tr>  
                      <tr><td><u>Telefono</u></td><td>`+response[0]['Telefono']+`</td></tr>  
                      <tr><td><u>Email</u></td><td>`+response[0]['Email']+`</td></tr>  
                      <tr><td><u>Direccion</u></td><td>`+response[0]['Direccion']+`</td></tr>  
                      <tr><td colspan="2" class=""><h3>Base de Datos</h3></td></tr>  
                      <tr><td><u>Nombre DB</u></td><td>`+response[0]['Base_datos']+`</td></tr>  
                      <tr><td colspan="2" class=""><b>SMTP Host</b></td></tr> 
                      <tr><td><u>Smtp host</u></td><td>`+response[0]['smtp_host']+`</td></tr>
                      <tr><td colspan="2" class=""><h3>Modulos</h3></td><tr></tr> `
                      response[0].modulos.forEach(function(item,i){
                          tbl+=` <tr><td>`+item.nombre_modulo+`</td><td></td></tr>`
                      })

            $('#tbl_detalles_canal').html(tbl);  
            $('#myModal_detalles_canales').modal('show');       
          },
          error: function(error) {
              console.error('Error al enviar datos:', error);
              // Puedes manejar los errores aquí
          }
      });

  }

  function dar_alta(id)
  {
    var parametros = {
      'id':id
    }
    $.ajax({
          data:  {parametros,parametros},
          url:   '../controlador/nueva_empresaC.php?dar_alta=true',
          type: 'POST',         
            dataType:'json',
          success: function(response) {
            console.log(response)
            if(response==1)
            {
              Swal.fire("Empresa verificada","","success")
              Empresascliente();
            }
          },
          error: function(error) {
              console.error('Error al enviar datos:', error);
              // Puedes manejar los errores aquí
          }
      });
  }
  
</script>
<div class="page-wrapper">
      <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-0">
          <div class="breadcrumb-title pe-3">Forms</div>
          <div class="ps-3">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Wizard</li>
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
	          			<div class="col-sm-10">
	          				<a href="../vista/inicio.php?mod=<?php echo '2017'?>&acc=nuevo_cliente_canal" class="btn btn-sm btn-primary">Nuevo</a>
	          			</div>	          			
	          		</div>
	          		<div class="row">
	          			<div class="col-sm-12">
	          				<table class="table w-100">
		          				<thead>
		          					<th>Empresa</th>
                        <th>Ruc</th>
                        <th>Telefono</th>
                        <th>Email</th>
                        <th>Estado</th>
                        <th></th>
		          				</thead>
		          				<tbody id="tbl_lista_cliente">
		          					
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


<div class="modal fade" id="myModal_detalles_canales" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-md modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-12">
              <table class="table table-sm table-hover" id="tbl_detalles_canal">
          
              </table>
            </div>            
          </div>
        </div>
        <div class="modal-footer"> 

        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>       
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="myModal_detalles_canales_lic" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-12">
              <table class="table table-sm table-hover" id="tbl_detalles_canal_lic">
          
              </table>
            </div>            
          </div>
        </div>
        <div class="modal-footer"> 

        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>       
        </div>
      </div>
    </div>
  </div>
