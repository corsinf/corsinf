<script>
    $(document).ready(function() {
        movimientos();
    });

    function movimientos() {
        var table = '';
        var desde = $('#txt_desde').val();
        var hasta = $('#txt_hasta').val();
        if (desde != '' && hasta == '' || desde == '' && hasta != '') {
            Swal.fire('Rango de fecha no valido', 'Seleccione fechas correctas', 'info');
        }
        var parametros = {
            'id': <?= $_id ?>,
            'desde': desde,
            'hasta': hasta,
        }
        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/ACTIVOS_FIJOS/detalle_articuloC.php?movimientos=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                $.each(response, function(i, item) {
                    //console.log(item);
                    table += "<tr><td>" + item.ob + "</td><td style='white-space: nowrap;'>" + (item.fe) + "</td><td>" + item.codigo_ant + "</td><td>" + item.dante + "</td><td>" + item.codigo_nue + "</td><td>" + item.dnuevo + "</td><td>" + item.responsable + "</td></tr>"
                });
                $('#table_contenido').html(table);


            }
        });
    }

    function editar_custodio() {
        idc = $('#id').val();
        location.href = '../vista/custodio_detalle.php?id=' + idc;
    }
</script>



<div class="card-body">
    <ul class="nav nav-tabs nav-primary mb-0" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" data-bs-toggle="tab" href="#tab_custodio" role="tab" aria-selected="true">
                <div class="d-flex align-items-center">
                    <div class="tab-icon"><i class='bx bx-user font-18 me-1'></i>
                    </div>
                    <div class="tab-title"> Custodio </div>
                </div>
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" data-bs-toggle="tab" href="#tab_movimientos" role="tab" aria-selected="false">
                <div class="d-flex align-items-center">
                    <div class="tab-icon"><i class='bx bx-bookmark-alt font-18 me-1'></i>
                    </div>
                    <div class="tab-title">Movimientos</div>
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
    </ul>

    <div class="tab-content pt-3">

        <!-- TAB Custodio -->
        <div class="tab-pane fade show active" id="tab_custodio" role="tabpanel">
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

        <!-- TAB movimientos -->
        <div class="tab-pane fade" id="tab_movimientos" role="tabpanel">
            <h3>Movimiento por articulo</h3>
            <div class="row">
                <br>
                <div class="col-sm-2">
                    <b>Desde</b>
                    <input type="date" name="txt_desde" id="txt_desde" class="form-control form-control-sm">
                </div>
                <div class="col-sm-2">
                    <b>Hasta</b>
                    <input type="date" name="txt_hasta" id="txt_hasta" class="form-control form-control-sm">
                </div>
                <div class="col-sm-8"><br>
                    <button class="btn btn-primary btn-sm" onclick="movimientos()"><i class="bx bx-search"></i> Buscar</button>
                    <button class="btn btn-default btn-sm" id="excel_movimientos_art"><i class="bx bx-file"></i> Informe</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <th>Proceso realizado</th>
                            <th style="white-space: nowrap;">Fecha Mov</th>
                            <th style="white-space: nowrap;">Cod ante.</th>
                            <th style="white-space: nowrap;">Dato anter.</th>
                            <th style="white-space: nowrap;">Cod nuevo</th>
                            <th style="white-space: nowrap;">Dato nuevo</th>
                            <th>Responsable</th>
                        </thead>
                        <tbody id="table_contenido">
                            <tr>
                                <td colspan="3">NO se a encontado movimientos de este articulo</td>
                            </tr>
                        </tbody>
                    </table>

                </div>
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