<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);


$_id = '';

if (isset($_GET['id'])) {
    $_id = $_GET['id'];
}

?>
<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>


<script type="text/javascript">
    $(document).ready(function() {
        <?php if (isset($_GET['id'])) { ?>
            cargarDatos(<?= $_id ?>);
        <?php } ?>

    })

    function cargarDatos(id) {
        $.ajax({
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_postulantesC.php?listar=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#txt_primer_nombre').val(response[0].th_pos_primer_nombre);
                $('#txt_segundo_nombre').val(response[0].th_pos_segundo_nombre);
                $('#txt_primer_apellido').val(response[0].th_pos_primer_apellido);
                $('#txt_segundo_apellido').val(response[0].th_pos_segundo_apellido);
                $('#txt_fecha_nacimiento').val(response[0].th_pos_fecha_nacimiento);
                $('#ddl_nacionalidad').val(response[0].th_pos_nacionalidad);
                $('#txt_numero_cedula').val(response[0].th_pos_cedula);
                $('#ddl_estado_civil').val(response[0].th_pos_estado_civil);
                $('#ddl_sexo').val(response[0].th_pos_sexo);
                $('#txt_telefono_1').val(response[0].th_pos_telefono_1);
                $('#txt_telefono_2').val(response[0].th_pos_telefono_2);
                $('#txt_correo').val(response[0].th_pos_correo);
                $('#ddl_provincia').val(response[0].th_prov_id);
                $('#ddl_ciudad').val(response[0].th_ciu_id);
                $('#ddl_parroquia').val(response[0].th_parr_id);
                $('#txt_codigo_postal').val(response[0].th_pos_postal);
                $('#txt_direccion').val(response[0].th_pos_direccion);

                calcular_edad('txt_edad', response[0].th_pos_fecha_nacimiento);
                console.log(response);
            },
        });
    }

    function insertar_editar() {

        var txt_primer_nombre = $('#txt_primer_nombre').val();
        var txt_segundo_nombre = $('#txt_segundo_nombre').val();
        var txt_primer_apellido = $('#txt_primer_apellido').val();
        var txt_segundo_apellido = $('#txt_segundo_apellido').val();
        var txt_fecha_nacimiento = $('#txt_fecha_nacimiento').val();
        var ddl_nacionalidad = $('#ddl_nacionalidad').val();
        var txt_numero_cedula = $('#txt_numero_cedula').val();
        var ddl_estado_civil = $('#ddl_estado_civil').val();
        var ddl_sexo = $('#ddl_sexo').val();
        var txt_telefono_1 = $('#txt_telefono_1').val();
        var txt_telefono_2 = $('#txt_telefono_2').val();
        var txt_correo = $('#txt_correo').val();
        var ddl_provincia = $('#ddl_provincia').val();
        var ddl_ciudad = $('#ddl_ciudad').val();
        var ddl_parroquia = $('#ddl_parroquia').val();
        var txt_codigo_postal = $('#txt_codigo_postal').val();
        var txt_direccion = $('#txt_direccion').val();

        var parametros = {
            '_id': '<?= $_id ?>',
            'txt_primer_nombre': txt_primer_nombre,
            'txt_segundo_nombre': txt_segundo_nombre,
            'txt_primer_apellido': txt_primer_apellido,
            'txt_segundo_apellido': txt_segundo_apellido,
            'txt_fecha_nacimiento': txt_fecha_nacimiento,
            'ddl_nacionalidad': ddl_nacionalidad,
            'txt_numero_cedula': txt_numero_cedula,
            'ddl_estado_civil': ddl_estado_civil,
            'ddl_sexo': ddl_sexo,
            'txt_telefono_1': txt_telefono_1,
            'txt_telefono_2': txt_telefono_2,
            'txt_correo': txt_correo,
            'ddl_provincia': ddl_provincia,
            'ddl_ciudad': ddl_ciudad,
            'ddl_parroquia': ddl_parroquia,
            'txt_codigo_postal': txt_codigo_postal,
            'txt_direccion': txt_direccion,

        };

        if ($("#registrar_postulantes").valid()) {
            // Si es válido, puedes proceder a enviar los datos por AJAX
            console.log(parametros);
            insertar(parametros);
        }


    }

    function insertar(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_postulantesC.php?insertar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=postulantes';


                    });
                } else if (response == -2) {
                    Swal.fire('', 'Operación fallida', 'warning');
                }
            }
        });
    }


    function delete_datos() {
        var id = '<?php echo $_id; ?>';
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
            url: '../controlador/PASANTES/02_ADRIAN/POSTULANTES/th_postulantesC.php?eliminar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response == 1) {
                    Swal.fire('Eliminado!', 'Registro Eliminado.', 'success').then(function() {
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=postulantes';
                    });
                }
            }
        });
    }
    
    function verificar_fecha_actual(input_name, fecha_actual, input_adicional) {
        let hoy = new Date().toISOString().split('T')[0];

        if (fecha_actual > hoy) {
            $('#' + input_name).val('');
            $('#' + input_adicional).val('');
        } 
    }

