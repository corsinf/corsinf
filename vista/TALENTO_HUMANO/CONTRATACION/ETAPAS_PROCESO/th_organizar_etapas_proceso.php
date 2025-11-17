<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);
?>

<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Etapas</div>
            <div class="ps-3 d-flex justify-content-between align-items-center">

                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Organizar etapas
                        </li>
                    </ol>
                </nav>

                <!-- Botón Regresar -->
                <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_contr_etapas_proceso"
                    class="btn btn-outline-dark btn-sm ms-3">
                    <i class="bx bx-arrow-back"></i> Regresar
                </a>

            </div>

        </div>
        <!--end breadcrumb-->

        <div class="row mb-3">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div class="d-flex gap-2 align-items-center">
                    <label for="ddl_plaza" class="form-label mb-0 me-2 fw-bold">
                        <i class="bx bx-briefcase me-2 text-primary"></i> Plaza:
                    </label>
                    <select id="ddl_plaza" name="ddl_plaza" class="form-select form-select-sm" style="min-width:320px;">
                        <option value="">-- Seleccione plaza (todas) --</option>
                    </select>
                    <button id="btn_cargar_etapas" class="btn btn-outline-primary btn-sm ms-2">
                        <i class="bx bx-refresh"></i> Cargar
                    </button>
                </div>

                <div class="d-flex gap-2">
                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registro_etapa_proceso"
                        class="btn btn-success btn-sm">
                        <i class="bx bx-plus me-1"></i> Nueva Etapa
                    </a>
                    <button id="btn_guardar_orden" class="btn btn-primary btn-sm">
                        <i class="bx bx-save me-1"></i> Guardar orden
                    </button>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Lista arrastrable de etapas -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="bx bx-list-check me-2"></i> Etapas (arrastra para reordenar)</h6>
                    </div>
                    <div class="card-body">
                        <div id="panel_etapas">
                            <ul id="lista_etapas" class="list-group">
                                <!-- items cargados por JS -->
                            </ul>
                        </div>

                        <div id="sin_etapas" class="text-center text-muted py-4 d-none">
                            <i class="bx bx-info-circle fs-3"></i>
                            <p class="mb-0 mt-2">No hay etapas para la plaza seleccionada.</p>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Panel derecho: detalle / vista previa / acciones -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="mb-0"><i class="bx bx-info-circle me-2"></i> Detalle etapa</h6>
                    </div>
                    <div class="card-body">
                        <div id="detalle_etapa" class="d-none">
                            <h5 id="detalle_nombre" class="mb-1"></h5>
                            <div class="mb-2"><small id="detalle_tipo" class="text-muted"></small></div>
                            <p id="detalle_descripcion"></p>
                            <div class="mb-2"><strong>Orden:</strong> <span id="detalle_orden"></span></div>
                            <div class="mb-2"><strong>Obligatoria:</strong> <span id="detalle_obligatoria"></span></div>
                            <div class="mb-2"><strong>Plaza:</strong> <span id="detalle_plaza"></span></div>

                            <div class="d-flex gap-2 mt-3">
                                <a id="btn_editar_detalle" class="btn btn-outline-primary btn-sm"><i
                                        class="bx bx-edit"></i> Editar</a>
                                <button id="btn_eliminar_detalle" class="btn btn-outline-danger btn-sm"><i
                                        class="bx bx-trash"></i> Eliminar</button>
                            </div>
                        </div>

                        <div id="detalle_vacio" class="text-center text-muted">
                            <i class="bx bx-layer fs-3"></i>
                            <p class="mb-0 mt-2">Selecciona una etapa para ver el detalle.</p>
                        </div>
                    </div>
                </div>

                <!-- ayuda / instrucciones -->
                <div class="card mt-3">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Instrucciones</h6>
                    </div>
                    <div class="card-body small text-muted">
                        Arrastra las etapas en el panel izquierdo para cambiar su orden. Cuando termines presiona
                        <strong>Guardar orden</strong>.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script type="text/javascript">
