<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);

$_id = '';

if (isset($_GET['_id_per'])) {
    $_id = $_GET['_id_per'];
     $_id_postulante = $_GET['_id'];
}
?>
<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        dispositivos();
        // cargar_tabla();
        <?php if (isset($_GET['_id_per'])) { ?>
            cargar_datos_persona(<?= $_id ?>);
            cargar_departamento(<?= $_id ?>);
        <?php } ?>
        cargar_selects2();

    });

     function cargar_datos_persona(id) {
        $.ajax({
            url: '../controlador/GENERAL/th_personasC.php?listar=true',
            type: 'post',
            data: { id: id },
            dataType: 'json',
            success: function(response) {
                if (!response || response.length === 0) {
                    console.error('No se encontraron datos para la persona');
                    return;
                }

                let persona = response[0];
                
                // Cargar datos personales
                $('#txt_primer_nombre').val(persona.primer_nombre);
                $('#txt_segundo_nombre').val(persona.segundo_nombre);
                $('#txt_primer_apellido').val(persona.primer_apellido);
                $('#txt_segundo_apellido').val(persona.segundo_apellido);
                $('#txt_fecha_nacimiento').val(persona.fecha_nacimiento);
                $('#ddl_nacionalidad').val(persona.nacionalidad);
                $('#txt_cedula').val(persona.cedula);
                $('#ddl_estado_civil').val(persona.estado_civil);
                $('#ddl_sexo').val(persona.sexo);
                $('#txt_telefono_1').val(persona.telefono_1);
                $('#txt_telefono_2').val(persona.telefono_2);
                $('#txt_correo').val(persona.correo);
                $('#txt_codigo_postal').val(persona.postal);
                $('#txt_direccion').val(persona.direccion);
                $('#txt_observaciones').val(persona.observaciones);
                $('#ddl_tipo_sangre').val(persona.tipo_sangre);

                calcular_edad('txt_edad', persona.fecha_nacimiento);

                // Cargar ubicación
                if (persona.id_provincia != null) {
                    cargar_select2_con_id('ddl_provincias', 
                        '../controlador/GENERAL/th_provinciasC.php?listar=true', 
                        persona.id_provincia, 'th_prov_nombre');
                    cargar_select2_con_id('ddl_ciudad', 
                        '../controlador/GENERAL/th_ciudadC.php?listar=true', 
                        persona.id_ciudad, 'th_ciu_nombre');
                    cargar_select2_con_id('ddl_parroquia', 
                        '../controlador/GENERAL/th_parroquiasC.php?listar=true', 
                        persona.id_parroquia, 'th_parr_nombre');
                }

                // Datos de visualización
                let nombres_completos = `${persona.primer_apellido} ${persona.segundo_apellido} ${persona.primer_nombre} ${persona.segundo_nombre}`;
                $('#txt_nombres_completos_v').html(nombres_completos);
                $('#txt_fecha_nacimiento_v').html(persona.fecha_nacimiento);
                $('#txt_nacionalidad_v').html(persona.nacionalidad);
                $('#txt_estado_civil_v').html(persona.estado_civil);
                $('#txt_numero_cedula_v').html(persona.cedula);
                $('#txt_telefono_1_v').html(persona.telefono_1);
                $('#txt_correo_v').html(persona.correo);

                // Inputs para modales (mantener compatibilidad)
                $('input[name="txt_postulante_id"]').val(<?=$_id_postulante?>);
                $('input[name="txt_postulante_cedula"]').val(persona.cedula);

                // Guardar id_comunidad si existe (para relación postulante)
                if (persona.id_comunidad) {
                    console.log('ID Comunidad (Postulante asociado):', persona.id_comunidad);
                    // Puedes guardar esto en un campo hidden o variable global
                    window.idPostulanteAsociado = persona.id_comunidad;
                }
            },
            error: function(xhr, status, error) {
                console.error('Error cargando datos de persona:', error);
                Swal.fire('Error', 'No se pudieron cargar los datos de la persona', 'error');
            }
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
            'ddl_tipo_sangre': $('#ddl_tipo_sangre').val(),
        };
    }

     function insertar_editar_persona() {
        let parametros = {
            '_id': '<?= $_id ?>',
        };

        let parametros_vista_persona = parametros_persona();
        parametros = {
            ...parametros,
            ...parametros_vista_persona
        };

        if ($("#registrar_personas").valid()) {
            // Si es válido, puedes proceder a enviar los datos por AJAX
            insertar(parametros);
        }
    }

    function insertar(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/GENERAL/th_personasC.php?insertar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                    });
                } else if (response == -2) {
                    //Swal.fire('', 'Operación fallida', 'warning');
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
                    });
                }
            }
        });
    }



    function dispositivos() {
        $.ajax({
            // data: {
            //     id: id
            // },
            url: '../controlador/TALENTO_HUMANO/th_dispositivosC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                console.log(response);
                op = '';
                response.forEach(function(item, i) {
                    op += '<option value="' + item._id + '">' + item.nombre + '</option>';
                })
                $('#ddl_dispositivos').html(op);

            },
            error: function(xhr, status, error) {
                console.log('Status: ' + status);
                console.log('Error: ' + error);
                console.log('XHR Response: ' + xhr.responseText);

                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });
    }

    function cargar_departamento(id) {
        $.ajax({
            data: {
                id: id
            },
            url: '../controlador/TALENTO_HUMANO/th_personasC.php?listar_persona_departamento=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response && response.length > 0) {
                    // Cargar el _id_perdep en el campo oculto para edición
                    $('#id_perdep').val(response[0]._id_perdep);

                    // Cargar el departamento seleccionado
                    $('#ddl_departamentos').append($('<option>', {
                        value: response[0].id_departamento,
                        text: response[0].nombre_departamento,
                        selected: true
                    }));

                    if (response[0].id_departamento == 0) {
                        cargar_persona_horario(response[0].id_persona);
                        $('#pnl_horarios_persona').hide();
                    } else {
                        cargar_persona_horario(response[0].id_persona);
                        $('#pnl_horarios_persona').show();
                    }
                }
            },
            error: function(xhr, status, error) { 
                console.error('Error al cargar departamento:', error);
            }
        });
    }

  

    


    function cargar_turnos_horario(id_horario) {

        $.ajax({
            url: '../controlador/TALENTO_HUMANO/th_turnos_horarioC.php?listar=true',
            type: 'post',
            data: {
                id: id_horario,
            },
            dataType: 'json',

            success: function(response) {

                calendar.removeAllEvents();
                // Recorrer la respuesta y agregar eventos al arreglo events
                response.forEach(function(evento) {
                    //console.log(evento);

                    if (evento.dia == '1') {
                        fecha_dia_estatico = '2024-02-11';
                    } else if (evento.dia == '2') {
                        fecha_dia_estatico = '2024-02-12';
                    } else if (evento.dia == '3') {
                        fecha_dia_estatico = '2024-02-13';
                    } else if (evento.dia == '4') {
                        fecha_dia_estatico = '2024-02-14';
                    } else if (evento.dia == '5') {
                        fecha_dia_estatico = '2024-02-15';
                    } else if (evento.dia == '6') {
                        fecha_dia_estatico = '2024-02-16';
                    } else if (evento.dia == '7') {
                        fecha_dia_estatico = '2024-02-17';
                    }

                    calendar.addEvent({
                        //id: evento.id_turno,
                        title: (evento.nombre),
                        start: fecha_dia_estatico + 'T' + minutos_formato_hora(evento.hora_entrada),
                        end: fecha_dia_estatico + 'T' + minutos_formato_hora(evento.hora_salida),
                        extendedProps: {
                            id_turno_horario: evento._id,
                            id_turno: evento.id_turno,
                        },

                        color: evento.color

                    });
                });
                // Renderizar el calendario después de agregar los eventos
                calendar.render();

            }
        });

    }
     function cargar_selects2() {

        url_departamentosC = '../controlador/TALENTO_HUMANO/th_departamentosC.php?buscar=true';
        cargar_select2_url('ddl_departamentos', url_departamentosC);

    }


    function recargar_imagen(id) {
        $.ajax({
            url: '../controlador/GENERAL/th_personasC.php?listar=true',
            type: 'post',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                $('#img_persona_inf').attr('src', response[0].th_per_foto_url + '?' + Math.random());
            }
        });
    }

    function insertar_editar_informacion_personal() {

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
        var ddl_provincias = $('#ddl_provincias').val();
        var ddl_ciudad = $('#ddl_ciudad').val();
        var ddl_parroquia = $('#ddl_parroquia').val();
        var txt_direccion_postal = $('#txt_direccion_postal').val();
        var txt_direccion = $('#txt_direccion').val();

        var parametros_informacion_personal = {
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
            'ddl_provincias': ddl_provincias,
            'ddl_ciudad': ddl_ciudad,
            'ddl_parroquia': ddl_parroquia,
            'txt_direccion_postal': txt_direccion_postal,
            'txt_direccion': txt_direccion,

        };

        if ($("#form_informacion_personal").valid()) {
            insertar_informacion_personal(parametros_informacion_personal);
        }
    }

    function insertar_informacion_personal(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/GENERAL/th_personasC.php?insertar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {

                    });
                    <?php if (isset($_GET['_id'])) { ?>
                        cargarDatos_informacion_personal(<?= $_id ?>);
                    <?php } ?>
                    $('#modal_informacion_personal').modal('hide');
                } else if (response == -2) {
                    Swal.fire('', 'Operación fallida', 'warning');
                }
            }
        });
    }

    function insertar_persona_departamento() {
        var deptId = $('#ddl_departamentos').val();
        var perdepId = $('#id_perdep').val();

        if (!deptId) {
            Swal.fire('', 'Seleccione un departamento', 'warning');
            return;
        }

        var parametros = {
            '_id': perdepId || '',
            'id_persona': '<?= $_id ?>',
            'id_departamento': deptId,
            'txt_visitor': $('#txt_visitor').val() || ''
        };

        $.ajax({
            url: '../controlador/TALENTO_HUMANO/th_personas_departamentosC.php?insertar_editar_persona=true',
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
                    Swal.fire('', 'Esta persona ya está asignada a este departamento', 'warning');
                } else {
                    Swal.fire('', 'Error en la operación', 'error');
                }
            }
        });
    }
