<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);
?>

<script src="../js/GENERAL/operaciones_generales.js"></script>
<script type="text/javascript">
    let id_departamento = '';
    let tbl_personas;
    let tbl_eliminados;

    $(document).ready(function() {

        tbl_personas = $('#tbl_personas').DataTable({
            responsive: true,
            stateSave: true,
            language: {
                url: '../assets/plugins/datatable/spanish.json'
            },
            ajax: {
                url: '../controlador/GENERAL/NO_CONCURRENTES/EMPLEADOSC.php?listar_personas_departamentos=true',
                type: 'POST',
                data: function(d) {
                    d.id = id_departamento;
                },
                dataSrc: ''
            },
            columns: [{
                    data: null,
                    render: function(data, type, item) {
                        let id_postulante = item._id_postulante ?? 'postulante';
                        let href = `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registrar_personas&id_persona=${item.id_persona}&id_postulante=${id_postulante}&_origen=nomina&_persona_nomina=true`;
                        return `<a href="${href}">
                            <u>${item.primer_apellido ?? ''} ${item.segundo_apellido ?? ''} ${item.primer_nombre ?? ''} ${item.segundo_nombre ?? ''}</u>
                        </a>`;
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
                {
                    data: null,
                    render: function(data, type, item) {
                        return fecha_formateada(item.fecha_creacion);
                    }
                },
            ],
            order: [
                [1, 'asc']
            ]
        });

        $('#ddl_departamentos').on('change', function() {
            id_departamento = $(this).val();
            tbl_personas.ajax.reload();
        });

        cargar_selects2();

        function cargar_selects2() {
            let url_departamentosC = '../controlador/TALENTO_HUMANO/th_departamentosC.php?buscar_departamento=true';
            let url_departamentos_normalC = '../controlador/TALENTO_HUMANO/th_departamentosC.php?buscar=true';
            cargar_select2_url('ddl_departamentos', url_departamentosC);
            cargar_select2_url('ddl_dep_restaurar', url_departamentos_normalC, '', '#modal_agregar_departamento');
        }

        // ── Empleados Eliminados ──────────────────────────────────────
        $('#btn_eliminados').on('click', function() {
            $('#modal_eliminados').modal('show');
        });

        $('#modal_eliminados').on('shown.bs.modal', function() {
            if (tbl_eliminados) {
                tbl_eliminados.ajax.reload();
                return;
            }

            tbl_eliminados = $('#tbl_eliminados').DataTable({
                responsive: true,
                language: {
                    url: '../assets/plugins/datatable/spanish.json'
                },
                ajax: {
                    url: '../controlador/GENERAL/NO_CONCURRENTES/EMPLEADOSC.php?listar=true',
                    type: 'POST',
                    data: function(d) {
                        d.id = id_departamento;
                    },
                    dataSrc: ''
                },
                columns: [{
                        data: null,
                        render: function(data, type, item) {
                            return `${item.primer_apellido ?? ''} ${item.segundo_apellido ?? ''} ${item.primer_nombre ?? ''} ${item.segundo_nombre ?? ''}`.trim();
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
                    {
                        data: null,
                        orderable: false,
                        className: 'text-center',
                        render: function(data, type, item) {
                            return `
                               <button type="button" class="btn btn-warning btn-xs" onclick="abrir_modal_agregar_departamento('${item._id}','${item.id_persona}')">
                                <i class="bx bx-edit fs-7 me-0 fw-bold"></i>
                            </button>
                           `;
                        }
                    }
                ],
                order: [
                    [1, 'asc']
                ]
            });
        });

        // ── Agregar al departamento ───────────────────────────────────
        $('#btn_confirmar_agregar').on('click', function() {
            confirmar_agregar_departamento();
        });

    });

    function abrir_modal_agregar_departamento(id_empleado, id_persona) {
        $('#hdn_id_empleado').val(id_empleado);
        $('#hdn_id_persona').val(id_persona);
        $('#ddl_dep_restaurar').val(null).trigger('change');
        $('#modal_agregar_departamento').modal('show');
    }

    function confirmar_agregar_departamento() {
        var id_departamento_sel = $('#ddl_dep_restaurar').val();
        var id_empleado = $('#hdn_id_empleado').val();
        var id_persona = $('#hdn_id_persona').val();

        if (!id_departamento_sel) {
            Swal.fire('Advertencia', 'Seleccione un departamento', 'warning');
            return;
        }

        $.ajax({
            url: '../controlador/GENERAL/NO_CONCURRENTES/EMPLEADOSC.php?restaurar=true',
            type: 'POST',
            dataType: 'json',
            data: {
                id_empleado: id_empleado,
                id_persona: id_persona,
                id_departamento: id_departamento_sel
            },
            success: function(response) {
                if (response >= 1) {
                    Swal.fire('', 'Persona agregada al departamento con éxito.', 'success').then(function() {
                        $('#modal_agregar_departamento').modal('hide');
                        tbl_eliminados.ajax.reload();
                        tbl_personas.ajax.reload();
                    });
                } else {
                    Swal.fire('Error', 'No se pudo completar la operación.', 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'Error en la conexión.', 'error');
            }
        });
    }
</script>


<div class="page-wrapper">
    <div class="page-content">

        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Personas</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">Nómina</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 mx-auto">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body p-5">

                        <div class="row">
                            <div class="col-12">
                                <div class="card-title">

                                    <div class="row mb-2">
                                        <div class="col-12">
                                            <a href="javascript:void(0)"
                                                class="btn btn-success btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modal_mensaje_personas">
                                                <i class="bx bx-envelope me-1"></i> Enviar Mensaje
                                            </a>

                                            <button class="btn btn-success btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modal_mensaje"
                                                disabled>
                                                <i class='bx bx-file me-1'></i> Descargar Nómina
                                            </button>

                                            <button class="btn btn-primary btn-sm" id="btn_eliminados">
                                                <i class='bx bx-trash me-1'></i> Empleados Eliminados
                                            </button>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <label for="ddl_departamentos" class="form-label mb-1">Departamentos</label>
                                            <select class="form-select select2-validation"
                                                id="ddl_departamentos"
                                                name="ddl_departamentos">
                                                <option selected disabled>-- Seleccione --</option>
                                                <option value="">Todos</option>
                                            </select>
                                            <label class="error d-none" for="ddl_departamentos"></label>
                                        </div>
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
                                    <table class="table table-striped responsive" id="tbl_personas" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Cédula</th>
                                                <th>Correo</th>
                                                <th>Teléfono</th>
                                                <th>Departamento</th>
                                                <th width="10%">Fecha Ingreso</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </section>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


<!-- Modal Empleados Eliminados -->
<div class="modal fade" id="modal_eliminados" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bx bx-trash me-1"></i> Empleados Eliminados
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped responsive" id="tbl_eliminados" style="width:100%">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Cédula</th>
                                <th>Correo</th>
                                <th>Teléfono</th>
                                <th>Departamento</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal Agregar al Departamento -->
<div class="modal fade" id="modal_agregar_departamento" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bx bx-transfer me-1"></i> Agregar al Departamento
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="hdn_id_empleado">
                <input type="hidden" id="hdn_id_persona">
                <div class="mb-3">
                    <label for="ddl_dep_restaurar" class="form-label">
                        Departamento <span class="text-danger">*</span>
                    </label>
                    <select class="form-select select2-validation" id="ddl_dep_restaurar" name="ddl_dep_restaurar">
                        <option selected disabled>-- Seleccione --</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success btn-sm" id="btn_confirmar_agregar">
                    <i class="bx bx-save me-1"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>


<!-- Modal Enviar Mensaje -->
<!-- Modal Enviar Mensaje -->
<div class="modal fade" id="modal_mensaje_personas" tabindex="-1" aria-labelledby="modal_mensaje_label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_mensaje_label">
                    <i class="bx bx-envelope me-1"></i> Enviar Mensaje
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="form_mensaje" onsubmit="return false;">
                <div class="modal-body">

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" value="1" id="cbx_enviar_credenciales" checked>
                        <label class="form-check-label fw-semibold" for="cbx_enviar_credenciales">Enviar credenciales</label>
                    </div>

                    <!-- Opciones de tipo de envío (visibles solo cuando "Enviar credenciales" está activo) -->
                    <div id="cont_tipo_envio">
                        <p class="text-muted small mb-2">Selecciona a quiénes deseas enviar las credenciales:</p>

                        <div class="border rounded p-3 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="rdb_tipo_envio" id="rdb_todos" value="todos" checked>
                                <label class="form-check-label fw-semibold" for="rdb_todos">
                                    <i class="bx bx-group me-1 text-primary"></i> Enviar a todos
                                </label>
                            </div>
                            <div class="small text-muted mt-1 ms-4">
                                Se genera una nueva contraseña y se reinicia la política de aceptación para <strong>todos</strong> los empleados del listado actual.
                            </div>
                        </div>

                        <div class="border rounded p-3 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="rdb_tipo_envio" id="rdb_faltantes" value="faltantes">
                                <label class="form-check-label fw-semibold" for="rdb_faltantes">
                                    <i class="bx bx-user-x me-1 text-warning"></i> Enviar a faltantes
                                </label>
                            </div>
                            <div class="small text-muted mt-1 ms-4">
                                Solo se envía a los empleados que <strong>aún no tienen contraseña</strong> asignada en el sistema. No afecta a los que ya la tienen.
                            </div>
                        </div>

                        <div class="border rounded p-3 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="rdb_tipo_envio" id="rdb_no_aceptan" value="no_aceptan">
                                <label class="form-check-label fw-semibold" for="rdb_no_aceptan">
                                    <i class="bx bx-shield-x me-1 text-danger"></i> Enviar a los que no aceptan políticas
                                </label>
                            </div>
                            <div class="small text-muted mt-1 ms-4">
                                Se envía a los empleados que <strong>no han aceptado las políticas</strong> del sistema (incluye también a quienes no tienen contraseña).
                            </div>
                        </div>
                    </div>

                    <!-- Campos para mensaje personalizado (visibles solo cuando NO se envían credenciales) -->
                    <div id="cont_inputs_mensaje" style="display: none;">
                        <div class="mb-3">
                            <label for="txt_asunto" class="form-label">Asunto</label>
                            <input type="text" class="form-control" id="txt_asunto" name="txt_asunto" placeholder="Asunto del mensaje">
                        </div>
                        <div class="mb-3">
                            <label for="txt_descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="txt_descripcion" name="txt_descripcion" rows="5" placeholder="Escribe aquí la descripción..."></textarea>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" id="btn_enviar_mensaje" class="btn btn-primary">
                        <i class="bx bx-send me-1"></i> Enviar
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
        var $contTipo = $('#cont_tipo_envio');
        var $modal = $('#modal_mensaje_personas');
        var perId = '<?= isset($id_persona) ? $id_persona : "" ?>';

        function actualizarVista() {
            if ($cbx.is(':checked')) {
                $contTipo.show();
                $contInputs.hide();
            } else {
                $contTipo.hide();
                $contInputs.show();
            }
        }

        $modal.on('show.bs.modal', function() {
            $cbx.prop('checked', true);
            $('input[name="rdb_tipo_envio"][value="todos"]').prop('checked', true);
            $('#txt_asunto').val('');
            $('#txt_descripcion').val('');
            actualizarVista();
        });

        $cbx.on('change', function() {
            actualizarVista();
        });

        $('#btn_enviar_mensaje').on('click', function() {
            enviarMensaje();
        });

        function enviarMensaje() {
            var enviarCred = $cbx.is(':checked');
            var tipoEnvio = $('input[name="rdb_tipo_envio"]:checked').val() || 'todos';
            var asunto = $.trim($('#txt_asunto').val() || '');
            var descripcion = $.trim($('#txt_descripcion').val() || '');

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

            // Confirmación con descripción del tipo seleccionado
            var mensajesConfirmacion = {
                todos: '¿Seguro? Se generará una nueva contraseña y se reiniciará la política de aceptación para <b>todos</b> los empleados.',
                faltantes: '¿Seguro? Solo se enviarán credenciales a los empleados <b>sin contraseña</b> asignada.',
                no_aceptan: '¿Seguro? Se enviará a los empleados que <b>no han aceptado las políticas</b> (incluye los sin contraseña).'
            };

            var htmlConfirm = enviarCred ?
                (mensajesConfirmacion[tipoEnvio] || '') :
                '¿Deseas enviar el mensaje a los empleados?';

            Swal.fire({
                title: 'Confirmar envío',
                html: htmlConfirm,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, enviar',
                cancelButtonText: 'Cancelar'
            }).then(function(result) {
                if (result.isConfirmed) {
                    var parametrosLogCorreos = {
                        enviar_credenciales: enviarCred ? 1 : 0,
                        tipo_envio: tipoEnvio,
                        asunto: asunto,
                        descripcion: descripcion,
                        per_id: perId,
                        personas: 'nomina'
                    };
                    enviar_Mail_Persona(parametrosLogCorreos);
                    $modal.modal('hide');
                }
            });
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
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function(response) {
                    let detalleHtml = '';
                    if (response.detalle && response.detalle.length > 0) {
                        detalleHtml = `
                            <hr style="margin:12px 0">
                            <details style="text-align:left">
                                <summary style="cursor:pointer;font-weight:600">Detalle de envíos</summary>
                                <ul style="margin-top:8px;padding-left:18px">
                                    ${response.detalle.map(d => `
                                        <li style="margin-bottom:6px">
                                            <span style="font-weight:600">${d.correo}</span>
                                            <span style="margin-left:6px;padding:1px 6px;border-radius:4px;font-size:12px;background:${d.estado === 'OK' ? '#e8f5e9' : '#ffebee'};color:${d.estado === 'OK' ? '#2e7d32' : '#c62828'}">${d.estado}</span><br>
                                            <span style="color:#666;font-size:13px">${d.mensaje}</span>
                                        </li>
                                    `).join('')}
                                </ul>
                            </details>`;
                    }
                    let mensaje = `
                        <div style="text-align:left;font-size:14px">
                            <div style="display:flex;justify-content:space-between;margin-bottom:6px">
                                <span>Total procesados</span><b>${response.total}</b>
                            </div>
                            <div style="display:flex;justify-content:space-between;margin-bottom:6px">
                                <span>Enviados</span><b style="color:#2e7d32">${response.enviados}</b>
                            </div>
                            <div style="display:flex;justify-content:space-between">
                                <span>Fallidos</span><b style="color:#c62828">${response.fallidos}</b>
                            </div>
                            ${detalleHtml}
                        </div>`;
                    Swal.fire({
                        icon: response.fallidos > 0 ? 'warning' : 'success',
                        title: 'Resultado del envío',
                        html: mensaje,
                        confirmButtonText: 'Aceptar',
                        width: 500,
                        allowOutsideClick: false
                    });
                },
                error: function() {
                    Swal.fire('Error', 'Error en la conexión', 'error');
                }
            });
        }
    });
</script>