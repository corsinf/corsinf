<script>
    function cargar_datos_persona(id) {
        $.ajax({
            url: '../controlador/GENERAL/th_personasC.php?listar=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#img_persona_inf')
                    .off('error') // limpiar por si acaso
                    .one('error', function() {
                        console.log("Error 404");
                        $(this).attr('src', '../img/sin_imagen.jpg');
                    })
                    .attr('src', response[0].foto_url + '?' + Math.random());

                $('#txt_primer_nombre').val(response[0].primer_nombre);
                $('#txt_segundo_nombre').val(response[0].segundo_nombre);
                $('#txt_primer_apellido').val(response[0].primer_apellido);
                $('#txt_segundo_apellido').val(response[0].segundo_apellido);
                $('#txt_fecha_nacimiento').val(response[0].fecha_nacimiento);
                // $('#ddl_nacionalidad').val(response[0].nacionalidad);
                $('#txt_cedula').val(response[0].cedula);
                // $('#ddl_estado_civil').val(response[0].estado_civil);
                // $('#ddl_sexo').val(response[0].sexo);
                $('#txt_telefono_1').val(response[0].telefono_1);
                $('#txt_telefono_2').val(response[0].telefono_2);
                $('#txt_correo').val(response[0].correo);
                $('#txt_codigo_postal').val(response[0].postal);
                $('#txt_direccion').val(response[0].direccion);
                $('#txt_observaciones').val(response[0].observaciones);
                // $('#ddl_tipo_sangre').val(response[0].tipo_sangre);

                calcular_edad('txt_edad', response[0].fecha_nacimiento);

                if (response[0].id_provincia != null) {
                    $('#ddl_provincias').append($('<option>', {
                        value: response[0].id_provincia,
                        text: response[0].th_prov_nombre,
                        selected: true
                    }));

                    $('#ddl_ciudad').append($('<option>', {
                        value: response[0].id_ciudad,
                        text: response[0].th_ciu_nombre,
                        selected: true
                    }));

                    $('#ddl_parroquia').append($('<option>', {
                        value: response[0].id_parroquia,
                        text: response[0].th_parr_nombre,
                        selected: true
                    }));
                }

                $('#ddl_etnia').append($('<option>', {
                    value: response[0].id_etnia,
                    text: response[0].descripcion_etnia,
                    selected: true
                }));

                $('#ddl_religion').append($('<option>', {
                    value: response[0].id_religion,
                    text: response[0].descripcion_religion,
                    selected: true
                }));

                $('#ddl_identidad_genero').append($('<option>', {
                    value: response[0].id_identidad_genero,
                    text: response[0].descripcion_identidad_genero,
                    selected: true
                }));

                $('#ddl_orientacion_sexual').append($('<option>', {
                    value: response[0].id_orientacion_sexual,
                    text: response[0].descripcion_orientacion_sexual,
                    selected: true
                }));

                $('#ddl_nacionalidad').append($('<option>', {
                    value: response[0].id_pais,
                    text: response[0].nacionalidad,
                    selected: true
                }));

                $('#ddl_tipo_sangre').append($('<option>', {
                    value: response[0].id_tipo_sangre,
                    text: response[0].tipo_sangre,
                    selected: true
                }));

                $('#ddl_estado_civil').append($('<option>', {
                    value: response[0].id_estado_civil,
                    text: response[0].estado_civil,
                    selected: true
                }));

                $('#ddl_sexo').append($('<option>', {
                    value: response[0].id_sexo,
                    text: response[0].sexo,
                    selected: true
                }));

                $('#ddl_origen_indigena').append($('<option>', {
                    value: response[0].id_origen_indigena,
                    text: response[0].descripcion_origen_indigena,
                    selected: true
                }));

                $('#txt_per_correo_personal_1').val(response[0].correo_personal_1);
                $('#txt_per_correo_personal_2').val(response[0].correo_personal_2);

                //Para cargar la seccion de th_per_cambiar_foto
                $('#img_persona_inf_modal').attr('src', response[0].foto_url || '../img/sin_imagen.jpg');
                $('#txt_cedula_foto').val(response[0].cedula);
                $('#txt_persona_id_foto').val(response[0].th_per_id);

                $('#lbl_nombre_completo_perfil').text(response[0].nombres_completos);
                $('#lbl_cedula_perfil').text(response[0].cedula);

                $('input[name="txt_persona_id"]').val(response[0].th_per_id);
                $('input[name="txt_persona_cedula"]').val(response[0].cedula);

                $('#txt_calle_primaria').val(response[0].calle_primaria);
                $('#txt_calle_secundaria').val(response[0].calle_secundaria);
            },
        });
    }

    function parametros_persona() {
        return parametros = {
            'txt_primer_nombre': $('#txt_primer_nombre').val(),
            'txt_segundo_nombre': $('#txt_segundo_nombre').val(),
            'txt_primer_apellido': $('#txt_primer_apellido').val(),
            'txt_segundo_apellido': $('#txt_segundo_apellido').val(),
            'txt_fecha_nacimiento': $('#txt_fecha_nacimiento').val(),
            'ddl_nacionalidad': $('#ddl_nacionalidad').val(),
            'txt_cedula_persona': $('#txt_cedula').val(),
            'ddl_estado_civil': $('#ddl_estado_civil').val(),
            'ddl_sexo': $('#ddl_sexo').val(),
            'txt_telefono_1': $('#txt_telefono_1').val(),
            'txt_telefono_2': $('#txt_telefono_2').val(),
            'txt_correo': $('#txt_correo').val(),
            'ddl_provincias': $('#ddl_provincias').val(),
            'ddl_ciudad': $('#ddl_ciudad').val(),
            'ddl_parroquia': $('#ddl_parroquia').val(),
            'txt_codigo_postal': $('#txt_codigo_postal').val(),
            'txt_direccion': $('#txt_direccion').val(),
            'txt_observaciones': $('#txt_observaciones').val(),
            'ddl_tipo_sangre': $('#ddl_tipo_sangre').val(),
            'ddl_etnia': $('#ddl_etnia').val(),
            'ddl_origen_indigena': $('#ddl_origen_indigena').val(),
            'ddl_religion': $('#ddl_religion').val(),
            'ddl_orientacion_sexual': $('#ddl_orientacion_sexual').val(),
            'ddl_identidad_genero': $('#ddl_identidad_genero').val(),
            'txt_per_correo_personal_1': $('#txt_per_correo_personal_1').val(),
            'txt_per_correo_personal_2': $('#txt_per_correo_personal_2').val(),
            'txt_calle_primaria': $('#txt_calle_primaria').val(),
            'txt_calle_secundaria': $('#txt_calle_secundaria').val(),
        };
    }

    function delete_datos_persona() {
        var id = '<?= $id_persona; ?>';
        Swal.fire({
            title: 'Eliminar Registro?',
            text: "Esta seguro de eliminar este registro?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.value) {
                eliminar_persona(id);
            }
        })
    }

    function eliminar_persona(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/GENERAL/th_personasC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=<?= $redireccionar_vista ?>';
                    });
                }
            }
        });
    }

    function cargar_select2_persona() {
        url_etniaC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_etniaC.php?buscar=true';
        cargar_select2_url('ddl_etnia', url_etniaC);

        url_religionC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_religionC.php?buscar=true';
        cargar_select2_url('ddl_religion', url_religionC);

        url_orientacion_sexualC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_orientacion_sexualC.php?buscar=true';
        cargar_select2_url('ddl_orientacion_sexual', url_orientacion_sexualC);

        url_identidad_generoC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_identidad_generoC.php?buscar=true';
        cargar_select2_url('ddl_identidad_genero', url_identidad_generoC);

        url_nacionalidadC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_paisC.php?buscar_nacionalidad=true';
        cargar_select2_url('ddl_nacionalidad', url_nacionalidadC, '-- Seleccione --', null, 2);

        url_tipo_sangreC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_tipo_sangreC.php?buscar=true';
        cargar_select2_url('ddl_tipo_sangre', url_tipo_sangreC);

        url_tipo_estado_civilC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_tipo_estado_civilC.php?buscar=true';
        cargar_select2_url('ddl_estado_civil', url_tipo_estado_civilC);

        url_tipo_sexoC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_tipo_sexoC.php?buscar=true';
        cargar_select2_url('ddl_sexo', url_tipo_sexoC);

        url_origen_indigenaC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_tipo_origen_indigenaC.php?buscar=true';
        cargar_select2_url('ddl_origen_indigena', url_origen_indigenaC);
    }
