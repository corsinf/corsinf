<script>
    $(document).ready(function() {
        cargar_tabla_movimientos();
    });

    function cargar_tabla_movimientos() {
        txt_fecha_inicio_temp = $('#txt_fecha_inicio').val();
        txt_fecha_fin_temp = $('#txt_fecha_fin').val();

        var fecha_Hoy = new Date();
        var formato_Fecha = fecha_Hoy.getFullYear() + '-' + (fecha_Hoy.getMonth() + 1) + '-' + fecha_Hoy.getDate();


        txt_fecha_inicio = '';
        txt_fecha_fin = '';
        if (txt_fecha_inicio_temp == '' && txt_fecha_fin_temp == '') {
            txt_fecha_inicio = formato_Fecha;
            txt_fecha_fin = formato_Fecha;
        } else {
            txt_fecha_inicio = txt_fecha_inicio_temp;
            txt_fecha_fin = txt_fecha_fin_temp;
        }

        var parametros = {
            'id': <?= $_id ?>,
            'desde': txt_fecha_inicio,
            'hasta': txt_fecha_fin,
        }

        tabla_movimientos = $('#tabla_movimientos').DataTable($.extend({}, configuracion_datatable('Movimientos', 'movimientos'), {
            destroy: true,
            reponsive: false,

            language: {
                url: '../assets/plugins/datatable/spanish.json'
            },
            ajax: {
                url: '../controlador/ACTIVOS_FIJOS/detalle_articuloC.php?movimientos=true',
                type: 'POST',
                data: function(d) {
                    d.movimientos = true;
                    d.parametros = parametros
                },
                dataSrc: ''
            },
            columns: [{
                    data: 'ob'
                },
                {
                    data: null,
                    render: function(data, type, item) {
                        return fecha_nacimiento_formateada(item.fe) + ' / ' + obtener_hora_formateada_arr(item.fe);
                    }
                },
                {
                    data: 'codigo_ant'
                },
                {
                    data: 'dante'
                },
                {
                    data: 'codigo_nue'
                },
                {
                    data: 'dnuevo'
                },
                {
                    data: 'responsable'
                },
            ],
            order: [
                [1, 'desc']
            ]
        }));
    }

    function buscar_fechas() {
        if (tabla_movimientos) {
            tabla_movimientos.destroy(); // Destruir la instancia existente del DataTable
        }
        cargar_tabla();
    }

    //Cargar datos de custodio en inputs
    function datos_col_custodio(response) {
        $('#txt_nombre').val(response.person_nom);
        $('#txt_ci').val(response.person_ci);
        $('#txt_email').val(response.person_correo);
        $('#txt_puesto').val(response.PUESTO);
        $('#txt_unidad_p').val(response.unidad_org);
        $('#txt_id_custodio').val(response.id_person);

        $('#titulo').text('Editar custodio');
        $('#op').text('Editar');
    }

    function editar_custodio() {
        idc = $('#txt_id_custodio').val();
        location.href = '../vista/inicio.php?mod=2&acc=ge_registrar_personas&_id=' + idc;
    }
</script>



