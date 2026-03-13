<?php
$modulo_sistema = isset($_SESSION['INICIO']['MODULO_SISTEMA']) ? $_SESSION['INICIO']['MODULO_SISTEMA'] : '';
$_id_plaza      = isset($_GET['_id_plaza']) ? $_GET['_id_plaza'] : '';
?>

<script>
    function verificar_acciones_postulantes(plaza_estado, tipo_seleccion) {
        if (plaza_estado && plaza_estado.permite_postulacion == true) {
            $('#btn_postulante').show();
        } else {
            $('#btn_postulante').hide();
        }

        if (tipo_seleccion) {
            window._tipo_seleccion_descripcion = (tipo_seleccion.descripcion || '').trim().toUpperCase();
            window._tipo_seleccion_id = tipo_seleccion.id_tipo_seleccion || 0;
        }

        var desc = window._tipo_seleccion_descripcion || '';

        if (desc === 'INTERNA' || desc === 'EXTERNA') {
            $('#bloque_ddl_tipo_seleccion').hide();
            id_seleccion = window._tipo_seleccion_id;
            cargar_postulantes_modal(id_seleccion);
        } else if (desc === 'MIXTA') {
            $('#bloque_ddl_tipo_seleccion').show();
            id_seleccion = 0;
        }
    }

    function actualizar_boton_postulante(permite) {
        if (permite) {
            $('#btn_postulante').show();
        } else {
            $('#btn_postulante').hide();
        }
    }

    var _etapas_pendiente_recarga = false;

    $(document).on('shown.bs.tab', 'a[href="#tab_etapas_proceso"]', function() {
        if (_etapas_pendiente_recarga) {
            _etapas_pendiente_recarga = false;
            <?php if (!empty($_id_plaza)) { ?>
                cargar_etapas_tarjetas(<?= (int)$_id_plaza ?>);
            <?php } ?>
        }
    });
</script>

<div class="d-flex align-items-center justify-content-between mb-2">
    <div></div>
    <button id="btn_postulante" style="display: none;" type="button"
        class="btn btn-success btn-sm" onclick="nuevo_postulante()">
        <i class="bx bx-plus"></i> Nuevo Postulante
    </button>
</div>
<input type="hidden" id="txt_tipo_seleccion_id" value="">

<table class="table table-striped table-postulantes" id="tabla_plaza_postulantes" style="width:100%">
    <thead>
        <tr>
            <th>Postulante</th>
            <th>Cédula</th>
            <th>Correo</th>
            <th>Teléfono</th>
            <th class="text-center">Tipo</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<!-- Modal postulantes -->
