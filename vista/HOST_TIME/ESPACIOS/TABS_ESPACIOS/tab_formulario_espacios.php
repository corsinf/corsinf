<!-- JS TAB DATOS -->
<script type="text/javascript">
    $(document).ready(function() {
        <?php if ($_id != ''): ?>
            cargar_espacio(<?= $_id ?>);
        <?php endif; ?>
        cargar_selects2();
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
                $('#txt_capacidad').val(r.capacidad);
                $('#txt_tarifa_hora').val(r.tarifa_hora);
                $('#txt_tarifa_dia').val(r.tarifa_dia);
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
            }
        });
    }

    function cargar_selects2() {
        cargar_select2_url('ddl_tipo_espacio', '../controlador/HOST_TIME/ESPACIOS/tipo_espacioC.php?buscar=true');
        cargar_select2_url('ddl_ubicacion', '../controlador/HOST_TIME/UBICACIONES/hub_ubicacionesC.php?buscar=true');
        cargar_select2_url('ddl_numero_piso', '../controlador/HOST_TIME/CATALOGOS/hub_cat_numero_pisoC.php?buscar=true');
    }

    function editar_insertar() {
        var parametros = {
            '_id': '<?= $_id ?>',
            'txt_nombre': $('#txt_nombre').val(),
            'txt_codigo': $('#txt_codigo').val(),
            'txt_capacidad': $('#txt_capacidad').val(),
            'txt_tarifa_hora': $('#txt_tarifa_hora').val(),
            'txt_tarifa_dia': $('#txt_tarifa_dia').val(),
            'ddl_tipo_espacio': $('#ddl_tipo_espacio').val(),
            'ddl_ubicacion': $('#ddl_ubicacion').val(),
            'ddl_numero_piso': $('#ddl_numero_piso').val(),
        };
        if ($("#form_espacios").valid()) insertar(parametros);
    }

    function insertar(parametros) {
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

    <div class="row pt-3 mb-col">
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

    <div class="row mb-col">
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

    <div class="row mb-col">
        <div class="col-md-6">
            <label for="ddl_numero_piso" class="form-label">Numero de piso </label>
            <select class="form-select form-select-sm select2-validation"
                id="ddl_numero_piso" name="ddl_numero_piso">
                <option value="" selected hidden>-- Seleccione --</option>
            </select>
            <label class="error" style="display:none;" for="ddl_numero_piso"></label>
        </div>
        <div class="col-md-6">
            <label for="txt_capacidad" class="form-label">Capacidad </label>
            <input type="number" class="form-control form-control-sm"
                id="txt_capacidad" name="txt_capacidad" min="0" step="1">
        </div>
    </div>

    <div class="row mb-col">
        <div class="col-md-6">
            <label for="txt_tarifa_hora" class="form-label">Tarifa (Hora) </label>
            <div class="input-group input-group-sm">
                <span class="input-group-text">$</span>
                <input type="number" class="form-control form-control-sm"
                    id="txt_tarifa_hora" name="txt_tarifa_hora"
                    placeholder="0.00" min="0" step="0.01">
            </div>
        </div>
        <div class="col-md-6">
            <label for="txt_tarifa_dia" class="form-label">Tarifa (Dia) </label>
            <div class="input-group input-group-sm">
                <span class="input-group-text">$</span>
                <input type="number" class="form-control form-control-sm"
                    id="txt_tarifa_dia" name="txt_tarifa_dia"
                    placeholder="0.00" min="0" step="0.01">
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end pt-2">
        <?php if ($_id == ''): ?>
            <button type="button" class="btn btn-success btn-sm px-4 m-0"
                onclick="editar_insertar()">
                <i class="bx bx-save"></i> Guardar
            </button>
        <?php else: ?>
            <button type="button" class="btn btn-success btn-sm px-4 m-1"
                onclick="editar_insertar()">
                <i class="bx bx-save"></i> Editar
            </button>
            <button type="button" class="btn btn-danger btn-sm px-4 m-1"
                onclick="delete_datos()">
                <i class="bx bx-trash"></i> Eliminar
            </button>
        <?php endif; ?>
    </div>

</form>

<script>
    $(document).ready(function() {
        agregar_asterisco_campo_obligatorio('txt_nombre');
        agregar_asterisco_campo_obligatorio('txt_codigo');
        agregar_asterisco_campo_obligatorio('txt_capacidad');
        agregar_asterisco_campo_obligatorio('txt_tarifa_hora');
        agregar_asterisco_campo_obligatorio('txt_tarifa_dia');
        agregar_asterisco_campo_obligatorio('ddl_tipo_espacio');
        agregar_asterisco_campo_obligatorio('ddl_ubicacion');
        agregar_asterisco_campo_obligatorio('ddl_numero_piso');

        $("#form_espacios").validate({
            rules: {
                txt_nombre: {
                    required: true
                },
                txt_codigo: {
                    required: true
                },
                txt_capacidad: {
                    required: true
                },
                txt_tarifa_hora: {
                    required: true
                },
                txt_tarifa_dia: {
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