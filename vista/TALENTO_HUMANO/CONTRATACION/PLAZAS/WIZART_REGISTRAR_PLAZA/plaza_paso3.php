<?php
$_id_plaza = isset($_GET['_id_plaza']) ? (int)$_GET['_id_plaza'] : 0;
$color_destino = '#d4edda';
$color_final   = '#fff3cd';
$etapas_por_defecto = [2, 3, 4, 6];
?>

<style>
    .list-group-item {
        cursor: grab;
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

    .badge-final {
        font-size: 0.65rem;
        background-color: #ffc107;
        color: #000;
        margin-left: 4px;
    }

    #indicador_guardado {
        font-size: 0.78rem;
        transition: opacity 0.4s ease;
    }
</style>

<script>
    const ETAPAS_POR_DEFECTO = <?= json_encode($etapas_por_defecto) ?>;

    $(document).ready(function() {
        cargarEtapasPlaza('<?= $_id_plaza ?>');

        $(".pnl_etapas_sort").sortable({
            connectWith: ".pnl_etapas_sort",
            placeholder: "sortable-placeholder",
            cursor: "move",
            revert: 150,

            change: function(event, ui) {
                let placeholder = ui.placeholder;
                let parentId = placeholder.parent().attr("id");
                if (parentId !== "pnl_lista_destino") return;

                let isDraggingFinal = parseInt(ui.item.data("es-final")) === 1;
                let draggingId = parseInt(ui.item.data("id-etapa"));
                let destino = $("#pnl_lista_destino");

                // Items reales sin placeholder ni helper
                let itemsReales = destino.find(
                    'li:not(.ui-sortable-placeholder):not(.ui-sortable-helper)'
                );

                if (isDraggingFinal) {

                    // Calcular posición exacta correcta:
                    // Debe ir DESPUÉS del último no-final Y después de todos los finales con id < draggingId
                    // Debe ir ANTES del primer final con id > draggingId

                    let posicionDespues = null; // item tras el cual insertar
                    let posicionAntes = null; // item antes del cual insertar

                    // Paso 1: debe ir después del último no-final
                    let lastNonFinal = itemsReales.filter('[data-es-final="0"]').last();
                    if (lastNonFinal.length > 0) {
                        posicionDespues = lastNonFinal;
                    }

                    // Paso 2: entre finales, después de todos los que tienen id < draggingId
                    itemsReales.filter('[data-es-final="1"]').each(function() {
                        let id = parseInt($(this).data("id-etapa"));
                        if (id < draggingId) {
                            posicionDespues = $(this); // el más cercano por debajo
                        }
                    });

                    // Paso 3: antes del primer final con id > draggingId
                    itemsReales.filter('[data-es-final="1"]').each(function() {
                        let id = parseInt($(this).data("id-etapa"));
                        if (id > draggingId && posicionAntes === null) {
                            posicionAntes = $(this);
                        }
                    });

                    let currentIdx = placeholder.index();

                    // Aplicar restricción de "antes" primero
                    if (posicionAntes !== null && currentIdx >= posicionAntes.index()) {
                        posicionAntes.before(placeholder);
                        return;
                    }

                    // Luego restricción de "después"
                    if (posicionDespues !== null && currentIdx <= posicionDespues.index()) {
                        posicionDespues.after(placeholder);
                        return;
                    }

                } else {
                    // No-final: no puede cruzar la primera final
                    let firstFinal = itemsReales.filter('[data-es-final="1"]').first();
                    if (firstFinal.length > 0 && placeholder.index() >= firstFinal.index()) {
                        firstFinal.before(placeholder);
                    }
                }
            },

            update: function(event, ui) {
                let item = ui.item;
                let parentId = item.parent().attr("id");

                if (parentId === "pnl_lista_destino") {
                    let esFinalVal = parseInt(item.data("es-final")) === 1;
                    item.css("background-color", esFinalVal ? "<?= $color_final ?>" : "<?= $color_destino ?>");
                } else {
                    item.css("background-color", "");
                }

                actualizarNumerosOrden();
                guardarEtapasPlaza();
            }
        }).disableSelection();

        $(document).on('change', '#pnl_lista_destino .chk-obligatoria', function() {
            guardarEtapasPlaza();
        });
    });

    // ─── Carga de datos ───────────────────────────────────────────────────────

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

            let catalogo = resCat[0];
            let asignadas = resAsig[0];

            asignadas.sort((a, b) => a.cn_plaet_orden - b.cn_plaet_orden);

            let idsAsignados = asignadas.map(e => String(e.id_etapa));

            let itemsDestino = asignadas.map((item, i) => buildItemDestino(
                item.id_etapa,
                item._id,
                item.etapa_nombre || 'Sin nombre',
                item.etapa_es_final || 0,
                item.cn_plaet_obligatoria || 0,
                i
            )).join('');

            let itemsOrigen = catalogo
                .filter(item => !idsAsignados.includes(String(item._id)))
                .map(item => buildItemOrigen(item._id, item.nombre, item.es_final))
                .join('');

            $("#pnl_lista_destino").html(itemsDestino);
            $("#pnl_lista_origen").html(itemsOrigen);
        }).fail(function() {
            Swal.fire('Error', 'No se pudieron cargar las etapas.', 'error');
        });
    }

    // ─── Builders de <li> ─────────────────────────────────────────────────────

    function buildItemDestino(idEtapa, idPlaet, nombre, esFinalVal, obligatoria, index) {
        let bg = parseInt(esFinalVal) === 1 ? "<?= $color_final ?>" : "<?= $color_destino ?>";
        let badgeFinal = parseInt(esFinalVal) === 1 ? `<span class="badge badge-final">Final</span>` : '';
        let uid = idPlaet || 'n' + index;
        return `<li class="list-group-item d-flex align-items-center gap-2"
                    data-id-etapa="${idEtapa}"
                    data-id-plaet="${idPlaet}"
                    data-es-final="${esFinalVal}"
                    style="background-color:${bg};">
                    <span class="badge bg-secondary badge-orden">${index + 1}</span>
                    <span class="flex-grow-1">${nombre} ${badgeFinal}</span>
                    <div class="form-check form-check-inline mb-0 ms-auto" style="cursor:default;" onclick="event.stopPropagation()">
                        <input class="form-check-input chk-obligatoria"
                               type="checkbox"
                               id="chk_obl_${uid}"
                               title="Etapa obligatoria"
                               ${parseInt(obligatoria) === 1 ? 'checked' : ''}>
                        <label class="form-check-label small text-muted" for="chk_obl_${uid}">Obligatoria</label>
                    </div>
                </li>`;
    }

    function buildItemOrigen(idEtapa, nombre, esFinalVal) {
        let badgeFinal = parseInt(esFinalVal) === 1 ?
            `<span class="badge badge-final ms-1">Final</span>` : '';
        return `<li class="list-group-item"
                    data-id-etapa="${idEtapa}"
                    data-id-plaet=""
                    data-es-final="${esFinalVal || 0}">
                    ${nombre || 'Sin nombre'} ${badgeFinal}
                </li>`;
    }

    // ─── Cargar por defecto ───────────────────────────────────────────────────

    function cargarEtapasPorDefecto() {
        if (!window._catalogo_etapas || window._catalogo_etapas.length === 0) {
            Swal.fire('Aviso', 'Primero deben cargarse las etapas.', 'warning');
            return;
        }

        let origen = $("#pnl_lista_origen");
        let destino = $("#pnl_lista_destino");

        ETAPAS_POR_DEFECTO.forEach(function(idEtapa) {
            let itemOrigen = origen.find(`li[data-id-etapa="${idEtapa}"]`);
            if (itemOrigen.length === 0) return;

            let info = window._catalogo_etapas.find(e => String(e._id) === String(idEtapa));
            if (!info) return;

            let esFinalVal = parseInt(info.es_final) || 0;

            if (esFinalVal === 1) {
                // Insertar entre finales respetando orden por id_etapa
                let insertado = false;
                destino.find('li[data-es-final="1"]').each(function() {
                    if (parseInt($(this).data("id-etapa")) > idEtapa) {
                        $(this).before(itemOrigen);
                        insertado = true;
                        return false;
                    }
                });
                if (!insertado) destino.append(itemOrigen);
            } else {
                // No-final: antes de la primera final
                let primeraFinal = destino.find('li[data-es-final="1"]').first();
                if (primeraFinal.length > 0) {
                    primeraFinal.before(itemOrigen);
                } else {
                    destino.append(itemOrigen);
                }
            }

            itemOrigen.css("background-color", esFinalVal === 1 ? "<?= $color_final ?>" : "<?= $color_destino ?>");
        });

        actualizarNumerosOrden();
        guardarEtapasPlaza();
    }

    // ─── Borrar todas ─────────────────────────────────────────────────────────

    function borrarTodasEtapas() {
        Swal.fire({
            title: '¿Borrar todas las etapas?',
            text: 'Se eliminarán todas las etapas asignadas a esta plaza.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, borrar todo',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (!result.isConfirmed) return;

            $("#pnl_lista_destino li").each(function() {
                $(this).css("background-color", "");
                $(this).find('.badge-orden').remove();
                $(this).find('.chk-obligatoria').closest('.form-check').remove();
                $(this).removeClass('d-flex align-items-center gap-2');
                $("#pnl_lista_origen").append($(this));
            });

            actualizarNumerosOrden();
            guardarEtapasPlaza();
        });
    }

    // ─── Actualizar badges de orden ───────────────────────────────────────────

    function actualizarNumerosOrden() {
        $("#pnl_lista_destino li").each(function(i) {
            let badge = $(this).find('.badge-orden');
            let esFinalVal = parseInt($(this).data("es-final")) === 1;
            $(this).css("background-color", esFinalVal ? "<?= $color_final ?>" : "<?= $color_destino ?>");

            if (badge.length === 0) {
                $(this).addClass('d-flex align-items-center gap-2');
                $(this).prepend(`<span class="badge bg-secondary badge-orden">${i + 1}</span>`);

                if (esFinalVal && $(this).find('.badge-final').length === 0) {
                    $(this).find('span:not(.badge-orden)').first()
                        .append(`<span class="badge badge-final ms-1">Final</span>`);
                }

                if ($(this).find('.chk-obligatoria').length === 0) {
                    let uid = $(this).data("id-plaet") || 'new_' + i;
                    $(this).append(`
                        <div class="form-check form-check-inline mb-0 ms-auto" style="cursor:default;" onclick="event.stopPropagation()">
                            <input class="form-check-input chk-obligatoria"
                                   type="checkbox"
                                   id="chk_obl_${uid}"
                                   title="Etapa obligatoria">
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

    // ─── Getters ──────────────────────────────────────────────────────────────

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
            if (idPlaet) {
                lista.push({
                    txt_id_etapa: $(this).data("id-etapa"),
                    txt_id_plaet: idPlaet
                });
            }
        });
        return lista;
    }

    // ─── Guardado ─────────────────────────────────────────────────────────────

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

<!-- TEMPLATE -->
<div class="row pt-3">

    <!-- ORIGEN -->
    <div class="col-md-5">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0 text-secondary fw-bold">
                <i class="bx bx-list-ul me-1"></i> Etapas disponibles
            </h6>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary btn-sm" type="button"
                    onclick="cargarEtapasPorDefecto()"
                    title="Cargar etapas predefinidas">
                    <i class="bx bx-download me-1"></i> Cargar por defecto
                </button>
                <button class="btn btn-outline-danger btn-sm" type="button"
                    onclick="borrarTodasEtapas()"
                    title="Quitar todas las etapas asignadas">
                    <i class="bx bx-trash me-1"></i> Borrar todas
                </button>
            </div>
        </div>
        <ul id="pnl_lista_origen" class="list-group lista-etapas pnl_etapas_sort">
            <li class="list-group-item text-muted text-center small fst-italic">Cargando...</li>
        </ul>
    </div>

    <!-- FLECHA central -->
    <div class="col-md-2 d-flex align-items-center justify-content-center flex-column">
        <i class="bx bx-transfer fs-2 text-muted"></i>
        <small class="text-muted mt-1">Arrastra</small>
    </div>

    <!-- DESTINO -->
    <div class="col-md-5">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0 text-success fw-bold">
                <i class="bx bx-check-circle me-1"></i> Etapas asignadas
                <small class="fw-normal text-muted">(el orden define el flujo)</small>
            </h6>
            <span id="indicador_guardado" style="opacity:0;"></span>
        </div>
        <ul id="pnl_lista_destino" class="list-group lista-etapas pnl_etapas_sort">
            <li class="list-group-item text-muted text-center small fst-italic">Cargando...</li>
        </ul>
    </div>
</div>