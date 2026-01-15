<script>
    $(document).ready(function() {

        cargar_datos_dotaciones(<?= $id_persona ?>);
        cargar_selects_dotacion();

        $('#ddl_dotacion').on('change', function() {
            id_dotacion = $(this).val();
            $('#div_adicionales').show();
            limpiar_selecteds();
            cargar_select_dotacion_item(id_dotacion);
        });

    });

    function cargar_datos_dotaciones(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/DOTACIONES/th_per_dotacion_detalleC.php?listar=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#pnl_dotaciones').html(response);
            }
        });
    }


    function cargar_datos_dotaciones_items(id) {
        if (!id || id == '' || id == 0) {
            console.warn("ID de dotación no válido para cargar items");
            return;
        }
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/DOTACIONES/th_per_dotacion_detalleC.php?listar_dotaciones_detalle=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                let html = '';

                if (response && response.length > 0) {
                    response.forEach(function(item) {
                        // Validamos la talla para mostrar N/A si es nulo o vacío
                        let talla = (item.codigo_talla && item.codigo_talla !== 'NULL') ? item.codigo_talla : 'N/A';

                        html += `
                        <tr>
                            <td>
                                <b>${item.nombre_item}</b><br>
                                <small class="text-muted">${item.tipo_item}</small>
                            </td>
                            <td class="text-center">${talla}</td>
                            <td class="text-center">${item.th_dotd_cantidad}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger btn-sm" 
                                    onclick="eliminar_detalle_item('${item.th_dotd_id}')" 
                                    title="Eliminar">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </td>
                        </tr>`;
                    });
                } else {
                    html = `
                    <tr id="mensaje_sin_items">
                        <td colspan="4" class="text-center text-muted">
                            <i class="bx bx-info-circle"></i> No hay items agregados
                        </td>
                    </tr>`;
                }

                $('#tbody_detalle_dotacion').html(html);
            }
        });
    }

    function cargar_selects_dotacion() {
        url_dotacionC = '../controlador/TALENTO_HUMANO/DOTACIONES/th_cat_dotacionC.php?buscar=true';
        cargar_select2_url('ddl_dotacion', url_dotacionC, '', '#modal_dotacion');

        url_tallaC = '../controlador/TALENTO_HUMANO/DOTACIONES/th_cat_tallaC.php?buscar=true';
        cargar_select2_url('ddl_talla', url_tallaC, '', '#modal_dotacion');
    }


    function cargar_select_dotacion_item(id_dotacion) {
        if ($('#ddl_dotacion_item').hasClass("select2-hidden-accessible")) {
            $('#ddl_dotacion_item').select2('destroy');
        }
        let th_dot_id = $('#txt_dotacion_id').val();

        $('#ddl_dotacion_item').select2({
            dropdownParent: $('#modal_dotacion'),
            ajax: {
                url: '../controlador/TALENTO_HUMANO/DOTACIONES/th_cat_dotacion_itemC.php?buscar_dotacion_item=true',
                dataType: 'json',
                data: function(params) {
                    return {
                        q: params.term,
                        id_dotacion: id_dotacion,
                        th_dot_id: th_dot_id
                    };
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                }
            },
            minimumInputLength: 0,
            placeholder: "Seleccione un ítem",
        }).on('select2:select', function(e) {
            let data = e.params.data;

            if (data.req_talla == "1") {
                $('#ddl_talla').prop('disabled', false);
                cargar_select_talla(data.tipo_item);
            } else {
                $('#ddl_talla').val(null).trigger('change');
                $('#ddl_talla').prop('disabled', true);
            }
        });
    }

    function cargar_select_talla(talla) {
        // Si select2 ya está inicializado, destruirlo
        if ($('#ddl_talla').hasClass("select2-hidden-accessible")) {
            $('#ddl_talla').select2('destroy');
        }

        $('#ddl_talla').select2({
            dropdownParent: $('#modal_dotacion'),
            ajax: {
                url: '../controlador/TALENTO_HUMANO/DOTACIONES/th_cat_tallaC.php?buscar_talla=true',
                dataType: 'json',
                data: function(params) {
                    return {
                        q: params.term,
                        talla: talla
                    };
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                }
            },
            minimumInputLength: 0,
            placeholder: "Seleccione un requisito",
            language: {
                noResults: function() {
                    return "No hay requisitos disponibles para asignar";
                },
                searching: function() {
                    return "Buscando...";
                }
            }
        });
    }

    async function agregar_dotacion() {
        let txt_fecha_entrega = $('#txt_fecha_entrega').val();
        let txt_observacion = $('#txt_observacion').val();
        let per_id = '<?= $id_persona ?>';

        let parametros_dotacion = {
            '_id': '',
            'th_per_id': per_id,
            'txt_fecha_entrega': txt_fecha_entrega,
            'txt_observacion': txt_observacion,
        }

        let th_dot_id = $('#txt_dotacion_id').val();

        if (th_dot_id == '' || th_dot_id == '0') {
            if ($("#form_dotacion").valid()) {
                th_dot_id = await insertar_dotacion(parametros_dotacion);
                if (!th_dot_id) return; // Si falló la inserción, detenemos
            } else {
                return;
            }
        }

        agregar_dotacion_item();
    }

    function agregar_dotacion_item() {

        //dotacion item
        let ddl_dotacion = $('#ddl_dotacion').val();
        let ddl_dotacion_item = $('#ddl_dotacion_item').val();
        let ddl_talla = $('#ddl_talla').val();
        let th_dot_id = $('#txt_dotacion_id').val();
        let txt_cantidad_adicional = $('#txt_cantidad_adicional').val();

        let parametros_dotacion_items = {
            '_id': '',
            'ddl_dotacion': ddl_dotacion,
            'ddl_dotacion_item': ddl_dotacion_item,
            'ddl_talla': ddl_talla,
            'th_dot_id': th_dot_id,
            'txt_cantidad_adicional': txt_cantidad_adicional,
        }

        if ($("#form_dotacion_items").valid()) {
            insertar_dotacion_items(parametros_dotacion_items);
        } else {
            return;
        }


    }


    function insertar_dotacion(parametros) {
        return new Promise((resolve, reject) => {
            $.ajax({
                data: {
                    parametros: parametros
                },
                url: '../controlador/TALENTO_HUMANO/DOTACIONES/th_per_dotacionC.php?insertar=true',
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    if (response > 0) {
                        $('#txt_dotacion_id').val(response);
                        resolve(response); // Retornamos el ID para que la función principal continúe
                    } else {
                        Swal.fire('', 'Error al crear la cabecera de dotación', 'error');
                        resolve(false);
                    }
                },
                error: function() {
                    resolve(false);
                }
            });
        });
    }

    function insertar_dotacion_items(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/DOTACIONES/th_per_dotacion_detalleC.php?insertar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operación realizada con éxito.', 'success');
                    $('#ddl_dotacion').val('').trigger('change');
                    limpiar_selecteds();
                    cargar_datos_dotaciones_items(parametros.th_dot_id);
                } else {
                    Swal.fire('', 'Operación fallida', 'warning');
                }
            }
        });
    }

    function limpiar_selecteds() {
        $('#ddl_dotacion_item').val('').trigger('change');
        $('#ddl_talla').val('').trigger('change');
    }

    function abrir_modal_informacion_adicional(id = null) {

        if (id) {
            $('#lbl_titulo_dotacion').html('Editar Dotación');
            $('#btn_guardar_dotacion').html('<i class="bx bx-save"></i> Editar');
            $('#btn_eliminar_dotacion').show();
        } else {
            $('#lbl_titulo_dotacion').html('Agregar Dotación');
            $('#btn_guardar_dotacion').html('<i class="bx bx-save"></i> Agregar');
            $('#btn_eliminar_dotacion').hide();
        }

        $('#modal_dotacion').modal('show');
    }


    function eliminar_detalle_item(id) {
        Swal.fire({
            title: '¿Eliminar Registro?',
            text: "¿Está seguro de eliminar este registro?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) {
                eliminar_item(id);
            }
        })
    }

    function eliminar_item(id) {
        let th_dot_id = $('#txt_dotacion_id').val();
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/DOTACIONES/th_per_dotacion_detalleC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success');
                    cargar_datos_dotaciones_items(th_dot_id);
                }
            }
        });
    }


     function eliminar_dotacion(id) {
        Swal.fire({
            title: '¿Eliminar Registro?',
            text: "¿Está seguro de eliminar este registro?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) {
                eliminar_dotacion_actual(id);
            }
        })
    }

    function eliminar_dotacion_actual(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/DOTACIONES/th_per_dotacionC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success');
                   cargar_datos_dotaciones(<?= $id_persona ?>);
                }
            }
        });
    }

    function insertar_editar_dotacion() {
        $('#modal_estado_laboral').modal('hide');
        cargar_datos_dotaciones(<?= $id_persona ?>);
    }
