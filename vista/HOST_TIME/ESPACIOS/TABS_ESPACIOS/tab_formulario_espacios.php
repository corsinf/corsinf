<!-- JS TAB DATOS -->
<script type="text/javascript">
    $(document).ready(function() {
        <?php if ($_id != ''): ?>
            cargar_espacio(<?= $_id ?>);
        <?php endif; ?>
        cargar_selects2();
        $('#txt_capacidad_max').on('change', function() {
            var min = parseInt($('#txt_capacidad_min').val());
            var max = parseInt($(this).val());

            if (max < min) {
                Swal.fire('', 'La capacidad máxima no puede ser menor que la mínima.', 'warning');
                $(this).val('');
            }
        });
    });



    function cargar_espacio(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/HOST_TIME/ESPACIOS/espaciosC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(r) {
                r = r[0];
                $('#txt_nombre').val(r.nombre);
                $('#txt_codigo').val(r.codigo);
                $('#txt_capacidad_min').val(r.capacidad_minima);
                $('#txt_capacidad_max').val(r.capacidad_maxima);
                $('#ddl_tipo_espacio').append($('<option>', {
                    value: r.id_tipo_espacio,
                    text: r.nombre_tipo_espacio,
                    selected: true
                }));
                $('#ddl_ubicacion').append($('<option>', {
                    value: r.id_ubicacion,
                    text: r.nombre_ubicacion,
                    selected: true
                }));
                $('#ddl_numero_piso').append($('<option>', {
                    value: r.id_numero_piso,
                    text: r.descripcion_numero_piso,
                    selected: true
                }));
                $('#ddl_estado_espacio').append($('<option>', {
                    value: r.id_estado_espacio,
                    text: r.nombre_estado_espacio,
                    selected: true
                }));
            }
        });
    }

    function cargar_selects2() {
        cargar_select2_url('ddl_tipo_espacio', '../controlador/HOST_TIME/CATALOGOS/hub_catn_tipo_espacioC.php?buscar=true');
        cargar_select2_url('ddl_ubicacion', '../controlador/HOST_TIME/UBICACIONES/hub_ubicacionesC.php?buscar=true');
        cargar_select2_url('ddl_numero_piso', '../controlador/HOST_TIME/CATALOGOS/hub_catn_numero_pisoC.php?buscar=true');
        cargar_select2_url('ddl_estado_espacio', '../controlador/HOST_TIME/CATALOGOS/hub_cats_estado_espaciosC.php?buscar=true');
    }

    function editar_insertar_espacio() {
        var parametros = {
            '_id': '<?= $_id ?>',
            'txt_nombre': $('#txt_nombre').val(),
            'txt_codigo': $('#txt_codigo').val(),
            'txt_capacidad_min': $('#txt_capacidad_min').val(),
            'txt_capacidad_max': $('#txt_capacidad_max').val(),
            'ddl_tipo_espacio': $('#ddl_tipo_espacio').val(),
            'ddl_estado': $('#ddl_estado_espacio').val(),
            'ddl_ubicacion': $('#ddl_ubicacion').val(),
            'ddl_numero_piso': $('#ddl_numero_piso').val(),
        };
        if ($("#form_espacios").valid()) insertar_espacio(parametros);
    }

    function insertar_espacio(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/HOST_TIME/ESPACIOS/espaciosC.php?insertar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=hub_espacios';
                    });
                } else if (response == -2) {
                    $('#txt_nombre').addClass('is-invalid');
                    $('#error_txt_nombre').text('El nombre ya esta en uso.');
                }
            },
            error: function(xhr) {
                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });
        $('#txt_nombre').on('input', function() {
            $('#error_txt_nombre').text('');
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
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    data: {
                        id: '<?= $_id ?>'
                    },
                    url: '../controlador/HOST_TIME/ESPACIOS/espaciosC.php?eliminar=true',
                    type: 'post',
                    dataType: 'json',
                    success: function(response) {
                        if (response == 1) {
                            Swal.fire('Eliminado!', 'Registro Eliminado.', 'success').then(function() {
                                location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=hub_espacios';
                            });
                        }
                    }
                });
            }
        });
    }
</script>