<div class="card-body">
    <ul class="nav nav-tabs nav-primary mb-0" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" data-bs-toggle="tab" href="#tab_movimientos" role="tab" aria-selected="false">
                <div class="d-flex align-items-center">
                    <div class="tab-icon"><i class='bx bx-bookmark-alt font-18 me-1'></i>
                    </div>
                    <div class="tab-title">Movimientos</div>
                </div>
            </a>
        </li>

        <?php if ($_SESSION['INICIO']['MODULO_SISTEMA'] != 2018) { ?>
            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#tab_custodio" role="tab" aria-selected="true">
                    <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class='bx bx-user font-18 me-1'></i>
                        </div>
                        <div class="tab-title"> Custodio </div>
                    </div>
                </a>
            </li>

            <li class="nav-item" role="presentation">
                <a class="nav-link" data-bs-toggle="tab" href="#tab_avaluos" role="tab" aria-selected="false">
                    <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class='bx bx-money font-18 me-1'></i>
                        </div>
                        <div class="tab-title">Avalúos</div>
                    </div>
                </a>
            </li>
        <?php } ?>



    </ul>

    <div class="tab-content pt-3">

        <!-- TAB movimientos -->
        <div class="tab-pane fade show active" id="tab_movimientos" role="tabpanel">
            <h3>Movimiento por articulo</h3>
            <div class="">
                <div class="row">
                    <div class="col-12">

                        <div class="row">

                            <div class="col-6">
                                <div class="card-title d-flex align-items-center">

                                    <!-- <h5 class="card-title fw-bold">Filtros</h5> -->
                                </div>
                            </div>

                            <!-- <div class="col-6 text-end">
                                <div id="contenedor_botones"></div>
                            </div> -->

                        </div>


                        <div class="row d-flex align-items-end">
                            <div class="col-md-2">
                                <label for="txt_fecha_inicio" class="form-label fw-bold">Desde</label>
                                <input type="date" class="form-control form-control-sm" id="txt_fecha_inicio" name="txt_fecha_inicio">
                            </div>

                            <div class="col-md-2">
                                <label for="txt_fecha_fin" class="form-label fw-bold">Hasta</label>
                                <input type="date" class="form-control form-control-sm" id="txt_fecha_fin" name="txt_fecha_fin">
                            </div>

                            <div class="col-md-8">
                                <!-- Etiqueta vacía para ocupar espacio y alinear el botón -->
                                <label class="form-label fw-bold d-block">&nbsp;</label>
                                <button class="btn btn-primary btn-sm px-3" onclick="buscar_fechas();" type="button">
                                    <i class='bx bx-search'></i> Buscar
                                </button>
                            </div>
                        </div>


                        <div class="row">

                        </div>
                    </div>
                </div>

                <section class="content pt-4">
                    <div class="container-fluid">
                        <div class="table-responsive">
                            <table class="table table-striped table-responsive" id="tabla_movimientos" style="width:100%">
                                <thead>
                                    <tr>
                                        <th width="30%">Proceso realizado</th>
                                        <th width="10%">Fecha Mov</th>
                                        <th width="10%">Cod Ant</th>
                                        <th width="15%">Dato Ant</th>
                                        <th width="10%">Cod Nuevo</th>
                                        <th width="15%">Dato Nuevo</th>
                                        <th width="10%">Responsable</th>
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

        <!-- TAB Custodio -->
        <div class="tab-pane fade" id="tab_custodio" role="tabpanel">
            <input type="hidden" name="txt_id_custodio" id="txt_id_custodio">
            <div class="row mb-col">
                <div class="col-sm-6">
                    <label for="txt_nombre">Nombre</label>
                    <input type="text" name="txt_nombre" id="txt_nombre" class="form-control form-control-sm" style="border: 0px;" readonly>
                </div>
                <div class="col-sm-6">
                    <label for="txt_ci">CI</label>
                    <input type="text" name="txt_ci" id="txt_ci" class="form-control form-control-sm" style="border: 0px;" readonly>
                </div>
            </div>

            <div class="row mb-col">
                <div class="col-sm-6">
                    <label for="txt_email">Correo</label>
                    <input type="email" name="txt_email" id="txt_email" class="form-control form-control-sm" style="border: 0px;" readonly>
                </div>
                <div class="col-sm-6">
                    <label for="txt_puesto">Puesto</label>
                    <input type="text" name="txt_puesto" id="txt_puesto" class="form-control form-control-sm" style="border: 0px;" readonly>
                </div>
            </div>

            <div class="row mb-col">
                <div class="col-sm-12">
                    <label for="txt_unidad_p">Unidad ORG</label>
                    <input type="text" name="txt_unidad_p" id="txt_unidad_p" class="form-control form-control-sm" style="border: 0px;" readonly>
                </div>
            </div>

            <div class="d-flex justify-content-start pt-2">
                <button type="button" class="btn btn-primary btn-sm px-4 m-0" onclick="editar_custodio()"><i class="bx bx-pencil"></i> Editar custodio</button>
            </div>

        </div>

        <!-- TAB avalúos -->
        <div class="tab-pane fade" id="tab_avaluos" role="tabpanel">
            <h3>Avalúos del artículo</h3>
            <div class="row">
                <div id="pnl_farmacologia">

                    <?php
                    if (
                        strtoupper($_SESSION['INICIO']['TIPO']) == 'EVALUADOR'
                        || strtoupper($_SESSION['INICIO']['TIPO']) == 'DBA'
                    ) { ?>
                        <div class="row pt-3">

                            <input type="hidden" name="txt_id_art_avaluo" id="txt_id_art_avaluo" value="<?= isset($_GET['id']) ? $_GET['id'] : '-1' ?>">

                            <div class="col-md-2">
                                <label for="txt_valor_art" class="form-label fw-bold">Valor <label style="color: red;">*</label> </label>
                                <input type="number" class="form-control form-control-sm" id="txt_valor_art" name="txt_valor_art">

                            </div>

                            <div class="col-md-4">
                                <label for="txt_obs_art" class="form-label fw-bold">Observación <label style="color: red;"></label> </label>
                                <input type="text" class="form-control form-control-sm" id="txt_obs_art" name="txt_obs_art">

                            </div>

                            <div class="col-md-2 mt-4 ">
                                <label for="agregarFila" class="form-label fw-bold"></label>
                                <button class="btn btn-primary" title="Agregar Avalúo" id="agregarFila" type="button"><i class='bx bx-plus me-0'></i> Agregar</button>
                            </div>
                        </div>

                    <?php  } ?>

                    <div class="row pt-3">
                        <div class="col-sm-6">
                            <div class="mb-2">

                                <style>
                                    /* Estilo adicional para cambiar el color de fondo al pasar el ratón por encima de una fila */

                                    /* Color de fondo al pasar el ratón por encima de una fila */
                                    #lista_avaluos tr:hover {
                                        background-color: #ddd;
                                    }
                                </style>

                                <table class="table table-bordered table-hover " id="lista_avaluos">
                                    <thead class="table-dark">
                                        <tr>
                                            <th width="20%">Fecha de Avalúo</th>
                                            <th width="20%">Valor</th>
                                            <th width="53%">Observación</th>
                                            <th width="5%">Acción</th>
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