$(function() {
    const URL_LISTAR = '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_etapas_procesoC.php?listar=true';
    const URL_PLAZAS = '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_plazasC.php?listar=true';
    const URL_ORDENAR =
        '../controlador/TALENTO_HUMANO/CONTRATACION/th_contr_etapas_procesoC.php?organizar=true';

    // Init
    cargarPlazas();

    $('#ddl_plaza').on('change', function() {
        const id = $(this).val();
        if (id) cargarEtapas(id);
        else limpiarLista();
    });
    $('#btn_cargar_etapas').on('click', () => {
        const id = $('#ddl_plaza').val();
        if (id) cargarEtapas(id);
    });
    $('#btn_guardar_orden').on('click', guardarOrden);

    // Sortable
    $('#lista_etapas').sortable({
        handle: '.drag-handle'
    }).disableSelection();

    function cargarPlazas() {
        $.post(URL_PLAZAS, {}, function(resp) {
            const $ddl = $('#ddl_plaza').empty().append(
                '<option value="">-- Seleccione plaza --</option>');
            resp.forEach(p => $ddl.append(
                `<option value="${p._id}">${p.th_pla_titulo||p.nombre||'Plaza '+p._id}</option>`
            ));
        }, 'json').fail(() => {
            $('#ddl_plaza').empty().append('<option value="">-- Error cargando plazas --</option>');
        });
    }

    function cargarEtapas(id_plaza) {
        limpiarLista();
        $.post(URL_LISTAR, {
            id: '',
            id_plaza: id_plaza
        }, function(resp) {
            if (!resp || !resp.length) {
                $('#sin_etapas').removeClass('d-none');
                return;
            }
            resp.forEach(e => {
                const item = $(`
                    <li class="list-group-item d-flex" data-id="${e._id}">
                        <span class="drag-handle btn btn-sm me-2"><i class="bx bx-menu"></i></span>
                        <div class="flex-grow-1">
                            <strong>${escapeHtml(e.nombre||e.th_etapa_nombre)}</strong>
                            <div class="small text-muted">${escapeHtml(e.tipo||'')}</div>
                            <div class="mt-2 small text-truncate">${escapeHtml(e.descripcion||'')}</div>
                        </div>
                        <div class="ms-2"><small>Orden: <span class="badge bg-secondary">${e.orden||''}</span></small></div>
                    </li>`);
                item.find('.drag-handle').css('cursor', 'grab');
                item.on('dblclick', () => window.location.href =
                    `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registro_etapa_proceso&_id=${e._id}`
                );
                $('#lista_etapas').append(item);
            });
            $('#sin_etapas').addClass('d-none');
        }, 'json').fail(() => Swal.fire('Error', 'No se pudo cargar las etapas', 'error'));
    }

    function guardarOrden() {
        const orden = $('#lista_etapas .list-group-item').map((i, li) => $(li).data('id')).get();
        if (!orden.length) {
            Swal.fire('', 'No hay etapas para ordenar.', 'info');
            return;
        }
        $.post(URL_ORDENAR, {
            orden: orden
        }, function(resp) {
            if (resp == 1) Swal.fire('', 'Orden guardado.', 'success').then(() => $(
                '#btn_guardar_orden').removeClass('btn-warning'));
            else Swal.fire('Error', 'No se pudo guardar el orden: ' + resp, 'error');
        }, 'json').fail(() => Swal.fire('Error', 'Error al guardar. Revisa la consola.', 'error'));
    }

    function limpiarLista() {
        $('#lista_etapas').empty();
        $('#detalle_etapa').addClass('d-none');
        $('#detalle_vacio').removeClass('d-none');
        $('#sin_etapas').addClass('d-none');
    }

    function escapeHtml(text) {
        return text ? String(text).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(
            /"/g, '&quot;').replace(/'/g, '&#39;') : '';
    }

    // Sortable + renumerar visualmente al soltar
    $('#lista_etapas').sortable({
        handle: '.drag-handle',
        update: function(event, ui) {
            // Renumerar badges/orden en la UI
            $('#lista_etapas .list-group-item').each(function(index) {
                // index es 0-based -> si quieres 1-based usa (index+1)
                const newOrder = index + 1;
                $(this).find('.badge-order').remove(); // remover antiguo si existe
                // insertar / actualizar badge de orden
                $(this).find('.ms-2').html(
                    `<small>Orden: <span class="badge bg-secondary badge-order">${newOrder}</span></small>`
                );
                // opcional: almacenar el orden actual en data-order del li
                $(this).data('orden', newOrder);
            });

            // marcar botón guardar (cambios pendientes)
            $('#btn_guardar_orden').addClass('btn-warning');

            // OPCIONAL: enviar auto-save cada vez que se reordena:
            // enviarOrdenAutomatico(); // descomenta si quieres auto-guardar
        }
    }).disableSelection();

    // Construye el array de ids con su nuevo orden (objeto [{id:..., orden:...}, ...])
    function obtenerOrdenActual() {
        const ordenes = [];
        $('#lista_etapas .list-group-item').each(function(index) {
            const id = $(this).data('id');
            const orden = $(this).data('orden') || (index + 1);
            ordenes.push({
                id: id,
                orden: orden
            });
        });
        return ordenes;
    }

    // Si quieres auto-guardar en cada update
    function enviarOrdenAutomatico() {
        const payload = obtenerOrdenActual(); // array de {id,orden}
        $.ajax({
            url: URL_ORDENAR,
            method: 'POST',
            dataType: 'json',
            data: {
                ordenes: JSON.stringify(payload)
            }, // o data: { orden: ids_array } según server
        }).done(function(resp) {
            if (resp == 1) {
                $('#btn_guardar_orden').removeClass('btn-warning');
            } else {
                console.warn('No se pudo guardar orden auto:', resp);
            }
        }).fail(function() {
            console.warn('Error auto-guardar orden');
        });
    }

    function guardarOrden() {
        const payload = obtenerOrdenActual(); // [{id,orden}, ...]

        if (payload.length === 0) {
            Swal.fire('', 'No hay etapas para ordenar.', 'info');
            return;
        }

        $('#btn_guardar_orden').prop('disabled', true).html('Guardando...');

        $.ajax({
            url: URL_ORDENAR,
            method: 'POST',
            dataType: 'json',
            data: {
                ordenes: JSON.stringify(payload)
            } // el controlador recibirá $_POST['ordenes']
        }).done(function(resp) {
            if (resp == 1 || resp === 1) {
                Swal.fire('', 'Orden guardado.', 'success');
                $('#btn_guardar_orden').removeClass('btn-warning');
            } else {
                Swal.fire('Error', resp.msg || ('No se pudo guardar el orden: ' + resp), 'error');
            }
        }).fail(function(xhr) {
            console.error(xhr.responseText);
            Swal.fire('Error', 'Error al guardar. Revisa la consola.', 'error');
        }).always(function() {
            $('#btn_guardar_orden').prop('disabled', false).html(
                '<i class="bx bx-save me-1"></i> Guardar orden');
        });
    }


});
</script>