<?php
$redireccionar_vista = 'th_postulantes';
if (isset($_GET['_origen']) && $_GET['_origen'] == 'postulante_info') {
    $redireccionar_vista = 'th_personas_nomina';
}

?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>


<script type="text/javascript">
    $(document).ready(function() {
        <?php if (isset($_GET['id'])) { ?>
            cargarDatos(<?= $id ?>);
        <?php } ?>
        cargar_selects2();
    })

    function cargar_selects2() {

        url_etniaC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_etniaC.php?buscar=true';
        cargar_select2_url('ddl_etnia', url_etniaC);

        url_religionC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_religionC.php?buscar=true';
        cargar_select2_url('ddl_religion', url_religionC);

        url_orientacion_sexualC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_orientacion_sexualC.php?buscar=true';
        cargar_select2_url('ddl_orientacion_sexual', url_orientacion_sexualC);

        url_identidad_generoC = '../controlador/TALENTO_HUMANO/CATALOGOS/th_cat_identidad_generoC.php?buscar=true';
        cargar_select2_url('ddl_identidad_genero', url_identidad_generoC);

    }


    function cargarDatos(id) {
        $.ajax({
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_postulantesC.php?listar=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {

                if (response.recargar == 1 && response.id_postulante) {
                    let nueva_Url = `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_informacion_personal&id=${response.id_postulante}&id_persona=<?= $id_persona ?>`;

                    // Cambia la URL sin recargar
                    window.history.replaceState(null, '', nueva_Url);

                    // Recarga real solo una vez
                    location.reload();
                    return;
                }

                $('#txt_primer_nombre').val(response[0].th_pos_primer_nombre);
                $('#txt_segundo_nombre').val(response[0].th_pos_segundo_nombre);
                $('#txt_primer_apellido').val(response[0].th_pos_primer_apellido);
                $('#txt_segundo_apellido').val(response[0].th_pos_segundo_apellido);
                $('#txt_fecha_nacimiento').val(response[0].th_pos_fecha_nacimiento);
                $('#ddl_nacionalidad').val(response[0].th_pos_nacionalidad);
                $('#txt_cedula').val(response[0].th_pos_cedula);
                $('#ddl_estado_civil').val(response[0].th_pos_estado_civil);
                $('#ddl_sexo').val(response[0].th_pos_sexo);
                $('#txt_telefono_1').val(response[0].th_pos_telefono_1);
                $('#txt_telefono_2').val(response[0].th_pos_telefono_2);
                $('#txt_correo').val(response[0].th_pos_correo);
                $('#txt_codigo_postal').val(response[0].th_pos_postal);
                $('#txt_direccion').val(response[0].th_pos_direccion);

                calcular_edad('txt_edad', response[0].th_pos_fecha_nacimiento);

                //Cargar Selects de provincia-ciudad-parroquia
                url_provinciaC = '../controlador/GENERAL/th_provinciasC.php?listar=true';
                cargar_select2_con_id('ddl_provincias', url_provinciaC, response[0].th_prov_id, 'th_prov_nombre');

                url_ciudadC = '../controlador/GENERAL/th_ciudadC.php?listar=true';
                cargar_select2_con_id('ddl_ciudad', url_ciudadC, response[0].th_ciu_id, 'th_ciu_nombre');

                url_parroquiaC = '../controlador/GENERAL/th_parroquiasC.php?listar=true';
                cargar_select2_con_id('ddl_parroquia', url_parroquiaC, response[0].th_parr_id, 'th_parr_nombre');
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
                $('#txt_per_correo_personal_1').val(response[0].th_pos_correo_personal_1);
                $('#txt_per_correo_personal_2').val(response[0].th_pos_correo_personal_2);


                $('#txt_nombres_completos_v').html(response[0].nombres_completos);
                $('#txt_fecha_nacimiento_v').html(response[0].th_pos_fecha_nacimiento);
                $('#txt_nacionalidad_v').html(response[0].th_pos_nacionalidad);
                $('#txt_estado_civil_v').html(response[0].th_pos_estado_civil);
                $('#txt_numero_cedula_v').html(response[0].th_pos_cedula);
                $('#txt_telefono_1_v').html(response[0].th_pos_telefono_1);
                $('#txt_correo_v').html(response[0].th_pos_correo);

                // //Input para todos los pos_id que se vayan a colocar en los modales
                // $('input[name="txt_postulante_id"]').val(response[0]._id);
                // $('input[name="txt_postulante_cedula"]').val(response[0].th_pos_cedula);
            },

            error: function(xhr, status, error) {
                console.log('Status: ' + status);
                console.log('Error: ' + error);
                console.log('XHR Response: ' + xhr.responseText);

                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });
    }

    function insertar_editar(redireccionar_vista = 'th_postulantes') {

        var txt_primer_nombre = $('#txt_primer_nombre').val();
        var txt_segundo_nombre = $('#txt_segundo_nombre').val();
        var txt_primer_apellido = $('#txt_primer_apellido').val();
        var txt_segundo_apellido = $('#txt_segundo_apellido').val();
        var txt_fecha_nacimiento = $('#txt_fecha_nacimiento').val();
        var ddl_nacionalidad = $('#ddl_nacionalidad').val();
        var txt_cedula = $('#txt_cedula').val();
        var ddl_estado_civil = $('#ddl_estado_civil').val();
        var ddl_sexo = $('#ddl_sexo').val();
        var txt_telefono_1 = $('#txt_telefono_1').val();
        var txt_telefono_2 = $('#txt_telefono_2').val();
        var txt_correo = $('#txt_correo').val();
        var ddl_provincias = $('#ddl_provincias').val();
        var ddl_ciudad = $('#ddl_ciudad').val();
        var ddl_parroquia = $('#ddl_parroquia').val();
        var txt_codigo_postal = $('#txt_codigo_postal').val();
        var txt_direccion = $('#txt_direccion').val();
        var ddl_etnia = $('#ddl_etnia').val();
        var ddl_religion = $('#ddl_religion').val();
        var ddl_orientacion_sexual = $('#ddl_orientacion_sexual').val();
        var ddl_identidad_genero = $('#ddl_identidad_genero').val();
        var txt_per_correo_personal_1 = $('#txt_per_correo_personal_1').val();
        var txt_per_correo_personal_2 = $('#txt_per_correo_personal_2').val();

        var parametros = {
            '_id': '<?= $id ?>',
            'txt_primer_nombre': txt_primer_nombre,
            'txt_segundo_nombre': txt_segundo_nombre,
            'txt_primer_apellido': txt_primer_apellido,
            'txt_segundo_apellido': txt_segundo_apellido,
            'txt_fecha_nacimiento': txt_fecha_nacimiento,
            'ddl_nacionalidad': ddl_nacionalidad,
            'txt_cedula': txt_cedula,
            'ddl_estado_civil': ddl_estado_civil,
            'ddl_sexo': ddl_sexo,
            'txt_telefono_1': txt_telefono_1,
            'txt_telefono_2': txt_telefono_2,
            'txt_correo': txt_correo,
            'ddl_provincias': ddl_provincias,
            'ddl_ciudad': ddl_ciudad,
            'ddl_parroquia': ddl_parroquia,
            'txt_codigo_postal': txt_codigo_postal,
            'txt_direccion': txt_direccion,
            'ddl_etnia': ddl_etnia,
            'ddl_religion': ddl_religion,
            'ddl_orientacion_sexual': ddl_orientacion_sexual,
            'ddl_identidad_genero': ddl_identidad_genero,
            'txt_per_correo_personal_1': txt_per_correo_personal_1,
            'txt_per_correo_personal_2': txt_per_correo_personal_2,

        };

        if ($("#form_registrar_postulantes").valid()) {
            // Si es válido, puedes proceder a enviar los datos por AJAX
            //.log(parametros);
            insertar(parametros, redireccionar_vista);
        }
    }

    function insertar(parametros, redireccionar_vista) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_postulantesC.php?insertar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    if(redireccionar_vista == 'th_informacion_personal'){
                        redireccionar_vista = 'th_informacion_personal' + '&id=<?= $id ?>';
                    }

                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=' + redireccionar_vista;
                    });
                } else if (response == -2) {
                    $(txt_cedula).addClass('is-invalid');
                    $('#error_txt_cedula').text('La cédula ya está en uso.');
                }
            },

            error: function(xhr, status, error) {
                console.log('Status: ' + status);
                console.log('Error: ' + error);
                console.log('XHR Response: ' + xhr.responseText);

                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });

        $('#txt_cedula').on('input', function() {
            $('#error_txt_cedula').text('');
        });
    }

    function delete_datos() {
        var id = '<?php echo $id; ?>';
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
                eliminar(id);
            }
        })
    }

    function eliminar(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/POSTULANTES/th_postulantesC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_postulantes';
                    });
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
</script>

