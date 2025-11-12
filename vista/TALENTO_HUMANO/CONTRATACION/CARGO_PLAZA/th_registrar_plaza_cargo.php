<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);
$_id = '';
if (isset($_GET['_id'])) {
    $_id = $_GET['_id'];
}
?>

<!-- Dependencias: SweetAlert2, Select2, jQuery Validate ya las usas en layout -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script type="text/javascript">
    $(document).ready(function () {

        // Si viene _id cargamos la asignación
        <?php if (isset($_GET['_id'])) { ?>
            cargar_asignacion(<?= $_id ?>);
        <?php } ?>

        // Inicializar y cargar selects (plaza y cargo)
        cargar_selects2();

        function cargar_selects2() {
            // Ajusta estas URLs si tus controladores difieren
            var url_plazas = '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_plazasC.php?buscar=true';
            var url_cargos = '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_cargosC.php?buscar=true';

            // helper que ya usas en el proyecto: cargar_select2_url(nombre_select, url)
            cargar_select2_url('ddl_plaza', url_plazas);
            cargar_select2_url('ddl_cargo', url_cargos);
        }

        // formato simple para datetime-local
        function formatDateToInput(dateStr) {
            if (!dateStr) return '';
            dateStr = String(dateStr).replace('.000','').trim();
            if (dateStr.indexOf(' ') !== -1) {
                return dateStr.slice(0,16).replace(' ', 'T');
            }
            if (dateStr.indexOf('T') !== -1) {
                return dateStr.slice(0,16);
            }
            return dateStr;
        }

        function boolVal(val) {
            return (val === 1 || val === '1' || val === true || val === 'true') ? true : false;
        }

        // CARGAR ASIGNACIÓN (SELECT por th_pc_id). Ajusta URL si tu controlador espera otro parámetro
        function cargar_asignacion(id) {
            $.ajax({
                url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_plaza_cargoC.php?listar=true',
                type: 'post',
                dataType: 'json',
                data: { id: id },
                success: function (response) {
                    if (!response || !response[0]) return;
                    var r = response[0];

                    // id interno
                    $('#txt_th_pc_id').val(r.th_pc_id || r._id || '');

                    // Cantidad y salario
                    $('#txt_th_pc_cantidad').val(r.th_pc_cantidad || r.cantidad || '');
                    $('#txt_th_pc_salario_ofertado').val(r.th_pc_salario_ofertado || r.salario_ofertado || '');

                    $('#ddl_plaza').append($('<option>', {
                        value: r.plaza_id,
                        text: r.plaza_titulo,
                        selected: true
                    }));

                    $('#ddl_cargo').append($('<option>', {
                        value: r.cargo_id,
                        text: r.cargo_nombre,
                        selected: true
                    }));
                   
                },
                error: function (err) {
                    console.error(err);
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Error al cargar la asignación (revisar consola).' });
                }
            });
        }

        // Validación simple antes de enviar (si luego implementas guardar/editar)
        function validarFormulario() {
            var plaza = $('#ddl_plaza').val();
            var cargo = $('#ddl_cargo').val();
            var cantidad = parseInt($('#txt_th_pc_cantidad').val() || '0');

            if (!plaza) { Swal.fire({ icon: 'warning', title: 'Validación', text: 'Seleccione una plaza.' }); return false; }
            if (!cargo) { Swal.fire({ icon: 'warning', title: 'Validación', text: 'Seleccione un cargo.' }); return false; }
            if (!cantidad || cantidad < 1) { Swal.fire({ icon: 'warning', title: 'Validación', text: 'Ingrese una cantidad válida.' }); return false; }
            return true;
        }

      function ParametrosPC() {
            return {
                'txt_th_pc_id': $('#txt_th_pc_id').val() || '',
                'th_pla_id': $('#ddl_plaza').val(),
                'th_car_id': $('#ddl_cargo').val(),
                'th_pc_cantidad': $('#txt_th_pc_cantidad').val() || 1,
                'th_pc_salario_ofertado': $('#txt_th_pc_salario_ofertado').val() || null,
                'th_pc_estado': 1
            };
        }

        function insertar_pc(parametros) {
            $.ajax({
                data: { parametros: parametros },
                url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_plaza_cargoC.php?insertar_editar=true',
                type: 'post',
                dataType: 'json',
                success: function(res) {
                    if (res == 1) {
                        Swal.fire('', 'Asignación creada con éxito.', 'success').then(function() {
                            window.location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_plaza_cargo';
                        });
                    } else if (res == -2) {
                        Swal.fire('', 'Ya existe una asignación activa para esa Plaza y Cargo.', 'warning');
                    } else {
                        Swal.fire('', res.msg || 'Error al guardar asignación.', 'error');
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    Swal.fire('', 'Error: ' + xhr.responseText, 'error');
                }
            });
        }

        // editar via AJAX (usa misma ruta)
        function editar_pc(parametros) {
            $.ajax({
                data: { parametros: parametros },
                url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_plaza_cargoC.php?insertar_editar=true',
                type: 'post',
                dataType: 'json',
                success: function(res) {
                    if (res == 1) {
                        Swal.fire('', 'Asignación actualizada con éxito.', 'success').then(function() {
                            window.location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_plaza_cargo';
                        });
                    } else if (res == -2) {
                        Swal.fire('', 'Ya existe otra asignación activa para esa Plaza y Cargo.', 'warning');
                    } else {
                        Swal.fire('', res.msg || 'Error al actualizar asignación.', 'error');
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    Swal.fire('', 'Error: ' + xhr.responseText, 'error');
                }
            });
        }

        // eliminar (soft-delete)
        function delete_pc() {
            var id = $('#txt_th_pc_id').val() || '';
            if (!id) { Swal.fire('', 'ID no encontrado para eliminar', 'warning'); return; }

            Swal.fire({
                title: 'Eliminar Asignación?',
                text: "¿Está seguro de eliminar esta asignación?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        data: { _id: id },
                        url: '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_plaza_cargoC.php?eliminar=true',
                        type: 'post',
                        dataType: 'json',
                        success: function(res) {
                            if (res == 1) {
                                Swal.fire('Eliminado!', 'Asignación eliminada.', 'success').then(function() {
                                    window.location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_plaza_cargo';
                                });
                            } else {
                                Swal.fire('', res.msg || 'No se pudo eliminar.', 'error');
                            }
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText);
                            Swal.fire('', 'Error: ' + xhr.responseText, 'error');
                        }
                    });
                }
            });
        }

        // Bind botones (añade dentro de ready)
        $('#btn_guardar_pc').on('click', function() {
            if (!$("#form_plaza_cargo").valid()) return;
            var params = ParametrosPC();
            insertar_pc(params);
        });
        $('#btn_editar_pc').on('click', function() {
            if (!$("#form_plaza_cargo").valid()) return;
            var params = ParametrosPC();
            editar_pc(params);
        });
        $('#btn_eliminar_pc').on('click', function() {
            delete_pc();
        });


    });
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Asignaciones</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Vincular Cargo con Plaza</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-4">
                        <div class="card-title d-flex align-items-center">
                            <div><i class="bx bx-link-alt me-1 font-22 text-primary"></i></div>
                            <h5 class="mb-0 text-primary"><?= ($_id == '') ? 'Nueva Asignación' : 'Modificar Asignación' ?></h5>
                            <div class="ms-auto">
                                <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_plaza_cargo" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                            </div>
                        </div>

                        <hr>

                        <form id="form_plaza_cargo">
                            <!-- hidden id -->
                            <input type="hidden" id="txt_th_pc_id" name="txt_th_pc_id" value="<?= $_id ?>" />

                            <div class="row g-3">
                                <!-- Plaza -->
                                <div class="col-md-6">
                                    <label for="ddl_plaza" class="form-label fw-bold">
                                        <i class="bx bx-briefcase me-2 text-primary"></i> Plaza
                                    </label>
                                    <select class="form-select select2-validation" id="ddl_plaza" name="ddl_plaza" required>
                                        <option value="" selected hidden>-- Seleccione Plaza --</option>
                                    </select>
                                </div>

                                <!-- Cargo -->
                                <div class="col-md-6">
                                    <label for="ddl_cargo" class="form-label fw-bold">
                                        <i class="bx bx-id-card me-2 text-success"></i> Cargo
                                    </label>
                                    <select class="form-select select2-validation" id="ddl_cargo" name="ddl_cargo" required>
                                        <option value="" selected hidden>-- Seleccione Cargo --</option>
                                    </select>
                                </div>

                                <!-- Cantidad -->
                                <div class="col-md-4">
                                    <label for="txt_th_pc_cantidad" class="form-label fw-bold">
                                        <i class="bx bx-layer me-2 text-warning"></i> Cantidad
                                    </label>
                                    <input type="number" min="1" class="form-control" id="txt_th_pc_cantidad" name="txt_th_pc_cantidad" placeholder="Ej: 1" required />
                                </div>

                                <!-- Salario ofertado -->
                                <div class="col-md-4">
                                    <label for="txt_th_pc_salario_ofertado" class="form-label fw-bold">
                                        <i class="bx bx-money me-2 text-success"></i> Salario ofertado
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" step="0.01" class="form-control" id="txt_th_pc_salario_ofertado" name="txt_th_pc_salario_ofertado" placeholder="0.00" />
                                    </div>
                                </div>

                            <div class="mt-4 text-end">
                                <?php if ($_id == '') { ?>
                                    <button type="button" class="btn btn-success" id="btn_guardar_pc"><i class="bx bx-save me-1"></i> Guardar Asignación</button>
                                <?php } else { ?>
                                    <button type="button" class="btn btn-primary" id="btn_editar_pc"><i class="bx bx-edit me-1"></i> Actualizar</button>
                                    <button type="button" class="btn btn-danger" id="btn_eliminar_pc"><i class="bx bx-trash me-1"></i> Eliminar</button>
                                <?php } ?>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>
