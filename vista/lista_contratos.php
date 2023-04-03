<?php include('./header.php'); ?>
<script type="text/javascript">	
    $( document ).ready(function() {
    	lista_contratos();
    })  
   function lista_contratos()
   {
   	var parametros = 
   	{
   		'query':$('#txt_query').val(),
   		'desde':$('#txt_desde').val(),
   		'hasta':$('#txt_hasta').val(),
   		'opcion':$('input[name="cbx_opc"]:checked').val(),
   	}
    $.ajax({
         data:  {parametros,parametros},
         url:   '../controlador/contratoC.php?lista_contratos=true',
         type:  'post',
         dataType: 'json',
           success:  function (response) { 
           	if(response!='')
           	{
           		// console.log(response);
           		$('#tbl_body').html(response);
           	}
          } 
          
       });
   }
</script>
<div class="page-wrapper">
      <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
          <div class="breadcrumb-title pe-3">Seguros</div>
          <div class="ps-3">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">lista de contratos de seguros</li>
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
                    <a href="contratos.php" class="btn btn-sm btn-primary"><i class="bx bx-plus"></i>Nuevo</a>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <b>Buscar proveedor</b>
                    <input type="" name="txt_query" id="txt_query" class="form-control form-control-sm" onkeyup="lista_contratos()">            
                  </div>
                  <div class="col-sm-6">
                    <div class="row">
                      <div class="col-sm-6">
                        <b>Desde</b>
                        <input type="date" name="txt_desde" id="txt_desde" class="form-control form-control-sm" onblur="lista_contratos()">           
                      </div>
                      <div class="col-sm-6">
                        <b>Hasta</b>
                        <input type="date" name="txt_hasta" id="txt_hasta" class="form-control form-control-sm" onblur="lista_contratos()">           
                      </div>                  
                    </div>
                    <label><input type="radio" name="cbx_opc" value="1" onclick="lista_contratos()"> Fecha contrato</label>
                    <label><input type="radio" name="cbx_opc" value="2" onclick="lista_contratos()"> Fecha fin contrato</label> 
                    <label><input type="radio" name="cbx_opc" value="0" onclick="lista_contratos()" checked> Ninguno</label>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-12">
                    <table class="table table-striped table-bordered dataTable">
                      <thead>
                        <th>Proveedor</th>
                        <th>Precio prima</th>
                        <th>Fecha contrato</th>
                        <th>Fecha fin</th>
                        <th>Suma asegurada</th>
                      </thead>
                      <tbody id="tbl_body">
                        <tr><td colspan="5">No se encontraton registros</td></tr>
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


<?php include('./footer.php'); ?>