</script>

<!-- Vista de la página -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Persona</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Información personal</li>
                    </ol>
                </nav>
            </div>
            <div class="row m-2">
                <div class="col-sm-12">
                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_personas" class="btn btn-outline-dark btn-sm d-flex align-items-center"><i class="bx bx-arrow-back"></i> Personas</a>
                </div>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="container-fluid">
            <div class="main-body">
                <div class="row d-flex justify-content-center">
                    <!-- Cards de la izquierda -->
                    <div class="col-xs-5 col-sm-5 col-md-5 col-lg-4 col-xxl-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="align-items-center">
                                    <!-- Cambiar Foto -->
                                    <div class="text-center">
                                       <!-- <?php include_once('../vista/GENERAL/per_cambiar_foto.php'); ?> -->

                                        <div class="position-relative">

                                            <div class="widget-user-image text-center">
                                                <img class="rounded-circle p-1 bg-primary" src="../img/sin_imagen.jpg" class="img-fluid" id="img_persona_inf" alt="Imagen Perfil Persona" width="110" height="110" />
                                            </div>

                                            <div>
                                                <a href="#" class="d-flex justify-content-center" data-bs-toggle="modal" data-bs-target="#modal_agregar_cambiar_foto" onclick="abrir_modal_cambiar_foto('<?= $_id ?>');">
                                                    <i class='bx bxs-camera bx-sm'></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Información Personal -->
                                    <div class="mt-3 bg-light rounded-3 p-3 shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-3">
         <div class="d-flex align-items-center">
        <i class="bx bx-info-circle fs-5 text-primary me-2"></i>
        <h6 class="fw-bold mb-0 text-primary">Información Personal</h6>
      </div>
        <a href="#" class="text-secondary" data-bs-toggle="modal" data-bs-target="#modal_informacion_personal">
            <i class="bx bx-pencil bx-sm"></i>
        </a>
    </div>

    <div class="d-flex flex-column gap-3">

        <div class="d-flex align-items-center border-bottom pb-2">
            <i class="bx bx-id-card text-primary fs-5 me-3"></i>
            <span id="txt_nombres_completos_v" class="fw-semibold text-dark"></span>
        </div>

        <div class="d-flex align-items-center border-bottom pb-2">
            <i class="bx bx-calendar text-primary fs-5 me-3"></i>
            <span id="txt_fecha_nacimiento_v" class="text-dark"></span>
        </div>

        <div class="d-flex align-items-center border-bottom pb-2">
            <i class="bx bx-flag text-primary fs-5 me-3"></i>
            <span id="txt_nacionalidad_v" class="text-dark"></span>
        </div>

        <div class="d-flex align-items-center border-bottom pb-2">
            <i class="bx bx-id-card text-primary fs-5 me-3"></i>
            <span id="txt_numero_cedula_v" class="text-dark"></span>
        </div>

        <div class="d-flex align-items-center border-bottom pb-2">
            <i class="bx bx-heart text-primary fs-5 me-3"></i>
            <span id="txt_estado_civil_v" class="text-dark"></span>
        </div>

        <div class="d-flex align-items-center border-bottom pb-2">
            <i class="bx bx-phone text-primary fs-5 me-3"></i>
            <span id="txt_telefono_1_v" class="text-dark"></span>
        </div>

        <div class="d-flex align-items-center">
            <i class="bx bx-envelope text-primary fs-5 me-3"></i>
            <span id="txt_correo_v" class="text-dark text-break"></span>
        </div>
    </div>
