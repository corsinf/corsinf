<?php
$_id_plaza = isset($_GET['_id_plaza']) ? (int)$_GET['_id_plaza'] : 0;
$color_default = '#d4edda';
?>

<style>
    .list-group-item {
        cursor: grab;
    }

    .list-group-item.fijo-inicio {
        cursor: not-allowed !important;
        opacity: .85;
    }

    .item-bloqueado {
        cursor: not-allowed !important;
        opacity: .9;
    }

    .sortable-placeholder {
        border: 2px dashed #007bff;
        background: #f8f9fa;
        height: 40px;
        margin-bottom: 5px;
    }

    .lista-etapas {
        min-height: 220px;
        max-height: 350px;
        overflow-y: auto;
        border: 1px solid #ccc;
        padding: 10px;
        border-radius: 5px;
    }

    .badge-orden {
        font-size: 0.7rem;
        margin-right: 6px;
    }

    .badge-tipo {
        font-size: 0.65rem;
        margin-left: 4px;
    }

    #indicador_guardado {
        font-size: 0.78rem;
        transition: opacity 0.4s ease;
    }
</style>

<script>
    $(document).ready(function() {
        verificarEtapasExistentes();
    });

    function verificarEtapasExistentes() {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/cn_plaza_etapasC.php?listar=true',
            type: 'POST',
            dataType: 'json',
            data: {
                id_plaza: <?= $_id_plaza ?>
            },
            success: function(response) {
                if (response && response.length > 0) {
                    inicializarEditor();
                    cargarEtapasPlaza('<?= $_id_plaza ?>');
                } else {
                    $('#pnl_inicio').show();
                    $('#pnl_editor').hide();
                }
            }
        });
    }

    function inicializarEditor() {
        $('#pnl_inicio').hide();
        $('#pnl_editor').show();

        if ($(".pnl_etapas_sort").hasClass("ui-sortable")) return;

        $(".pnl_etapas_sort").sortable({
            connectWith: ".pnl_etapas_sort",
            placeholder: "sortable-placeholder",
            cursor: "move",
            revert: 150,
            cancel: ".item-bloqueado, .fijo-inicio", // ← estos nunca serán arrastrables

            change: function(event, ui) {
                let placeholder = ui.placeholder;
                if (placeholder.parent().attr("id") !== "pnl_lista_destino") return;

                let destino = $("#pnl_lista_destino");
                let itemsReales = destino.find('li:not(.ui-sortable-placeholder):not(.ui-sortable-helper)');
                let currentIdx = placeholder.index();
                let esFinFijo = parseInt(ui.item.data("es-fin-fijo")) === 1;
                let esInicioFijo = parseInt(ui.item.data("es-inicio-fijo")) === 1;
                let draggingId = parseInt(ui.item.data("id-etapa"));

                let lastInicio = itemsReales.filter('[data-es-inicio-fijo="1"]').last();
                if (lastInicio.length > 0 && currentIdx <= lastInicio.index()) {
                    lastInicio.after(placeholder);
                    return;
                }

                let firstFin = itemsReales.filter('[data-es-fin-fijo="1"]').first();

                if (esFinFijo) {
                    let posDepues = null,
                        posAntes = null;
                    let lastNormal = itemsReales.filter('[data-es-fin-fijo="0"][data-es-inicio-fijo="0"]').last();
                    if (lastNormal.length > 0) posDepues = lastNormal;

                    itemsReales.filter('[data-es-fin-fijo="1"]').each(function() {
                        let id = parseInt($(this).data("id-etapa"));
                        if (id < draggingId) posDepues = $(this);
                    });
                    itemsReales.filter('[data-es-fin-fijo="1"]').each(function() {
                        let id = parseInt($(this).data("id-etapa"));
                        if (id > draggingId && posAntes === null) posAntes = $(this);
                    });

                    if (posAntes !== null && currentIdx >= posAntes.index()) {
                        posAntes.before(placeholder);
                        return;
                    }
                    if (posDepues !== null && currentIdx <= posDepues.index()) {
                        posDepues.after(placeholder);
                        return;
                    }
                } else {
                    if (firstFin.length > 0 && currentIdx >= firstFin.index()) firstFin.before(placeholder);
                }
            },

            update: function(event, ui) {
                if (ui.item.parent().attr("id") !== "pnl_lista_destino") {
                    ui.item.css("background-color", "");
                }
                actualizarNumerosOrden();
                guardarEtapasPlaza();
            }

        }).disableSelection();

        $(document).on('change', '#pnl_lista_destino .chk-obligatoria', function() {
            guardarEtapasPlaza();
        });
    }

    function getColor(item) {
        if (item.etapa_color || item.color) return item.etapa_color || item.color;
        if (parseInt(item.etapa_es_inicio_fijo || item.es_inicio_fijo) === 1) return '#cfe2ff';
        if (parseInt(item.etapa_es_fin_fijo || item.es_fin_fijo) === 1) return '#fff3cd';
        return '<?= $color_default ?>';
    }

    function cargarEtapasPlaza(id_plaza) {
        let req_catalogo = $.ajax({
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/cn_cat_plaza_etapasC.php?listar=true',
            type: 'POST',
            dataType: 'json'
        });
        let req_asignadas = $.ajax({
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/cn_plaza_etapasC.php?listar=true',
            type: 'POST',
            dataType: 'json',
            data: {
                id_plaza: id_plaza
            }
        });

        $.when(req_catalogo, req_asignadas).done(function(resCat, resAsig) {
            window._catalogo_etapas = resCat[0];

            let asignadas = resAsig[0];
            asignadas.sort((a, b) => a.cn_plaet_orden - b.cn_plaet_orden);
            let idsAsignados = asignadas.map(e => String(e.id_etapa));

            let itemsDestino = asignadas.map((item, i) => buildItemDestino(
                item.id_etapa,
                item._id,
                item.etapa_nombre || 'Sin nombre',
                item.etapa_es_fin_fijo || 0,
                item.etapa_es_inicio_fijo || 0,
                item.cn_plaet_obligatoria || 0,
                getColor(item),
                i,
                item.etapa_obligatoria_default || 0
            )).join('');

            let itemsOrigen = resCat[0]
                .filter(item => !idsAsignados.includes(String(item._id)))
                .map(item => buildItemOrigen(item._id, item.nombre, item.es_fin_fijo, item.es_inicio_fijo, getColor(item)))
                .join('');

            $("#pnl_lista_destino").html(itemsDestino);
            $("#pnl_lista_origen").html(itemsOrigen);
        }).fail(function() {
            Swal.fire('Error', 'No se pudieron cargar las etapas.', 'error');
        });
    }

    function buildItemDestino(idEtapa, idPlaet, nombre, esFinFijo, esInicioFijo, obligatoria, color, index, bloqueada) {
        let uid = idPlaet || 'n' + index;
        let fijoClass = parseInt(esInicioFijo) === 1 ? 'fijo-inicio' : '';
        let bloqClass = parseInt(bloqueada) === 1 ? 'item-bloqueado' : '';

        let badgeTipo = parseInt(esInicioFijo) === 1 ?
            `<span class="badge badge-tipo" style="background:#0d6efd;color:#fff;">Inicio</span>` :
            (parseInt(esFinFijo) === 1 ? `<span class="badge badge-tipo" style="background:#ffc107;color:#000;">Final</span>` : '');

        let badgeBloq = parseInt(bloqueada) === 1 ?
            `<span class="badge badge-tipo" style="background:#6c757d;color:#fff;"><i class="bx bx-lock-alt"></i></span>` : '';

        return `<li class="list-group-item d-flex align-items-center gap-2 ${fijoClass} ${bloqClass}"
                    data-id-etapa="${idEtapa}" data-id-plaet="${idPlaet}"
                    data-es-fin-fijo="${esFinFijo}" data-es-inicio-fijo="${esInicioFijo}"
                    data-bloqueada="${bloqueada || 0}"
                    style="background-color:${color};">
                    <span class="badge bg-secondary badge-orden">${index + 1}</span>
                    <span class="flex-grow-1">${nombre} ${badgeTipo} ${badgeBloq}</span>
                    <div class="form-check form-check-inline mb-0 ms-auto" style="cursor:default;" onclick="event.stopPropagation()">
                        <input class="form-check-input chk-obligatoria" type="checkbox"
                               id="chk_obl_${uid}" title="Etapa obligatoria"
                               ${parseInt(obligatoria) === 1 ? 'checked' : ''}
                               ${parseInt(bloqueada)   === 1 ? 'disabled' : ''}>
                        <label class="form-check-label small text-muted" for="chk_obl_${uid}">Obligatoria</label>
                    </div>
                </li>`;
    }

    function buildItemOrigen(idEtapa, nombre, esFinFijo, esInicioFijo, color) {
        let badgeTipo = parseInt(esInicioFijo) === 1 ?
            `<span class="badge badge-tipo ms-1" style="background:#0d6efd;color:#fff;">Inicio</span>` :
            (parseInt(esFinFijo) === 1 ? `<span class="badge badge-tipo ms-1" style="background:#ffc107;color:#000;">Final</span>` : '');
        return `<li class="list-group-item"
                    data-id-etapa="${idEtapa}" data-id-plaet=""
                    data-es-fin-fijo="${esFinFijo || 0}" data-es-inicio-fijo="${esInicioFijo || 0}"
                    data-bloqueada="0">
                    ${nombre || 'Sin nombre'} ${badgeTipo}
                </li>`;
    }

    function cargarEtapasPorDefecto() {
        let $btn = $('#btn_cargar_defecto');
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Cargando...');

        $.ajax({
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/cn_plaza_etapasC.php?crear_plaza_etapas=true',
            type: 'POST',
            dataType: 'json',
            data: {
                cn_pla_id: <?= $_id_plaza ?>
            },
            success: function(response) {
                if (response == 1) {
                    inicializarEditor();
                    cargarEtapasPlaza('<?= $_id_plaza ?>');
                } else {
                    $btn.prop('disabled', false).html('<i class="bx bx-download me-1"></i> Cargar etapas por defecto');
                    Swal.fire('Error', 'No se pudieron generar las etapas.', 'error');
                }
            },
            error: function() {
                $btn.prop('disabled', false).html('<i class="bx bx-download me-1"></i> Cargar etapas por defecto');
                Swal.fire('Error', 'No se pudieron cargar las etapas.', 'error');
            }
        });
    }

    function borrarTodasEtapas() {
        Swal.fire({
            title: '¿Borrar todas las etapas?',
            text: 'Se eliminarán todas las etapas no bloqueadas de esta plaza.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, borrar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (!result.isConfirmed) return;
            $("#pnl_lista_destino li").each(function() {
                if (parseInt($(this).data("bloqueada")) === 1) return;
                $(this).css("background-color", "");
                $(this).find('.badge-orden').remove();
                $(this).find('.chk-obligatoria').closest('.form-check').remove();
                $(this).removeClass('d-flex align-items-center gap-2 fijo-inicio item-bloqueado');
                $("#pnl_lista_origen").append($(this));
            });
            actualizarNumerosOrden();
            guardarEtapasPlaza();
        });
    }

    function actualizarNumerosOrden() {
        $("#pnl_lista_destino li").each(function(i) {
            let badge = $(this).find('.badge-orden');
            let esInicioFijo = parseInt($(this).data("es-inicio-fijo")) === 1;
            let esFinFijo = parseInt($(this).data("es-fin-fijo")) === 1;
            let bloqueada = parseInt($(this).data("bloqueada")) === 1;

            if (badge.length === 0) {
                $(this).addClass('d-flex align-items-center gap-2');
                if (esInicioFijo) $(this).addClass('fijo-inicio');
                if (bloqueada) $(this).addClass('item-bloqueado');
                $(this).prepend(`<span class="badge bg-secondary badge-orden">${i + 1}</span>`);

                let badgeTipo = esInicioFijo ?
                    `<span class="badge badge-tipo ms-1" style="background:#0d6efd;color:#fff;">Inicio</span>` :
                    (esFinFijo ? `<span class="badge badge-tipo ms-1" style="background:#ffc107;color:#000;">Final</span>` : '');

                if (badgeTipo && $(this).find('.badge-tipo').length === 0) {
                    $(this).find('span:not(.badge-orden)').first().append(badgeTipo);
                }

                if ($(this).find('.chk-obligatoria').length === 0) {
                    let uid = $(this).data("id-plaet") || 'new_' + i;
                    $(this).append(`
                        <div class="form-check form-check-inline mb-0 ms-auto" style="cursor:default;" onclick="event.stopPropagation()">
                            <input class="form-check-input chk-obligatoria" type="checkbox"
                                   id="chk_obl_${uid}" title="Etapa obligatoria"
                                   ${bloqueada ? 'disabled' : ''}>
                            <label class="form-check-label small text-muted" for="chk_obl_${uid}">Obligatoria</label>
                        </div>`);
                }
            } else {
                badge.text(i + 1);
            }
        });

        $("#pnl_lista_origen .badge-orden").remove();
        $("#pnl_lista_origen .chk-obligatoria").closest('.form-check').remove();
    }

    function getListaDestino() {
        let lista = [];
        $("#pnl_lista_destino li").each(function(i) {
            lista.push({
                txt_id_etapa: $(this).data("id-etapa"),
                txt_id_plaet: $(this).data("id-plaet") || '',
                txt_orden: i + 1,
                txt_obligatoria: $(this).find('.chk-obligatoria').is(':checked') ? 1 : 0
            });
        });
        return lista;
    }

    function getListaOrigen() {
        let lista = [];
        $("#pnl_lista_origen li").each(function() {
            let idPlaet = $(this).data("id-plaet");
            if (idPlaet) lista.push({
                txt_id_etapa: $(this).data("id-etapa"),
                txt_id_plaet: idPlaet
            });
        });
        return lista;
    }

    function mostrarIndicador(estado) {
        let el = $("#indicador_guardado");
        let ico = estado === 'guardando' ?
            `<i class="bx bx-loader-alt bx-spin text-secondary me-1"></i> Guardando...` :
            `<i class="bx bx-check-circle text-success me-1"></i> Guardado`;
        el.html(ico).css('opacity', 1);
        if (estado === 'ok') setTimeout(() => el.css('opacity', 0), 2000);
    }

    function guardarEtapasPlaza() {
        let listaDestino = getListaDestino();
        let listaOrigen = getListaOrigen();
        if (listaDestino.length === 0 && listaOrigen.length === 0) return;
        mostrarIndicador('guardando');
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/cn_plaza_etapasC.php?guardar_bulk=true',
            type: 'POST',
            dataType: 'json',
            data: {
                id_plaza: <?= $_id_plaza ?>,
                lista_destino: listaDestino,
                lista_origen: listaOrigen
            },
            success: function(response) {
                if (response == 1) {
                    mostrarIndicador('ok');
                    cargarEtapasPlaza(<?= $_id_plaza ?>);
                } else {
                    Swal.fire('', 'Ocurrió un error al guardar.', 'warning');
                }
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseText, 'error');
            }
        });
    }
