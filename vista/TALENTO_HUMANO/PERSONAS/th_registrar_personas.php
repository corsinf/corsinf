<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);
$redireccionar_vista = 'th_personas';

$_id = '';

if (isset($_GET['_id'])) {
    $_id = $_GET['_id'];
}

?>
<script>
// Creamos la variable JS con el valor obtenido en PHP
const PERSON_ID = '<?= $_id ?>';
</script>


<style>
.custom-file-upload {
    display: inline-block;
    padding: 8px 15px;
    cursor: pointer;
    background-color: #007bff;
    color: white;
    border-radius: 5px;
    font-size: 14px;
    border: none;
    transition: background-color 0.3s ease;
}

.custom-file-upload:hover {
    background-color: #0056b3;
}

input[type="file"] {
    display: none;
}

#file-name {
    margin-left: 10px;
    font-style: italic;
}
</style>

<script>
var PersonaId = '<?php echo $_id; ?>'
</script>
<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>
<script src="../js/RECURSOS_HUMANOS/biometria.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    dispositivos();
    // cargar_tabla();
    <?php if (isset($_GET['_id'])) { ?>
    cargar_datos_persona(<?= $_id ?>);
    cargar_departamento(<?= $_id ?>);
    <?php } ?>
    cargar_selects2();

});

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

function cargar_persona_horario(id_persona) {
    $.ajax({
        data: {
            id: id_persona
        },
        url: '../controlador/TALENTO_HUMANO/th_programar_horariosC.php?listar_persona_horario=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            if (response && response.length > 0) {
                cargar_turnos_horario(response[0].id_horario);
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
                    start: fecha_dia_estatico + 'T' + minutos_formato_hora(evento
                        .hora_entrada),
                    end: fecha_dia_estatico + 'T' + minutos_formato_hora(evento
                        .hora_salida),
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

// ---- 1A: Insertar/Actualizar varias personas (reutiliza tu endpoint actual) ----
function insertar_persona_departamento() {
    var deptId = $('#ddl_departamentos').val();
    var perdepId = $('#id_perdep').val();

    if (!deptId) {
        Swal.fire('', 'Seleccione un departamento', 'warning');
        return;
    }

    var parametros = {
        '_id': perdepId || '', // th_perdep_id para edición
        'id_persona': PERSON_ID, // th_per_id
        'id_departamento': deptId, // th_dep_id
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
                    location.reload(); // O redirigir según necesites
                });
            } else if (response == -2) {
                Swal.fire('', 'Esta persona ya está asignada a este departamento', 'warning');
            } else {
                Swal.fire('', 'Error en la operación', 'error');
            }
        },
        error: function(xhr, status, error) {
            Swal.fire('', 'Error: ' + xhr.responseText, 'error');
        }
    });
}

function cargar_selects2() {

    url_departamentosC = '../controlador/TALENTO_HUMANO/th_departamentosC.php?buscar=true';
    cargar_select2_url('ddl_departamentos', url_departamentosC);

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
                    location.href =
                        '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=<?= $redireccionar_vista ?>';
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
</script>

<script>
/**
 * Script para manejar los dispositivos biométricos y la captura de huellas dactilares.
 */

function cambiar(finger) {
    $('.btn-outline-primary').removeClass('active');
    $('#img_palma').attr('src', '../img/de_sistema/palma' + finger + '.gif');
    $('#btn_finger_' + finger).addClass('active');
    $('#txt_dedo_num').val(finger);
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


function eliminarfinger(id) {
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
            eliminarFing(id);
        }
    })
}

function eliminarFing(id) {
    $.ajax({
        data: {
            id: id
        },
        url: '../controlador/TALENTO_HUMANO/th_personasC.php?eliminarFing=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            if (response == 1) {
                Swal.fire('Eliminado!', 'Registro Eliminado.', 'success').then(function() {
                    tbl_dispositivos.ajax.reload(null, false);
                });
            }
        }
    });
}