</div>

                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <!-- Contacto de Emergencia -->
                            <div class="card-body">
                                <div class="align-items-center">
                                    <div class="mt-3">
                                        <div class="row">
                                            <div class="col-10">
                                                <h5 class="fw-bold text-primary">Contacto de Emergencia</h5>
                                            </div>
                                            <div class="col-2">
                                                <a href="#" class="text-dark icon-hover" data-bs-toggle="modal" data-bs-target="#modal_contacto_emergencia">
                                                    <i class='bx bx-show bx-sm'></i></a>
                                            </div>
                                        </div>

                                         <!--  <?php include_once('../vista/GENERAL/per_contacto_emergencia.php'); ?>-->

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cards de la derecha -->
                    <div class="col-xs-7 col-sm-7 col-md-7 col-lg-8 col-xxl-8">
                        <div class="card-body">
                            <!-- Nav Cards -->
                             <ul class="nav nav-tabs nav-success" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#tab_experiencia" role="tab" aria-selected="true">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bxs-briefcase font-18 me-1"></i>
                                            </div>
                                            <div class="tab-title">Experiencia</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#successdocs" role="tab" aria-selected="true">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bxs-file-doc font-18 me-1"></i>
                                            </div>
                                            <div class="tab-title">Documentos</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#successprofile" role="tab" aria-selected="false" tabindex="-1">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bx-brain font-18 me-1"></i>
                                            </div>
                                            <div class="tab-title">Habilidades</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#successcontact" role="tab" aria-selected="false" tabindex="-1">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bxs-user-check font-18 me-1"></i>
                                            </div>
                                            <div class="tab-title">Estado del Empleado</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#tab_departamento" role="tab" aria-selected="false" tabindex="-1">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx bxs-school font-18 me-1"></i>
                                            </div>
                                            <div class="tab-title">Departamento</div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                           
                            <div class="tab-content pt-3">
                                <!-- Primera Sección, Historial Laboral -->
                                <div class="tab-pane fade show active" id="tab_experiencia" role="tabpanel">
                                    <div class="card">
                                        <div class="d-flex flex-column mx-4">
                                            <!-- Experiencia Previa -->
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-9 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Experiencia Previa:</h6>
                                                        </div>

                                                        <div class="col-3 d-flex justify-content-end">
                                                            <a href="#" class="text-success icon-hover d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modal_agregar_experiencia">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span>Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>

                                                <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_experiencia_previa.php'); ?>

                                            </div>
                                            <!-- Formación Académica -->
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-9 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Formación Académica:</h6>
                                                        </div>
                                                        <div class="col-3 d-flex justify-content-end">
                                                            <a href="#" class="text-success icon-hover d-flex align-items-center" id="btn_modal_agregar_formacion_academica" data-bs-toggle="modal" data-bs-target="#modal_agregar_formacion">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span>Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>

                                                <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_formacion_academica.php'); ?>

                                            </div>
                                            <!-- Certificaciones y capacitación -->
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-9 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Certificación y/o Capacitación:</h6>
                                                        </div>
                                                        <div class="col-3 d-flex justify-content-end">
                                                            <a href="#" class="text-success icon-hover d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modal_agregar_certificaciones">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span>Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>

                                                <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_certificaciones_capacitaciones.php'); ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Segunda Sección, Documentos relevantes -->
                                <div class="tab-pane fade" id="successdocs" role="tabpanel">
                                    <div class="card">
                                        <div class="d-flex flex-column mx-4">
                                            <!-- Documento de Identidad -->
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-7 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Documento de Identidad:</h6>
                                                        </div>
                                                        <div class="col-5 d-flex justify-content-end align-items-center">
                                                            <a href="#" class="text-success icon-hover d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modal_agregar_documentos_identidad">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span class="">Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="my-0 mb-3">

                                                <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_documento_identidad.php'); ?>

                                            </div>
                                            <!-- Contratos de Trabajo -->
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-7 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Contratos de Trabajo:</h6>
                                                        </div>
                                                        <div class="col-5 d-flex justify-content-end align-items-center">
                                                            <a href="#" class="text-success icon-hover d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modal_agregar_contratos">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span class="">Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="my-0 mb-3">

                                                <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_contratos_trabajo.php'); ?>

                                            </div>
                                            <!-- Certificado Médicos -->
                                            <div class="card-body my-0">
                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-7 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Certificados Médicos:</h6>
                                                        </div>
                                                        <div class="col-5 d-flex justify-content-end align-items-center">
                                                            <a href="#" class="text-success icon-hover d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modal_agregar_certificados_medicos">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span class="">Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="my-0 mb-3">

                                                <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_certificado_medico.php'); ?>

                                            </div>
                                            <!-- Referencias Laborales -->
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-7 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Referencias laborales:</h6>
                                                        </div>
                                                        <div class="col-5 d-flex justify-content-end align-items-center">
                                                            <a href="#" class="text-success icon-hover d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modal_agregar_referencia_laboral">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span class="">Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="my-0 mb-3">

                                                <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_referencias_laborales.php'); ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Tercera Sección, Idiomas y aptitudes -->
                                <div class="tab-pane fade" id="successprofile" role="tabpanel">
                                    <div class="card">
                                        <div class="d-flex flex-column mx-4">
                                            <!-- Idiomas -->
                                            <div class="card-body">
                                                <div class="mb-1">
                                                    <div class="row">
                                                        <div class="col-6 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Idiomas</h6>
                                                        </div>
                                                        <div class="col-6 d-flex justify-content-end">
                                                            <a href="#" class="text-success icon-hover d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modal_agregar_idioma">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span>Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="my-0">

                                                <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_idiomas.php'); ?>

                                            </div>
                                            <!-- Aptitudes -->
                                            <div class="card-body">
                                                <div class="mb-1">
                                                    <div class="row">
                                                        <div class="col-6 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Aptitudes</h6>
                                                        </div>
                                                        <div class="col-6 d-flex justify-content-end">
                                                            <a href="#" class="text-success icon-hover d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modal_agregar_aptitudes" onclick="activar_select2();">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span>Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="my-0">

                                                <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_aptitudes.php'); ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Cuarta Sección, Estado del Empleado -->
                                <div class="tab-pane fade" id="successcontact" role="tabpanel">
                                    <div class="card">
                                        <div class="d-flex flex-column mx-4">
                                            <div class="card-body">
                                                <div class="mb-2">
                                                    <div class="row">
                                                        <div class="col-6 d-flex align-items-center">
                                                            <h6 class="mb-0 fw-bold text-primary">Estado laboral:</h6>
                                                        </div>
                                                        <div class="col-6 d-flex justify-content-end">
                                                            <a href="#" class="text-success icon-hover d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#modal_estado_laboral">
                                                                <i class='bx bx-plus-circle bx-sm me-1'></i>
                                                                <span>Agregar</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>

                                                <?php include_once('../vista/TALENTO_HUMANO/POSTULANTES/pos_estado_laboral.php'); ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Cuarta Sección, Departamento -->
                                <div class="tab-pane fade" id="tab_departamento" role="tabpanel">
                                    <div class="card">
                                        <div class="d-flex flex-column mx-4">
                                            <div class="card-body">
                                                <?php include_once('../vista/TALENTO_HUMANO/PERSONAS/th_persona_departamento_horario.php'); ?>
                                                <div class="d-flex justify-content-end pt-2">
                                                    <button class="btn btn-primary btn-sm px-4 m-1 d-flex align-items-center" onclick="insertar_persona_departamento();" type="button"><i class="bx bx-save"></i> Guardar</button>
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
    </div>
