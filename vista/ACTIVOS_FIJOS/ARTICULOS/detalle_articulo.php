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

    //$('#cbx_detalle_it').prop('disabled', true);

    $('#cbx_detalle_it').change(function() {
      if ($(this).is(':checked')) {
        $('#nav_detalle_it').show(); // Mostrar el div si está checkeado
        $('#is_it_estado').show(); // Mostrar el div si está checkeado

      } else {
        $('#nav_detalle_it').hide(); // Ocultar el div si está desmarcado
        $('#is_it_estado').hide(); // Mostrar el div si está checkeado

      }

    });
    $('#cbx_kit').change(function() {
      if ($(this).is(':checked')) {
        $('#nav_kit_interno').show(); // Mostrar el div si está checkeado
      } else {
        $('#nav_kit_interno').hide(); // Ocultar el div si está desmarcado
      }
    });
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
      $('#lbl_sap_loc').text('Código:' + data.DENOMINACION)
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

  function calcula_depreciacion() {
    // Obtener el texto dentro de los elementos y convertirlo
    let text_valor_activo = parseFloat($('#lbl_valor_activo').text()) || 0;
    let text_valor_residual = parseFloat($('#lbl_valor_residual').text()) || 0;
    let text_vida_utill = parseInt($('#lbl_vida_util').text()) || 0;


    // Ejemplo: calcular depreciación lineal anual
    if (text_vida_utill > 0) {
      let depreciacion_anual = (text_valor_activo - text_valor_residual) / text_vida_utill;
      $('#lbl_total_depreciacion').text(depreciacion_anual.toFixed(2));
    } else {
      $('#lbl_total_depreciacion').text("La vida útil debe ser mayor que cero.")
    }
  }

  function depreciacion_activo() {
    calcula_depreciacion();

    const $seccion = $('#seccion_depreciacion');
    if ($seccion.length) {
      $('html, body').animate({
        scrollTop: $seccion.offset().top - 80
      }, 600); // 600 ms de animación (puedes ajustar)
    }
  }


  function abrir_modal_depreciacion() {
    let text_valor_activo = parseFloat($('#lbl_valor_activo').text()) || 0;
    let text_valor_residual = parseFloat($('#lbl_valor_residual').text()) || 0;
    let text_vida_utill = parseInt($('#lbl_vida_util').text()) || 0;
    let id_articulo = $('#id_articulo').val(); // este debe estar bien cargado antes

    $('#edit_valor_activo').val(text_valor_activo);
    $('#edit_valor_residual').val(text_valor_residual);
    $('#edit_vida_util').val(text_vida_utill);
    $('#edit_id_articulo').val(id_articulo); // este es el que va al modal

    $('#modalDepreciacion').modal('show');
  }


  function guardar_depreciacion() {

    const form = document.getElementById('form_depreciacion');
    const formData = new FormData(form);

    $.ajax({
      url: '../controlador/ACTIVOS_FIJOS/detalle_articuloC.php?actualizarDatosArticuloDepreciacion=true',
      type: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      dataType: 'json',
      success: function(response) {
        if (response == 1) {
          Swal.fire('', 'Operacion realizada con exito.', 'success').then(function() {
            location.reload();
          });
        } else {
          Swal.fire('Error', 'No se pudo guardar.', 'error');
        }
      },
      error: function() {
        Swal.fire('Error', 'Fallo en la comunicación con el servidor.', 'error');
      }
    });
  }

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
        calcula_depreciacion();

        datos_col_custodio(data);
      },

      error: function(xhr, status, error) {
        console.error("Error en la petición AJAX: ", status, error);
      }
    });
  }

  function cargar_articulo_vista_pnl(data) {
    $('#lbl_descripcion').text(data.nom);
    $('#id_articulo').val(data.id_A);
    $('#txt_id_articulo').val(data.id_A);
    $('#lbl_descripcion2').text(data.des ?? '');
    $('#lbl_localizacion1').html(`<b>Emplazamiento / Localización</b> | <label style="font-size:65%"> Código: ${data.loc_nom}</label>`);
    $('#lbl_localizacion').text(data.c_loc);

    $('#lbl_custodio1').html(`<b>Custodio:</b> | <label style="font-size:65%"> Código: ${data.person_ci}</label>`);
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

    $('#lbl_valor_activo').text(data.prec);
    $('#lbl_valor_residual').text(data.text_valor_residual);
    $('#lbl_vida_util').text((data.text_vida_utill || 0) + " años");



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

            <?php
            $nombre_imprimir_cedula = "Cédula activo";
            if ($_SESSION['INICIO']['MODULO_SISTEMA'] == 2018) {
              $nombre_imprimir_cedula = "Datasheet";
            }
            ?>

            <button class="btn btn-outline-secondary btn-sm" type="button" id="imprimir_cedula"><i class="bx bx-file"></i> <?= $nombre_imprimir_cedula ?></button>

            <button disabled class="btn btn-outline-secondary btn-sm" type="button" onclick="imprimir_tags_masivo()"><i class="bx bx-purchase-tag"></i> Reimprimir Tag RFID</button>

            <?php if ($_SESSION['INICIO']['MODULO_SISTEMA'] != 2018) { ?>
              <button class="btn btn-outline-secondary btn-sm" type="button" onclick="depreciacion_activo()">
                <i class="bx bx-trending-down"></i> Depreciación
              </button>
            <?php } ?>

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
                  <div class="text-default">ARTICULO</div>
                </dd>
              </div>

              <div class="text-muted" id="lbl_descripcion2"></div>
              <input type="hidden" name="id_articulo" id="id_articulo" value="">

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

              <?php if ($_SESSION['INICIO']['MODULO_SISTEMA'] != 2018) { ?>
                <p class="text-muted mb-0" id="lbl_custodio1">.</p>
                <p class="text-muted mb-3" id="lbl_custodio">.</p>
              <?php } ?>

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
              <div class="row" hidden>
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

              <div id="is_it_estado" style="display: none;">

                <?php include('../vista/ACTIVOS_FIJOS/ARTICULOS/RUBROS_COMERCIALES/it_vista.php'); ?>

                <hr>
              </div>
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

              <p class="" id="lbl_caracteristicas"></p>
              <p class="" id="lbl_observaciones"></p>

              <hr>

              <?php if ($_SESSION['INICIO']['MODULO_SISTEMA'] != 2018) { ?>
                <div id="seccion_depreciacion">
                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="fw-bold mb-0">Depreciación</h5>
                    <button class="btn btn-sm btn-outline-primary" onclick="abrir_modal_depreciacion()" title="Editar datos">
                      <i class="bx bx-pencil"></i>
                    </button>
                  </div>
                  <div class="row">
                    <dt class="col-sm-3">Valor activo: &nbsp;</dt>
                    <dd class="col-sm-8" id="lbl_valor_activo">0</dd>
                  </div>
                  <div class="row">
                    <dt class="col-sm-3">Valor residual: &nbsp;</dt>
                    <dd class="col-sm-8" id="lbl_valor_residual">0</dd>
                  </div>
                  <div class="row">
                    <dt class="col-sm-3">Vida útil: &nbsp;</dt>
                    <dd class="col-sm-8" id="lbl_vida_util">0</dd>
                  </div>
                  <div class="row">
                    <dt class="col-sm-3">Total depreciación: &nbsp;</dt>
                    <dd class="col-sm-8" id="lbl_total_depreciacion">0</dd>
                  </div>
                </div>
              <?php } ?>

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
                        <div class="tab-title">Detalle</div>
                      </div>
                    </a>
                  </li>

                  <!-- Kit interno -->
                  <div id="nav_kit_interno" style="display: none;">
                    <li class="nav-item" role="presentation">
                      <a class="nav-link" data-bs-toggle="tab" href="#tab_detalle_kit_interno" role="tab" aria-selected="false" tabindex="-1">
                        <div class="d-flex align-items-center">
                          <div class="tab-icon"><i class="bx bx-list-ul font-18 me-1"></i>
                          </div>
                          <div class="tab-title">Kit interno</div>
                        </div>
                      </a>
                    </li>
                  </div>

                  <!-- Detalle IT -->
                  <div id="nav_detalle_it" style="display: none;">
                    <li class="nav-item" role="presentation">
                      <a class="nav-link" data-bs-toggle="tab" href="#tab_detalle_it" role="tab" aria-selected="false" tabindex="-1">
                        <div class="d-flex align-items-center">
                          <div class="tab-icon"><i class="bx bx-cog font-18 me-1"></i>
                          </div>
                          <div class="tab-title">Detalle IT</div>
                        </div>
                      </a>
                    </li>
                  </div>

                </ul>

                <!-- Contenedor de TAB -->
                <div class="tab-content py-3">

                  <!-- TAB Detalle Activo-->
                  <div class="tab-pane fade show active" id="tab_detalle_articulo" role="tabpanel">
                    <?php include('../vista/ACTIVOS_FIJOS/ARTICULOS/ac_articulos_pnl.php'); ?>
                  </div>

                  <!-- TAB Kit interno -->
                  <?php include('../vista/ACTIVOS_FIJOS/ARTICULOS/RUBROS_COMERCIALES/kit.php'); ?>

                  <!-- Detalle IT -->
                  <?php include('../vista/ACTIVOS_FIJOS/ARTICULOS/RUBROS_COMERCIALES/it_pnl.php'); ?>

                </div>
              </div>

            </div>
            <!--end row-->
          </div>

        </div>
      </div>

      <?php include('../vista/ACTIVOS_FIJOS/ARTICULOS/RUBROS_COMERCIALES/articulo_informacion_adicional.php'); ?>

    </div>

  </div>
</div>




<div class="modal fade" id="modalDepreciacion" tabindex="-1" aria-labelledby="modalDepreciacionLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <form id="form_depreciacion">
        <div class="modal-header">
          <h5 class="modal-title" id="modalDepreciacionLabel">Editar Depreciación</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
          <!-- ID oculto -->
          <input type="hidden" name="id_articulo_update" id="edit_id_articulo" value="">

          <div class="mb-3">
            <label for="edit_valor_activo" class="form-label">Valor activo</label>
            <input type="number" name="text_valor_activo" id="edit_valor_activo" step="0.01" class="form-control" readonly>
          </div>

          <div class="mb-3">
            <label for="edit_valor_residual" class="form-label">Valor residual</label>
            <input type="number" name="text_valor_residual" id="edit_valor_residual" step="0.01" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="edit_vida_util" class="form-label">Vida útil (años)</label>
            <input type="number" name="text_vida_utill" id="edit_vida_util" step="1" class="form-control" required>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" onclick="guardar_depreciacion()">Guardar</button>
        </div>
      </form>

    </div>
  </div>
</div>