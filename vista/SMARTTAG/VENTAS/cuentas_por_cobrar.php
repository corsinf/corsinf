<script type="text/javascript">
    $(document).ready(function() {
        facturas_por_pagar();
        facturas_pagagadas();
        autocoplet_tipo_pago();
        autocoplet_cliente();
    });


    function autocoplet_cliente() {
        $('#ddl_clientes').select2({
            placeholder: 'Seleccione una familia',
            width: '90%',
            ajax: {
                url: '../controlador/cuentas_x_cobrarC.php?search_cliente=true',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    // console.log(data);
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

    }

    function autocoplet_tipo_pago() {
        $('#ddl_tipo_pago').select2({
            placeholder: 'Seleccione una familia',
            width: '90%',
            ajax: {
                url: '../controlador/cuentas_x_cobrarC.php?tipo_pago=true',
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    // console.log(data);
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
    }

    function facturas_por_pagar() {
        var id = $('#ddl_clientes').val();
        var parametros = {
            'id': id,
        }
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/cuentas_x_cobrarC.php?facturas_por_pagar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                $('#tbl_facturas').html(response);
            }

        });
    }

    function facturas_pagagadas() {
        var id = $('#ddl_clientes').val();
        var parametros = {
            'id': id,
        }
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/cuentas_x_cobrarC.php?facturas_pagadas=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                $('#tbl_facturas_pagadas').html(response);
            }

        });
    }


    function abonos_a_factura(id) {
        var parametros = {
            'id': id,
        }
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/cuentas_x_cobrarC.php?abonos_tabla=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                // console.log(response);
                $('#tbl_abonos').html(response.tabla);
                $('#tbl_cuotas').html(response.tabla_cuotas);
                $('#txt_total_abono').val(response.total_abono);
                $('#txt_total_factura').val(response.total);
                $('#txt_restante_factura').val(response.faltante);
            }

        });
    }



    function limpiar_ddl_cli() {
        $('#ddl_clientes').empty();
        facturas_por_pagar();
        facturas_pagagadas();

    }

    function Agregar_Abono(id) {
        $('#nuevo_abono').modal('show');
        $('#id_fac').val(id);
        abonos_a_factura(id);
    }

    function ingresar_abono() {
        var total_abo = $('#txt_total_abono').val();
        var total_fac = $('#txt_total_factura').val();
        var total_res = $('#txt_restante_factura').val();
        var tip = $('#ddl_tipo_pago').val();
        var mon = $('#txt_monto').val();
        var comp = $('#txt_cheq_comp').val();
        var fec = $('#txt_fecha_abono').val();
        var id = $('#id_fac').val();
        var ban = $('#txt_banco').val();
        if (mon == '' || !is_numeric(mon)) {
            Swal.fire('', 'Monto invalido.', 'info');
            return false;
        }
        var t = tip.split('_');
        if (t[1] == '1' && comp == '') {
            Swal.fire('', 'Ingrese numero de comprobante o cheque.', 'info');
            return false;
        }

        if (parseFloat(mon) > parseFloat(total_fac)) {
            Swal.fire('', 'El monto no debe superaral total de la factura.', 'info');
            return false;
        }
        if (tip == '') {
            Swal.fire('', 'Seleccione tipo de pago.', 'info');
            return false;
        }


        var parametros = {
            'fecha': fec,
            'monto': mon,
            'cheqcomp': comp,
            'pago': $('#ddl_tipo_pago option:selected').text(),
            'fac': id,
            'falt': total_res - mon,
            'banco': ban,
        }
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/cuentas_x_cobrarC.php?add_abono=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Abono agregado.', 'success');
                    $('#txt_monto').val('');
                    $('#txt_cheq_comp').val('');
                    $('#ddl_tipo_pago').empty();
                    abonos_a_factura(id);
                    facturas_pagagadas();
                    facturas_por_pagar();
                } else if (response == 2) {
                    Swal.fire('', 'Factura cancelada en su totalidad.', 'success');
                    $('#txt_monto').val('');
                    $('#txt_cheq_comp').val('');
                    $('#ddl_tipo_pago').empty();
                    abonos_a_factura(id);
                    facturas_pagagadas();
                    facturas_por_pagar();
                    $('#nuevo_abono').modal('hide');
                } else {
                    Swal.fire('', 'No se pudo agregar.', 'error');
                }
            }

        });



    }

    function habilitar_cheq_comp() {
        var tip = $('#ddl_tipo_pago').val();
        var t = tip.split('_');
        if (t[1] == 0) {
            $('#txt_cheq_comp').attr('readonly', true);
            $('#txt_cheq_comp').val('');

            $('#txt_banco').attr('readonly', true);
            $('#txt_banco').val('');

        } else {
            $('#txt_cheq_comp').attr('readonly', false);
            $('#txt_banco').attr('readonly', false);
        }
    }

    function Eliminar_abono(id) {
        var idfac = $('#id_fac').val();
        Swal.fire({
            title: 'Desea eliminar este abono',
            text: "Esta seguro de eliminar el abono!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    data: {
                        id: id
                    },
                    url: '../controlador/cuentas_x_cobrarC.php?eliminar_abono=true',
                    type: 'post',
                    dataType: 'json',
                    success: function(response) {
                        abonos_a_factura(idfac);
                        facturas_pagagadas();
                        facturas_por_pagar();
                    }

                });
            }
        });
    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Cuentas por cobrar</div>
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
                            <h5 class="mb-0 text-primary"></h5>
                        </div>
                        <section class="content">
                            <div class="container-fluid">
                                <div class="row mb-3">
                                    <div class="col-sm-4">
                                        <b>Cliente</b>
                                        <div class="input-group input-group-sm">
                                            <select class="form-control form-control-sm" id="ddl_clientes" onchange="facturas_por_pagar();facturas_pagagadas()">
                                                <option value="">Seleccione cliente</option>
                                            </select>
                                            <button type="button" class="btn btn-primary btn-sm" onclick="limpiar_ddl_cli()">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-sm-8"></div> <!-- Espaciado vacío -->
                                </div>

                                <hr>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">
                                            <div class="card-header p-2">
                                                <ul class="nav nav-pills">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" id="facturas_por_pagar" data-bs-toggle="pill" href="#todas">Facturas por pagar</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="facturas_pagadas" data-bs-toggle="pill" href="#finalizadas">Facturas pagadas</a>
                                                    </li>
                                                </ul>
                                            </div><!-- /.card-header -->
                                            <div class="card-body" style="padding: 3px;">
                                                <div class="tab-content">
                                                    <div class="tab-pane fade show active" id="todas">
                                                        <div id="tbl_facturas" class="col-sm-12"></div>
                                                    </div>
                                                    <!-- /.tab-pane -->
                                                    <div class="tab-pane fade" id="finalizadas">
                                                        <div id="tbl_facturas_pagadas" class="col-sm-12"></div>
                                                    </div>
                                                </div><!-- /.tab-content -->
                                            </div><!-- /.card-body -->
                                        </div><!-- /.card -->
                                    </div>
                                </div><!-- /.row -->
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