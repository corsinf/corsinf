<?php //include('../cabeceras/header.php');

$modulo_sistema = ($_SESSION['INICIO']['MODULO_SISTEMA']);

$_id = '';

if (isset($_GET['_id'])) {
  $_id = $_GET['_id'];
}

?>

<script src="../js/ACTIVOS_FIJOS/avaluos.js"></script>
<script src="../lib/jquery_validation/jquery.validate.js"></script>
<script src="../js/GENERAL/operaciones_generales.js"></script>

<style>
  .text-start-1 {
    display: flex;
    gap: 5px;
    /* Espacio entre los radios */
    flex-wrap: nowrap;
    /* Asegura que los radios no se vayan abajo */
  }

  .form-check-1 {
    display: flex;
    align-items: center;
    white-space: nowrap;
    /* Evita que el texto de los radios haga que bajen */
  }

  .form-check-input-1 {
    width: 12px;
    height: 12px;
  }

  .form-check-label-1 {
    font-size: 12px;
    margin-left: 3px;
  }

  #img_articulo {
    height: 300px;
    object-fit: contain;
    width: 100%;
    background-color: #f8f9fa;
  }
</style>

<script type="text/javascript">
  $(document).ready(function() {
    // navegacion();
    validar_datos();

    $('#imprimir_cedula').click(function() {
      // var url = '../lib/Reporte_pdf.php?reporte_cedula=true&id=' + $('#txt_id').val();
      var url = '../controlador/ACTIVOS_FIJOS/REPORTES/?ac_reporte_cedula_activo=true&id_activo=' + $('#txt_id').val();
      window.open(url, '_blank');
    });


    /**
     * 
     * Sirve para Marca los label con los codigos seleccionados
     */

    //--------------------------------
    $('#ddl_marca').on('select2:select', function(e) {
      var data = e.params.data.data;

      $('#lbl_sap_mar').text('Código:' + data.CODIGO)
      // console.log(data);
    });

    //---------------------------------
    $('#ddl_genero').on('select2:select', function(e) {
      var data = e.params.data.data;
      $('#lbl_sap_gen').text('Código:' + data.CODIGO)
      // console.log(data);
    });

    //---------------------------------
    $('#ddl_color').on('select2:select', function(e) {
      var data = e.params.data.data;
      $('#lbl_sap_col').text('Código:' + data.CODIGO)
      // console.log(data);
    });

    //---------------------------------
    $('#ddl_estado').on('select2:select', function(e) {
      var data = e.params.data.data;
      $('#lbl_sap_est').text('Código:' + data.CODIGO)
      // console.log(data);
    });

    //---------------------------------
    $('#ddl_proyecto').on('select2:select', function(e) {
      var data = e.params.data.data;
      $('#lbl_sap_pro').text('Código:' + data.pro)
      // console.log(data);
    });

    //---------------------------------
    $('#ddl_localizacion').on('select2:select', function(e) {
      var data = e.params.data.data;
      $('#lbl_sap_loc').text('Código:' + data.EMPLAZAMIENTO)
      // console.log(data);
    });

    //---------------------------------
  });
</script>

