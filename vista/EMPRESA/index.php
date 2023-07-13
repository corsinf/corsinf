<?php include('../../cabeceras/header.php'); ?> 
 <script type="text/javascript">
  $( document ).ready(function() {
    usuarios();
    patrimoniales();
    bajas();
    terceros();
    articulos();
    custodio();
    localizacion();
    datos_seguros();


    custodio_des();
    localizacion_des();

  });


    function pie(sin,con) {
       var donutData        = {
      labels: [
          'Asegurados', 
          'Sin seguro',
          
      ],
      datasets: [
        {
          data: [con,sin],
          backgroundColor : ['#00a65a','#f56954'],
        }
      ]
    }
   
    //-------------
    //- PIE CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.
    var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
    var pieData        = donutData;
    var pieOptions     = {
      maintainAspectRatio : false,
      responsive : true,
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    var pieChart = new Chart(pieChartCanvas, {
      type: 'pie',
      data: pieData,
      options: pieOptions      
    })

  }


    function usuarios()
    {

       $.ajax({
         // data:  {parametros:parametros},
         url:   '../../controlador/usuariosC.php?usuarios=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {  
            var res = response.length;
            $('#lbl_usuarios').text(res);
          
          }
       });
    }

    function custodio()
    {

       $.ajax({
         // data:  {parametros:parametros},
         url:   '../../controlador/custodioC.php?numero_custodios=true',
         type:  'post',
         dataType: 'json',
           success:  function (response) {  
            console.log(response);
            $('#lbl_custodios').text(response[0]['cant']);
          
          }
       });
    }

    function localizacion()
    {

       $.ajax({
         // data:  {parametros:parametros},
         url:   '../../controlador/localizacionC.php?numero_localizaciones=true',
         type:  'post',
         dataType: 'json',
           success:  function (response) {  
            console.log(response);
            $('#lbl_localizaciones').text(response[0]['cant']);
          
          }
       });
    }



    function custodio_des()
    {

       $.ajax({
         // data:  {parametros:parametros},
         url:   '../../controlador/vinculacionC.php?numero_custodios=true',
         type:  'post',
         dataType: 'json',
           success:  function (response) {  
            console.log(response);
            if(response.length>0)
            {
              $('#lbl_custodios').text(response[0]['cant']);
            }
          }
       });
    }

    function localizacion_des()
    {

       $.ajax({
         // data:  {parametros:parametros},
         url:   '../../controlador/vinculacionC.php?numero_localizaciones=true',
         type:  'post',
         dataType: 'json',
           success:  function (response) {  
            console.log(response);
            if(response.length>0)
            {
              $('#lbl_localizaciones').text(response[0]['cant']);
            }
          
          }
       });
    }

    function patrimoniales()
    { 
      var parametros = 
      {
        'bajas':0,
        'terceros':0,
        'patrimoniales':1,
        'articulos':0,
      }
        $.ajax({
         data:  {parametros:parametros},
         url:   '../../controlador/articulosC.php?articulos_especiales=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {  
            var res = response.length;
            $('#lbl_patrimoniales').text(res);
            // console.log(res)
          
          } 
          
       });

    }

    function bajas()
    { var parametros = 
      {
        'bajas':1,
        'terceros':0,
        'patrimoniales':0,
        'articulos':0,
      }
        $.ajax({
         data:  {parametros:parametros},
         url:   '../../controlador/articulosC.php?articulos_especiales=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {  
            var res = response.length;
            $('#lbl_bajas').text(res);
            // console.log(res)
          
          } 
          
       });

    }

    function terceros()
    { var parametros = 
      {
        'bajas':0,
        'terceros':1,
        'patrimoniales':0,
        'articulos':0,
      }
        $.ajax({
         data:  {parametros:parametros},
         url:   '../../controlador/articulosC.php?articulos_especiales=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {  
            var res = response.length;
            $('#lbl_terceros').text(res);
            // console.log(res)
          
          } 
          
       });      
    }

    function articulos()
    {
      var parametros = 
      {
        'bajas':0,
        'terceros':0,
        'patrimoniales':0,
        'articulos':1,
      }
        $.ajax({
         data:  {parametros:parametros},
         url:   '../../controlador/articulosC.php?articulos_especiales=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {  
            var res = response[0]['numreg'];
            var res1 = response[1]['eti'];
            console.log(response);
            $('#lbl_articulos').text(res);
            $('#lbl_articulos1').text(res);
            $('#lbl_etiqueta').text(res1);

            var b = parseInt(res1*100/res);
            $('#lbl_porcen').html('<b>'+b+'</b>/100');
            $('#progres').css('width',b+'%');
            $('#lbl_porce').html('<i class="bx bxs-up-arrow align-middle"></i> '+b+'%');


            console.log(b)
          
          } 
          
       });  
  }    


    function datos_seguros()
    {
     
        $.ajax({
         // data:  {parametros:parametros},
         url:   '../../controlador/contratoC.php?datos_seguros=true',
         type:  'post',
         dataType: 'json',        
           success:  function (response) {  
            console.log(response);
            pie(response.sinseguro,response.asegurados);   

            var sin = ((response.sinseguro*100)/response.total);
            var con = ((response.asegurados*100)/response.total);
            console.log(sin);console.log(con);
            $('#lbl_porce_sin_seguro').html('<i class="fas fa-caret-up">'+sin.toFixed(3)+'%');
            $('#lbl_porce_asegurados').html('<i class="fas fa-caret-up">'+con.toFixed(3)+'%');

            $('#lbl_sin_seguro').text(response.sinseguro);
            $('#lbl_asgurados').text(response.asegurados);
            $('#lbl_articulos2').text(response.total);   
            $('#lbl_num_seguros').text(response.seguros);
          
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
        <hr>
        <div class="row">
          <div class="col-md-12">    
              <div class="card-body">
                <div class="row">
                  <div class="col-md-7">
                    <div class="row">
                        <!-- /.col -->

                      <div class="col-6" onclick="location.href='usuarios.php'">
                        <div class="card radius-10">
                          <div class="card-body">
                            <div class="d-flex align-items-center">
                              <div>
                                <p class="mb-0 text-secondary">Usuarios de sistema</p>
                                <h4 class="my-1" id="lbl_usuarios">0</h4>
                                <!-- <p class="mb-0 font-13 text-success"><i class="bx bxs-up-arrow align-middle"></i>$34 from last week</p> -->
                              </div>
                              <div class="widgets-icons bg-light-success text-primary ms-auto"><i class="bx bx-user"></i>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-6" onclick="location.href='custodio.php'">
                        <div class="card radius-10">
                          <div class="card-body">
                            <div class="d-flex align-items-center">
                              <div>
                                <p class="mb-0 text-secondary">Custodios</p>
                                <h4 class="my-1" id="lbl_custodios">0</h4>
                                <!-- <p class="mb-0 font-13 text-success"><i class="bx bxs-up-arrow align-middle"></i>$34 from last week</p> -->
                              </div>
                              <div class="widgets-icons bg-light-success text-primary ms-auto"><i class="bx bx-user-circle"></i>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-12" onclick="location.href='localizacion.php'">
                        <div class="card radius-10">
                          <div class="card-body">
                            <div class="d-flex align-items-center">
                              <div>
                                <p class="mb-0 text-secondary">Localizaciones / Emplazamiento</p>
                                <h4 class="my-1" id="lbl_localizaciones">0</h4>
                                <!-- <p class="mb-0 font-13 text-success"><i class="bx bxs-up-arrow align-middle"></i>$34 from last week</p> -->
                              </div>
                              <div class="widgets-icons bg-light-success text-primary ms-auto"><i class="bx bx-map"></i>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- /.col -->
                  <div class="col-md-5">
                    <p class="text-center">
                      <strong>Porcentaje de articulos etiquetados</strong>
                    </p>

                    <div class="progress-group">
                      % de articulos etiquetados
                      <span class="float-right" id="lbl_porcen"><b>0</b>/0</span>
                      <div class="progress progress-sm">
                        <div class="progress-bar bg-primary" style="width: 0%" id="progres"></div>
                      </div>
                    </div>

                     <div class="row">
                      <div class="col">
                        <div class="card radius-10">
                          <div class="card-body">
                            <div class="d-flex align-items-center">
                              <div>
                                <p class="mb-0 text-secondary">Total Activos</p>
                                <h4 class="my-1" id="lbl_articulos1">0</h4>
                                <p class="mb-0 font-13 text-success"><i class="bx bx-circle align-middle"></i>100% </p>
                              </div>
                              <div class="widgets-icons bg-light-success text-success ms-auto"><i class="bx bx-package"></i>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                       <div class="col">
                        <div class="card radius-10">
                          <div class="card-body">
                            <div class="d-flex align-items-center">
                              <div>
                                <p class="mb-0 text-secondary">Activos Etiquetados</p>
                                <h4 class="my-1" id="lbl_etiqueta">0</h4>
                                <p class="mb-0 font-13 text-warning" id="lbl_porce"><i class="bx bxs-up-arrow align-middle"></i>0% </p>
                              </div>
                              <div class="widgets-icons bg-light-warning text-success ms-auto"><i class="bx bx-tag"></i>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                    </div>
                  </div>
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
  
<?php include('../../cabeceras/footer.php'); ?>