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
                        href = `../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registrar_personas&_id=${item._id}`;
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

                $('#myModal_espera').modal('hide');
                tr = '';
                $('#txt_recuperado').val(JSON.stringify(response));
                response.forEach(function(item, i) {
                    nombre = item.nombre;
                    nom = '';
                    nombre.forEach(function(item2,j){
                        nom+="<td>" + item2 + "</td>";
                    })
                    tr += "<tr><td>" + item.CardNo + "</td>"+nom+"</tr>";
                });

                $('#tbl_import').html(tr);
            },
            error: function(xhr, status, error) {
                
                $('#myModal_espera').modal('hide');
                console.log('Status: ' + status);
                console.log('Error: ' + error);
                console.log('XHR Response: ' + xhr.responseText);

                Swal.fire('', 'Error: ' + xhr.responseText, 'error').then(function(){
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
                                <div class="card-title d-flex align-items-center">

                                    <div class="" id="btn_nuevo">

                                        <a href="../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=th_registrar_personas"
                                            type="button" class="btn btn-success btn-sm">
                                            <i class="bx bx-plus me-0 pb-1"></i> Nuevo
                                        </a>

                                    </div>

                                    <button type="button" class="btn btn-primary btn-sm ms-1" onclick="import_bio()">
                                            <i class="bx bx-import me-0 pb-1"></i> Importar desde biometrico
                                    </button>

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
                                                <th>Nombre</th>
                                                <th>Cédula</th>
                                                <th>Correo</th>
                                                <th>Teléfono</th>
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

<div class="modal fade" id="importar_device" tabindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
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
                        <button class="btn btn-primary btn-sm" onclick="conectar_buscar()"><i class="bx bx-sync"></i>Conectar y buscar</button>
                    </div>
                    <div class="col-sm-12">
                        <div class="table-responsive" style="height: 250px;">
                            <table class="table table-striped" id="">
                                <thead>
                                    <tr>
                                        <th>Numero de tarjeta</th>
                                        <th>Nombre</th>
                                        <th>Nombre</th>
                                        <th>Nombre</th>
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