</script>

<div id="pnl_dotaciones"></div>

<div class="modal" id="modal_dotacion" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5><small class="text-body-secondary fw-bold" id="lbl_titulo_dotacion">Agregar Dotación</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="limpiar_campos_dotacion_modal()"></button>
            </div>

            <div class="modal-body">
                <!-- FORMULARIO 1: Datos Generales -->
                <form id="form_dotacion">
                    <input type="hidden" name="txt_dotacion_id" id="txt_dotacion_id">

                    <div class="card mb-3">
                        <div class="card-header bg-primary text-white">
                            <strong>Datos Generales</strong>
                        </div>
                        <div class="card-body">
                            <div class="row mb-col">
                                <div class="col-md-6">
                                    <label for="txt_fecha_entrega" class="form-label form-label-sm">Fecha de Entrega</label>
                                    <input type="date" class="form-control form-control-sm" name="txt_fecha_entrega" id="txt_fecha_entrega" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="txt_observacion" class="form-label form-label-sm">Observación</label>
                                    <textarea class="form-control form-control-sm" name="txt_observacion" id="txt_observacion" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- FIN FORMULARIO 1 -->

                <!-- FORMULARIO 2: Items de Dotación -->
                <form id="form_dotacion_items">
                    <div class="card mb-3">
                        <div class="card-header bg-success text-white">
                            <strong>Agregar Dotación</strong>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="ddl_dotacion" class="form-label form-label-sm">Dotación</label>
                                    <select class="form-select form-select-sm" id="ddl_dotacion" name="ddl_dotacion">
                                        <option selected disabled value="">-- Seleccione una Dotación --</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Sección de adicionales -->
                            <div class="row" id="div_adicionales" style="display: none;">
                                <div class="col-md-12">
                                    <div class="card border-secondary">
                                        <div class="card-header bg-light">
                                            <strong class="text-secondary">Agregar Adicionales</strong>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label for="ddl_dotacion_item" class="form-label form-label-sm">Item Adicional</label>
                                                    <select class="form-select form-select-sm" id="ddl_dotacion_item" name="ddl_dotacion_item">
                                                        <option selected disabled value="">-- Seleccione un Item --</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="ddl_talla" class="form-label form-label-sm">Talla</label>
                                                    <select class="form-select form-select-sm" id="ddl_talla" name="ddl_talla" disabled>
                                                        <option selected disabled value="">-- Seleccione una Talla --</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="txt_cantidad_adicional" class="form-label form-label-sm">Cantidad</label>
                                                    <input type="number" class="form-control form-control-sm" id="txt_cantidad_adicional" name="txt_cantidad_adicional" value="1" min="1">
                                                </div>
                                                <div class="col-md-3 d-flex align-items-end">
                                                    <button type="button" class="btn btn-success btn-sm w-100" onclick="agregar_dotacion()">
                                                        <i class="bx bx-plus-circle"></i> Agregar dotación
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <!-- FIN FORMULARIO 2 -->

                <!-- Tabla de detalle (fuera de los formularios) -->
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <strong>Detalle de Dotación</strong>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">Item</th>
                                        <th class="text-center">Talla</th>
                                        <th class="text-center">Cantidad</th>
                                        <th class="text-center" style="width: 100px;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody_detalle_dotacion">
                                    <tr id="mensaje_sin_items">
                                        <td colspan="4" class="text-center text-muted">
                                            <i class="bx bx-info-circle"></i> No hay items agregados
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- FIN MODAL-BODY -->

            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn btn-success btn-sm px-4 m-1" id="btn_guardar_dotacion" onclick="insertar_editar_dotacion();">
                    <i class="bx bx-save"></i> Agregar
                </button>
                <button type="button" style="display: none;" class="btn btn-danger btn-sm px-4 m-1" id="btn_eliminar_dotacion" onclick="delete_datos_dotacion();">
                    <i class="bx bx-trash"></i> Eliminar
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        agregar_asterisco_campo_obligatorio('txt_fecha_entrega');
        agregar_asterisco_campo_obligatorio('txt_observacion');

        $("#form_dotacion").validate({
            rules: {
                txt_fecha_entrega: {
                    required: true,
                },
                txt_observacion: {
                    required: true,
                }
            },
            messages: {
                txt_fecha_entrega: {
                    required: "Por favor ingrese la fecha de entrega",
                },
                txt_observacion: {
                    required: "Por favor ingrese la observación",
                }
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


<script>
    $(document).ready(function() {
        agregar_asterisco_campo_obligatorio('ddl_dotacion_item');
        agregar_asterisco_campo_obligatorio('ddl_talla');
        agregar_asterisco_campo_obligatorio('txt_cantidad_adicional');

        $("#form_dotacion_items").validate({
            rules: {
                ddl_dotacion_item: {
                    required: true,
                },
                txt_cantidad_adicional: {
                    required: true,
                }
            },
            messages: {
                ddl_dotacion_item: {
                    required: "Por favor selccione un item",
                },
                txt_cantidad_adicional: {
                    required: "Por favor ingrese la cantidad",
                }
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