</div>

<!-- Modal para la informacion personal -->
<div class="modal modal_general" id="modal_informacion_personal" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5><small class="text-body-secondary fw-bold">Información Personal</small></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <form id="registrar_personas">
                <div class="modal-body">
                    
                    <div class="row">
                        <!-- Columna Izquierda: Foto -->
                        <div class="col-md-3">
                            <div class="text-center border-end pe-3" style="min-height: 100%;">
                                <label class="form-label form-label-sm fw-bold d-block mb-3">Fotografía</label>
                                <div class="foto-perfil-container d-inline-block position-relative mb-2">
                                    <img id="preview_foto" class="foto-perfil" src="" alt="Foto de perfil" style="display: none; width: 130px; height: 130px; border-radius: 50%; object-fit: cover; border: 4px solid #0d6efd; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                                    <div id="foto-placeholder" class="d-flex align-items-center justify-content-center" style="width: 130px; height: 130px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: 4px solid #0d6efd; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                                        <i class="fas fa-user" style="font-size: 50px; color: white;"></i>
                                    </div>
                                    <label for="input_foto" class="btn-cambiar-foto position-absolute" style="bottom: 5px; right: 5px; width: 36px; height: 36px; border-radius: 50%; background-color: #0d6efd; border: 3px solid white; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-camera" style="color: white; font-size: 14px;"></i>
                                    </label>
                                    <input type="file" id="input_foto" name="input_foto" accept="image/*" style="display: none;">
                                </div>
                                <p class="text-muted mb-0" style="font-size: 10px; line-height: 1.3;">Click en el ícono de cámara para subir imagen</p>
                            </div>
                        </div>

                        <!-- Columna Derecha: Formulario -->
                        <div class="col-md-9">
                            <div class="row mb-2">
                                <div class="col-md-3">
                                    <label for="txt_primer_apellido" class="form-label form-label-sm">Primer Apellido</label>
                                    <input type="text" class="form-control form-control-sm no_caracteres" name="txt_primer_apellido" id="txt_primer_apellido" maxlength="50">
                                </div>
                                <div class="col-md-3">
                                    <label for="txt_segundo_apellido" class="form-label form-label-sm">Segundo Apellido</label>
                                    <input type="text" class="form-control form-control-sm no_caracteres" name="txt_segundo_apellido" id="txt_segundo_apellido" maxlength="50">
                                </div>
                                <div class="col-md-3">
                                    <label for="txt_primer_nombre" class="form-label form-label-sm">Primer Nombre</label>
                                    <input type="text" class="form-control form-control-sm no_caracteres" name="txt_primer_nombre" id="txt_primer_nombre" maxlength="50">
                                </div>
                                <div class="col-md-3">
                                    <label for="txt_segundo_nombre" class="form-label form-label-sm">Segundo Nombre</label>
                                    <input type="text" class="form-control form-control-sm no_caracteres" name="txt_segundo_nombre" id="txt_segundo_nombre" maxlength="50">
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-3">
                                    <label for="txt_cedula" class="form-label form-label-sm">N° de Cédula</label>
                                    <input type="text" class="form-control form-control-sm no_caracteres" name="txt_cedula" id="txt_cedula" maxlength="10">
                                </div>
                                <div class="col-md-3">
                                    <label for="ddl_sexo" class="form-label form-label-sm">Sexo</label>
                                    <select class="form-select form-select-sm" id="ddl_sexo" name="ddl_sexo">
                                        <option selected disabled value="">-- Selecciona una opción --</option>
                                        <option value="Masculino">Masculino</option>
                                        <option value="Femenino">Femenino</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="txt_fecha_nacimiento" class="form-label form-label-sm">Fecha de nacimiento</label>
                                    <input type="date" class="form-control form-control-sm" name="txt_fecha_nacimiento" id="txt_fecha_nacimiento">
                                </div>
                                <div class="col-md-3">
                                    <label for="txt_edad" class="form-label form-label-sm">Edad </label>
                                    <input type="text" class="form-control form-control-sm solo_numeros_int" name="txt_edad" id="txt_edad" readonly>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <label for="txt_telefono_1" class="form-label form-label-sm">Teléfono 1 </label>
                                    <input type="text" class="form-control form-control-sm solo_numeros_int" name="txt_telefono_1" id="txt_telefono_1" value="" maxlength="12" required>
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

                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <label for="ddl_nacionalidad" class="form-label form-label-sm">Nacionalidad</label>
                                    <select class="form-select form-select-sm" id="ddl_nacionalidad" name="ddl_nacionalidad">
                                        <option selected disabled value="">-- Selecciona una Nacionalidad --</option>
                                        <option value="Ecuatoriano">Ecuatoriano</option>
                                        <option value="Colombiano">Colombiano</option>
                                        <option value="Peruano">Peruano</option>
                                        <option value="Venezolano">Venezolano</option>
                                        <option value="Paraguayo">Paraguayo</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
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
                                <div class="col-md-4">
                                    <label for="ddl_tipo_sangre" class="form-label form-label-sm">Tipo Sangre </label>
                                    <select class="form-select form-select-sm" id="ddl_tipo_sangre" name="ddl_tipo_sangre" required>
                                        <option selected disabled value="">-- Selecciona una opción --</option>
                                        <option value="A+">A+</option>
                                        <option value="A-">A-</option>
                                        <option value="B+">B+</option>
                                        <option value="B-">B-</option>
                                        <option value="O+">O+</option>
                                        <option value="O-">O-</option>
                                        <option value="AB+">AB+</option>
                                        <option value="AB-">AB-</option>
                                    </select>
                                </div>
                            </div>

                            <?php include_once('../vista/TALENTO_HUMANO/PERSONAS/provincias_ciudades_parroquias.php'); ?>

                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <label for="txt_direccion" class="form-label form-label-sm">Dirección </label>
                                    <input type="text" class="form-control form-control-sm no_caracteres" name="txt_direccion" id="txt_direccion">
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <label for="txt_observaciones" class="form-label form-label-sm">Observaciones </label>
                                    <input type="text" class="form-control form-control-sm" name="txt_observaciones" id="txt_observaciones" maxlength="200">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer d-flex justify-content-center">
                    <button type="button" class="btn btn-success btn-sm" id="btn_guardar_informacion_personal" onclick="insertar_editar_persona();">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Script para preview de la foto
