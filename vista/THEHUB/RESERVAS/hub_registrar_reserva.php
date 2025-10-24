<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);

$_id = '';

if (isset($_GET['_id'])) {
    $_id = $_GET['_id'];
}

?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        <?php if (isset($_GET['_id'])) { ?>
            cargar_ubicacion(<?= $_id ?>);
            cargar_servicios_libres(<?= $_id ?>);
            cargar_tabla_servicios(<?= $_id ?>);
        <?php } ?>
        cargar_selects2();


        function cargar_ubicacion(id) {
            $.ajax({
                data: {
                    id: id
                },
                url: '../controlador/XPACE_CUBE/reservasC.php?listar=true',
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    //console.log(response);
                    $('#txt_nota').val(response[0].notas);
                    $('#txt_numero_personas').val(response[0].numero_personas);
                    $('#txt_inicio').val(formatearFechaParaInput(response[0].inicio));
                    $('#txt_fin').val(formatearFechaParaInput(response[0].fin));
                    $('#ddl_miembro').append($('<option>', {
                        value: response[0].id_miembro,
                        text: response[0].nombre_miembro,
                        selected: true
                    }));
                    $('#ddl_espacio').append($('<option>', {
                        value: response[0].id_espacio,
                        text: response[0].nombre_espacio,
                        selected: true
                    }));
                }
            });

        }
    });

    function formatearFechaParaInput(fechaSQL) {
        if (!fechaSQL) return "";
        // Elimina los microsegundos si existen y reemplaza el espacio por 'T'
        return fechaSQL.replace(" ", "T").substring(0, 16);
    }

    function cargar_selects2() {
        url_espaciosC = '../controlador/XPACE_CUBE/espaciosC.php?buscar=true';
        cargar_select2_url('ddl_espacio', url_espaciosC);

        url_miembrosC = '../controlador/XPACE_CUBE/miembrosC.php?buscar=true';
        cargar_select2_url('ddl_miembro', url_miembrosC);

    }
</script>

