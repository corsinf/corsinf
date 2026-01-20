<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);

?>

<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>
<script type="text/javascript">
    var tbl_protecion_datos;
    $(document).ready(function() {

        tbl_protecion_datos = $('#tbl_protecion_datos').DataTable($.extend({}, configuracion_datatable('Codigo', 'Nombre'), {
            reponsive: true,
            language: {
                url: '../assets/plugins/datatable/spanish.json'
            },
            ajax: {
                url: '../controlador/TALENTO_HUMANO/th_proteccion_datos_personaC.php?listar=true',
                dataSrc: ''
            },
            columns: [{
                data: null,
                render: function(data, type, item) {
                    href = `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registrar_comision&_id=${item._id}`;
                    return `<a href="${href}"><u>${item.nombre_completo}</u></a>`;
                }
            }, {
                data: 'th_prod_rol'
            }, {
                data: null,
                orderable: false,
                className: 'text-center',
                render: function(data, type, item) {
                    return `
                        <div class="d-flex justify-content-center gap-1">
                            <button type="button" class="btn btn-danger btn-xs" onclick="delete_datos_persona_proteccion('${item._id}')">
                                <i class="bx bx-trash fs-7 fw-bold"></i>
                            </button>
                        </div>
                    `;
                }
            }],
            order: [
                [1, 'asc']
            ]
        }));

        cargar_selects2();

        function cargar_selects2() {
            url_personasC = '../controlador/TALENTO_HUMANO/th_personasC.php?buscar=true';
            cargar_select2_url('ddl_personas', url_personasC, '', '#modal_registrar_persona');
        }

    });

    function delete_datos_persona_proteccion(id) {
        Swal.fire({
            title: 'Eliminar Registro',
            text: '¿Está seguro de eliminar este registro?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {

            if (result.isConfirmed) {

                $.ajax({
                    url: '../controlador/TALENTO_HUMANO/th_proteccion_datos_personaC.php?eliminar=true',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id: id
                    },
                    success: function(resp) {

                        if (resp == 1 || resp.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Eliminado',
                                text: 'El registro fue eliminado correctamente.',
                                timer: 1500,
                                showConfirmButton: false
                            });

                            if (typeof tbl_protecion_datos !== 'undefined') {
                                tbl_protecion_datos.ajax.reload(null, false);
                            }

                        } else {
                            Swal.fire('', 'No se pudo eliminar el registro.', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('', 'Error en el servidor.', 'error');
                    }
                });
            }
        });
    }


    function insertar_editar_proteccion_datos() {

        var ddl_personas = $('#ddl_personas').val() || '';;
        var ddl_rol = $('#ddl_rol').val() || '';
        var th_prod_id = $('#txt_th_prod_id').val() || '';

        var parametros_proteccion = {
            '_id': th_prod_id,
            'th_per_id': ddl_personas,
            'th_prod_rol': ddl_rol
        };

        if ($("#form_proteccion_datos").valid()) {
            insertar_proteccion_datos(parametros_proteccion);
        }
    }

    function insertar_proteccion_datos(parametros) {
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/TALENTO_HUMANO/th_proteccion_datos_personaC.php?insertar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {

                if (response == 1 || response == 2) {
                    Swal.fire('', 'Operación realizada con éxito.', 'success');
                    $('#modal_registrar_persona').modal('hide');
                    tbl_protecion_datos.ajax.reload(null, false);
                    limpiar_campos_proteccion_datos();
                } else if (response == -3) {
                    Swal.fire('', 'Operación fallida rol asignado', 'warning');
                } else {
                    Swal.fire('', 'Operación fallida', 'warning');
                }
            }
        });
    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Protección de datos</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Lista de Personas
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

                        <div class="row">

                            <div class="col-12 col-md-6">
                                <div class="card-title d-flex align-items-center">

                                    <div id="btn_nuevo">
                                        <button type="button"
                                            class="btn btn-success btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modal_registrar_persona">
                                            <i class="bx bx-plus me-0 pb-1"></i> Nuevo
                                        </button>
                                    </div>

                                </div>
                            </div>

                            <div class="col-12 col-md-6 text-md-end text-start">
                                <div id="contenedor_botones"></div>
                            </div>

                        </div>

                        <hr>

                        <section class="content pt-0">
                            <div class="container-fluid">

                                <div class="table-responsive">
                                    <table class="table table-striped responsive " id="tbl_protecion_datos" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Persona</th>
                                                <th>Rol</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody class="">

                                        </tbody>
                                    </table>
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


<div class="modal fade" id="modal_registrar_persona" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header text-white">
                <h5 class="modal-title">
                    <i class="bx bx-user-plus me-1"></i> Registrar Protección de datos
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="form_proteccion_datos">
                    <div class="col-md-12">
                        <label for="ddl_personas" class="form-label">Personas</label>
                        <select class="form-select form-select-sm select2-validation"
                            id="ddl_personas"
                            name="ddl_personas">
                            <option selected disabled>-- Seleccione --</option>
                        </select>
                        <label class="error" style="display: none;" for="ddl_personas"></label>
                    </div>

                    <div class="col-md-12">
                        <label for="ddl_rol" class="form-label">Rol</label>
                        <select class="form-select form-select-sm"
                            id="ddl_rol"
                            name="ddl_rol">
                            <option selected disabled>-- Seleccione --</option>
                            <option value="RESPONSABLE DE TRATAMIENTO">RESPONSABLE DE TRATAMIENTO</option>
                            <option value="ENCARGADO DE TRATAMIENTO">ENCARGADO DE TRATAMIENTO</option>
                            <option value="DELEGADO DE PROTECCIÓN DE DATOS PERSONALES">
                                DELEGADO DE PROTECCIÓN DE DATOS PERSONALES
                            </option>
                        </select>
                        <label class="error" style="display: none;" for="ddl_rol"></label>
                    </div>
                </form>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-success btn-sm" onclick="insertar_editar_proteccion_datos()">
                    <i class="bx bx-save"></i> Guardar
                </button>
            </div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        // Asteriscos campos obligatorios
        agregar_asterisco_campo_obligatorio('ddl_personas');
        agregar_asterisco_campo_obligatorio('ddl_rol');

        // Validación Protección de Datos
        $("#form_proteccion_datos").validate({
            rules: {
                ddl_personas: {
                    required: true
                },
                ddl_rol: {
                    required: true
                }
            },
            messages: {
                ddl_personas: {
                    required: "Por favor seleccione una persona"
                },
                ddl_rol: {
                    required: "Por favor seleccione un rol"
                }
            },

            highlight: function(element) {
                $(element).addClass('is-invalid');
                $(element).removeClass('is-valid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid');
                $(element).addClass('is-valid');
            },

            errorPlacement: function(error, element) {
                // Para select2
                if (element.hasClass('select2-validation')) {
                    error.insertAfter(element.next('.select2'));
                } else {
                    error.insertAfter(element);
                }
            }
        });

    });
</script>