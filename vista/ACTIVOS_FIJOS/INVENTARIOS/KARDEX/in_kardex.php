<script>
    $(document).ready(function() {
        ac_movimiento();
    });

    function ac_movimiento() {
        $.ajax({
            // data:  {id:id},
            url: '../controlador/ACTIVOS_FIJOS/ac_movimientoC.php?listar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {

                console.log(response);
            }
        });
    }
</script>

<script type="text/javascript">
    let tablaKardex;
    $(document).ready(function() {
        tablaKardex = $('#listaKardex').DataTable({
            ajax: {
                url: '../controlador/ACTIVOS_FIJOS/INVENTARIOS/in_kardexC.php?Listatabla=true',
                type: "POST",
                data: function(d) {
                    var parametros = {
                        desde: $('#txt_desde').val(),
                        hasta: $('#txt_hasta').val()
                    };
                    return {
                        parametros: parametros
                    };
                },
                dataSrc: ''
            },
            columns: [{
                    data: 'in_kar_codigo_referencia'
                },
                {
                    data: 'descripcion'
                },
                {
                    data: 'in_kar_fecha'
                },
                {
                    data: 'in_kar_salida'
                },
                {
                    data: 'in_kar_entrada'
                },
                {
                    data: 'in_kar_valor_unitario'
                },
                {
                    data: 'in_kar_valor_total'
                },
                {
                    data: 'in_kar_orden_no'
                },
                {
                    data: 'apellidos'
                },
                {
                    data: 'id_usuarios'
                },
            ],
            dom: '<"d-flex justify-content-between mb-2"fB>rtip', // Define la posición de los botones
            buttons: [
                'excelHtml5',
                'pdfHtml5',
                'csvHtml5',
                'print'
            ],
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            }
        });

    });

    function Listatabla() {
        if ($.fn.DataTable.isDataTable('#listaKardex')) {
            tablaKardex.ajax.reload(); // Si ya existe, recarga los datos
        }
    }


    function Listatabla2() {
        $.ajax({
            // data:  {id:id},
            url: '../controlador/ACTIVOS_FIJOS/reportesC.php?informes_activos=true',
            type: 'post',
            dataType: 'json',
            /*beforeSend: function () {   
                 var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
               $('#tabla_').html(spiner);
            },*/
            success: function(response) {

                $('#ddl_informes').html(response);
            }
        });
    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Kardex</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Kardex
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
                        <h5 class="mb-0 text-primary"></h5>

                        <!--  <div class="row mb-3">
                            <div class="col-sm-12" id="btn_nuevo">

                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal_Kardex"><i class="bx bx-plus"></i> Nuevo</button>

                            </div>
                        </div> -->
                        <div class="row">
                            <div class="col-sm-2">
                                <b>Desde</b>
                                <input type="date" class="form-control form-control-sm" name="txt_desde" id="txt_desde" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col-sm-2">
                                <b>Hasta</b>
                                <input type="date" class="form-control form-control-sm" name="txt_hasta" id="txt_hasta" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <!-- <div class="col-sm-2">
                                <input type="date" class="form-control form-control-sm" name="" id="" value="">
                            </div>  
                            <div class="col-sm-2">
                                <input type="date" class="form-control form-control-sm" name="" id="" value="">
                            </div> -->
                            <div class="col-sm-8 text-end">
                                <br>
                                <button class="btn btn-primary btn-sm" onclick="Listatabla()">Buscar</button>
                            </div>
                        </div>


                        <section class="content pt-2">
                            <div class="container-fluid">
                                <div class="table-responsive">
                                    <table class="table table-striped responsive " id="listaKardex" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Referencia</th>
                                                <th>Articulo</th>
                                                <th>Fecha</th>
                                                <th>Salida</th>
                                                <th>Entrada</th>
                                                <th>Valor</th>
                                                <th>Total</th>
                                                <th>Orden</th>
                                                <th>Responsable</th>
                                                <th width="10px">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
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
                        <label for="">Kardex <label class="text-danger">*</label></label>
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