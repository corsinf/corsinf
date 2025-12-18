<?php
$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);


?>

<script src="../js/GENERAL/operaciones_generales.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    tbl_personas = $('#tbl_personas').DataTable($.extend({}, configuracion_datatable('Personas', 'personas'), {
        reponsive: true,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
        },
        ajax: {
            url: '../controlador/TALENTO_HUMANO/th_personasC.php?listar=true',
            dataSrc: ''
        },
        columns: [{
                data: null,
                render: function(data, type, item) {
                    href =
                        `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registrar_persona_postulate&_id_per=${item.th_per_id}&_id=${item.id_comunidad}`;
                    btns =
                        `<a href="${href}" class="btn btn-xs btn-primary" title="CV"><i class="bx bxs-user-pin fs-6 me-0"></i></a>`;
                    return btns;
                }
            },

            {
                data: null,
                render: function(data, type, item) {
                    href =
                        `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registrar_personas&_id=${item.th_per_id}`;
                    return `<a href="${href}"><u>${item.primer_apellido} ${item.segundo_apellido} ${item.primer_nombre} ${item.segundo_nombre}</u></a>`;
                }
            },
            {
                data: 'cedula'
            },

            {
                // data: null,
                // render: function(data, type, item) {
                //     return `<button type="button" class="btn btn-primary btn-xs" onclick=""><i class="lni lni-spinner-arrow fs-7 me-0 fw-bold"></i></button>`;
                // }
                data: 'correo'
            },
            {
                data: 'telefono_1'
            },
            {
                data: 'th_dep_nombre'
            },
        ],
        order: [
            [1, 'asc']
        ],
    }));
});

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

function import_bio() {
    dispositivos();
    $('#importar_device').modal('show');
}

function conectar_buscar() {
    var parametros = {
        'id': $('#ddl_dispositivos').val(),
    };

    $('#myModal_espera').modal('show');
    $('#lbl_msj_espera').text("Conectando y Sincronizando");
    $.ajax({
        data: {
            parametros: parametros
        },
        url: '../controlador/TALENTO_HUMANO/th_personasC.php?conectar_buscar=true',
        type: 'post',
        dataType: 'json',

        success: function(response) {
            console.log(response);

            $('#myModal_espera').modal('hide');
            tr = '';
            $('#txt_recuperado').val(JSON.stringify(response));
            response.forEach(function(item, i) {
                nombre = item.nombre;
                tr += "<tr><td>" + item.CardNo + "</td><td>" + nombre + "</td></tr>";
            });

            $('#tbl_import').html(tr);
        },
        error: function(xhr, status, error) {

            $('#myModal_espera').modal('hide');
            console.log('Status: ' + status);
            console.log('Error: ' + error);
            console.log('XHR Response: ' + xhr.responseText);

            Swal.fire('', 'Error: ' + xhr.responseText, 'error').then(function() {
                $('#myModal_espera').modal('hide');
            });
        }
    });
}

function conectar_buscar_() {
    var parametros = {
        'id': $('#ddl_dispositivos').val(),
    };

    $('#myModal_espera').modal('show');
    $('#lbl_msj_espera').text("Conectando y Sincronizando");
    $.ajax({
        data: {
            parametros: parametros
        },
        url: '../controlador/TALENTO_HUMANO/th_personasC.php?conectar_buscar_=true',
        type: 'post',
        dataType: 'json',

        success: function(response) {
            console.log(response);

            $('#myModal_espera').modal('hide');
            tr = '';
            $('#txt_recuperado').val(JSON.stringify(response));
            response.forEach(function(item, i) {
                nombre = item.FullName;
                card = '';
                if (item.CardList != '') {
                    card = item.CardList.Card[0].CardNo;
                }
                tr += "<tr><td>" + card + "</td><td>" + nombre + "</td></tr>";
            });

            $('#tbl_import').html(tr);
        },
        error: function(xhr, status, error) {

            $('#myModal_espera').modal('hide');
            console.log('Status: ' + status);
            console.log('Error: ' + error);
            console.log('XHR Response: ' + xhr.responseText);

            Swal.fire('', 'Error: ' + xhr.responseText, 'error').then(function() {
                $('#myModal_espera').modal('hide');
            });
        }
    });
}

