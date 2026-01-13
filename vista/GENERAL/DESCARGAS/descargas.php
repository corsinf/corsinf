<?php


$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);

if ($modulo_sistema == 2) {
  include_once('../vista/GENERAL/DESCARGAS/activos_fijos.php');
}