function cargar_tabla() {
    tbl_dispositivos = $('#tbl_bio_finger').DataTable($.extend({}, {
        reponsive: true,
        searching: false, // Desactiva el buscador
        paging: false, // Desactiva la paginación
        info: false, // Opcional: Desactiva la información (ej. "Mostrando 1 a 10 de 100 registros")

        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        ajax: {
            type: 'POST',
            url: '../controlador/TALENTO_HUMANO/th_personasC.php?registros_biometria=true',
            data: function(d) {

                var parametros = {
                    id: '<?php echo $_id; ?>', // Parámetro personalizado
                };
                return {
                    parametros: parametros
                };
            },
            dataSrc: ''
        },
        columns: [{
                data: 'detalle'
            },
            {
                data: null,
                render: function(data, type, item) {
                    return `<button type="button" class="btn btn-danger btn-xs" onclick="eliminarfinger('${item.id}')"><i class="bx bx-trash fs-7 me-0 fw-bold"></i></button>`;
                }
            },

        ],
        order: [
            [1, 'asc']
        ],
    }));
}

function modalBiometria() {
    $('#modalBiometria').modal('show');
}

function syncronizarPersonaBio() {
    if ($('#txt_CardNumero').val() == '') {
        Swal.fire("Debe tener numero de tarjea en biometria", "", "info");
        return false;
    }
    id = '<?= $_id ?>';
    parametros = {
        'id': id,
        'device': $('#ddl_dispositivosSync').val(),
        'card': $('#txt_CardNumero').val(),
    }
    $.ajax({
        data: {
            parametros: parametros
        },
        url: '../controlador/TALENTO_HUMANO/th_personasC.php?syncronizarPersona=true',
        type: 'post',
        dataType: 'json',
        success: function(response) {
            Swal.fire('', response.msj, 'success')
        }
    });

}
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Personas</div>
            <?php
            //print_r($_SESSION['INICIO']);die(); 

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Agregar Persona
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
                        <div class="card-title align-items-center">
                            <div class="row">
                                <div class="col-12">

                                    <h5 class="mb-0 text-primary">
                                        <i class="bx bxs-user me-1 font-22 text-primary"></i>
                                        <?php
                                        if ($_id == '') {
                                            echo 'Registrar Persona';
                                        } else {
                                            echo 'Modificar Persona';
                                        }
                                        ?>
                                    </h5>
                                </div>
                                <hr>
                                <div class="col-12">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_personas"
                                        class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i>
                                        Regresar</a>
                                    <button class="btn btn-primary btn-sm" onclick="modalBiometria()"><i
                                            class="bx bx-sync"></i>Biometria</button>
                                    <!-- <button class="btn btn-primary btn-sm" onclick="syncronizarPersona()"><i class="bx bx-sync"></i>Syncronizar persona en biometrico</button>                                     -->
                                </div>
                            </div>
                        </div>

                        <div class="pt-2">
                            <ul class="nav nav-tabs nav-primary" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#datos" role="tab"
                                        aria-selected="true">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class='bx bx-home font-18 me-1'></i>
                                            </div>
                                            <div class="tab-title">Datos</div>
                                        </div>
                                    </a>
                                </li>

                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#departamentos" role="tab"
                                        aria-selected="false">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class='bx bxs-school font-18 me-1'></i>
                                            </div>
                                            <div class="tab-title">Departamento</div>
                                        </div>
                                    </a>
                                </li>

                            </ul>

                            <div class="tab-content py-3">
                                <div class="tab-pane fade show active" id="datos" role="tabpanel">

                                    <form id="registrar_departamento" class="modal_general_provincias">

                                        <?php include_once('../vista/GENERAL/registrar_personas.php'); ?>

                                        <div class="d-flex justify-content-end pt-2">
                                            <?php if ($_id == '') { ?>
                                            <button class="btn btn-primary btn-sm px-4 m-0 d-flex align-items-center"
                                                onclick="insertar_editar_departamento();" type="button"><i
                                                    class="bx bx-save"></i> Guardar</button>
                                            <?php } else { ?>
                                            <button class="btn btn-primary btn-sm px-4 m-1 d-flex align-items-center"
                                                onclick="insertar_editar_departamento();" type="button"><i
                                                    class="bx bx-save"></i> Guardar</button>
                                            <button class="btn btn-danger btn-sm px-4 m-1 d-flex align-items-center"
                                                onclick="delete_datos_departamento()" type="button"><i
                                                    class="bx bx-trash"></i> Eliminar</button>
                                            <?php } ?>
                                        </div>
                                    </form>

                                </div>
                            </div>

                            <div class="tab-content py-3">
                                <div class="tab-pane fade" id="departamentos" role="tabpanel">

                                    <form id="registrar_departamento" class="modal_general_provincias">


                                        <?php include_once('../vista/TALENTO_HUMANO/PERSONAS/th_persona_departamento_horario.php'); ?>


                                        <div class="d-flex justify-content-end pt-2">
                                            <?php if ($_id == '') { ?>
                                            <button class="btn btn-primary btn-sm px-4 m-0 d-flex align-items-center"
                                                onclick="insertar_persona_departamento();" type="button"><i
                                                    class="bx bx-save"></i> Guardar</button>
                                            <?php } else { ?>
                                            <button class="btn btn-primary btn-sm px-4 m-1 d-flex align-items-center"
                                                onclick="insertar_persona_departamento();" type="button"><i
                                                    class="bx bx-save"></i> Guardar</button>
                                            <?php } ?>
                                        </div>
                                    </form>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!--end row-->
        </div>
    </div>





    <div class="modal" id="modalBiometria" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h3>Datos de biometria</h3>
                </div>

                <!-- Modal body -->
                <div class="modal-body">

                    <div class="row">
                        <div class="d-flex align-items-start">
                            <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist"
                                aria-orientation="vertical">
                                <button class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home"
                                    aria-selected="true">Tarjeta / card</button>
                                <button class="nav-link disabled" id="v-pills-profile-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-pills-profile" type="button" role="tab"
                                    aria-controls="v-pills-profile" aria-selected="false">Huella digital /
                                    Finger</button>
                                <button class="nav-link disabled" id="v-pills-messages-tab" data-bs-toggle="pill"
                                    data-bs-target="#v-pills-messages" type="button" role="tab"
                                    aria-controls="v-pills-messages" aria-selected="false">Facial / Face</button>
                            </div>
                            <div class="tab-content w-100" id="v-pills-tabContent">
                                <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel"
                                    aria-labelledby="v-pills-home-tab">
                                    <div class="row">
                                        <div class="col-12 text-end">
                                            <button type="button" class="btn btn-primary btn-sm"
                                                onclick="nuevaTarjeta()"><i class="bx bx-plus"></i>Nueva
                                                Tarjeta</button>
                                        </div>
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table table-hover" id="tbl_cards" style="width:100%">
                                                    <thead>
                                                        <th>Numero Tarjeta</th>
                                                        <th>Acción</th>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row pt-3">
                                        <div class="col-12 text-end">
                                            <button type="button" class="btn btn-success btn-sm"
                                                onclick="syncronizarPersona(1)"><i class="bx bx-sync"></i> Enviar a
                                                biometrico</button>
                                            <button type="button" class="btn btn-sm btn-secondary"
                                                data-bs-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="v-pills-profile" role="tabpanel"
                                    aria-labelledby="v-pills-profile-tab">
                                    <div class="row">
                                        <div class="col-12 text-end">
                                            <button type="button" class="btn btn-sm btn-primary"
                                                onclick="nuevahuellaBio()"><i class="bx bx-plus"></i>Nueva Huella
                                                digital
                                            </button>
                                        </div>
                                        <div class="col-sm-12">
                                            <table class="table table-hover" id="tbl_bio_finger" style="width:100%">
                                                <thead>
                                                    <th>Numero de Dedo</th>
                                                    <th>Tarjeta Asociada</th>
                                                    <th>Acción</th>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row pt-3">
                                        <div class="col-12 text-end">
                                            <!-- <button type="button" class="btn btn-success btn-sm" onclick="syncronizarPersona(2)"><i class="bx bx-sync"></i> Enviar a biometrico</button> -->
                                            <button type="button" class="btn btn-sm btn-secondary"
                                                data-bs-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="v-pills-messages" role="tabpanel"
                                    aria-labelledby="v-pills-messages-tab">
                                    <div class="row">
                                        <div class="col-sm-12 text-end">
                                            <button class="btn btn-primary btn-sm" onclick="nuevofacial()"><i
                                                    class="bx bx-plus"></i>Nuevo facial</button>

                                        </div>
                                        <div class="col-sm-12">
                                            <table class="table table-hover" id="tbl_bio_face" style="width:100%">
                                                <thead>
                                                    <th>Imagen</th>
                                                    <th>Tarjeta Asociada</th>
                                                    <th>Acción</th>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row pt-3">
                                        <div class="col-12 text-end">
                                            <!-- <button type="button" class="btn btn-success btn-sm" onclick="syncronizarPersona(7)"><i class="bx bx-sync"></i> Enviar a biometrico</button> -->
                                            <button type="button" class="btn btn-sm btn-secondary"
                                                data-bs-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>


                    <!--  <div class="row pt-3">
                        <div class="col-12 text-end">
                            <button type="button" class="btn btn-success btn-sm" onclick="syncronizarPersonaBio()"><i class="bx bx-sync"></i> Enviar a biometrico</button>
                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div> -->

                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {

        //Para validar los select2
        $(".select2-validation").on("select2:select", function(e) {
            unhighlight_select(this);
        });

        //* Validacion de formulario
        $("#registrar_personas").validate({
            rules: {
                txt_primer_apellido: {
                    required: true,
                },
                txt_segundo_apellido: {
                    // required: true,
                },
                txt_primer_nombre: {
                    required: true,
                },
                txt_segundo_nombre: {
                    // required: true,
                },
                txt_cedula: {
                    required: true,
                },
                ddl_sexo: {
                    required: true,
                },
                txt_fecha_nacimiento: {
                    required: true,
                },
                txt_edad: {
                    //required: true,
                },
                txt_telefono_1: {
                    required: true,
                },
                txt_telefono_2: {
                    //required: true,
                },
                txt_correo: {
                    required: true,
                },
                ddl_nacionalidad: {
                    //required: true,
                },
                ddl_estado_civil: {
                    //required: true,
                },
                ddl_provincias: {
                    required: true,
                },
                ddl_ciudad: {
                    required: true,
                },
                ddl_parroquia: {
                    //required: true,
                },
                txt_codigo_postal: {
                    required: true,
                },
                txt_direccion: {
                    //required: true,
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
                txt_cedula: {
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
                    required: "Por favor ingrese un número de teléfono",
                },
                txt_telefono_2: {
                    required: "Por favor ingrese un número de teléfono",
                },
                txt_correo: {
                    required: "Por favor ingrese un correo electrónico",
                },
                ddl_nacionalidad: {
                    required: "Por favor seleccione una nacionalidad",
                },
                ddl_estado_civil: {
                    required: "Por favor seleccione un estado civil",
                },
                ddl_provincias: {
                    required: "Por favor seleccione una provincia",
                },
                ddl_ciudad: {
                    required: "Por favor seleccione una ciudad",
                },
                ddl_parroquia: {
                    required: "Por favor seleccione una parroquia",
                },
                txt_codigo_postal: {
                    required: "Por favor ingrese una dirección postal",
                },
                txt_direccion: {
                    required: "Por favor ingrese una dirección",
                },
            },
            highlight: function(element) {
                let $element = $(element);

                if ($element.hasClass("select2-hidden-accessible")) {
                    // Elimina la clase 'is-invalid' y agrega 'is-valid' al contenedor correcto de select2
                    $element.next(".select2-container").find(".select2-selection").removeClass(
                        "is-valid").addClass("is-invalid");
                } else if ($element.is(':radio')) {
                    // Si es un radio button, aplicar la clase al grupo de radios (al contenedor padre si existe)
                    $('input[name="' + $element.attr("name") + '"]').addClass("is-invalid")
                        .removeClass("is-valid");
                } else {
                    // Elimina la clase 'is-invalid' y agrega 'is-valid' al input normal
                    $element.removeClass("is-valid").addClass("is-invalid");
                }
            },

            unhighlight: function(element) {
                let $element = $(element);

                if ($element.hasClass("select2-hidden-accessible")) {
                    // Para Select2, elimina 'is-invalid' y agrega 'is-valid' en el contenedor adecuado
                    $element.next(".select2-container").find(".select2-selection").removeClass(
                        "is-invalid").addClass("is-valid");
                } else if ($element.is(':radio')) {
                    // Si es un radio button, marcar todo el grupo como válido
                    $('input[name="' + $element.attr("name") + '"]').removeClass("is-invalid")
                        .addClass("is-valid");
                } else {
                    // Para otros elementos normales
                    $element.removeClass("is-invalid").addClass("is-valid");
                }
            }
        });
    });
    </script>

    <div class="modal" id="nuevaTarjeta" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Nueva tarjeta</h3>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="row text-center">
                        <div class="col-md-12">
                            <b>Numero de tarjeta</b>
                            <input type="text" name="txt_CardNumero" id="txt_CardNumero"
                                class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="row pt-3">
                        <div class="col-12 text-end">
                            <button type="button" class="btn btn-success btn-sm" onclick="addTarjetaBase()"><i
                                    class="bx bx-sync"></i>Guardar</button>
                            <button type="button" class="btn btn-sm btn-secondary"
                                data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="nuevahuella" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Nueva Huella</h3>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="row text-center">
                        <div class="col-sm-12">
                            <div class="input-group input-group-sm"> <span class="input-group-text"><b>Tarjeta
                                        Asociada</b></span>
                                <select class="form-select form-select-sm" id="ddl_tarjetas">
                                    <option value="">Seleccione Tarjeta</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="col">
                                <div class="btn-group" role="group" aria-label="First group">
                                    <button type="button" id="btn_finger_1"
                                        class="btn btn-sm btn-outline-primary active" onclick="cambiar(1)">Dedo
                                        1</button>
                                    <button type="button" id="btn_finger_2" class="btn btn-sm btn-outline-primary "
                                        onclick="cambiar(2)">Dedo 2</button>
                                    <button type="button" id="btn_finger_3" class="btn btn-sm btn-outline-primary "
                                        onclick="cambiar(3)">Dedo 3</button>
                                    <button type="button" id="btn_finger_4" class="btn btn-sm btn-outline-primary "
                                        onclick="cambiar(4)">Dedo 4</button>
                                    <button type="button" id="btn_finger_5" class="btn btn-sm btn-outline-primary "
                                        onclick="cambiar(5)">Dedo 5</button>
                                    <input type="hidden" name="txt_dedo_num" value="1" id="txt_dedo_num">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <img id="img_palma" src="../img/de_sistema/palma1.gif" style="width:50%">
                        </div>
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-12">
                                    <button type="button" class="btn btn-primary btn-sm"
                                        onclick="syncronizarPersona(99)">Lectura desde Biometrico</button>
                                    <span id="file-name_bio"></span>
                                </div>
                                <div class="col-12">
                                    <label for="file_huella" class="btn btn-outline-dark btn-sm">Seleccionar
                                        Huella</label>
                                    <input id="file_huella" type="file" accept=".dat" /><br>
                                    <span id="file-name"></span>
                                </div>

                            </div>
                        </div>
                    </div>


                    <div class="row pt-3">
                        <div class="col-12 text-end">
                            <button type="button" class="btn btn-primary btn-sm" onclick="addHuellaBase()"><i
                                    class="bx bx-save"></i> Guardar Huella</button>
                            <button type="button" class="btn btn-sm btn-secondary"
                                data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <div class="modal" id="nuevofacial" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Nueva facial</h3>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="row text-center">
                        <div class="col-sm-12">
                            <div class="input-group input-group-sm"> <span class="input-group-text"><b>Tarjeta
                                        Asociada</b></span>
                                <select class="form-select form-select-sm" id="ddl_tarjetas_facial">
                                    <option value="">Seleccione Tarjeta</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <img id="img_face" src="../img/de_sistema/facial.png">
                            <span id="file_name_bio_face"></span>
                        </div>
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-12">
                                    <button type="button" class="btn btn-sm btn-primary"
                                        onclick="syncronizarPersona(98)">Capturar de Biometrico</button>
                                </div>
                                <div class="col-12">
                                    <label for="file_face" class="btn btn-outline-dark btn-sm">Seleccionar foto</label>
                                    <input id="file_face" type="file" /><br>
                                    <span id="file-name-face"></span>
                                </div>
                            </div>
                        </div>
                        <p style="color:red">Recuerde,la imagen del facial debe ser menor a 200k</p>
                    </div>
                    <div class="row pt-3">
                        <div class="col-12 text-end">
                            <!-- 
                        <button type="button" class="btn btn-sm btn-primary" onclick="addFaceBio()">Enviar al biometrico</button>  -->

                            <button type="button" class="btn btn-sm btn-primary"
                                onclick="addFaceBase()">Guardar</button>
                            <button type="button" class="btn btn-sm btn-secondary"
                                data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="sync_biometrico" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h3>Syncronizar a biometrico</h3>
                </div>

                <!-- Modal body -->
                <div class="modal-body">

                    <div class="row">
                        <div class="col-sm-12">
                            <b>Biometrico a syncronizar</b>
                            <select class="form-select" id="ddl_dispositivos"></select>
                            <input type="hidden" name="txt_cardNo" id="txt_cardNo">
                            <input type="hidden" name="txt_id_reg" id="txt_id_reg">
                        </div>
                    </div>


                    <div class="row pt-3">
                        <div class="col-12 text-end">
                            <button type="button" id="btn_tarjeta_all" class="btn btn-success btn-sm d-none"
                                onclick="addTarjetaBioAll()"><i class="bx bx-sync"></i>Sincronizar todas las
                                Tarjeta</button>
                            <button type="button" id="btn_tarjeta" class="btn btn-success btn-sm d-none"
                                onclick="addTarjetaBio()"><i class="bx bx-sync"></i>Sincronizar Tarjeta</button>
                            <button type="button" id="btn_delete_tarjeta" class="btn btn-danger btn-sm d-none"
                                onclick=" deteleTarjetaBio()"><i class="bx bx-sync"></i>Eliminar Tarjeta</button>

                            <button type="button" id="btn_huella_all" class="btn btn-success btn-sm d-none"
                                onclick="()"><i class="bx bx-sync"></i> Sincronizar todas las Huella</button>
                            <button type="button" id="btn_huella" class="btn btn-success btn-sm d-none"
                                onclick="addHuellaBio()"><i class="bx bx-sync"></i> Sincronizar Huella</button>
                            <button type="button" id="btn_delete_huella" class="btn btn-danger btn-sm d-none"
                                onclick="deteleHuella()"><i class="bx bx-sync"></i>Eliminar Huella</button>


                            <button type="button" id="btn_facial" class="btn btn-success btn-sm d-none"
                                onclick="addFaceBio()"><i class="bx bx-sync"></i> Sincronizar Facial</button>
                            <button type="button" id="btn_delete_facial" class="btn btn-danger btn-sm d-none"
                                onclick="deteleFace()"><i class="bx bx-sync"></i>Eliminar facial</button>


                            <button type="button" id="btn_huella_lectura" class="btn btn-primary btn-sm d-none"
                                onclick="leerDedo()"><i class="bx bx-sync"></i> Iniciar lectura de huella</button>
                            <button type="button" id="btn_face_lectura" class="btn btn-primary btn-sm d-none"
                                onclick="leerFace()"><i class="bx bx-sync"></i> Iniciar lectura facial</button>


                            <button type="button" class="btn btn-sm btn-secondary"
                                data-bs-dismiss="modal">Cerrar</button>


                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>