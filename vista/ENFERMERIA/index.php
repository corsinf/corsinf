<?php //include('../cabeceras/header.php'); 
//print_r($_SESSION['INICIO']); die(); ?>
  <!-- Content Wrapper. Contains page content -->

  <script type="text/javascript">
  $( document ).ready(function() {
    total_pacientes()
    total_docentes()
    total_estudiantes()
    total_comunidad()
    total_Agendas()
    total_medicamentos()
    total_insumos()
    lista_medicamentos()
    lista_insumos()
    total_consultas()
    pacientes_atendidos()
  });


   

   function total_pacientes()
    {
      $.ajax({
         // data:  {parametros:parametros},
         url:   '../controlador/index_saludC.php?total_pacientes=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {  
            $('#lbl_pacientes').text(response);
          },error: function (jqXHR, textStatus, errorThrown) {
            $('#pnl_pacientes').css('display','none');             
          }
       });

    }
    function total_docentes()
    {
      $.ajax({
         // data:  {parametros:parametros},
         url:   '../controlador/index_saludC.php?total_docentes=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {  
            $('#lbl_docentes').text(response);
          },error: function (jqXHR, textStatus, errorThrown) {
            $('#pnl_docentes').css('display','none');             
          }
       });

    }
    function total_estudiantes()
    {
      $.ajax({
         // data:  {parametros:parametros},
         url:   '../controlador/index_saludC.php?total_estudiantes=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {              
            $('#lbl_estudiantes').text(response);          
          },error: function (jqXHR, textStatus, errorThrown) {
            $('#pnl_estudiantes').css('display','none');             
          }
       });

    }
    function total_comunidad()
    {
      $.ajax({
         // data:  {parametros:parametros},
         url:   '../controlador/index_saludC.php?total_comunidad=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {              
            $('#lbl_comunidad').text(response);          
          },error: function (jqXHR, textStatus, errorThrown) {
            $('#pnl_comunidad').css('display','none');             
          }
       });

    }
    function total_Agendas()
    {
      $.ajax({
         // data:  {parametros:parametros},
         url:   '../controlador/index_saludC.php?total_Agendas=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) { 
            $('#lbl_agenda').text(response);
          },error: function (jqXHR, textStatus, errorThrown) {
            $('#pnl_agenda').css('display','none');             
          }

       });

    }
    function total_consultas()
    {
      $.ajax({
         // data:  {parametros:parametros},
         url:   '../controlador/index_saludC.php?total_consultas=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) { 
            $('#lbl_consultas').text(response);
          },error: function (jqXHR, textStatus, errorThrown) {
            $('#pnl_consultas').css('display','none');             
          }

       });

    }
    function total_medicamentos()
    {
      $.ajax({
         // data:  {parametros:parametros},
         url:   '../controlador/index_saludC.php?total_medicamentos=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {              
            $('#lbl_medicamentos').text(response);          
          }
          ,error: function (jqXHR, textStatus, errorThrown) {
            $('#pnl_medicamentos').css('display','none');             
          }
       });

    }
    function total_insumos()
    {
      $.ajax({
         // data:  {parametros:parametros},
         url:   '../controlador/index_saludC.php?total_insumos=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {              
            $('#lbl_insumos').text(response);          
          },error: function (jqXHR, textStatus, errorThrown) {
            $('#pnl_insumos').css('display','none');             
          }
       });
        
    }

    function lista_medicamentos()
    {
      $.ajax({
         // data:  {parametros:parametros},
         url:   '../controlador/index_saludC.php?lista_medicamentos=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {  
            $('#pnl_alertas_farmacia').append(response.alertas)            
            lista_medicamentos_chart(response.data,response.cate)  
          },error: function (jqXHR, textStatus, errorThrown) {
            $('#pnl_insumos').css('display','none');             
          }
       });
    }
    function lista_insumos()
    {
      $.ajax({
         // data:  {parametros:parametros},
         url:   '../controlador/index_saludC.php?lista_insumos=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) { 
            $('#pnl_alertas_farmacia').append(response.alertas)             
            lista_insumos_chart(response.data,response.cate)  
          },error: function (jqXHR, textStatus, errorThrown) {
            $('#pnl_insumos').css('display','none');             
          }
       });
    }
    function pacientes_atendidos()
    {
      $.ajax({
         // data:  {parametros:parametros},
         url:   '../controlador/index_saludC.php?pacientes_atendidos=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {          
           pie(response.tipo,response.cant)
          },error: function (jqXHR, textStatus, errorThrown) {
            $('#pnl_').css('display','none');             
          }
       });
    }
  </script>

