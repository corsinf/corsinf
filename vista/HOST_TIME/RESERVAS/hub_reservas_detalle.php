<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);
$_id = $_GET['_id'] ?? '';
?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script>
    $(document).ready(function() {

        cargar_selects2();

        <?php if ($_id != '') { ?>
            datos_reserva(<?= $_id ?>);
        <?php } ?>

    });

    function cargar_selects2() {

        cargar_select2_url('ddl_persona', '../controlador/GENERAL/NO_CONCURRENTES/CLIENTESC.php?buscar_clientes=true');
        cargar_select2_url('ddl_espacio', '../controlador/HOST_TIME/ESPACIOS/espaciosC.php?buscar=true');
        cargar_select2_url('ddl_estado', '../controlador/HOST_TIME/CATALOGOS/hub_cats_estado_reservasC.php?buscar=true');

    }

    function datos_reserva(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/HOST_TIME/RESERVAS/hub_reservasC.php?listar_detalle=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {

                let r = response[0];

                $('#txt_codigo').val(r.codigo);
                $('#txt_inicio').val(fecha_input_datelocal(r.inicio));
                $('#txt_fin').val(fecha_input_datelocal(r.fin));
                $('#txt_observaciones').val(r.observaciones);

                $('#ddl_persona').append($('<option>', {
                    value: r.th_per_id,
                    text: r.cedula + " - " + r.nombre_persona,
                    selected: true
                }));
                $('#ddl_estado').append($('<option>', {
                    value: r.id_estado_reservas,
                    text: r.estado_reserva,
                    selected: true
                }));
                $('#ddl_espacio').append($('<option>', {
                    value: r.id_espacio,
                    text: r.nombre_espacio,
                    selected: true
                }));

            }
        });
    }

    function editar_insertar() {

        let parametros = {
            _id: '<?= $_id ?>',
            txt_codigo: $('#txt_codigo').val(),
            ddl_persona: $('#ddl_persona').val(),
            ddl_espacio: $('#ddl_espacio').val(),
            ddl_estado: $('#ddl_estado').val(),
            txt_inicio: fecha_formato_datetime2($('#txt_inicio').val()),
            txt_fin: fecha_formato_datetime2($('#txt_fin').val()),
            txt_observaciones: $('#txt_observaciones').val()
        };

        if ($("#form_reserva").valid()) {
            $.ajax({
                data: {
                    parametros: parametros
                },
                url: '../controlador/HOST_TIME/RESERVAS/hub_reservasC.php?insertar=true',
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    if (response == 1) {
                        Swal.fire('', 'Operación realizada con éxito.', 'success').then(() => {
                            location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=hub_reservas';
                        });
                    }
                }
            });
        }
    }

    function eliminar_reserva() {
        let id = '<?= $_id ?>';

        Swal.fire({
            title: 'Eliminar?',
            text: 'Esta seguro?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Si'
        }).then((result) => {
            if (result.isConfirmed) {

                $.post('../controlador/HOST_TIME/RESERVAS/hub_reservasC.php?eliminar=true', {
                    id: id
                }, function() {
                    location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=hub_reservas';
                });

            }
        });
    }
</script>

<div class="page-wrapper">
    <div class="page-content">

        <div class="page-breadcrumb mb-3">
            <div class="breadcrumb-title pe-3">Reservas</div>
        </div>

        <div class="card border-top border-4 border-primary">
            <div class="card-body p-5">

                <div class="d-flex align-items-center ">

                    <div class="d-flex align-items-center p-2">
                        <i class="bx bxs-building me-2 font-22 text-primary"></i>
                        <h5 class="mb-0 text-primary">
                            <?= $_id == '' ? 'Nueva Reserva' : 'Editar Reserva' ?>
                        </h5>
                    </div>

                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=hub_reservas"
                        class="btn btn-outline-dark btn-sm">
                        <i class="bx bx-arrow-back"></i> Regresar
                    </a>

                </div>

                <hr>
                <form id="form_reserva">

                    <div class="row g-3">

                        <div class="col-4">
                            <label for="txt_codigo">Código </label>
                            <input type="text" id="txt_codigo" name="txt_codigo" class="form-control form-control-sm">
                        </div>

                        <div class="col-4">
                            <label for="ddl_persona">Persona </label>
                            <select id="ddl_persona" name="ddl_persona" class="form-select form-select-sm"></select>
                        </div>

                        <div class="col-4">
                            <label for="ddl_espacio">Espacio </label>
                            <select id="ddl_espacio" name="ddl_espacio" class="form-select form-select-sm"></select>
                        </div>

                        <div class="col-4">
                            <label for="ddl_estado">Estado </label>
                            <select id="ddl_estado" name="ddl_estado" class="form-select form-select-sm"></select>
                        </div>

                        <div class="col-4">
                            <label for="txt_inicio">Inicio </label>
                            <input type="datetime-local" id="txt_inicio" name="txt_inicio" class="form-control form-control-sm">
                        </div>

                        <div class="col-4">
                            <label for="txt_fin">Fin </label>
                            <input type="datetime-local" id="txt_fin" name="txt_fin" class="form-control form-control-sm">
                        </div>

                        <div class="col-12">
                            <label for="txt_observaciones" >Observaciones </label>
                            <textarea id="txt_observaciones" name="txt_observaciones" class="form-control form-control-sm"></textarea>
                        </div>

                    </div>

                    <div class="mt-4 text-end">
                        <button type="button" class="btn btn-success btn-sm" onclick="editar_insertar()">
                            <i class="bx bx-save"></i> Guardar
                        </button>

                        <?php if ($_id != '') { ?>
                            <button type="button" class="btn btn-danger btn-sm" onclick="eliminar_reserva()">
                                <i class="bx bx-trash"></i> Eliminar
                            </button>
                        <?php } ?>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>

<script>
    $(document).ready(function() {

        agregar_asterisco_campo_obligatorio('txt_codigo');
        agregar_asterisco_campo_obligatorio('ddl_persona');
        agregar_asterisco_campo_obligatorio('ddl_estado');
        agregar_asterisco_campo_obligatorio('ddl_espacio');
        agregar_asterisco_campo_obligatorio('txt_inicio');
        agregar_asterisco_campo_obligatorio('txt_fin');
        agregar_asterisco_campo_obligatorio('txt_observaciones');

        $("#form_reserva").validate({
            rules: {
                txt_codigo: {
                    required: true
                },
                ddl_persona: {
                    required: true
                },
                ddl_estado: {
                    required: true
                },
                ddl_espacio: {
                    required: true
                },
                txt_inicio: {
                    required: true
                },
                txt_fin: {
                    required: true
                },
                txt_observaciones: {
                    required: true
                }
            },
            highlight: function(element) {
                $(element).addClass('is-invalid').removeClass('is-valid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid').addClass('is-valid');
            }
        });

    });
</script>