<script>
    // Si tienes el id en PHP ($_id), pásalo a JS
    function cargar_servicios_libres(id_espacio) {
        $.ajax({
            url: '../controlador/XPACE_CUBE/serviciosC.php?buscar_libres=true',
            type: 'GET',
            dataType: 'json',
            data: {
                id_espacio: id_espacio
            },
            success: function(response) {
                const $select = $('#ddl_servicios');
                $select.empty(); // limpia opciones anteriores
                $select.append('<option value="" selected hidden>-- Seleccione --</option>');

                if (response.length > 0) {
                    $.each(response, function(index, item) {
                        $select.append(
                            $('<option>', {
                                value: item.id,
                                text: item.text
                            })
                        );
                    });
                } else {
                    $select.append('<option disabled>No hay servicios disponibles</option>');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error al cargar servicios:', error);
            }
        });
    }


    function cargar_tabla_servicios(id_reserva) {
        tbl_reserva_servicio = $('#tbl_reserva_servicio').DataTable($.extend({},  {
            responsive: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            ajax: {
                url: '../controlador/XPACE_CUBE/reserva_servicioC.php?listar=true',
                type: 'POST',
                data: function(d) {
                    d.id = id_reserva; // cambiar id_reserva a id
                },
                dataSrc: ''
            },
            columns: [{
                    data: 'nombre'
                },
                {
                    data: 'cantidad',
                    className: 'text-center'
                },
                {
                    data: 'precio_unitario',
                    className: 'text-end',
                    render: $.fn.dataTable.render.number(',', '.', 2, '$')
                },
                {
                    data: null,
                    className: 'text-end',
                    render: function(data, type, row) {
                        return '$' + (row.cantidad * row.precio_unitario).toFixed(2);
                    }
                }
            ],
            order: [
                [1, 'asc']
            ]
        }));

        $('#tbl_reserva_servicio').on('draw.dt', function() {
            let total = 0;
            tbl_reserva_servicio.rows().every(function() {
                let data = this.data();
                total += data.cantidad * data.precio_unitario;
            });
            $('#serv_subtotal').text('$' + total.toFixed(2));
        });
    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Reservas</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Registros
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

                            <div><i class="bx bxs-user me-1 font-22 text-primary"></i>
                            </div>
                            <h5 class="mb-0 text-primary">
                                <?php
                                if ($_id == '') {
                                    echo 'Registrar Reserva';
                                } else {
                                    echo 'Modificar Reserva';
                                }
                                ?>
                            </h5>

                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=hub_reservas" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-4 shadow-sm">
                            <div class="card-header bg-white">
                                <h5 class="mb-0"><i class="bx bx-info-circle me-2"></i> Información sobre la reserva</h5>
                            </div>

                            <div class="card-body">
                                <form id="form_espacios" class="needs-validation" novalidate>
                                    <div class="row g-3">
                                        <!-- Nota -->
                                        <div class="col-md-6">
                                            <label for="txt_nota" class="form-label fw-bold d-flex align-items-center">
                                                <i class="bx bx-notepad me-2 text-primary fs-5"></i> Nota
                                            </label>
                                            <input type="text" class="form-control form-control-sm no_caracteres" id="txt_nota" name="txt_nota" placeholder="Ingrese una nota" autocomplete="off">
                                        </div>

                                        <!-- Número de personas -->
                                        <div class="col-md-6">
                                            <label for="txt_numero_personas" class="form-label fw-bold d-flex align-items-center">
                                                <i class="bx bx-group me-2 text-info fs-5"></i> Número de personas
                                            </label>
                                            <input type="number" class="form-control form-control-sm" id="txt_numero_personas" name="txt_numero_personas" placeholder="Ingrese el número de personas" min="0" autocomplete="off">
                                        </div>

                                        <!-- Fecha de inicio -->
                                        <div class="col-md-6">
                                            <label for="txt_inicio" class="form-label fw-bold d-flex align-items-center">
                                                <i class="bx bx-calendar me-2 text-success fs-5"></i> Fecha de inicio
                                            </label>
                                            <input type="datetime-local" class="form-control form-control-sm" id="txt_inicio" name="txt_inicio" autocomplete="off">
                                        </div>

                                        <!-- Fecha de fin -->
                                        <div class="col-md-6">
                                            <label for="txt_fin" class="form-label fw-bold d-flex align-items-center">
                                                <i class="bx bx-calendar-check me-2 text-success fs-5"></i> Fecha de fin
                                            </label>
                                            <input type="datetime-local" class="form-control form-control-sm" id="txt_fin" name="txt_fin" autocomplete="off">
                                        </div>

                                        <!-- Espacio -->
                                        <div class="col-md-6">
                                            <label for="ddl_espacio" class="form-label fw-bold d-flex align-items-center">
                                                <i class="bx bx-buildings me-2 text-warning fs-5"></i> Espacio
                                            </label>
                                            <select class="form-select form-select-sm select2-validation" id="ddl_espacio" name="ddl_espacio" required>
                                                <option value="" selected hidden>-- Seleccione --</option>
                                                <!-- opciones dinámicas -->
                                            </select>
                                            <div class="form-text">Seleccione el espacio de la reserva</div>
                                            <div class="invalid-feedback">Seleccione un espacio.</div>
                                        </div>

                                        <!-- Miembro -->
                                        <div class="col-md-6">
                                            <label for="ddl_miembro" class="form-label fw-bold d-flex align-items-center">
                                                <i class="bx bx-user-circle me-2 text-danger fs-5"></i> Miembro
                                            </label>
                                            <select class="form-select form-select-sm select2-validation" id="ddl_miembro" name="ddl_miembro" required>
                                                <option value="" selected hidden>-- Seleccione --</option>
                                                <!-- opciones dinámicas -->
                                            </select>
                                            <div class="form-text">Seleccione el miembro de la reserva</div>
                                            <div class="invalid-feedback">Seleccione un miembro.</div>
                                        </div>
                                    </div>

                                    <div class="mt-4 text-end">
                                        <?php if ($_id == '') { ?>
                                            <button type="button" class="btn btn-success btn-sm px-4" id="btn_guardar"><i class="bx bx-save"></i> Guardar</button>
                                        <?php } else { ?>
                                            <button type="button" class="btn btn-success btn-sm px-4 me-1" id="btn_guardar"><i class="bx bx-save"></i> Editar</button>
                                            <button type="button" class="btn btn-danger btn-sm px-4" id="btn_eliminar"><i class="bx bx-trash"></i> Eliminar</button>
                                        <?php } ?>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Sección: Servicios sobre la reserva -->
                        <div class="card mb-4 shadow-sm">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="bx bx-conversation me-2"></i> Servicios sobre la reserva</h5>
                            </div>

                            <div class="card-body">
                                <!-- Fila para agregar servicio rápido -->
                                <div class="row g-2 align-items-end mb-3">
                                    <div class="col-md-5">
                                        <label for="ddl_servicios" class="form-label fw-bold">Servicio</label>
                                        <select class="form-select form-select-sm" id="ddl_servicios" name="ddl_servicios" required>
                                            <option value="" selected hidden>-- Seleccione servicio --</option>
                                            <!-- opciones dinámicas -->
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label fw-bold">Cant.</label>
                                        <input id="txt_serv_cantidad" type="number" class="form-control form-control-sm" min="1" value="1">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Precio unitario</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">$</span>
                                            <input id="txt_serv_precio" type="number" class="form-control form-control-sm" step="0.01" min="0" placeholder="0.00">
                                        </div>
                                    </div>
                                    <div class="col-md-2 text-end">
                                        <button id="btn_add_service" class="btn btn-primary btn-sm w-100"><i class="bx bx-check"></i> Añadir</button>
                                    </div>
                                </div>

                                <!-- Tabla de servicios añadidos -->
                                <div class="table-responsive">
                                    <table class="table table-striped responsive " id="tbl_reserva_servicio" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Cantidad</th>
                                                <th>Precio</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="">

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="3" class="text-end fw-bold">Subtotal</td>
                                                <td class="text-end fw-bold" id="serv_subtotal">$0.00</td>
                                            </tr>
                                        </tfoot>

                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>