<div class="page-wrapper">
  <div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">Inicio</div>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page"></li>
          </ol>
        </nav>
      </div>     
    </div>
    <!--end breadcrumb-->
    <div class="row">
      <div class="col-xl-12 mx-auto">

      	<?php if($_SESSION['INICIO']['TIPO']=='DBA' || strtoupper($_SESSION['INICIO']['TIPO'])=='DOCENTES'
       || strtoupper($_SESSION['INICIO']['TIPO'])=='COMUNIDAD'  || strtoupper($_SESSION['INICIO']['TIPO'])=='ADMINISTRADOR') { ?>
        <h6 class="mb-0 text-uppercase">Gestion Educativa</h6>
        <hr>
            
        <div class="row">
          <!-- <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>
              <div class="info-box-content">
                <span class="info-box-text" title="Ultima actualizacion con SAP">Ultima actu. con SAP</span>
                <span class="info-box-number">
                   <?php echo date('Y-m-d H:i:s'); ?>
                </span>
              </div>
            </div>
          </div> -->

          <div class="col-4"  id="pnl_estudiantes">
            <div class="card radius-10">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div>
                    <p class="mb-0 text-secondary">Estudiantes</p>
                    <h4 class="my-1" id="lbl_estudiantes">0</h4>
                    <!-- <p class="mb-0 font-13 text-success"><i class="bx bxs-up-arrow align-middle"></i>$34 from last week</p> -->
                  </div>
                  <div class="widgets-icons bg-light-success text-warning ms-auto"><i class="bx bx-package"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-4" id="pnl_docentes">
            <div class="card radius-10">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div>
                    <p class="mb-0 text-secondary">Docentes</p>
                    <h4 class="my-1" id="lbl_docentes">0</h4>
                    <!-- <p class="mb-0 font-13 text-success"><i class="bx bxs-up-arrow align-middle"></i>$34 from last week</p> -->
                  </div>
                  <div class="widgets-icons bg-light-success text-success ms-auto"><i class="bx bx-package"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>         

          

          <div class="col-4" id="pnl_comunidad">
            <div class="card radius-10">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div>
                    <p class="mb-0 text-secondary">Comunidad</p>
                    <h4 class="my-1" id="lbl_comunidad">0</h4>
                    <!-- <p class="mb-0 font-13 text-success"><i class="bx bxs-up-arrow align-middle"></i>$34 from last week</p> -->
                  </div>
                  <div class="widgets-icons bg-light-success text-primary ms-auto"><i class="bx bx-package"></i>
                  </div>
                </div>
              </div>
            </div>
          </div> 
      <?php } ?>


      <?php if($_SESSION['INICIO']['TIPO']=='DBA'  || strtoupper($_SESSION['INICIO']['TIPO'])=='MEDICO'  || strtoupper($_SESSION['INICIO']['TIPO'])=='ENFERMERA'  || strtoupper($_SESSION['INICIO']['TIPO'])=='ADMINISTRADOR') { ?>
        <h6 class="mb-0 text-uppercase">Pacientes</h6>
        <hr>
        <div class="row">
        	<div class="col-4" id="pnl_pacientes">
	            <div class="card radius-10">
	              <div class="card-body">
	                <div class="d-flex align-items-center">
	                  <div>
	                    <p class="mb-0 text-secondary">Pacientes</p>
	                    <h4 class="my-1" id="lbl_pacientes">0</h4>
	                    <!-- <p class="mb-0 font-13 text-success"><i class="bx bxs-up-arrow align-middle"></i>$34 from last week</p> -->
	                  </div>
	                  <div class="widgets-icons bg-light-success text-primary ms-auto"><i class="bx bx-package"></i>
	                  </div>
	                </div>
	              </div>
	            </div>
	          </div>       
	          <div class="col-4" id="pnl_consultas">
	            <div class="card radius-10">
	              <div class="card-body">
	                <div class="d-flex align-items-center">
	                  <div>
	                    <p class="mb-0 text-secondary">Atenciones </p>
	                    <h4 class="my-1" id="lbl_consultas">0</h4>
	                    <!-- <p class="mb-0 font-13 text-success"><i class="bx bxs-up-arrow align-middle"></i>$34 from last week</p> -->
	                  </div>
	                  <div class="widgets-icons bg-light-success text-primary ms-auto"><i class="bx bx-package"></i>
	                  </div>
	                </div>
	              </div>
	            </div>
	          </div>         
	          <div class="col-4" id="pnl_agenda">
	            <div class="card radius-10">
	              <div class="card-body">
	                <div class="d-flex align-items-center">
	                  <div>
	                    <p class="mb-0 text-secondary">Agenda</p>
	                    <h4 class="my-1" id="lbl_agenda">0</h4>
	                    <!-- <p class="mb-0 font-13 text-success"><i class="bx bxs-up-arrow align-middle"></i>$34 from last week</p> -->
	                  </div>
	                  <div class="widgets-icons bg-light-success text-primary ms-auto"><i class="bx bx-package"></i>
	                  </div>
	                </div>
	              </div>
	            </div>
	          </div>
            <div class="col-6">
              <div class="card">
              <div class="card-body">
                <div id="chart8"></div>
              </div>
            </div>
            </div>         	
        </div>        
       <?php } ?>


      <?php if($_SESSION['INICIO']['TIPO']=='DBA'  || strtoupper($_SESSION['INICIO']['TIPO'])=='COMUNIDAD'  || strtoupper($_SESSION['INICIO']['TIPO'])=='ENFERMERA'  || strtoupper($_SESSION['INICIO']['TIPO'])=='DOCTOR'  || strtoupper($_SESSION['INICIO']['TIPO'])=='ADMINISTRADOR') { ?>

        <h6 class="mb-0 text-uppercase">Farmacia</h6>
        <hr>
        <div class="row" id="pnl_alertas_farmacia">
          
        </div>
        <div class="row">
        	<div class="col-6"  id="pnl_medicamentos">
	            <div class="card radius-10">
	              <div class="card-body">
	                <div class="d-flex align-items-center">
	                  <div>
	                    <p class="mb-0 text-secondary">Medicamentos</p>
	                    <h4 class="my-1" id="lbl_medicamentos">0</h4>
	                    <!-- <p class="mb-0 font-13 text-success"><i class="bx bxs-up-arrow align-middle"></i>$34 from last week</p> -->
	                  </div>
	                  <div class="widgets-icons bg-light-success text-primary ms-auto"><i class="bx bx-package"></i>
	                  </div>
	                </div>
	              </div>
	            </div>
	          </div> 
	         <div class="col-6" id="pnl_insumos">
	            <div class="card radius-10">
	              <div class="card-body">
	                <div class="d-flex align-items-center">
	                  <div>
	                    <p class="mb-0 text-secondary">Insumos</p>
	                    <h4 class="my-1" id="lbl_insumos">0</h4>
	                    <!-- <p class="mb-0 font-13 text-success"><i class="bx bxs-up-arrow align-middle"></i>$34 from last week</p> -->
	                  </div>
	                  <div class="widgets-icons bg-light-success text-primary ms-auto"><i class="bx bx-package"></i>
	                  </div>
	                </div>
	              </div>
	            </div>
	          </div>
	          <div class="col-6">
	          	<div class="card">
      					<div class="card-body">
      						<div id="chartMed"></div>
      					</div>
      				</div>	          	
	          </div> 
            <div class="col-6">
              <div class="card">
                <div class="card-body">
                  <div id="chartIns"></div>
                </div>
              </div>              
            </div> 
        	
        </div>
 <?php } ?>







           
          </div>
        </div>
      </div>
    </div>
    <!--end row-->
  </div>
