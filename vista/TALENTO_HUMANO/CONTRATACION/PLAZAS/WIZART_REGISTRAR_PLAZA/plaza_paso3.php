<?php
$_id_plaza = isset($_GET['_id_plaza']) ? (int)$_GET['_id_plaza'] : 0;
$color_destino = '#d4edda';
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

    #indicador_guardado {
        font-size: 0.78rem;
        transition: opacity 0.4s ease;
    }
</style>

<script>
    $(document).ready(function() {
        cargarEtapasPlaza('<?= $_id_plaza ?>');

        $(".pnl_etapas_sort").sortable({
            connectWith: ".pnl_etapas_sort",
            placeholder: "sortable-placeholder",
            cursor: "move",
            revert: 150,
            update: function(event, ui) {
                let item = ui.item;
                let parentId = item.parent().attr("id");

                if (parentId === "pnl_lista_destino") {
                    item.css("background-color", "<?= $color_destino ?>");
                } else {
                    item.css("background-color", "");
                }

                actualizarNumerosOrden();
                guardarEtapasPlaza(); // Auto-guardar al mover
            }
        }).disableSelection();

        // Auto-guardar al cambiar cualquier checkbox (delegado, funciona aunque se genere dinámicamente)
        $(document).on('change', '#pnl_lista_destino .chk-obligatoria', function() {
            guardarEtapasPlaza();
        });
    });

    function cargarEtapasPlaza(id_plaza) {
        let req_catalogo = $.ajax({
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/cn_cat_plaza_etapasC.php?listar=true',
            type: 'POST',
            dataType: 'json'
        });

        let req_asignadas = $.ajax({
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/cn_plaza_etapasC.php?listar=true',
            type: 'POST',
            data: {
                id_plaza: id_plaza
            },
            dataType: 'json'
        });

        $.when(req_catalogo, req_asignadas).done(function(resCat, resAsig) {
            let catalogo = resCat[0];
            let asignadas = resAsig[0];

            asignadas.sort((a, b) => a.cn_plaet_orden - b.cn_plaet_orden);

            let idsAsignados = asignadas.map(e => String(e.id_etapa));

            let itemsDestino = asignadas.map((item, i) =>
                `<li class="list-group-item d-flex align-items-center gap-2"
                     data-id-etapa="${item.id_etapa}"
                     data-id-plaet="${item._id}"
                     style="background-color: <?= $color_destino ?>;">
                    <span class="badge bg-secondary badge-orden">${i + 1}</span>
                    <span class="flex-grow-1">${item.etapa_nombre || 'Sin nombre'}</span>
                    <div class="form-check form-check-inline mb-0 ms-auto" style="cursor:default;" onclick="event.stopPropagation()">
                        <input class="form-check-input chk-obligatoria"
                               type="checkbox"
                               id="chk_obl_${item._id}"
                               title="Etapa obligatoria"
                               ${item.cn_plaet_obligatoria == 1 ? 'checked' : ''}>
                        <label class="form-check-label small text-muted" for="chk_obl_${item._id}">Obligatoria</label>
                    </div>
                </li>`
            ).join('');

            let itemsOrigen = catalogo
                .filter(item => !idsAsignados.includes(String(item._id)))
                .map(item =>
                    `<li class="list-group-item"
                         data-id-etapa="${item._id}"
                         data-id-plaet="">
                         ${item.nombre || 'Sin nombre'}
                     </li>`
                ).join('');

            $("#pnl_lista_destino").html(itemsDestino);
            $("#pnl_lista_origen").html(itemsOrigen);
        }).fail(function() {
            Swal.fire('Error', 'No se pudieron cargar las etapas.', 'error');
        });
    }

    function actualizarNumerosOrden() {
        $("#pnl_lista_destino li").each(function(i) {
            let badge = $(this).find('.badge-orden');
            if (badge.length === 0) {
                $(this).addClass('d-flex align-items-center gap-2');
                $(this).prepend(`<span class="badge bg-secondary badge-orden">${i + 1}</span>`);

                if ($(this).find('.chk-obligatoria').length === 0) {
                    let uid = $(this).data("id-plaet") || 'new_' + i;
                    $(this).append(`
                        <div class="form-check form-check-inline mb-0 ms-auto" style="cursor:default;" onclick="event.stopPropagation()">
                            <input class="form-check-input chk-obligatoria"
                                   type="checkbox"
                                   id="chk_obl_${uid}"
                                   title="Etapa obligatoria">
                            <label class="form-check-label small text-muted" for="chk_obl_${uid}">Obligatoria</label>
                        </div>
                    `);
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
            if (idPlaet) {
                lista.push({
                    txt_id_etapa: $(this).data("id-etapa"),
                    txt_id_plaet: idPlaet
                });
            }
        });
        return lista;
    }

    function mostrarIndicador(estado) {
        let el = $("#indicador_guardado");
        let ico = estado === 'guardando' ?
            `<i class="bx bx-loader-alt bx-spin text-secondary me-1"></i> Guardando...` :
            `<i class="bx bx-check-circle text-success me-1"></i> Guardado`;

        el.html(ico).css('opacity', 1);

        if (estado === 'ok') {
            setTimeout(() => el.css('opacity', 0), 2000);
        }
    }

    function guardarEtapasPlaza() {
        let listaDestino = getListaDestino();
        let listaOrigen = getListaOrigen();

        if (listaDestino.length === 0) return;

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
                    // Recargar silenciosamente para actualizar data-id-plaet de los nuevos
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
        <h6 class="mb-2 text-secondary fw-bold">
            <i class="bx bx-list-ul me-1"></i> Etapas disponibles
        </h6>
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
            <!-- Indicador de guardado automatico -->
            <span id="indicador_guardado" style="opacity:0;"></span>
        </div>
        <ul id="pnl_lista_destino" class="list-group lista-etapas pnl_etapas_sort">
            <li class="list-group-item text-muted text-center small fst-italic">Cargando...</li>
        </ul>
    </div>
</div>