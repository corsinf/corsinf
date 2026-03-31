<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);
?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script>
    let tbl_espacio_tarifas;

    $(document).ready(function() {

        tbl_espacio_tarifas = $('#tbl_espacio_tarifas').DataTable({
            responsive: true,
            stateSave: true,
            language: {
                url: '../assets/plugins/datatable/spanish.json'
            },
            ajax: {
                url: '../controlador/HOST_TIME/ESPACIOS/hub_espacios_tarifasC.php?listar=true',
                type: 'POST',
                data: function(d) {
                    d.id_espacio = '<?= $_id ?>';
                },
                dataSrc: ''
            },
            columns: [{
                    data: 'nombre_plan'
                },
                {
                    data: 'precio'
                }, {
                    data: null,
                    render: function(data, type, row) {
                        var cantidad = parseInt(data.cantidad);
                        var unidad = data.unidad_tiempo.toLowerCase();

                        // Singular o plural
                        var label = '';
                        if (unidad === 'mes' || unidad === 'meses') {
                            label = cantidad === 1 ? 'Mes' : 'Meses';
                        } else if (unidad === 'hora' || unidad === 'horas') {
                            label = cantidad === 1 ? 'Hora' : 'Horas';
                        } else if (unidad === 'dia' || unidad === 'días' || unidad === 'dias') {
                            label = cantidad === 1 ? 'Día' : 'Días';
                        } else {
                            label = data.unidad_tiempo;
                        }

                        return cantidad + ' ' + label;
                    }
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        return `
                         <div class="d-flex justify-content-center gap-1">
                        <button class="btn btn-primary btn-xs"
                            onclick="abrir_modal_tarifas(${item._id})">
                          <i class="bx bx-edit fs-7 me-0 fw-bold"></i>
                        </button>
                        <button class="btn btn-danger btn-xs"
                                onclick="eliminar_tarifa(${item._id})">
                              <i class="bx bx-trash fs-7 me-0 fw-bold"></i>
                            </button>
                             </div>
                    `;
                    }
                }
            ]
        });
    });
</script>
<script type="text/javascript">
    function cargar_tarifa(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/HOST_TIME/ESPACIOS/hub_espacios_tarifasC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(r) {
                r = r[0];
                $('#id_espacio_tarifa').val(r._id);
                $('#txt_nombre_plan').val(r.nombre_plan);
                $('#ddl_unidad').val(r.unidad_tiempo);
                $('#txt_cantidad').val(r.cantidad);
                $('#txt_precio').val(r.precio);
            }
        });
    }



    function guardar_tarifa() {
        var parametros = {
            '_id': $('#id_espacio_tarifa').val(),
            'ddl_espacio': <?= $_id ?>,
            'txt_nombre_plan': $('#txt_nombre_plan').val(),
            'ddl_unidad': $('#ddl_unidad').val(),
            'txt_cantidad': $('#txt_cantidad').val(),
            'txt_precio': $('#txt_precio').val(),
        };

        if ($("#form_tarifas").valid()) insertar(parametros);
    }

    function insertar(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/HOST_TIME/ESPACIOS/hub_espacios_tarifasC.php?insertar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    $('#form_tarifas')[0].reset();
                    $('#id_tarifa').val('');
                    tbl_espacio_tarifas.ajax.reload();
                    Swal.fire('', 'Operación realizada con éxito.', 'success');
                    $('#modal_tarifas').modal('hide');
                }
            },
            error: function(xhr) {
                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });
    }

    function abrir_modal_tarifas(id) {
        $('#modal_tarifas').modal('show');
        if (id) {
            cargar_tarifa(id);
        } else {
            $('#form_tarifas')[0].reset();
            $('#id_tarifa').val('');
        }
    }

    function eliminar_tarifa(id) {
        Swal.fire({
            title: '¿Eliminar Registro?',
            text: "Esta seguro de eliminar este registro?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Si'
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    data: {
                        id: id
                    },
                    url: '../controlador/HOST_TIME/ESPACIOS/hub_espacios_tarifasC.php?eliminar=true',
                    type: 'post',
                    dataType: 'json',
                    success: function(response) {
                        if (response == 1) {
                            $('#form_tarifas')[0].reset();
                            $('#id_tarifa').val('');
                            tbl_espacio_tarifas.ajax.reload();
                            Swal.fire('Eliminado!', '', 'success');
                            $('#modal_tarifas').modal('hide');
                        }
                    }
                });
            }
        });
    }
