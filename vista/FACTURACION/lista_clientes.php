<?php 
$mod = $_GET['mod']; 
?>

<script src="../js/FACTURACION/lista_clientes.js"></script>     
<script type="text/javascript">
    var modulo = '<?php echo $mod; ?>'
</script>
<script type="text/javascript">
    $(document).ready(function (){
         lista_cliente()
    });

</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Clientes / Proveedores</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Clientes
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-2">
                <a class="btn btn-success btn-sm" href="./inicio.php?mod=<?php echo $_GET['mod']; ?>&acc=cliente_factura&tipo=FA"><i class="bx bx-plus"></i> Nuevo</a>
            </div>
        </div>
        <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-4" id="pnl_lista_cliente">
                    
                   
                    
        </div>
    </div>
</div>