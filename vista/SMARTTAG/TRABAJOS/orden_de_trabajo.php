<script type="text/javascript">
    $(document).ready(function() {
        cargar_orden();
        //  // restriccion();
        // Lista_clientes();
        // Lista_procesos();

    });

    function cargar_orden() {

        // console.log(parametros);
        $.ajax({
            // data:  {parametros:parametros},
            url: '../controlador/orden_trabajoC.php?ordenes=true',
            type: 'post',
            dataType: 'json',
            /*beforeSend: function () {   
                 var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
               $('#tabla_').html(spiner);
            },*/
            success: function(response) {
                if (response) {
                    $('#tbl_orden').html(response);
                }
            }

        });

    }

    function ver(id, est) {
        var url = 'nueva_orden_trabajo.php?id=' + id + '&estado=' + est;
        $(location).attr('href', url);
    }

    function nueva_orden() {
        $.ajax({
            // data:  {parametros:parametros},
            url: '../controlador/orden_trabajoC.php?new_order=true',
            type: 'post',
            dataType: 'json',
            /*beforeSend: function () {   
                 var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
               $('#tabla_').html(spiner);
            },*/
            success: function(response) {

                // console.log(response);
                ver(response, 'P');
            }

        });


    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Orden trabajo</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Blank
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-5">
                        <div class="card-title d-flex align-items-center">

                            <h5 class="mb-0 text-primary">Orden de Trabajo</h5>
                        </div>

                        <section class="content">
                            <div class="container-fluid">
                                <div class="row mb-3">
                                    <div class="col-sm-12">
                                    <a class="btn btn-primary btn-sm" href="./inicio.php?mod=2014&acc=nueva_orden_trabajo">
                                    <i class="fa fa-plus"></i> Nuevo
                                </a>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12" id="tbl_orden">
                                        <!-- Aquí se llenará la tabla de órdenes -->
                                    </div>
                                </div>
                            </div><!-- /.container-fluid -->
                        </section>

                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>


<div class="modal" id="modal_blank" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">

                <div class="row">
                    <div class="col-12">
                        <label for="">Tipo de <label class="text-danger">*</label></label>
                        <select name="" id="" class="form-select form-select-sm" onchange="">
                            <option value="">Seleccione el </option>
                        </select>
                    </div>
                </div>

                <div class="row pt-3">
                    <div class="col-12">
                        <label for="">Blank <label class="text-danger">*</label></label>
                        <select name="" id="" class="form-select form-select-sm">
                            <option value="">Seleccione el </option>
                        </select>
                    </div>
                </div>

                <div class="row pt-3">
                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-success btn-sm" onclick=""><i class="bx bx-save"></i> Agregar</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>