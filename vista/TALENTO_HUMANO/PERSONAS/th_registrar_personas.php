<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);
$redireccionar_vista = 'th_personas';

$_id = '';

if (isset($_GET['_id'])) {
    $_id = $_GET['_id'];
}

?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        dispositivos();
        dispositivosSync();
        // cargar_tabla();
        <?php if (isset($_GET['_id'])) { ?>
            cargar_datos_persona(<?= $_id ?>);
        <?php } ?>


    });

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
                        location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=<?= $redireccionar_vista ?>';
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

    function leerDedo() {
        $('#myModal_espera').modal('show');
        var parametros = {
            'iddispostivos': $('#ddl_dispositivos').val(),
            'Idusuario': <?php echo $_id; ?>,
            'dedo': $('#txt_dedo_num').val(),
            'usuario': $('#txt_primer_apellido').val() + ' ' + $('#txt_segundo_apellido').val() + ' ' + $('#txt_primer_nombre').val() + ' ' + $('#txt_segundo_nombre').val(),
            'CardNo': $('#txt_CardNumero').val(),
        }
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/th_detectar_dispositivosC.php?CapturarFinger=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                $('#myModal_espera').modal('hide');
                if (response.resp == 1) {
                    Swal.fire("Huella dactilar Guardada", response.patch, "success");
                } else {
                    Swal.fire("Huella dactilar", response.msj, "info");
                }


                tbl_dispositivos.ajax.reload(null, false);

            },
            error: function(xhr, status, error) {
                console.log('Status: ' + status);
                console.log('Error: ' + error);
                console.log('XHR Response: ' + xhr.responseText);

                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
                $('#myModal_espera').modal('hide');
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

    function dispositivosSync() {
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
                $('#ddl_dispositivosSync').html(op);

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

    function syncronizarPersona() {
        $('#sync_biometrico').modal('show');

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
                        <div class="card-title d-flex align-items-center">

                            <div><i class="bx bxs-user me-1 font-22 text-primary"></i>
                            </div>
                            <h5 class="mb-0 text-primary">
                                <?php
                                if ($_id == '') {
                                    echo 'Registrar Persona';
                                } else {
                                    echo 'Modificar Persona';
                                }
                                ?>
                            </h5>

                            <div class="row m-2">
                                <div class="col-sm-12">
                                    <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_personas" class="btn btn-outline-dark btn-sm"><i class="bx bx-arrow-back"></i> Regresar</a>
                                    <button class="btn btn-primary btn-sm" onclick="syncronizarPersona()"><i class="bx bx-sync"></i>Syncronizar persona en biometrico</button>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <div class="pt-2">
                            <ul class="nav nav-tabs nav-primary" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#datos" role="tab" aria-selected="true">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class='bx bx-home font-18 me-1'></i>
                                            </div>
                                            <div class="tab-title">Datos</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#tarjetas" role="tab" aria-selected="false">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class='bx bx-credit-card font-18 me-1'></i>
                                            </div>
                                            <div class="tab-title">Biometría</div>
                                        </div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#departamentos" role="tab" aria-selected="false">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class='bx bxs-school font-18 me-1'></i>
                                            </div>
                                            <div class="tab-title">Departamentos</div>
                                        </div>
                                    </a>
                                </li>

                            </ul>

                            <div class="tab-content py-3">
                                <div class="tab-pane fade show active" id="datos" role="tabpanel">

                                    <form id="registrar_personas" class="modal_general_provincias">

                                        <?php include_once('../vista/GENERAL/registrar_personas.php'); ?>

                                        <div class="d-flex justify-content-end pt-2">
                                            <?php if ($_id == '') { ?>
                                                <button class="btn btn-primary btn-sm px-4 m-0 d-flex align-items-center" onclick="insertar_editar_persona();" type="button"><i class="bx bx-save"></i> Guardar</button>
                                            <?php } else { ?>
                                                <button class="btn btn-primary btn-sm px-4 m-1 d-flex align-items-center" onclick="insertar_editar_persona();" type="button"><i class="bx bx-save"></i> Guardar</button>
                                                <button class="btn btn-danger btn-sm px-4 m-1 d-flex align-items-center" onclick="delete_datos_persona()" type="button"><i class="bx bx-trash"></i> Eliminar</button>
                                            <?php } ?>
                                        </div>
                                    </form>

                                </div>

                                <div class="tab-pane fade" id="tarjetas" role="tabpanel">

                                    <div class="row">
                                        <div class="col-md-3">
                                            <b>Numero de tarjeta</b>
                                            <input type="text" name="txt_CardNumero" id="txt_CardNumero" class="form-control form-control-sm">
                                            <b>Registro de facial</b>
                                            <input type="text" name="" id="" class="form-control form-control-sm">
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="col">
                                                        <div class="btn-group" role="group" aria-label="First group">
                                                            <button type="button" id="btn_finger_1" class="btn btn-sm btn-outline-primary active" onclick="cambiar(1)">Dedo 1</button>
                                                            <button type="button" id="btn_finger_2" class="btn btn-sm btn-outline-primary " onclick="cambiar(2)">Dedo 2</button>
                                                            <button type="button" id="btn_finger_3" class="btn btn-sm btn-outline-primary " onclick="cambiar(3)">Dedo 3</button>
                                                            <button type="button" id="btn_finger_4" class="btn btn-sm btn-outline-primary " onclick="cambiar(4)">Dedo 4</button>
                                                            <button type="button" id="btn_finger_5" class="btn btn-sm btn-outline-primary " onclick="cambiar(5)">Dedo 5</button>
                                                            <input type="hidden" name="txt_dedo_num" value="1" id="txt_dedo_num">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <select class="form-select" id="ddl_dispositivos" name="ddl_dispositivos">
                                                        <option value="">Seleccione Dispositivo</option>
                                                    </select>
                                                </div>
                                                <div class="col-2 text-end">
                                                    <button type="button" class="btn btn-sm btn-primary" onclick="leerDedo()">Iniciar Lectura</button>
                                                </div>

                                                <div class="row text-center">
                                                    <div class="col-sm-6">
                                                        <img id="img_palma" src="../img/de_sistema/palma1.gif" style="width:100%">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <table class="table table-hover" id="tbl_bio_finger" style="width:100%">
                                                            <thead>
                                                                <th>Numero de Dedo</th>
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

                                        </div>

                                        <!-- 
                                    <div class="row pt-4">
                                        <div class="table-responsive">
                                            <table class="table table-striped responsive" id="tbl_departamento_personas" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>Cédula</th>
                                                        <th>Nombre</th>
                                                        <th>Correo</th>
                                                        <th>Teléfono</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div> -->

                                    </div>

                                    <div class="tab-pane fade" id="departamentos" role="tabpanel">

                                        <div class="row pt-3">
                                            <div class="col-sm-12" id="btn_nuevo">
                                                <button type="button" class="btn btn-success btn-sm" onclick="abrir_modal_personas();"><i class="bx bx-plus"></i> Agregar Departamentos</button>
                                            </div>
                                        </div>

                                        <div class="row pt-4">
                                            <div class="table-responsive">
                                                <table class="table table-striped responsive" id="tbl_departamento_personas" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Cédula</th>
                                                            <th>Nombre</th>
                                                            <th>Correo</th>
                                                            <th>Teléfono</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!--end row-->
        </div>
    </div>


    <div class="modal" id="sync_biometrico" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
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
                            <select class="form-select" id="ddl_dispositivosSync"></select>
                        </div>
                    </div>


                    <div class="row pt-3">
                        <div class="col-12 text-end">
                            <button type="button" class="btn btn-success btn-sm" onclick="syncronizarPersonaBio()"><i class="bx bx-sync"></i> Enviar a biometrico</button>
                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>

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
                        required: true,
                    },
                    txt_primer_nombre: {
                        required: true,
                    },
                    txt_segundo_nombre: {
                        required: true,
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
                        $element.next(".select2-container").find(".select2-selection").removeClass("is-valid").addClass("is-invalid");
                    } else if ($element.is(':radio')) {
                        // Si es un radio button, aplicar la clase al grupo de radios (al contenedor padre si existe)
                        $('input[name="' + $element.attr("name") + '"]').addClass("is-invalid").removeClass("is-valid");
                    } else {
                        // Elimina la clase 'is-invalid' y agrega 'is-valid' al input normal
                        $element.removeClass("is-valid").addClass("is-invalid");
                    }
                },

                unhighlight: function(element) {
                    let $element = $(element);

                    if ($element.hasClass("select2-hidden-accessible")) {
                        // Para Select2, elimina 'is-invalid' y agrega 'is-valid' en el contenedor adecuado
                        $element.next(".select2-container").find(".select2-selection").removeClass("is-invalid").addClass("is-valid");
                    } else if ($element.is(':radio')) {
                        // Si es un radio button, marcar todo el grupo como válido
                        $('input[name="' + $element.attr("name") + '"]').removeClass("is-invalid").addClass("is-valid");
                    } else {
                        // Para otros elementos normales
                        $element.removeClass("is-invalid").addClass("is-valid");
                    }
                }
            });
        });
    </script>