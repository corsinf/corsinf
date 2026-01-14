<?php @session_start();
// if (!isset($_SESSION['INICIO'])) {
//   header('Location: ../login.php');
// }

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!--favicon-->
  <link rel="icon" href="../assets/images/favicon-32x32.png" type="image/png" />
  <!--plugins-->
  <link href="../assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
  <link href="../assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
  <link href="../assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />

  <link href="../assets/plugins/select2/css/select2.min.css" rel="stylesheet" />
  <link href="../assets/plugins/select2/css/select2-bootstrap4.css" rel="stylesheet" />
  <!-- loader-->
  <link href="../assets/css/pace.min.css" rel="stylesheet" />
  <script src="../assets/js/pace.min.js"></script>
  <script src="../assets/js/jquery.min.js"></script>
  <!-- Bootstrap CSS -->
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assets/css/bootstrap-extended.css" rel="stylesheet">
  <link href="../assets/css/app.css" rel="stylesheet">
  <link href="../assets/css/icons.css" rel="stylesheet">
  <!-- Theme Style CSS -->
  <link rel="stylesheet" href="../assets/css/dark-theme.css" />
  <link rel="stylesheet" href="../assets/css/semi-dark.css" />
  <link rel="stylesheet" href="../assets/css/header-colors.css" />

  <link rel="stylesheet" href="../assets/plugins/summernote/summernote-lite.css">
  <!-- <link rel="stylesheet" href="../assets/plugins/summernote/css/styles_summernote.css"> -->
  <link rel="stylesheet" href="../assets/plugins/summernote/summernote-bs5.min.css">
  <!-- <link rel="stylesheet" href="../assets/plugins/summernote/css/font-awesome.min.css"> -->

  <script src="../js/codigos_globales.js"></script>
  <script src="../js/sweetalert2.all.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      // restriccion();

    });

    function cerrar_session() {

      $.ajax({
        // data:  {parametros:parametros},
        url: '../controlador/loginC.php?cerrar=true',
        type: 'post',
        dataType: 'json',
        /*beforeSend: function () {   
             var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
           $('#tabla_').html(spiner);
        },*/
        success: function(response) {
          if (response == 1) {
            console.log(response);
            window.location.href = "../login.php";
          }
        }

      });
    }
  </script>

</head>

<body class="hold-transition sidebar-mini layout-fixed">

  <div class="wrapper">
    <input type="hidden" name="" id="dba">
    <input type="hidden" name="" id="ver">
    <input type="hidden" name="" id="editar">
    <input type="hidden" name="" id="eliminar">