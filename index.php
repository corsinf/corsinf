<?php
  @session_start();
if(isset($_SESSION['INICIO']))
{
  echo '<script type="text/javascript">  location.href = "modulos_sistema.php";  </script>';
}else
{

  echo '<script type="text/javascript">  location.href = "login.php";  </script>';
}
?>
