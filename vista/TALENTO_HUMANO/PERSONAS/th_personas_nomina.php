<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);

?>

<script src="../js/GENERAL/operaciones_generales.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        tbl_personas = $('#tbl_personas').DataTable({
            reponsive: true,
            stateSave: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            ajax: {
                url: '../controlador/TALENTO_HUMANO/th_personas_departamentosC.php?listar=true',
                dataSrc: ''
            },
            columns: [{
                    data: null,
                    render: function(data, type, item) {
                        // href = `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_informacion_personal&id_postulante=${item._id_postulante ?? 'postulante'}&id_persona=${item.id_persona}`;
                        // btns = `<a href="${href}" class="btn btn-xs btn-primary" title="CV"><i class="bx bxs-user-pin fs-6 me-0"></i></a>`;

                        // return btns;

                        return fecha_formateada(item.fecha_creacion);
                    }
                },

                {
                    data: null,
                    render: function(data, type, item) {
                        id_postulante = 'postulante';

                        if (item._id_postulante != null && item._id_postulante != '') {
                            id_postulante = item._id_postulante;
                        }

                        href = `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registrar_personas&id_persona=${item.id_persona}&id_postulante=${id_postulante}&_origen=nomina&_persona_nomina=true`;
                        return `<a href="${href}"><u>${item.primer_apellido} ${item.segundo_apellido} ${item.primer_nombre} ${item.segundo_nombre}</u></a>`;
                    }
                },
                {
                    data: 'cedula'
                },

                {
                    data: 'correo'
                },
                {
                    data: 'telefono_1'
                },
                {
                    data: 'nombre_departamento'
                },
            ],
            order: [
                [1, 'asc']
            ],
        });
    });
</script>




<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Personas</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Nómina
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
                                <div class="card-title">
                                    <div class="d-flex flex-wrap align-items-center gap-2" id="btn_nuevo">

                                        <a href="javascript:void(0)" class="btn btn-success btn-sm"
                                            data-bs-toggle="modal" data-bs-target="#modal_mensaje_personas">
                                            <i class="bx bx-envelope me-1"></i> Enviar Mensaje
                                        </a>

                                        <button class="btn btn-success btn-sm"
                                            data-bs-toggle="modal" data-bs-target="#modal_mensaje" disabled>
                                            <i class='bx bx-file'></i> Descargar Nómina
                                        </button>

                                    </div>
                                </div>

                            </div>
                            <div class="col-12 col-md-6 text-md-end text-start">
                                <div id="contenedor_botones"></div>
                            </div>
                        </div>

                        <hr>

                        <section class="content pt-2">
                            <div class="container-fluid">
                                <div class="table-responsive">
                                    <table class="table table-striped responsive " id="tbl_personas" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th width="10%">#</th>
                                                <th>Nombre</th>
                                                <th>Cédula</th>
                                                <th>Correo</th>
                                                <th>Teléfono</th>
                                                <th>Departamento</th>
                                            </tr>
                                        </thead>
                                        <tbody>

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

<div class="modal fade" id="modal_mensaje_personas" tabindex="-1" aria-labelledby="modal_mensaje_label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_mensaje_label">Enviar Mensaje</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="form_mensaje" onsubmit="return false;">
                <div class="modal-body">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" value="1" id="cbx_enviar_credenciales" checked>
                        <label class="form-check-label" for="cbx_enviar_credenciales">Enviar credenciales</label>
                    </div>

                    <!-- Contenedor de inputs que se muestran cuando NO está marcado 'Enviar credenciales' -->
                    <div id="cont_inputs_mensaje" style="display: none;">
                        <div class="mb-3">
                            <label for="txt_asunto" class="form-label">Asunto</label>
                            <input type="text" class="form-control" id="txt_asunto" name="txt_asunto"
                                placeholder="Asunto del mensaje">
                        </div>
                        <div class="mb-3">
                            <label for="txt_descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="txt_descripcion" name="txt_descripcion" rows="5"
                                placeholder="Escribe aquí la descripción..."></textarea>
                        </div>
                    </div>

                    <!-- Mensaje informativo opcional -->
                    <div id="info_credenciales" class="small text-muted" style="display: block;">
                        Se enviarán las credenciales almacenadas para todas las personas.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" id="btn_enviar_mensaje" class="btn btn-primary">
                        Enviar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var $cbx = $('#cbx_enviar_credenciales');
        var $contInputs = $('#cont_inputs_mensaje');
        var $infoCred = $('#info_credenciales');
        var $modal = $('#modal_mensaje_personas');

        // Variable PHP convertida a JavaScript al inicio
        var perId = '<?= isset($id_persona) ? $id_persona : "" ?>';

        function actualizarVista() {
            if ($cbx.is(':checked')) {
                $contInputs.hide();
                $infoCred.show();
            } else {
                $contInputs.show();
                $infoCred.hide();
            }
        }

        // Al abrir modal - resetear el formulario
        $modal.on('show.bs.modal', function() {
            // Resetear checkbox a marcado
            $cbx.prop('checked', true);
            // Limpiar campos
            $('#txt_asunto').val('');
            $('#txt_descripcion').val('');
            // Actualizar vista
            actualizarVista();
        });

        // Cambio checkbox
        $cbx.on('change', function() {
            actualizarVista();
        });

        // Click botón enviar
        $('#btn_enviar_mensaje').on('click', function() {
            enviarMensaje();
        });

        function enviarMensaje() {
            var enviarCred = $cbx.is(':checked');
            var asunto = $.trim($('#txt_asunto').val() || '');
            var descripcion = $.trim($('#txt_descripcion').val() || '');

            // Validación solo si NO se envían credenciales
            if (!enviarCred) {
                if (asunto === '') {
                    Swal.fire('Advertencia', 'Ingresa el asunto', 'warning');
                    return;
                }
                if (descripcion === '') {
                    Swal.fire('Advertencia', 'Ingresa la descripción', 'warning');
                    return;
                }
            }

            var parametrosLogCorreos = {
                enviar_credenciales: enviarCred ? 1 : 0,
                asunto: asunto,
                descripcion: descripcion,
                per_id: perId,
                personas: 'nomina'
            };

            enviar_Mail_Persona(parametrosLogCorreos);
            $modal.modal('hide');
        }

        function enviar_Mail_Persona(parametrosLogCorreos) {
            $.ajax({
                url: '../controlador/TALENTO_HUMANO/th_logs_correosC.php?enviar_correo=true',
                type: 'POST',
                dataType: 'json',
                data: {
                    parametros: parametrosLogCorreos
                },
                beforeSend: function() {
                    Swal.fire({
                        title: 'Enviando correos...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });
                },
                success: function(response) {
                    Swal.fire('OK', 'Proceso terminado', 'success');
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    console.error('Response:', xhr.responseText);
                    Swal.fire('Error', 'Error en la conexión', 'error');
                }
            });
        }
    });
</script>