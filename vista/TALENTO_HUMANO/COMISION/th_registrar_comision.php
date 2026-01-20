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
    $(document).ready(function() {
        <?php if (isset($_GET['_id'])) { ?>
            datos_col(<?= $_id ?>);
            cargar_personas_comisiones();
        <?php } ?>
        cargar_selects2();

        // Evento para detectar cuando se cambia de página en la tabla de personas
        $('#tbl_personas').on('page.dt', function() {
            $('#cbx_per_com_all').prop('checked', false);
            console.log('Página cambiada');
        });
    });

    /**
     * Comisiones
     */
    function cargar_selects2(enModal = false) {
        url_comisionesC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_comisionC.php?buscar=true';
        cargar_select2_url('ddl_comisiones', url_comisionesC, '-- Seleccione --', '#modal_blank');
        url_comisionesTodosC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_comisionC.php?buscar=true';
        cargar_select2_url('ddl_comision_destino', url_comisionesTodosC, '-- Seleccione --', '#modalMoverPersonas');
    }

    function datos_col(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_comisionC.php?listar_comisiones=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                console.log(response);
                $('#txt_codigo').val(response[0].codigo);
                $('#txt_nombre').val(response[0].nombre);
                $('#txt_descripcion').val(response[0].descripcion);
            }
        });
    }

    function editar_insertar() {
        var txt_codigo = $('#txt_codigo').val();
        var txt_nombre = $('#txt_nombre').val();
        var txt_descripcion = $('#txt_descripcion').val();

        var parametros = {
            '_id': '<?= $_id ?>',
            'txt_codigo': txt_codigo,
            'txt_nombre': txt_nombre,
            'txt_descripcion': txt_descripcion,
        };

        if ($("#form_comision").valid()) {
            insertar(parametros);
        }
    }

    function insertar(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_comisionC.php?insertar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_comision';
                    });
                } else if (response == -2) {
                    $('#txt_codigo').addClass('is-invalid');
                    $('#error_txt_codigo').text('El código de la comisión ya está en uso.');
                } else if (response == -3) {
                    $('#txt_nombre').addClass('is-invalid');
                    $('#error_txt_nombre').text('El nombre de la comisión ya está en uso.');
                }
            },

            error: function(xhr, status, error) {
                console.log('Status: ' + status);
                console.log('Error: ' + error);
                console.log('XHR Response: ' + xhr.responseText);
                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });
    }

    function insertar_persona_comision() {
        var comisionId = $('#ddl_comisiones').val();
        var percomId = $('#id_percom').val();
        var id_per = $('#id_per').val();

        if (!comisionId) {
            Swal.fire('', 'Seleccione una comisión', 'warning');
            return;
        }

        var parametros = {
            '_id': percomId || '',
            'id_persona': id_per,
            'id_comision': comisionId,
            'txt_visitor': $('#txt_visitor').val() || ''
        };

        $.ajax({
            url: '../controlador/TALENTO_HUMANO/th_per_comisionC.php?insertar_editar_persona=true',
            type: 'post',
            dataType: 'json',
            data: {
                parametros: parametros
            },
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operación realizada con éxito.', 'success').then(() => {
                        location.reload();
                    });
                } else if (response == -2) {
                    Swal.fire('', 'Esta persona ya está asignada a esta comisión', 'warning');
                } else {
                    Swal.fire('', 'Error en la operación', 'error');
                }
            },
            error: function(xhr, status, error) {
                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });
    }

    function editar_datos_personas_comisiones(id_com_per, id_persona) {
        var modalEl = document.getElementById('modal_blank');
        var modal = new bootstrap.Modal(modalEl);
        modal.show();

        $.ajax({
            data: {
                id: id_com_per
            },
            url: '../controlador/TALENTO_HUMANO/th_per_comisionC.php?listar_modal=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response && response.length > 0) {
                    $('#id_percom').val(id_com_per);
                    $('#id_per').val(response[0].th_per_id);

                    $('#ddl_comisiones').append($('<option>', {
                        value: response[0].id_comision,
                        text: response[0].comision_nombre,
                        selected: true
                    }));
                }
            },
            error: function(xhr, status, error) {
                console.error('Error al cargar comisión:', error);
            }
        });
    }

    function delete_datos() {
        var id = '<?= $_id ?>';
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
                eliminar(id);
            }
        })
    }

    function eliminar(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_comisionC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_comision';
                    });
                }
            }
        });
    }

    /**
     * Relación personas_comisiones
     */

    let tbl_comision_personas;

    function cargar_personas_comisiones() {
        if ($.fn.DataTable.isDataTable('#tbl_comision_personas')) {
            $('#tbl_comision_personas').DataTable().destroy();
            $('#tbl_comision_personas tbody').empty();
        }

        tbl_comision_personas = $('#tbl_comision_personas').DataTable({
            language: {
                url: '../assets/plugins/datatable/spanish.json'
            },
            responsive: true,
            ajax: {
                url: '../controlador/TALENTO_HUMANO/th_per_comisionC.php?listar_personas_comision=true',
                type: 'POST',
                data: function(d) {
                    return {
                        id: '<?= $_id ?>'
                    };
                },
                dataSrc: ''
            },
            columns: [{
                    data: null,
                    orderable: false,
                    className: 'text-center',
                    render: function(data, type, item) {
                        const percom = item._id || '';
                        const person = item.id_persona || item.th_per_id || '';
                        return `<input type="checkbox" class="select-person"
                    data-percom="${percom}"
                    data-person="${person}" />`;
                    }
                },
                {
                    data: 'cedula'
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        let nombres_completos = `${item.primer_apellido} ${item.segundo_apellido} ${item.primer_nombre} ${item.segundo_nombre}`;
                        return `<a href="#"><u>${nombres_completos}</u></a>`;
                    }
                },
                {
                    data: 'correo'
                },
                {
                    data: 'telefono_1'
                },
                {
                    data: null,
                    orderable: false,
                    className: 'text-center',
                    render: function(data, type, item) {
                        return `
                        <div class="d-flex justify-content-center gap-1">
                            <button type="button" class="btn btn-warning btn-xs" onclick="editar_datos_personas_comisiones('${item._id}','${item.id_persona}')">
                                <i class="bx bx-edit fs-7 fw-bold"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-xs" onclick="delete_datos_personas_comisiones('${item._id}')">
                                <i class="bx bx-trash fs-7 fw-bold"></i>
                            </button>
                        </div>
                    `;
                    }
                }
            ],
            order: [
                [2, 'asc']
            ],
            drawCallback: function() {
                setupCheckboxHandlers();
            }
        });
    }

    let ids_a_mover = [];

    function setupCheckboxHandlers() {
        $('#select_all_personas').off('change').on('change', function() {
            const checked = $(this).is(':checked');
            $('#tbl_comision_personas').find('tbody input.select-person').prop('checked', checked);
        });

        $('#tbl_comision_personas').on('change', 'tbody input.select-person', function() {
            const total = $('#tbl_comision_personas').find('tbody input.select-person').length;
            const marcados = $('#tbl_comision_personas').find('tbody input.select-person:checked').length;
            $('#select_all_personas').prop('checked', total === marcados && total > 0);
        });
    }

    function obtener_ids_personas_seleccionadas() {
        const items = [];
        $('#tbl_comision_personas').find('tbody input.select-person:checked').each(function() {
            const percom = $(this).data('percom') === undefined ? '' : String($(this).data('percom'));
            const person = $(this).data('person') === undefined ? '' : String($(this).data('person'));
            items.push({
                percom: percom,
                person: person
            });
        });
        return items;
    }

    function abrir_modal_mover_varios() {
        ids_a_mover = obtener_ids_personas_seleccionadas();

        if (ids_a_mover.length === 0) {
            Swal.fire('', 'Seleccione al menos una persona para mover.', 'warning');
            return;
        }

        $('#titulo_modal_mover').text('Mover personas a comisión');
        $('#texto_personas_seleccionadas').text(`Se moverán ${ids_a_mover.length} persona(s)`);

        cargar_comisiones_en_select();

        const modalEl = document.getElementById('modalMoverPersonas');
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }

    function cargar_comisiones_en_select() {
        const _idPHP = "<?php echo $_id; ?>";

        $('#ddl_comision_destino').html('<option value="">-- Seleccione --</option>');

        $.ajax({
            data: {
                id: _idPHP
            },
            url: '../controlador/TALENTO_HUMANO/th_cat_comisionC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response && response.length > 0) {
                    $('#ddl_comision_destino').append($('<option>', {
                        value: response[0]._id,
                        text: response[0].nombre,
                        selected: true
                    }));
                }
            },
            error: function(xhr, status, error) {
                console.error('Error al cargar comisión:', error);
            }
        });
    }

    function mover_personas_comision() {
        const selected = obtener_ids_personas_seleccionadas();
        const destino = $('#ddl_comision_destino').val();

        if (!destino) {
            Swal.fire('', 'Seleccione la comisión destino.', 'warning');
            return;
        }
        if (!selected || selected.length === 0) {
            Swal.fire('', 'Seleccione al menos una persona.', 'warning');
            return;
        }

        $('#mover_msg').text('Procesando...');

        $.ajax({
            url: '../controlador/TALENTO_HUMANO/th_per_comisionC.php?mover_varios=true',
            method: 'POST',
            data: {
                ids: JSON.stringify(selected),
                id_comision_destino: destino,
                txt_visitor: ''
            },
            dataType: 'json',
            success: function(resp) {
                $('#mover_msg').text('');
                const modalEl = document.getElementById('modalMoverPersonas');
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();

                if (tbl_comision_personas) tbl_comision_personas.ajax.reload(null, false);
                $('#select_all_personas').prop('checked', false);

                if (!resp) {
                    Swal.fire('', 'Respuesta inesperada del servidor.', 'error');
                    return;
                }

                const titulo = resp.success ? 'Operación completada' : 'Resultado';
                const icono = (resp.fallidos && resp.fallidos > 0) ? 'warning' : (resp.success ? 'success' : 'info');
                const contadores = `Movidas: ${resp.exitosos || 0}. Ya estaban: ${resp.duplicados || 0}. Fallidos: ${resp.fallidos || 0}.`;

                let htmlDetalles = `<p>${resp.message || contadores}</p>`;
                if (resp.errores && resp.errores.length > 0) {
                    htmlDetalles += '<hr><div style="text-align:left"><strong>Detalles:</strong><ul style="margin:0;padding-left:1.2em">';
                    resp.errores.forEach(function(err) {
                        if (typeof err === 'string') htmlDetalles += `<li>${escapeHtml(err)}</li>`;
                        else if (err.message) htmlDetalles += `<li>${escapeHtml(err.message)}</li>`;
                        else htmlDetalles += `<li>${escapeHtml(JSON.stringify(err))}</li>`;
                    });
                    htmlDetalles += '</ul></div>';
                }

                Swal.fire({
                    title: titulo,
                    html: htmlDetalles,
                    icon: icono,
                    confirmButtonText: 'Aceptar',
                    width: '520px'
                });

                if (resp.errores && resp.errores.length) console.warn('Errores mover_varios:', resp.errores);
            },
            error: function(xhr, status, error) {
                $('#mover_msg').text('');
                console.error(error, xhr.responseText);
                Swal.fire('', 'Error en el servidor al intentar mover las personas.', 'error');
            }
        });
    }

    function delete_datos_personas_comisiones(id) {
        Swal.fire({
            title: 'Eliminar Registro',
            text: '¿Está seguro de eliminar este registro?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {

            if (result.isConfirmed) {

                $.ajax({
                    url: '../controlador/TALENTO_HUMANO/th_per_comisionC.php?eliminar=true',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id: id
                    },
                    success: function(resp) {

                        if (resp == 1 || resp.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Eliminado',
                                text: 'El registro fue eliminado correctamente.',
                                timer: 1500,
                                showConfirmButton: false
                            });

                            if (tbl_comision_personas) {
                                tbl_comision_personas.ajax.reload(null, false);
                            }

                            if ($.fn.DataTable.isDataTable('#tbl_personas')) {
                                tbl_personas.ajax.reload(null, false);
                            }

                        } else {
                            Swal.fire('', 'No se pudo eliminar el registro.', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('', 'Error en el servidor.', 'error');
                    }
                });
            }
        });
    }


    let personas_seleccionadas = [];

    function cargar_personas() {
        tbl_personas = $('#tbl_personas').DataTable({
            responsive: true,
            language: {
                url: '../assets/plugins/datatable/spanish.json'
            },
            ajax: {
                url: '../controlador/TALENTO_HUMANO/th_per_comisionC.php?listar_personas_modal=true',
                type: 'POST',
                data: function(d) {
                    return {
                        id: '<?= $_id ?>',
                    };
                },
                dataSrc: ''
            },
            columns: [{
                    data: null,
                    render: function(data, type, item) {
                        return `<a href="#"><u>${item.primer_apellido} ${item.segundo_apellido} ${item.primer_nombre} ${item.segundo_nombre}</u></a>`;
                    }
                },
                {
                    data: 'cedula'
                },
                {
                    data: 'correo'
                },
                {
                    data: 'telefono_1'
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        return `<div class="form-check">
                                <input class="form-check-input cbx_com_per" type="checkbox" value="${item._id}" name="cbx_com_per_${item._id}" id="cbx_com_per_${item._id}">
                                <label class="form-label" for="cbx_com_per_${item._id}">Seleccionar</label>
                            </div>`;
                    },
                    orderable: false
                }
            ],
            order: [
                [1, 'asc']
            ],
        });

        $('#tbl_personas tbody').on('change', '.cbx_com_per', function() {
            var id = $(this).val();

            if (this.checked) {
                if (!personas_seleccionadas.includes(id)) {
                    personas_seleccionadas.push(id);
                }
            } else {
                personas_seleccionadas = personas_seleccionadas.filter(item => item !== id);
            }

            console.log('Seleccionados:', personas_seleccionadas);
        });
    }

    function marcar_cbx_modal_comisiones_personas(source) {
        var cbx_com_per_all = document.querySelectorAll('.cbx_com_per');

        cbx_com_per_all.forEach(function(cbx) {
            cbx.checked = source.checked;
            var id = cbx.value;

            if (source.checked) {
                if (!personas_seleccionadas.includes(id)) {
                    personas_seleccionadas.push(id);
                }
            } else {
                personas_seleccionadas = personas_seleccionadas.filter(item => item !== id);
            }
        });

        console.log('Seleccionados:', personas_seleccionadas);
    }

    function insertar_editar_personas_comisiones() {
        var parametros = {
            '_id': '<?= $_id ?>',
            'personas_seleccionadas': personas_seleccionadas,
            'txt_visitor': ''
        };

        insertar_personas_comisiones(parametros);
    }

    function insertar_personas_comisiones(parametros) {
        Swal.fire({
            title: 'Por favor, espere',
            text: 'Procesando la solicitud...',
            allowOutsideClick: false,
            onOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/th_per_comisionC.php?insertar_editar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        $('#modal_personas').modal('hide');
                        tbl_comision_personas.ajax.reload();
                        tbl_personas.ajax.reload();
                        personas_seleccionadas = [];
                        $('#cbx_per_com_all').prop('checked', false);
                        Swal.close();
                    });
                } else if (response == -2 || response == null) {
                    Swal.fire('', 'Seleccione personas', 'warning');
                }
            },

            error: function(xhr, status, error) {
                console.log('Status: ' + status);
                console.log('Error: ' + error);
                console.log('XHR Response: ' + xhr.responseText);
                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });
    }

    function abrir_modal_personas() {
        $('#modal_personas').modal('show');
        if (!$.fn.DataTable.isDataTable('#tbl_personas')) {
            cargar_personas();
        }
    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Comisiones</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Agregar Comisión</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-5">
                        <div class="card-title d-flex align-items-center">
                            <div><i class="bx bxs-user me-1 font-22 text-primary"></i></div>
                            <h5 class="mb-0 text-primary">
                                <?php
                                if ($_id == '') {
                                    echo 'Registrar Comisión';
                                } else {
                                    echo 'Modificar Comisión';
                                }
                                ?>
                            </h5>

                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_comision"
                                        class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="pt-2">
                            <ul class="nav nav-tabs nav-primary" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#successhome" role="tab" aria-selected="true">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class='bx bx-home font-18 me-1'></i></div>
                                            <div class="tab-title">Datos</div>
                                        </div>
                                    </a>
                                </li>

                                <?php if (isset($_GET['_id'])) { ?>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" data-bs-toggle="tab" href="#successprofile" role="tab" aria-selected="false">
                                            <div class="d-flex align-items-center">
                                                <div class="tab-icon"><i class='bx bx-user-pin font-18 me-1'></i></div>
                                                <div class="tab-title">Personas</div>
                                            </div>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>

                            <div class="tab-content py-3">
                                <div class="tab-pane fade show active" id="successhome" role="tabpanel">
                                    <form id="form_comision">
                                        <div class="row pt-3 mb-col">
                                            <div class="col-md-4">
                                                <label for="txt_codigo" class="form-label">Código </label>
                                                <input type="text" class="form-control form-control-sm" id="txt_codigo" name="txt_codigo" maxlength="20">
                                                <span id="error_txt_codigo" class="text-danger"></span>
                                            </div>
                                            <div class="col-md-8">
                                                <label for="txt_nombre" class="form-label">Nombre </label>
                                                <input type="text" class="form-control form-control-sm no_caracteres" id="txt_nombre" name="txt_nombre" maxlength="100">
                                                <span id="error_txt_nombre" class="text-danger"></span>
                                            </div>
                                        </div>

                                        <div class="row pt-3 mb-col">
                                            <div class="col-md-12">
                                                <label for="txt_descripcion" class="form-label">Descripción </label>
                                                <textarea class="form-control form-control-sm" id="txt_descripcion" name="txt_descripcion" rows="3" maxlength="255"></textarea>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-end pt-2">
                                            <?php if ($_id == '') { ?>
                                                <button class="btn btn-success btn-sm px-4 m-0" onclick="editar_insertar();" type="button"><i class="bx bx-save"></i> Guardar</button>
                                            <?php } else { ?>
                                                <button class="btn btn-success btn-sm px-4 m-1" onclick="editar_insertar();" type="button"><i class="bx bx-save"></i> Editar</button>
                                                <button class="btn btn-danger btn-sm px-4 m-1" onclick="delete_datos();" type="button"><i class="bx bx-trash"></i> Eliminar</button>
                                            <?php } ?>
                                        </div>
                                    </form>
                                </div>

                                <div class="tab-pane fade" id="successprofile" role="tabpanel">
                                    <div class="row pt-2">
                                        <div class="col-sm-12 text-end" id="btn_nuevo_bottom">
                                            <button type="button" class="btn btn-success btn-sm" onclick="abrir_modal_personas();">
                                                <i class="bx bx-plus"></i> Agregar Personas
                                            </button>
                                            <button type="button" class="btn btn-primary btn-sm ms-2" onclick="abrir_modal_mover_varios();">
                                                <i class="bx bx-transfer"></i> Mover seleccionados
                                            </button>
                                        </div>
                                    </div>

                                    <div class="row pt-4">
                                        <div class="table-responsive">
                                            <table class="table table-striped responsive" id="tbl_comision_personas" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th style="width:40px"><input id="select_all_personas" type="checkbox" /></th>
                                                        <th>Cédula</th>
                                                        <th>Nombre</th>
                                                        <th>Correo</th>
                                                        <th>Teléfono</th>
                                                        <th>Acción</th>
                                                    </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar personas -->
<div class="modal" id="modal_personas" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row pt-3">
                    <div class="table-responsive">
                        <table class="table table-striped responsive" id="tbl_personas" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Cédula</th>
                                    <th>Correo</th>
                                    <th>Teléfono</th>
                                    <th>
                                        <div class="form-check" style="display: block;">
                                            <input class="form-check-input" type="checkbox" id="cbx_per_com_all" onchange="marcar_cbx_modal_comisiones_personas(this)">
                                            <label class="form-check-label" for="cbx_per_com_all">Todo</label>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <div class="d-flex justify-content-center">
                    <button class="btn btn-success btn-sm px-4 m-1" onclick="insertar_editar_personas_comisiones();" type="button"><i class="bx bx-plus me-0"></i> Agregar</button>
                    <button class="btn btn-secondary btn-sm px-4 m-1" data-bs-dismiss="modal" type="button"><b>X </b> Cancelar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para mover personas -->
<div class="modal fade" id="modalMoverPersonas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mover personas a comisión</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="ddl_comision_destino" class="form-label">Comisión destino</label>
                    <select id="ddl_comision_destino" class="form-select">
                        <option value="">-- Seleccione --</option>
                    </select>
                </div>
                <div id="mover_msg" class="small text-muted"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary btn-sm" onclick="mover_personas_comision();">Mover</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para cambiar de comisión -->
<div class="modal" id="modal_blank" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cambiar De Comisión</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="registrar_comision" class="modal_general_comisiones">
                    <input id="id_percom" type="hidden" value="" />
                    <input id="id_per" type="hidden" value="" />

                    <div class="mb-3">
                        <label for="ddl_comisiones" class="form-label">Comisiones <span class="text-danger">*</span></label>
                        <select class="form-select form-select-sm select2-validation" id="ddl_comisiones" name="ddl_comisiones">
                            <option selected disabled>-- Seleccione --</option>
                        </select>
                        <label class="error" style="display: none;" for="ddl_comisiones"></label>
                    </div>

                    <div class="d-flex justify-content-end pt-2">
                        <button type="button" class="btn btn-success btn-sm" onclick="insertar_persona_comision();">
                            <i class="bx bx-save"></i> Guardar
                        </button>
                        <button type="button" class="btn btn-secondary btn-sm ms-2" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        agregar_asterisco_campo_obligatorio('txt_codigo');
        agregar_asterisco_campo_obligatorio('txt_nombre');

        $("#form_comision").validate({
            rules: {
                txt_codigo: {
                    required: true,
                },
                txt_nombre: {
                    required: true,
                },
            },
            messages: {
                txt_codigo: {
                    required: "El campo 'Código' es obligatorio",
                },
                txt_nombre: {
                    required: "El campo 'Nombre' es obligatorio",
                },
            },

            highlight: function(element) {
                $(element).addClass('is-invalid');
                $(element).removeClass('is-valid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid');
                $(element).addClass('is-valid');
            }
        });
    });
</script>