</script>

<!-- Atributos de una persona para que se pueda reutilizar en cualquier parte del sistema -->

<div class="container-fluid pt-3">
    <div class="row">
        <!-- <div class="col-md-3 text-center border-end">
            <div class="card shadow-sm">
                <div class="card-body">
                    <label class="form-label d-block"><strong>Fotografía del Usuario</strong></label>

                    <div class="text-center">
                        <div class="position-relative">

                            <div class="widget-user-image text-center">
                                <img class="rounded-circle p-1 bg-primary" src="../img/sin_imagen.jpg" class="img-fluid"
                                    id="img_persona_inf" alt="Imagen Perfil Persona" width="110"
                                    height="110" />
                            </div>

                            <div>
                                <a href="#" class="d-flex justify-content-center" data-bs-toggle="modal"
                                    data-bs-target="#modal_agregar_cambiar_foto_persona"
                                    onclick="abrir_modal_cambiar_foto_persona('<?php //echo $id_persona 
                                                                                ?>');">
                                    <i class='bx bxs-camera bx-sm'></i>
                                </a>
                                <small class="text-muted">Formatos: JPG, PNG. Máx 2MB</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->

        <div class="col-md-12">

            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-light text-primary"><strong><i class="fas fa-user"></i> Datos de Identidad</strong></div>
                <div class="card-body">
                    <div class="row mb-col">
                        <div class="col-md-3">
                            <label for="txt_primer_apellido" class="form-label form-label-sm">Primer Apellido </label>
                            <input type="text" class="form-control form-control-sm no_caracteres" name="txt_primer_apellido" id="txt_primer_apellido" placeholder="Apellido paterno" maxlength="50" oninput="texto_mayusculas(this);">
                        </div>
                        <div class="col-md-3">
                            <label for="txt_segundo_apellido" class="form-label form-label-sm">Segundo Apellido </label>
                            <input type="text" class="form-control form-control-sm no_caracteres" name="txt_segundo_apellido" id="txt_segundo_apellido" placeholder="Apellido materno" maxlength="50" oninput="texto_mayusculas(this);">
                        </div>
                        <div class="col-md-3">
                            <label for="txt_primer_nombre" class="form-label form-label-sm">Primer Nombre </label>
                            <input type="text" class="form-control form-control-sm no_caracteres" name="txt_primer_nombre" id="txt_primer_nombre" placeholder="Primer nombre" maxlength="50" oninput="texto_mayusculas(this);">
                        </div>
                        <div class="col-md-3">
                            <label for="txt_segundo_nombre" class="form-label form-label-sm">Segundo Nombre </label>
                            <input type="text" class="form-control form-control-sm no_caracteres" name="txt_segundo_nombre" id="txt_segundo_nombre" placeholder="Segundo nombre" maxlength="50" oninput="texto_mayusculas(this);">
                        </div>
                    </div>

                    <div class="row mb-col">
                        <div class="col-md-3">
                            <label for="txt_cedula" class="form-label form-label-sm">Cédula de Identidad </label>
                            <input type="text" class="form-control form-control-sm solo_numeros_int" name="txt_cedula" id="txt_cedula" maxlength="10">
                            <span id="error_txt_cedula" class="text-danger small"></span>
                        </div>
                        <div class="col-md-3">
                            <label for="ddl_sexo" class="form-label form-label-sm">Sexo </label>
                            <select class="form-select form-select-sm" id="ddl_sexo" name="ddl_sexo">
                                <option selected disabled value="">Seleccione...</option>
                            </select>
                            <span id="error_ddl_sexo" class="text-danger small"></span>
                        </div>
                        <div class="col-md-3">
                            <label for="txt_fecha_nacimiento" class="form-label form-label-sm">Fecha de nacimiento </label>
                            <input type="date" class="form-control form-control-sm" name="txt_fecha_nacimiento" id="txt_fecha_nacimiento" onblur="calcular_edad('txt_edad', this.value); verificar_fecha_actual('txt_fecha_nacimiento', this.value, 'txt_edad');">
                        </div>
                        <div class="col-md-3">
                            <label for="txt_edad" class="form-label form-label-sm">Edad </label>
                            <input type="text" class="form-control form-control-sm bg-light" name="txt_edad" id="txt_edad" readonly>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <div class="">
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-light text-primary"><strong><i class="fas fa-info-circle"></i> Información Socio-Demográfica</strong></div>
            <div class="card-body">
                <div class="row mb-col">
                    <div class="col-md-4">
                        <label for="ddl_nacionalidad" class="form-label form-label-sm">Nacionalidad </label>
                        <select class="form-select form-select-sm" id="ddl_nacionalidad" name="ddl_nacionalidad">

                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="ddl_estado_civil" class="form-label form-label-sm">Estado civil </label>
                        <select class="form-select form-select-sm" id="ddl_estado_civil" name="ddl_estado_civil">
                            <option selected disabled value="">-- Selecciona un Estado Civil --</option>
                            <option value="Soltero/a">Soltero/a</option>
                            <option value="Casado/a">Casado/a</option>
                            <option value="Divorciado/a">Divorciado/a</option>
                            <option value="Viudo/a">Viudo/a</option>
                            <option value="Unión de hecho">Unión de hecho</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="ddl_tipo_sangre" class="form-label form-label-sm">Grupo Sanguíneo </label>
                        <select class="form-select form-select-sm" id="ddl_tipo_sangre" name="ddl_tipo_sangre">

                        </select>
                    </div>
                </div>
                <div class="row mb-col">
                    <div class="col-md-4">
                        <label for="ddl_orientacion_sexual" class="form-label form-label-sm">Orientación Sexual </label>
                        <select class="form-select form-select-sm select2-validation" id="ddl_orientacion_sexual" name="ddl_orientacion_sexual"></select>
                    </div>
                    <div class="col-md-4">
                        <label for="ddl_religion" class="form-label form-label-sm">Religión </label>
                        <select class="form-select form-select-sm select2-validation" id="ddl_religion" name="ddl_religion"></select>
                    </div>
                    <div class="col-md-4">
                        <label for="ddl_identidad_genero" class="form-label form-label-sm">Identidad Genero </label>
                        <select class="form-select form-select-sm select2-validation" id="ddl_identidad_genero" name="ddl_identidad_genero"></select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <label for="ddl_etnia" class="form-label form-label-sm">Etnía </label>
                        <select class="form-select form-select-sm select2-validation" id="ddl_etnia" name="ddl_etnia"></select>
                    </div>
                    <div class="col-md-9">
                        <label for="ddl_origen_indigena" class="form-label form-label-sm">Origen Indígena </label>
                        <select class="form-select form-select-sm select2-validation" id="ddl_origen_indigena" name="ddl_origen_indigena"></select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-light text-primary"><strong><i class="fas fa-map-marker-alt"></i> Ubicación y Contacto</strong></div>
            <div class="card-body">
                <?php include_once('../vista/GENERAL/provincias_ciudades_parroquias.php'); ?>

                <div class="row mb-col">
                    <div class="col-md-6">
                        <label for="txt_calle_primaria" class="form-label form-label-sm">Calle Principal </label>
                        <input type="text" class="form-control form-control-sm" name="txt_calle_primaria" id="txt_calle_primaria" maxlength="200" oninput="texto_mayusculas(this);">
                    </div>

                    <div class="col-md-6">
                        <label for="txt_calle_secundaria" class="form-label form-label-sm">Calle Secundaria </label>
                        <input type="text" class="form-control form-control-sm" name="txt_calle_secundaria" id="txt_calle_secundaria" maxlength="200" oninput="texto_mayusculas(this);">
                    </div>
                </div>

                <div class="row mb-col">
                    <div class="col-md-12">
                        <label for="txt_direccion" class="form-label form-label-sm">
                            Dirección Exacta
                            <i class='bx bx-info-circle text-primary'
                                data-bs-toggle="tooltip"
                                title="Ingrese referencias adicionales como edificio, piso, departamento, conjunto o puntos de referencia.">
                            </i>
                        </label>

                        <textarea
                            class="form-control form-control-sm"
                            name="txt_direccion"
                            id="txt_direccion"
                            rows="3"
                            maxlength="300"
                            placeholder="Ej: Edif. Torres del Norte, piso 3, dpto. 302. Frente al parque central."
                            oninput="texto_mayusculas(this);"></textarea>
                    </div>
                </div>

                <div class="row mb-col">
                    <div class="col-md-3">
                        <label for="txt_telefono_1" class="form-label form-label-sm">Teléfono Principal </label>
                        <input type="text" class="form-control form-control-sm solo_numeros_int" name="txt_telefono_1" id="txt_telefono_1" maxlength="12">
                    </div>
                    <div class="col-md-3">
                        <label for="txt_telefono_2" class="form-label form-label-sm">Teléfono Alternativo </label>
                        <input type="text" class="form-control form-control-sm solo_numeros_int" name="txt_telefono_2" id="txt_telefono_2" maxlength="12">
                    </div>
                    <div class="col-md-6">
                        <label for="txt_correo" class="form-label form-label-sm">Correo Institucional </label>
                        <input type="email" class="form-control form-control-sm" name="txt_correo" id="txt_correo" maxlength="100" oninput="texto_minusculas(this);">
                    </div>
                </div>

                <div class="row mb-col">
                    <div class="col-md-6">
                        <label for="txt_per_correo_personal_1" class="form-label form-label-sm">Correo Personal </label>
                        <input type="email" class="form-control form-control-sm" name="txt_per_correo_personal_1" id="txt_per_correo_personal_1" maxlength="100" oninput="texto_minusculas(this);">
                    </div>
                    <div class="col-md-6">
                        <label for="txt_per_correo_personal_2" class="form-label form-label-sm">Correo Personal Alternativo </label>
                        <input type="email" class="form-control form-control-sm" name="txt_per_correo_personal_2" id="txt_per_correo_personal_2" maxlength="100" oninput="texto_minusculas(this);">
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body">
                <label for="txt_observaciones" class="form-label form-label-sm">Observaciones Adicionales </label>
                <textarea class="form-control form-control-sm" name="txt_observaciones" id="txt_observaciones" rows="2" maxlength="200" oninput="texto_mayusculas(this);"></textarea>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        agregar_asterisco_campo_obligatorio('txt_primer_apellido');
        // agregar_asterisco_campo_obligatorio('txt_segundo_apellido');
        agregar_asterisco_campo_obligatorio('txt_primer_nombre');
        // agregar_asterisco_campo_obligatorio('txt_segundo_nombre');
        agregar_asterisco_campo_obligatorio('txt_cedula');
        agregar_asterisco_campo_obligatorio('ddl_sexo');
        agregar_asterisco_campo_obligatorio('txt_fecha_nacimiento');
        // agregar_asterisco_campo_obligatorio('txt_edad');
        agregar_asterisco_campo_obligatorio('txt_telefono_1');
        agregar_asterisco_campo_obligatorio('txt_correo');
        agregar_asterisco_campo_obligatorio('ddl_provincias');
        agregar_asterisco_campo_obligatorio('ddl_ciudad');
        //agregar_asterisco_campo_obligatorio('ddl_parroquia');
        agregar_asterisco_campo_obligatorio('txt_codigo_postal');
        //agregar_asterisco_campo_obligatorio('txt_direccion');

    });
</script>