document.getElementById('input_foto').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview_foto').src = e.target.result;
            document.getElementById('preview_foto').style.display = 'block';
            document.getElementById('foto-placeholder').style.display = 'none';
        }
        reader.readAsDataURL(file);
    }
});
</script>


<script>
    //Validacion de formulario
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
        agregar_asterisco_campo_obligatorio('txt_telefono_2');
        agregar_asterisco_campo_obligatorio('txt_correo');
        agregar_asterisco_campo_obligatorio('ddl_nacionalidad');
        agregar_asterisco_campo_obligatorio('ddl_estado_civil');
        agregar_asterisco_campo_obligatorio('ddl_provincias');
        agregar_asterisco_campo_obligatorio('ddl_ciudad');
        agregar_asterisco_campo_obligatorio('ddl_parroquia');
        agregar_asterisco_campo_obligatorio('txt_codigo_postal');
        agregar_asterisco_campo_obligatorio('txt_direccion');
        
        //Validación Información Personal
        $("#form_informacion_personal").validate({
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
                txt_telefono_2: {
                    required: true,
                },
                txt_correo: {
                    required: true,
                },
                ddl_nacionalidad: {
                    required: true,
                },
                ddl_estado_civil: {
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
                txt_telefono_2: {
                    required: "Por favor ingrese el segundo teléfono",
                },
                txt_correo: {
                    required: "Por favor ingrese un correo",
                },
                ddl_nacionalidad: {
                    required: "Por favor seleccione su nacionalidad",
                },
                ddl_estado_civil: {
                    required: "Por favor seleccione su estado civil",
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

    function convertir_postulante_a_persona(id_postulante) {
    Swal.fire({
        title: '¿Convertir a Persona?',
        text: "Se creará un registro de persona con todos los datos del postulante",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, convertir',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value) {
            procesar_conversion_postulante(id_postulante);
        }
    });
}

/**
 * Procesa la conversión del postulante a persona
 */
function procesar_conversion_postulante(id_postulante) {
    $.ajax({
        data: {
            id_postulante: id_postulante
        },
        url: '../controlador/GENERAL/th_personasC.php?convertir_postulante=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            if (response.status == 1) {
                Swal.fire({
                    title: 'Éxito',
                    text: 'El postulante ha sido convertido a persona exitosamente',
                    icon: 'success',
                    confirmButtonText: 'Ver Persona'
                }).then(() => {
                    // Redirigir a la vista de persona creada
                    window.location.href = '../vista/inicio.php?mod=' + <?= $modulo_sistema ?> + '&acc=th_persona_informacion&_id=' + response.id_persona;
                });
            } else if (response.status == -1) {
                Swal.fire('Error', 'Ya existe una persona con esta cédula', 'error');
            } else if (response.status == -2) {
                Swal.fire('Error', 'El postulante no existe', 'warning');
            } else {
                Swal.fire('Error', 'No se pudo realizar la conversión', 'error');
            }
        },
        error: function(xhr, status, error) {
            Swal.fire('Error', 'Error en el servidor: ' + error, 'error');
        }
    });
}

/**
 * Función para verificar si un postulante ya fue convertido a persona
 */
function verificar_postulante_convertido(id_postulante) {
    $.ajax({
        data: {
            id_postulante: id_postulante
        },
        url: '../controlador/GENERAL/th_personasC.php?verificar_conversion=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            if (response.convertido) {
                // Mostrar mensaje y botón para ir a la persona
                $('#btn_convertir_persona').hide();
                $('#lbl_ya_convertido').show();
                $('#btn_ir_persona').attr('onclick', 'ir_a_persona("' + response.id_persona + '")').show();
            } else {
                $('#btn_convertir_persona').show();
                $('#lbl_ya_convertido').hide();
                $('#btn_ir_persona').hide();
            }
        }
    });
}

