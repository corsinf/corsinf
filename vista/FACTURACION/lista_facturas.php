<?php 
$mod = $_GET['mod']; 
?>
<script type="text/javascript">
    var modulo = '<?php echo $mod; ?>'
</script>
<script type="text/javascript">
    $(document).ready(function () {
     series();
     cargar_facturas();
});

</script>
<script src="../js/FACTURACION/lista_facturas.js"></script>		

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Documentos</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Lista de facturas
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
        <div class="row">
            <div class="card shadow mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <b>Cliente</b>
                            <input type="text" name="txt_query" id="txt_query" class="form-control form-control-sm" placeholder="Cliente" onkeyup="cargar_facturas()">
                        </div>
                        <div class="col-sm-2">
                            <b>No Factura</b>
                            <input type="text" name="txt_num_fac" id="txt_num_fac" class="form-control form-control-sm" placeholder="No factura" onkeyup="cargar_facturas()">
                        </div>
                        <div class="col-sm-2">
                            <b>Desde</b>
                            <input type="date" name="txt_desde" id="txt_desde" class="form-control form-control-sm" value="<?php echo date('Y-m-d');?>" onblur="cargar_facturas()">
                        </div>
                        <div class="col-sm-2">
                            <b>Hasta</b>
                            <input type="date" name="txt_hasta" id="txt_hasta" class="form-control form-control-sm" onblur="cargar_facturas()">
                        </div>
                        <div class="col-sm-2">
                            <b>Serie</b>
                            <select class="form-control form-control-sm" id="ddl_serie" name="ddl_serie" onchange="cargar_facturas()">
                                <option value="">Serie</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="card shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <table class="table table-sm" id="dataTable">
                                <thead>                            
                                    <th width="15%"></th>
                                    <th>Cliente</th>
                                    <th>Fecha</th>
                                    <th>Factura</th>
                                    <th>Serie</th>
                                    <th class="text-right">Total</th>
                                    <th width="8%">Estado</th>
                                </thead>
                                <tbody id="lista_facturas">
                                    
                                </tbody>
                            </table>
                        </div>
                        
                    </div>
                </div>
            </div>      
        </div>
    </div>
</div>