function importar() {
    var parametros = {
        'datos': $('#txt_recuperado').val(),
    };

    // $('#myModal_espera').modal('show');
    // $('#lbl_msj_espera').text("Conectando y Sincronizando");
    $.ajax({
        data: {
            parametros: parametros
        },
        url: '../controlador/TALENTO_HUMANO/th_personasC.php?guardarImport=true',
        type: 'post',
        dataType: 'json',

        success: function(response) {
            if (response.msj == '') {
                Swal.fire('Registros Importados', '', 'success');
            } else {
                Swal.fire('Registros Importados', response.msj, 'info');
            }

            tbl_personas.ajax.reload(null, false);
            $('#importar_device').modal('hide');
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
</script>

<script>
$(function() {

    const $cbx = $('#cbx_enviar_credenciales');
    const $contInputs = $('#cont_inputs_mensaje');
    const $infoCred = $('#info_credenciales');
    const $modal = $('#modal_mensaje');
    const $btnEnviar = $('#btn_enviar_mensaje');

    function actualizarVista() {
        if ($cbx.is(':checked')) {
            $contInputs.hide();
            $infoCred.show();
        } else {
            $contInputs.show();
            $infoCred.hide();
        }
    }

    // Al abrir el modal
    $modal.on('show.bs.modal', actualizarVista);

    // Cambio checkbox
    $cbx.on('change', actualizarVista);

    // Click botón enviar
    $btnEnviar.on('click', enviarMensaje);

    function enviarMensaje() {

        const enviarCred = $cbx.is(':checked');
        const asunto = $.trim($('#txt_asunto').val());
        const descripcion = $.trim($('#txt_descripcion').val());

        // VALIDAR SOLO SI NO ES ENVÍO DE CREDENCIALES
        if (!enviarCred) {
            if (!asunto) {
                Swal.fire('Advertencia', 'Ingresa el asunto', 'warning');
                $('#txt_asunto').focus();
                return;
            }
            if (!descripcion) {
                Swal.fire('Advertencia', 'Ingresa la descripción', 'warning');
                $('#txt_descripcion').focus();
                return;
            }
        }

        const parametrosLogCorreos = {
            enviar_credenciales: enviarCred ? 1 : 0,
            asunto: asunto,
            descripcion: descripcion,
            per_id: '<?= $_id ?? '' ?>'
        };

        enviar_Mail_Persona(parametrosLogCorreos);
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

                if (!response || response.total === undefined) {
                    Swal.fire('Error', 'Respuesta inválida del servidor', 'error');
                    return;
                }

                let mensaje = `
                    <b>Total:</b> ${response.total}<br>
                    <b>Enviados:</b> ${response.enviados}<br>
                    <b>Fallidos:</b> ${response.fallidos}
                `;

                if (response.fallidos > 0 && response.detalle) {
                    mensaje += '<hr><b>Correos con error:</b><br>';
                    response.detalle.forEach(item => {
                        if (item.estado === 'ERROR') {
                            mensaje += `• ${item.correo}<br>`;
                        }
                    });
                }

                Swal.fire({
                    icon: response.fallidos > 0 ? 'warning' : 'success',
                    title: 'Resultado del envío',
                    html: mensaje
                });

                $modal.modal('hide');
            },

            error: function(xhr, status, error) {
                Swal.fire('Error', 'Error en la conexión: ' + error, 'error');
            }
        });
    }

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
                                <div class="card-title">
                                    <div class="d-flex flex-wrap align-items-center gap-2" id="btn_nuevo">

                                        <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registrar_personas"
                                            class="btn btn-success btn-sm">
                                            <i class="bx bx-plus me-1"></i> Nuevo
                                        </a>

                                        <button type="button" class="btn btn-primary btn-sm" onclick="import_bio()">
                                            <i class="bx bx-import me-1"></i> Importar desde biométrico
                                        </button>

                                        <a href="javascript:void(0)" class="btn btn-success btn-sm"
                                            data-bs-toggle="modal" data-bs-target="#modal_mensaje">
                                            <i class="bx bx-envelope me-1"></i> Enviar Mensaje
                                        </a>

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

<div class="modal fade" id="modal_mensaje" tabindex="-1" aria-labelledby="modal_mensaje_label" aria-hidden="true">
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
                    <button type="button" id="btn_enviar_mensaje" class="btn btn-primary"
                        onclick="enviarMensaje()">Enviar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="importar_device" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Importar desde Dispositivo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" name="txt_recuperado" id="txt_recuperado">
                    <div class="col-sm-12 mb-2">
                        <select class="form-select" id="ddl_dispositivos" name="ddl_dispositivos">
                            <option value="">Seleccione Dispositivo</option>
                        </select>
                    </div>
                    <div class="col-sm-12 text-end">
                        <button class="btn btn-primary btn-sm" onclick="conectar_buscar()"><i
                                class="bx bx-sync"></i>Conectar y buscar</button>
                        <button class="btn btn-primary btn-sm" onclick="conectar_buscar_()"><i
                                class="bx bx-sync"></i>HIKC</button>
                    </div>
                    <div class="col-sm-12">
                        <div class="table-responsive" style="height: 250px;">
                            <table class="table table-striped" id="">
                                <thead>
                                    <tr>
                                        <th>Numero de tarjeta</th>
                                        <th>Nombre</th>
                                    </tr>
                                </thead>
                                <tbody id="tbl_import">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button class="btn btn-primary btn-sm" onclick="importar()"><i class="bx bx-sync"></i>Importar</button>
            </div>
        </div>
    </div>
</div>