<script>
    function insertar() {
        var txt_tipo = 'persona_juridica';
        var txt_razon_social = $('#txt_razon_social').val();
        var txt_ruc_juridico = $('#txt_ruc_juridico').val();
        var txt_primer_nombre = $('#txt_primer_nombre').val();
        var txt_segundo_nombre = $('#txt_segundo_nombre').val();
        var txt_primer_apellido = $('#txt_primer_apellido').val();
        var txt_segundo_apellido = $('#txt_segundo_apellido').val();
        var txt_numero_identificacion = $('#txt_numero_identificacion').val();
        var txt_direccion_ruc = $('#txt_direccion_ruc').val();
        var txt_provincia = $('#txt_provincia').val();
        var txt_ciudad = $('#txt_ciudad').val();
        var txt_correo_empresarial = $('#txt_correo_empresarial').val();
        var txt_celular = $('#txt_celular').val();
        var txt_fijo = $('#txt_fijo').val();
        
        var parametros = {
            'txt_tipo': txt_tipo,
            'txt_razon_social': txt_razon_social,
            'txt_ruc_juridico': txt_ruc_juridico,
            'txt_primer_nombre': txt_primer_nombre,
            'txt_segundo_nombre': txt_segundo_nombre,
            'txt_primer_apellido': txt_primer_apellido,
            'txt_segundo_apellido': txt_segundo_apellido,
            'txt_numero_identificacion': txt_numero_identificacion,
            'txt_direccion_ruc': txt_direccion_ruc,
            'txt_provincia': txt_provincia,
            'txt_ciudad': txt_ciudad,
            'txt_correo_empresarial': txt_correo_empresarial,
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
                    alert('Ingresado')
                } else {
                    alert('Error')
                }
            }
        });
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
                                    <div class="col-6">
                                        <div>
                                            <div class="card-title d-flex align-items-center">
                                                <div><i class="bx bxs-user me-1 font-22 text-primary"></i></div>
                                                <h5 class="mb-0 text-primary">Formulario Persona Juridica</h5>
                                            </div>
                                            <hr />
                                            <div>
                                                <div class="row mb-3">
                                                    <label for="txt_razon_social" class="col-sm-4 col-form-label">Raz&oacute;n Social</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control form-control-sm" id="txt_razon_social" placeholder="Raz&oacute;n Social" maxlength="2500">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="txt_ruc_juridico" class="col-sm-4 col-form-label">R.U.C</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control form-control-sm" id="txt_ruc_juridico" placeholder="R.U.C" maxlength="13">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="txt_primer_nombre" class="col-sm-4 col-form-label">Primer Nombre</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control form-control-sm" id="txt_primer_nombre" placeholder="Primer Nombre" maxlength="20">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="txt_segundo_nombre" class="col-sm-4 col-form-label">Segundo Nombre</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control form-control-sm" id="txt_segundo_nombre" placeholder="Segundo Nombre" maxlength="20">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="txt_primer_apellido" class="col-sm-4 col-form-label">Primer Apellido</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control form-control-sm" id="txt_primer_apellido" placeholder="Primer Apellido" maxlength="20">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="txt_segundo_apellido" class="col-sm-4 col-form-label">Segundo Apellido</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control form-control-sm" id="txt_segundo_apellido" placeholder="Segundo Apellido" maxlength="20">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="txt_numero_identificacion" class="col-sm-4 col-form-label">N&uacute;mero de C&eacute;dula o Pasaporte</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control form-control-sm" id="txt_numero_identificacion" placeholder="N&uacute;mero de C&eacute;dula o Pasaporte" maxlength="10">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="txt_direccion_ruc" class="col-sm-4 col-form-label">Direcci&oacute;n como está en el RUC</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control form-control-sm" id="txt_direccion_ruc" placeholder="Direcci&oacute;n como está en el RUC" maxlength="150">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="txt_provincia" class="col-sm-4 col-form-label">Provincia</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control form-control-sm" id="txt_provincia" placeholder="Provincia" maxlength="27">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="txt_ciudad" class="col-sm-4 col-form-label">Ciudad</label>
                                                    <div class="col-sm-8">
                                                        <input type="text" class="form-control form-control-sm" id="txt_ciudad" placeholder="Ciudad">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="txt_correo_empresarial" class="col-sm-4 col-form-label">Correo Electr&oacute;nico Empresarial</label>
                                                    <div class="col-sm-8">
                                                        <input type="email" class="form-control form-control-sm" id="txt_correo_empresarial" placeholder="Correo Electr&oacute;nico Empresarial" maxlength="38">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="txt_celular" class="col-sm-4 col-form-label">No. Celular</label>
                                                    <div class="col-sm-8">
                                                        <input type="tel" class="form-control form-control-sm" id="txt_celular" placeholder="No. Celular (Poner código de país)" maxlength="13">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <label for="txt_fijo" class="col-sm-4 col-form-label">No. Fijo</label>
                                                    <div class="col-sm-8">
                                                        <input type="tel" class="form-control form-control-sm" id="txt_fijo" placeholder="No. Fijo (Poner código de país)" maxlength="9">
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-4"></div>
                                                    <div class="col-sm-8">
                                                        <button type="button" class="btn btn-success btn-sm px-5" onclick="insertar();">Guardar</button>
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