<div class="modal fade" id="modal_agregar_postulante" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bx bx-user-plus me-2"></i>Agregar Postulantes</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <!-- Tipo de selección (solo MIXTA) -->
                <div class="row mb-3" id="bloque_ddl_tipo_seleccion" style="display:none;">
                    <div class="col-md-4">
                        <label for="ddl_id_tipo_seleccion" class="form-label fw-semibold small">Tipo de Selección</label>
                        <select class="form-select form-select-sm select2-validation"
                            id="ddl_id_tipo_seleccion" name="ddl_id_tipo_seleccion">
                            <option value="" selected hidden>-- Seleccione --</option>
                        </select>
                    </div>
                </div>

                <!-- Barra de filtros con checkboxes -->
                <div class="p-2 bg-light rounded border mb-3">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bx bx-filter-alt text-primary"></i>
                        <h6 class="text-muted fs-7 mb-0 fw-bold text-uppercase ls-1">Filtrar por requisitos de la plaza</h6>
                    </div>
                    <div class="d-flex align-items-center gap-4">
                        <div class="form-check form-switch mb-0">
                            <input type="checkbox" class="form-check-input" id="cbx_filtro_area"
                                onchange="aplicar_filtros_modal()">
                            <label for="cbx_filtro_area" class="form-check-label fw-semibold small text-primary">
                                <i class="bx bx-book-open me-1"></i>Área de Estudio
                            </label>
                        </div>
                        <div class="form-check form-switch mb-0">
                            <input type="checkbox" class="form-check-input" id="cbx_filtro_nivel"
                                onchange="aplicar_filtros_modal()">
                            <label for="cbx_filtro_nivel" class="form-check-label fw-semibold small text-primary">
                                <i class="bx bx-graduation me-1"></i>Nivel Académico
                            </label>
                        </div>
                    </div>
                </div>

                <table class="table table-striped" id="tbl_postulantes_modal" style="width:100%">
                    <thead>
                        <tr>
                            <th style="width:35px"><input type="checkbox" id="cbx_all_modal" class="form-check-input"></th>
                            <th>Postulante</th>
                            <th>Cédula</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success btn-sm" onclick="agregar_postulantes_seleccionados()">
                    <i class="bx bx-plus me-1"></i> Agregar seleccionados
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let tabla_plaza_postulantes = null;
    let tabla_modal_postulantes = null;
    let id_seleccion = 0;

    $(document).ready(function() {
        cargar_postulantes();
        cargar_seleted_postulante();

        $('#ddl_id_tipo_seleccion').on('change', function() {
            id_seleccion = $(this).val();
            aplicar_filtros_modal();
        });

        // Al cerrar el modal → resetear checkboxes
        $('#modal_agregar_postulante').on('hidden.bs.modal', function() {
            $('#cbx_filtro_area').prop('checked', false);
            $('#cbx_filtro_nivel').prop('checked', false);
        });
    });

    function cargar_seleted_postulante() {
        cargar_select2_url(
            'ddl_id_tipo_seleccion',
            '../controlador/TALENTO_HUMANO/CATALOGOS/cn_cat_tipo_seleccionC.php?buscar=true',
            '', '#modal_agregar_postulante', 0, {},
            function() {
                $('#ddl_id_tipo_seleccion option').filter(function() {
                    return $(this).text().trim().toUpperCase() === 'MIXTA';
                }).remove();
            }
        );
    }

    function nuevo_postulante() {
        var modalEl = document.getElementById('modal_agregar_postulante');
        var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        var desc = (window._tipo_seleccion_descripcion || '').trim().toUpperCase();

        $(modalEl).off('shown.bs.modal').on('shown.bs.modal', function() {
            if (desc === 'INTERNA' || desc === 'EXTERNA') {
                $('#bloque_ddl_tipo_seleccion').hide();
                id_seleccion = window._tipo_seleccion_id || 0;
                cargar_postulantes_modal(id_seleccion);
            } else {
                $('#bloque_ddl_tipo_seleccion').show();
                var val = $('#ddl_id_tipo_seleccion').val();
                if (val) {
                    cargar_postulantes_modal(val);
                } else {
                    _destruir_tabla_modal();
                }
            }
        });

        modal.show();
    }

    // ─── APLICAR FILTROS ─────────────────────────────────────────────────────
    function aplicar_filtros_modal() {
        var filtro_area = $('#cbx_filtro_area').is(':checked');
        var filtro_nivel = $('#cbx_filtro_nivel').is(':checked');

        // Ninguno marcado → sin filtro
        if (!filtro_area && !filtro_nivel) {
            cargar_postulantes_modal(id_seleccion);
            return;
        }

        var areas_ids = [];
        var niveles_ids = [];

        if (filtro_area) {
            areas_ids = window._areas_estudio_requeridas || [];
            if (areas_ids.length === 0) {
                Swal.fire('Aviso', 'Esta plaza no tiene áreas de estudio requeridas definidas.', 'info');
                $('#cbx_filtro_area').prop('checked', false);
                // Si el otro también está vacío, no filtrar
                if (!filtro_nivel) {
                    cargar_postulantes_modal(id_seleccion);
                    return;
                }
            }
        }

        if (filtro_nivel) {
            niveles_ids = [...new Set(
                (window._detalle_niveles_academicos || []).map(function(n) {
                    return n.id_nivel_academico;
                })
            )];
            if (niveles_ids.length === 0) {
                Swal.fire('Aviso', 'Esta plaza no tiene niveles académicos requeridos definidos.', 'info');
                $('#cbx_filtro_nivel').prop('checked', false);
                if (!filtro_area || areas_ids.length === 0) {
                    cargar_postulantes_modal(id_seleccion);
                    return;
                }
            }
        }

        cargar_postulantes_modal_filtrado(id_seleccion, areas_ids, niveles_ids);
    }

    // ─── TABLA MODAL SIN FILTRO ───────────────────────────────────────────────
    function cargar_postulantes_modal(id_sel) {
        _destruir_tabla_modal();

        tabla_modal_postulantes = $('#tbl_postulantes_modal').DataTable({
            responsive: true,
            dom: 'frtip',
            language: {
                url: '../assets/plugins/datatable/spanish.json',
                emptyTable: 'No hay postulantes disponibles.',
                zeroRecords: 'No se encontraron postulantes.'
            },
            ajax: {
                url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_postulantesC.php?listar_todos_postulantes=true',
                type: 'POST',
                data: {
                    id: id_sel
                },
                dataSrc: ''
            },
            columns: _columnas_modal(),
            order: [
                [1, 'asc']
            ],
            drawCallback: function() {
                $('#cbx_all_modal').prop('checked', false);
            }
        });
    }

    // ─── TABLA MODAL CON FILTRO ───────────────────────────────────────────────
    function cargar_postulantes_modal_filtrado(id_sel, areas_ids, niveles_ids) {
        _destruir_tabla_modal();

        tabla_modal_postulantes = $('#tbl_postulantes_modal').DataTable({
            responsive: true,
            dom: 'frtip',
            language: {
                url: '../assets/plugins/datatable/spanish.json',
                emptyTable: 'No hay postulantes que coincidan con los filtros aplicados.',
                zeroRecords: 'No se encontraron postulantes.'
            },
            ajax: {
                url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_postulantesC.php?listar_postulantes_filtro_combinado=true',
                type: 'POST',
                data: {
                    id_tipo_seleccion: id_sel || 0,
                    areas_ids: JSON.stringify(areas_ids || []),
                    niveles_ids: JSON.stringify(niveles_ids || [])
                },
                dataSrc: ''
            },
            columns: _columnas_modal(),
            order: [
                [1, 'asc']
            ],
            drawCallback: function() {
                $('#cbx_all_modal').prop('checked', false);
            }
        });
    }

    // ─── HELPERS ─────────────────────────────────────────────────────────────
    function _destruir_tabla_modal() {
        if (tabla_modal_postulantes && $.fn.DataTable.isDataTable('#tbl_postulantes_modal')) {
            tabla_modal_postulantes.destroy();
            $('#tbl_postulantes_modal tbody').empty();
        }
    }

    function _columnas_modal() {
        return [{
                data: null,
                orderable: false,
                className: 'text-center',
                width: '35px',
                render: function(d, t, item) {
                    return '<input type="checkbox" class="cbx-postulante-modal form-check-input" value="' + (item._id || '') + '">';
                }
            },
            {
                data: null,
                render: function(d, t, item) {
                    var n = [
                        item.th_pos_primer_apellido || '',
                        item.th_pos_segundo_apellido || '',
                        item.th_pos_primer_nombre || '',
                        item.th_pos_segundo_nombre || ''
                    ].join(' ').replace(/\s+/g, ' ').trim();
                    return '<strong>' + (n || 'Sin nombre') + '</strong>';
                }
            },
            {
                data: 'th_pos_cedula',
                defaultContent: '<span class="text-muted">—</span>'
            },
            {
                data: 'th_pos_correo',
                defaultContent: '<span class="text-muted">—</span>'
            },
            {
                data: 'th_pos_telefono_1',
                defaultContent: '<span class="text-muted">—</span>'
            }
        ];
    }

    // ─── TABLA PRINCIPAL ─────────────────────────────────────────────────────
    function cargar_postulantes() {
        if (tabla_plaza_postulantes && $.fn.DataTable.isDataTable('#tabla_plaza_postulantes')) {
            tabla_plaza_postulantes.destroy();
            $('#tabla_plaza_postulantes tbody').empty();
        }

        tabla_plaza_postulantes = $('#tabla_plaza_postulantes').DataTable({
            responsive: true,
            dom: 'frtip',
            language: {
                url: '../assets/plugins/datatable/spanish.json',
                emptyTable: 'No hay postulantes en esta plaza.',
                zeroRecords: 'No se encontraron postulantes.'
            },
            ajax: {
                url: '../controlador/TALENTO_HUMANO/CONTRATACION/cn_postulacionC.php?listar_por_plaza=true',
                type: 'POST',
                data: {
                    cn_pla_id: '<?= $_id_plaza ?>'
                },
                dataSrc: ''
            },
            columns: [{
                    data: 'nombre_completo',
                    render: function(d) {
                        return '<strong>' + (d || 'Sin nombre') + '</strong>';
                    }
                },
                {
                    data: 'th_pos_cedula',
                    defaultContent: '<span class="text-muted">—</span>'
                },
                {
                    data: 'th_pos_correo',
                    defaultContent: '<span class="text-muted">—</span>'
                },
                {
                    data: 'th_pos_telefono_1',
                    defaultContent: '<span class="text-muted">—</span>'
                },
                {
                    data: 'tipo_postulante',
                    className: 'text-center',
                    render: function(d) {
                        return d === 'Interno' ?
                            '<span class="badge bg-primary">Interno</span>' :
                            '<span class="badge bg-success">Externo</span>';
                    }
                }
            ],
            order: [
                [0, 'asc']
            ]
        });
    }

    function eliminar_postulacion(id) {
        Swal.fire({
            title: '¿Eliminar postulante?',
            text: 'Se eliminará al postulante de esta plaza.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (!result.isConfirmed) return;
            $.ajax({
                url: '../controlador/TALENTO_HUMANO/CONTRATACION/cn_postulacionC.php?eliminar=true',
                type: 'POST',
                dataType: 'json',
                data: {
                    _id: id
                },
                success: function() {
                    cargar_postulantes();
                }
            });
        });
    }

    function agregar_postulantes_seleccionados() {
        var ids = [];
        $('#tbl_postulantes_modal tbody .cbx-postulante-modal:checked').each(function() {
            ids.push($(this).val());
        });

        if (ids.length === 0) {
            Swal.fire('Aviso', 'Seleccione al menos un postulante.', 'warning');
            return;
        }

        $.ajax({
            url: '../controlador/TALENTO_HUMANO/CONTRATACION/cn_postulacionC.php?crear_postulacion_bulk=true',
            type: 'POST',
            dataType: 'json',
            data: {
                cn_pla_id: <?= (int)$_id_plaza ?>,
                th_pos_ids: JSON.stringify(ids)
            },
            success: function(response) {
                if (response.error) {
                    Swal.fire('Error', response.error, 'error');
                    return;
                }

                bootstrap.Modal.getInstance(document.getElementById('modal_agregar_postulante')).hide();

                Swal.fire({
                    icon: response.fallidos > 0 ? 'warning' : 'success',
                    title: 'Operación completada',
                    html: response.fallidos > 0 ?
                        'Agregados: <b>' + response.exitosos + '</b> &nbsp; Fallidos: <b>' + response.fallidos + '</b>' :
                        '<b>' + response.exitosos + '</b> postulante(s) agregado(s) correctamente.',
                    timer: 2000,
                    showConfirmButton: false
                }).then(function() {
                    cargar_postulantes();
                    var $tabEtapas = $('a[href="#tab_etapas_proceso"]');
                    if ($tabEtapas.length) {
                        $tabEtapas[0].click();
                        setTimeout(function() {
                            <?php if (!empty($_id_plaza)) { ?>
                                cargar_etapas_tarjetas(<?= (int)$_id_plaza ?>);
                            <?php } ?>
                        }, 150);
                    } else {
                        _etapas_pendiente_recarga = true;
                    }
                });
            },
            error: function() {
                Swal.fire('Error', 'Ocurrió un error al agregar los postulantes.', 'error');
            }
        });
    }

    // Select-all modal
    $(document).on('change', '#cbx_all_modal', function() {
        $('#tbl_postulantes_modal tbody .cbx-postulante-modal').prop('checked', $(this).is(':checked'));
    });

    $(document).on('change', '#tbl_postulantes_modal tbody .cbx-postulante-modal', function() {
        var total = $('#tbl_postulantes_modal tbody .cbx-postulante-modal').length;
        var marcados = $('#tbl_postulantes_modal tbody .cbx-postulante-modal:checked').length;
        $('#cbx_all_modal').prop('checked', total > 0 && total === marcados);
    });
</script>