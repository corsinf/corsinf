<script type="text/javascript">
    $(document).ready(function() {
        transacciones();
        autocoplet_usuario();
        autocoplet_bodega();
        autocoplet_bodegas_entrada();
        autocoplet_tipo_tran();

    });


    function transacciones(query = '') {
        var parametros = {
            'desde': $('#txt_desde').val(),
            'hasta': $('#txt_hasta').val(),
            'usu': $('#ddl_usuario').val(),
            'tipo': $('#ddl_tipo_tran').val(),
            'salida': $('#ddl_salida').val(),
            'entrada': $('#ddl_entrada').val(),


        }
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/transaccionesC.php?transacciones=true',
            type: 'post',
            dataType: 'json',
            /*beforeSend: function () {   
                 var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
               $('#tabla_').html(spiner);
            },*/
            success: function(response) {
                if (response != "") {
                    $('#transacciones').html(response);
                }
            }

        });
    }

    function autocoplet_usuario() {
        $('#ddl_usuario').select2({
            placeholder: 'Seleccione una familia',
            width: '90%',
            ajax: {
                url: '../controlador/usuariosC.php?lista_usuarios_ddl=true',
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

    function autocoplet_tipo_tran() {
        $('#ddl_tipo_tran').select2({
            placeholder: 'Seleccione una familia',
            width: '90%',
            ajax: {
                url: '../controlador/transaccionesC.php?ddl_tipo_transaccion=true',
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

    function autocoplet_bodega() {
        $('#ddl_salida').select2({
            placeholder: 'Seleccione una familia',
            width: '90%',
            ajax: {
                url: '../controlador/bodegasC.php?lista_ddl_bodega=true',
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


    function autocoplet_bodegas_entrada() {
        $('#ddl_entrada').select2({
            placeholder: 'Seleccione una familia',
            width: '90%',
            ajax: {
                url: '../controlador/bodegasC.php?lista_ddl_bodega=true',
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






    function new_usuario() {

        if ($('#txt_nombre_new').val() == '' || $('#txt_ci_new').val() == '' || $('#txt_telefono').val() == '' || $('#txt_emial').val() == '' || $('#txt_dir').val() == '') {
            Swal.fire('', 'Llene todo los campos.', 'info');
            return false;
        }

        var datos = $('#form_usuario_new').serialize();
        $.ajax({
            data: datos,
            url: '../controlador/transaccionesC.php?new_usuario=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Nuevo cliente registrado.', 'success');
                    $('#nuevo_cliente').modal('hide');
                    limpiar();
                    transacciones();
                } else {
                    Swal.fire('', 'UPs aparecio un problema', 'success');
                    $('#nuevo_cliente').modal('hide');
                }

            }

        });
    }

    // function Transacciones(id)
    // {
    //   $('#txt_id_transacciones').val(id);
    //   $('#transacciones_cliente').modal('show');
    //    $.ajax({
    //        data:  {id,id},
    //        url:   '../controlador/transaccionesC.php?transacciones=true',
    //        type:  'post',
    //        dataType: 'json',
    //          success:  function (response) { 
    //           $('#tbl_facturas').html(response.factura);
    //           $('#tbl_cotizaciones').html(response.pedido);

    //          } 

    //      });


    // }
    function limpiar() {
        $('#txt_nombre_new').val('');
        $('#txt_ci_new').val('');
        $('#txt_dir').val('');
        $('#txt_emial').val('');
        $('#txt_telefono').val('');
        $('#txt_credito').val('');
        $('#txt_id').val('');
    }

    function Editar(id) {
        $('#txt_id').val(id);
        $.ajax({
            data: {
                id,
                id
            },
            url: '../controlador/transaccionesC.php?ficha_usuario=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                $('#txt_nombre_new').val(response.nombre);
                $('#txt_ci_new').val(response.ci);
                $('#txt_dir').val(response.direccion);
                $('#txt_emial').val(response.email);
                $('#txt_telefono').val(response.telefono);
                $('#txt_credito').val(response.credito);
                $('#nuevo_cliente').modal('show');
                // console.log(response);
            }

        });

    }

    function Eliminar(id) {
        Swal.fire({
            title: 'Quiere eliminar este registro?',
            text: "Esta seguro de eliminar este registro!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si'
        }).then((result) => {
            if (result.value) {

                $.ajax({
                    data: {
                        id,
                        id
                    },
                    url: '../controlador/transaccionesC.php?delete_usuario=true',
                    type: 'post',
                    dataType: 'json',
                    success: function(response) {
                        if (response == 1) {
                            Swal.fire('', 'Cliente eliminado', 'success');
                            transacciones();
                        } else if (response == -2) {
                            Swal.fire({
                                title: 'Este cliente tiene Facturas asociadas y no se podrta eliminar',
                                text: "Desea inhabilitado a este cliente?",
                                showDenyButton: true,
                                showCancelButton: true,
                                confirmButtonText: 'Si!',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    inhabilitar_usuario(id);
                                }
                            })
                            // Swal.fire('','El Usuario esta ligado a uno o varios registros y no se podra eliminar.','error')
                        } else {
                            Swal.fire('', 'Eno se pudo eliminar', 'info');
                        }
                    }

                });
            }
        });

    }

    function inhabilitar_usuario(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/transaccionesC.php?cliente_estado=true',
            type: 'post',
            dataType: 'json',
            /*beforeSend: function () {   
                 var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
               $('#tabla_').html(spiner);
            },*/
            success: function(response) {
                if (response == 1) {
                    transacciones();
                    transacciones_inactivos();
                    Swal.fire('El cliente  se a inhabilitado!', 'El cliente no podra ser seleccionado en futuras compras o ventas', 'success');

                } else {
                    Swal.fire('', 'UPs aparecio un problema', 'success');
                }

            }

        });

    }

    function Activar(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/transaccionesC.php?cliente_activar=true',
            type: 'post',
            dataType: 'json',
            /*beforeSend: function () {   
                 var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
               $('#tabla_').html(spiner);
            },*/
            success: function(response) {
                if (response == 1) {
                    transacciones();
                    transacciones_inactivos();
                    Swal.fire('El cliente  se a habilitado!', '', 'success');

                } else {
                    Swal.fire('', 'UPs aparecio un problema', 'success');
                }

            }

        });

    }

    function Visualizar_Factura(id, doc, estado, punto) {
        var url = "Facturacion.php?numfac=" + id + "&doc=" + doc + '&est=' + estado + '&pnt=' + punto;
        $(location).attr('href', url);
    }


    function Visualizar_Cotizacion(id, doc, estado, punto) {
        var url = "presupuestos.php?numfac=" + id + "&doc=" + doc + '&est=' + estado + '&pnt=' + punto;
        $(location).attr('href', url);

    }

    function Ver(id, tran) {
        var url = '../controlador/transaccionesC.php?ver_transacciones=true&id=' + id + '&tran=' + tran
        $('#informe_pdf').html("<iframe src=" + url + '#zoom=90' + " width='100%' height='500px' frameborder='0' allowfullscreen></iframe>");
        $('#transacciones_vista').modal('show');
    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Transacciones</div>
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
            <div class="card-body p-4">
                
                <!-- Filtros -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="row g-3 align-items-end border-bottom pb-3">
                            <div class="col-md-2">
                                <label for="txt_desde" class="form-label"><b>Desde:</b></label>
                                <input type="date" class="form-control" id="txt_desde" onchange="transacciones()">
                            </div>
                            <div class="col-md-2">
                                <label for="txt_hasta" class="form-label"><b>Hasta:</b></label>
                                <input type="date" class="form-control" id="txt_hasta" onchange="transacciones()">
                            </div>
                            <div class="col-md-2">
                                <label for="ddl_usuario" class="form-label"><b>Usuario:</b></label>
                                <select class="form-select" id="ddl_usuario" onchange="transacciones()">
                                    <option value="">Seleccione usuario</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="ddl_tipo_tran" class="form-label"><b>Tipo de movimiento:</b></label>
                                <select class="form-select" id="ddl_tipo_tran" onchange="transacciones()">
                                    <option value="">Seleccione tipo</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="ddl_salida" class="form-label"><b>Bodega Salida:</b></label>
                                <select class="form-select" id="ddl_salida" onchange="transacciones()">
                                    <option value="">Seleccione salida</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="ddl_entrada" class="form-label"><b>Bodega Entrada:</b></label>
                                <select class="form-select" id="ddl_entrada" onchange="transacciones()">
                                    <option value="">Seleccione entrada</option>
                                </select>
                            </div>
                        </div>

                        <input type="hidden" id="txt_tipo" value="C">

                        <!-- Contenido dinámico -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <div id="transacciones"></div>
                            </div>
                        </div>
                    </div>
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