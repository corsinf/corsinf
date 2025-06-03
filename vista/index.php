<?php
@session_start();
if (isset($_SESSION['INICIO'])) {
    // print_r($_SESSION['INICIO']);die();
    echo '<script type="text/javascript">  location.href = "./modulos_sistema.php";  </script>';
} else {

    echo '<script type="text/javascript">  location.href = "../login.php";  </script>';
}