<form id="form_espacios">

    <!-- FILA 1: Imagen izquierda + campos a la derecha -->
    <div class="row pt-3 mb-col align-items-center">

        <?php if ($_id != ''): ?>
            <!-- Imagen col-3 -->
            <div class="col-md-3 d-flex justify-content-center align-items-center">
                <div class="position-relative d-inline-block">
                    <img id="img_espacio"
                        src="../img/sin_imagen.jpg"
                        alt="Imagen espacio"
                        class="rounded border"
                        style="width:160px; height:160px; object-fit:cover;">
                    <button type="button"
                        class="btn btn-dark btn-sm rounded-circle position-absolute bottom-0 end-0 d-flex align-items-center justify-content-center shadow"
                        style="width:30px; height:30px; border:2px solid #fff;"
                        data-bs-toggle="modal"
                        data-bs-target="#modal_imagen_espacio"
                        title="Cambiar imagen">
                        <i class='bx bxs-camera' style="font-size:13px;"></i>
                    </button>
                </div>
            </div>
        <?php endif; ?>

        <!-- Campos apilados col-9 -->
        <div class="col-md-<?= $_id != '' ? '9' : '12' ?>">

            <!-- Nombre y Codigo en la misma fila -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="txt_nombre" class="form-label">Nombre </label>
                    <input type="text" class="form-control form-control-sm no_caracteres"
                        id="txt_nombre" name="txt_nombre" autocomplete="off">
                    <span id="error_txt_nombre" class="text-danger"></span>
                </div>
                <div class="col-md-6">
                    <label for="txt_codigo" class="form-label">Codigo </label>
                    <input type="text" class="form-control form-control-sm no_caracteres"
                        id="txt_codigo" name="txt_codigo" autocomplete="off">
                </div>
            </div>

            <!-- Tipo espacio y Ubicación en la misma fila -->
            <div class="row">
                <div class="col-md-6">
                    <label for="ddl_tipo_espacio" class="form-label">Tipo de espacio </label>
                    <select class="form-select form-select-sm select2-validation"
                        id="ddl_tipo_espacio" name="ddl_tipo_espacio">
                        <option value="" selected hidden>-- Seleccione --</option>
                    </select>
                    <label class="error" style="display:none;" for="ddl_tipo_espacio"></label>
                </div>
                <div class="col-md-6">
                    <label for="ddl_ubicacion" class="form-label">Ubicación </label>
                    <select class="form-select form-select-sm select2-validation"
                        id="ddl_ubicacion" name="ddl_ubicacion">
                        <option value="" selected hidden>-- Seleccione --</option>
                    </select>
                    <label class="error" style="display:none;" for="ddl_ubicacion"></label>
                </div>
            </div>

        </div>

    </div>

    <!-- FILA 2: Número de piso + Capacidad -->
    <div class="row mb-col mt-3">
        <div class="col-md-3">
            <label for="ddl_numero_piso" class="form-label">Numero de piso </label>
            <select class="form-select form-select-sm select2-validation"
                id="ddl_numero_piso" name="ddl_numero_piso">
                <option value="" selected hidden>-- Seleccione --</option>
            </select>
            <label class="error" style="display:none;" for="ddl_numero_piso"></label>
        </div>
        <div class="col-md-3">
            <label for="ddl_estado_espacio" class="form-label">Estado del espacio</label>
            <select class="form-select form-select-sm select2-validation"
                id="ddl_estado_espacio" name="ddl_estado_espacio">
                <option value="" selected hidden>-- Seleccione --</option>
            </select>
            <label class="error" style="display:none;" for="ddl_estado_espacio"></label>
        </div>
        <div class="col-md-3">
            <label for="txt_capacidad_min" class="form-label">Capacidad mínima</label>
            <input type="number" class="form-control form-control-sm"
                id="txt_capacidad_min" name="txt_capacidad_min" min="0" step="1">
        </div>

        <div class="col-md-3">
            <label for="txt_capacidad_max" class="form-label">Capacidad máxima</label>
            <input type="number" class="form-control form-control-sm"
                id="txt_capacidad_max" name="txt_capacidad_max" min="0" step="1">
        </div>

    </div>

    <!-- Botones -->
    <div class="d-flex justify-content-end pt-2">
        <?php if ($_id == ''): ?>
            <button type="button" class="btn btn-success btn-sm px-4 m-0" onclick="editar_insertar_espacio()">
                <i class="bx bx-save"></i> Guardar
            </button>
        <?php else: ?>
            <button type="button" class="btn btn-success btn-sm px-4 m-1" onclick="editar_insertar_espacio()">
                <i class="bx bx-save"></i> Editar
            </button>
            <button type="button" class="btn btn-danger btn-sm px-4 m-1" onclick="delete_datos()">
                <i class="bx bx-trash"></i> Eliminar
            </button>
        <?php endif; ?>
    </div>