<script type="text/javascript">
  $(document).ready(function() {
    $('#txt_id').val('<?= $_id ?>');

    $("#subir_imagen").on('click', function() {
      var formData = new FormData(document.getElementById("form_img"));
      var files = $('#file_img')[0].files[0];
      formData.append('file', files);
      // formData.append('curso',curso);
      $.ajax({
        url: '../controlador/ACTIVOS_FIJOS/detalle_articuloC.php?cargar_imagen=true',
        type: 'post',
        data: formData,
        contentType: false,
        processData: false,
        dataType: 'json',
        // beforeSend: function () {
        //        $("#foto_alumno").attr('src',"../img/gif/proce.gif");
        //     },
        success: function(response) {
          if (response == -1) {
            Swal.fire(
              '',
              'La foto no se subió.',
              'error')

          } else if (response == -2) {
            Swal.fire(
              '',
              'Tipo no permitido.',
              'error')
          } else if (response == -3) {
            Swal.fire(
              '',
              'Datos incompletos.',
              'error')
          } else if (response == -4) {
            Swal.fire(
              '',
              'No se pudo mover.',
              'error')
          } else if (response == -5) {
            Swal.fire(
              '',
              'Ruta inválida o no accesible.',
              'error')
          } else {
            cargar_datos_articulo('<?= $_id ?>');
            cargar_tabla_movimientos();
            vista_pnl();
            limpiar_parametros_articulo();
          }
        }
      });
    });

  });

  //Función principal para cargar todos los datos con base al id_articulo
  function validar_datos() {
    var id = '<?= $_id ?>';

    // console.log(id);
    if (id == '') {
      alert('No ha seleccionado ningún artículo');
    } else {
      cargar_tipo_articulo();

      cargar_datos_articulo(id);
      cargar_selects2();
      cargarAvaluo('<?= $_id ?>');
    }
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

  /**
   * Select2 carga
   */

  //Carga los datos en la vista principal para modificar y en la vista solo para visualizar
  function cargar_datos_articulo(id) {
    $.ajax({
      data: {
        id: id
      },
      url: '../controlador/ACTIVOS_FIJOS/detalle_articuloC.php?cargar_datos=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        if (!response || response.length === 0) {
          console.error("No se recibieron datos válidos");
          return;
        }

        let data = response[0];
        //console.log(data);

        cargar_articulo_vista_pnl(data);
        cargar_articulo_editar_pnl(data);

        datos_col_custodio(data);
      },

      error: function(xhr, status, error) {
        console.error("Error en la petición AJAX: ", status, error);
      }
    });
  }

  function cargar_articulo_vista_pnl(data) {
    $('#lbl_descripcion').text(data.nom);
    $('#lbl_descripcion2').text(data.des ?? '');
    $('#lbl_localizacion1').html(`<b>Emplazamiento / Localización</b> | <label style="font-size:65%"> Código: ${data.c_loc}</label>`);
    $('#lbl_localizacion').text(data.loc_nom);

    $('#lbl_custodio1').html(`<b>Custodio:</b> | <label style="font-size:65%"> Código: ${data.person_no}</label>`);
    $('#lbl_custodio').text(data.person_nom);

    $('#lbl_marca').html(`${data.marca} | <label style="font-size:65%"> Código: ${data.c_mar}</label>`);
    $('#lbl_color').html(`${data.color} | <label style="font-size:65%"> Código: ${data.c_col}</label>`);
    $('#lbl_genero').html(`${data.genero} | <label style="font-size:65%"> Código: ${data.c_gen}</label>`);
    $('#lbl_proyecto').html(`${data.proyecto} | <label style="font-size:65%"> Código: ${data.c_pro}</label>`);
    $('#lbl_estado').html(`${data.estado} | <label style="font-size:65%"> Código: ${data.c_est}</label>`);

    $('#lbl_sku').html(`<b>SKU:</b> ${data.tag_s}`);
    $('#lbl_sub_num').html(`<b>SubNum:</b> ${data.subnum}`);
    $('#lbl_rfid').html(data.rfid);
    $('#lbl_tag_ant').html(`<b>RFID Antiguo:</b> ${data.ant}`);
    $('#lbl_serie').text(data.ser);

    if (data.fecha_referencia != null) {
      $('#lbl_fecha_inve').text(formatoDate(data.fecha_referencia));
    } else {
      $('#lbl_fecha_inve').text(('dd/mm/aaaa'));
    }

    $('#lbl_modelo').text(data.mod);

    if (data.obs) {
      $('#lbl_observaciones').css('display', 'block').html(`<b>Observaciones:</b> ${data.obs}`);
    }

    if (data.ruta_imagen && data.ruta_imagen !== null) {
      let url_sin_cache = data.ruta_imagen + '&v=' + new Date().getTime();
      $("#img_articulo").attr("src", url_sin_cache);
    }

    $('#txt_nom_img').val(data.tag_s);
    $('#txt_idA_img').val('<?= $_id ?>');




    // $('#lbl_unidad').text('/' + data.id_unidad_medida);
    if (data.fecha_contabilizacion != null) {
      $('#lbl_fecha_compra').text(formatoDate(data.fecha_contabilizacion));
    } else {
      $('#lbl_fecha_compra').text(('dd/mm/aaaa'));
    }

    if (data.carac) {
      $('#lbl_caracteristicas').css('display', 'block').html(`<b>Características:</b> ${data.carac}`);
    }

    let precioRedondeado = Math.ceil(data.prec * 100) / 100;
    $('#lbl_precio').text(`$${precioRedondeado.toFixed(2)}`);
    $('#lbl_canti').text(data.cant);

    if (data.tipo_articulo === 'PATRIMONIALES') {
      $('#lbl_tipo').html('<div class="text-warning">ACTIVO PATRIMONIAL</div>');
    } else if (data.tipo_articulo === 'TERCEROS') {
      $('#lbl_tipo').html('<div class="text-primary">ACTIVO DE TERCERO</div>');
    } else if (data.tipo_articulo === 'BAJAS') {
      $('#lbl_tipo').html('<div class="text-danger">ACTIVO DE BAJA</div>');
    }
  }

  function cargar_articulo_editar_pnl(data) {
    $('#cbx_kit').prop('checked', data.es_kit === "1");
    $('input[name="rbl_tip_articulo"][value="' + data.id_tipo_articulo + '"]').prop('checked', true);
    console.log(data.id_tipo_articulo);

    $('input[name="rbl_asset"][value="' + data.longitud_rfid + '"]').prop('checked', true);

    // Asignar valores a los campos de texto
    $('#txt_descripcion').val(data.nom);
    $('#txt_descripcion_2').val(data.des);

    $('#txt_rfid').val(data.rfid);
    $('#txt_tag_serie').val(data.tag_s);
    $('#txt_tag_anti').val(data.ant);


    $('#txt_subno').val(data.subnum);
    $('#txt_cant').val(data.cant);
    $('#txt_valor').val(data.prec);
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
      text: data.loc_nom,
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
    $('#lbl_sap_loc').text('Código:' + data.c_loc);
    $('#lbl_sap_custodio').text('Código:' + data.person_ci);
  }

  function guardar_articulo() {

    var parametros = {
      'idAr': $('#txt_id').val(),
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
    };

    // console.log(parametros);
    var id = <?= $_id ?>;

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
          if (response == 1) {
            Swal.fire('', 'Operacion realizada con éxito.', 'success');

            cargar_datos_articulo(id);
            cargar_tabla_movimientos();
            vista_pnl();
            limpiar_parametros_articulo();
          } else {
            Swal.fire('', 'Algo extraño ha pasado.', 'error');
          }
        }
      });
    }
  }

  function cargar_tipo_articulo() {
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
          radioButtons +=
            `<div class="col-sm-auto m-0">
                <div class="form-check">
                    <input class="form-check-input" type="radio" id="rbl_tip_articulo_${item._id}" name="rbl_tip_articulo" value="${item._id}">
                    <label class="form-check-label" for="rbl_tip_articulo_${item._id}">${item.descripcion}</label>
                </div>
            </div>`;
        });

        mensaje_error = `<label class="error mb-2" style="display: none;" for="rbl_tip_articulo"></label>`

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

  function validar_campo() {
    var asset = $('#txt_rfid').val();
    var cant = $('input[type=radio][name="rbl_asset"]:checked').val();
    if (cant != 0) {
      num_caracteres('txt_rfid', cant);
    }

    console.log(asset);
    console.log(cant);
  }

  function editar_pnl() {
    // alert('entra');
    var id = '<?= $_id ?>';

    // console.log(id);
    if (id == '') {
      alert('No ha seleccionado ningún artículo');
    } else {
      $('#form_img').css('display', 'block');
      $('#panel_editar').css('display', 'block');
      $('#panel_vista').css('display', 'none');
      $('#btn_editar').css('display', 'none');
      $('#btn_vista').css('display', 'block');
    }
  }

  function vista_pnl() {
    var id = '<?= $_id ?>';

    // console.log(id);
    if (id == '') {
      alert('No ha seleccionado ningún artículo');
    } else {
      $('#form_img').css('display', 'none');
      $('#panel_editar').css('display', 'none');
      $('#panel_vista').css('display', 'block');
      $('#btn_editar').css('display', 'block');
      $('#btn_vista').css('display', 'none');
    }
  }

  function limpiar_parametros_articulo() {
    //Limpiar validaciones
    $("#form_articulo").validate().resetForm();
    $('.form-control, .form-select, .select2-selection, .form-check-input').removeClass('is-valid is-invalid');
  }

  //Impresión
  function imprimir_tags_masivo() {
    var query = $('#txt_buscar').val();
    var parametros = {
      'query': $('#lbl_sku').text(),
      'localizacion': '',
      'custodio': '',
      'pag': '',
    }
    var lineas = '';
    $.ajax({
      data: {
        parametros: parametros
      },
      url: '../controlador/ACTIVOS_FIJOS/articulosC.php?lista_imprimir=true',
      type: 'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
      success: function(response) {
        console.log(response);
        if (response == 1) {
          Swal.fire('',
            'Etiquetas generadas Dirijase a Zebra designer.',
            'info');
        } else if (response == 2) {
          Swal.fire({
            title: 'Existen etiquetas generadas para impresion!',
            text: "desea generar etiquetas!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Eliminar y continuar!'
          }).then((result) => {
            if (result.value) {
              vaciar_tags();
            }
          })

        }
      }

    });
  }

  function vaciar_tags() {
    $.ajax({
      // data:  {parametros:parametros},
      url: '../controlador/ACTIVOS_FIJOS/articulosC.php?vaciar=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        imprimir_tags_masivo();
      }

    });
  }

  function navegacion() {
    var fil1 = '<?php echo isset($_GET["fil1"]) ? $_GET["fil1"] : ""; ?>';
    var fil2 = '<?php echo isset($_GET["fil2"]) ? $_GET["fil2"] : ""; ?>';
    var id = '<?= $_id ?>';

    var parametros = {
      'loc': fil1,
      'cus': fil2,
      'id': id
    };

    $.ajax({
      data: {
        parametros: parametros
      },
      url: '../controlador/ACTIVOS_FIJOS/detalle_articuloC.php?navegacion=true',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        var botones = '';

        if (response.atras != 0) {
          botones = `
                    <a class="btn btn-default" href="../vista/detalle_articulo.php?id=${response.atras}&fil1=${fil1}&fil2=${fil2}">
                        <i class="fa fa-caret-left"></i> Atrás
                    </a>
                    <a class="btn btn-default" href="../vista/detalle_articulo.php?id=${response.siguiente}&fil1=${fil1}&fil2=${fil2}">
                        Siguiente <i class="fa fa-caret-right"></i>
                    </a>`;
        } else {
          botones = `
                    <a class="btn btn-default" href="../vista/detalle_articulo.php?id=${response.siguiente}&fil1=${fil1}&fil2=${fil2}">
                        Siguiente <i class="fa fa-caret-right"></i>
                    </a>`;
        }

        $('#na').html(botones);
      }
    });
  }