/**
 * Función para ir a la vista de persona
 */
function ir_a_persona(id_persona) {
    window.location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_persona_informacion&_id=' + id_persona;
}

/**
 * Función para copiar datos específicos de postulante a persona existente
 * Útil cuando ya existe la persona pero queremos actualizar con datos del postulante
 */
function sincronizar_datos_postulante_persona(id_postulante, id_persona) {
    Swal.fire({
        title: '¿Sincronizar datos?',
        text: "Se actualizarán los datos de la persona con la información del postulante",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, sincronizar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                data: {
                    id_postulante: id_postulante,
                    id_persona: id_persona
                },
                url: '../controlador/GENERAL/th_personasC.php?sincronizar_postulante_persona=true',
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    if (response == 1) {
                        Swal.fire('Éxito', 'Datos sincronizados correctamente', 'success');
                        cargarDatos_informacion_personal(id_persona);
                    } else {
                        Swal.fire('Error', 'No se pudieron sincronizar los datos', 'error');
                    }
                }
            });
        }
    });
}

/**
 * Función para listar datos del postulante y compararlos con persona
 */
function ver_comparacion_postulante_persona(id_postulante, id_persona) {
    $.ajax({
        data: {
            id_postulante: id_postulante,
            id_persona: id_persona
        },
        url: '../controlador/GENERAL/th_personasC.php?comparar_datos=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            // Mostrar modal con comparación
            mostrar_modal_comparacion(response);
        }
    });
}