<form id="form_registrar_postulantes" class="modal_general_provincias">
    <div class="row mb-col pt-3">
        <div class="col-md-3">
            <label for="txt_primer_apellido" class="form-label form-label-sm">Primer Apellido </label>
            <input type="text" class="form-control form-control-sm no_caracteres" name="txt_primer_apellido" id="txt_primer_apellido" placeholder="Escriba su apellido paterno" maxlength="50" required>
        </div>
        <div class="col-md-3">
            <label for="txt_segundo_apellido" class="form-label form-label-sm">Segundo Apellido </label>
            <input type="text" class="form-control form-control-sm no_caracteres" name="txt_segundo_apellido" id="txt_segundo_apellido" placeholder="Escriba su apellido materno" maxlength="50" required>
        </div>
        <div class="col-md-3">
            <label for="txt_primer_nombre" class="form-label form-label-sm">Primer Nombre </label>
            <input type="text" class="form-control form-control-sm no_caracteres" name="txt_primer_nombre" id="txt_primer_nombre" placeholder="Escriba su primer nombre" maxlength="50" required>
        </div>
        <div class="col-md-3">
            <label for="txt_segundo_nombre" class="form-label form-label-sm">Segundo Nombre </label>
            <input type="text" class="form-control form-control-sm no_caracteres" name="txt_segundo_nombre" id="txt_segundo_nombre" placeholder="Escriba su primer nombre" maxlength="50" required>
        </div>
    </div>

    <div class="row mb-col">
        <div class="col-md-3">
            <label for="txt_cedula" class="form-label form-label-sm">Cédula de Identidad </label>
            <input type="text" class="form-control form-control-sm solo_numeros_int" name="txt_cedula" id="txt_cedula" placeholder="Digite su número de cédula" maxlength="10" required>
            <span id="error_txt_cedula" class="text-danger"></span>
        </div>
        <div class="col-md-3">
            <label for="ddl_sexo" class="form-label form-label-sm">Sexo </label>
            <select class="form-select form-select-sm" id="ddl_sexo" name="ddl_sexo" required>
                <option selected disabled value="">-- Selecciona una opción --</option>
                <option value="Masculino">Masculino</option>
                <option value="Femenino">Femenino</option>
            </select>
        </div>
        <div class="col-md-3">
            <label for="txt_fecha_nacimiento" class="form-label form-label-sm">Fecha de nacimiento </label>
            <input type="date" class="form-control form-control-sm" name="txt_fecha_nacimiento" id="txt_fecha_nacimiento" onblur="calcular_edad('txt_edad', this.value); verificar_fecha_actual('txt_fecha_nacimiento', this.value, 'txt_edad');" required>
        </div>
        <div class="col-md-3">
            <label for="txt_edad" class="form-label form-label-sm">Edad </label>
            <input type="text" class="form-control form-control-sm solo_numeros_int" name="txt_edad" id="txt_edad" readonly>
        </div>
    </div>

    <div class="row mb-col">
        <div class="col-md-4">
            <label for="txt_telefono_1" class="form-label form-label-sm">Teléfono 1 </label>
            <input type="text" class="form-control form-control-sm solo_numeros_int" name="txt_telefono_1" id="txt_telefono_1" value="" maxlength="12">
        </div>
        <div class="col-md-4">
            <label for="txt_telefono_2" class="form-label form-label-sm">Teléfono 2 </label>
            <input type="text" class="form-control form-control-sm solo_numeros_int" name="txt_telefono_2" id="txt_telefono_2" value="" maxlength="12">
        </div>
        <div class="col-md-4">
            <label for="txt_correo" class="form-label form-label-sm">Correo Electrónico </label>
            <input type="email" class="form-control form-control-sm" name="txt_correo" id="txt_correo" value="" maxlength="100">
        </div>
    </div>

    <div class="row mb-col">
        <div class="col-md-6">
            <label for="ddl_nacionalidad" class="form-label form-label-sm">Nacionalidad </label>
            <select class="form-select form-select-sm" id="ddl_nacionalidad" name="ddl_nacionalidad">
                <option selected disabled value="">-- Selecciona una Nacionalidad --</option>
                <option value="Ecuatoriano">Ecuatoriano</option>
                <option value="Colombiano">Colombiano</option>
                <option value="Peruano">Peruano</option>
                <option value="Venezolano">Venezolano</option>
                <option value="Paraguayo">Paraguayo</option>
            </select>
        </div>
        <div class="col-md-6">
            <label for="ddl_estado_civil" class="form-label form-label-sm">Estado civil</label>
            <select class="form-select form-select-sm" id="ddl_estado_civil" name="ddl_estado_civil">
                <option selected disabled value="">-- Selecciona un Estado Civil --</option>
                <option value="Soltero">Soltero/a</option>
                <option value="Casado">Casado/a</option>
                <option value="Divorciado">Divorciado/a</option>
                <option value="Viudo">Viudo/a</option>
                <option value="Union">Unión de hecho</option>
            </select>
        </div>

    </div>

    <!-- Vista de provincias reutilizada -->
    <?php include_once('../vista/GENERAL/provincias_ciudades_parroquias.php'); ?>

    <div class="row mb-col">
        <div class="col-md-12">
            <label for="txt_direccion" class="form-label form-label-sm">Dirección </label>
            <input type="text" class="form-control form-control-sm" name="txt_direccion" id="txt_direccion" maxlength="200">
        </div>
    </div>

    <div class="row mb-col">
        <div class="col-md-3">
            <label for="ddl_etnia" class="form-label form-label-sm">Etnía </label>
            <select class="form-select form-select-sm select2-validation" id="ddl_etnia" name="ddl_etnia" maxlenght="5000">
            </select>
            <label class="error" style="display: none;" for="ddl_etnia"></label>
        </div>
        <div class="col-md-3">
            <label for="ddl_orientacion_sexual" class="form-label form-label-sm">Orientación Sexual </label>
            <select class="form-select form-select-sm select2-validation" id="ddl_orientacion_sexual" name="ddl_orientacion_sexual" maxlenght="5000">
            </select>
            <label class="error" style="display: none;" for="ddl_orientacion_sexual"></label>
        </div>
        <div class="col-md-3">
            <label for="ddl_religion" class="form-label form-label-sm">Religión </label>
            <select class="form-select form-select-sm select2-validation" id="ddl_religion" name="ddl_religion" maxlenght="5000">
            </select>
            <label class="error" style="display: none;" for="ddl_religion"></label>
        </div>
        <div class="col-md-3">
            <label for="ddl_identidad_genero" class="form-label form-label-sm">Identidad Genero</label>
            <select class="form-select form-select-sm select2-validation" id="ddl_identidad_genero" name="ddl_identidad_genero" maxlenght="5000">
            </select>
            <label class="error" style="display: none;" for="ddl_identidad_genero"></label>
        </div>
    </div>

    <div class="row mb-col">
        <div class="col-md-6">
            <label for="txt_per_correo_personal_1" class="form-label form-label-sm">Correo Personal</label>
            <input type="email" class="form-control form-control-sm" name="txt_per_correo_personal_1" id="txt_per_correo_personal_1" value="" maxlength="100">
        </div>
        <div class="col-md-6">
            <label for="txt_per_correo_personal_2" class="form-label form-label-sm">Correo Personal Alternativo</label>
            <input type="email" class="form-control form-control-sm" name="txt_per_correo_personal_2" id="txt_per_correo_personal_2" value="" maxlength="100">
        </div>
    </div>

</form>