</script>

<div class="page-wrapper">
  <div class="page-content">

    <!--breadcrumb-->
    <div class="page-breadcrumb d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">Articulos</div>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Detalle de articulos</li>
          </ol>
        </nav>
      </div>
      <div class="ms-auto">
        <div class="btn-group">
          <button type="button" class="btn btn-primary btn-compact">Opciones</button>
          <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"> <span class="visually-hidden">Toggle Dropdown</span>
          </button>
          <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
            <button type="button" class="dropdown-item" id="btn_editar" onclick="editar_pnl()"><i class="bx bx-pencil"></i> Editar</button>
            <button type="button" class="dropdown-item" id="btn_vista" onclick="vista_pnl()" style="display: none;"><i class="bx bx-eye"></i> Vista</button>
          </div>
        </div>
      </div>

    </div>
    <!--end breadcrumb-->
    <div class="card">

      <div class="card-body">

        <div class="row row-cols-auto g-1">
          <div class="col">
            <a class="btn btn-outline-secondary btn-sm" href="inicio.php?mod=<?= $modulo_sistema ?>&acc=articulos_cr"><i class="bx bx-left-arrow-alt"></i> Regresar</a>
          </div>
          <div class="col">
            <button class="btn btn-outline-secondary btn-sm" type="button" id="imprimir_cedula"><i class="bx bx-file"></i> Cedula activo</button>
            <button class="btn btn-outline-secondary btn-sm" type="button" onclick="imprimir_tags_masivo()"><i class="bx bx-purchase-tag"></i> Reimprimir Tag RFID</button>
          </div>
        </div>

        <div class="row g-0 pt-4">

          <div class="col-md-3 border-end">

            <div class="image-zoom-section pe-3">
              <div class="border mb-3 p-3 img-container" data-slider-id="1">
                <div class="item">
                  <img src="../img/sin_imagen.jpg" class="img-fluid" id="img_articulo" alt="">
                </div>
              </div>

              <form enctype="multipart/form-data" id="form_img" method="post" style="display: none;">
                <div class="mb-2">
                  <label for="file_img" class="form-label">Selecciona una imagen</label>
                  <input type="file" class="form-control form-control-sm" name="file_img" id="file_img" accept="image/*">
                </div>

                <input type="hidden" name="txt_nom_img" id="txt_nom_img">
                <input type="hidden" name="txt_idA_img" id="txt_idA_img">

                <button type="button" class="btn btn-primary btn-sm w-100" id="subir_imagen">
                  Subir Imagen
                </button>
              </form>


            </div>
          </div>

          <div class="col-md-9">
            <input type="hidden" name="" id="txt_id">
            <input type="hidden" name="" id="txt_id_A">

            <!-- vista normal -->
            <div class="card-body" id="panel_vista">
              <h4 class="card-title fw-bold" id="lbl_descripcion"></h4>
              <div class="row">
                <dd class="col-sm-4" id="lbl_tipo">
                  <div class="text-default">ACTIVO PROPIO</div>
                </dd>
              </div>

              <div class="text-muted" id="lbl_descripcion2"></div>

              <div class="d-flex flex-wrap gap-3 py-2">
                <span class="badge bg-secondary" id="lbl_sub_num"></span>
                <span class="badge bg-primary" id="lbl_sku"></span>
                <span class="badge bg-warning" id="lbl_tag_ant"></span>
              </div>

              <dl class="row mb-1">
                <dt class="col-sm-auto">RFID: </dt>
                <dd class="col-sm-auto" id="lbl_rfid"></dd>
              </dl>

              <div class="row mb-3">
                <div class="col-sm-6">
                  <div class="bg-light p-2 rounded mb-2">
                    <span class="fw-bold">Valor Actual: </span>
                    <span class="text-success h4" id="lbl_precio">0</span>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="bg-light p-2 rounded mb-2">
                    <span class="fw-bold">Cantidad: </span>
                    <span class="h4" id="lbl_canti">0</span>
                    <!-- <span class="text-muted" id="lbl_unidad">/</span> -->
                  </div>
                </div>
              </div>

              <p class="text-muted mb-0" id="lbl_custodio1">.</p>
              <p class="text-muted mb-3" id="lbl_custodio">.</p>
              <p class="text-muted mb-0" id="lbl_localizacion1">.</p>
              <p class="text-muted mb-0" id="lbl_localizacion">.</p>


              <hr>

              <div class="row">
                <dt class="col-sm-2">Marca: &nbsp;</dt>
                <dd class="col-sm-8" id="lbl_marca"></dd>
              </div>
              <div class="row">
                <dt class="col-sm-2">Género: &nbsp;</dt>
                <dd class="col-sm-8" id="lbl_genero"></dd>
              </div>
              <div class="row">
                <dt class="col-sm-2">Color: &nbsp;</dt>
                <dd class="col-sm-8" id="lbl_color"></dd>
              </div>
              <div class="row">
                <dt class="col-sm-2">Estado: &nbsp;</dt>
                <dd class="col-sm-8" id="lbl_estado"></dd>
              </div>
              <div class="row">
                <dt class="col-sm-2">Proyecto: &nbsp;</dt>
                <dd class="col-sm-8" id="lbl_proyecto"></dd>
              </div>
              <div class="row">
                <dt class="col-sm-2">Modelo: &nbsp;</dt>
                <dd class="col-sm-8" id="lbl_modelo"></dd>
              </div>
              <div class="row">
                <dt class="col-sm-2">Serie: &nbsp;</dt>
                <dd class="col-sm-8" id="lbl_serie"></dd>
              </div>



              <div id="detalle_it" style="display:block">
                <hr>
                <h5 class="fw-bold">Detalles IT - Completar!</h5>
                <dl class="row">
                  <dt class="col-sm-3">Sistema Operativo</dt>
                  <dd class="col-sm-9" id="lbl_sistema_op"></dd>

                  <dt class="col-sm-3">Arquitectura</dt>
                  <dd class="col-sm-9" id="lbl_arquitectura"></dd>

                  <dt class="col-sm-3">Kernel</dt>
                  <dd class="col-sm-9" id="lbl_kernel"></dd>

                  <dt class="col-sm-3">Producto ID</dt>
                  <dd class="col-sm-9" id="lbl_producto_id"></dd>

                  <dt class="col-sm-3">Versión</dt>
                  <dd class="col-sm-9" id="lbl_version"></dd>

                  <dt class="col-sm-3">Service Pack</dt>
                  <dd class="col-sm-9" id="lbl_service_pack"></dd>

                  <dt class="col-sm-3">Edición</dt>
                  <dd class="col-sm-9" id="lbl_edicion"></dd>
                </dl>
              </div>

              <hr>
              <div class="row row-cols-auto align-items-center mt-3">
                <div class="col">
                  <label class="form-label"><b>Fecha de compra</b></label>
                  <div id="lbl_fecha_compra"></div>
                </div>
                <div class="col">
                  <label class="form-label"><b>Fecha de Referencia</b></label>
                  <div id="lbl_fecha_inve"></div>
                </div>
              </div>

              <hr>

              <p class="" id="lbl_caracteristicas">.</p>
              <p class="" id="lbl_observaciones">.</p>
            </div>


            <!-- vista para editar -->
            <div class="card-body" id="panel_editar" style="display:none">

              <div class="row">
                <ul class="nav nav-tabs nav-primary" role="tablist">
                  <!-- Detalle Activo -->
                  <li class="nav-item" role="presentation">
                    <a class="nav-link active" data-bs-toggle="tab" href="#tab_detalle_articulo" role="tab" aria-selected="true">
                      <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class="bx bx-package font-18 me-1"></i>
                        </div>
                        <div class="tab-title">Detalle Activo</div>
                      </div>
                    </a>
                  </li>

                  <!-- Kit interno -->
                  <li class="nav-item" role="presentation">
                    <a class="nav-link" data-bs-toggle="tab" href="#tab_detalle_kit_interno" role="tab" aria-selected="false" tabindex="-1">
                      <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class="bx bx-list-ul font-18 me-1"></i>
                        </div>
                        <div class="tab-title">Kit interno</div>
                      </div>
                    </a>
                  </li>

                  <!-- Detalle IT -->
                  <li class="nav-item" role="presentation">
                    <a class="nav-link" data-bs-toggle="tab" href="#tab_detalle_it" role="tab" aria-selected="false" tabindex="-1">
                      <div class="d-flex align-items-center">
                        <div class="tab-icon"><i class="bx bx-cog font-18 me-1"></i>
                        </div>
                        <div class="tab-title">Detalle IT</div>
                      </div>
                    </a>
                  </li>

                </ul>

                <!-- Contenedor de TAB -->
                <div class="tab-content py-3">

                  <!-- TAB Detalle Activo -->
                  <div class="tab-pane fade show active" id="tab_detalle_articulo" role="tabpanel">
                    <form id="form_articulo">
                      <div class="row">
                        <div class="col-auto">
                          <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="cbx_kit" id="cbx_kit">
                            <label class="form-label" for="cbx_kit">KIT </label>
                          </div>
                          <label class="error" style="display: none;" for="cbx_kit"></label>
                        </div>
                      </div>

                      <hr class="text-primary mb-2 mt-1">

                      <!-- Hacerlo dinámico -->
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
                        <div class="col-sm-6">
                          <div class="d-flex justify-content-between align-items-center">
                            <label for="ddl_custodio" class="form-label">Custodio </label>
                            <small id="lbl_sap_custodio" class="text-muted"><u>Código:</u></small>
                          </div>

                          <select class="form-control form-control-sm select2-validation" name="ddl_custodio" id="ddl_custodio">
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
                        </div>

                        <div class="col-sm-6">
                          <label for="txt_tag_serie" class="form-label">Referencia de almacén (SKU) </label>
                          <input type="text" class="form-control form-control-sm solo_numeros_int" name="txt_tag_serie" id="txt_tag_serie" maxlength="15">
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

                        <div class="col-sm-6">
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

                      <div class="d-flex justify-content-end pt-2">
                        <button class="btn btn-success btn-sm px-4 m-0" onclick="guardar_articulo();" type="button"><i class="bx bx-save"></i> Guardar</button>
                      </div>

                    </form>
                  </div>

                  <!-- TAB Kit interno -->
                  <?php include('../vista/ACTIVOS_FIJOS/RUBROS_COMERCIALES/kit.php'); ?>

                  <!-- Detalle IT -->
                  <?php include('../vista/ACTIVOS_FIJOS/RUBROS_COMERCIALES/it.php'); ?>

                </div>
              </div>

            </div>
            <!--end row-->
          </div>

        </div>
      </div>

      <?php include('../vista/ACTIVOS_FIJOS/RUBROS_COMERCIALES/articulo_informacion_adicional.php'); ?>

    </div>

  </div>
</div>


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