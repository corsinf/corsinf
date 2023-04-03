<?php @session_start(); if(!isset($_SESSION['INICIO'])){header('Location: ../login.php');}?> 
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Activos Fijos| PUCE</title>
  <link rel="icon" type="image/png" href="../img/de_sistema/puce_logo.png" />

  <script src="../plugins/jquery/jquery.min.js"></script>
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="../plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="../plugins/summernote/summernote-bs4.min.css">

  <link rel="stylesheet" href="../css/select2.min.css">
  <script src="../js/select2.min.js"></script>
  <script src="../js/informes.js"></script>
  <script src="../js/codigos_globales.js"></script>
  <script src="../js/sweetalert2.all.min.js"></script>
  <style>
      .container-iframe {
        position: relative;
        width: 100%;
        height: 500px;
        overflow: hidden;
        padding-top: 62.5%; /* 8:5 Aspect Ratio */
      }

      .responsive-iframe {
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        width: 100%;
        height: 100%;
        border: none;
      }
</style>
   <script type="text/javascript">
    // accesos();
    menu_lateral();
    $( document ).ready(function() {
      restriccion();

    });

    function formatoDate(date)
    {
      var formattedDate = new Date(date); 
      var d = formattedDate.getDate(); 
      var m = formattedDate.getMonth(); 
      m += 1; // javascript months are 0-11
      if(m<10)
      {
        m = '0'+m;
      } 
      var y = formattedDate.getFullYear(); 
      var Fecha = y + "-" + m + "-" + d;
      console.log(Fecha);
      return Fecha;
    }
     function cerrar_session()
  {
    
       $.ajax({
         // data:  {parametros:parametros},
         url:   '../controlador/loginC.php?cerrar=true',
         type:  'post',
         dataType: 'json',
         /*beforeSend: function () {   
              var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
            $('#tabla_').html(spiner);
         },*/
           success:  function (response) {  
           // if (response==1) 
           // {
            // console.log(response);
            // location.href = "../login.php";
            location.reload();
           // } 
          } 
          
       });
  }

  //  function restriccion()
  // {
    
  //      $.ajax({
  //        // data:  {parametros:parametros},
  //        url:   '../controlador/loginC.php?restriccion=true',
  //        type:  'post',
  //        dataType: 'json',
  //        /*beforeSend: function () {   
  //             var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
  //           $('#tabla_').html(spiner);
  //        },*/
  //          success:  function (response) {  
           
  //               $('#dba').val(response.dba);
  //               $('#ver').val(response.ver);
  //               $('#editar').val(response.editar);                
  //               $('#eliminar').val(response.eliminar);
  //               console.log(response);
  //               if($('#ver').val()==1)
  //               {
  //                 $('#btn_nuevo').hide();

  //               }
  //               if(response.dba==1)
  //               {
  //                 $('#btn_nuevo').show();
  //               }
             
          
  //            //window.location.href = "../login.php";

  //         } 
          
  //      });
  // }

   function menu_lateral()
  {
    
       $.ajax({
         url:   '../controlador/loginC.php?menu_lateral=true',
         type:  'post',
         dataType: 'json',
        
           success:  function (response) {  
           
             // console.log(response);
             $('#menu_lateral').html(response);

          } 
          
       });
  }

function num_caracteres(campo,num)
{
  var val = $('#'+campo).val();
  var cant = val.length;
  console.log(cant+'-'+num);

  if(cant>num)
  {
    $('#'+campo).val(val.substr(0,num));
    return false;
  }

}

  </script>

</head>
<body class="hold-transition sidebar-mini layout-fixed">
 
<div class="wrapper">
   <input type="hidden" name="" id="dba">
  <input type="hidden" name="" id="ver">
  <input type="hidden" name="" id="editar">
  <input type="hidden" name="" id="eliminar">

  <!-- Navbar -->
   <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="index.php" class="nav-link">Home</a>
      </li>      
    </ul>
    <ul class="navbar-nav ml-auto">


      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
          <i class="far fa-user"></i>
           <?php echo $_SESSION['INICIO']['USUARIO']; ?>
          </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="../img/de_sistema/puce_logo.png" alt="User Avatar" class="img-size-50 mr-3 img-circle">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                 <?php echo $_SESSION['INICIO']['USUARIO']; ?>
                </h3>
                <p class="text-sm"><?php echo $_SESSION['INICIO']['TIPO']; ?></p>
              </div>
            </div>
            <!-- Message End -->
          </a>

          <div class="dropdown-divider"></div>
           <a href="perfil.php" class="dropdown-item"><i class="fa fa-user-circle mr-2"></i> Perfil</a>         
          <div class="dropdown-divider"></div>
           <button class="dropdown-item" onclick="cerrar_session();">
              <i class="fa fa-door-closed mr-2"></i> Salir
           </button>
        </div>
      </li>
    </ul>

   

    <!-- Right navbar links -->    
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
      <img src="../img/de_sistema/puce_logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">ACTIVOS</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
           <!-- SidebarSearch Form -->
      <!-- Sidebar Menu -->
      <nav class="mt-2">
         <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false" id="menu_lateral">
         </ul>
       
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
