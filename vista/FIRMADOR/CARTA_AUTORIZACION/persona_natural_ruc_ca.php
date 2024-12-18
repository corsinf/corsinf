<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script>
    function editar_insertar() {
        var txt_tipo = 'persona_natural_ruc';
        var txt_direccion_domicilio = $('#txt_direccion_domicilio').val();
        var txt_primer_nombre = $('#txt_primer_nombre').val();
        var txt_segundo_nombre = $('#txt_segundo_nombre').val();
        var txt_primer_apellido = $('#txt_primer_apellido').val();
        var txt_segundo_apellido = $('#txt_segundo_apellido').val();
        var txt_numero_identificacion = $('#txt_numero_identificacion').val();
        var txt_provincia = $('#txt_provincia').val();
        var txt_ciudad = $('#txt_ciudad').val();
        var txt_correo = $('#txt_correo').val();
        var txt_celular = $('#txt_celular').val();
        var txt_fijo = $('#txt_fijo').val();

        var parametros = {
            'txt_tipo': txt_tipo,
            'txt_direccion_domicilio': txt_direccion_domicilio,
            'txt_primer_nombre': txt_primer_nombre,
            'txt_segundo_nombre': txt_segundo_nombre,
            'txt_primer_apellido': txt_primer_apellido,
            'txt_segundo_apellido': txt_segundo_apellido,
            'txt_numero_identificacion': txt_numero_identificacion,
            'txt_provincia': txt_provincia,
            'txt_ciudad': txt_ciudad,
            'txt_correo': txt_correo,
            'txt_celular': txt_celular,
            'txt_fijo': txt_fijo,
        };

        $.ajax({
            data: {
                parametros: parametros,
            },
            url: '../controlador/PASANTES/01_SEBASTIAN/formularios_firmasC.php?insertar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        location.href = 'http://localhost/corsinf/vista/inicio.php?mod=1010&acc=solicitudes';
                    });
                } else {
                    Swal.fire('', 'Operación fallida', 'error');
                }
            }
        });
    }

    function insertar(parametros) {
        $.ajax({
            data: {
                parametros: parametros,
            },
            url: '../controlador/PASANTES/01_SEBASTIAN/formularios_firmasC.php?insertar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=1010&acc=solicitudes';
                    });
                } else {
                    Swal.fire('', 'Operación fallida', 'error');
                }
            }
        });
    }

    function validar() {
        var txt_primer_nombre = $('#txt_primer_nombre').val().trim();
        var txt_segundo_nombre = $('#txt_segundo_nombre').val().trim();
        var txt_primer_apellido = $('#txt_primer_apellido').val().trim();
        var txt_segundo_apellido = $('#txt_segundo_apellido').val().trim();
        var txt_numero_identificacion = $('#txt_numero_identificacion').val().trim();
        var txt_direccion_domicilio = $('#txt_direccion_domicilio').val().trim();
        var txt_provincia = $('#txt_provincia').val().trim();
        var txt_ciudad = $('#txt_ciudad').val().trim();
        var txt_correo = $('#txt_correo').val().trim();
        var txt_celular = $('#txt_celular').val().trim();
        var txt_fijo = $('#txt_fijo').val().trim();
        var allFilled = true;

        if (!txt_primer_nombre) {
            $('#txt_primer_nombre').addClass('is-invalid');
            allFilled = false;
        } else {
            $('#txt_primer_nombre').removeClass('is-invalid');
        }

        if (!txt_segundo_nombre) {
            $('#txt_segundo_nombre').addClass('is-invalid');
            allFilled = false;
        } else {
            $('#txt_segundo_nombre').removeClass('is-invalid');
        }

        if (!txt_primer_apellido) {
            $('#txt_primer_apellido').addClass('is-invalid');
            allFilled = false;
        } else {
            $('#txt_primer_apellido').removeClass('is-invalid');
        }

        if (!txt_segundo_apellido) {
            $('#txt_segundo_apellido').addClass('is-invalid');
            allFilled = false;
        } else {
            $('#txt_segundo_apellido').removeClass('is-invalid');
        }

        if (!txt_numero_identificacion) {
            $('#txt_numero_identificacion').addClass('is-invalid');
            allFilled = false;
        } else {
            $('#txt_numero_identificacion').removeClass('is-invalid');
        }

        if (!txt_direccion_domicilio) {
            $('#txt_direccion_domicilio').addClass('is-invalid');
            allFilled = false;
        } else {
            $('#txt_direccion_domicilio').removeClass('is-invalid');
        }

        if (!txt_provincia) {
            $('#txt_provincia').addClass('is-invalid');
            allFilled = false;
        } else {
            $('#txt_provincia').removeClass('is-invalid');
        }

        if (!txt_ciudad) {
            $('#txt_ciudad').addClass('is-invalid');
            allFilled = false;
        } else {
            $('#txt_ciudad').removeClass('is-invalid');
        }

        if (!txt_correo) {
            $('#txt_correo').addClass('is-invalid');
            allFilled = false;
        } else {
            $('#txt_correo').removeClass('is-invalid');
        }

        if (!txt_celular) {
            $('#txt_celular').addClass('is-invalid');
            allFilled = false;
        } else {
            $('#txt_celular').removeClass('is-invalid');
        }

        if (allFilled) {
            insertar();
        } else {
            Swal.fire('', 'Operación fallida', 'error');
        }
    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Firmador</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Formulario
                        </li>
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

                            <h5 class="mb-0 text-primary"></h5>

                            <div class="row mx-0">
                                <div class="col-sm-12" id="btn_nuevo">

                                    <!-- Para agregar botones -->

                                </div>
                            </div>
                        </div>


                        <section class="content pt-2">
                            <div class="container-fluid">

                                <div class="row">
                                    <div class="col-xl-9 mx-12">
                                        <div>
                                            <div class="card-title d-flex align-items-center">
                                                <div><i class="bx bxs-user me-1 font-22 text-primary"></i></div>
                                                <h5 class="mb-0 text-primary">Formulario Persona Natural RUC</h5>
                                            </div>
                                            <hr />
                                            <div>
                                                <div class="row mb-3">
                                                    <label for="txt_primer_nombre" class="col-sm-4 col-form-label">Primer Nombre</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control form-control-sm" id="txt_primer_nombre" placeholder="Primer Nombre" maxlength="20" oninput="this.value = this.value.replace(/[^a-zA-ZñÑ\s]/g, '')">
                                                        <div class="invalid-feedback">Porfavor ingresa un nombre valido</div>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="txt_segundo_nombre" class="col-sm-4 col-form-label">Segundo Nombre</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control form-control-sm" id="txt_segundo_nombre" placeholder="Segundo Nombre" maxlength="20" oninput="this.value = this.value.replace(/[^a-zA-ZñÑ\s]/g, '')">
                                                        <div class="invalid-feedback">Porfavor ingresa un nombre valido</div>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="txt_primer_apellido" class="col-sm-4 col-form-label">Primer Apellido</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control form-control-sm" id="txt_primer_apellido" placeholder="Primer Apellido" maxlength="20" oninput="this.value = this.value.replace(/[^a-zA-ZñÑ\s]/g, '')">
                                                        <div class="invalid-feedback">Porfavor ingresa un apellido valido</div>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="txt_segundo_apellido" class="col-sm-4 col-form-label">Segundo Apellido</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control form-control-sm" id="txt_segundo_apellido" placeholder="Segundo Apellido" maxlength="20" oninput="this.value = this.value.replace(/[^a-zA-ZñÑ\s]/g, '')">
                                                        <div class="invalid-feedback">Porfavor ingresa un apellido valido</div>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="txt_numero_identificacion" class="col-sm-4 col-form-label">N&uacute;mero de RUC</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control form-control-sm" id="txt_numero_identificacion" placeholder="N&uacute;mero de RUC" maxlength="13" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                                        <div class="invalid-feedback">Porfavor ingresa un número de RUC valido</div>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="txt_direccion_domicilio" class="col-sm-4 col-form-label">Direcci&oacute;n Domicilio</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control form-control-sm" id="txt_direccion_domicilio" placeholder="Direcci&oacute;n Domicilio" maxlength="150">
                                                        <div class="invalid-feedback">Porfavor ingresa una dirección valida</div>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="txt_provincia" class="col-sm-4 col-form-label">Provincia</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control form-control-sm" id="txt_provincia" placeholder="Provincia" maxlength="27" oninput="this.value = this.value.replace(/[^a-zA-ZñÑ\s]/g, '')">
                                                        <div class="invalid-feedback">Porfavor ingresa una provincia valida</div>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="txt_ciudad" class="col-sm-4 col-form-label">Ciudad</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control form-control-sm" id="txt_ciudad" placeholder="Ciudad" maxlength="38" oninput="this.value = this.value.replace(/[^a-zA-ZñÑ\s]/g, '')">
                                                        <div class="invalid-feedback">Porfavor ingresa una ciudad valida</div>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="txt_correo" class="col-sm-4 col-form-label">Correo Electr&oacute;nico</label>
                                                    <div class="col-sm-8">
                                                        <input type="email" class="form-control form-control-sm" id="txt_correo" placeholder="Correo Electr&oacute;nico" maxlength="150">
                                                        <div class="invalid-feedback">Porfavor ingresa un correo valido</div>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="txt_celular" class="col-sm-4 col-form-label">No. Celular</label>
                                                    <div class="col-sm-8">
                                                        <input type="tel" class="form-control form-control-sm" id="txt_celular" placeholder="No. Celular (Poner código de país)" maxlength="13" oninput="this.value = this.value.replace(/[^0-9+]/g, '')">
                                                        <div class="invalid-feedback">Porfavor ingresa un número de celular valido</div>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="txt_fijo" class="col-sm-4 col-form-label">No. Fijo</label>
                                                    <div class="col-sm-8">
                                                        <input type="tel" class="form-control form-control-sm" id="txt_fijo" placeholder="No. Fijo (Poner código de país)" maxlength="9" oninput="this.value = this.value.replace(/[^0-9+]/g, '')">
                                                        <div class="invalid-feedback">Porfavor ingresa un número fijo valido</div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-4"></div>
                                                    <div class="col-sm-8">
                                                        <button type="button" class="btn btn-success btn-sm px-5" onclick="validar();">Guardar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.container-fluid -->
                        </section>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>