</form>
<?php if ($_id != ''): ?>

    <!-- Modal imagen espacio -->
    <div class="modal fade" id="modal_imagen_espacio" tabindex="-1"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="fw-bold mb-0">Imagen del espacio</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="form_imagen_espacio" enctype="multipart/form-data" method="post">
                    <div class="modal-body">
                        <input type="hidden" name="txt_espacio_id_foto" id="txt_espacio_id_foto"
                            value="<?= $_id ?>">
                        <div class="text-center mb-3">
                            <img id="img_espacio_preview"
                                src="../img/sin_imagen.jpg"
                                class="rounded border"
                                style="width:150px; height:150px; object-fit:cover;">
                        </div>
                        <hr>
                        <input type="file" class="form-control form-control-sm"
                            name="txt_copia_imagen_espacio"
                            id="txt_copia_imagen_espacio"
                            accept=".jpg,.jpeg,.png,.webp">
                        <small class="text-muted">Formatos: JPG, PNG, WEBP · Máx 2MB</small>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-success btn-sm px-4"
                            onclick="guardar_imagen_espacio();">
                            <i class="bx bx-save"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $('#modal_imagen_espacio').on('show.bs.modal', function() {
            var src = $('#img_espacio').attr('src');
            $('#img_espacio_preview').attr('src', src);
        });
        $('#modal_imagen_espacio').on('hidden.bs.modal', function() {
            $('#txt_copia_imagen_espacio').val('');
            var src = $('#img_espacio').attr('src').split('?')[0]; // quitar el ?random
            $('#img_espacio_preview').attr('src', src);
        });
        // Preview en el modal
        $('#txt_copia_imagen_espacio').on('change', function(e) {
            var file = e.target.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(ev) {
                    $('#img_espacio_preview').attr('src', ev.target.result);
                };
                reader.readAsDataURL(file);
            }
        });

        // Guardar imagen
        function guardar_imagen_espacio() {
            if (!$("#form_imagen_espacio").valid()) return;

            var form_data = new FormData(document.getElementById('form_imagen_espacio'));

            $.ajax({
                url: '../controlador/HOST_TIME/ESPACIOS/espaciosC.php?insertar_imagen=true',
                type: 'post',
                data: form_data,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(r) {
                    if (r == 1) {
                        Swal.fire('', 'Imagen actualizada con éxito.', 'success');
                        recargar_imagen_espacio('<?= $_id ?>');
                        $('#modal_imagen_espacio').modal('hide');
                    } else if (r == -2) {
                        Swal.fire('', 'Formato no válido. Use JPG, PNG o WEBP.', 'error');
                    } else {
                        Swal.fire('', 'Error al guardar la imagen.', 'error');
                    }
                },
                error: function(xhr) {
                    Swal.fire('', 'Error: ' + xhr.responseText, 'error');
                }
            });
        }

        // Recargar imagen principal en la página
        function recargar_imagen_espacio(id) {
            $.ajax({
                url: '../controlador/HOST_TIME/ESPACIOS/espaciosC.php?listar=true',
                type: 'post',
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(r) {
                    if (r && r[0] && r[0].imagen) {
                        $('#img_espacio').attr('src', r[0].imagen + '?' + Math.random());
                    }
                }
            });
        }

        // Cargar imagen al iniciar si ya tiene una
        $(document).ready(function() {
            recargar_imagen_espacio('<?= $_id ?>');

            // Validación del form del modal
            $("#form_imagen_espacio").validate({
                rules: {
                    txt_copia_imagen_espacio: {
                        required: true
                    }
                },
                messages: {
                    txt_copia_imagen_espacio: {
                        required: 'Seleccione una imagen.'
                    }
                },
                highlight: function(el) {
                    $(el).addClass('is-invalid');
                },
                unhighlight: function(el) {
                    $(el).removeClass('is-invalid');
                }
            });
        });
    </script>
<?php endif; ?>

<script>
    $(document).ready(function() {
        agregar_asterisco_campo_obligatorio('txt_nombre');
        agregar_asterisco_campo_obligatorio('txt_codigo');
        agregar_asterisco_campo_obligatorio('txt_capacidad_min');
        agregar_asterisco_campo_obligatorio('txt_capacidad_max');
        agregar_asterisco_campo_obligatorio('ddl_tipo_espacio');
        agregar_asterisco_campo_obligatorio('ddl_ubicacion');
        agregar_asterisco_campo_obligatorio('ddl_numero_piso');
        agregar_asterisco_campo_obligatorio('ddl_estado_espacio');

        $("#form_espacios").validate({
            rules: {
                txt_nombre: {
                    required: true
                },
                txt_codigo: {
                    required: true
                },
                txt_capacidad_min: {
                    required: true
                },
                txt_capacidad_max: {
                    required: true
                },
                ddl_tipo_espacio: {
                    required: true
                },
                ddl_ubicacion: {
                    required: true
                },
                ddl_numero_piso: {
                    required: true
                },
                ddl_estado_espacio: {
                    required: true
                },
            },
            errorPlacement: function(error, element) {
                if (element.closest('.input-group').length) {
                    error.insertAfter(element.closest('.input-group'));
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function(el) {
                $(el).addClass('is-invalid').removeClass('is-valid');
            },
            unhighlight: function(el) {
                $(el).removeClass('is-invalid').addClass('is-valid');
            }
        });
    });
</script>