</div>


  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">HOME</h1>
          </div><!-- /.col -->
         
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">



        <div class="row">
          <!-- <img src="../img/de_sistema/modulo_inventario1.gif" style="width: 100%"> -->
        </div>
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>


	<script src="../assets/plugins/apexcharts-bundle/js/apexcharts.min.js"></script>
<script type="text/javascript">
   function lista_medicamentos_chart(data,cate)
    {
      var options = {
        series: [{
          data: data
        }],
        chart: {
          foreColor: '#9ba7b2',
          type: 'bar',
          height: 350
        },
        colors: ["#0dfd64"],
        plotOptions: {
          bar: {
            horizontal: true,
            columnWidth: '35%',
            endingShape: 'rounded'
          }
        },
        dataLabels: {
          enabled: false
        },
        xaxis: {
          categories: cate,
        },
        title: {
            text: 'Stock de Medicamentos',
            align: 'center',            
        }
      };
      var chart = new ApexCharts(document.querySelector("#chartMed"), options);
      chart.render();

    }
  function lista_insumos_chart(data,cate)
    {
      var options = {
        series: [{
          data: data
        }],
        chart: {
          foreColor: '#9ba7b2',
          type: 'bar',
          height: 350
        },
        colors: ["#0d6efd"],
        plotOptions: {
          bar: {
            horizontal: true,
            columnWidth: '35%',
            endingShape: 'rounded'
          }
        },
        dataLabels: {
          enabled: false
        },
        xaxis: {
          categories: cate,
        },title: {
            text: 'Stock de Insumos',
            align: 'center',            
        }
      };
      var chart = new ApexCharts(document.querySelector("#chartIns"), options);
      chart.render();
    }

    function pie(tipo,cant) {
      var options = {
        series: cant,
        chart: {
          foreColor: '#9ba7b2',
          height: 330,
          type: 'pie',
        },title: {
            text: 'Pacientes Atendidos',
            align: 'center',            
        },
        colors: ["#0d6efd", "#6c757d", "#17a00e", "#f41127", "#ffc107","#0d5efd", "#6c767d", "#17a10e", "#f41327", "#ffc207"],
        labels: tipo,
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              height: 360
            },
            legend: {
              position: 'bottom'
            }
          }
        }]
      };
      var chart = new ApexCharts(document.querySelector("#chart8"), options);
      chart.render();

  }
	
</script>
 
<?php //include('../cabeceras/footer.php'); ?>