</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Postulantes</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Registrar Postulantes</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="container-fluid">
            <div class="main-body">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-5">
                        <div class="card-title d-flex align-items-center">
                            <div><i class="bx bxs-id-card me-1 font-24 text-primary"></i>
                            </div>
                            <h5 class="mb-0 text-primary">
                                <?php
                                if ($_id == '') {
                                    echo 'Registrar Postulante';
                                } else {
                                    echo 'Modificar Postulante';
                                }
                                ?>
                            </h5>
                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=postulantes" class="btn btn-outline-dark btn-sm d-flex align-items-center"><i class="bx bx-arrow-back"></i> Regresar</a>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <form id="registrar_postulantes">
                            <div class="row mb-col pt-3">
                                <div class="col-3">
                                    <label for="txt_primer_apellido" class="form-label form-label-sm">Primer Apellido </label>
                                    <input type="text" class="form-control form-control-sm" name="txt_primer_apellido" id="txt_primer_apellido" placeholder="Escriba su apellido paterno" maxlength="50" required>
                                </div>
                                <div class="col-3">
                                    <label for="txt_segundo_apellido" class="form-label form-label-sm">Segundo Apellido </label>
                                    <input type="text" class="form-control form-control-sm" name="txt_segundo_apellido" id="txt_segundo_apellido" placeholder="Escriba su apellido materno" maxlength="50" required>
                                </div>
                                <div class="col-3">
                                    <label for="txt_primer_nombre" class="form-label form-label-sm">Primer Nombre </label>
                                    <input type="text" class="form-control form-control-sm" name="txt_primer_nombre" id="txt_primer_nombre" placeholder="Escriba su primer nombre" maxlength="50" required>
                                </div>
                                <div class="col-3">
                                    <label for="txt_segundo_nombre" class="form-label form-label-sm">Segundo Nombre </label>
                                    <input type="text" class="form-control form-control-sm" name="txt_segundo_nombre" id="txt_segundo_nombre" placeholder="Escriba su primer nombre" maxlength="50" required>
                                </div>
                            </div>

                            <div class="row mb-col">
                                <div class="col-3">
                                    <label for="txt_numero_cedula" class="form-label form-label-sm">Cédula de Identidad </label>
                                    <input type="text" class="form-control form-control-sm solo_numeros_int" name="txt_numero_cedula" id="txt_numero_cedula" placeholder="Digite su número de cédula" maxlength="10" required>
                                </div>
                                <div class="col-3">
                                    <label for="ddl_sexo" class="form-label form-label-sm">Sexo </label>
                                    <select class="form-select form-select-sm" id="ddl_sexo" name="ddl_sexo" required>
                                        <option selected disabled value="">-- Selecciona una opción --</option>
                                        <option value="Masculino">Masculino</option>
                                        <option value="Femenino">Femenino</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="txt_fecha_nacimiento" class="form-label form-label-sm">Fecha de nacimiento </label>
                                    <input type="date" class="form-control form-control-sm" name="txt_fecha_nacimiento" id="txt_fecha_nacimiento" onblur="calcular_edad('txt_edad', this.value); verificar_fecha_actual('txt_fecha_nacimiento', this.value, 'txt_edad');" required>
                                </div>
                                <div class="col-3">
                                    <label for="txt_edad" class="form-label form-label-sm">Edad </label>
                                    <input type="text" class="form-control form-control-sm" name="txt_edad" id="txt_edad" readonly>
                                </div>
                            </div>

                            <div class="row mb-col">
                                <div class="col-4">
                                    <label for="txt_telefono_1" class="form-label form-label-sm">Teléfono 1 </label>
                                    <input type="text" class="form-control form-control-sm solo_numeros_int" name="txt_telefono_1" id="txt_telefono_1" value="" placeholder="Escriba su teléfono personal o fijo" maxlength="12" required>
                                </div>
                                <div class="col-4">
                                    <label for="txt_telefono_2" class="form-label form-label-sm">Teléfono 2 </label>
                                    <input type="text" class="form-control form-control-sm solo_numeros_int" name="txt_telefono_2" id="txt_telefono_2" value="" placeholder="Escriba su teléfono personal o fijo (opcional)" maxlength="12">
                                </div>
                                <div class="col-4">
                                    <label for="txt_correo" class="form-label form-label-sm">Correo Electrónico </label>
                                    <input type="email" class="form-control form-control-sm" name="txt_correo" id="txt_correo" value="" placeholder="Escriba su correo electrónico">
                                </div>
                            </div>


                            <?php include('../vista/PASANTES/02_ADRIAN/POSTULANTES/provincias_ciudades_parroquias.php'); ?>


                            <div class="row mb-col">
                                <div class="col-12">
                                    <label for="txt_direccion" class="form-label form-label-sm">Dirección </label>
                                    <input type="text" class="form-control form-control-sm" name="txt_direccion" id="txt_direccion" placeholder="Escriba su dirección">
                                </div>
                            </div>
                            <div class="d-flex justify-content-end pt-2">
                                <?php if ($_id == '') { ?>
                                    <button class="btn btn-primary btn-sm px-4 m-0 d-flex align-items-center" onclick="insertar_editar();" type="button"><i class="bx bx-save"></i> Guardar</button>
                                <?php } else { ?>
                                    <button class="btn btn-primary btn-sm px-4 m-1 d-flex align-items-center" onclick="insertar_editar();" type="button"><i class="bx bx-save"></i> Guardar</button>
                                    <button class="btn btn-danger btn-sm px-4 m-1 d-flex align-items-center" onclick="delete_datos()" type="button"><i class="bx bx-trash"></i> Eliminar</button>
                                <?php } ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        agregar_asterisco_campo_obligatorio('txt_primer_apellido');
        agregar_asterisco_campo_obligatorio('txt_segundo_apellido');
        agregar_asterisco_campo_obligatorio('txt_primer_nombre');
        agregar_asterisco_campo_obligatorio('txt_segundo_nombre');
        agregar_asterisco_campo_obligatorio('txt_numero_cedula');
        agregar_asterisco_campo_obligatorio('ddl_sexo');
        agregar_asterisco_campo_obligatorio('txt_fecha_nacimiento');
        agregar_asterisco_campo_obligatorio('txt_edad');
        agregar_asterisco_campo_obligatorio('txt_telefono_1');
        // agregar_asterisco_campo_obligatorio('txt_telefono_2');
        // agregar_asterisco_campo_obligatorio('txt_correo'); 
        // agregar_asterisco_campo_obligatorio('ddl_provincias');
        // agregar_asterisco_campo_obligatorio('ddl_ciudad');
        // agregar_asterisco_campo_obligatorio('ddl_parroquia');
        // agregar_asterisco_campo_obligatorio('txt_codigo_postal');
        // agregar_asterisco_campo_obligatorio('txt_direccion');

        //* Validacion de formulario
        $("#registrar_postulantes").validate({
            rules: {
                txt_primer_apellido: {
                    required: true,
                },
                txt_segundo_apellido: {
                    required: true,
                },
                txt_primer_nombre: {
                    required: true,
                },
                txt_segundo_nombre: {
                    required: true,
                },
                txt_numero_cedula: {
                    required: true,
                },
                ddl_sexo: {
                    required: true,
                },
                txt_fecha_nacimiento: {
                    required: true,
                },
                txt_edad: {
                    required: true,
                },
                txt_telefono_1: {
                    required: true,
                },
            },
            messages: {
                txt_primer_apellido: {
                    required: "Por favor ingrese el primer apellido",
                },
                txt_segundo_apellido: {
                    required: "Por favor ingrese el segundo apellido",
                },
                txt_primer_nombre: {
                    required: "Por favor ingrese el primer nombre",
                },
                txt_segundo_nombre: {
                    required: "Por favor ingrese el segundo nombre",
                },
                txt_numero_cedula: {
                    required: "Por favor ingresa un número de cédula",
                },
                ddl_sexo: {
                    required: "Por favor seleccione el sexo",
                },
                txt_fecha_nacimiento: {
                    required: "Por favor ingrese la fecha de nacimiento",
                },
                txt_edad: {
                    required: "Por favor ingrese la edad (fecha de nacimiento)",
                },
                txt_telefono_1: {
                    required: "Por favor ingrese el primero teléfono",
                },
            },
            highlight: function(element) {
                // Agrega la clase 'is-invalid' al input que falla la validación
                $(element).addClass('is-invalid');
                $(element).removeClass('is-valid');
            },
            unhighlight: function(element) {
                // Elimina la clase 'is-invalid' si la validación pasa
                $(element).removeClass('is-invalid');
                $(element).addClass('is-valid');
            }
        });
    });
</script>