/**
 * Función para cargar experiencia previa del postulante a persona
 */
function copiar_experiencia_postulante(id_postulante, id_persona) {
    $.ajax({
        data: {
            id_postulante: id_postulante,
            id_persona: id_persona
        },
        url: '../controlador/GENERAL/th_personasC.php?copiar_experiencia=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            if (response == 1) {
                Swal.fire('Éxito', 'Experiencia copiada correctamente', 'success');
                // Recargar tabla de experiencia
                if (typeof tbl_experiencia !== 'undefined') {
                    tbl_experiencia.ajax.reload(null, false);
                }
            }
        }
    });
}

/**
 * Función para copiar formación académica del postulante a persona
 */
function copiar_formacion_postulante(id_postulante, id_persona) {
    $.ajax({
        data: {
            id_postulante: id_postulante,
            id_persona: id_persona
        },
        url: '../controlador/GENERAL/th_personasC.php?copiar_formacion=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            if (response == 1) {
                Swal.fire('Éxito', 'Formación académica copiada correctamente', 'success');
                // Recargar tabla de formación
                if (typeof tbl_formacion !== 'undefined') {
                    tbl_formacion.ajax.reload(null, false);
                }
            }
        }
    });
}

/**
 * Función para copiar certificaciones del postulante a persona
 */
