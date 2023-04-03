<?php include('./header.php'); include('../controlador/venta_facturasC.php'); 
$admin = 0;
if($_SESSION['INICIO']['TIPO_NOMBRE']=='ADMINISTRADOR'){ $admin =1;}
$punto = '';$nom_punto = '';
if($_SESSION['INICIO']['PUNTO_VENTA_SELECIONADO']!=''){ $punto = $_SESSION['INICIO']['PUNTO_VENTA_SELECIONADO'];$nom_punto = $_SESSION['INICIO']['PUNTO_VENTA_SELECIONADO_NOM'];} ?>
<script type="text/javascript">
	 $( document ).ready(function() {
    var admin = '<?php echo $admin; ?>';
    if(admin==0)
    {
      var punt = '<?php echo $punto; ?>';
      var nom = '<?php echo $nom_punto; ?>';
      $('#ddl_bodega').prop('disabled', true);
      $('#btn_lim').prop('disabled', true);
      $('#ddl_bodega').append($('<option>',{value:punt, text: nom,selected: true }));
    }
    console.log(admin);
    autocoplet_bodegas();
	 	cargar_todas_factura();
    cargar_factura_pendientes();
    cargar_factura_fnalizadas();

    });
    function autocoplet_bodegas(){
      $('#ddl_bodega').select2({
        placeholder: 'Seleccione una bodega',
        width:'90%',
        ajax: {
          url:   '../controlador/venta_facturasC.php?punto_venta=true',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            // console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
      });
  }


	 function cargar_todas_factura()
	 {

    var parametros = 
    {
      'punto':$('#ddl_bodega').val(),
      'query':$('#txt_query').val(),
    }
       $.ajax({
         data:  {parametros:parametros},
         url:   '../controlador/venta_facturasC.php?facturas=true',
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

    function cargar_factura_pendientes()
   { 
    var parametros = 
    {
      'tipo':'P',
      'punto':$('#ddl_bodega').val(),
      'query':$('#txt_query').val(),
    }
       $.ajax({
         data:  {parametros:parametros},
         url:   '../controlador/venta_facturasC.php?facturas_pendientes=true',
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

    function cargar_factura_fnalizadas()
   { 
    var parametros = 
    {
      'tipo':'F', 
      'punto':$('#ddl_bodega').val(),
      'query':$('#txt_query').val(),
    }
       $.ajax({
         data:  {parametros:parametros},
         url:   '../controlador/venta_facturasC.php?facturas_finalizadas=true',
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
    var url = "Facturacion.php?numfac="+id+"&doc="+doc+'&est='+estado+'&pnt='+punto;
    $(location).attr('href',url);
   }

</script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Facturas de Ventas</h1>
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
          <div class="col-sm-6">
            Cliente
            <input type="text" name="txt_query" id="txt_query" class="form-control form-control-sm" onkeyup="cargar_todas_factura();cargar_factura_fnalizadas();cargar_factura_pendientes()">
          </div>
           <div class="col-sm-6">
            Punto de venta
              <div class="input-group input-group-sm">
                <select class="form-control form-control-sm" name="ddl_bodega" id="ddl_bodega" onchange="cargar_todas_factura();cargar_factura_fnalizadas();cargar_factura_pendientes()">
                    <option value="">Seleccione bodega</option>
                </select>  
                <span class="input-group-append">
                    <button type="button" class="btn btn-primary btn-flat" id="btn_lim" onclick="$('#ddl_bodega').val(null).trigger('change');cargar_todas_factura();cargar_factura_fnalizadas();cargar_factura_pendientes();"><i class="fa fa-trash"></i></button>
                </span>
              </div> 
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