</script>

<!-- ESTADO INICIAL -->
<div id="pnl_inicio" class="text-center py-5" style="display:none;">
    <i class="bx bx-layer fs-1 text-muted d-block mb-3"></i>
    <p class="text-muted mb-4">Esta plaza aún no tiene etapas configuradas.</p>
    <button id="btn_cargar_defecto" class="btn btn-primary px-4" onclick="cargarEtapasPorDefecto()">
        <i class="bx bx-download me-1"></i> Cargar etapas por defecto
    </button>
</div>

<!-- EDITOR -->
<div id="pnl_editor" class="row pt-3" style="display:none;">
    <div class="col-md-5">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0 text-secondary fw-bold">
                <i class="bx bx-list-ul me-1"></i> Etapas disponibles
            </h6>
            <!--
            <button class="btn btn-outline-danger btn-sm" type="button" onclick="borrarTodasEtapas()">
                <i class="bx bx-trash me-1"></i> Borrar todas
            </button>
-->
        </div>
        <ul id="pnl_lista_origen" class="list-group lista-etapas pnl_etapas_sort"></ul>
    </div>

    <div class="col-md-2 d-flex align-items-center justify-content-center flex-column">
        <i class="bx bx-transfer fs-2 text-muted"></i>
        <small class="text-muted mt-1">Arrastra</small>
    </div>

    <div class="col-md-5">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0 text-success fw-bold">
                <i class="bx bx-check-circle me-1"></i> Etapas asignadas
                <small class="fw-normal text-muted">(el orden define el flujo)</small>
            </h6>
            <span id="indicador_guardado" style="opacity:0;"></span>
        </div>
        <ul id="pnl_lista_destino" class="list-group lista-etapas pnl_etapas_sort"></ul>
    </div>
</div>