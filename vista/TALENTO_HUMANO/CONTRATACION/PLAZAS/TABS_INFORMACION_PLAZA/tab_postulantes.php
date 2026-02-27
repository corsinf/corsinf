<button type="button" class="btn btn-outline-success btn-sm" onclick="nuevo_postulante()">
    <i class="bx bx-plus"></i> Nuevo Postulante
</button>

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
                <div class="row mb-2">
                    <div class="col-md-4">
                        <label for="ddl_id_tipo_seleccion" class="form-label">Tipo de Selección </label>
                        <select class="form-select form-select-sm select2-validation" id="ddl_id_tipo_seleccion" name="ddl_id_tipo_seleccion">
                            <option value="" selected hidden>-- Seleccione --</option>
                        </select>
                    </div>
                </div>
                <table class="table table-striped" id="tbl_postulantes_modal" style="width:100%">
                    <thead>
                        <tr>
                            <th style="width:35px">
                                <input type="checkbox" id="cbx_all_modal" class="form-check-input">
                            </th>
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
            if ($.fn.DataTable.isDataTable('#tbl_postulantes_modal')) {
                cargar_postulantes_modal(id_seleccion);
            }
        });
    });

    function cargar_seleted_postulante() {
        cargar_select2_url('ddl_id_tipo_seleccion', '../controlador/TALENTO_HUMANO/CATALOGOS/cn_cat_tipo_seleccionC.php?buscar=true', '', '#modal_agregar_postulante');
    }

    function nuevo_postulante() {
        var modalEl = document.getElementById('modal_agregar_postulante');
        var modal = bootstrap.Modal.getOrCreateInstance(modalEl);

        $(modalEl).off('shown.bs.modal').on('shown.bs.modal', function() {
            cargar_postulantes_modal(id_seleccion);
        });

        modal.show();
    }

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
                emptyTable: 'No hay postulantes en esta etapa.',
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

    function cargar_postulantes_modal(id_seleccion) {

        id_seleccion = id_seleccion;

        if (tabla_modal_postulantes && $.fn.DataTable.isDataTable('#tbl_postulantes_modal')) {
            tabla_modal_postulantes.destroy();
            $('#tbl_postulantes_modal tbody').empty();
        }

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
                    id: id_seleccion
                },
                dataSrc: ''
            },
            columns: [{
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
                        var n = ((item.th_pos_primer_apellido || '') + ' ' + (item.th_pos_segundo_apellido || '') + ' ' + (item.th_pos_primer_nombre || '') + ' ' + (item.th_pos_segundo_nombre || '')).trim();
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
            ],
            order: [
                [1, 'asc']
            ],
            drawCallback: function() {
                $('#cbx_all_modal').prop('checked', false);
            }
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
                    html: `Agregados: <b>${response.exitosos}</b> &nbsp; Fallidos: <b>${response.fallidos}</b>`
                });
                cargar_postulantes();
                console.log(ids);

            },
            error: function() {
                Swal.fire('Error', 'Ocurrió un error al agregar los postulantes.', 'error');
            }
        });
    }

    $(document).on('change', '#cbx_all_modal', function() {
        $('#tbl_postulantes_modal tbody .cbx-postulante-modal').prop('checked', $(this).is(':checked'));
    });

    $(document).on('change', '#tbl_postulantes_modal tbody .cbx-postulante-modal', function() {
        var total = $('#tbl_postulantes_modal tbody .cbx-postulante-modal').length;
        var marcados = $('#tbl_postulantes_modal tbody .cbx-postulante-modal:checked').length;
        $('#cbx_all_modal').prop('checked', total > 0 && total === marcados);
    });
</script>