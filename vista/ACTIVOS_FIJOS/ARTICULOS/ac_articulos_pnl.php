<script>
    function cargar_articulo_editar_pnl(data) {
        $('#cbx_kit').prop('checked', data.es_kit === "1");
        cargar_tipo_articulo(data.id_tipo_articulo);
        // console.log(data.id_tipo_articulo);

        $('input[name="rbl_asset"][value="' + data.longitud_rfid + '"]').prop('checked', true);

        // Asignar valores a los campos de texto
        $('#txt_descripcion').val(data.nom);
        $('#txt_descripcion_2').val(data.des);

        $('#txt_rfid').val(data.rfid);
        $('#txt_tag_serie').val(data.tag_s);
        $('#txt_tag_anti').val(data.ant);


        $('#txt_subno').val(data.subnum);
        $('#txt_cant').val(data.cant);
        $('#txt_valor').val(data.prec || 0);
        $('#txt_maximo').val(data.max);
        $('#txt_minimo').val(data.min);
        $('#txt_modelo').val(data.mod);
        $('#txt_serie').val(data.ser);

        $('#txt_carac').val(data.carac);
        $('#txt_observacion').val(data.obs);


        // Asignar valores a los campos dropdown
        $('#ddl_custodio').append($('<option>', {
            value: data.id_person,
            text: data.person_nom,
            selected: true
        }));

        $('#ddl_localizacion').append($('<option>', {
            value: data.id_loc,
            text: data.c_loc,
            selected: true
        }));

        $('#ddl_unidad').append($('<option>', {
            value: data.id_unidad_medida,
            text: data.unidad_medida,
            selected: true
        }));

        $('#ddl_familia').append($('<option>', {
            value: data.id_fam,
            text: data.familia,
            selected: true
        }));

        $('#ddl_subfamilia').append($('<option>', {
            value: data.id_subfam,
            text: data.subfamilia,
            selected: true
        }));

        // 
        $('#ddl_marca').append($('<option>', {
            value: data.id_mar,
            text: data.marca,
            selected: true
        }));

        $('#ddl_estado').append($('<option>', {
            value: data.id_est,
            text: data.estado,
            selected: true
        }));

        $('#ddl_genero').append($('<option>', {
            value: data.id_gen,
            text: data.genero,
            selected: true
        }));

        $('#ddl_color').append($('<option>', {
            value: data.id_col,
            text: data.color,
            selected: true
        }));

        $('#ddl_clase_mov').append($('<option>', {
            value: data.id_clase_movimiento,
            text: data.movimiento,
            selected: true
        }));

        $('#ddl_proyecto').append($('<option>', {
            value: data.id_pro,
            text: data.proyecto,
            selected: true
        }));

        // Asignar valores a los otros campos
        $('#txt_company').val(data.companycode);
        $('#txt_resp_cctr').val(data.resp_cctr);
        $('#txt_centro_costos').val(data.centro_costos);
        $('#txt_funds_ctr_apc').val(data.funds_ctr_apc);
        $('#txt_profit_ctr').val(data.profit_ctr);

        $('#txt_compra').val(fecha_formateada(data.fecha_contabilizacion));
        $('#txt_fecha').val(fecha_formateada(data.fecha_referencia));

        // SAP
        $('#lbl_sap_col').text('Código:' + data.c_col);
        $('#lbl_sap_est').text('Código:' + data.c_est);
        $('#lbl_sap_mar').text('Código:' + data.c_mar);
        $('#lbl_sap_pro').text('Código:' + data.c_pro);
        $('#lbl_sap_gen').text('Código:' + data.c_gen);
        $('#lbl_sap_loc').text('Código:' + data.loc_nom);
        $('#lbl_sap_custodio').text('Código:' + data.person_ci);
        $('#txt_ac_ait_sku').val(data.tag_s);

        if (data.es_kit == 1) {
            $('#cbx_kit').prop('checked', true).prop('disabled', true);
            $('#nav_kit_interno').show();
        } else {
            $('#cbx_kit_cointainer').show();
            $('#nav_kit_interno').hide();
        }

        if (data.es_it == 1) {
            $('#nav_detalle_it').show();
            $('#cbx_detalle_it').prop('checked', true).prop('disabled', true);
        } else {
            $('#nav_detalle_it').hide();
            $('#cbx_detalle_it_cointainer').show();

        }

        $('#txt_valor_lote_1').val(data.lote_1);
        $('#txt_valor_lote_2').val(data.lote_2);
        $('#txt_valor_lote_3').val(data.lote_3);

    }

    function guardar_articulo() {

        let valor = $('input[name="rbx_lote_tipo"]:checked').val();

        var parametros = {
            'idAr': $('#txt_id').val() ?? '',
            'movimiento': $('#ddl_clase_mov option:selected').text(),

            'cbx_kit': $('#cbx_kit').is(':checked') ? 1 : 0,
            'rbl_tip_articulo': $('input[name="rbl_tip_articulo"]:checked').val(),
            'txt_descripcion': $('#txt_descripcion').val(),
            'txt_descripcion_2': $('#txt_descripcion_2').val(),
            'ddl_custodio': $('#ddl_custodio').val(),
            'ddl_localizacion': $('#ddl_localizacion').val(),
            'txt_rfid': $('#txt_rfid').val(),
            'rbl_asset': $('input[name="rbl_asset"]:checked').val(),
            'txt_tag_serie': $('#txt_tag_serie').val(),
            'txt_tag_anti': $('#txt_tag_anti').val(),
            'txt_subno': $('#txt_subno').val(),
            'txt_cant': $('#txt_cant').val(),
            'txt_valor': $('#txt_valor').val(),
            'txt_maximo': $('#txt_maximo').val(),
            'txt_minimo': $('#txt_minimo').val(),
            'ddl_unidad': $('#ddl_unidad').val(),
            'txt_modelo': $('#txt_modelo').val(),
            'txt_serie': $('#txt_serie').val(),
            'ddl_familia': $('#ddl_familia').val(),
            'ddl_subfamilia': $('#ddl_subfamilia').val(),
            'ddl_marca': $('#ddl_marca').val(),
            'ddl_estado': $('#ddl_estado').val(),
            'ddl_genero': $('#ddl_genero').val(),
            'ddl_color': $('#ddl_color').val(),
            'ddl_clase_mov': $('#ddl_clase_mov').val(),
            'ddl_proyecto': $('#ddl_proyecto').val(),
            'txt_company': $('#txt_company').val(),
            'txt_resp_cctr': $('#txt_resp_cctr').val(),
            'txt_centro_costos': $('#txt_centro_costos').val(),
            'txt_funds_ctr_apc': $('#txt_funds_ctr_apc').val(),
            'txt_profit_ctr': $('#txt_profit_ctr').val(),
            'txt_compra': $('#txt_compra').val(),
            'txt_fecha': $('#txt_fecha').val(),
            'txt_carac': $('#txt_carac').val(),
            'txt_observacion': $('#txt_observacion').val(),
            'cbx_detalle_it': $('#cbx_detalle_it').is(':checked') ? 1 : 0,
            'txt_valor_lote_1': $('#txt_valor_lote_1').val(),
            'txt_valor_lote_2': $('#txt_valor_lote_2').val(),
            'txt_valor_lote_3': $('#txt_valor_lote_3').val(),
        };

        // console.log(parametros);
        var id = '<?= $_id ?>';

        if ($("#form_articulo").valid()) {
            // Si es válido, puedes proceder a enviar los datos por AJAX
            $.ajax({
                data: {
                    parametros: parametros
                },
                url: '../controlador/ACTIVOS_FIJOS/detalle_articuloC.php?guardarArticulo=true',
                type: 'post',
                dataType: 'json',
                success: function(response) {
                    if (id == '') {
                        if (response == 1) {
                            Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
                                location.href = '../vista/inicio.php?mod=<?= $modulo_sistema ?>&acc=articulos_cr';
                            });
                        } else {
                            Swal.fire('', 'SKU repetido.', 'error');
                        }
                    } else {
                        if (response == 1) {
                            cargar_datos_articulo(id);
                            cargar_tabla_movimientos();
                            vista_pnl();
                            limpiar_parametros_articulo();
                            // cargar_articulo_detalles_it(id);

                            Swal.fire('', 'Operacion realizada con éxito.', 'success');
                        } else {
                            Swal.fire('', 'Algo extraño ha pasado.', 'error');
                        }
                    }
                }
            });
        }
    }

    function cargar_tipo_articulo(id_tipo_articulo = '') {
        $.ajax({
            data: {
                _id: ''
            },
            url: '../controlador/ACTIVOS_FIJOS/CATALOGOS/ac_cat_tipo_articuloC.php?listar=true',
            type: 'post',
            dataType: 'json',
            success: function(response) {
                let radioButtons = '';
                $.each(response, function(i, item) {
                    const checked = (item._id === id_tipo_articulo) ? 'checked' : '';
                    radioButtons +=
                        `<div class="col-sm-auto m-0">
              <div class="form-check">
                  <input class="form-check-input" type="radio" id="rbl_tip_articulo_${item._id}" name="rbl_tip_articulo" value="${item._id}" ${checked}>
                  <label class="form-check-label" for="rbl_tip_articulo_${item._id}">${item.descripcion}</label>
              </div>
          </div>`;
                });

                let mensaje_error = `<label class="error mb-2" style="display: none;" for="rbl_tip_articulo"></label>`;

                $('#pnl_tipo_articulo').html(radioButtons + mensaje_error);
            },
            error: function(xhr, status, error) {
                console.log('Status: ' + status);
                console.log('Error: ' + error);
                console.log('XHR Response: ' + xhr.responseText);

                Swal.fire('', 'Error: ' + xhr.responseText, 'error');
            }
        });
    }

    function add_familia() {
        $('#modal_familia').modal('show');
    }

    function add_subfamilia() {
        var fam = $('#ddl_familia').val();
        if (fam == '') {
            Swal.fire('Seleccione una familia', '', 'info');
            return false;
        }

        $('#modal_subfamilia').modal('show');
    }

    function guardar_familia() {
        if ($('#txt_new_familia').val() == '') {
            Swal.fire('Llene el campo', '', 'info');
            return false;
        }
        var parametros = {
            'id': '',
            'familia': $('#txt_new_familia').val(),
        }

        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/ACTIVOS_FIJOS/familiasC.php?insertar=true',
            type: 'post',
            dataType: 'json',
            /*beforeSend: function () {   
                 var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
               $('#tabla_').html(spiner);
            },*/
            success: function(response) {
                // console.log(response);
                if (response == 1) {
                    Swal.fire('Familia ingresada', '', 'success');
                    $('#modal_familia').modal('hide');
                }
            }
        });
    }

    function guardar_subfamilia() {
        if ($('#txt_new_subfamilia').val() == '') {
            Swal.fire('Llene el campo', '', 'info');
            return false;
        }
        var parametros = {
            'id': '',
            'familia': $('#ddl_familia').val(),
            'subfamilia': $('#txt_new_subfamilia').val(),
        }

        $.ajax({
            data: {
                parametros: parametros
            },
            url: '../controlador/ACTIVOS_FIJOS/familiasC.php?insertar_sub=true',
            type: 'post',
            dataType: 'json',
            /*beforeSend: function () {   
                 var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
               $('#tabla_').html(spiner);
            },*/
            success: function(response) {
                // console.log(response);
                if (response == 1) {
                    Swal.fire('SubFamilia ingresada', '', 'success');
                    $('#modal_subfamilia').modal('hide');
                }
            }
        });
    }

    /**
     * Select2 carga
     */

    function cargar_selects2() {
        let url_custodioC = '../controlador/ACTIVOS_FIJOS/custodioC.php?lista=true';
        cargar_select2_url('ddl_custodio', url_custodioC, '-- Seleccione --');

        let url_familiasC = '../controlador/ACTIVOS_FIJOS/familiasC.php?lista_drop=true';
        cargar_select2_url('ddl_familia', url_familiasC, '-- Seleccione --');

        let url_clase_movimientoC = '../controlador/ACTIVOS_FIJOS/clase_movimientoC.php?buscar_auto=true';
        cargar_select2_url('ddl_clase_mov', url_clase_movimientoC, '-- Seleccione --');

        let url_localizacionC = '../controlador/ACTIVOS_FIJOS/localizacionC.php?lista=true';
        cargar_select2_url('ddl_localizacion', url_localizacionC, '-- Seleccione --');

        let url_colores = '../controlador/ACTIVOS_FIJOS/detalle_articuloC.php?colores=true';
        cargar_select2_url('ddl_color', url_colores, '-- Seleccione --');

        let url_marca = '../controlador/ACTIVOS_FIJOS/detalle_articuloC.php?marca=true';
        cargar_select2_url('ddl_marca', url_marca, '-- Seleccione --');

        let url_genero = '../controlador/ACTIVOS_FIJOS/detalle_articuloC.php?genero=true';
        cargar_select2_url('ddl_genero', url_genero, '-- Seleccione --');

        let url_estado = '../controlador/ACTIVOS_FIJOS/estadoC.php?lista_drop=true';
        cargar_select2_url('ddl_estado', url_estado, '-- Seleccione --');

        let url_proyecto = '../controlador/ACTIVOS_FIJOS/detalle_articuloC.php?proyecto=true';
        cargar_select2_url('ddl_proyecto', url_proyecto, '-- Seleccione --');

        //Unidad de medida
        let url_unidad_medidaC = '../controlador/ACTIVOS_FIJOS/CATALOGOS/ac_cat_unidad_medidaC.php?buscar=true';
        cargar_select2_url('ddl_unidad', url_unidad_medidaC, '-- Seleccione --');

        autocmpletar_subfam();

    }

    function autocmpletar_subfam() {
        var fa = $('#ddl_familia').val();
        if (fa == '') {
            return false;
        }

        $('#ddl_subfamilia').select2({
            placeholder: 'Seleccione una Subfamilia',
            ajax: {
                url: '../controlador/ACTIVOS_FIJOS/familiasC.php?lista_subfamilias_drop=true&fam=' + fa,
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
    }

    //Validaciones
    function validar_campo() {
        var asset = $('#txt_rfid').val();
        var cant = $('input[type=radio][name="rbl_asset"]:checked').val();
        if (cant != 0) {
            num_caracteres('txt_rfid', cant);
        }

        console.log(asset);
        console.log(cant);
    }

    function limpiar_parametros_articulo() {
        //Limpiar validaciones
        $("#form_articulo").validate().resetForm();
        $('.form-control, .form-select, .select2-selection, .form-check-input').removeClass('is-valid is-invalid');
    }
</script>

<?php
$modulo_acceso = '';
if ($_SESSION['INICIO']['MODULO_SISTEMA'] == 2018) {
    $modulo_acceso = 'hidden';
}
?>



<form id="form_articulo">
    <div class="row">
        <div class="col-auto">
            <div id="cbx_kit_cointainer">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="cbx_kit" id="cbx_kit">
                    <label class="form-label" for="cbx_kit">KIT </label>
                </div>
                <label class="error" style="display: none;" for="cbx_kit"></label>
            </div>
        </div>

        <div class="col-auto" <?= $modulo_acceso ?>>
            <div id="cbx_detalle_it_cointainer">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="cbx_detalle_it" id="cbx_detalle_it">
                    <label class="form-label" for="cbx_detalle_it">IT </label>
                </div>
                <label class="error" style="display: none;" for="cbx_detalle_it"></label>
            </div>
        </div>

    </div>

    <hr class="text-primary mb-2 mt-1">

    <div class="row mb-col" id="pnl_tipo_articulo">
        <!-- <div class="col-sm-3">
                <div class="form-check">
                    <input class="form-check-input" type="radio" id="txt_bajas" name="rbl_op">
                    <label class="form-check-label" for="txt_bajas">Bajas</label>
                </div>
            </div> -->
    </div>

    <!-- Detalle Artículo  -->
    <div class="row mb-col">
        <div class="col-sm-6">
            <label for="txt_descripcion" class="form-label">Descripción </label>
            <input type="text" class="form-control form-control-sm" name="txt_descripcion" id="txt_descripcion" maxlength="200">
        </div>

        <div class="col-sm-6">
            <label for="txt_descripcion_2" class="form-label">Descripción 2 </label>
            <input type="text" class="form-control form-control-sm" name="txt_descripcion_2" id="txt_descripcion_2" maxlength="200">
        </div>
    </div>

    <div class="row mb-col">
        <div class="col-sm-6" <?= $modulo_acceso ?>>
            <div class="d-flex justify-content-between align-items-center">
                <label for="ddl_custodio" class="form-label">Custodio </label>
                <small id="lbl_sap_custodio" class="text-muted"><u>Código:</u></small>
            </div>

            <select class="form-control form-control-sm select2-validation" name="ddl_custodio" id="ddl_custodio" disabled>
                <option value="">Seleccione</option>
            </select>
            <label class="error" style="display: none;" for="ddl_custodio"></label>
        </div>

        <div class="col-sm-6">
            <div class="d-flex justify-content-between align-items-center">
                <label for="ddl_localizacion" class="form-label">Emplazamiento / Localización </label>
                <small id="lbl_sap_loc" class="text-muted"><u>Código:</u></small>
            </div>

            <select class="form-control form-control-sm select2-validation" name="ddl_localizacion" id="ddl_localizacion">
                <option value="">Seleccione</option>
            </select>
            <label class="error" style="display: none;" for="ddl_localizacion"></label>
        </div>
    </div>

    <hr class="text-primary mb-2 mt-1">

    <!-- Detalles TAG -->
    <div class="row mb-col">
        <div class="col-sm-6">
            <label for="txt_rfid" class="form-label">RFID </label>
            <input type="text" class="form-control form-control-sm" name="txt_rfid" id="txt_rfid" onkeyup="validar_campo()" maxlength="50">


            <?php if ($_SESSION['INICIO']['MODULO_SISTEMA'] == 2018) { ?>
                <div class="text-start text-start-1 mt-0">
                    <div class="form-check form-check-1 form-check-inline">
                        <input class="form-check-input form-check-input-1" type="radio" name="rbl_asset" id="rbl_asset_0" value="0" onclick="validar_campo()" checked>
                        <label class="form-check-label form-check-label-1" for="rbl_asset_0"><small>Ninguno</small></label>
                    </div>
                </div>
            <?php } else { ?>
                <div class="text-start text-start-1 mt-0">
                    <div class="form-check form-check-1 form-check-inline">
                        <input class="form-check-input form-check-input-1" type="radio" name="rbl_asset" id="rbl_asset_8" value="8" onclick="validar_campo()" checked>
                        <label class="form-check-label form-check-label-1" for="rbl_asset_8"><small>Activo (8)</small></label>
                    </div>
                    <div class="form-check form-check-1 form-check-inline">
                        <input class="form-check-input form-check-input-1" type="radio" name="rbl_asset" id="rbl_asset_9" value="9" onclick="validar_campo()">
                        <label class="form-check-label form-check-label-1" for="rbl_asset_9"><small>Patrimonial (9)</small></label>
                    </div>
                    <div class="form-check form-check-1 form-check-inline">
                        <input class="form-check-input form-check-input-1" type="radio" name="rbl_asset" id="rbl_asset_0" value="0" onclick="validar_campo()">
                        <label class="form-check-label form-check-label-1" for="rbl_asset_0"><small>Ninguno</small></label>
                    </div>
                </div>
            <?php } ?>

        </div>

        <div class="col-sm-6">
            <label for="txt_tag_serie" class="form-label">Referencia de almacén (SKU) </label>
            <input type="text" class="form-control form-control-sm solo_numeros_int" name="txt_tag_serie" id="txt_tag_serie" maxlength="100">
        </div>
    </div>

    <div class="row mb-col">
        <div class="col-sm-6">
            <label for="txt_tag_anti" class="form-label">RFID Antiguo </label>
            <input type="text" class="form-control form-control-sm solo_numeros_int" name="txt_tag_anti" id="txt_tag_anti" maxlength="15">
        </div>



        <div class="col-sm-6">
            <label for="txt_subno" class="form-label">Subnúmero </label>
            <input type="text" class="form-control form-control-sm solo_numeros_int" name="txt_subno" id="txt_subno" maxlength="15">
        </div>
    </div>

    <hr class="text-primary mb-2 mt-1">

    <!-- Información Adicional -->
    <div class="row mb-col">
        <div class="col-sm-3">
            <label for="txt_cant" class="form-label">Cantidad </label>
            <input type="text" class="form-control form-control-sm solo_numeros_int" name="txt_cant" id="txt_cant" value="1" maxlength="1" readonly>
        </div>

        <div class="col-sm-3">
            <label for="txt_valor" class="form-label">Precio </label>
            <input type="number" class="form-control form-control-sm" name="txt_valor" id="txt_valor" maxlength="16">
        </div>

        <div class="col-sm-3" hidden>
            <label for="txt_maximo" class="form-label">Máximo </label>
            <input type="text" class="form-control form-control-sm solo_numeros_int" name="txt_maximo" id="txt_maximo" maxlength="8" readonly>
        </div>

        <div class="col-sm-3" hidden>
            <label for="txt_minimo" class="form-label">Mínimo </label>
            <input type="text" class="form-control form-control-sm solo_numeros_int" name="txt_minimo" id="txt_minimo" maxlength="8" readonly>
        </div>
    </div>

    <div class="row mb-col">
        <div class="col-sm-4">
            <label for="ddl_unidad" class="form-label">Unidad de medida </label>
            <select class="form-control form-control-sm select2-validation" name="ddl_unidad" id="ddl_unidad">
                <option value="">Seleccione</option>
            </select>
            <label class="error" style="display: none;" for="ddl_unidad"></label>
        </div>

        <div class="col-sm-4">
            <label for="txt_modelo" class="form-label">Modelo </label>
            <input type="text" class="form-control form-control-sm" name="txt_modelo" id="txt_modelo" maxlength="255">
        </div>

        <div class="col-sm-4">
            <label for="txt_serie" class="form-label">Serie </label>
            <input type="text" class="form-control form-control-sm" name="txt_serie" id="txt_serie" maxlength="255">
        </div>
    </div>

    <hr class="text-primary mb-2 mt-1">

    <!-- Información SAP -->
    <div class="row mb-col">
        <div class="col-sm-6">
            <label for="ddl_familia" class="form-label">
                Familia
                <!-- <button type="button" class="btn btn-success btn-xss mb-1" onclick="add_familia()" title="Nueva familia">
                              <i class="bx bx-plus fs-7 me-0 fw-bold"></i>
                            </button> -->
            </label>

            <select class="form-select form-select-sm select2-validation" name="ddl_familia" id="ddl_familia" onchange="autocmpletar_subfam()">
                <option value="">Seleccione</option>
            </select>
            <label class="error" style="display: none;" for="ddl_familia"></label>
        </div>

        <div class="col-sm-6">
            <label for="ddl_subfamilia" class="form-label">
                Subfamilia
                <!-- <button type="button" class="btn btn-success btn-xss mb-1" onclick="add_subfamilia()" title="Nueva sub familia">
                              <i class="bx bx-plus fs-7 me-0 fw-bold"></i>
                            </button> -->
            </label>
            <select class="form-select form-select-sm select2-validation" name="ddl_subfamilia" id="ddl_subfamilia">
                <option value="">Seleccione una familia</option>
            </select>
            <label class="error" style="display: none;" for="ddl_subfamilia"></label>
        </div>
    </div>

    <div class="row mb-col">
        <div class="col-sm-3">
            <div class="d-flex justify-content-between align-items-center">
                <label for="ddl_marca" class="form-label">Marca </label>
                <small id="lbl_sap_mar"><u>Código: </u></small>
            </div>

            <select class="form-control form-control-sm select2-validation" name="ddl_marca" id="ddl_marca">
                <option value="">Seleccione</option>
            </select>
            <label class="error" style="display: none;" for="ddl_marca"></label>
        </div>

        <div class="col-sm-3">
            <div class="d-flex justify-content-between align-items-center">
                <label for="ddl_estado" class="form-label">Estado </label>
                <small id="lbl_sap_est"><u>Código: </u></small>
            </div>
            <select class="form-control form-control-sm select2-validation" name="ddl_estado" id="ddl_estado">
                <option value="">Seleccione</option>
            </select>
            <label class="error" style="display: none;" for="ddl_estado"></label>
        </div>

        <div class="col-sm-3">
            <div class="d-flex justify-content-between align-items-center">
                <label for="ddl_genero" class="form-label">Género </label>
                <small id="lbl_sap_gen"><u>Código: </u></small>
            </div>
            <select class="form-control form-control-sm select2-validation" name="ddl_genero" id="ddl_genero">
                <option value="">Seleccione</option>
            </select>
            <label class="error" style="display: none;" for="ddl_genero"></label>
        </div>

        <div class="col-sm-3">
            <div class="d-flex justify-content-between align-items-center">
                <label for="ddl_color" class="form-label">Color </label>
                <small id="lbl_sap_col"><u>Código: </u></small>
            </div>
            <select class="form-control form-control-sm select2-validation" name="ddl_color" id="ddl_color">
                <option value="">Seleccione</option>
            </select>
            <label class="error" style="display: none;" for="ddl_color"></label>
        </div>
    </div>

    <div class="row mb-col">
        <div class="col-sm-6">
            <label for="ddl_clase_mov" class="form-label">Clase de movimiento </label>
            <select class="form-select form-select-sm select2-validation" name="ddl_clase_mov" id="ddl_clase_mov">
                <option value="">Seleccione</option>
            </select>
            <label class="error" style="display: none;" for="ddl_clase_mov"></label>
        </div>

        <div class="col-sm-6" hidden>
            <div class="d-flex justify-content-between align-items-center">
                <label for="ddl_proyecto" class="form-label">Proyecto </label>
                <small id="lbl_sap_pro"><u>Código: </u></small>
            </div>
            <select class="form-control form-control-sm select2-validation" name="ddl_proyecto" id="ddl_proyecto">
                <option value="">Seleccione</option>
            </select>
            <label class="error" style="display: none;" for="ddl_proyecto"></label>
        </div>
    </div>

    <hr class="text-primary mb-2 mt-1" style="border-top: 3px solid;">

    <div <?= $modulo_acceso ?>>
        <div class="row mb-col">
            <div class="col-sm-6">
                <label for="txt_company" class="form-label form-label-sm">Company Code </label>
                <input type="text" class="form-control form-control-sm" name="txt_company" id="txt_company" maxlength="100">
            </div>

            <div class="col-sm-6">
                <label for="txt_resp_cctr" class="form-label form-label-sm">Responsable del Centro de Costos </label>
                <input type="text" class="form-control form-control-sm" name="txt_resp_cctr" id="txt_resp_cctr" maxlength="100">
            </div>
        </div>

        <div class="row mb-col">
            <div class="col-sm-4">
                <label for="txt_centro_costos" class="form-label form-label-sm">Centro de Costos </label>
                <input type="text" class="form-control form-control-sm" name="txt_centro_costos" id="txt_centro_costos" maxlength="100">
            </div>

            <div class="col-sm-4">
                <label for="txt_funds_ctr_apc" class="form-label form-label-sm">Control de Fondos APC </label>
                <input type="text" class="form-control form-control-sm" name="txt_funds_ctr_apc" id="txt_funds_ctr_apc" maxlength="100">
            </div>

            <div class="col-sm-4">
                <label for="txt_profit_ctr" class="form-label form-label-sm">Centro de Beneficio </label>
                <input type="text" class="form-control form-control-sm" name="txt_profit_ctr" id="txt_profit_ctr" maxlength="100">
            </div>
        </div>

        <hr class="text-primary mb-2 mt-1">
    </div>

    <!-- Información articulo adicional -->
    <div class="row mb-col">
        <div class="col-sm-4">
            <label for="txt_compra" class="form-label">Fecha de Compra </label>
            <input type="date" class="form-control form-control-sm" name="txt_compra" id="txt_compra">
        </div>

        <div class="col-sm-4">
            <label for="txt_fecha" class="form-label">Fecha de Referencia </label>
            <input type="date" class="form-control form-control-sm" name="txt_fecha" id="txt_fecha">
        </div>
    </div>

    <div class="row mb-col">
        <div class="col-sm-12">
            <label for="txt_carac" class="form-label">Características </label>
            <textarea class="form-control form-control-sm" name="txt_carac" id="txt_carac" placeholder="Características" rows="1" maxlength="255"></textarea>
        </div>
    </div>

    <div class="row mb-col">
        <div class="col-sm-12">
            <label for="txt_observacion" class="form-label">Observaciones </label>
            <textarea class="form-control form-control-sm" name="txt_observacion" id="txt_observacion" placeholder="Observaciones" rows="1" maxlength="255"></textarea>
        </div>
    </div>

    <hr>
    <div class="row mb-col">

        <div class="col-sm-4">
            <label for="txt_valor_lote_1" class="form-label">Lote 1</label>
            <input type="text" class="form-control form-control-sm" name="txt_valor_lote_1" id="txt_valor_lote_1" maxlength="255">
        </div>
        <div class="col-sm-4">
            <label for="txt_valor_lote_2" class="form-label">Lote 2</label>
            <input type="text" class="form-control form-control-sm" name="txt_valor_lote_2" id="txt_valor_lote_2" maxlength="255">
        </div>
        <div class="col-sm-4">
            <label for="txt_valor_lote_3" class="form-label">Lote 3</label>
            <input type="text" class="form-control form-control-sm" name="txt_valor_lote_3" id="txt_valor_lote_3" maxlength="255">
        </div>

        <div class="d-flex justify-content-end pt-2">
            <button class="btn btn-success btn-sm px-4 m-0" onclick="guardar_articulo();" type="button"><i class="bx bx-save"></i> Guardar</button>
        </div>
    </div>

</form>

<div class="modal fade" id="modal_familia" tabindex="-1" data-bs-backdrop="static" aria-labelledby="modal_familia_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_familia_label">Nueva familia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="text" name="txt_new_familia" id="txt_new_familia" class="form-control form-control-sm" placeholder="Ingrese el nombre de la familia">
                </div>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button> -->
                <button type="button" class="btn btn-success btn-sm px-4 m-0" onclick="guardar_familia();"><i class="bx bx-save"></i> Guardar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_subfamilia" tabindex="-1" data-bs-backdrop="static" aria-labelledby="modal_subfamilia_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_subfamilia_label">Nueva Subfamilia</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="text" name="txt_new_subfamilia" id="txt_new_subfamilia" class="form-control form-control-sm" placeholder="Ingrese el nombre de la subfamilia">
                </div>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button> -->
                <button type="button" class="btn btn-success btn-sm px-4 m-0" onclick="guardar_subfamilia();"><i class="bx bx-save"></i> Guardar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        agregar_asterisco_inputs();

        //Para validar los select2
        $(".select2-validation").on("select2:select", function(e) {
            unhighlight_select(this);
        });

        $("#form_articulo").validate({
            rules: {
                cbx_kit: {
                    // required: true,
                },
                rbl_tip_articulo: {
                    required: true,
                },
                txt_descripcion: {
                    required: true,
                },
                txt_descripcion_2: {
                    // required: true,
                },
                ddl_custodio: {
                    required: true,
                },
                ddl_localizacion: {
                    required: true,
                },
                txt_rfid: {
                    // required: true,
                },
                rbl_asset: {
                    // required: true,
                },
                txt_tag_serie: {
                    required: true,
                },
                txt_tag_anti: {
                    // required: true,
                },
                txt_subno: {
                    // required: true,
                },
                txt_cant: {
                    required: true,
                },
                txt_valor: {
                    // required: true,
                },
                txt_maximo: {
                    // required: true,
                },
                txt_minimo: {
                    // required: true,
                },
                ddl_unidad: {
                    required: true,
                },
                txt_modelo: {
                    // required: true,
                },
                txt_serie: {
                    // required: true,
                },
                ddl_familia: {
                    // required: true,
                },
                ddl_subfamilia: {
                    // required: true,
                },
                ddl_marca: {
                    required: true,
                },
                ddl_estado: {
                    required: true,
                },
                ddl_genero: {
                    required: true,
                },
                ddl_color: {
                    required: true,
                },
                ddl_clase_mov: {
                    // required: true,
                },
                ddl_proyecto: {
                    // required: true,
                },
                txt_company: {
                    // required: true,
                },
                txt_resp_cctr: {
                    // required: true,
                },
                txt_centro_costos: {
                    // required: true,
                },
                txt_funds_ctr_apc: {
                    // required: true,
                },
                txt_profit_ctr: {
                    // required: true,
                },
                txt_compra: {
                    // required: true,
                },
                txt_fecha: {
                    required: true,
                },
                txt_carac: {
                    // required: true,
                },
                txt_observacion: {
                    // required: true,
                },

            },

            highlight: function(element) {
                let $element = $(element);

                if ($element.hasClass("select2-hidden-accessible")) {
                    // Elimina la clase 'is-invalid' y agrega 'is-valid' al contenedor correcto de select2
                    $element.next(".select2-container").find(".select2-selection").removeClass("is-valid").addClass("is-invalid");
                } else {
                    // Elimina la clase 'is-invalid' y agrega 'is-valid' al input normal
                    $element.removeClass("is-valid").addClass("is-invalid");
                }
            },

            unhighlight: function(element) {
                let $element = $(element);

                if ($element.hasClass("select2-hidden-accessible")) {
                    // Elimina la clase 'is-invalid' y agrega 'is-valid' al contenedor correcto de select2
                    $element.next(".select2-container").find(".select2-selection").removeClass("is-invalid").addClass("is-valid");
                } else {
                    // Elimina la clase 'is-invalid' y agrega 'is-valid' al input normal
                    $element.removeClass("is-invalid").addClass("is-valid");
                }
            }
        });
    });

    function agregar_asterisco_inputs() {
        // agregar_asterisco_campo_obligatorio('cbx_kit');
        agregar_asterisco_campo_obligatorio('txt_descripcion');
        // agregar_asterisco_campo_obligatorio('txt_descripcion_2');
        agregar_asterisco_campo_obligatorio('ddl_custodio');
        agregar_asterisco_campo_obligatorio('ddl_localizacion');
        // agregar_asterisco_campo_obligatorio('txt_rfid');
        // agregar_asterisco_campo_obligatorio('rbl_asset');
        agregar_asterisco_campo_obligatorio('txt_tag_serie');
        // agregar_asterisco_campo_obligatorio('txt_tag_anti');
        // agregar_asterisco_campo_obligatorio('txt_subno');
        agregar_asterisco_campo_obligatorio('txt_cant');
        // agregar_asterisco_campo_obligatorio('txt_valor');
        agregar_asterisco_campo_obligatorio('txt_maximo');
        agregar_asterisco_campo_obligatorio('txt_minimo');
        agregar_asterisco_campo_obligatorio('ddl_unidad');
        // agregar_asterisco_campo_obligatorio('txt_modelo');
        // agregar_asterisco_campo_obligatorio('txt_serie');
        // agregar_asterisco_campo_obligatorio('ddl_familia');
        // agregar_asterisco_campo_obligatorio('ddl_subfamilia');
        agregar_asterisco_campo_obligatorio('ddl_marca');
        agregar_asterisco_campo_obligatorio('ddl_estado');
        agregar_asterisco_campo_obligatorio('ddl_genero');
        agregar_asterisco_campo_obligatorio('ddl_color');
        // agregar_asterisco_campo_obligatorio('ddl_clase_mov');
        // agregar_asterisco_campo_obligatorio('ddl_proyecto');
        // agregar_asterisco_campo_obligatorio('txt_company');
        // agregar_asterisco_campo_obligatorio('txt_resp_cctr');
        // agregar_asterisco_campo_obligatorio('txt_centro_costos');
        // agregar_asterisco_campo_obligatorio('txt_funds_ctr_apc');
        // agregar_asterisco_campo_obligatorio('txt_profit_ctr');
        // agregar_asterisco_campo_obligatorio('txt_compra');
        agregar_asterisco_campo_obligatorio('txt_fecha');
        // agregar_asterisco_campo_obligatorio('txt_carac');
        // agregar_asterisco_campo_obligatorio('txt_observacion');
    }
</script>