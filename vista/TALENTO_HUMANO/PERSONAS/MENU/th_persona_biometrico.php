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
                        id: '<?php echo $id_persona; ?>', // Parámetro personalizado
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
        id = '<?= $id_persona ?>';
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
                        <input class="form-check-input" type="checkbox" value="1" id="cbx_enviar_credenciales"
                            checked>
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
                        Se enviarán las credenciales almacenadas para esta persona.
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
                            onclick=""><i class="bx bx-sync"></i> Sincronizar todas las Huella</button>
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