<script src="../js/RED_CONSULTORIOS/operaciones_generales.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        consultarDatosExamenes();
        consultarDatosFeatExamenes();

        //Inicializar la tabla para la table intermedia que se muestra en el modal
        tbl_itee = $('#tbl_itee').DataTable();

        //examen()

        cargarSelect2FExamen('ddl_TIEE', '');

    });

    function consultarDatosExamenes(id = '') {
        tbl_examen = $('#tbl_examen').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            responsive: true,
            processing: true,
            ajax: {
                url: '../controlador/RED_CONSULTORIOS/cat_examenesC.php?listar=true',
                dataSrc: ''
            },
            columns: [{
                    data: null,
                    render: function(data, type, item, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: 'DESCRIPCION_EXAMEN',
                    render: function(data, type, item) {
                        return `<a href="#" onclick="mostrarITEE('${item.ID_EXAMEN}','${data}'); reajustarDataTable();"> 
                                    <u>${data.toUpperCase()}</u>
                                </a>`;
                    }
                },
                {
                    data: 'ESTADO_EXAMEN'
                },

            ],
            columnDefs: [{
                "targets": [], // Índice de la columna que quieres ocultar (Edad en este caso)
                "visible": false,
            }],
            order: [
                [0, 'asc']
            ],
        });
    }

    function consultarDatosFeatExamenes(id = '') {
        tbl_feat_examen = $('#tbl_feat_examen').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            responsive: true,
            processing: true,
            ajax: {
                url: '../controlador/RED_CONSULTORIOS/cat_feat_examenesC.php?listar=true',
                dataSrc: ''
            },
            columns: [{
                    data: null,
                    render: function(data, type, item, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: 'DESCRIPCION_FEAT_EXAMEN'
                },
                {
                    data: 'INPUT_FEAT_EXAMEN'
                },

            ],
            columnDefs: [{
                "targets": [], // Índice de la columna que quieres ocultar (Edad en este caso)
                "visible": false,
            }],
            order: [
                [0, 'asc']
            ],
        });
    }

    function consultarDatosITEE(id_itee = '') {
        if (tbl_itee) {
            tbl_itee.destroy();
        }

        tbl_itee = $('#tbl_itee').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json'
            },
            responsive: true,
            processing: true,
            ajax: {
                url: '../controlador/RED_CONSULTORIOS/interm_examen_featExamenC.php?listar=true',
                type: 'POST',
                data: {
                    t_examen_desc: id_itee
                },
                dataSrc: ''
            },
            columns: [{
                    data: null,
                    render: function(data, type, item, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: 'ex_descripcion'
                },
                {
                    data: 'fex_descripcion'
                }
            ],
            columnDefs: [{
                "targets": [0, 1], // Índice de la columna que quieres ocultar (Edad en este caso)
                "visible": false,
            }],
            order: [
                [0, 'asc']
            ]
        });
    }

    //Para el examen
    function insertarExamen() {
        let txt_id_examen = $('#txt_id_examen').val();
        let txt_examen = $('#txt_examen').val();

        let parametros = {
            'txt_id_examen': txt_id_examen,
            'txt_examen': txt_examen,
        };

        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/RED_CONSULTORIOS/cat_examenesC.php?insertar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        tbl_examen.ajax.reload();
                        $('#modal_examen').modal('hide');
                        $('#txt_examen').val('');
                    });
                } else if (response == -2) {
                    Swal.fire('', 'Exámen ya registrado', 'error');
                }
            },
            error: function(xhr, status, error) {
                // Capturar el mensaje de error del servidor
                var errorMessage = xhr.status + ' - ' + xhr.statusText;
                Swal.fire('', 'Error: ' + errorMessage, 'error');
            }
        });
    }

    //Para las caracterisiticas del examen 
    function insertarFExamen() {
        let txt_feat_id = $('#txt_feat_id').val();
        let txt_feat_examen = $('#txt_feat_examen').val();
        let txt_feat_input = $('#txt_feat_input').val();


        let parametros = {
            'txt_feat_id': txt_feat_id,
            'txt_feat_examen': txt_feat_examen,
            'txt_feat_input': txt_feat_input,
        };



        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/RED_CONSULTORIOS/cat_feat_examenesC.php?insertar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        tbl_feat_examen.ajax.reload();
                        $('#modal_feat_examen').modal('hide');
                        $('#txt_feat_examen').val('');
                        $('#txt_feat_input').val('');
                    });
                } else if (response == -2) {
                    Swal.fire('', 'Input ya registrado', 'error');
                }
            },
            error: function(xhr, status, error) {
                // Capturar el mensaje de error del servidor
                var errorMessage = xhr.status + ' - ' + xhr.statusText;
                Swal.fire('', 'Error: ' + errorMessage, 'error');
            }
        });
    }

    //Para las caracterisiticas del examen 
    function insertarIEExamen() {
        let txt_id_itee = $('#txt_id_itee').val();
        let txt_id_examen_itee = $('#txt_id_examen_itee').val();
        let txt_feat_id_itee = $('#ddl_TIEE').val();

        let parametros = {
            'txt_id_itee': txt_id_itee,
            'txt_id_examen_itee': txt_id_examen_itee,
            'txt_feat_id_itee': txt_feat_id_itee,
        };


        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/RED_CONSULTORIOS/interm_examen_featExamenC.php?insertar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                if (response == 1) {
                    Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                        tbl_itee.ajax.reload();
                    });
                } else if (response == -2) {
                    Swal.fire('', 'Caracteristica del exámen ya registrada', 'error');
                }
            },
            error: function(xhr, status, error) {
                // Capturar el mensaje de error del servidor
                var errorMessage = xhr.status + ' - ' + xhr.statusText;
                Swal.fire('', 'Error: ' + errorMessage, 'error');
            }
        });
    }



    /**
     * Funciones 
     */

    //Para cargar los datos en el modal y mostrar la tabla intermedia
    function mostrarITEE(id_examen, id_itee) {
        consultarDatosITEE(id_itee);

        $('#txt_t_examen_disabled').val(id_itee);
        $('#titulo_ITEE_desc').html(id_itee);

        $('#txt_id_examen_itee').val(id_examen);

        $('#modal_ver_examen_completo').modal('show');
    }

    function cargarSelect2FExamen(ddl, input_descripcion) {

        $('#' + ddl).select2({
                language: {
                    inputTooShort: function() {
                        return "Por favor ingresa 3 o más caracteres";
                    },
                    noResults: function() {
                        return "No se encontraron resultados";
                    },
                    searching: function() {
                        return "Buscando...";
                    },
                    errorLoading: function() {
                        return "No se encontraron resultados";
                    }
                },
                //minimumInputLength: 3,
                dropdownParent: $('#modal_ver_examen_completo'),
                placeholder: '-- Seleccione --',
                width: '100%',
                ajax: {
                    url: '../controlador/RED_CONSULTORIOS/cat_feat_examenesC.php?buscar=true',
                    dataType: 'json',
                    delay: 250,
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            })
            .off('select2:select')
            .on('select2:select', function(e) {
                var data = e.params.data.data;

                //$('#' + input_descripcion).val(data.sa_cie10_codigo);

                //console.log(data);
                // Para verificar los datos en la consola
            });

    }

    //Para hacer pruebas
    function examen() {
        $.ajax({
            data: {
                id: ''
            },
            url: '../controlador/RED_CONSULTORIOS/cat_feat_examenesC.php?listar=true',
            type: 'post',
            dataType: 'json',

            success: function(response) {
                console.log(response);

            }
        });
    }
</script>

<div class="page-wrapper">
    <div class="page-content">

        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Configuración</div>
            <?php
            // print_r($_SESSION['INICIO']);die();

            ?>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Configuración
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">

            <div class="col-12">
                <div class="card border-top border-0 border-4 border-primary">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-center">
                            <div><i class="bx bxs-user me-1 font-22 text-primary"></i>
                            </div>
                            <h5 class="mb-0 text-primary">Configuración</h5>
                        </div>
                        <hr>

                        <div class="content">
                            <!-- Content Header (Page header) -->
                            <br>

                            <section class="content">
                                <div class="container-fluid">
                                    <div id="hola"></div>

                                    <div>
                                        <ul class="nav nav-pills mb-3" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link active" data-bs-toggle="pill" href="#nav-t-examen" role="tab" aria-selected="true" onclick="reajustarDataTable();">
                                                    <div class="d-flex align-items-center">
                                                        <div class="tab-icon"><i class='bx bx-book font-18 me-1'></i>
                                                        </div>
                                                        <div class="tab-title">Exámenes</div>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link" data-bs-toggle="pill" href="#nav-examen" role="tab" aria-selected="false" onclick="reajustarDataTable();">
                                                    <div class="d-flex align-items-center">
                                                        <div class="tab-icon"><i class='bx bx-book-content font-18 me-1'></i>
                                                        </div>
                                                        <div class="tab-title">Carácteristicas del Exámen</div>
                                                    </div>
                                                </a>
                                            </li>

                                        </ul>
                                        <div class="tab-content" id="pills-tabContent">
                                            <div class="tab-pane fade show active" id="nav-t-examen" role="tabpanel">
                                                <hr>
                                                <div class="row">
                                                    <div class="col-sm-12" id="btn_nuevo">
                                                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal_examen"><i class="bx bx-plus"></i> Nuevo</button>
                                                    </div>
                                                </div>

                                                <br>

                                                <h2>Exámenes</h2>

                                                <div class="table-responsive">
                                                    <table class="table table-striped responsive" id="tbl_examen" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th width="5%">#</th>
                                                                <th>Nombre</th>
                                                                <th>Accion</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="nav-examen" role="tabpanel">
                                                <hr>
                                                <div class="row">
                                                    <div class="col-sm-12" id="btn_nuevo">
                                                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modal_feat_examen"><i class="bx bx-plus"></i> Nuevo</button>
                                                    </div>
                                                </div>

                                                <br>

                                                <h2>Carácteristicas del Exámen</h2>

                                                <div class="table-responsive">
                                                    <table class="table table-striped responsive" id="tbl_feat_examen" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th width="5%">#</th>
                                                                <th>Nombre</th>
                                                                <th>Input</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>






                                </div><!-- /.container-fluid -->
                            </section>
                            <!-- /.content -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
</div>



<div class="modal" id="modal_examen" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">

                <div class="row">
                    <div class="col-12">
                        <label for="sa_pac_tabla">Exámen <label class="text-danger">*</label></label>
                        <input type="text" class="form-control form-control-sm" id="txt_examen" name="txt_examen" oninput="textoMinusculas(this)">
                        <span class="font-10 text-danger">*El texto se guardará en minúsculas.</span>
                    </div>
                </div>

                <input type="hidden" name="txt_id_examen" id="txt_id_examen">

                <div class="row pt-3">
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-success btn-sm" onclick="insertarExamen()"><i class="bx bx-save"></i> Agregar</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal" id="modal_feat_examen" abindex="-1" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">

                <div class="row">
                    <div class="col-12">
                        <label for="sa_pac_tabla">Caracteristicas del Exámen <label class="text-danger">*</label></label>
                        <input type="text" class="form-control form-control-sm" id="txt_feat_examen" name="txt_feat_examen">
                    </div>
                </div>

                <div class="row pt-3">
                    <div class="col-12">
                        <label for="sa_pac_tabla">Nombre del input <label class="text-danger">*</label></label>
                        <input type="text" class="form-control form-control-sm" id="txt_feat_input" name="txt_feat_input">
                    </div>
                </div>

                <input type="hidden" name="txt_feat_id" id="txt_feat_id">

                <div class="row pt-3">
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-success btn-sm" onclick="insertarFExamen()"><i class="bx bx-save"></i> Agregar</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal para mostrar caracteristicas completas -->
<button hidden type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_ver_examen_completo">Modal caracteristicas completas</button>
<!-- Para poner los tipos de examen y examen -->
<div class="modal fade" id="modal_ver_examen_completo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Exámen - <label id="titulo_ITEE_desc" class="text-success text-uppercase"></label></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="row pt-3">
                    <div class="col-2">

                    </div>

                    <input type="hidden" id="txt_id_itee" name="txt_id_itee">

                    <div class="col-8">
                        <div class="row">
                            <div class="col-12">
                                <label for="sa_pac_tabla">Exámen <label class="text-danger">*</label></label>
                                <input type="text" class="form-control form-control-sm" id="txt_t_examen_disabled" name="txt_t_examen_disabled" disabled>
                                <input type="hidden" id="txt_id_examen_itee" name="txt_id_examen_itee">
                            </div>
                        </div>

                        <div class="row pt-3">
                            <div class="col-12">
                                <label for="" class="form-label fw-bold">Caracteristica del Exámen <label style="color: red;">*</label> </label>
                                <select name="ddl_TIEE" id="ddl_TIEE" class="form-select form-select-sm">
                                    <option value="">Seleccione</option>
                                </select>
                                <input type="hidden" id="ddl_TIEE_temp">
                            </div>
                        </div>

                        <div class="row pt-3">
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-success btn-sm" onclick="insertarIEExamen()"><i class="bx bx-save"></i> Agregar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row pt-3">
                    <div class="col-2">

                    </div>
                    <div class="col-8">
                        <div class="table-responsive">
                            <table class="table table-striped" id="tbl_itee" style="width:100%">
                                <thead>
                                    <tr>
                                        <th width="5%">Codigo</th>
                                        <th>Nombre</th>
                                        <th>Accion</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>