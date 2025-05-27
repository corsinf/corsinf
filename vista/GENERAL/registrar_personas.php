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
                $('#txt_primer_nombre').val(response[0].primer_nombre);
                $('#txt_segundo_nombre').val(response[0].segundo_nombre);
                $('#txt_primer_apellido').val(response[0].primer_apellido);
                $('#txt_segundo_apellido').val(response[0].segundo_apellido);
                $('#txt_fecha_nacimiento').val(response[0].fecha_nacimiento);
                $('#ddl_nacionalidad').val(response[0].nacionalidad);
                $('#txt_cedula').val(response[0].cedula);
                $('#ddl_estado_civil').val(response[0].estado_civil);
                $('#ddl_sexo').val(response[0].sexo);
                $('#txt_telefono_1').val(response[0].telefono_1);
                $('#txt_telefono_2').val(response[0].telefono_2);
                $('#txt_correo').val(response[0].correo);
                $('#txt_codigo_postal').val(response[0].postal);
                $('#txt_direccion').val(response[0].direccion);
                $('#txt_observaciones').val(response[0].observaciones);

                calcular_edad('txt_edad', response[0].fecha_nacimiento);

                if (response[0].id_provincia != null) {
                    //Cargar Selects de provincia-ciudad-parroquia
                    url_provinciaC = '../controlador/GENERAL/th_provinciasC.php?listar=true';
                    cargar_select2_con_id('ddl_provincias', url_provinciaC, response[0].id_provincia, 'th_prov_nombre');

                    url_ciudadC = '../controlador/GENERAL/th_ciudadC.php?listar=true';
                    cargar_select2_con_id('ddl_ciudad', url_ciudadC, response[0].id_ciudad, 'th_ciu_nombre');

                    url_parroquiaC = '../controlador/GENERAL/th_parroquiasC.php?listar=true';
                    cargar_select2_con_id('ddl_parroquia', url_parroquiaC, response[0].id_parroquia, 'th_parr_nombre');
                }


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
            'txt_cedula': $('#txt_cedula').val(),
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
        };
    }

    function delete_datos_persona() {
        var id = '<?= $_id; ?>';
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
</script>

<!-- Atributos de una persona para que se pueda reutilizar en cualquier parte del sistema -->

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
            <option value="Soltero/a">Soltero/a</option>
            <option value="Casado/a">Casado/a</option>
            <option value="Divorciado/a">Divorciado/a</option>
            <option value="Viudo/a">Viudo/a</option>
            <option value="Unión de hecho">Unión de hecho</option>
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
    <div class="col-md-12">
        <label for="txt_observaciones" class="form-label form-label-sm">Observaciones </label>
        <input type="text" class="form-control form-control-sm" name="txt_observaciones" id="txt_observaciones" maxlength="200">
    </div>
</div>



<script>
    $(document).ready(function() {
        agregar_asterisco_campo_obligatorio('txt_primer_apellido');
        agregar_asterisco_campo_obligatorio('txt_segundo_apellido');
        agregar_asterisco_campo_obligatorio('txt_primer_nombre');
        agregar_asterisco_campo_obligatorio('txt_segundo_nombre');
        agregar_asterisco_campo_obligatorio('txt_cedula');
        agregar_asterisco_campo_obligatorio('ddl_sexo');
        agregar_asterisco_campo_obligatorio('txt_fecha_nacimiento');
        agregar_asterisco_campo_obligatorio('txt_edad');
        agregar_asterisco_campo_obligatorio('txt_telefono_1');
        agregar_asterisco_campo_obligatorio('txt_correo');
        agregar_asterisco_campo_obligatorio('ddl_provincias');
        agregar_asterisco_campo_obligatorio('ddl_ciudad');
        //agregar_asterisco_campo_obligatorio('ddl_parroquia');
        agregar_asterisco_campo_obligatorio('txt_codigo_postal');
        //agregar_asterisco_campo_obligatorio('txt_direccion');
    });
</script>