function copiar_certificaciones_postulante(id_postulante, id_persona) {
    $.ajax({
        data: {
            id_postulante: id_postulante,
            id_persona: id_persona
        },
        url: '../controlador/GENERAL/th_personasC.php?copiar_certificaciones=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            if (response == 1) {
                Swal.fire('Éxito', 'Certificaciones copiadas correctamente', 'success');
                // Recargar tabla de certificaciones
                if (typeof tbl_certificaciones !== 'undefined') {
                    tbl_certificaciones.ajax.reload(null, false);
                }
            }
        }
    });
}

/**
 * Función para copiar documentos del postulante a persona
 */
function copiar_documentos_postulante(id_postulante, id_persona) {
    $.ajax({
        data: {
            id_postulante: id_postulante,
            id_persona: id_persona
        },
        url: '../controlador/GENERAL/th_personasC.php?copiar_documentos=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            if (response == 1) {
                Swal.fire('Éxito', 'Documentos copiados correctamente', 'success');
            }
        }
    });
}

/**
 * Función para copiar idiomas del postulante a persona
 */
function copiar_idiomas_postulante(id_postulante, id_persona) {
    $.ajax({
        data: {
            id_postulante: id_postulante,
            id_persona: id_persona
        },
        url: '../controlador/GENERAL/th_personasC.php?copiar_idiomas=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            if (response == 1) {
                Swal.fire('Éxito', 'Idiomas copiados correctamente', 'success');
                // Recargar tabla de idiomas
                if (typeof tbl_idiomas !== 'undefined') {
                    tbl_idiomas.ajax.reload(null, false);
                }
            }
        }
    });
}

/**
 * Función para copiar aptitudes del postulante a persona
 */
function copiar_aptitudes_postulante(id_postulante, id_persona) {
    $.ajax({
        data: {
            id_postulante: id_postulante,
            id_persona: id_persona
        },
        url: '../controlador/GENERAL/th_personasC.php?copiar_aptitudes=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            if (response == 1) {
                Swal.fire('Éxito', 'Aptitudes copiadas correctamente', 'success');
                // Recargar tabla de aptitudes
                if (typeof tbl_aptitudes !== 'undefined') {
                    tbl_aptitudes.ajax.reload(null, false);
                }
            }
        }
    });
}

/**
 * Función para copiar TODOS los datos del postulante (completo)
 */
function copiar_todos_datos_postulante(id_postulante, id_persona) {
    Swal.fire({
        title: '¿Copiar todos los datos?',
        text: "Se copiarán todos los registros del postulante a la persona",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, copiar todo',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value) {
            // Mostrar loading
            Swal.fire({
                title: 'Copiando datos...',
                text: 'Por favor espere',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                data: {
                    id_postulante: id_postulante,
                    id_persona: id_persona
                },
                url: '../controlador/GENERAL/th_personasC.php?copiar_todos_datos=true',
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    Swal.close();
                    if (response.status == 1) {
                        Swal.fire({
                            title: 'Éxito',
                            html: 'Datos copiados correctamente:<br>' +
                                  'Experiencias: ' + response.experiencias + '<br>' +
                                  'Formaciones: ' + response.formaciones + '<br>' +
                                  'Certificaciones: ' + response.certificaciones + '<br>' +
                                  'Documentos: ' + response.documentos + '<br>' +
                                  'Idiomas: ' + response.idiomas + '<br>' +
                                  'Aptitudes: ' + response.aptitudes,
                            icon: 'success'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', 'No se pudieron copiar todos los datos', 'error');
                    }
                },
                error: function() {
                    Swal.close();
                    Swal.fire('Error', 'Error al procesar la solicitud', 'error');
                }
            });
        }
    });
}
</script>


