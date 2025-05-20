<?php 

if ($_GET['acc'] == 'clientes_canal') {
    include('CLIENTES/clientes_canal.php');
}
if ($_GET['acc'] == 'nuevo_cliente_canal') {
    include('CLIENTES/nuevo_cliente_canal.php');
}
?>