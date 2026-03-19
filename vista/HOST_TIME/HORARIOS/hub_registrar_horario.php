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
    const DIAS = ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];

    $(document).ready(function() {
        cargar_select2_url('ddl_espacio', '../controlador/HOST_TIME/ESPACIOS/espaciosC.php?buscar=true');

        <?php if (isset($_GET['_id'])): ?>
            datos_col(<?= $_id ?>);
        <?php endif; ?>

        $('#ddl_espacio, #ddl_dia_semana').on('change', function() {
            let id_espacio = $('#ddl_espacio').val();
            let dia = $('#ddl_dia_semana').val();
            if (id_espacio && dia !== '') cargar_timeline(id_espacio, dia);
        });
    });

    function datos_col(id) {
        $.ajax({
            data: { id: id },
            url: '../controlador/HOST_TIME/HORARIOS/hub_horariosC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                let r = response[0];
                $('#ddl_espacio').append($('<option>', {
                    value: r.id_espacio,
                    text: r.nombre_espacio,
                    selected: true
                }));
                $('#ddl_dia_semana').val(r.dia_semana);
                $('#txt_hora_inicio').val(r.hora_inicio.substring(0, 5));
                $('#txt_hora_fin').val(r.hora_fin.substring(0, 5));
                $('#cbx_activo').prop('checked', r.activo == 1);
                cargar_timeline(r.id_espacio, r.dia_semana);
            }
        });
    }

    function cargar_timeline(id_espacio, dia) {
        $.ajax({
            data: { id_espacio: id_espacio },
            url: '../controlador/HOST_TIME/HORARIOS/hub_horariosC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                let horarios_dia = response.filter(h => h.dia_semana == dia && h._id != '<?= $_id ?>');
                renderizar_timeline(horarios_dia);
                $('#contenedor_timeline').show();
            }
        });
    }

    function renderizar_timeline(horarios) {
        let $tl = $('#timeline_24h');
        $tl.empty();

        for (let h = 0; h < 24; h++) {
            let pct = (h / 24) * 100;
            $tl.append(`
                <div style="position:absolute;left:${pct}%;top:0;bottom:0;border-left:1px solid #dee2e6;"></div>
                <span style="position:absolute;left:calc(${pct}% + 2px);top:2px;font-size:10px;color:#6c757d;">${String(h).padStart(2,'0')}:00</span>
            `);
        }

        horarios.forEach(function(h) {
            let ini = tiempo_a_minutos(h.hora_inicio);
            let fin = tiempo_a_minutos(h.hora_fin);
            let pct_ini = (ini / 1440) * 100;
            let pct_ancho = ((fin - ini) / 1440) * 100;
            let color = h.activo == 1 ? '#dc3545' : '#6c757d';

            $tl.append(`
                <div style="position:absolute;left:${pct_ini}%;width:${pct_ancho}%;top:22px;bottom:4px;
                    background:${color};opacity:0.7;border-radius:4px;"
                    title="Ocupado: ${h.hora_inicio.substring(0,5)} - ${h.hora_fin.substring(0,5)}"
                    data-bs-toggle="tooltip">
                    <span style="font-size:10px;color:#fff;padding:2px 4px;white-space:nowrap;overflow:hidden;display:block;">
                        ${h.hora_inicio.substring(0,5)} - ${h.hora_fin.substring(0,5)}
                    </span>
                </div>
            `);
        });

        $('[data-bs-toggle="tooltip"]').tooltip();
    }

    function tiempo_a_minutos(hora) {
        let p = hora.split(':');
        return parseInt(p[0]) * 60 + parseInt(p[1]);
    }

    function editar_insertar() {
        var parametros = {
            '_id':           '<?= $_id ?>',
            'ddl_espacio':   $('#ddl_espacio').val(),
            'ddl_dia_semana': $('#ddl_dia_semana').val(),
            'txt_hora_inicio': $('#txt_hora_inicio').val(),
            'txt_hora_fin':  $('#txt_hora_fin').val(),
            'cbx_activo':    $('#cbx_activo').prop('checked') ? 1 : 0,
        };

        if ($('#form_horario').valid()) {
            insertar(parametros);
        }
    }

    function insertar(parametros) {
        $.ajax({
            data: { parametros: parametros },
            url: '../controlador/HOST_TIME/HORARIOS/hub_horariosC.php?insertar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=hub_horarios';
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });
    }

    function delete_datos() {
        Swal.fire({
            title: 'Eliminar Registro?',
            text: "Esta seguro de eliminar este registro?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    data: { id: '<?= $_id ?>' },
                    url: '../controlador/HOST_TIME/HORARIOS/hub_horariosC.php?eliminar=true',
                    type: 'post',
                    dataType: 'json',
                    success: function(response) {
                        if (response == 1) {
                            Swal.fire('Eliminado!', 'Registro Eliminado.', 'success').then(function() {
                                location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=hub_horarios';
                            });
                        }
                    }
                });
            }
        });
    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Horarios</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            <?= $_id == '' ? 'Registrar Horario' : 'Ver Horario' ?>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-5">
                        <div class="card-title d-flex align-items-center">
                            <div><i class="bx bxs-time me-1 font-22 text-primary"></i></div>
                            <h5 class="mb-0 text-primary">
                                <?= $_id == '' ? 'Registrar Horario' : 'Ver Horario' ?>
                            </h5>
                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=hub_horarios" class="btn btn-outline-dark btn-sm">
                                        <i class="bx bx-arrow-back"></i> Regresar
                                    </a>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <form id="form_horario">

                            <div class="row pt-3 mb-col">
                                <div class="col-md-6">
                                    <label for="ddl_espacio" class="form-label">Espacio</label>
                                    <select class="form-select form-select-sm select2-validation" id="ddl_espacio" name="ddl_espacio" required>
                                        <option value="" selected hidden>-- Seleccione --</option>
                                    </select>
                                    <label class="error" style="display:none;" for="ddl_espacio"></label>
                                </div>

                                <div class="col-md-6">
                                    <label for="ddl_dia_semana" class="form-label">Día de la semana</label>
                                    <select class="form-select form-select-sm" id="ddl_dia_semana" name="ddl_dia_semana" required>
                                        <option value="" selected hidden>-- Seleccione --</option>
                                        <option value="0">Domingo</option>
                                        <option value="1">Lunes</option>
                                        <option value="2">Martes</option>
                                        <option value="3">Miércoles</option>
                                        <option value="4">Jueves</option>
                                        <option value="5">Viernes</option>
                                        <option value="6">Sábado</option>
                                    </select>
                                    <label class="error" style="display:none;" for="ddl_dia_semana"></label>
                                </div>
                            </div>

                            <!-- Timeline de ocupación -->
                            <div id="contenedor_timeline" style="display:none;" class="row mb-col">
                                <div class="col-12">
                                    <div class="card border">
                                        <div class="card-header d-flex align-items-center justify-content-between">
                                            <strong class="small">Horarios ocupados este día</strong>
                                            <div class="d-flex gap-3 small text-muted">
                                                <span><span class="badge bg-danger">&nbsp;</span> Ocupado activo</span>
                                                <span><span class="badge bg-secondary">&nbsp;</span> Ocupado inactivo</span>
                                            </div>
                                        </div>
                                        <div class="card-body py-3">
                                            <div id="timeline_24h" style="position:relative;height:56px;background:#f8f9fa;border-radius:4px;overflow:hidden;border:1px solid #dee2e6;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-col">
                                <div class="col-md-6">
                                    <label for="txt_hora_inicio" class="form-label">Hora inicio</label>
                                    <input type="time" class="form-control form-control-sm" id="txt_hora_inicio" name="txt_hora_inicio">
                                </div>

                                <div class="col-md-6">
                                    <label for="txt_hora_fin" class="form-label">Hora fin</label>
                                    <input type="time" class="form-control form-control-sm" id="txt_hora_fin" name="txt_hora_fin">
                                </div>
                            </div>

                            <div class="row mb-col">
                                <div class="col-md-6">
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" id="cbx_activo" name="cbx_activo" checked>
                                        <label class="form-check-label" for="cbx_activo">Disponible</label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end pt-2">
                                <?php if ($_id == ''): ?>
                                    <button class="btn btn-success btn-sm px-4 m-0" onclick="editar_insertar()" type="button"><i class="bx bx-save"></i> Guardar</button>
                                <?php else: ?>
                                    <button class="btn btn-success btn-sm px-4 m-1" onclick="editar_insertar()" type="button"><i class="bx bx-save"></i> Editar</button>
                                    <button class="btn btn-danger btn-sm px-4 m-1" onclick="delete_datos()" type="button"><i class="bx bx-trash"></i> Eliminar</button>
                                <?php endif; ?>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        agregar_asterisco_campo_obligatorio('ddl_espacio');
        agregar_asterisco_campo_obligatorio('ddl_dia_semana');
        agregar_asterisco_campo_obligatorio('txt_hora_inicio');
        agregar_asterisco_campo_obligatorio('txt_hora_fin');

        $('#form_horario').validate({
            rules: {
                ddl_espacio:    { required: true },
                ddl_dia_semana: { required: true },
                txt_hora_inicio:{ required: true },
                txt_hora_fin:   { required: true },
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