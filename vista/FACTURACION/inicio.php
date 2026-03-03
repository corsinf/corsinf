<?php


if ($_GET['acc'] == 'lista_facturas') {
    include('FACTURACION/lista_facturas.php');
}
if ($_GET['acc'] == 'lista_productos') {
    include('FACTURACION/lista_productos.php');
}
if ($_GET['acc'] == 'detalle_articulos') {
    include('FACTURACION/detalle_articulos.php');
}
if ($_GET['acc'] == 'detalle_factura') {
    include('FACTURACION/detalle_factura.php');
}

if ($_GET['acc'] == 'cliente_factura') {
    include('FACTURACION/cliente_factura.php');
}

?>