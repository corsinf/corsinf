<?php
$_id_plaza = isset($_GET['_id_plaza']) ? (int)$_GET['_id_plaza'] : 0;

$color_destino = '#d4edda'; // verde claro para etapas asignadas
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
</style>

<script>
    $(document).ready(function() {
        cargarEtapasPlaza('<?= $_id_plaza ?>');

        // Conectar ambas listas para arrastrar entre ellas
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
                    // Si vuelve al origen se limpia el id de registro asignado
                    // (se mantendrá en data-id-plaet para poder eliminarlo al guardar)
                }

                actualizarNumerosOrden(); // Redibujar los badges de orden
            }
        }).disableSelection();
    });

    /**
     * Carga las etapas del catálogo y las etapas ya asignadas a la plaza.
     * Llama a ambos endpoints en paralelo y luego separa las listas.
     */
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
            let catalogo = resCat[0]; // todas las etapas del catálogo
            let asignadas = resAsig[0]; // etapas ya asignadas a esta plaza

            // Ordenar las asignadas por cn_plaet_orden
            asignadas.sort((a, b) => a.cn_plaet_orden - b.cn_plaet_orden);

            // IDs de etapas ya asignadas (para excluirlas del origen)
            let idsAsignados = asignadas.map(e => String(e.id_etapa));

            // ── Lista DESTINO (ya asignadas) ───────────────────────────────
            let itemsDestino = asignadas.map((item, i) =>
                `<li class="list-group-item d-flex align-items-center gap-2"
                    data-id-etapa="${item.id_etapa}"
                    data-id-plaet="${item._id}"
                    style="background-color: <?= $color_destino ?>;">

                    <!-- Badge de orden -->
                    <span class="badge bg-secondary badge-orden">${i + 1}</span>

                    <!-- Nombre de la etapa -->
                    <span class="flex-grow-1">${item.etapa_nombre || 'Sin nombre'}</span>

                    <!-- Checkbox obligatoria -->
                    <div class="form-check form-check-inline mb-0 ms-auto" style="cursor:default;" onclick="event.stopPropagation()">
                        <input class="form-check-input chk-obligatoria"
                            type="checkbox"
                            id="chk_obl_${item._id}"
                            title="Etapa obligatoria"
                            ${item.cn_plaet_obligatoria == 1 ? 'checked' : ''}>
                        <label class="form-check-label small text-muted" for="chk_obl_${item._id}">
                            Obligatoria
                        </label>
                    </div>
                </li>`
            ).join('');

            // ── Lista ORIGEN (no asignadas aún) ───────────────────────────
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

    /**
     * Redibujar los badges de número de orden en la lista destino.
     */
    function actualizarNumerosOrden() {
        $("#pnl_lista_destino li").each(function(i) {
            let badge = $(this).find('.badge-orden');
            if (badge.length === 0) {
                // Es un elemento que viene del origen, no tiene checkbox aún → agregarlo
                $(this).find('span').first().before(`<span class="badge bg-secondary badge-orden">${i + 1}</span>`);

                // Agregar checkbox si no existe
                if ($(this).find('.chk-obligatoria').length === 0) {
                    let idPlaet = $(this).data("id-plaet") || 'new_' + i;
                    $(this).addClass('d-flex align-items-center gap-2');
                    $(this).append(`
                    <div class="form-check form-check-inline mb-0 ms-auto" style="cursor:default;" onclick="event.stopPropagation()">
                        <input class="form-check-input chk-obligatoria"
                               type="checkbox"
                               id="chk_obl_${idPlaet}"
                               title="Etapa obligatoria">
                        <label class="form-check-label small text-muted" for="chk_obl_${idPlaet}">
                            Obligatoria
                        </label>
                    </div>
                `);
                }
            } else {
                badge.text(i + 1);
            }
        });

        // Limpiar badges y checkboxes en origen
        $("#pnl_lista_origen .badge-orden").remove();
        $("#pnl_lista_origen .chk-obligatoria").closest('.form-check').remove();
    }

    /**
     * Construye el array de la lista destino para enviar al servidor.
     */
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

    /**
     * Construye el array de la lista origen (solo los que tenían id-plaet,
     * es decir los que fueron movidos de vuelta al origen).
     */
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

    /**
     * Guardar las asignaciones.
     */
    function guardarEtapasPlaza() {
        let listaDestino = getListaDestino();
        let listaOrigen = getListaOrigen();

        if (listaDestino.length === 0) {
            Swal.fire('Aviso', 'Debe asignar al menos una etapa a la plaza.', 'warning');
            return;
        }

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
                    Swal.fire('', 'Etapas guardadas correctamente.', 'success');
                    cargarEtapasPlaza(<?= $_id_plaza ?>); // Recargar para actualizar data-id-plaet
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

<!-- ═══════════════════════════════════════════════ TEMPLATE ══ -->
<div class="row pt-3">

    <!-- ORIGEN: etapas disponibles -->
    <div class="col-md-5">
        <h6 class="mb-2 text-secondary fw-bold">
            <i class="bx bx-list-ul me-1"></i> Etapas disponibles
        </h6>
        <ul id="pnl_lista_origen"
            class="list-group lista-etapas pnl_etapas_sort">
            <li class="list-group-item text-muted text-center small fst-italic">
                Cargando...
            </li>
        </ul>
    </div>

    <!-- FLECHA central -->
    <div class="col-md-2 d-flex align-items-center justify-content-center flex-column">
        <i class="bx bx-transfer fs-2 text-muted"></i>
        <small class="text-muted mt-1">Arrastra</small>
    </div>

    <!-- DESTINO: etapas asignadas a la plaza -->
    <div class="col-md-5">
        <h6 class="mb-2 text-success fw-bold">
            <i class="bx bx-check-circle me-1"></i> Etapas asignadas
            <small class="fw-normal text-muted">(el orden define el flujo)</small>
        </h6>
        <ul id="pnl_lista_destino"
            class="list-group lista-etapas pnl_etapas_sort">
            <li class="list-group-item text-muted text-center small fst-italic">
                Cargando...
            </li>
        </ul>
    </div>
</div>