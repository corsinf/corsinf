<?php include('./header.php'); include('../controlador/venta_pedidosC.php'); ?>
<script type="text/javascript">
	 $( document ).ready(function() {
	 	cargar_todas_pedidos();
        cargar_pedidos_pendientes();
        cargar_pedidos_fnalizadas();

    });

	 function cargar_todas_pedidos()
	 { 
       $.ajax({
         // data:  {parametros:parametros},
         url:   '../controlador/venta_pedidosC.php?pedidos=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {  
           if (response!="") 
           {
             $('#todas').html(response);        
           } 
          } 
          
       });
	 }

   function cargar_pedidos_pendientes()
   { 
    var parametros = 
    {
      'tipo':'PR',
    }
       $.ajax({
         data:  {parametros:parametros},
         url:   '../controlador/venta_pedidosC.php?pedidos_pendientes=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {  
           if (response!="") 
           {
             $('#pendientes').html(response);        
           } 
          } 
          
       });
   }

    function cargar_pedidos_fnalizadas()
   { 
    var parametros = 
    {
      'tipo':'F',
    }
       $.ajax({
         data:  {parametros:parametros},
         url:   '../controlador/venta_pedidosC.php?pedidos_finalizadas=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {  
           if (response!="") 
           {
             $('#finalizadas').html(response);        
           } 
          } 
          
       });
   }
   function Ver_factura(id,doc,estado,punto)
   {
    var url = "presupuestos.php?numfac="+id+"&doc="+doc+'&est='+estado+'&pnt='+punto;
    $(location).attr('href',url);
   }

</script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Cotizacion de Venta</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <section class="content">
      <div class="container-fluid">
      	<div class="row">
      		<div class="col-sm-6">
      			<a href="punto_venta.php" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Nuevo</a>
      		</div>
      	</div>
      	<div class="row">
      		<div class="col-md-12"><br>
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link active" href="#todas" data-toggle="tab">Todos</a></li>
                  <li class="nav-item"><a class="nav-link" href="#finalizadas" data-toggle="tab">Finalizadas</a></li>
                  <li class="nav-item"><a class="nav-link" href="#pendientes" data-toggle="tab">Pendientes</a></li>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="tab-pane active" id="todas">

                  </div>
                  <!-- /.tab-pane -->
                  <div class="tab-pane" id="finalizadas">
                   
                  </div>

                  <div class="tab-pane" id="pendientes">
                    
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.nav-tabs-custom -->
          </div>
      		
      	</div>

                   

      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

<?php include('./footer.php'); ?>