</script>

<div class="row">
    <div class="col-12">
        <div class="card-title d-flex align-items-center justify-content-between">
            <div id="btn_nuevo">
                <button class="btn btn-success btn-sm"
                    onclick="abrir_modal_tarifas()">
                    <i class="bx bx-plus"></i> Nuevo
                </button>
            </div>
        </div>
        </br>
        <div class="table-responsive">
            <table class="table table-striped w-100" id="tbl_espacio_tarifas">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Periodo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>


<div class="modal fade" id="modal_tarifas" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content shadow">

            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">
                    <i class="bx bx-money me-1"></i> Gestión de Tarifas
                </h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="form_tarifas">
                    <input type="hidden" id="txt_id_espacio">
                    <input type="hidden" id="id_espacio_tarifa" value="">

                    <div class="row g-3">
                        <div class="col-12">
                            <label for="txt_nombre_plan" class="form-label fw-semibold">Nombre del plan </label>
                            <input type="text" class="form-control form-control-sm" id="txt_nombre_plan" name="txt_nombre_plan" placeholder="Ej: Plan Corporativo Mensual">
                        </div>

                        <div class="col-md-6">
                            <label for="ddl_unidad" class="form-label fw-semibold">Unidad </label>
                            <select class="form-select form-select-sm" id="ddl_unidad" name="ddl_unidad">
                                <option value="">-- Seleccione --</option>
                                <option value="HORA">Hora</option>
                                <option value="MES">Mes</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="txt_cantidad" class="form-label fw-semibold">Cantidad </label>
                            <input type="number" class="form-control form-control-sm" id="txt_cantidad" name="txt_cantidad" min="1" value="1">
                        </div>

                        <div class="col-12">
                            <label for="txt_precio" class="form-label fw-semibold">Precio </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="txt_precio" name="txt_precio" step="0.01" min="0" placeholder="0.00">
                            </div>
                        </div>

                        <div class="col-12 text-end mt-4">
                            <hr class="text-muted mb-3"> <button type="button" class="btn btn-success btn-sm px-4" onclick="guardar_tarifa()">
                                <i class="bx bx-save me-1"></i> Guardar Tarifa
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        agregar_asterisco_campo_obligatorio('txt_nombre_plan');
        agregar_asterisco_campo_obligatorio('ddl_unidad');
        agregar_asterisco_campo_obligatorio('txt_cantidad');
        agregar_asterisco_campo_obligatorio('txt_precio');

        $("#form_tarifas").validate({
            rules: {
                txt_nombre_plan: {
                    required: true
                },
                ddl_unidad: {
                    required: true
                },
                txt_cantidad: {
                    required: true,
                    min: 1
                },
                txt_precio: {
                    required: true,
                    min: 0
                }
            },
            messages: {
                txt_nombre_plan: "Ingrese el nombre del plan",
                ddl_unidad: "Seleccione una unidad",
                txt_cantidad: "Mínimo 1",
                txt_precio: "Ingrese el precio"
            },
            errorElement: 'span', // Cambia el error a un span
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.col-12, .col-md-6').append(error); // Ubica el error debajo del input
            },
            highlight: function(el) {
                $(el).addClass('is-invalid').removeClass('is-valid');
            },
            unhighlight: function(el) {
                $(el).removeClass('is-invalid').addClass('is-valid');
            }
        });
    });
</script>