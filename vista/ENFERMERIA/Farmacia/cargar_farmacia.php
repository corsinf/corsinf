<?php //include('../cabeceras/header.php'); 
?>
<script type="text/javascript">
    $(document).ready(function() {
        //log_activos();

        $("#btn_carga").on('click', function() {
            var id = $('#ddl_opcion').val();
            $('#txt_opcion').val(id);
            var fi = $('#file').val();

            if (id != '' && fi != '') {

                var formData = new FormData(document.getElementById("form_img"));
                $.ajax({
                    url: '../controlador/SALUD_INTEGRAL/cargar_datos_saludC.php?subir_archivo_server=true',
                    type: 'post',
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    // beforeSend: function () {
                    //        $("#foto_alumno").attr('src',"../img/gif/proce.gif");
                    //     },
                    success: function(response) {
                        if (response == 1) {
                            //cargar_datos();
                            Swal.fire('Formato del archivo CORRECTO', 'asegurese que el archivo sea (.cvs)', 'error');
                        } else {
                            Swal.fire('Formato del archivo incorrecto', 'asegurese que el archivo sea (.cvs)', 'error');
                        }
                    }
                });
            } else {
                Swal.fire('', 'Destino o archivo no seleccionados', 'error');
            }
        });
    });
</script>

<script type="text/javascript">
    function cargar_datos() {
        var id = $('#ddl_opcion').val();
        var parametros = {
            'id': id,
            'tip': $('#rbl_primera').prop('checked'),
        };
        $('#myModal').modal('show');
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/SALUD_INTEGRAL/cargar_datos_saludC.php?ejecutar_sp=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                console.log(response);
                if (response == 1) {
                    Swal.fire('carga completada', '', 'success').then(function() {
                        $('#myModal').modal('hide');
                        // console.log(id);                  
                        log_activos()
                    });
                } else {
                    Swal.fire('No se pudo completar', 'Asegurese que los datos esten en los formatos correctos y sin (;) punto y comas ó revise la cantidad de items en el archivo', 'error').then(function() {

                        $('#myModal').modal('hide');
                    });
                }
            }

        });
    }

    function opcion_carga() {
        var op = $('#ddl_opcion').val();
        if (op == 1) {
            $('#lbl_check').css('display', 'none');
        } else {
            $('#lbl_check').css('display', 'block');
        }
    }

    function log_activos() {
        parametros = {
            'fecha': $('#txt_fecha').val(),
            'accion': $('#txt_accion').val(),
            'intento': $('#txt_intento').val(),
            'estado': $('input[name="rbl_estado"]:checked').val(),
        }
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/SALUD_INTEGRAL/cargar_datos_saludC.php?log_activos=true',
            type: 'post',
            dataType: 'json',
            beforeSend: function() {
                // $("#foto_alumno").attr('src',"../img/gif/proce.gif");
                $('#tbl_datos').html('<tr class="text-center"><td colspan="6"><img src="../img/de_sistema/loader_sistema.gif" style="width:10%"></td></tr>');
            },
            success: function(response) {

                $('#tbl_datos').html(response);
                console.log(response);
            }

        });
    }

    function leer_datos() {
        $.ajax({
            // data:  {parametros:parametros},
            url: '../controlador/SALUD_INTEGRAL/carga_datos/cargar_controlador.php?leer=true',
            type: 'post',
            dataType: 'json',
            // beforeSend: function () {
            //        // $("#foto_alumno").attr('src',"../img/gif/proce.gif");
            //   $('#tbl_datos').html('<tr class="text-center"><td colspan="6"><img src="../img/de_sistema/loader_sistema.gif" style="width:10%"></td></tr>');
            // },
            success: function(response) {

                $('#tbl_datos').html(response);
                console.log(response);
            }

        });
    }
</script>

<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Carga de datos</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">carga de datos</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <div class="row">
            <div class="col-xl-12 mx-auto">
                <hr>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <form enctype="multipart/form-data" id="form_img" method="post">
                                    <input type="hidden" id="txt_opcion" name="txt_opcion">
                                    <input type="file" name="file" id="file" class="form-control">
                                    <p><b>Nota:</b> El archivo debera tener un maximo de 10000 items</p>
                                </form>
                            </div>
                            <div class="col-sm-3">
                                <select class="form-control form-select" id="ddl_opcion" onchange="opcion_carga()">
                                    <option value="">-- Seleccione destino de datos --</option>
                                    <option value="1">Cargar Medicamentos</option>
                                    <option value="2">Cargar Insumos</option>
                                </select>
                                <label id="lbl_check"><input type="checkbox" name="rbl_primera" id="rbl_primera"> Como primera vez</label>
                            </div>
                            <div class="col-sm-3">
                                <button class="btn btn-sm btn-primary" id="btn_carga">Actualizar archivos</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <h5> Log de carga</h5>
                            <div class="col-sm-12">
                                <form id="form_filtros">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <b>Accion</b>
                                            <input type="type" class="form-control form-control-sm" placeholder="Accion realizada" name="txt_accion" id="txt_accion">
                                        </div>
                                        <div class="col-sm-2">
                                            <b>Fecha</b>
                                            <input type="date" class="form-control form-control-sm" id="txt_fecha" name="txt_fecha">
                                        </div>
                                        <div class="col-sm-1">
                                            <b>Intento</b>
                                            <input type="number" class="form-control form-control-sm" value="" id="txt_intento" name="txt_intento">
                                        </div>
                                        <div class="col-sm-3">
                                            <b>Estado</b><br>
                                            <label><input type="radio" name="rbl_estado" checked value="" onclick="log_activos()"> Todos</label>
                                            <label><input type="radio" name="rbl_estado" value="1" onclick="log_activos()"> Subidos</label>
                                            <label><input type="radio" name="rbl_estado" value="-1" onclick="log_activos()"> Errores</label>
                                        </div>
                                        <div class="col-sm-2">
                                            <button type="button" class="btn btn-primary btn-sm" onclick="log_activos()">Buscar</button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" id="excel_log">Informe</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-sm-12">
                                <table class="table table-sm">
                                    <thead>
                                        <th>Detalle log</th>
                                        <th>Fecha</th>
                                        <th>intento</th>
                                        <th>Accion</th>
                                        <th>Estado</th>
                                        <th>Encargado</th>
                                    </thead>
                                    <tbody id="tbl_datos">
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div id="cargar">
                    <div class="text-center"><img src="../img/de_sistema/loader_sistema.gif" width="100" height="100">SUBIENDO DATOS</div>
                </div>
                <div>
                    <div class="progress-group" id="loader">
                        <span class="progress-number" id="pro_partes"><b>1/?</b></span>
                        <div class="progress sm">
                            <div class="progress-bar progress-bar-aqua" style="width: 1%" id="loader_"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php //include('../cabeceras/footer.php'); 
?>